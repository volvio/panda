@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Реєстрація на змінення ціни OLX</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('olx.subscribe.post') }}">
        @csrf
        <div class="mb-3">
            <label for="url" class="form-label">Посилання на оголошення OLX.</label>
            <input type="url" class="form-control" id="url" name="url" required value="{{ old('url') }}">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Ваш Email</label>
            <input type="email" class="form-control" id="email" name="email" required value="{{ old('email') }}">
        </div>

        <button type="submit" class="btn btn-primary">Зареєструвати</button>
    </form>
</div>
@endsection
