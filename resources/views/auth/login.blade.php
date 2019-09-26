@extends('layouts.default', ['rightSidebar' => false, 'leftmenu' => false])

@section('title', 'Login')

@section('sidebar')
@endsection

@section('blank_content')
    <div class="page parallel">
        <div class="row align-items-center">
            <div class="col-md-4 dark">
                <div class="p-5 mt-5"></div>
                <div class="p-30">
                    <h3 class="mb-3">Welcome</h3>

                    @include('layouts.partials.session-messages')

                    {!! Form::open(['method' => 'POST', 'action' => 'Login\Login@postLogin', 'id' => "form_validation"])
                     !!}
                        <div class="form-group has-icon">
                            <i class="icon-envelope-o"></i>
                            {!! Form::email('email', null, ['class'=>'form-control form-control-lg', 'placeholder' => 'Email Address', 'required']) !!}
                            @if ($errors->has('email'))
                                <label id="name-error" class="error text-danger" for="name">{{ $errors->first('email')
                                }}</label>
                            @endif
                        </div>
                        <div class="form-group has-icon">
                            <i class="icon-user-secret"></i>
                            {!! Form::password('password', ['class'=>'form-control form-control-lg', 'placeholder' => 'Password', 'required']) !!}

                            @if ($errors->has('password'))
                                <label id="name-error" class="error text-danger" for="name">{{ $errors->first
                                ('password') }}</label>
                            @endif
                        </div>

                        <div class="form-group">
                            <label class="form-check-label">
                                {!! Form::checkbox('remember_me', 'on', false); !!} Remember Me
                            </label>
                        </div>

                        {!! Form::submit('Log In', ['class'=>'btn btn-primary btn-lg btn-block']) !!}

                    {!! Form::close() !!}

                    <p class="mt-2">Have you forgot your username or password ? <a href="">Click Here !</a></p>
                </div>
            </div>
            <div class="col-md-8  height-full blue accent-3 align-self-center text-center" data-bg-repeat="false" data-bg-possition="center" style="background: url({{ asset('assets/img/icon/icon-plane.png') }}) #FFEFE4">
            </div>
        </div>
    </div>
@endsection
