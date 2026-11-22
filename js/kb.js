/**
 * Knowledge base: search suggestions, copy buttons on code, the topic menu
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

	/* ---------- Code blocks: the home page's code window ---------- */

	// Colour coding with the home page's palette (.code-window in styles.css):
	// comments, keys, strings, numbers and keywords, function names. Each
	// block's language is worked out from its content (the blocks carry none).
	var COMMENT_SLASH = /\/\/[^\n]*|\/\*[\s\S]*?\*\//;
	var COMMENT_HASH = /(?:^|(?<=\s))#[^\n]*/;
	var STRING = /"(?:\\.|[^"\\\n])*"|'(?:\\.|[^'\\])*'/;
	var NUMBER = /\b\d+(?:\.\d+)?\b/;

	var LANGUAGES = {
		json: [
			[ /"(?:\\.|[^"\\\n])*"(?=\s*:)/, 'key' ],
			[ STRING, 'string' ],
			[ /\b(?:true|false|null)\b/, 'keyword' ],
			[ NUMBER, 'number' ]
		],
		php: [
			[ COMMENT_SLASH, 'comment' ],
			[ COMMENT_HASH, 'comment' ],
			[ STRING, 'string' ],
			[ /\b(?:function|fn|return|array|true|false|null|new|if|else|elseif|foreach|for|while|as|echo|static|use|isset|empty|exit|die|global|throw|try|catch|class|public|private|protected|const)\b/, 'keyword' ],
			[ /\b[A-Za-z_]\w*(?=\s*\()/, 'func' ],
			[ NUMBER, 'number' ]
		],
		js: [
			[ COMMENT_SLASH, 'comment' ],
			[ /`(?:\\.|[^`\\])*`/, 'string' ],
			[ STRING, 'string' ],
			[ /\b(?:const|let|var|function|return|new|if|else|true|false|null|await|async|of|for)\b/, 'keyword' ],
			[ /\b[A-Za-z_$][\w$]*(?=\s*\()/, 'func' ],
			[ NUMBER, 'number' ]
		],
		shell: [
			[ COMMENT_HASH, 'comment' ],
			[ STRING, 'string' ],
			[ /\b(?:curl|sudo|nginx|systemctl|wp|php|git)\b/, 'func' ],
			[ /(?<=\s)--?[A-Za-z][\w-]*/, 'key' ]
		],
		http: [
			[ /^(?:GET|POST|PUT|PATCH|DELETE|HEAD|OPTIONS)\b/m, 'keyword' ],
			[ /^[A-Za-z][\w-]*(?=:\s)/m, 'key' ]
		],
		nginx: [
			[ COMMENT_HASH, 'comment' ],
			[ STRING, 'string' ],
			[ /\b(?:location|deny|allow|return|server|root|try_files|add_header|internal|rewrite|if)\b/, 'keyword' ],
			[ NUMBER, 'number' ]
		]
	};

	function detectLanguage( code ) {
		var text = code.trim();

		if ( /^[\[{]/.test( text ) && /"\s*:/.test( text ) ) {
			return 'json';
		}
		if ( /^(?:curl|sudo|wp|git)\b/m.test( text ) ) {
			return 'shell';
		}
		if ( /<\?php|\badd_(?:filter|action)\s*\(|\bdefine\s*\(|^\s*\$\w+\s*=/m.test( text ) ) {
			return 'php';
		}
		if ( /\blocation\b[^\n]*\{/.test( text ) ) {
			return 'nginx';
		}
		if ( /\bfetch\s*\(|=>|\b(?:const|let)\s/.test( text ) ) {
			return 'js';
		}
		if ( /^(?:GET|POST|PUT|PATCH|DELETE)\s+\//m.test( text ) || /^[A-Za-z][\w-]*:\s/m.test( text ) ) {
			return 'http';
		}

		return '';
	}

	function escapeHtml( text ) {
		return text.replace( /&/g, '&amp;' ).replace( /</g, '&lt;' ).replace( />/g, '&gt;' );
	}

	function highlight( code, rules ) {
		var pattern = new RegExp(
			rules.map( function ( rule ) {
				return '(' + rule[ 0 ].source + ')';
			} ).join( '|' ),
			'gm'
		);
		var html = '';
		var last = 0;
		var match;

		while ( ( match = pattern.exec( code ) ) !== null ) {
			if ( '' === match[ 0 ] ) {
				pattern.lastIndex++;
				continue;
			}

			for ( var i = 1; i < match.length; i++ ) {
				if ( undefined !== match[ i ] ) {
					html += escapeHtml( code.slice( last, match.index ) ) +
						'<span class="mjb-tok mjb-tok--' + rules[ i - 1 ][ 1 ] + '">' + escapeHtml( match[ 0 ] ) + '</span>';
					last = match.index + match[ 0 ].length;
					break;
				}
			}
		}

		return html + escapeHtml( code.slice( last ) );
	}

	var COPY_ICON = '<svg class="mjb-code-copy__icon mjb-code-copy__icon--copy" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>' +
		'<svg class="mjb-code-copy__icon mjb-code-copy__icon--done" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M20 6 9 17l-5-5"/></svg>';

	document.querySelectorAll( '.mjb-kb-article pre.wp-block-code' ).forEach( function ( pre ) {
		var code = pre.querySelector( 'code' ) || pre;
		var source = code.textContent;
		var language = detectLanguage( source );
		var win = document.createElement( 'div' );
		var bar = document.createElement( 'div' );

		if ( language ) {
			code.innerHTML = highlight( source, LANGUAGES[ language ] );
			pre.setAttribute( 'data-language', language );
		}

		// Window chrome, as on the home page: the three dots, and the copy icon.
		win.className = 'mjb-code-window';
		bar.className = 'mjb-code-window__bar';
		bar.innerHTML = '<span class="mjb-code-window__dots" aria-hidden="true"><span></span><span></span><span></span></span>';
		pre.parentNode.insertBefore( win, pre );
		win.appendChild( bar );
		win.appendChild( pre );

		if ( ! navigator.clipboard ) {
			return;
		}

		var wrap = document.createElement( 'span' );
		var button = document.createElement( 'button' );
		var tip = document.createElement( 'span' );
		var live = document.createElement( 'span' );
		var timer;

		wrap.className = 'mjb-code-copy';
		button.type = 'button';
		button.className = 'mjb-code-copy__btn';
		button.setAttribute( 'aria-label', settings.copyCode || 'Copy code' );
		button.innerHTML = COPY_ICON;
		tip.className = 'mjb-code-copy__tooltip';
		tip.setAttribute( 'role', 'tooltip' );
		tip.setAttribute( 'aria-hidden', 'true' );
		tip.textContent = settings.copy || 'Copy';
		live.className = 'screen-reader-text';
		live.setAttribute( 'aria-live', 'polite' );

		button.addEventListener( 'click', function () {
			navigator.clipboard.writeText( source.replace( /\n$/, '' ) ).then( function () {
				wrap.classList.add( 'is-done' );
				tip.textContent = settings.copied || 'Copied';
				live.textContent = settings.copied || 'Copied';
				window.clearTimeout( timer );
				timer = window.setTimeout( function () {
					wrap.classList.remove( 'is-done' );
					tip.textContent = settings.copy || 'Copy';
					live.textContent = '';
				}, 1800 );
			} );
		} );

		wrap.appendChild( button );
		wrap.appendChild( tip );
		wrap.appendChild( live );
		bar.appendChild( wrap );
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
