@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Ajouter un mot au dictionnaire</div>
    
                    <div class="card-body">
                        @include('common.errors')

                        <form method="POST" action="{{ url('dictionnaire') }}">
                            {{-- @method('PUT') --}}
                            @csrf
    
                            <div class="form-group row">
                                <label for="word-label" class="col-md-4 col-form-label text-md-right">Mot</label>
    
                                <div class="col-md-6">
                                    <input id="word-label" class="form-control{{ $errors->has('label') ? ' is-invalid' : '' }}" name="label" value="{{ old('label') }}" autofocus>
                                </div>
                            </div>
    
                            <div class="form-group row">
                                <label for="word-description" class="col-md-4 col-form-description text-md-right">Description</label>
    
                                <div class="col-md-6">
                                    <textarea class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" id="word-description" name="description" rows="3">{{ old('description') }}</textarea>
                                </div>
                            </div>
    
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-plus"></i> Ajouter
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
