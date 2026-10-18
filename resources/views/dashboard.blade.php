@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1>Dashboard</h1>

    <p>Добро пожаловать, {{ auth()->user()->name }}!</p>
@endsection