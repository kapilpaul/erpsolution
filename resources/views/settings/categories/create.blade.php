@extends('layouts.default')

@section('title', 'Category')

@section('sidebar')

    @parent

    @section('pagetitle', 'Add Category')

    @section('content')
        <div class="card mb-3 shadow no-b r-0">
            <div class="card-header white">
                <h6>Category</h6>
            </div>
            <category-create></category-create>
        </div>
    @stop

@endsection

@push('footer_top_js')
    <script src="{{ mix('js/app.js') }}"></script>
@endpush
