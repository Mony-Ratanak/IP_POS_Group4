<?php

namespace App\Http\Controllers\Admin;

// ============================================================================>> Core Library
use Illuminate\Http\Response; // For Responsing data back to Client

// ============================================================================>> Custom Library
// Controller
use App\Http\Controllers\MainController;

// Model
use App\Models\Order\Order;
use App\Models\Product\Product;
use Illuminate\Support\Facades\DB;


class DashboardController extends MainController
{
    public function getDashboardInfo()
    {
        // Total sale today
        $totalSaleToday = Order::sum('total_price');

        // Total orders
        $orderCount = Order::count();

        // Most ordered products with details
        $mostOrderedProducts = DB::table('order_details')
            ->select('product_id', DB::raw('SUM(qty) as total_ordered'))
            ->groupBy('product_id')
            ->orderByDesc('total_ordered')
            ->limit(6) // Adjust limit as needed
            ->get();

            $lastTenOrderProducts = DB::table('order_details')
            ->join('product', 'order_details.product_id', '=', 'product.id')
            ->select(
                'order_details.product_id',
                'product.name as product_name',
                'order_details.unit_price',
                'order_details.qty'
            )
            ->orderByDesc('order_details.id')
            ->limit(10)
            ->get();

        // Fetch details for each product
        $productsDetails = [];
        foreach ($mostOrderedProducts as $product) {
            $productDetails = Product::find($product->product_id);
            if ($productDetails) {
                $productDetails->total_ordered = $product->total_ordered;
                $productsDetails[] = $productDetails;
            }
        }

        $data = [
            'total_sale_today' => $totalSaleToday,
            'total_orders' => $orderCount,
            'most_ordered_products' => $productsDetails,
            'lastTenOrderProducts' => $lastTenOrderProducts,
        ];

        return response()->json($data, Response::HTTP_OK);
    }
}
