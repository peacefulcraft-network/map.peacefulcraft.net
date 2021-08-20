<?php
/**
 * /delete.php?subdir&file
 * @param string subdir Subdirectory where file resides.
 * @param string file Name of file.
 * 
 * @return 201 - File deleted
 * @return 400 - Request is missing parameters or has malformed parameters.
 * @return 401 - Not authorized.
 * @return 404 - File doesn't exist.
 * @return 500 - FS error occured during deletion.
 */
require(__DIR__ . '/common.php');

// Authenticate
if (is_request_authorized()) {
	http_response_code(401);
	exit();
}

// Check for optional subdir, filter it and the file name.
$upload_dir = CONFIG['file_dir'];
if (array_key_exists('subdir', $_GET) && strlen($_GET['subdir']) > 0) {
	$upload_dir .= '/'. filter_file_path($_GET['subdir']);
}
$upload_name - filter_file_path($_FILES['file']['name']);
if (strlen($upload_name) < 1) {
	http_response_code(400);
	echo 'Invalid file name.';
	exit();
}
$file_path = "${upload_dir}/${upload_name}";

// Check file exists and delete
if (file_exists($file_path)) {
	if (unlink($file_path)) {
		http_response_code(201);
		exit();
	} else {
		http_response_code(500);
		exit();
	}
} else {
	http_response_code(404);
}
?>