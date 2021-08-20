<?php
/**
 * /upload.php?subdir
 * @param string subdir Optional subdirectory in which to store the file.
 * 
 * @return 201 - Upload proccessed OK.
 * @return 400 - Request is missing parameters or aas invalid parameters.
 * @return 500 - Intenal error occured while handling upload.
 */
require(__DIR__ . '/common.php');

// Authenticate
if (is_request_authorized()) {
	http_response_code(401);
	exit();
}

// Check that [only] a [single] file was provided
if (count($_FILES) !== 1) {
	http_response_code(400);
	echo 'Expected exactly one file to be uploaded with this request.';
	exit();
}

// Check that provided file uploaded properly
if ($_FILES['file']['error'] !== 0) {
	http_response_code(500);
	if (!array_key_exists($_FILES['file']['error'], $phpFileUploadErrors)) {
		echo 'Unknown upload error occured ' . $_FILES['file']['error'];
	} else {
		echo $phpFileUploadErrors[$_FILES['file']['error']];
	}
	exit();
}

// Ignore null files
if ($_FILES['file']['size'] < 1) {
	http_response_code(400);
	echo 'Expected exactly one non-null file to be uploaded with this request.';
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


// Move out of temporary storage
if (move_uploaded_file($_FILES['file']['tmp_name'], $file_path) === true) {
	http_response_code(201);
} else {
	http_response_code(500);
}
?>