<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Http;
use App\Http\Controllers\MainController;
use App\Models\Order\Order;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

// class PrintController extends MainController
// {
//     public function printInvoiceOrder($receipt_number = 0)
//     {
//         $template = env('JS_TEMPLATE');

//         $receipt = Order::where('receipt_number', $receipt_number)->first();

//         if (!$receipt) {
//             throw new BadRequestException("Invalid receipt number");
//         }

//         $dataFormat = [
//             'receipt_number' => $receipt->receipt_number,
//             'total_price' => $receipt->total_price,
//             'ordered_at' => Carbon::parse($receipt->ordered_at)->format('Y-m-d H:i:s'),
//             'cashier' => $receipt->cashier ? ['id' => $receipt->cashier->id, 'name' => $receipt->cashier->name] : [],
//             'details' => $receipt->details($receipt->details),
//         ];
//         $result = $this->print($template, $dataFormat);
//         if (isset($result->error)) {
//             return response()->json([
//                 'status' => 'error',
//                 'message' => $result->error,
//             ], 500);
//         }
//     }

//     private function details($details)
//     {
//         $data = [];
//         if ($details->count()) {
//             foreach ($details as $detail) {
//                 $data[] = [
//                     'product_id' => $detail->product_id,
//                     'qty' => $detail->qty,
//                     'unit_price' => $detail->unit_price,
//                     'product' => $this->product($detail->product)
//                     // 'total' => $detail->total,
//                 ];
//             }
//         }
//         return $data;
//     }

//     private function product($product)
//     {
//         $data = null;
//         if ($product) {
//             $data = [
//                 'id' => $product->id,
//                 'name' => $product->name ?? null,
//                 'image' => $product->image ?? null,
//             ];
//         }

//         return $data;
//     }
// }





class PrintController extends MainController
{
    //====================Global variable====================
    private $JS_BASE_URL;
    private $JS_USERNAME;
    private $JS_PASSWORD;
    private $JS_TEMPLATE;

    // Constructor method to initialize the global variables
    public function __construct()
    {

        // Retrieve values for global variables from environment variables
        $this->JS_BASE_URL   = env('JS_BASE_URL');
        $this->JS_USERNAME   = env('JS_USERNAME');
        $this->JS_PASSWORD   = env('JS_PASSWORD');
    }

    public function printInvoiceOrder($receipt_number = 0)
    {
        try {
            // Prepare the request body with template information and data for the report
            $body = [

                "template" => [
                    "name" => '/Invoice/main', // Name or path of the template
                ],
                "data" => $this->_getReceiptData($receipt_number), // Retrieve data for the report
            ];
            // return $body;

            // Send a POST request to the JavaScript service API endpoint for report generation
            $response = Http::withBasicAuth($this->JS_USERNAME, $this->JS_PASSWORD)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->JS_BASE_URL . '/api/report', $body);

            // Return a response containing the base64-encoded file and no error if successful
            return [

                'file_base64' => base64_encode($response),
                'error'       => '',
            ];
        } catch (\Exception $e) {

            // Handle exceptions and return an error message
            return [
                'file_base64' => '',
                'error'       => $e->getMessage(),
            ];
        }
    }

    private function _getReceiptData($receiptNumber = 0)
    {
        try {
            // Retrieve order data with specific columns and relationships from the database
            $data = Order::select('id', 'receipt_number', 'cashier_id', 'total_price', 'ordered_at')
                ->with([
                    'cashier',  // Relationship with cashier (M:1)
                    'details' => function ($query) {
                        $query->select('id', 'order_id', 'qty', 'product_id', 'unit_price')
                            ->with([
                                'product:id,name,image'
                            ]);
                    }
                ])
                ->where('receipt_number', $receiptNumber)  // Condition to filter by receipt number
                ->orderBy('id', 'desc') // Order
                ->first();  // Retrieve the first matching record

            // Return the retrieved data
            return $data;
        } catch (\Exception $e) {
            // Handle the exception
            // If an exception occurs, return an array with total = 0, empty data array, and the error message
            return [
                'total' => 0,
                'data'  => [],
                'error' => $e->getMessage(),
            ];
        }
    }
    // private function _getReceiptData($receiptNumber = 0)
    // {
    //     try {
    //         // ===>> Get Data from DB
    //         // Retrieve order data with specific columns and relationships from the database
    //         $data = Order::select('id', 'receipt_number', 'cashier_id', 'total_price', 'ordered_at')

    //             ->with([
    //                 'cashier',  // Relationship with cashier (M:1)
    //                 'details'   // Relationship with order details (1:M)
    //             ])
    //             ->where('receipt_number', $receiptNumber)  // Condition to filter by receipt number
    //             ->orderBy('id', 'desc') // Order
    //             ->first();  // Retrieve the first matching record

    //         // Return the retrieved data
    //         // Find Total Price
    //         $totalPrice = 0;
    //         foreach ($data as $row) {
    //             $totalPrice += $row->total_price;
    //         }


    //         return $data;
    //     } catch (\Exception $e) {

    //         // ===> Handle the exception
    //         // If an exception occurs, return an array with total = 0, empty data array, and the error message
    //         return [

    //             'total' => 0,
    //             'data'  => [],
    //             'error' => $e->getMessage(),
    //         ];
    //     }
    // }
}


