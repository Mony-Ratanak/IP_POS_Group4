<?php

namespace App\Services;

class FileUpload
{

    public static function uploadFile($file, $folder, $fileName)
    {

        // Data to be sent in the POST request
        $data = [

            'project'   => 'minimart',
            'file'      => $file,
            'folder'    => $folder,
            'fileName'  => $fileName
        ];

        // Initialize cURL session
        $curl = curl_init();

        // Set cURL options
        curl_setopt_array($curl, array(

            CURLOPT_URL => env('FILE_URL') . "/api/set-file",
            // CURLOPT_URL => "http://file:8000/api/set-file",

            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => "",
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 60,
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => "POST",
            CURLOPT_POSTFIELDS      => $data,
            CURLOPT_FAILONERROR     => true,

            CURLOPT_HTTPHEADER      => array(
                "Accept: application/json",
            ),
        ));

        // Execute cURL session and get the response
        $response = curl_exec($curl);

        // Check for cURL errors
        if (curl_errno($curl)) {
            return   ['url' => ""];
        }

        // Close cURL session
        curl_close($curl);

        // Decode the JSON response
        return   json_decode($response, true);
    }
}
