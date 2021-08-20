<?php
/**
 * /hash.php?subdir&file&algo
 * @param string subdir Subdirectory where file resides.
 * @param string file Name of file.
 * @param string algo Hash algorithm to use.
 * 
 * @return 200 - Hash string.
 * @return 400 - Request is missing parameters.
 * @return 404 - File doesn't exist.
 * @return 500 - Unspecified error occured during hash.
 */
require(__DIR__ . '/common.php');

// Authenticate
if (is_request_authorized()) {
	http_response_code(401);
	exit();
}

// Check for optional subdir and filter it
$file_dir = CONFIG['file_dir'];
if (array_key_exists('subdir', $_GET)) {
	$file_dir .= '/'. filter_file_path($_GET['subdir']);
}
// Ensure filename is provided
if (!array_key_exists('file', $_GET)) {
	http_response_code(400);
	echo 'Expected param \'file\'.';
	exit();
}
// Filter file name and create full, filtered file path.
$file_name - filter_file_path($_GET['file']);
$file_path = "${file_dir}/${file_name}";

// Ensure the file exists
if (!file_exists($file_path)) {
	http_response_code(404);
	exit();
}

// Check for valid hash alg
$algo = 'sha256';
if (array_key_exists('algo', $_GET)) {
	if (in_array(hash_algos(), strtolower($_GET['algo']))) {
		$algo = strtolower($_GET['algo']);
	} else {
		http_response_code(400);
		echo 'Unknown hashing algorithm provided ' . htmlentities($_GET['algo']);
		exit();
	}
}

// Hash and respond
$hash = hash_file($algo, $file_path);
if ($hash === false) {
	http_response_code(500);
} else {
	http_response_code(200);
	echo $hash;
}
?>