<?php

namespace WPBrazilianRegistry\functions;

function get_file_extension ( $path ) {
	return \strtolower( pathinfo( $path, PATHINFO_EXTENSION ) );
}

function rscandir ( $dir ) {
	$files = scandir( $dir );
	$result = [];

	unset( $files[ array_search( '.', $files, true ) ] );
	unset( $files[ array_search( '..', $files, true ) ] );

	if ( count( $files ) == 0) return;

	foreach( $files as $entry ) {
		$entry = "$dir/$entry";

		if ( ! is_dir( $entry ) ) {
			$result[] = $entry;
		} else {
			$result = array_merge( $result, rscandir( $entry ) );
		}
	}

	return $result;
}

function upload_remote_file ( $url, $filename = 'download' ) {
	// Gives us access to the download_url() and wp_handle_sideload() functions.
	require_once ABSPATH . 'wp-admin/includes/file.php';

	$download_timeout = \apply_filters( prefix( __FUNCTION__ . '_timeout' ), 10, $url );
	$signature_verification = \apply_filters( prefix( __FUNCTION__ . '_signature_verification' ), false, $url );
	$response = \download_url( $url, $download_timeout, $signature_verification );
	$error_prefix = 'Error while uploading file: ';

	throw_if(
		\is_wp_error( $response ),
		$error_prefix . 'HTTP error',
		$response
	);

	$mime_type = mime_content_type( $response );

	$file = array(
		'name'     => $filename,
		'type'     => $mime_type,
		'tmp_name' => $response,
		'error'    => 0,
		'size'     => filesize( $response ),
	);

	$overrides = array(
		// This tells WordPress to not look for the POST form
		// Since the file is being downloaded from a remote server,
		// there will be no form fields.
		'test_form'   => false,
		// Setting this to false lets WordPress allow empty files – not recommended.
		'test_size'   => true,
		// A properly uploaded file will pass this test.
		// There should be no reason to override this one.
		'test_upload' => true,
	);

	$file_attributes = \wp_handle_sideload( $file, $overrides );

	throw_if(
		! empty( $file_attributes['error'] ),
		$error_prefix . 'sideload error',
		$file_attributes
	);

	// Insert the image as a new attachment.
	$file_path = $file_attributes['file'];
	$wp_upload_dir = wp_upload_dir();
	$attachment_data = [
		'guid'           => $wp_upload_dir['url'] . '/' . basename( $file_path ),
		'post_mime_type' => $mime_type,
		'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file_path ) ),
		'post_content'   => '',
		'post_status'    => 'inherit',
	];

	$attachment_id = \wp_insert_attachment( $attachment_data, $file_path );

	throw_if(
		0 === $attachment_id,
		$error_prefix . ' can\'t add the attachment'
	);

	$attachment = \get_post( $attachment_id );
	$full_size_path = \get_attached_file( $attachment_id );
	$meta_data = \wp_generate_attachment_metadata( $attachment_id, $full_size_path );
	\wp_update_attachment_metadata( $attachment_id, $meta_data );

	return $attachment_id;
}
