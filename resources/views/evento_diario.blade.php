<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" sizes="60x60" href="/control_de_pago/public/img/favicon-16x16.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <title>Eventos del dia: {{date("d-m-Y",strtotime($date))}}</title>
</head>

<body class="" id="body">
    <style>
        * {
            text-align: center;
            font-weight: bolder;
        }

        .container {
            display: grid;
            grid-template-columns: 50% 50%;
            justify-items: center;
            min-width: max-content;
        }

        .body {
            padding: 0;
            margin: 0;
            font-size: 20px;
            text-align: center;
        }

        .container_picture {
            display: grid;
        }

        a {
            text-decoration: none;
        }

        .imprimir {
            background-image: url('/control_de_pago/public/img/imp.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position-x: 320px;
        }

        .main {
            text-align: center;
        }

        td {
            padding: 15px;
        }

        .mas {
            color: green;
            font-weight: bolder;
        }

        .menos {
            color: red;
            font-weight: bolder;
        }

        .main_form {
            display: grid;
            grid-template-columns: 1fr;
            grid-gap: 5px;
            align-items: center;
            justify-items: center;
        }

        #evento {
            resize: none;
        }

        .del {
            background: url("/control_de_pago/public/img/trash.png");
            color: #fff;
            border: none;
            cursor: pointer;
            width: -webkit-fill-available;
            height: -webkit-fill-available;
            background-position: center;
            background-size: 50px 50px;
            background-repeat: no-repeat;
        }

        .td_del {
            background-color: red;
        }

        .picture_mode {
            display: none;
        }

        .eliminar {
            width: -webkit-fill-available;
            height: -webkit-fill-available;
            padding: 10px;
        }

        .form_event {
            font-weight: bolder;
            border: solid 1px #d9d9d9;
            padding-bottom: 15px;
        }

        #imprimir {
            padding: 10px 25px 10px 25px;
            border-radius: 5px;
            background-color: #000;
            color: #fff;
            cursor: pointer;
        }

        #imprimir:hover {
            color: #000;
            background-color: #fff;
        }

        .imprimir_b {
            padding: 10px 25px 10px 25px;
            border-radius: 5px;
            background-color: #000;
            color: #fff;
            cursor: pointer;
        }

        .imprimir_b:hover {
            color: #000;
            background-color: #fff;
        }
    </style>

    @if(session('delete') == "ok")
    <script>
        Swal.fire(
            'Eliminado!',
            'El evento ha sido eliminado',
            'con exito'
        )
    </script>
    @endif

    @if(session('add') == "ok")
    <script>
        Swal.fire(
            'Agregado!',
            'El evento ha sido agregado',
            'con exito'
        )
    </script>
    @endif

    @extends('layouts.side_bar')
    @if(auth()->user()->email == 'marco' || auth()->user()->email == 'kennerth' || auth()->user()->email == 'antonio' || auth()->user()->email == 'Marcelo')
    <h1 class="picture_mode_off">Eventos del dia: {{date("d-m-Y",strtotime($date))}}</h1>

    @if($j > 0)
    <form id="date_form">
        Resumen del dia: <input type="date" id="date" name="date" value="{{$date}}" onchange="this.form.submit()">
    </form>

    <style>
        .head_container,
        .main_container_grid {
            display: grid;
            grid-template-columns: 5% 5fr 1fr 1fr 1fr 1fr 1fr;
            gap: 5px;
            align-items: center;
            justify-items: center;
            text-align: center;
            border: solid 1px #D9D9D9;
            gap: 5px;
        }

        .main_container_grid {
            height: 120px;
        }

        .head_container {
            background-color: #000;
            color: #fff;
            font-weight: bolder;
            margin-top: 3%;
        }

        .event_des {
            word-break: break-all;
            text-align: center;
        }

        .main_container_grid:hover {
            background-color: #D9D9D9;
        }

        .div_container {
            display: grid;
            grid-template-columns: 2fr 1fr;
        }

        @media (min-width: 350px) and (max-width: 480px) {
            .div_container {
                grid-template-columns: 1fr;
            }

            .form_event {
                margin-top: 5%;
            }
        }
    </style>

    <div class="div_container" id="div_container">
        <div class="">
            <div class="head_container" id="head">
                <p>#</p>
                <p>Evento</p>
                <p>Hora</p>
                <p>Dolares</p>
                <p>Bolivares</p>
                <p colspan="1" class="picture_mode_off" id="opcion_1">Opcion</p>
                <p class="picture_mode_off" id="opcion_2">Verificar</p>
            </div>
            @foreach($evento_diario as $evento)

            <div class="main_container_grid">

                <p>{{$i+=1}}</p>
                <p class="event_des">{{$evento->evento}}</p>
                <p>{{date("h:i:s a", strtotime($evento->created_at))}}</p>


                @if($evento->total_d >= 1)
                <p class="mas">{{$evento->total_d+0}}$</p>
                @elseif($evento->total_d < 0) <p class="menos">{{$evento->total_d+0}}$</p>
                    @else
                    <p>{{$evento->total_d+0}}$</p>
                    @endif

                    @if($evento->total_bs >= 1)
                    <p class="mas">{{$evento->total_bs+0}}Bs</p>
                    @elseif($evento->total_bs < 0) <p class="menos">{{$evento->total_bs+0}}Bs</p>
                        @else
                        <p>{{$evento->total_bs+0}}Bs</p>
                        @endif

                        @if(auth()->user()->role == 1 || auth()->user()->role == 2 || auth()->user()->email == 'Marcelo')

                        <form action="{{route('evento_diario_delete',$evento->id)}}" class="eliminar" method="get">
                            <input type="submit" class="del" value="">
                        </form>

                        @else
                        <p class="eliminar">X</p>
                        @endif
                        <input type="checkbox" name="" class="check">
            </div>
            @endforeach
            <div class="form_event">
                <form action="{{route('evento_diario_print')}}" method="get">
                    <p>Total recaudado en dolares: {{$total_sin_resto+0}}$. (Total con resto: {{$total_d+0}}$) | Total recaudado en bolivares: {{$total_bs+0}}Bs.</p>
                    @csrf
                    <input type="hidden" name="print" value="{{$date}}">
                    <input type="submit" class="picture_mode_off" id="imprimir" class="imprimir_b" value="imprimir">
                </form>
                @else
                <div class="main">
                    <p>Resumen del dia: Seleccionar Fecha</p>
                    <form><input type="date" name="date" value="{{$date}}" onchange="this.form.submit()"></form>
                    <p>No hay registros para esta fecha</p>
                </div>
                @endif

            </div>
            <div>
                @foreach($evento_diario_type as $evento)
                    @if($k == 1)
                    <p>Modificaciones de fecha de corte</p>
                    @endif
                    <p><span>{{$k++}} - </span>{{$evento->evento}}</p>
                @endforeach
            </div>
        </div>
        @if(auth()->user()->role == 1 || auth()->user()->role == 2 || auth()->user()->email == 'Marcelo')
        <form action="{{route('evento_diario_add')}}" id="del_add" class="form-add picture_mode_off" method="POST">
            <div class="main_form">
                <h1>Agregar evento</h1>
                @csrf
                <label>Describir evento:</label>
                <textarea name="evento" id="evento" cols="45" rows="5" resizable="1" required></textarea>
                <label>Fecha:</label>
                <input type="date" name="date_print" value="{{$hoy}}">
                <label>Cantidad en dólares:</label>
                <input type="number" name="total_d">
                <label>Cantidad en bolivares:</label>
                <input type="number" name="total_bs">
                <input type="submit" class="imprimir_b" value="Agregar">
            </div>
        </form>
        @else
        <p id="del_add">No se pueden agregar eventos</p>
        @endif
        <div class="picture_mode_off button_picture" id="function_pic" onclick="picture_mode()">
            <img class="img_pic_mode picture_mode_off" src="/control_de_pago/public/img/cam.png"><span class="picture_mode_off">Modo Foto</span></img>
        </div>

        <style>
            .button_picture {
                position: fixed;
                top: 20px;
                right: 20px;
                cursor: pointer;
                display: grid;
                width: 6%;
                align-items: center;
                justify-items: center;
            }

            .img_pic_mode {
                height: 30px;
            }
        </style>

        <script>
            function picture_mode() {

                let elementos = document.getElementsByClassName('main_container_grid');
                let elementos_1 = document.getElementsByClassName('head_container');
                let div_container = document.getElementById('div_container');
                let head = document.getElementById('head');
                let side_bar = document.getElementById('ul');
                let body = document.getElementById('body');
                let date_value = document.getElementById('date').value;
                let date_form = document.getElementById('date_form');
                let check = document.getElementsByClassName('check');
                let eliminar = document.getElementsByClassName('eliminar');
                let main_container_grid = document.getElementsByClassName('main_container_grid');
                let del_add = document.getElementById('del_add').style.display = "none";
                let imprimir = document.getElementById('imprimir').style.display = "none";
                let function_pic = document.getElementById('function_pic').style.display = "none";


                let option_1 = document.getElementById('opcion_1').style.display = "none";
                let option_2 = document.getElementById('opcion_2').style.display = "none";

                date_form.classList.add("picture_mode");

                side_bar.classList.add("picture_mode");
                side_bar.classList.remove("ul");

                body.style.fontWeight = "bolder";

                body.classList.add("body");

                head.style.gridTemplateColumns = "5% 1fr 1fr 1fr 1fr";

                div_container.style.gridTemplateColumns = "1fr";

                for (let i = 0; i <= elementos.length - 1; i++) {
                    elementos[i].classList.add("picture_mode");

                    check[i].style.display = "none";
                    eliminar[i].style.display = "none";
                    main_container_grid[i].style.gridTemplateColumns = "5% 1fr 1fr 1fr 1fr";
                }

                for (let j = 0; j <= elementos_1.length - 1; j++) {
                    elementos_1[j].classList.add("picture_mode");
                }
            }

            $('.eliminar').submit(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Estas seguro?',
                    text: "No seras capaz de revertir este cambio!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Eliminar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();

                    }
                })
            });

            $('.form-add').submit(function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '¿Agregar evento?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Agregar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();

                    }
                })
            });
        </script>
        @else
        <h1>Privilegios insuficientes</h1>
        @endif
</body>

</html>