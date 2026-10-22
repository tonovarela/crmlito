@extends('layout.master')
@section('content')
@include('partials.loading')
        {{-- <div class="loanding">
            <div id="circle">
                <div class="loader">
                    <div class="loader">
                        <div class="loader">
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <div style="justify-content: center; align-items: center; display: flex; height: 100vh;">
        <h1 class="text-center">Bienvenido a CANDY CRM LitoApps</h1>
    </div>    
@endsection
@section('scripts')
    <script src="{{ asset('js/pagina_panel.js?n=1') }}"></script>
@endsection
