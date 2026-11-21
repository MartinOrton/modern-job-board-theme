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
			first.focus();
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
