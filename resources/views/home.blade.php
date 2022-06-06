@extends('layouts.app')
@section('content')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="{{ asset('js/ajax.js') }}?v={{ time() }}" defer></script>
  <script src="{{ asset('js/main.js') }}?v={{ time() }}" defer></script>
  <script src="{{ asset('js/custom.js') }}?v={{ time() }}" defer></script>

  <div class="container">
    <x-dashboard />
    @include('components.network_connections')
      <input name="token" type="hidden" value="{!! csrf_token() !!}" id="token">
</div>
@endsection
