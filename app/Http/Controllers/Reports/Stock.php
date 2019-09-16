<?php

namespace App\Http\Controllers\Reports;

use App\Models\Settings\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Stock extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function stock(Request $request)
    {
        if($request->is('api/*')) {
            $products = Product::paginate(100);
            return response()->json(['stock' => $products], 200);
        }

        return view('reports.stock.stock');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function inStock(Request $request)
    {
        if($request->is('api/*')) {
            $products = Product::where('stock', '!=', 0)->paginate(100);
            return response()->json(['stock' => $products], 200);
        }

        return view('reports.stock.instock');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function outOfStock(Request $request)
    {
        if($request->is('api/*')) {
            $products = Product::where('stock', '<=', 5)->paginate(100);
            return response()->json(['stock' => $products], 200);
        }

        return view('reports.stock.outofstock');
    }

    /**
     * @param $stockType
     * @param $value
     * @return \Illuminate\Http\JsonResponse
     */
    public function search($stockType, $value)
    {
        if($stockType === 'instock') {
            $products = $this->productFilterByStockValue('stock', '!=', 0, $value);
        } elseif ($stockType === 'outofstock') {
            $products = $this->productFilterByStockValue('stock', '<=', 5, $value);
        } else {
            $products = Product::where('name', 'like', '%' . $value . '%')
                                 ->orWhere('model', 'like', '%' . $value . '%')
                                 ->orWhere('barcode', 'like', '%' . $value . '%')
                                 ->paginate(100);
        }

        return response()->json(['stock' => $products], 200);
    }

    /**
     * @param $column
     * @param $operator
     * @param $condValue
     * @param $value
     * @return mixed
     */
    public function productFilterByStockValue($column, $operator, $condValue, $value)
    {
        $products = Product::where("$column", "$operator", "$condValue")
                           ->where(function ($query) use ($value) {
                                $query->where('name', 'like', '%' . $value . '%')
                                      ->orWhere('model', 'like', '%' . $value . '%')
                                      ->orWhere('barcode', 'like', '%' . $value . '%');
                           })
                           ->paginate(100);
        return $products;
    }
}
