@extends('layouts.default')

@section('title', ucfirst($type).' Transaction')

@section('sidebar')

    @parent

@section('pagetitle', 'Add '. ucfirst($type) .'Transaction')

@section('content')
    <div class="card mb-3 shadow no-b r-0">
        <div class="card-header white">
            <h6>{{ ucfirst($type) }}</h6>
        </div>

        <transaction-create :banks="{{ $banks }}" transaction-type="{{ $type }}"></transaction-create>
    </div>
@stop

@endsection

@push('footer_top_js')
    <script src="{{ asset('js/app.js') }}"></script>
@endpush