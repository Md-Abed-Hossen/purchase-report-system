<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function generateReport()
    {
        // Fetch and store the latest data
        $response = Http::get('https://raw.githubusercontent.com/Bit-Code-Technologies/mockapi/main/purchase.json');
        $data = $response->json();

        foreach ($data as $item) {
            $user = User::firstOrCreate(
                ['phone' => $item['user_phone']],
                ['name' => $item['name']]
            );

            $product = Product::firstOrCreate(
                ['code' => $item['product_code']],
                ['name' => $item['product_name'], 'price' => $item['product_price']]
            );

            Purchase::updateOrCreate(
                ['order_no' => $item['order_no']],
                [
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => $item['purchase_quantity'],
                    'created_at' => $item['created_at']
                ]
            );
        }

        // Generate the report
        $report = DB::table('purchases')
            ->join('users', 'purchases.user_id', '=', 'users.id')
            ->join('products', 'purchases.product_id', '=', 'products.id')
            ->select(
                'products.name as product_name',
                'users.name as customer_name',
                DB::raw('SUM(purchases.quantity) as total_quantity'),
                'products.price',
                DB::raw('SUM(purchases.quantity * products.price) as total_amount')
            )
            ->groupBy('products.name', 'users.name', 'products.price')
            ->orderByDesc('total_amount')
            ->get();

        // Calculate total price across all entries
        $totalPrice = $report->sum(function ($item) {
            return (float)$item->price;
        });

        // Calculate total quantity and total amount
        $totalQuantity = $report->sum('total_quantity');
        $grossTotal = $report->sum('total_amount');

        return response()->json([
            'report' => $report,
            'grossTotal' => $grossTotal,
            'totalQuantity' => $totalQuantity,
            'totalPrice' => $totalPrice
        ]);
    }
}
