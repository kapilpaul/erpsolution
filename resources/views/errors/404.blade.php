@extends('layouts.default', ['rightSidebar' => false, 'leftmenu' => false])

@section('title', '404')

@section('sidebar')
@endsection

@section('blank_content')
    <main class="parallel">
        <div class="row grid">
            <div class="col-md-6 white">
                <div class="p-5">
                    <div class="p-5">
                        <div class="text-center p-t-100">
                            <p class="s-128 bolder p-t-b-100">404</p>
                            <p class="s-18">opps! oh dear! you are lost don't try to hard.</p>
                            <div class="p-t-b-20">
                                <a href="{{ route('login') }}" class="btn  btn-outline-primary btn-lg">
                                    <i class="icon icon-arrow_back"></i>
                                    Go Back To Home
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 height-full" data-bg-repeat="false" data-bg-possition="center"
                 style="background: url('{{ asset('assets/img/dummy/cs3.gif') }}') #FFEFE4">
            </div>
        </div>
    </main>
@endsection