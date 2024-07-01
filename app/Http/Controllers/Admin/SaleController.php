<?php

namespace App\Http\Controllers\Admin;

// ================================>> Core Library
use Illuminate\Http\Request;
use Illuminate\Http\Response;

// ================================>> Third party library
use Tymon\JWTAuth\Facades\JWTAuth;

// ================================>> Cistom Library
use App\Http\Controllers\MainController;
use App\Models\Order\Order;

class SaleController extends MainController
{

    // Check if the given date can be successfully parsed using strtotime
    private function _isValidData($date)
    {
        if (false === strtotime($date)) {

            // If strtotime returns false, the date is not valid   
            return false;
        } else {
            // If strtotime is successful, the date is valid
            return true;
        }
    }

    public function listing(Request $req)
    {
        // Initialize the query to select all orders with related cashier and order details
        $data = Order::select('*')
            ->with([
                'cashier', //M:1
                'details'   // 1:M
            ]);
        // ->orderBy('id', 'desc')
        // ->paginate($req->limit ? $req->limit : 10);

        // ==============================>> Date Range
        // Check if valid 'from' and 'to' dates are provided, then filter orders within the date range
        if ($req->from && $req->to && $this->isValidDate($req->from) && $this->isValidDate($req->to)) {
            $data = $data->whereBetween('created_at', [$req->from . " 00:00:00", $req->to . " 23:59:59"]);
        }

        // =========================== Search receipt number
        // Check if receipt_number is provided, then filter orders with the specific receipt number
        if ($req->receipt_number && $req->receipt_number != "") {
            $data = $data->where('receipt_number', $req->receipt_number);
        }

        // ========================== search filter status
        // Assuming this is meant to filter by receipt_number, use the correct condition
        if ($req->status) {
            $data = $data->where('receipt_number', $req->status);
        }

        // ===========================>> If Not admin, get only records that this user made orders
        $user = JWTAuth::parseToken()->authenticate();
        if ($user->type_id == 2) { // Manager
            $data = $data->where('cashier_id', $user->id);
        }

        // Order the results by id in descending order and apply pagination
        $data = $data->orderBy('id', 'desc')
            ->paginate($req->limit ? $req->limit : 10);

        // Return the paginated result as a JSON response
        return response()->json($data, Response::HTTP_OK);
    }

    // delete sale in order
    public function delete($id = 0)
    {
        // Find the order by ID
        $data = Order::find($id);

        //==============================>> Start deleting data
        // Check if the order data exists
        if ($data) {
            // If the order data exists, delete it
            $data->delete();

            // Return a JSON response indicating success
            return response()->json([

                'status'  => 'ជោគជ័យ',
                'message' => 'ទិន្នន័យត្រូវបានលុប',
            ], Response::HTTP_OK);
        } else {
            // If the order data does not exist, return a JSON response indicating failure
            return response()->json([

                'status'  => 'បរាជ័យ',
                'message' => 'ទិន្នន័យមិនត្រឹមត្រូវ។',

            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
