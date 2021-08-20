<?php
/**
 * /list.php?subdir
 * @param string subdir Optional subdirectory in which to look.
 * 
 * @return 200 - Line 1: Number of files, Line 2-n: File name. No directory prefixes.
 * @return 400 - Request is missing parameters or has invalid parameters.
 * @return 500 - Internal error occured while handling upload.
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

// Check that target can be meaningfully scanned.
if (!is_dir($upload_dir)) {
	http_response_code(400);
	exit();
}

// Scandir
$files = scandir($upload_dir);
if ($files === false) {
	http_response_code(500);
	exit();
}

$fc = count($files);
// Just the 2 dots '.', '..' - we can ignore.
if ($fc < 3) {
	http_response_code(200);
	echo 0;
	exit();
}

/*
	Emit files:
	[number of files - 2 for dots]
	[list of all files, no prefixes, starting after the dots]
*/
http_response_code(200);
echo ($fc - 2) . PHP_EOL;
for ($i=2; $i<$fc; $i++) {
	echo $files[$i] . PHP_EOL;
}
?>