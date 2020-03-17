@extends('layouts.app')

@section('content')
<div class="row align-items-center">
    <div class="col-8">
        <div class="card">
            <div class="text-center">
                <p>Текущая температура в Брянске
                <span class="badge">{{ $weather['fact']['temp'] }}&deg;C</span>
                </a>
              <p><a href="{{ $weather['info']['url'] }}" class="btn btn-primary" target="_blank">Полный прогноз здесь</a></p>
            </div>
        </div>
    </div>
</div>
@endsection