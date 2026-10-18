@extends('layouts.app')

@section('title', 'Компании')

@section('content')
    <h1>Компании</h1>

    <p>Всего компаний: {{ $companies->count() }}</p>

    @if ($companies->isEmpty())
        <p>Компаний пока нет.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Сайт</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($companies as $company)
                    <tr>
                        <td>{{ $company->name }}</td>
                        <td>
                            @if ($company->website)
                                {{ $company->website }}
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection