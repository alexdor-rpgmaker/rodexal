@extends('layouts.jukebox')

@push('scripts')
    <script type="text/javascript" src="{{ asset('js/jukebox.js') }}" defer></script>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div id="jukebox-wrapper" class="col-md-8">
                <jukebox />
            </div>
        </div>
    </div>
@stop
