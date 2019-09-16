@extends('layouts.default')

@section('title', 'Accounts')

@section('sidebar')

    @parent

    @section('pagetitle', 'Add Account')

    @section('content')
        <div class="card mb-3 shadow no-b r-0">
            <div class="card-header white">
                <h6>Account</h6>
            </div>
            <account-create></account-create>
        </div>
    @stop

@endsection

@push('footer_top_js')
    <script src="{{ mix('js/app.js') }}"></script>
@endpush
