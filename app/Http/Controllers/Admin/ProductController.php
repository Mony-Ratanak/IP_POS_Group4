<?php

namespace App\Http\Controllers\Admin;

// ================================>> Core Library
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Carbon\Carbon;

// ================================>> Third party library
use Tymon\JWTAuth\Facades\JWTAuth;

// ================================>> Custom Library
use App\Services\FileUpload;
use App\Http\Controllers\MainController;
use App\Models\Product\Product;
use App\Models\Order\Order;
use Psy\Readline\Hoa\Console;

class ProductController extends MainController
{
    //get data
    // public function listing(Request $req)
    // {

    //     $data = Product::select('*')->with(['type']);

    //     //Filter
    //     if ($req->key && $req->key != '') {
    //         $data = $data->where('code', 'LIKE', '%' . $req->key . '%')->Orwhere('name', 'LIKE', '%' . $req->key . '%');
    //     }

    //     if ($req->type && $req->type != 0) {
    //         $data = $data->where('type_id', $req->type);
    //     }

    //     // Format the created_at field for each product in the collection
    // $data->each(function ($product) {
    //     $product->created_at = date('Y-m-d H:i:s', strtotime($product->created_at));
    // });

    //     $data = $data->orderBy('id', 'desc')
    //         ->paginate($req->limit ? $req->limit : 10, 'per_page');

    //     return response()->json($data, Response::HTTP_OK);
    // }

    public function listing(Request $req)
    {
        $data = Product::select('*')->with(['type']);

        // Filter
        if ($req->key && $req->key != '') {
            $data = $data->where('code', 'LIKE', '%' . $req->key . '%')
                ->orWhere('name', 'LIKE', '%' . $req->key . '%');
        }

        if ($req->type && $req->type != 0) {
            $data = $data->where('type_id', $req->type);
        }

        // Apply ordering and pagination
        $data = $data->orderBy('id', 'desc')->paginate($req->limit ? $req->limit : 10, ['*'], 'page');

        // Transform the collection to format the dates
        $data->getCollection()->transform(function ($product) {
            $product->created_at_formatted = Carbon::parse($product->created_at)->format('Y-m-d H:i:s');
            $product->updated_at_formatted = Carbon::parse($product->updated_at)->format('Y-m-d H:i:s');
            return $product;
        });

        // Convert the data to an array and then to JSON
        $dataArray = $data->toArray();
        foreach ($dataArray['data'] as &$item) {
            $item['created_at'] = $item['created_at_formatted'];
            $item['updated_at'] = $item['updated_at_formatted'];
            unset($item['created_at_formatted'], $item['updated_at_formatted']);
        }

        return response()->json($dataArray, Response::HTTP_OK);
    }

    // listing 1 product by id
    public function view($id = 0)
    {

        $data = Product::select('*')->find($id);

        // check condition if yes
        if ($data) {
            return response()->json($data, Response::HTTP_OK);
        } else {
            return response()->json([

                'status'    => 'fil',
                'message'   => 'no data',

            ], Response::HTTP_BAD_REQUEST);
        }
    }

    // create new product
    public function create(Request $req)
    {
        // check validation
        $this->validate(
            $req,
            [
                'name'                  => 'required|max:50',
                'code'                  => 'required|max:20',
                'unit_price'            => 'required|numeric',
                'type_id'               => 'required|exists:products_type,id',
                'des'                   => 'required|max:50',
                'quantity'              => 'required|numeric',
            ],
            [
                'name.required'         => 'សូមបញ្ចូលឈ្មោះផលិតផល',
                'name.max'              => 'ឈ្មោះផលិតផលមិនអាចលើសពី50ខ្ទង់',

                'code.required'         => 'សូមបញ្ចូលឈ្មោះលេខកូដផលិតផល',
                'code.max'              => 'សូមបញ្ចូលឈ្មោះលេខកូដផលិតផលមិនអាចលើសពី២០ខ្ទង់',

                'unit_price.required'   => 'សូមបញ្ចូលតម្លៃរាយ',
                'unit_price.numeric'    => 'សូមបញ្ចូលតម្លៃរាយជាលេខ',

                'type_id.exists'        => 'សូមជ្រើសរើសឈ្មោះផលិតផល អោយបានត្រឹមត្រូវ កុំបោកពេក'

            ]
        );

        //start saving data to database
        $pro        = new Product;
        $pro->name     = $req->name;
        $pro->code     = $req->code;
        $pro->des      = $req->des;
        $pro->quantity = $req->quantity;
        $pro->type_id  = $req->type_id;
        $pro->unit_price = $req->unit_price;

        $pro->save();

        // update image
        if ($req->image) {

            // need to create folder before storing image
            $folder = Carbon::today()->format('d-m-y');
            $image  = FileUpload::uploadFile($req->image, 'products/' . $folder, $req->fileName);

            $pro->image = $image['url'];

            $pro->save();
        }

        return response()->json([

            'data'      => Product::select('*')
                ->with(['type'])
                ->find($pro->id),
            'message'   => 'create prduct is success'

        ], Response::HTTP_OK);
    }

