<footer id="footer" class="footer">
    <div class="copyright">{{ __('home.copyright') }} &copy;
        <strong><span>
                <a href="{{ url(env('COPYRIGHT_URL')) }}">{{ env('COPYRIGHT_TITLE') }}</a>
            </span></strong>
        <script>
            document.write(new Date().getFullYear());
        </script> - {{ __('home.right_reserved') }}
    </div>
    {{-- <div class="credits">Developed by
        <a href="{{ config('app.url') }}" target="_blank">{{ config('app.name', '') }}</a>
    </div> --}}
</footer>
