@extends('layouts.app')

{{-- @push('stylesheets')
    <link href="{{ asset('css/pre_tests.css') }}" rel="stylesheet">
@endpush --}}

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div id="qcm" class="col-md-12">
                <a href="{{ route('qcm.edit', ['game_id' => $pre_test->id]) }}" class="bouton">Modifier</a>
                <div id="titre_corps">QCM #{{ $pre_test->id }}</div>
                <div id="final_thought">Conforme : {{ $pre_test->final_thought ? 'Oui' : 'Non' }}</div>
                @if ($pre_test->final_thought_explanation)
                    <div id="final_thought_explanation">Plus d'infos : {{ $pre_test->final_thought_explanation }}</div>
                @endif
                <p>Table</p>
                <table>
                    @foreach (App\PreTest::FIELDS as $field)
                        @if ($pre_test->questionnaire[$field['id']]['activated'])
                            <tr>
                                <td>{{ $field['label'] }}</td>
                                <td>{{ $pre_test->questionnaire[$field['id']]['explanation'] }}</td>
                            </tr>
                        @endif
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@stop
