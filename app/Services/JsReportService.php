<?php

namespace App\Services;

use illuminate\Support\Facades\Http;

trait JsReportService
// {
//     protected function print ($template, $data)
//     {
//         $JS_BASE_URL   = env('JS_BASE_URL', 'http://localhost:5488);
//         $JS_USERNAME   = env('JS_USERNAME' ,'admin');
//         $JS_PASSWORD   = env('JS_PASSWORD', 'admin');
//         $result = new \stdClass();

//         try{
//         $response = Http::withBasicAuth($JS_USERNAME, $JS_PASSWORD)->post($JS_BASE_URL. '/print', [
//             'template' => $template,
//             'data'     => $data
//         ]);
//         }
//     }
// }