<?php

namespace  App\Http;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as HttpStatus;

/**
 * @author Yim Klok <yimklok.kh@gmail.com>
 */
class ResponseSuccess extends JsonResponse
{
    public function __construct($data = null, $message = null, $pagination = null)
    {
        // Prepare the basic response data
        $responseData = [
            'status_code' => HttpStatus::HTTP_OK
        ];

        // If an data is provided, add it to the response
        if ($data !== null) {
            $responseData['data'] = $data;
        }

        // If an message is provided, add it to the response
        if ($message !== null) {
            $responseData['message'] = $message;
        }

        // If an pagination is provided, add it to the response
        if ($pagination !== null) {
            $responseData['pagination'] = $pagination;
        }

        // Create an ResponseSuccess with the desired response.
        parent::__construct($responseData, HttpStatus::HTTP_OK);
    }
}
