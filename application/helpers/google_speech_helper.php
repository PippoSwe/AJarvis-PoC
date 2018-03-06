<?php defined('BASEPATH') OR exit('No direct script access allowed');

require FCPATH . '/vendor/autoload.php';

use Google\Cloud\Speech\SpeechClient;
use Google\Cloud\Storage\StorageClient;
use Google\Cloud\Core\ExponentialBackoff;

/**
 * Transcribe an audio file using Google Cloud Speech API
 * Example:
 * ```
 * transcribe_async_gcs('your-bucket-name', 'audiofile.wav');
 * ```.
 *
 * @param string $objectName The Cloud Storage object name.
 * @param string $languageCode The Cloud Storage
 *     be recognized. Accepts BCP-47 (e.g., `"en-US"`, `"es-ES"`).
 * @param array $options configuration options.
 *
 * @return string the text transcription
 */
function transcribe_async_gcs($objectName, $languageCode = 'it-IT', $options = ['encoding' => 'FLAC', 'sampleRateHertz' => 48000, 'singleUtterance' => true])
{
    $CI = & get_instance();
    $CI->config->load('google_cloud');

    $bucketName  = $CI->config->item('audio_bucket_name');
    $keyFilePath = $CI->config->item('key_file_path');

    // Create the speech client
    $speech = new SpeechClient([
        'languageCode' => $languageCode,
        'keyFilePath'  => $keyFilePath
    ]);

    // Fetch the storage object
    $storage = new StorageClient([
        'keyFilePath' => $keyFilePath
    ]);

    $object = $storage->bucket($bucketName)->object($objectName);

    // Create the asyncronous recognize operation
    $operation = $speech->beginRecognizeOperation(
        $object,
        $options
    );

    while(!$operation->isComplete()){
        sleep(100);
        $operation->reload();
    }

    $return = ['<<< Nessunoo ha parlato >>>'];

    // Print the results
    if ($operation->isComplete() && count($operation->results()) > 0) {
        $results = $operation->results();
        $return  = $results[0]->alternatives()[0];
    }

    echo '<pre>';
    print_r($return);
    echo '</pre>';
    $return = substr($return, 0, 1 ) .  preg_replace('/[A-Z]/', '. $0',substr($return, 1 ));

    return json_encode($return);
}


/**
 * Transcribe an audio file using Google Cloud Speech API
 * Example:
 * ```
 * transcribe_sync_gcs('your-bucket-name', 'audiofile.wav');
 * ```.
 *
 * @param string $languageCode The language of the content to
 *     be recognized. Accepts BCP-47 (e.g., `"en-US"`, `"es-ES"`).
 * @param array $options configuration options.
 *
 * @return string the text transcription
 */
function transcribe_sync_gcs($objectName, $languageCode = 'it-IT', $options = ['encoding' => 'FLAC', 'sampleRateHertz' => 48000, 'singleUtterance' => true])
{
    $CI = & get_instance();
    $CI->config->load('google_cloud');

    $bucketName  = $CI->config->item('audio_bucket_name');
    $keyFilePath = $CI->config->item('key_file_path');

    // Create the speech client
    $speech = new SpeechClient([
        'languageCode' => $languageCode,
        'keyFilePath'  => $keyFilePath
    ]);

    // Fetch the storage object
    $storage = new StorageClient([
        'keyFilePath' => $keyFilePath
    ]);

    $object = $storage->bucket($bucketName)->object($objectName);

    // Make the API call
    $results = $speech->recognize(
        $object,
        $options
    );

    $return = array('transcript' => '');

echo '<pre>';
print_r($results);
echo '</pre>';

    // fetch and return the result
    foreach ($results as $result) {
        $alternative = $result->alternatives()[0];
        $return['transcript'] .= $alternative['transcript'] . '.';
    }

    $return['transcript'] = substr($return['transcript'], 0, 1 ) .  preg_replace('/[A-Z]/', '. $0',substr($return['transcript'], 1 ));

    return json_encode($return);
}
?>