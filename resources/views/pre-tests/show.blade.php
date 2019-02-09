@extends('layouts.app')

@push('stylesheets')
    <link href="{{ asset('css/pre_tests.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div id="qcm" class="col-md-12">
                <div id="titre_corps">QCM #{{ $pre_test->id }}</div>
                <div id="final_thought">{{ $pre_test->final_thought }}</div>
                <div id="final_thought_explanation">{{ $pre_test->final_thought_explanation }}</div>
                <div id="questionnaire">{{ $pre_test->questionnaire }}</div>
            </div>
        </div>
    </div>
@stop
