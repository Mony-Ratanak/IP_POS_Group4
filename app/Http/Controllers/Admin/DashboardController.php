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
        $totalNumberOfCustomer = DB::table('user')
            ->where('type_id', 3)
            ->count();

        // Most ordered products with details
        $mostOrderedProducts = DB::table('order_details')
            ->select('product_id', DB::raw('SUM(qty) as total_ordered'))
            ->groupBy('product_id')
            ->orderByDesc('total_ordered')
            ->limit(6) // Adjust limit as needed
            ->get();

        $lastTenOrderProducts = DB::table('order_details')
            ->join('order', 'order_details.order_id', '=', 'order.id')
            ->join('user as customer', 'order.customer_id', '=', 'customer.id')
            ->join('product', 'order_details.product_id', '=', 'product.id')
            ->select(
                'order.customer_id',
                'customer.avatar as avatar',
                'customer.name as customer_name',
                'order_details.product_id',
                'product.name as product_name',
                'order_details.unit_price',
                'order_details.qty'
            )
            ->orderByDesc('order_details.id')
            ->limit(10)
            ->get();

            $mostOrderedCategory = DB::table('order_details')
            ->join('product', 'order_details.product_id', '=', 'product.id')
            ->join('products_type', 'product.type_id', '=', 'products_type.id')
            ->select('products_type.name as product_type_name', DB::raw('COUNT(*) as count'))
            ->groupBy('products_type.name')
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
            'total_customers' => $totalNumberOfCustomer,
            'most_ordered_products' => $productsDetails,
            'lastTenOrderProducts' => $lastTenOrderProducts,
            'mostOrderedCategory' => $mostOrderedCategory,
        ];

        return response()->json($data, Response::HTTP_OK);
    }
}
