/**
 * Code blocks in the docs and on feature pages: the home page's code window
 * (slate panel, three-dot bar), the home page's colour coding, and a copy
 * icon with the plugin's pill tooltip.
 */
( function () {
	'use strict';

	var settings = window.mjbCode || {};

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
			[ /\b(?:curl|sudo|nginx|systemctl|wp|php|git|claude|gemini|npx|printf|base64)\b/, 'func' ],
			[ /(?<=\s)--?[A-Za-z][\w-]*/, 'key' ]
		],
		// A request line or headers, and often a JSON body below them.
		http: [
			[ /^(?:GET|POST|PUT|PATCH|DELETE|HEAD|OPTIONS)\b/m, 'keyword' ],
			[ /^[A-Za-z][\w-]*(?=:\s)/m, 'key' ],
			[ /"(?:\\.|[^"\\\n])*"(?=\s*:)/, 'key' ],
			[ STRING, 'string' ],
			[ /\b(?:true|false|null)\b/, 'keyword' ],
			[ /(?<![\w\/.-])\d+(?:\.\d+)?\b/, 'number' ]
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
		if ( /^(?:curl|sudo|wp|git|claude|gemini|npx|printf)\b/m.test( text ) ) {
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

	document.querySelectorAll( '.mjb-kb-article pre.wp-block-code, .mjb-feature-body pre.wp-block-code' ).forEach( function ( pre ) {
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
}() );
