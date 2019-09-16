@extends('layouts.default')

@section('title', 'Customer')

@section('sidebar')

    @parent

    @section('pagetitle', 'Add Customer')

    @section('content')
        <div class="card mb-3 shadow no-b r-0">
            <div class="card-header white">
                <h6>Customer</h6>
            </div>

            <customer-create></customer-create>

        </div>
    @stop

@endsection

@push('footer_top_js')
    <script src="{{ mix('js/app.js') }}"></script>
@endpush
