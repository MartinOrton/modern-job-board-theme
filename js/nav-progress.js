/**
 * Navigation progress: when a visitor follows an internal link or sends a
 * form that loads a new page, show a thin progress bar at the top of the
 * window and a small spinner on the link or button they used. The next page
 * finishes the bar, so the wait reads as one movement.
 *
 * Clicks and submits that other scripts handle themselves (they call
 * preventDefault: AJAX job search, dashboard tabs, the contact form, …) are
 * left alone. So are new tabs, downloads, same-page anchors, and clicks with
 * a modifier key. Add data-no-progress to a link or form to opt it out.
 */
( function () {
	'use strict';

	var KEY = 'mjbNavProgress';
	var reduced = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	var bar = null;
	var fill = null;
	var value = 0;
	var timer = null;
	var safety = null;
	var spinTimer = null;
	var pending = null;

	function store( set ) {
		try {
			if ( set ) {
				window.sessionStorage.setItem( KEY, String( Date.now() ) );
			} else {
				window.sessionStorage.removeItem( KEY );
			}
		} catch ( e ) {}
	}

	function recent() {
		try {
			var t = parseInt( window.sessionStorage.getItem( KEY ) || '0', 10 );
			return t && Date.now() - t < 15000;
		} catch ( e ) {
			return false;
		}
	}

	function ensureBar() {
		if ( bar ) {
			return;
		}

		bar = document.createElement( 'div' );
		bar.className = 'mjb-nav-progress';
		bar.setAttribute( 'aria-hidden', 'true' );
		fill = document.createElement( 'span' );
		fill.className = 'mjb-nav-progress__fill';
		bar.appendChild( fill );
		document.body.appendChild( bar );
	}

	function set( v ) {
		value = Math.max( 0, Math.min( 1, v ) );
		fill.style.transform = 'scaleX(' + value + ')';
	}

	function trickle() {
		// Slow down as it nears 90%: it never "finishes" on this page.
		set( value + ( 0.9 - value ) * ( reduced ? 0.5 : 0.08 ) );
	}

	function clearSpinner() {
		clearTimeout( spinTimer );

		if ( pending ) {
			pending.classList.remove( 'is-nav-pending' );
			pending.removeAttribute( 'aria-busy' );

			var spin = pending.querySelector( '.mjb-nav-spin' );

			if ( spin ) {
				spin.parentNode.removeChild( spin );
			}

			pending = null;
		}
	}

	function stop() {
		clearInterval( timer );
		clearTimeout( safety );
		clearSpinner();
		store( false );

		if ( bar ) {
			bar.classList.remove( 'is-active' );
			set( 0 );
		}
	}

	function start( element ) {
		ensureBar();
		clearInterval( timer );
		clearTimeout( safety );
		bar.classList.add( 'is-active' );
		set( 0.12 );
		timer = setInterval( trickle, 250 );
		store( true );

		// Nothing replaced the page (a download, a 204, the visitor stopped it): give up quietly.
		safety = setTimeout( stop, 12000 );

		clearSpinner();

		// A spinner on small targets only (menu links, buttons), and only if the wait is noticeable.
		if ( element && element.getBoundingClientRect().height <= 72 && ( element.textContent || '' ).trim() ) {
			pending = element;
			spinTimer = setTimeout( function () {
				if ( pending !== element ) {
					return;
				}

				var spin = document.createElement( 'span' );
				spin.className = 'mjb-nav-spin';
				spin.setAttribute( 'aria-hidden', 'true' );
				element.appendChild( spin );
				element.classList.add( 'is-nav-pending' );
				element.setAttribute( 'aria-busy', 'true' );
			}, 150 );
		}
	}

	// The previous page started the bar: finish it here.
	function finish() {
		if ( ! recent() ) {
			return;
		}

		store( false );
		ensureBar();
		bar.classList.add( 'is-active', 'is-finishing' );
		set( 0.85 );

		window.requestAnimationFrame( function () {
			set( 1 );
			setTimeout( function () {
				bar.classList.remove( 'is-active' );
				setTimeout( function () {
					bar.classList.remove( 'is-finishing' );
					set( 0 );
				}, 350 );
			}, reduced ? 0 : 250 );
		} );
	}

	function loadsNewPage( url ) {
		if ( url.origin !== window.location.origin || ! /^https?:$/.test( url.protocol ) ) {
			return false;
		}

		// The same page (an anchor, "#", or the page itself).
		if ( url.pathname === window.location.pathname && url.search === window.location.search ) {
			return false;
		}

		// Files and protected downloads.
		if ( /\.(pdf|zip|docx?|xlsx?|csv|txt|jpe?g|png|gif|webp|svg|mp4|xml)$/i.test( url.pathname ) || /[?&]mjb_download=/.test( url.search ) ) {
			return false;
		}

		return true;
	}

	document.addEventListener( 'click', function ( event ) {
		if ( event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey ) {
			return;
		}

		var link = event.target.closest ? event.target.closest( 'a[href]' ) : null;

		if ( ! link || link.hasAttribute( 'download' ) || link.closest( '[data-no-progress]' ) ) {
			return;
		}

		var target = ( link.getAttribute( 'target' ) || '' ).toLowerCase();

		if ( target && target !== '_self' ) {
			return;
		}

		var url;

		try {
			url = new URL( link.href, window.location.href );
		} catch ( e ) {
			return;
		}

		if ( ! loadsNewPage( url ) ) {
			return;
		}

		// Decide after every other click handler has run.
		setTimeout( function () {
			if ( ! event.defaultPrevented ) {
				start( link );
			}
		}, 0 );
	} );

	document.addEventListener( 'submit', function ( event ) {
		var form = event.target;

		if ( ! form || form.closest( '[data-no-progress]' ) ) {
			return;
		}

		var target = ( form.getAttribute( 'target' ) || '' ).toLowerCase();

		if ( target && target !== '_self' ) {
			return;
		}

		var button = event.submitter || null;

		setTimeout( function () {
			if ( ! event.defaultPrevented ) {
				start( button );
			}
		}, 0 );
	} );

	// Back/forward cache: the page is shown again as it was left.
	window.addEventListener( 'pageshow', function ( event ) {
		if ( event.persisted ) {
			stop();
		}
	} );

	// A page prerendered by the browser (WordPress speculative loading) ran
	// this script before the click: finish the bar when it is shown instead.
	if ( document.prerendering ) {
		document.addEventListener( 'prerenderingchange', finish, { once: true } );
	} else if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', finish );
	} else {
		finish();
	}
}() );
