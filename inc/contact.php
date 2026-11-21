<?php
/**
 * Contact form block (mjb/contact-form).
 *
 * - Rendered in PHP; works without JavaScript (post, redirect back) and with
 *   it (js/contact.js sends the form with fetch and shows the result inline).
 * - Spam protection: a hidden honeypot field, a signed timestamp (forms sent
 *   within 3 seconds of loading are rejected) and 5 messages per hour per
 *   visitor. reCAPTCHA can be added once keys are configured.
 * - Every message is saved under Contact messages in wp-admin, with a note of
 *   whether the email was sent, so nothing is lost if mail fails. Messages
 *   older than 12 months are deleted automatically (see the Privacy Policy).
 * - The recipient is set in the block's sidebar and read on the server from
 *   the page's saved content; it never appears in the page's HTML.
 *
 * @package ModernJobBoardTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const MJB_THEME_CONTACT_CPT       = 'mjb_contact_msg';
const MJB_THEME_CONTACT_RATE      = 5;
const MJB_THEME_CONTACT_MIN_AGE   = 3;
const MJB_THEME_CONTACT_RETENTION = 365;

/**
 * Default topics for the topic dropdown.
 *
 * @return string[]
 */
function mjb_theme_contact_default_topics() {
	return array(
		__( 'Sales and pricing', 'modern-job-board-theme' ),
		__( 'Technical support', 'modern-job-board-theme' ),
		__( 'Complete Site build', 'modern-job-board-theme' ),
		__( 'Partnerships', 'modern-job-board-theme' ),
		__( 'Privacy request', 'modern-job-board-theme' ),
		__( 'Something else', 'modern-job-board-theme' ),
	);
}

/**
 * Block attributes with their defaults.
 *
 * @return array
 */
function mjb_theme_contact_attributes() {
	return array(
		'recipient'      => array(
			'type'    => 'string',
			'default' => '',
		),
		'topics'         => array(
			'type'    => 'string',
			'default' => implode( "\n", mjb_theme_contact_default_topics() ),
		),
		'submitLabel'    => array(
			'type'    => 'string',
			'default' => __( 'Send message', 'modern-job-board-theme' ),
		),
		'successMessage' => array(
			'type'    => 'string',
			'default' => __( 'Thanks, your message has been sent. We will reply by email.', 'modern-job-board-theme' ),
		),
	);
}

/**
 * Register the block, the message post type and the form handlers.
 */
