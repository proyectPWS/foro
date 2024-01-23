@extends('adminlte::page')

@section('title', 'Blog Test JETSTREAM')

@section('content_header')
    <h1>Crear un nuevo post</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            {!! Form::open(['route' => 'admin.posts.store', 'autocomplete' => 'off', 'files' => true]) !!}
            @include('admin.posts.partials.form')
            {!! Form::submit('Crear post', ['class' => 'btn btn-primary']) !!}
            {!! Form::close() !!}
        </div>
    </div>
@stop
