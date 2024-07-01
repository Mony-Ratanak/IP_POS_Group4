<?php

namespace App\Http\Controllers\Admin;

// ================================>> Custom Library
use App\Http\Controllers\MainController;
use App\Models\Product\Product;
use App\Models\Order\Detail;
use App\Models\Order\Order;
use App\Models\Product\Type as ProductType;
use App\Services\TelegramService;

//====================================>> Third Party Library
use Tymon\JWTAuth\Facades\JWTAuth;

// ================================>> Core Library
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class POSController extends MainController
{

    // get product
    public function getProducts()
    {
        // Select product types along with their associated products
        $data = ProductType::select('id', 'name')
            ->with([

                // Eager load products related to each product type
                'products:id,name,image,type_id,unit_price'
            ])
            ->get();

        // Return the result as a JSON response with HTTP status code 200 (OK)
        return response()->json($data, Response::HTTP_OK);
    }

    private function _generateReceiptNumber()
    {

        // Generate a random 7-digit number
        $number = rand(1000000, 9999999);

        // Check if the generated number already exists in the 'receipt_number' column of the 'orders' table
        $check  = Order::where('receipt_number', $number)->first();

        // If the number already exists, recursively call the function to generate a new number
        if ($check) {
            return $this->_generateReceiptNumber();
        }

        // If the number is unique, return it
        return $number;
    }

    private function _sendNotification($orderData = null)
    {

        // Prepare the initial message with basic order information
        $htmlMessage = "<b>Your order is sucessful!</b>\n";
        $htmlMessage = "- Receipt number: " . $orderData->receipt_number . "\n";
        $htmlMessage = "- Cashier: " . $orderData->cashier->name;

        // Prepare the product list section
        $productList  = '';
        $totalProduct = 0;

        // Iterate through order details to create a formatted product list
        foreach ($orderData->details as $detail) {

            $productList =  sprintf(
                "%-20s | %-15s | %-10s | %s\n",
                $detail->product->name,
                $detail->unit_price,
                $detail->qty,
                PHP_EOL
            );

            // Calculate the total quantity of products
            $totalProduct += $detail->qty;
        }

        // Add the formatted product list to the message
        $htmlMessage .= "\n---------------------------------------\n";
        $htmlMessage .= "Product             | Price($)     | Quantity\n";
        $htmlMessage .= $productList . "\n";
        $htmlMessage .= "<b>* Total product:</b> $totalProduct total $orderData->total_price $\n";
        $htmlMessage .= "- Date: " . $orderData->ordered_at;

        // Send the notification message using a TelegramService (assumed but not provided)
        TelegramService::sendMessage($htmlMessage);
    }

    public function makeOrder(Request $req)
    {
        // ===>> Check validation
        $this->validate($req, [
            'cart'      => 'required|json'
        ]);

        // ===>> Get currently logged in user to save who make order
        $user = JWTAuth::parseToken()->authenticate();

        // ===>> Create order
        $order                  = new Order;
        $order->cashier_id      = $user->id;
        $order->total_price     = 0;
        $order->receipt_number  = $this->_generateReceiptNumber();
        $order->save();

        // ===>> Find total price & order detail
        $details        = [];
        $totalPrice     = 0;
        $cart           = json_decode($req->cart); // Decode JSON string to PHP array

        foreach ($cart as $productId => $qty) {

            $product = Product::find($productId);

            if ($product && is_numeric($qty)) {

                $details[] = [
                    'order_id'      => $order->id,
                    'product_id'    => $productId,
                    'qty'           => $qty,
                    'unit_price'    => $product->unit_price,
                ];

                $totalPrice += $qty * $product->unit_price;
            }
        }

        // ===>> Save to detail
        Detail::insert($details);
        // if (!empty($details)) {
        //     Detail::insert($details);
        // } else {
        //     return response()->json([
        //         'message'   => 'Order creation failed: no valid products found in cart',
        //     ], Response::HTTP_BAD_REQUEST);
        // }

        // ===>> Update order
        $order->total_price     = $totalPrice;
        $order->ordered_at      = Date('Y-m-d H:i:s');
        $order->save();

        // ===>> Get data for client response
        $orderData = Order::select('*')
            ->with([
                'cashier:id,name,type_id', // M:1
                'cashier.type:id,name',  // M:1

                'details:id,order_id,product_id,unit_price,qty', // 1:M

                'details.product:id,name,type_id', // M:1 (order_details -> product)

                'details.product.type:id,name'  // M:1 (product -> products_type)
            ])
            ->find($order->id);
        // $orderData->load('details');

        // ===>> Send notification
        $this->_sendNotification($orderData);

        return response()->json([
            'order'     => $orderData,
            'message'   => 'Order has been created successfully',
        ], Response::HTTP_OK);
    }
}
