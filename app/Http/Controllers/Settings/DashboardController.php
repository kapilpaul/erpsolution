<?php

namespace App\Http\Controllers\Settings;

use App\Models\Settings\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class DashboardController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $topSalesProductsLifetime = $this->topSalesProductsLifetime();
        $topSalesProductsMonthly = $this->topSalesProductsMonthly(date('m'));
        $topSalesProductsPreviousMonth = $this->topSalesProductsMonthly((date('m') - 1));

        $topCustomers = $this->topCustomers();

        return view('dashboard.index', compact(
            'topSalesProductsLifetime',
            'topSalesProductsMonthly',
            'topSalesProductsPreviousMonth',
            'topCustomers'
        ));
    }

    /**
     * lifetime top sales product
     * @return array
     */
    public function topSalesProductsLifetime()
    {
        $topSalesProductQty = Product::orderBy('out_quantity', 'desc')->limit(5)->pluck('out_quantity');
        $topSalesProductNames = Product::orderBy('out_quantity', 'desc')
            ->select('name')
            ->limit(5)
            ->pluck('name');

        return [
            'topSalesProductQty' => $topSalesProductQty,
            'topSalesProductNames' => $topSalesProductNames
        ];
    }

    /**
     * monthly top sales product
     * @return array
     */
    public function topSalesProductsMonthly($month)
    {
        $topSalesProductQty = DB::table('invoice_products')
            ->join('invoices', 'invoice_products.invoice_id', '=', 'invoices.id')
            ->join('products', 'invoice_products.product_id', '=', 'products.id')
            ->whereMonth('date', '=', $month)
            ->whereYear('date', '=', date('Y'))
            ->whereNull('invoice_products.deleted_at')
            ->whereNull('invoices.deleted_at')
            ->select('product_id', DB::raw('SUM(quantity) as quantity'), DB::raw('CONCAT(products.name, " ", products.model) AS name'))
            ->groupBy('product_id')
            ->orderBy('quantity', 'desc')
            ->pluck('quantity');

        $topSalesProductNames = DB::table('invoice_products')
            ->join('invoices', 'invoice_products.invoice_id', '=', 'invoices.id')
            ->join('products', 'invoice_products.product_id', '=', 'products.id')
            ->whereMonth('date', '=', $month)
            ->whereYear('date', '=', date('Y'))
            ->whereNull('invoice_products.deleted_at')
            ->whereNull('invoices.deleted_at')
            ->select('product_id', DB::raw('SUM(quantity) as quantity'), DB::raw('CONCAT(products.name, " ", products.model) AS name'))
            ->groupBy('product_id')
            ->orderBy('quantity', 'desc')
            ->pluck('name');

        return [
            'topSalesProductQty' => $topSalesProductQty,
            'topSalesProductNames' => $topSalesProductNames
        ];
    }

    /**
     * top Customers who purchase
     * @return mixed
     */
    public function topCustomers()
    {
        $topCustomers = DB::table('customers')
            ->join('invoices', 'customers.id', '=', 'invoices.customer_id')
            ->select('customers.*', DB::raw('SUM(grand_total) as gtotal'))
            ->whereNull('invoices.deleted_at')
            ->whereNull('customers.deleted_at')
            ->groupBy('customers.id')
            ->orderBy('gtotal', 'desc')
            ->limit(10)
            ->get();

        return $topCustomers;
    }
}