function mjb_theme_contact_register() {
	register_block_type(
		'mjb/contact-form',
		array(
			'api_version'           => 3,
			'title'                 => __( 'Contact Form', 'modern-job-board-theme' ),
			'description'           => __( 'Name, email, topic and message. Messages are emailed to the recipient and saved under Contact messages.', 'modern-job-board-theme' ),
			'category'              => 'mjb',
			'icon'                  => 'email',
			'keywords'              => array( 'contact', 'form', 'email', 'enquiry' ),
			'attributes'            => mjb_theme_contact_attributes(),
			'supports'              => array(
				'html'     => false,
				'align'    => false,
				'multiple' => false,
			),
			'editor_script_handles' => array( 'mjb-site-blocks-editor' ),
			'render_callback'       => 'mjb_theme_render_contact_form',
		)
	);

	register_post_type(
		MJB_THEME_CONTACT_CPT,
		array(
			'labels'              => array(
				'name'               => __( 'Contact messages', 'modern-job-board-theme' ),
				'singular_name'      => __( 'Contact message', 'modern-job-board-theme' ),
				'menu_name'          => __( 'Contact messages', 'modern-job-board-theme' ),
				'all_items'          => __( 'Contact messages', 'modern-job-board-theme' ),
				'edit_item'          => __( 'Contact message', 'modern-job-board-theme' ),
				'search_items'       => __( 'Search messages', 'modern-job-board-theme' ),
				'not_found'          => __( 'No messages yet.', 'modern-job-board-theme' ),
				'not_found_in_trash' => __( 'No messages in the trash.', 'modern-job-board-theme' ),
			),
			'public'              => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_rest'        => false,
			'exclude_from_search' => true,
			'menu_icon'           => 'dashicons-email-alt',
			'menu_position'       => 26,
			'supports'            => array( 'title' ),
			'capability_type'     => 'page',
			'capabilities'        => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap'        => true,
		)
	);

	wp_register_script(
		'mjb-contact',
		get_template_directory_uri() . '/js/contact.js',
		array(),
		MJB_THEME_VERSION,
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'init', 'mjb_theme_contact_register' );

/**
 * Signed timestamp for the form.
 *
 * @param int $time Unix time.
 * @return string
 */
function mjb_theme_contact_stamp( $time ) {
	return $time . '.' . substr( wp_hash( 'mjb_contact|' . $time, 'nonce' ), 0, 20 );
}

/**
 * Seconds since a signed timestamp was issued, or null if it is not valid.
 *
 * @param string $stamp Signed timestamp.
 * @return int|null
 */
function mjb_theme_contact_stamp_age( $stamp ) {
	$parts = explode( '.', (string) $stamp, 2 );

	if ( 2 !== count( $parts ) || ! ctype_digit( $parts[0] ) ) {
		return null;
	}

	if ( ! hash_equals( mjb_theme_contact_stamp( (int) $parts[0] ), (string) $stamp ) ) {
		return null;
	}

	return time() - (int) $parts[0];
}

/**
 * Topics as a list.
 *
 * @param string $topics One topic per line.
 * @return string[]
 */
function mjb_theme_contact_topic_list( $topics ) {
	$list = array_values( array_filter( array_map( 'trim', preg_split( '/\R/', (string) $topics ) ) ) );

	return $list ? $list : mjb_theme_contact_default_topics();
}

/**
 * Link to the published privacy policy, if there is one.
 *
 * @return string
 */
function mjb_theme_contact_privacy_link() {
	$url = get_privacy_policy_url();

	if ( '' === $url ) {
		return __( 'our privacy policy', 'modern-job-board-theme' );
	}

	return '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener">' . esc_html__( 'Privacy Policy', 'modern-job-board-theme' ) . '</a>';
}

/**
 * Render the form.
 *
 * @param array $attributes Block attributes.
 * @return string
 */
function mjb_theme_render_contact_form( $attributes ) {
	$defaults   = wp_list_pluck( mjb_theme_contact_attributes(), 'default' );
	$attributes = wp_parse_args( $attributes, $defaults );
	$topics     = mjb_theme_contact_topic_list( $attributes['topics'] );
	$page_id    = get_the_ID();
	$is_editor  = defined( 'REST_REQUEST' ) && REST_REQUEST;

	if ( ! $is_editor ) {
		wp_enqueue_script( 'mjb-contact' );
	}

	// Result of a submission without JavaScript (post, redirect, get).
	$values = array();
	$errors = array();
	$status = '';

	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- Read-only display of a redirect result.
	if ( isset( $_GET['mjb_contact'] ) ) {
		$result = sanitize_key( wp_unslash( $_GET['mjb_contact'] ) );

		if ( 'sent' === $result ) {
			$status = 'sent';
		} elseif ( isset( $_GET['mjb_ct'] ) ) {
			$saved = get_transient( 'mjb_contact_ret_' . sanitize_key( wp_unslash( $_GET['mjb_ct'] ) ) );

			if ( is_array( $saved ) ) {
				$values = $saved['values'];
				$errors = $saved['errors'];
				$status = 'error';
			}
		}
	}
	// phpcs:enable

	$value = static function ( $key ) use ( $values ) {
		return isset( $values[ $key ] ) ? (string) $values[ $key ] : '';
	};

	$field = static function ( $key, $label, $input, $required = true ) use ( $errors ) {
		$error = isset( $errors[ $key ] ) ? $errors[ $key ] : '';
		$id    = 'mjb-contact-' . $key;

		return sprintf(
			'<div class="mjb-field mjb-field--%1$s%2$s"><label for="%3$s">%4$s%5$s</label>%6$s<p class="mjb-field__error" id="%3$s-error"%7$s>%8$s</p></div>',
			esc_attr( $key ),
			$error ? ' has-error' : '',
			esc_attr( $id ),
			esc_html( $label ),
			$required ? ' <span class="mjb-field__req" aria-hidden="true">*</span>' : ' <span class="mjb-field__opt">' . esc_html__( '(optional)', 'modern-job-board-theme' ) . '</span>',
			$input,
			$error ? '' : ' hidden',
			esc_html( $error )
		);
	};

	$aria = static function ( $key, $required = true ) use ( $errors ) {
		$out = ' id="mjb-contact-' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" aria-describedby="mjb-contact-' . esc_attr( $key ) . '-error"';

		if ( $required ) {
			$out .= ' required aria-required="true"';
		}

		if ( isset( $errors[ $key ] ) ) {
			$out .= ' aria-invalid="true"';
		}

		return $out;
	};

	$topic_options = '';

	foreach ( $topics as $topic ) {
		$topic_options .= sprintf( '<option value="%1$s"%2$s>%1$s</option>', esc_attr( $topic ), selected( $value( 'topic' ), $topic, false ) );
	}

	$status_html = '';

	if ( 'sent' === $status ) {
		$status_html = '<p class="mjb-contact-form__notice is-success">' . esc_html( $attributes['successMessage'] ) . '</p>';
	} elseif ( 'error' === $status ) {
		$message     = isset( $errors['_form'] ) ? $errors['_form'] : __( 'Please check the highlighted fields.', 'modern-job-board-theme' );
		$status_html = '<p class="mjb-contact-form__notice is-error">' . esc_html( $message ) . '</p>';
	}

	ob_start();
	?>
	<form class="mjb-contact-form" id="contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate data-success="<?php echo esc_attr( $attributes['successMessage'] ); ?>">
		<input type="hidden" name="action" value="mjb_contact" />
		<input type="hidden" name="mjb_contact_page" value="<?php echo esc_attr( (string) $page_id ); ?>" />
		<input type="hidden" name="mjb_contact_ts" value="<?php echo esc_attr( mjb_theme_contact_stamp( time() ) ); ?>" />
		<div class="mjb-contact-form__hp" aria-hidden="true">
			<label for="mjb-contact-website"><?php esc_html_e( 'Leave this field empty', 'modern-job-board-theme' ); ?></label>
			<input type="text" id="mjb-contact-website" name="website" value="" tabindex="-1" autocomplete="off" />
		</div>
		<div class="mjb-contact-form__status" role="status" aria-live="polite" tabindex="-1"><?php echo $status_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped above. ?></div>
		<div class="mjb-contact-form__grid">
			<?php
			// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- Built from escaped parts.
			echo $field( 'first_name', __( 'First name', 'modern-job-board-theme' ), '<input type="text"' . $aria( 'first_name' ) . ' autocomplete="given-name" maxlength="60" value="' . esc_attr( $value( 'first_name' ) ) . '" />' );
			echo $field( 'last_name', __( 'Last name', 'modern-job-board-theme' ), '<input type="text"' . $aria( 'last_name' ) . ' autocomplete="family-name" maxlength="60" value="' . esc_attr( $value( 'last_name' ) ) . '" />' );
			echo $field( 'email', __( 'Email address', 'modern-job-board-theme' ), '<input type="email"' . $aria( 'email' ) . ' autocomplete="email" maxlength="190" value="' . esc_attr( $value( 'email' ) ) . '" />' );
			echo $field( 'company', __( 'Company', 'modern-job-board-theme' ), '<input type="text"' . $aria( 'company', false ) . ' autocomplete="organization" maxlength="150" value="' . esc_attr( $value( 'company' ) ) . '" />', false );
			// "website" is the honeypot's name, so the real field is company_url.
			echo $field( 'company_url', __( 'Website', 'modern-job-board-theme' ), '<input type="text" inputmode="url"' . $aria( 'company_url', false ) . ' autocomplete="url" maxlength="200" placeholder="example.com" value="' . esc_attr( $value( 'company_url' ) ) . '" />', false );
			echo $field( 'topic', __( 'Topic', 'modern-job-board-theme' ), '<select' . $aria( 'topic' ) . '>' . $topic_options . '</select>' );
			echo $field( 'message', __( 'Message', 'modern-job-board-theme' ), '<textarea' . $aria( 'message' ) . ' rows="7" minlength="10" maxlength="5000">' . esc_textarea( $value( 'message' ) ) . '</textarea>' );
			// phpcs:enable

			$consent_error = isset( $errors['consent'] ) ? $errors['consent'] : '';
			?>
			<div class="mjb-field mjb-field--consent<?php echo $consent_error ? ' has-error' : ''; ?>">
				<label class="mjb-check" for="mjb-contact-consent">
					<input type="checkbox" id="mjb-contact-consent" name="consent" value="1" required aria-required="true" aria-describedby="mjb-contact-consent-error"<?php checked( '1', $value( 'consent' ) ); ?><?php echo $consent_error ? ' aria-invalid="true"' : ''; ?> />
					<span>
						<?php
						printf(
							/* translators: %s: link to the privacy policy */
							wp_kses( __( 'I agree that my details are used to reply to this message, as described in the %s.', 'modern-job-board-theme' ), array() ),
							mjb_theme_contact_privacy_link() // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in the helper.
						);
						?>
					</span>
				</label>
				<p class="mjb-field__error" id="mjb-contact-consent-error"<?php echo $consent_error ? '' : ' hidden'; ?>><?php echo esc_html( $consent_error ); ?></p>
			</div>
			<div class="mjb-contact-form__actions">
				<button type="submit" class="btn btn-primary mjb-contact-form__submit"><?php echo esc_html( $attributes['submitLabel'] ); ?></button>
			</div>
		</div>
	</form>
	<?php
	return (string) ob_get_clean();
}

/**
 * Visitor key for rate limiting (a hash; the IP address itself is not stored).
 *
 * @return string
 */
function mjb_theme_contact_visitor_key() {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';

	return 'mjb_contact_rl_' . substr( wp_hash( 'mjb_contact_rl|' . $ip ), 0, 24 );
}

/**
 * Attributes of the contact form block saved on a page.
 *
 * @param int $page_id Page id.
 * @return array|null Null when the page has no published contact form.
 */
function mjb_theme_contact_block_attributes( $page_id ) {
	$post = get_post( $page_id );

	if ( ! $post instanceof WP_Post || 'publish' !== $post->post_status || ! has_block( 'mjb/contact-form', $post ) ) {
		return null;
	}

	$find = static function ( $blocks ) use ( &$find ) {
		foreach ( $blocks as $block ) {
			if ( 'mjb/contact-form' === $block['blockName'] ) {
				return (array) $block['attrs'];
			}

			if ( ! empty( $block['innerBlocks'] ) ) {
				$found = $find( $block['innerBlocks'] );

				if ( null !== $found ) {
					return $found;
				}
			}
		}

		return null;
	};

	$attributes = $find( parse_blocks( $post->post_content ) );

	if ( null === $attributes ) {
		return null;
	}

	return wp_parse_args( $attributes, wp_list_pluck( mjb_theme_contact_attributes(), 'default' ) );
}

/**
 * Tidy an optional website address: adds https:// when no scheme was typed.
 *
 * @param string $raw What the visitor typed.
 * @return string|null The address ('' when left empty), or null when it isn't a usable web address.
 */
function mjb_theme_contact_normalise_url( $raw ) {
	$raw = trim( (string) $raw );

	if ( '' === $raw ) {
		return '';
	}

	if ( mb_strlen( $raw ) > 200 || preg_match( '/\s/', $raw ) ) {
		return null;
	}

	if ( ! preg_match( '#^https?://#i', $raw ) ) {
		if ( preg_match( '#^[a-z][a-z0-9+.-]*:#i', $raw ) && ! preg_match( '#^[^/:]+:\d+#', $raw ) ) {
			return null; // Another scheme (javascript:, mailto: and so on).
		}

		$raw = 'https://' . ltrim( $raw, '/' );
	}

	$host = wp_parse_url( $raw, PHP_URL_HOST );

	if ( ! is_string( $host ) || false === strpos( $host, '.' ) || ! preg_match( '/^[\p{L}\p{N}.-]+$/u', $host ) ) {
		return null;
	}

	$url = esc_url_raw( $raw, array( 'http', 'https' ) );

	return '' === $url ? null : $url;
}

/**
 * Handle a submission (admin-post.php, logged in or not).
 */
function mjb_theme_contact_handle() {
	// phpcs:disable WordPress.Security.NonceVerification.Missing -- Public form; protected by the signed timestamp, honeypot and rate limit.
	$ajax    = isset( $_SERVER['HTTP_X_MJB_AJAX'] );
	$page_id = isset( $_POST['mjb_contact_page'] ) ? absint( $_POST['mjb_contact_page'] ) : 0;
	$attrs   = mjb_theme_contact_block_attributes( $page_id );
	$back    = $page_id ? get_permalink( $page_id ) : home_url( '/' );

	$values = array(
		'first_name'  => isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '',
		'last_name'   => isset( $_POST['last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : '',
		'email'       => isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '',
		'company'     => isset( $_POST['company'] ) ? sanitize_text_field( wp_unslash( $_POST['company'] ) ) : '',
		'company_url' => isset( $_POST['company_url'] ) ? sanitize_text_field( wp_unslash( $_POST['company_url'] ) ) : '',
		'topic'       => isset( $_POST['topic'] ) ? sanitize_text_field( wp_unslash( $_POST['topic'] ) ) : '',
		'message'     => isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '',
		'consent'     => empty( $_POST['consent'] ) ? '' : '1',
	);
	$honeypot = isset( $_POST['website'] ) ? trim( (string) wp_unslash( $_POST['website'] ) ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Only checked for emptiness.
	$stamp    = isset( $_POST['mjb_contact_ts'] ) ? sanitize_text_field( wp_unslash( $_POST['mjb_contact_ts'] ) ) : '';
	// phpcs:enable

	$respond = static function ( $ok, $errors = array() ) use ( $ajax, $back, $values, $attrs ) {
		if ( $ajax ) {
			wp_send_json(
				array(
					'ok'      => $ok,
					'message' => $ok ? ( $attrs ? $attrs['successMessage'] : '' ) : ( isset( $errors['_form'] ) ? $errors['_form'] : __( 'Please check the highlighted fields.', 'modern-job-board-theme' ) ),
					'errors'  => (object) $errors,
				),
				$ok ? 200 : 400
			);
		}

		if ( $ok ) {
			$url = add_query_arg( 'mjb_contact', 'sent', $back );
		} else {
			$token = wp_generate_password( 16, false );
			set_transient(
				'mjb_contact_ret_' . strtolower( $token ),
				array(
					'values' => $values,
					'errors' => $errors,
				),
				10 * MINUTE_IN_SECONDS
			);
			$url = add_query_arg(
				array(
					'mjb_contact' => 'error',
					'mjb_ct'      => strtolower( $token ),
				),
				$back
			);
		}

		wp_safe_redirect( $url . '#contact-form', 303 );
		exit;
	};

	if ( ! $attrs ) {
		$respond( false, array( '_form' => __( 'This form is not available. Please reload the page and try again.', 'modern-job-board-theme' ) ) );
	}

	// Bots that fill in the hidden field are told it worked and nothing is sent.
	if ( '' !== $honeypot ) {
		$respond( true );
	}

	$age = mjb_theme_contact_stamp_age( $stamp );

	if ( null === $age || $age > DAY_IN_SECONDS ) {
		$respond( false, array( '_form' => __( 'This form has expired. Please reload the page and send your message again.', 'modern-job-board-theme' ) ) );
	}

	if ( $age < MJB_THEME_CONTACT_MIN_AGE ) {
		$respond( false, array( '_form' => __( 'That was very quick. Please wait a moment and send your message again.', 'modern-job-board-theme' ) ) );
	}

	$errors = array();

	if ( '' === $values['first_name'] ) {
		$errors['first_name'] = __( 'Please enter your first name.', 'modern-job-board-theme' );
	} elseif ( mb_strlen( $values['first_name'] ) > 60 ) {
		$errors['first_name'] = __( 'Please use 60 characters or fewer.', 'modern-job-board-theme' );
	}

	if ( '' === $values['last_name'] ) {
		$errors['last_name'] = __( 'Please enter your last name.', 'modern-job-board-theme' );
	} elseif ( mb_strlen( $values['last_name'] ) > 60 ) {
		$errors['last_name'] = __( 'Please use 60 characters or fewer.', 'modern-job-board-theme' );
	}

	if ( '' === $values['email'] || ! is_email( $values['email'] ) ) {
		$errors['email'] = __( 'Please enter a valid email address.', 'modern-job-board-theme' );
	}

	if ( mb_strlen( $values['company'] ) > 150 ) {
		$errors['company'] = __( 'Please use 150 characters or fewer.', 'modern-job-board-theme' );
	}

	$website = mjb_theme_contact_normalise_url( $values['company_url'] );

	if ( null === $website ) {
		$errors['company_url'] = __( 'Please enter a valid website address, like example.com.', 'modern-job-board-theme' );
	}

	if ( ! in_array( $values['topic'], mjb_theme_contact_topic_list( $attrs['topics'] ), true ) ) {
		$errors['topic'] = __( 'Please choose a topic.', 'modern-job-board-theme' );
	}

	$length = mb_strlen( $values['message'] );

	if ( $length < 10 ) {
		$errors['message'] = __( 'Please write a message of at least 10 characters.', 'modern-job-board-theme' );
	} elseif ( $length > 5000 ) {
		$errors['message'] = __( 'Please keep your message under 5,000 characters.', 'modern-job-board-theme' );
	}

	if ( '1' !== $values['consent'] ) {
		$errors['consent'] = __( 'Please tick the box so we may use your details to reply.', 'modern-job-board-theme' );
	}

	if ( $errors ) {
		$respond( false, $errors );
	}

	$rate_key = mjb_theme_contact_visitor_key();
	$count    = (int) get_transient( $rate_key );

	if ( $count >= MJB_THEME_CONTACT_RATE ) {
		$respond( false, array( '_form' => __( 'You have sent several messages in the last hour. Please try again later.', 'modern-job-board-theme' ) ) );
	}

	set_transient( $rate_key, $count + 1, HOUR_IN_SECONDS );

	$full_name = trim( $values['first_name'] . ' ' . $values['last_name'] );

	$message_id = wp_insert_post(
		array(
			'post_type'    => MJB_THEME_CONTACT_CPT,
			'post_status'  => 'private',
			/* translators: 1: sender name, 2: topic */
			'post_title'   => sprintf( __( '%1$s: %2$s', 'modern-job-board-theme' ), $full_name, $values['topic'] ),
			'post_content' => $values['message'],
			'meta_input'   => array(
				'_mjb_contact_name'       => $full_name,
				'_mjb_contact_first_name' => $values['first_name'],
				'_mjb_contact_last_name'  => $values['last_name'],
				'_mjb_contact_email'      => $values['email'],
				'_mjb_contact_company'    => $values['company'],
				'_mjb_contact_website'    => (string) $website,
				'_mjb_contact_topic'   => $values['topic'],
				'_mjb_contact_page'    => $page_id,
				'_mjb_contact_consent' => gmdate( 'c' ),
			),
		),
		true
	);

	$recipient = is_email( $attrs['recipient'] ) ? $attrs['recipient'] : get_option( 'admin_email' );
	$site      = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
	$lines     = array(
		/* translators: %s: site name */
		sprintf( __( 'New message from the contact form on %s', 'modern-job-board-theme' ), $site ),
		'',
		__( 'Name:', 'modern-job-board-theme' ) . ' ' . $full_name,
		__( 'Email:', 'modern-job-board-theme' ) . ' ' . $values['email'],
		__( 'Company:', 'modern-job-board-theme' ) . ' ' . ( '' !== $values['company'] ? $values['company'] : '-' ),
		__( 'Website:', 'modern-job-board-theme' ) . ' ' . ( '' !== (string) $website ? $website : '-' ),
		__( 'Topic:', 'modern-job-board-theme' ) . ' ' . $values['topic'],
		'',
		__( 'Message:', 'modern-job-board-theme' ),
		$values['message'],
		'',
		'--',
		__( 'Sent from:', 'modern-job-board-theme' ) . ' ' . $back,
	);

	if ( ! is_wp_error( $message_id ) ) {
		$lines[] = __( 'Saved in WordPress:', 'modern-job-board-theme' ) . ' ' . admin_url( 'post.php?post=' . $message_id . '&action=edit' );
	}

	$name_header = str_replace( array( '"', "\r", "\n" ), '', $full_name );
	$sent        = wp_mail(
		$recipient,
		/* translators: 1: site name, 2: topic, 3: sender name */
		sprintf( __( '[%1$s] %2$s from %3$s', 'modern-job-board-theme' ), $site, $values['topic'], $name_header ),
		implode( "\n", $lines ),
		array( 'Reply-To: "' . $name_header . '" <' . $values['email'] . '>' )
	);

	if ( ! is_wp_error( $message_id ) ) {
		update_post_meta( $message_id, '_mjb_contact_mailed', $sent ? 'yes' : 'no' );
	}

	$respond( true );
}
add_action( 'admin_post_nopriv_mjb_contact', 'mjb_theme_contact_handle' );
add_action( 'admin_post_mjb_contact', 'mjb_theme_contact_handle' );

/**
 * Contact messages list: sender, topic and email status columns.
 *
 * @param array $columns Columns.
 * @return array
 */
function mjb_theme_contact_columns( $columns ) {
	return array(
		'cb'           => $columns['cb'],
		'title'        => __( 'Message', 'modern-job-board-theme' ),
		'mjb_from'     => __( 'From', 'modern-job-board-theme' ),
		'mjb_topic'    => __( 'Topic', 'modern-job-board-theme' ),
		'mjb_mailed'   => __( 'Email sent', 'modern-job-board-theme' ),
		'date'         => $columns['date'],
	);
}
add_filter( 'manage_' . MJB_THEME_CONTACT_CPT . '_posts_columns', 'mjb_theme_contact_columns' );

/**
 * Print a column cell.
 *
 * @param string $column  Column.
 * @param int    $post_id Message id.
 */
function mjb_theme_contact_column( $column, $post_id ) {
	if ( 'mjb_from' === $column ) {
		$email = (string) get_post_meta( $post_id, '_mjb_contact_email', true );
		echo esc_html( (string) get_post_meta( $post_id, '_mjb_contact_name', true ) ) . '<br /><a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>';
	} elseif ( 'mjb_topic' === $column ) {
		echo esc_html( (string) get_post_meta( $post_id, '_mjb_contact_topic', true ) );
	} elseif ( 'mjb_mailed' === $column ) {
		$mailed = get_post_meta( $post_id, '_mjb_contact_mailed', true );
		echo 'yes' === $mailed ? esc_html__( 'Yes', 'modern-job-board-theme' ) : '<strong>' . esc_html__( 'Failed', 'modern-job-board-theme' ) . '</strong>';
	}
}
add_action( 'manage_' . MJB_THEME_CONTACT_CPT . '_posts_custom_column', 'mjb_theme_contact_column', 10, 2 );

/**
 * Read-only view of a message on its edit screen.
 */
function mjb_theme_contact_meta_box() {
	add_meta_box(
		'mjb-contact-message',
		__( 'Message', 'modern-job-board-theme' ),
		static function ( $post ) {
			$rows = array(
				__( 'Name', 'modern-job-board-theme' )               => get_post_meta( $post->ID, '_mjb_contact_name', true ),
				__( 'Email', 'modern-job-board-theme' )              => get_post_meta( $post->ID, '_mjb_contact_email', true ),
				__( 'Company', 'modern-job-board-theme' )            => get_post_meta( $post->ID, '_mjb_contact_company', true ),
				__( 'Website', 'modern-job-board-theme' )            => get_post_meta( $post->ID, '_mjb_contact_website', true ),
				__( 'Topic', 'modern-job-board-theme' )              => get_post_meta( $post->ID, '_mjb_contact_topic', true ),
				__( 'Received', 'modern-job-board-theme' )           => get_the_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $post ),
				__( 'Email sent', 'modern-job-board-theme' )         => 'yes' === get_post_meta( $post->ID, '_mjb_contact_mailed', true ) ? __( 'Yes', 'modern-job-board-theme' ) : __( 'Failed', 'modern-job-board-theme' ),
			);

			echo '<table class="form-table" role="presentation"><tbody>';

			foreach ( $rows as $label => $text ) {
				echo '<tr><th scope="row">' . esc_html( $label ) . '</th><td>' . esc_html( '' !== (string) $text ? (string) $text : '-' ) . '</td></tr>';
			}

			echo '</tbody></table>';
			echo '<div style="white-space:pre-wrap;padding:12px 16px;background:#f6f7f7;border-radius:4px;">' . esc_html( $post->post_content ) . '</div>';

			$email = (string) get_post_meta( $post->ID, '_mjb_contact_email', true );

			if ( is_email( $email ) ) {
				echo '<p><a class="button button-primary" href="mailto:' . esc_attr( $email ) . '?subject=' . rawurlencode( 'Re: ' . get_post_meta( $post->ID, '_mjb_contact_topic', true ) ) . '">' . esc_html__( 'Reply by email', 'modern-job-board-theme' ) . '</a></p>';
			}
		},
		MJB_THEME_CONTACT_CPT,
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes_' . MJB_THEME_CONTACT_CPT, 'mjb_theme_contact_meta_box' );

/**
 * Delete messages older than the retention period (daily).
 */
function mjb_theme_contact_cleanup() {
	$old = get_posts(
		array(
			'post_type'      => MJB_THEME_CONTACT_CPT,
			'post_status'    => 'any',
			'posts_per_page' => 200,
			'fields'         => 'ids',
			'date_query'     => array(
				array(
					'before' => MJB_THEME_CONTACT_RETENTION . ' days ago',
				),
			),
		)
	);

	foreach ( $old as $id ) {
		wp_delete_post( $id, true );
	}
}
add_action( 'mjb_theme_contact_cleanup', 'mjb_theme_contact_cleanup' );

/**
 * Schedule the daily clean-up.
 */
function mjb_theme_contact_schedule() {
	if ( ! wp_next_scheduled( 'mjb_theme_contact_cleanup' ) ) {
		wp_schedule_event( time() + HOUR_IN_SECONDS, 'daily', 'mjb_theme_contact_cleanup' );
	}
}
add_action( 'init', 'mjb_theme_contact_schedule' );
