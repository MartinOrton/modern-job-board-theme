/**
 * Knowledge base: search suggestions, the topic menu
 * on phones, "On this page" highlighting, and links to sections of the old
 * single-page documentation (/docs/#webhooks) sent to their new article.
 */
( function () {
	'use strict';

	var settings = window.mjbKb || {};

	// Old anchors on the Documentation page → the article that now covers them.
	var legacy = settings.legacy || {};

	function followLegacyAnchor() {
		var hash = decodeURIComponent( window.location.hash.replace( /^#/, '' ) );

		if ( hash && legacy[ hash ] && ! document.getElementById( hash ) ) {
			window.location.replace( legacy[ hash ] );
			return true;
		}

		return false;
	}

	if ( followLegacyAnchor() ) {
		return;
	}

	window.addEventListener( 'hashchange', followLegacyAnchor );

	/* ---------- Search suggestions ---------- */
	document.querySelectorAll( '[data-kb-search]' ).forEach( function ( form ) {
		var input = form.querySelector( 'input[type="search"]' );
		var list = form.querySelector( '.mjb-kb-search__list' );
		var timer = null;
		var active = -1;
		var lastQuery = '';
		var controller = null;

		if ( ! input || ! list || ! settings.search || ! window.fetch ) {
			return;
		}

		function options() {
			return Array.prototype.slice.call( list.querySelectorAll( '[role="option"]' ) );
		}

		function close() {
			list.hidden = true;
			list.innerHTML = '';
			input.setAttribute( 'aria-expanded', 'false' );
			input.removeAttribute( 'aria-activedescendant' );
			active = -1;
		}

		function highlight( index ) {
			var items = options();

			items.forEach( function ( item, i ) {
				item.classList.toggle( 'is-active', i === index );
				item.setAttribute( 'aria-selected', i === index ? 'true' : 'false' );
			} );

			active = index;

			if ( items[ index ] ) {
				input.setAttribute( 'aria-activedescendant', items[ index ].id );
			} else {
				input.removeAttribute( 'aria-activedescendant' );
			}
		}

		function render( results, query ) {
			list.innerHTML = '';

			results.forEach( function ( result, i ) {
				var li = document.createElement( 'li' );
				var a = document.createElement( 'a' );

				li.id = input.id + '-opt-' + i;
				li.setAttribute( 'role', 'option' );
				li.setAttribute( 'aria-selected', 'false' );
				a.href = result.url;
				a.tabIndex = -1;
				a.innerHTML = result.title;
				li.appendChild( a );
				list.appendChild( li );
			} );

			var all = document.createElement( 'li' );
			all.id = input.id + '-opt-all';
			all.className = 'mjb-kb-search__all';
			all.setAttribute( 'role', 'option' );
			all.setAttribute( 'aria-selected', 'false' );
			all.textContent = results.length ? ( settings.showAll || 'See all results' ) : ( settings.none || 'No matching articles.' );
			all.addEventListener( 'mousedown', function ( event ) {
				event.preventDefault();
				form.submit();
			} );
			list.appendChild( all );

			list.hidden = false;
			input.setAttribute( 'aria-expanded', 'true' );
			active = -1;
			lastQuery = query;
		}

		function suggest() {
			var query = input.value.trim();

			if ( query.length < 2 ) {
				close();
				return;
			}

			if ( query === lastQuery && ! list.hidden ) {
				return;
			}

			if ( controller ) {
				controller.abort();
			}

			controller = window.AbortController ? new AbortController() : null;

			var url = settings.search + ( settings.search.indexOf( '?' ) === -1 ? '?' : '&' ) +
				'type=post&subtype=mjb_kb&per_page=6&_fields=id,title,url&search=' + encodeURIComponent( query );

			fetch( url, controller ? { signal: controller.signal } : {} )
				.then( function ( response ) {
					return response.ok ? response.json() : [];
				} )
				.then( function ( results ) {
					if ( input.value.trim() === query ) {
						render( Array.isArray( results ) ? results : [], query );
					}
				} )
				.catch( function () {} );
		}

		input.addEventListener( 'input', function () {
			clearTimeout( timer );
			timer = setTimeout( suggest, 180 );
		} );

		input.addEventListener( 'keydown', function ( event ) {
			var items = options();

			if ( 'ArrowDown' === event.key && items.length ) {
				event.preventDefault();
				highlight( active < items.length - 1 ? active + 1 : 0 );
			} else if ( 'ArrowUp' === event.key && items.length ) {
				event.preventDefault();
				highlight( active > 0 ? active - 1 : items.length - 1 );
			} else if ( 'Enter' === event.key && active > -1 && items[ active ] ) {
				var link = items[ active ].querySelector( 'a' );

				if ( link ) {
					event.preventDefault();
					window.location.href = link.href;
				}
			} else if ( 'Escape' === event.key ) {
				close();
			}
		} );

		input.addEventListener( 'blur', function () {
			setTimeout( close, 150 );
		} );

		input.addEventListener( 'focus', function () {
			if ( input.value.trim().length >= 2 ) {
				lastQuery = '';
				suggest();
			}
		} );
	} );

	/* ---------- Topic menu: keep the current article in view on desktop ---------- */
	var navCurrent = document.querySelector( '.mjb-kb__nav a[aria-current="page"]' );
	var navBox = document.querySelector( '.mjb-kb__nav' );

	if ( navCurrent && navBox && navBox.scrollHeight > navBox.clientHeight + 4 ) {
		navBox.scrollTop += navCurrent.getBoundingClientRect().top - navBox.getBoundingClientRect().top - navBox.clientHeight / 3;
	}

	/* ---------- Topic menu on phones ---------- */
	document.querySelectorAll( '.mjb-kb-nav__toggle' ).forEach( function ( toggle ) {
		var nav = toggle.closest( '.mjb-kb-nav' );

		toggle.addEventListener( 'click', function () {
			var open = 'true' !== toggle.getAttribute( 'aria-expanded' );

			toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			nav.classList.toggle( 'is-open', open );
		} );
	} );

	/* ---------- "On this page": highlight the section in view ---------- */
	var toc = document.querySelector( '.mjb-kb-toc' );

	if ( toc && window.IntersectionObserver ) {
		var links = {};
		var visible = {};

		toc.querySelectorAll( 'a[href^="#"]' ).forEach( function ( link ) {
			links[ decodeURIComponent( link.getAttribute( 'href' ).slice( 1 ) ) ] = link;
		} );

		var headings = Object.keys( links ).map( function ( id ) {
			return document.getElementById( id );
		} ).filter( Boolean );

		var update = function () {
			var current = null;

			headings.forEach( function ( heading ) {
				if ( heading.getBoundingClientRect().top < window.innerHeight * 0.35 ) {
					current = heading.id;
				}
			} );

			if ( ! current && headings[ 0 ] ) {
				current = headings[ 0 ].id;
			}

			Object.keys( links ).forEach( function ( id ) {
				links[ id ].classList.toggle( 'is-active', id === current );
			} );
		};

		var observer = new IntersectionObserver( function ( entries ) {
			entries.forEach( function ( entry ) {
				visible[ entry.target.id ] = entry.isIntersecting;
			} );
			update();
		}, { rootMargin: '0px 0px -60% 0px' } );

		headings.forEach( function ( heading ) {
			observer.observe( heading );
		} );

		window.addEventListener( 'scroll', update, { passive: true } );
		update();
	}
}() );
