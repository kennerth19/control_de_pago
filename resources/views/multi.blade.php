<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Multipagos</title>
    <link rel="shortcut icon" sizes="60x60" href="/control_de_pago/public/img/favicon-16x16.png">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
</head>

<body>
@extends('layouts.side_bar')
    <style>
        @media screen and (max-width: 800px) {
            body {
                margin: 0;
                padding: 0;
            }

            .id,
            .estado,
            .plan,
            .coste_plan,
            .advan,
            .total {
                display: none
            }

            #sub {
                padding: 10px;
                margin: 5px;
            }

            .tr {
                margin-top: 20px;
            }

            table {
                margin: 0 auto;
            }
        }
    </style>

    <a class="back" href="{{route('control')}}">Volver</a>
    <h1>Multipagos</h1>

    <h1>Total de registros: {{$count}}</h1>

    <form action="" style="top:30%; width:50%;" class="search border rounded absolute z-10 left-1/2 transform -translate-x-1/2 -translate-y-1/4">
        <input type="search" class="find" value="" name="find" maxlength="34">
        <label>
            <input type="submit" value="Buscar" class="sub">
        </label>
    </form>

    <table border="1" style="text-align: center">
        <tr>
            <th class="id">Id</th>
            <th>Cliente</th>
            <th class="estado">Estado</th>
            <th class="plan">Plan</th>
            <th class="coste_plan">Coste del plan</th>
            <th class="advan">Adelanto del cliente</th>
            <th class="total">Total a pagar</th>
        </tr>
        @foreach($clientes as $cliente)
        @continue($cliente->paye == "pago")
        <tr>
            <th class="id">{{$cliente->id}}</th>
            <th>{{$cliente->full_name}}</th>
            <td class="estado border border-slate-300 head">

                @if($cliente->status == 1)
                <span class="text-green-500 font-bold">Solvente</span>
                @elseif($cliente->status == 2)
                <span class="text-orange-500 font-bold">Prorroga día 2</span>
                @elseif($cliente->status == 3)
                <span class="text-orange-500 font-bold">Prorroga día 1</span>
                @elseif($cliente->status == 4)
                <span class="text-orange-500 font-bold">Resta 1 día</span>
                @elseif($cliente->status == 5)
                <span class="text-orange-500 font-bold">Restan 2 días</span>
                @elseif($cliente->status == 6)
                <span class="text-red-500 font-bold">Dia de corte</span>
                @elseif($cliente->status == 7)
                <span class="text-red-500 font-bold">Requiere suspension</span>
                @elseif($cliente->status == 0)
                <span class="font-bold">Fecha incorrecta</span>
                @endif

            </td class="border border-slate-300">
            <th class="plan">{{$cliente->plan}}</th>
            <th class="total">{{$cliente->total}}</th>
            <th class="advan">{{$cliente->advan}}</th>
            <th class="total">{{$cliente->total - $cliente->advan}}</th>
            {{-- formulario de pagos automatico --}}
            <th>
                <form action="{{route('cliente.pay_update',$cliente)}}" method="POST" name="multipay" class="formulario-pagado w-full max-w-lg m-5">
                    @method('put')
                    @csrf
                    <input type="hidden" name="id_user" value="{{$cliente->id_user}}">
                    <input type="hidden" name="dir" value="{{$cliente->dir}}">
                    <input type="hidden" name="day" value="{{date('Y-m-d',strtotime(date('Y-m-d')))}}">
                    <input type="hidden" name="cut" disabled value="{{date('Y-m-d',strtotime($cliente->cut .'+ 1 month'))}}">
                    <input type="hidden" name="plan" disabled value="{{$cliente->plan}}">
                    <input type="hidden" name="pago" value="1">
                    <input type="hidden" name="status" value="1">
                    <input type="hidden" min="0" max="{{$cliente->total}}" name="monto" value="{{$cliente->total - $cliente->advan}}">
                    <input type="submit" id="sub" value="Pagar">
                </form>
            </th>
            {{-- formulario de pagos automatico --}}
        </tr>
        @endforeach
    </table>

    @if(session('pagado') == "ok")
    <script>
        Swal.fire(
            'Pagado!',
            'El pago ha sido efectuado',
            'con exito'
        )
    </script>
    @endif

    <script>
        $('.formulario-pagado').submit(function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Realizar pago?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Pagar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            })
        });
    </script>
</body>

</html>