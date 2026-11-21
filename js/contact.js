/**
 * Contact form (mjb/contact-form): checks the fields in the browser, sends the
 * form with fetch and shows the result inline. Without JavaScript the form
 * posts normally and the page shows the result after the redirect.
 */
( function () {
	'use strict';

	var form = document.querySelector( '.mjb-contact-form' );

	if ( ! form || ! window.fetch || ! window.FormData ) {
		return;
	}

	/**
	 * Dropdown for a native <select>, the same as the plugin's (enhanceSelect in
	 * modern-job-board/assets/js/mjb-admin-job-editor.js): a trigger button and a
	 * listbox of options. The <select> stays in the form and keeps the value;
	 * picking an option sets it and fires "change".
	 *
	 * @param {HTMLSelectElement} select
	 * @return {HTMLButtonElement} The trigger.
	 */
	function enhanceSelect( select ) {
		var wrap = document.createElement( 'div' );
		var button = document.createElement( 'button' );
		var menu = document.createElement( 'div' );
		var chevron = '<svg class="mjb-dd__chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m6 9 6 6 6-6"/></svg>';

		wrap.className = 'mjb-dd';
		select.parentNode.insertBefore( wrap, select );
		wrap.appendChild( select );
		select.classList.add( 'mjb-dd__native' );
		select.tabIndex = -1;
		select.setAttribute( 'aria-hidden', 'true' );

		button.type = 'button';
		button.className = 'mjb-dd__trigger';
		button.id = select.id + '-dd';
		button.setAttribute( 'aria-haspopup', 'listbox' );
		button.setAttribute( 'aria-expanded', 'false' );

		menu.className = 'mjb-dd__menu';
		menu.id = button.id + '-menu';
		menu.setAttribute( 'role', 'listbox' );
		menu.hidden = true;
		button.setAttribute( 'aria-controls', menu.id );

		// The visible label now names the button; errors still describe it.
		document.querySelectorAll( 'label[for="' + select.id + '"]' ).forEach( function ( label ) {
			label.htmlFor = button.id;
			menu.setAttribute( 'aria-labelledby', label.id || ( label.id = select.id + '-label' ) );
		} );

		if ( select.getAttribute( 'aria-describedby' ) ) {
			button.setAttribute( 'aria-describedby', select.getAttribute( 'aria-describedby' ) );
		}

		function paint() {
			var option = select.options[ select.selectedIndex ];
			var text = document.createElement( 'span' );

			text.className = 'mjb-dd__value';
			text.textContent = option ? option.text.trim() : '';
			button.innerHTML = '';
			button.appendChild( text );
			button.insertAdjacentHTML( 'beforeend', chevron );

			if ( select.getAttribute( 'aria-invalid' ) ) {
				button.setAttribute( 'aria-invalid', 'true' );
			} else {
				button.removeAttribute( 'aria-invalid' );
			}
		}

		function items() {
			return Array.prototype.slice.call( menu.querySelectorAll( '.mjb-dd__item' ) );
		}

		function build() {
			menu.innerHTML = '';

			Array.prototype.forEach.call( select.options, function ( option, index ) {
				var item = document.createElement( 'button' );
				var on = index === select.selectedIndex;

				if ( option.hidden || ( option.disabled && '' === option.value ) ) {
					return;
				}

				item.type = 'button';
				item.tabIndex = -1;
				item.className = 'mjb-dd__item' + ( on ? ' is-selected' : '' );
				item.id = menu.id + '-opt-' + index;
				item.setAttribute( 'role', 'option' );
				item.setAttribute( 'aria-selected', on ? 'true' : 'false' );
				item.setAttribute( 'data-index', String( index ) );
				item.textContent = option.text.trim();
				item.addEventListener( 'click', function () {
					choose( index );
				} );
				menu.appendChild( item );
			} );
		}

		function setActive( item ) {
			items().forEach( function ( other ) {
				other.classList.toggle( 'is-active', other === item );
			} );

			if ( item ) {
				button.setAttribute( 'aria-activedescendant', item.id );
				item.scrollIntoView( { block: 'nearest' } );
			} else {
				button.removeAttribute( 'aria-activedescendant' );
			}
		}

		function setOpen( open ) {
			if ( open === ! menu.hidden ) {
				return;
			}

			if ( open ) {
				build();
			}

			menu.hidden = ! open;
			wrap.classList.toggle( 'is-open', open );
			button.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			setActive( open ? ( menu.querySelector( '.is-selected' ) || items()[ 0 ] || null ) : null );
		}

		function choose( index ) {
			var changed = select.selectedIndex !== index;

			select.selectedIndex = index;
			paint();
			setOpen( false );
			button.focus();

			if ( changed ) {
				select.dispatchEvent( new Event( 'change', { bubbles: true } ) );
			}
		}

		function move( step ) {
			var all = items();
			var index = all.indexOf( menu.querySelector( '.is-active' ) );

			if ( ! all.length ) {
				return;
			}

			if ( 'first' === step || 'last' === step ) {
				index = 'first' === step ? 0 : all.length - 1;
			} else {
				index = -1 === index ? 0 : Math.max( 0, Math.min( all.length - 1, index + step ) );
			}

			setActive( all[ index ] );
		}

		// Type a letter to jump to the next option that starts with it.
		function jump( key ) {
			var all = items();
			var start = all.indexOf( menu.querySelector( '.is-active' ) );

			for ( var i = 1; i <= all.length; i++ ) {
				var item = all[ ( start + i ) % all.length ];

				if ( 0 === item.textContent.toLowerCase().indexOf( key ) ) {
					setActive( item );
					return;
				}
			}
		}

		button.addEventListener( 'click', function () {
			setOpen( menu.hidden );
		} );

		button.addEventListener( 'keydown', function ( event ) {
			var open = ! menu.hidden;
			var active = menu.querySelector( '.is-active' );

			if ( 'ArrowDown' === event.key || 'ArrowUp' === event.key ) {
				event.preventDefault();

				if ( open ) {
					move( 'ArrowDown' === event.key ? 1 : -1 );
				} else {
					setOpen( true );
				}
			} else if ( open && ( 'Home' === event.key || 'End' === event.key ) ) {
				event.preventDefault();
				move( 'Home' === event.key ? 'first' : 'last' );
			} else if ( 'Enter' === event.key || ' ' === event.key ) {
				event.preventDefault();

				if ( ! open ) {
					setOpen( true );
				} else if ( active ) {
					choose( parseInt( active.getAttribute( 'data-index' ), 10 ) );
				}
			} else if ( 'Escape' === event.key && open ) {
				event.preventDefault();
				event.stopPropagation();
				setOpen( false );
			} else if ( 'Tab' === event.key && open ) {
				setOpen( false );
			} else if ( 1 === event.key.length && ! event.ctrlKey && ! event.metaKey && ! event.altKey ) {
				setOpen( true );
				jump( event.key.toLowerCase() );
			}
		} );

		document.addEventListener( 'mousedown', function ( event ) {
			if ( ! wrap.contains( event.target ) ) {
				setOpen( false );
			}
		} );

		select.addEventListener( 'change', paint );

		// Error state (aria-invalid) is set on the select by the validation below.
		new MutationObserver( paint ).observe( select, { attributes: true, attributeFilter: [ 'aria-invalid' ] } );

		form.addEventListener( 'reset', function () {
			window.setTimeout( paint, 0 );
		} );

		wrap.insertBefore( button, select );
		wrap.appendChild( menu );
		paint();

		return button;
	}

	Array.prototype.forEach.call( form.querySelectorAll( '.mjb-field select' ), function ( select ) {
		select.mjbTrigger = enhanceSelect( select );
	} );

	var status = form.querySelector( '.mjb-contact-form__status' );
	var submit = form.querySelector( '.mjb-contact-form__submit' );
	var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

	// Optional; a bare domain is fine (the server adds https://).
	var urlPattern = /^(https?:\/\/)?[^\s\/:?#]+\.[^\s\/:?#]+(:\d+)?([\/?#]\S*)?$/i;

	var messages = {
		first_name: 'Please enter your first name.',
		last_name: 'Please enter your last name.',
		email: 'Please enter a valid email address.',
		company_url: 'Please enter a valid website address, like example.com.',
		topic: 'Please choose a topic.',
		message: 'Please write a message of at least 10 characters.',
		consent: 'Please tick the box so we may use your details to reply.'
	};

	function fieldWrap( name ) {
		var input = form.elements[ name ];
		return input ? input.closest( '.mjb-field' ) : null;
	}

	function setError( name, text ) {
		var input = form.elements[ name ];
		var wrap = fieldWrap( name );

		if ( ! input || ! wrap ) {
			return;
		}

		var error = wrap.querySelector( '.mjb-field__error' );

		wrap.classList.toggle( 'has-error', !! text );

		if ( text ) {
			input.setAttribute( 'aria-invalid', 'true' );
		} else {
			input.removeAttribute( 'aria-invalid' );
		}

		if ( error ) {
			error.textContent = text || '';
			error.hidden = ! text;
		}
	}

	function check( name ) {
		var input = form.elements[ name ];

		if ( ! input ) {
			return '';
		}

		var value = 'checkbox' === input.type ? ( input.checked ? '1' : '' ) : input.value.trim();

		if ( 'email' === name ) {
			return emailPattern.test( value ) ? '' : messages.email;
		}

		if ( 'company_url' === name ) {
			return ! value || urlPattern.test( value ) ? '' : messages.company_url;
		}

		if ( 'message' === name ) {
			return value.length >= 10 ? '' : messages.message;
		}

		if ( messages[ name ] ) {
			return value ? '' : messages[ name ];
		}

		return '';
	}

	function showStatus( text, ok ) {
		status.innerHTML = '';

		if ( ! text ) {
			return;
		}

		var p = document.createElement( 'p' );
		p.className = 'mjb-contact-form__notice ' + ( ok ? 'is-success' : 'is-error' );
		p.textContent = text;
		status.appendChild( p );
		status.focus( { preventScroll: true } );
		status.scrollIntoView( { behavior: 'smooth', block: 'center' } );
	}

	// Clear a field's error as soon as it is fixed.
	Object.keys( messages ).forEach( function ( name ) {
		var input = form.elements[ name ];

		if ( ! input ) {
			return;
		}

		input.addEventListener( 'checkbox' === input.type || 'SELECT' === input.tagName ? 'change' : 'input', function () {
			if ( fieldWrap( name ).classList.contains( 'has-error' ) && ! check( name ) ) {
				setError( name, '' );
			}
		} );

		input.addEventListener( 'blur', function () {
			if ( input.value.trim() || 'checkbox' === input.type ) {
				setError( name, check( name ) );
			}
		} );
	} );

	form.addEventListener( 'submit', function ( event ) {
		event.preventDefault();

		var first = null;

		Object.keys( messages ).forEach( function ( name ) {
			var error = check( name );
			setError( name, error );

			if ( error && ! first ) {
				first = form.elements[ name ];
			}
		} );

		if ( first ) {
			showStatus( 'Please check the highlighted fields.', false );
			( first.mjbTrigger || first ).focus();
			return;
		}

		submit.disabled = true;
		submit.classList.add( 'is-busy' );
		form.setAttribute( 'aria-busy', 'true' );

		fetch( form.getAttribute( 'action' ), {
			method: 'POST',
			body: new FormData( form ),
			credentials: 'same-origin',
			headers: { 'X-MJB-Ajax': '1' }
		} )
			.then( function ( response ) {
				return response.json();
			} )
			.then( function ( data ) {
				var errors = data && data.errors ? data.errors : {};

				Object.keys( messages ).forEach( function ( name ) {
					setError( name, errors[ name ] || '' );
				} );

				if ( data && data.ok ) {
					form.reset();
					showStatus( data.message || form.getAttribute( 'data-success' ), true );
				} else {
					showStatus( ( data && data.message ) || 'Your message could not be sent. Please try again.', false );
				}
			} )
			.catch( function () {
				showStatus( 'Your message could not be sent. Please check your connection and try again.', false );
			} )
			.then( function () {
				submit.disabled = false;
				submit.classList.remove( 'is-busy' );
				form.removeAttribute( 'aria-busy' );
			} );
	} );
}() );
