<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" sizes="60x60" href="/control_de_pago/public/img/favicon-16x16.png">
    <title>Pagos</title>
    <style>
        *{
            font-size: 12px;
        }
    </style>
</head>

<body>
@extends('layouts.side_bar')
    <a class="back" href="{{route('control')}}">Volver</a>
    <br><br>
    <form action="">
        @csrf

        <label>
            Desde <input type="date" required name="inicio" id="">
        </label>
        <br><br>
        <label>
            Hasta <input type="date" required name="fin" id="">
        </label>
        <br><br>
        <input type="submit" value="Calcular">
    </form>
    <br>
    @if($mensaje)
    {{$mensaje}}
    @elseif($mensaje == '')
    <p>Se muestran {{$cuenta}} registros</p>   
    <p>Total por cobrar: {{$sum}}$</p>   
    <br>
    <table border="1">
        <tr>
            <th>Nombre</th>
            <th>Direccion</th>
            <th>Fecha de corte</th>
            <th>Plan</th>
            <th>Pendiente</th>
        </tr>
        @foreach($rango as $rangos)
        <tr>
            <td>{{$rangos->full_name}}</td>
            <td>{{$rangos->dir}}</td>
            <td>{{$rangos->cut}}</td>
            <td>{{$rangos->plan}}</td>
            <td>{{$rangos->total}}$</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="5" align="center">Total {{$sum}}$</td>
        </tr>
    </table>
    @endif
</body>

</html>