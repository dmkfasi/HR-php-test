@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col"></div>
    <div class="col">
        <div class="card rounded bg-info p-2" style="width: 20em;">
            <div class="text-center">
                <p>Текущая температура в Брянске
                <span class="badge">{{ $weather['fact']['temp'] }}&deg;C</span>
                </a>
              <p><a href="{{ $weather['info']['url'] }}" class="btn btn-primary" target="_blank">Полный прогноз здесь!</a></p>
            </div>
        </div>
    </div>
    <div class="col"></div>
</div>
@endsection