<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" sizes="60x60" href="/control_de_pago/public/img/favicon-16x16.png">
    <title>{{$cliente->full_name}}</title>
</head>

<body>
@extends('layouts.side_bar')
    <style>
        .profile {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .api {
            border: solid 1px red;
            width: 98%;
            margin: 5px;
            padding: 5px;
        }

        .data {
            width: 400px;
            height: 300px;
            border: solid 1px red;
            margin: 5px;
            padding: 5px;
        }

        .pic {
            width: 100px;
            height: 140px;
            border: solid 1px red;
            margin: 5px;
            padding: 5px;
        }

        .galery {
            width: 90%;
            height: 200px;
            border: solid 1px red;
            margin: 5px;
            padding: 5px;
        }

        .pg {
            display: grid;
            grid-template-columns: 1fr;
        }

        .data {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .back {
            background-color: white;
            border: solid 1px black;
            border-radius: 5px;
            padding: 10px;
            text-decoration: none;
            font-weight: bold;
            width: 120px;
            height: 20px;
            text-align: center;
        }

        .nav{
            display: grid;
            justify-content: space-between;
            grid-template-columns: 1fr 1fr;
            grid-gap: 5px;
        }
    </style>
    <div class="nav">
        <h1>Perfil de {{$cliente->full_name}}</h1>
        <a href="{{route('control')}}" class="back">Volver al inicio</a>
    </div>

    <div class="profile">
        <div class="data">
            <p class="grid-item">Direccion: {{$cliente->dir}}</p>
            <p class="grid-item">Cedula: {{$cliente->id_user}}</p>
            <p class="grid-item">Telefono: {{$cliente->tlf}}</p>
            <p class="grid-item">Fecha de corte: {{$cliente->cut}}</p>
            <p class="grid-item">Plan: {{$cliente->plan}} ({{$cliente->total}}$)</p>
            @if($cliente->ip != "Default")
            <p class="grid-item">Ip: <a href="http://{{$cliente->ip}}" target="_blank">{{$cliente->ip}}</a></p>
            @else
            <p class="grid-item" style="color:red;">Ip no registrada!</p>
            @endif
            <p class="grid-item">Observacion: {{$cliente->observation}}</p>
        </div>
        <div class="pg">
            <div class="galery">GALERIA</div>
        </div>
    </div>
    <div class="api">
        @if($cliente->lat != "none" && $cliente->lon != "none")
            <iframe class="api" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3923.3927746290306!2d{{$cliente->lon}}!3d{{$cliente->lat}}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xaac10b6e27122c83!2zMTDCsDI4JzEwLjgiTiA2OMKwMDInMDYuMCJX!5e0!3m2!1ses!2sve!4v1663700104950!5m2!1ses!2sve"  width="1000" height="270" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        @else
        <p>No hay datos de la ubicacion registrados</p>
        @endif
    </div>

</body>

</html>