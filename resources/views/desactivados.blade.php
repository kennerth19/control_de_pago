<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" sizes="60x60" href="/control_de_pago/public/img/favicon-16x16.png">
    <title>Clientes desactivados</title>
</head>

<body>
@extends('layouts.side_bar')
    @if(auth()->user()->role == 1 || auth()->user()->role == 2)
    <style>
        .tabla {
            width: 98%;
        }
    </style>

    <table class="tabla text-center absolute z-10 rounded border-teal-700 top-64 left-1/2 transform -translate-x-1/2" style="box-shadow: 2px 2px 5px #5b8dea;">
        <tr>
            <th class="border border-slate-300 p-2" style="box-shadow: 1px 1px 2px #5b8dea;">#</th>
            <th class="border border-slate-300 p-2" style="box-shadow: 1px 1px 2px #5b8dea;">Nombre</th>
            <th class="border border-slate-300 p-2" style="box-shadow: 1px 1px 2px #5b8dea;">Cedula</th>
            <th class="border border-slate-300 p-2" style="box-shadow: 1px 1px 2px #5b8dea;">Direccion</th>
            <th class="border border-slate-300 p-2" style="box-shadow: 1px 1px 2px #5b8dea;">Opción</th>
        </tr>
        <div style="display: none; position:absolute;">{{$i=0}}</div>
        @foreach($clientes as $cliente)
        <tr>
            <td class="border border-slate-300 p-2" style="box-shadow: 1px 1px 2px #5b8dea;">{{$i+=1}}</td>
            <td class="border border-slate-300 p-2" style="box-shadow: 1px 1px 2px #5b8dea;">{{$cliente->full_name}}</td>
            <td class="border border-slate-300 p-2" style="box-shadow: 1px 1px 2px #5b8dea;">
                @if($cliente->id_user)
                {{$cliente->id_user}}
                @else
                No registrada
                @endif
            </td>
            <td class="border border-slate-300 p-2" style="box-shadow: 1px 1px 2px #5b8dea;">{{$cliente->dir}}</td>
            <td class="border border-slate-300 p-2" style="box-shadow: 1px 1px 2px #5b8dea;">
                <a href="{{ route('control.activate',$cliente->id) }}" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">Activar</a>
            </td>
        </tr>
        @endforeach
    </table>
    @else
    <p>Privilegios insuficientes</p>
    @endif
</body>

</html>