    public function update(Request $req, $id = 0)
    {
        //==============================>> Check validation
        $this->validate(
            $req,
            [
                'name'                  => 'required|max:50',
                'code'                  => 'required|max:20',
                'unit_price'            => 'required|numeric',
                'type_id'               => 'required|exists:products_type,id',
                'des'                   => 'required|max:50',
                'quantity'              => 'required|numeric',
            ],
            [
                'name.required'         => 'សូមបញ្ចូលឈ្មោះផលិតផល',
                'name.max'              => 'ឈ្មោះផលិតផលមិនអាចលើសពី50ខ្ទង់',

                'code.required'         => 'សូមបញ្ចូលឈ្មោះលេខកូដផលិតផល',
                'code.max'              => 'សូមបញ្ចូលឈ្មោះលេខកូដផលិតផលមិនអាចលើសពី២០ខ្ទង់',

                'unit_price.required'   => 'សូមបញ្ចូលតម្លៃរាយ',
                'unit_price.numeric'    => 'សូមបញ្ចូលតម្លៃរាយជាលេខ',

                'type_id.exists'        => 'សូមជ្រើសរើសឈ្មោះផលិតផល អោយបានត្រឹមត្រូវ កុំបោកពេក'

            ]
        );

        //==============================>> Start Updating data
        $product = Product::find($id);

        if ($product) {
            $product->name          = $req->name;
            $product->code          = $req->code;
            $product->type_id       = $req->type_id;
            $product->quantity      = $req->quantity;
            $product->unit_price    = $req->unit_price;
            $product->des           = $req->des;

            // save to database
            $product->save();

            // Image Upload
            if ($req->image) {

                // Need to create folder before storing images
                //$folder = Carbon::today()->format('d') . '-' . Carbon::today()->format('M') . '-' . Carbon::today()->format('Y');
                $folder = Carbon::today()->format('d-m-y');

                //return $folder;

                $image  = FileUpload::uploadFile($req->image, 'products/', $req->fileName);

                //return $image;

                if ($image['url']) {

                    $product->image     = $image['url'];
                    $product->save();
                }
            }

            // Prepare Data back to Client
            $product = Product::select('*')
                ->with([
                    'type'
                ])


                ->find($product->id);

            return response()->json([

                'status'    => 'ជោគជ័យ',
                'message'   => 'ផលិតផលត្រូវបានកែប្រែជោគជ័យ',
                'product'   => $product,

            ], Response::HTTP_OK);
        } else {

            return response()->json([

                'status'    => 'បរាជ័យ',
                'message'   => 'ទិន្នន័យមិនត្រឹមត្រូវ',

            ], Response::HTTP_BAD_REQUEST);
        }
    }

    // delete product
    public function delete($id = 0)
    {
        $data = Product::find($id);

        //check condition
        if ($data) {

            $data->delete();
            return response()->json([
                'status'        => 'ជោគជ័យ',
                'message'       => 'ទិន្នន័យត្រូវបានលុប',
            ], Response::HTTP_OK);
        } else {
            return response()->json([

                'status'        => 'បរាជ័យ',
                'message'       => 'ទិន្នន័យមិនត្រឹមត្រូវ',

            ], Response::HTTP_BAD_REQUEST);
        }
    }

    // transaction
    public function getProduct($id = 0, Request $req)
    {
        try {

            // Fetch a specific product by ID
            $product = Product::with(['orderDetails.order.cashier'])->find($id);


            if (!$product) {
                // Return an error response if the product is not found
                return response()->json(['error' => 'Product not found.'], Response::HTTP_NOT_FOUND);
            }

            // Date Range Filter
            if ($this->isValidDate($req->from) && $this->isValidDate($req->to)) {
                $product->orderDetails = $this->filterOrderDetailsByDateRange($product->orderDetails, $req->from, $req->to);
            }

            // Filter by receipt number if provided in the request
            if ($req->filled('receipt_number')) {
                $product->orderDetails = $this->filterOrderDetailsByReceiptNumber($product->orderDetails, $req->input('receipt_number'));
            }

            // Get the limit from the request or use a default of 10
            $limit = $req->input('limit', 10);

            // Paginate the orderDetails array with the specified limit
            $orderDetails = $this->paginateOrderDetails($product->orderDetails, $limit, $req->input('page', 1));

            // Append the paginated orderDetails to the product
            $product->setRelation('orderDetails', $orderDetails);

            // Return the product along with its paginated and ordered orderDetails
            return response()->json($product, Response::HTTP_OK);
        } catch (\Exception $e) {

            // Handle any exceptions, if needed
            return response()->json([
                'status' => 'បរាជ័យ',
                'message' => 'ទិន្នន័យមិនត្រឹមត្រូវ',
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    // Helper function to check if a date is valid
    private function isValidDate($date)
    {
        return $date && strtotime($date) !== false;
    }

    // Helper function to filter orderDetails by date range
    private function filterOrderDetailsByDateRange($orderDetails, $from, $to)
    {
        return $orderDetails->filter(function ($orderDetail) use ($from, $to) {

            $orderedAt      = strtotime($orderDetail->order->ordered_at);
            $fromTimestamp  = strtotime($from . " 00:00:00");
            $toTimestamp    = strtotime($to . " 23:59:59");
            return $orderedAt >= $fromTimestamp && $orderedAt <= $toTimestamp;
        });
    }

    // Helper function to filter orderDetails by receipt number
    private function filterOrderDetailsByReceiptNumber($orderDetails, $receiptNumber)
    {
        return $orderDetails->filter(function ($orderDetail) use ($receiptNumber) {
            return $orderDetail->order->receipt_number == $receiptNumber;
        });
    }

    // Helper function to paginate orderDetails
    private function paginateOrderDetails($orderDetails, $limit, $page)
    {
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $orderDetails->values()->forPage($page, $limit),
            count($orderDetails),
            $limit,
            $page
        );
    }
}
