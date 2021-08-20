<?php
require(__DIR__ . '/config.php');

// CREDIT: https://www.php.net/manual/en/features.file-upload.errors.php#115746
$phpFileUploadErrors = array(
	0 => 'There is no error, the file uploaded with success',
	1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
	2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
	3 => 'The uploaded file was only partially uploaded',
	4 => 'No file was uploaded',
	6 => 'Missing a temporary folder',
	7 => 'Failed to write file to disk.',
	8 => 'A PHP extension stopped the file upload.',
);

/**
 * Determine if request contains appropraite authorization token(s).
 * @return bool - True if authorized
 * @return bool - False if un-authorized. It is upto the caller to
 *                teardown resources and exit the script.
 */
function is_request_authorized(): bool {
	if (array_key_exists('Authorized', $_SERVER) && $_SERVER['Authorized'] === CONFIG['api_token']) {
		return true;
	}

	return false;
}

/**
 * Filter the supplied string, replacing all non alptha-numeric,
 * underscore, and hyphen characters with an '_'.
 * 
 * @param string $path - Path to filter.
 * 
 * @return string - Filtered path.
 */
function filter_file_path(string $path): string {
	return preg_replace('/[^A-Za-z0-9_\-]/', '_', $path);
}
?>