@extends('layouts.app')

@push('stylesheets')
    <link href="{{ asset('css/dictionary.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div id="dictionary" class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ $title }}</div>
    
                    <div class="card-body">
                        @include('common.errors')

                        <form method="POST" action="{{ $form_url }}">
                            @method($form_method)
                            @csrf
    
                            <div class="form-group row">
                                <label for="word-label" class="col-md-4 col-form-label text-md-right">Mot</label>
    
                                <div class="col-md-6">
                                    <input id="word-label" class="form-control{{ $errors->has('label') ? ' is-invalid' : '' }}" name="label" value="{{ old('label', $word->label) }}" autofocus>
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="word-description" class="col-md-4 col-form-description text-md-right">Description</label>
    
                                <div class="col-md-6">
                                    <textarea class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" id="word-description" name="description" rows="9">{{ old('description', $word->description) }}</textarea>
                                </div>
                            </div>
    
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="submit bouton">
                                        Ajouter
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
