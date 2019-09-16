<!--/#app -->
<!-- JavaScript -->
{{--<script src="{{ asset('js/app.js') }}"></script>--}}
@stack('footer_top_js')

<script src="{{ asset('assets/js/app.js') }}"></script>
<script src="{{ asset('assets/js/customjs.js') }}"></script>

@stack('footer_js')

@if(session('access_token'))
    <script>
        localStorage.setItem('token', '{{ session('access_token') }}');
        localStorage.setItem('expiration', '{{ session('expiration') }}' + Date.now());
    </script>
@endif

<!--
--- Footer Part - Use Jquery anywhere at page.
--- http://writing.colin-gourlay.com/safely-using-ready-before-including-jquery/
-->
<script>
    (function ($, d) {
        $.each(readyQ, function (i, f) {
            $(f)
        });
        $.each(bindReadyQ, function (i, f) {
            $(d).bind("ready", f)
        })
    })(jQuery, document)
</script>

</body>
</html>