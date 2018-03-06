<?php defined('BASEPATH') OR exit('No direct script access allowed');

# Includes the autoloader for libraries installed with composer
require  FCPATH . '/vendor/autoload.php';

# Imports the Google Cloud client library
use Google\Cloud\Storage\StorageClient;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * @param bool $file_path the path for find the file
 * @param bool $file_name the name of the file
 * @return bool the result of the function
 */
function upload_file($file_path = false, $file_name = false)
{
	$result = false;

	if($file_path && $file_name){
		$CI = & get_instance();
	    $CI->config->load('google_cloud');

		$bucketName  = $CI->config->item('audio_bucket_name');
		$keyFilePath = $CI->config->item('key_file_path');

		// instantiates a client
		$storage = new StorageClient([
			'keyFilePath' => $keyFilePath
		]);

		// open file and instantiate bucket
		$file     = fopen($file_path, 'r');
		$bucket   = $storage->bucket($bucketName);
		$metadata = ['contentType' => 'audio/flac','uploadType' => 'resumable'];

		if($bucket->upload($file, ['name' => $file_name,'metadata' => $metadata])){
			printf('Uploaded %s to gs://%s/%s' . PHP_EOL, basename($file_path), $bucketName, $file_name);
			unlink($file_path);
			$result = true;
		}
		else {
			printf('Failed uploaded %s to gs://%s/%s' . PHP_EOL, basename($file_path), $bucketName, $file_name);
		}
	}

	return $result;
}