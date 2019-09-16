@extends('layouts.default')

@section('title', 'Supplier')

@section('sidebar')

    @parent

    @section('pagetitle', 'Add Supplier')

    @section('content')
        <div class="card mb-3 shadow no-b r-0">
            <div class="card-header white">
                <h6>Supplier</h6>
            </div>

            <supplier-create></supplier-create>

        </div>
    @stop

@endsection

@push('footer_top_js')
    <script src="{{ mix('js/app.js') }}"></script>
@endpush
