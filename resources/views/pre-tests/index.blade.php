@extends('layouts.app')

@push('stylesheets')
    <link href="{{ asset('css/pre_tests.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div id="qcm" class="col-md-8">
                <div id="titre_corps">QCM</div>

                @can('create', App\PreTest::class)
                    <p>
                        <a href="{{ route('qcm.create', ['game_id' => 937]) }}" class="bouton">
                            <i class="fa fa-plus"></i> Ajouter une entrée de QCM pour jeu 937
                        </a>
                    </p>
                @endcan

                @if($pre_tests->isEmpty())
                    <p>Pas de QCMs...</p>
                @else
                    <ul class="pre-tests">
                        @foreach ($pre_tests as $pre_test)
                            <li>
                                <a href="{{ route('qcm.show', $pre_test) }}">
                                    QCM #{{ $pre_test->id }}
                                </a>
                            </li>
                        @endforeach
                    </div>
                @endempty
            </div>
        </div>
    </div>
@stop
