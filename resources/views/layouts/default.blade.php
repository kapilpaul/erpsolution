@include('layouts.partials.header')

<div id="app">
    @if(! isset($leftmenu))
        @include('layouts.partials.leftmenu')
    @endif

    @section('sidebar')
        <div class="has-sidebar-left">
            @include('layouts.partials.header-top')

            <div class="container-fluid animatedParent animateOnce my-3">
                <div class="animated fadeInUpShort">
                    @include('layouts.partials.session-messages')
                    @yield('content')
                </div>
            </div>
        </div>
    @show

    @yield('blank_content')


    @if(! isset($rightSidebar))
        @include('layouts.partials.right-sidebar')
    @endif

</div>

@include('layouts.partials.footer')