<?php

namespace App\Http\Controllers\Admin;

// ================================>> Core Library
use Illuminate\Http\Request;
use Illuminate\Http\Response;

// ================================>> Cistom Library
use App\Http\Controllers\MainController;
use App\Models\Product\Product;
use App\Models\Product\Type;

class ProductTypeController extends MainController
{
    //get data 
    public function listing(Request $req)
    {

        // select in product type
        $data = Type::select('id', 'name', 'created_at')
        ->withCount(['products as n_of_products'])
        // ->orderBy('name', 'ASC')
        ->get();

        // response to client
        return response()->json($data, Response::HTTP_OK);
    }

    // create new product type
    public function create(Request $req)
    {
        //==============================>> Check validation
        $this->validate(
            $req,
            [
                'name'             => 'required|max:20',
            ],
            [
                'name.required'    => 'សូមបញ្ចូលឈ្មោះប្រភេទផលិតផល',
                'name.max'         => 'ឈ្មោះប្រភេទផលិតផលមិនអាចលើសពី២០ខ្ទង់',
            ]
        );

        // save data to database
        $product_type = new Type;
        $product_type->name = $req->name;
        $product_type-> save();

        return response()->json([

            // show data type that recently create
            'product_type'  => $product_type,
            'message'       => 'ទិន្នន័យត្រូវបានបង្កើតដោយជោគជ័យ។'

        ], Response::HTTP_OK);
    }

    public function update(Request $req , $id = 0)
    {
        //==============================>> Check validation
        $this->validate(

            $req,
            [
                'name'             => 'required|max:20',
            ],
            [
                'name.required'    => 'សូមបញ្ចូលឈ្មោះប្រភេទផលិតផល',
                'name.max'         => 'ឈ្មោះប្រភេទផលិតផលមិនអាចលើសពី២០ខ្ទង់',
            ]
        );

        // find product_type by id
        $product_type = Type::find($id);
        // ->withCount([
        //     'products as n_of_products'
        // ])
        // ->first();

        // check condition if true 
        if ($product_type) {

            $product_type->name = $req->name;
            $product_type->save();

            // back up to client
            return response()->json([

                'status'        => 'ជោគជ័យ',
                'message'       => 'ប្រភេទផលិតផលត្រូវបានកែប្រែជោគជ័យ!',
                'product_type'  => $product_type,
            ], Response::HTTP_OK);

        } 
        else { // if no

            return response()->json([
                'status'    => 'បរាជ័យ',
                'message'   => 'ទិន្នន័យមិនត្រឹមត្រូវ',
            ], Response::HTTP_BAD_REQUEST);

        }
    }

    public function delete($id = 0)
    {
        $data = Type::find($id);

        // if data true
        if ($data) {

            $data->delete();
            return response()->json([
                'status'    => 'ជោគជ័យ',
                'message'   => 'ទិន្នន័យត្រូវបានលុប'
            ], Response::HTTP_OK);

        } else {

            return response()->json([
                'status'    => 'បរាជ័យ',
                'message'   => 'ទិន្នន័យមិនត្រឹមត្រូវ',
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
