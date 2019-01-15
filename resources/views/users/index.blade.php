@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1>Membres</h1>

                @if($users->isEmpty())
                    <p>Pas d'utilisateurs...</p>
                @else
                    <ul>
                        @forelse ($users as $user)
                            <li>{{ $user->name }}</li>
                        @endforeach
                    </ul>
                @endempty
            </div>
        </div>
    </div>
@stop