// class PrintController extends MainController
// {
//     private $JS_BASE_URL;
//     private $JS_USERNAME;
//     private $JS_PASSWORD;
//     private $JS_TEMPLATE;

//     public function __construct()
//     {
//         $this->JS_BASE_URL   = env('JS_BASE_URL');
//         $this->JS_USERNAME   = env('JS_USERNAME');
//         $this->JS_PASSWORD   = env('JS_PASSWORD');
//         $this->JS_TEMPLATE   = env('JS_TEMPLATE');
//     }

//     public function printInvoiceOrder($receiptNumber = 0)
//     {
//         try {

//             // URL Preparation

//             $url = $this->JS_BASE_URL . "/api/report";
//             // Debugging: Log the constructed URL
//             info("Constructed URL: $url");

//             // Get Data from DB
//             $receipt = Order::select('id', 'receipt_number', 'cashier_id', 'total_price', 'ordered_at')
//                 ->with([
//                     'cashier', // M:1
//                     'details' // 1:M
//                 ])
//                 ->where('receipt_number', $receiptNumber)
//                 ->orderBy('id', 'desc')
//                 ->get();

//             // Find Total Price
//             $totalPrice = 0;
//             foreach ($receipt as $row) {
//                 $totalPrice += $row->total_price;
//             }

//             // Prepare Payload for JS Report Service
//             $payload = [
//                 "template" => [
//                     "name" => $this->JS_TEMPLATE,
//                 ],
//                 "data" => [
//                     'total' => $totalPrice,
//                     'data'  => $receipt,
//                 ],
//             ];

//             // Send Request to JS Report Service
//             $response = Http::withBasicAuth($this->JS_USERNAME, $this->JS_PASSWORD)
//                 ->withHeaders([
//                     'Content-Type' => 'application/json',
//                 ])
//                 ->post($url, $payload);

//             // Success Response Back to Client
//             return [
//                 'file_base64'   => base64_encode($response),
//                 'error'         => '',
//             ];
//         } catch (\Exception $e) {
//             // Handle the exception
//             return [
//                 'file_base64' => '',
//                 'error' => $e->getMessage(),
//             ];
//         }
//     }
// }



// namespace App\Http\Controllers\Print;

// use App\Http\Controllers\MainController;
// use Illuminate\Http\Request;
// use App\Models\Order\Order;
// use Tymon\JWTAuth\Facades\JWTAuth;


// class printController extends MainController
// {
//     public function printInvoiceOrder($receipt_number = 0)
//         {
//             return $this->getData($receipt_number);
//             $header = array(
//                 'Accept: application/json',
//                 'Authorization: Basic U29uZ2hhazowMTIyNjM1NjI=',
//                 'Content-Type: application/json',
//                 'Cookie: render-complete=true'
//             );
//             $curl = curl_init();
//             curl_setopt_array($curl, array(
//                 CURLOPT_URL => 'https://jsreport.hychin.tech/api/report',
//                 CURLOPT_RETURNTRANSFER => true,
//                 CURLOPT_ENCODING => '',
//                 CURLOPT_MAXREDIRS => 10,
//                 CURLOPT_TIMEOUT => 0,
//                 CURLOPT_FOLLOWLOCATION => true,
//                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                 CURLOPT_CUSTOMREQUEST => 'POST',
//                 CURLOPT_POSTFIELDS => json_encode($this->getData($receipt_number)),
//                 CURLOPT_HTTPHEADER => $header,
//             ));

//             $response = curl_exec($curl);
//             $error = curl_error($curl);
//             curl_close($curl);

//             return [
//                 'file_base64' => base64_encode($response),
//                 'error' => $error
//             ];
//         }

//         public static function getData($receipt_number = 0)
//         {
//             $data  = Order::select('id', 'receipt_number', 'cashier_id', 'total_price', 'ordered_at')
//                 ->where('receipt_number', $receipt_number)
//                 ->with([
//                     'cashier',
//                     'details'

//                 ]);

//             $data = $data->orderBy('id', 'desc')->get();

//             $total = 0;
//             foreach ($data as $row) {
//                 $total += $row->total_price;
//             }

//             $payload = [
//                 'total'         => $total,
//                 'data'          => $data,

//             ];
//             $template = [
//                 "template" => [
//                     "name" => "/POS-Order/POS-Inovice",
//                 ],
//                 "data" => $payload,
//             ];
//             return $template;
//         }
// }
