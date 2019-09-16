@extends('layouts.default')

@section('title', 'Bank')

@section('sidebar')

    @parent

@section('pagetitle', 'Add Bank')

@section('content')
    <div class="card mb-3 shadow no-b r-0">
        <div class="card-header white">
            <h6>Bank</h6>
        </div>

        <bank-create></bank-create>
    </div>
@stop

@endsection

@push('footer_top_js')
    <script src="{{ asset('js/app.js') }}"></script>
@endpush