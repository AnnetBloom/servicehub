@extends('layouts.app')

@section('title', $company->name)

@section('content')
    <h1>{{ $company->name }}</h1>

    <p>
        <strong>Сайт:</strong>
        @if ($company->website)
            {{ $company->website }}
        @else
            —
        @endif
    </p>

    <p>
        <a href="{{ route('companies.index') }}">← К списку компаний</a>
    </p>
@endsection
