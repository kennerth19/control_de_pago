<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" sizes="60x60" href="/control_de_pago/public/img/favicon-16x16.png">
    <title>Control de gastos</title>
</head>

<body>
@extends('layouts.side_bar')
    <style>
        .container {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
        }

        .tabla {
            text-align: center;
        }

        .tabla_second {
            text-align: center;
        }

        .trash {
            background-image: url('/control_de_pago/public/img/trash.png');
            background-repeat: no-repeat;
            background-position: 50%;
            background-size: contain;
        }

        #h2 {
            display: inline;
        }
    </style>

    <div class="container">
        <form action="{{route('admin.pay_bills')}}" method="POST">
            @csrf
            <h1>Control de gastos e inversiónes</h1>
            <label>
                Monto en bolivares <br>
                <input type="text" id="in_1" maxlength="5" name="bs" onkeyup="tasa()" onkeypress="return valideKey(event);">
            </label>

            <h2 id="h2">Al cambio 0.00$</h2> <br>

            <script>
                function tasa() {
                    let bs = document.querySelector("#in_1");
                    let h1 = document.querySelector("#h2");
                    h1.innerHTML = "Al cambio " + (bs.value / {{$cambio->tasa}}).toFixed(2) + "$";
                }
            </script>

            <label>
                Monto en dolares <br>
                <input type="text" id="in_2" name="d" onkeypress="return valideKey(event);">
            </label> <br> <br>

            <label>
                Tipo de gasto
                <select name="type" id="">
                    <option value="0">Gastos</option>
                    <option value="1">Inversión</option>
                </select>
            </label> <br> <br>

            <label>
                Fecha de pago
                <input type="date" name="comodin" value="{{date('Y-m-d',strtotime(date('Y-m-d')))}}">
            </label> <br> <br>

            <label>
                Concepto <br>
                <textarea name="concepto" id="in_3" cols="21" rows="5" required></textarea>
            </label> <br> <br>

            <input type="submit" value="Pagar">
        </form>

        <div>
            <form>
                @csrf
                <label>
                    @if($inicio == $fin)
                    <h1>{{date("d-m-Y",strtotime($inicio))}}</h1>
                    <input type="date" value="{{$inicio}}" name="inicio">
                    <input type="date" value="{{$fin}}" name="fin">
                    @else
                    <h1>Entre {{date("d-m-Y",strtotime($inicio))}} y {{date("d-m-Y",strtotime($fin))}}</h1>
                    <input type="date" value="{{$inicio}}" name="inicio">
                    <input type="date" value="{{$fin}}" name="fin">
                    @endif
                </label> <br> <br>

                <label>
                    Tipo de gasto
                    <select name="type">
                        <option value="0">Todo</option>
                        <option value="1">Gastos</option>
                        <option value="2">Inversión</option>
                    </select>
                </label> <br> <br>
                <input type="submit" value="Calcular">
            </form>
        </div>

        <table border="1" class="tabla">
            <tr>
                <th>Dolares</th>
                <th>Bolivares</th>
                <th>Numero de pagos</th>
            </tr>
            <tr>
                <td>{{$sum_d}}$</td>
                <td>{{$sum_bs}}BS</td>
                <td>{{$cuenta}}</td>
            </tr>
            <tr>
                <td colspan="3">Tasa: {{$cambio->tasa}}BS</td>
            </tr>
            <tr>
                <td colspan="3">{{$sum_bs}}Bs al cambio: {{round($sum_bs / $cambio->tasa ,2)}}$</td>
            </tr>
            <tr>
                <td colspan="3">Total: {{$sum_d + round($sum_bs / $cambio->tasa ,2)}}$</td>
            </tr>
        </table>
    </div>
    <br>
    <div>
        <table border="1" class="tabla_second">
            <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Concepto</th>
                <th>Tipo</th>
                <th>Monto $</th>
                <th>Monto BS</th>
                <th>Total</th>
                <th class="trash"></th>
            </tr>
            @foreach($gastos as $gasto)
            <tr>
                <td>{{$n+=1}}</td>
                <td>{{date("d-m-Y",strtotime($gasto->comodin))}}</td>
                <td>{{$gasto->concepto}}</td>
                <td>
                    @if($gasto->type == '0')
                    Gasto
                    @else
                    Inversión
                    @endif
                </td>
                <td>{{$gasto->dollar + 0}}$</td>
                <td>{{$gasto->bs + 0}}BS</td>
                <td>{{round($gasto->total ,2)}}$</td>
                <td>
                    <form action="{{route('admin.pay_bills_delete',$gasto->id)}}" method="POST">
                        @method('put')
                        @csrf
                        <input type="submit" value="Eliminar">
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    <script type="text/javascript">
        function valideKey(evt) {

            let code = (evt.which) ? evt.which : evt.keyCode;

            if (code == 8) {
                return true;
            } else if (code == 46) { //puntos incluidos
                return true;
            } else if (code >= 48 && code <= 57) {
                return true;
            } else {
                return false;
            }
        }

        {
            document.addEventListener("dragover", function(event) {
                event.preventDefault();
            }, false);
        }

        {
            window.onload = function() {
                let myInput_1 = document.getElementById('in_1');
                let myInput_2 = document.getElementById('in_2');
                let myInput_3 = document.getElementById('in_3');

                myInput_1.onpaste = function(e) {
                    e.preventDefault();
                }

                myInput_1.oncopy = function(e) {
                    e.preventDefault();
                }

                myInput_2.onpaste = function(e) {
                    e.preventDefault();
                }

                myInput_2.oncopy = function(e) {
                    e.preventDefault();
                }

                myInput_3.onpaste = function(e) {
                    e.preventDefault();
                }

                myInput_3.oncopy = function(e) {
                    e.preventDefault();
                }
            }
        }
    </script>
</body>

</html>