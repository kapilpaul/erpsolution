@extends('layouts.default')

@section('title', 'Product Details')

@section('sidebar')

    @parent

@section('pagetitle', 'Product Details')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Product Details of {{ $product->name }} {{ $product->model }}
                </div>
                <div class="card-body">
                    <div class="row  align-items-center">
                        <div class="col-md-3">
                            <div class="">
                                <h5 class="card-title mb-0">Name : {{ $product->name }} {{ $product->model }}</h5>
                                <p class="card-text mb-0">Unit : {{ $product->unit }}</p>
                                <p class="card-text mb-0">{{ $product->description }}</p>
                                <p class="card-text mb-0">Purchase Price : {{ $product->price }}</p>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div id="echart_erp_pie" style="height:300px;"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-7">
                                    <div>
                                        <p class="mb-0">
                                            <i class="icon-circle-o text-red mr-2"></i>Total Purchase Amount</p>
                                        <p class="mb-0">
                                            <i class="icon-circle-o text-green mr-2"></i>Total Sales Amount</p>
                                        <p class="mb-0">
                                            <i class="icon-circle-o text-green mr-2"></i>Total Discount Amount</p>
                                        <hr>
                                        <p class="mb-0">
                                            <i class="icon-circle-o text-green mr-2"></i>Total Profit</p>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="float-right">
                                        <p class="mb-0">{{ $totalPurchaseAmount }}</p>
                                        <p class="mb-0">{{ $totalSaleAmount }}</p>
                                        <p class="mb-0">{{ $totalDiscount }}</p>
                                        <hr>
                                        <p class="mb-0">
                                            @if($totalSaleAmount != 0)
                                            {{ round(($totalSaleAmount - $totalDiscount) - $totalPurchaseAmount) }}
                                            @else
                                                0
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--purchase ledger--}}
    <div class="row pt-5">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header white">
                    <i class="icon-clipboard-edit blue-text"></i>
                    <strong>Purchase Ledger </strong>
                </div>
                <div class="box-body no-padding">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <!-- Table heading -->
                            <tbody>
                            <tr class="no-b">
                                <th>Sl.</th>
                                <th>Date</th>
                                <th>Invoice No</th>
                                <th>Purchase No</th>
                                <th>Supplier Name</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Total Amount</th>
                            </tr>

                            @foreach($purchaseDetails as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ Carbon\Carbon::parse($item->purchase_date)->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('purchase.show', $item->purchase_no) }}">{{ $item->invoice_no }}</a>
                                </td>

                                <td><a href="{{ route('purchase.show', $item->purchase_no) }}">{{ $item->purchase_no }}</a></td>
                                <td><a href="{{ route('suppliers.show', $item->supplier_code) }}">{{ $item->supplier_name }}</a></td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->price }}</td>
                                <td>{{ $item->total }}</td>
                            </tr>
                            @endforeach

                            <tr class="">
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>{{ $product->in_quantity }}</th>
                                <th>Grand Total</th>
                                <th>{{ $totalPurchaseAmount }}</th>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--sales ledger --}}
    <div class="row pt-5">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header white">
                    <i class="icon-clipboard-edit blue-text"></i>
                    <strong>Sales Ledger </strong>
                </div>
                <div class="box-body no-padding">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <!-- Table heading -->
                            <tbody>
                            <tr class="no-b">
                                <th>Sl.</th>
                                <th>Date</th>
                                <th>Invoice No</th>
                                <th>Customer Name</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Discount</th>
                                <th>Total Amount</th>
                            </tr>
                            @foreach($salesDetails as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ Carbon\Carbon::parse($item->date)->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('invoice.show', $item->invoice_no) }}">{{ $item->invoice_no }}</a>
                                </td>
                                <td><a href="{{ route('customer.show', $item->customer_code) }}">{{ $item->customer_name }}</a></td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->price }}</td>
                                <td>{{ $item->discount }}</td>
                                <td>{{ $item->total }}</td>
                            </tr>
                            @endforeach

                            <tr class="">
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Total</th>
                                <th>{{ $product->out_quantity }}</th>
                                <th></th>
                                <th>{{ $totalDiscount }}</th>
                                <th>{{ $totalSaleAmount }}</th>
                            </tr>

                            <tr class="">
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Grand Total</th>
                                <th>{{ $totalSaleAmount - $totalDiscount }}</th>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@endsection

@push('footer_js')
    <script src="{{ asset('assets/js/echarts.js') }}"></script>

    <script type="text/javascript">
        // based on prepared DOM, initialize echarts instance
        var myChart = echarts.init(document.getElementById('echart_erp_pie'));

        // specify chart configuration item and data
        var option = {
            tooltip : {
                trigger: 'item',
                formatter: "{a} <br/>{b} : {c} ({d}%)"
            },
            legend: {
                bottom: 0,
                left: 'center',
                data: ['Purchase', 'Sales', 'In Stock']
            },
            series : [
                {
                    name: 'Product',
                    type: 'pie',
                    radius : '65%',
                    center: ['50%', '50%'],
                    selectedMode: 'single',
                    data:[
                        {
                            value: '<?php echo $product->in_quantity; ?>',
                            name:'Purchase',
                            itemStyle : {
                                color : '#03a9f4'
                            }
                        },
                        {
                            value: '<?php echo $product->out_quantity; ?>',
                            name:'Sales',
                            itemStyle : {
                                color : '#8bc34a'
                            }
                        },
                        {
                            value: '<?php echo $product->stock; ?>',
                            name:'In Stock',
                            itemStyle : {
                                color : '#d9d828'
                            }
                        }
                    ],
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }
            ]
        };

        // use configuration item and data specified to show chart
        myChart.setOption(option);
    </script>
@endpush
