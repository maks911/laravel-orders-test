@extends('layouts.master')

@section('title', 'Weather Show')

@section('content')
    <div class="container text-center">
        <p class="temperature">Температура в Брянске: {{ $temperature }}.</p>
    </div>
@stop
