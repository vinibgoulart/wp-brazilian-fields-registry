<?php

namespace WPBrazilianRegistry\functions;

/*
	USAGE:
	
	notify( 'your message', [
		'email' => [
			'recipients' => 'dest@email.com',
			'subject'    => 'your subject',
			'to_admin'   => true, // send email to site admin
			'handler'    => function ( $mes, $opts ) {
				// to use another mailer
				// omit this option to use wp_mail
			}
		],
		'sms' => [
			// implement this notification channel
			// handle it with 'handler' option here
			// or listening the action hook '{$prefix}handle_notification_sms'
		]
	);
*/
function notify ( $message, $options = [] ) {
	// default handler
	if ( isset( $options['email'] ) ) {
		$opts = array_get( $options, 'email', [] );

		$opts = \apply_filters( prefix( 'notify_email_options' ), $opts );
		$content = \apply_filters( prefix( 'notify_email_content' ), $message, $opts );

		$opts['recipients']  = array_get( $opts, 'recipients', [] );
		$opts['subject']     = array_get( $opts, 'subject', config_get( 'NAME' ) . ' Notification' );
		$opts['content_type']     = array_get( $opts, 'content_type', 'content-type: text/html; charset=utf-8' );
		$opts['headers']     = array_get( $opts, 'headers', [] );
		$opts['attachments'] = array_get( $opts, 'attachments', [] );

		$opts['headers'][] = $opts['content_type'];

		// notify site admin by default
		if ( array_get( $opts, 'to_admin', true ) ) {
			$opts['recipients'][] = \get_bloginfo( 'admin_email' );
		}

		if ( isset( $opts['handler'] ) && is_callable( $opts['handler'] ) ) {
			call_user_func( $opts['handler'], $content, $opts );
		} else {
			\wp_mail(
				$opts['recipients'],
				$opts['subject'],
				$content,
				$opts['headers'],
				$opts['attachments']
			);
		}
	}

	unset( $options['email'] );

	foreach ( $options as $type => $opts ) {
		if ( isset( $opts['handler'] ) && is_callable( $opts['handler'] ) ) {
			call_user_func( $opts['handler'], $message, $opts );
		} else {
			\do_action( prefix( "handle_notification_$type" ), $message, $opts );
		}
	}

	logf( "Notification sent: {$message}" );
}
