@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1>Dictionnaire</h1>

                @if($words->isEmpty())
                    <p>Pas de mots...</p>
                @else
                    <ul>
                        @forelse ($words as $word)
                            <li>{{ $word->word }}</li>
                            <li>{{ $word->description }}</li>
                        @endforeach
                    </ul>
                @endempty
            </div>
        </div>
    </div>
@stop
