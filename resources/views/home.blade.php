@extends('layouts.app')
<title>Home</title>

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <a class="back" href="{{route('control')}}">Control de pagos</a>
                    <br>
                    <a class="back" href="{{route('lista.fibreros')}}">Lista de instalaciones de fibra</a>
                    <br>
                    <a class="back" href="{{route('lista.anteneros')}}">Lista de instalaciones de antena</a>
                </div>
    </div>
</div>
@endsection
