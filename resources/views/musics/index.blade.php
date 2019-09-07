@extends('layouts.jukebox')

@push('scripts')
    <script type="text/javascript" src="{{ asset('js/jukebox.js') }}" defer></script>

    <script type="text/javascript">
        const debug = @json(env('APP_DEBUG'));
        const formerAppUrl = @json(env('FORMER_APP_URL'));
    </script>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div id="jukebox-wrapper" class="col-md-8">
                <Jukebox></Jukebox>
            </div>
        </div>
    </div>
@stop
