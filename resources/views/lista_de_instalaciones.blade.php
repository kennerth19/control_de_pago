<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app_fibreros.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/checkbox.css') }}">
    <script src="{{ asset('js/app_fibreros.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="shortcut icon" sizes="60x60" href="/control_de_pago/public/img/favicon-16x16.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <title id="tt">Pre-registro - FIBRA</title>
</head>

<body>

    <h1 class="main_tittle" id="main_title">Pre-registro - FIBRA</h1>

    <div class="main_container" id="main_container">
        <p>#</p>
        <p>Cliente</p>
        <p>Opcion</p>
    </div>
    <style>
        .bolita{
            display: grid;
            grid-template-columns: 1fr;
            justify-items: center;
        }

        .sub_bolita{
            width: 20px;
            height: 20px;
            border-radius: 50%;
            cursor: pointer;
            margin-left: 10px;
            border: 1px solid #000;
        }

        .id_pre{
            margin: 0;
            margin-left: 10px;
        }
    </style>
    @foreach($pre_reg as $users)
    @if($i == 1)
        <div class="second_container rounded">
            <div class="bolita">
                <p onclick="modal({{$i}}, {{$count}})" class="id_pre">{{$i++}}</p>
                <form action="{{route('fibreros_act_des',$users->id)}}" method="POST">
                    @csrf
                    @if($users->active == 1)
                        <input class="sub_bolita" type="submit" value title="No instalado" style="background-color: #7F7F80;">
                    @elseif($users->active == 0)
                        <input class="sub_bolita" type="submit" value title="Instalado sin registrar" style="background-color: #27d719;">
                    @else
                        <input class="sub_bolita" type="submit" value title="No se pudo instalar" style="background-color: #FF0000;">
                    @endif
                </form>
            </div>
            <p class="name" onclick="modal({{$i-1}}, {{$count}})">{{$users->full_name}}</p>
            <button onclick="modal_r({{$i-1}}, {{$count}})" class="op_form rounded_f">Registrar</button>
    @else
        <div class="second_container">
            <div class="bolita">
                <p onclick="modal({{$i}}, {{$count}})" class="id_pre">{{$i++}}</p>
                <form action="{{route('fibreros_act_des',$users->id)}}" method="POST">
                    @csrf
                    @if($users->active == 1)
                        <input class="sub_bolita" type="submit" value title="No instalado" style="background-color: #7F7F80;">
                    @elseif($users->active == 0)
                        <input class="sub_bolita" type="submit" value title="Instalado sin registrar" style="background-color: #27d719;">
                    @else
                        <input class="sub_bolita" type="submit" value title="No se pudo instalar" style="background-color: #FF0000;">
                    @endif
                </form>
            </div>
            <p class="name" onclick="modal({{$i-1}}, {{$count}})">{{$users->full_name}}</p>
            <button onclick="modal_r({{$i-1}}, {{$count}})" class="op_form">Registrar</button>
    @endif
    </div>
    @endforeach

    <div class="modal animate__animated animate__backInUp" onclick="modal_f()">
        @foreach($pre_reg as $users)
        <div id="modal_{{$j++}}">
            <p class="name">{{$users->full_name}}</p>
            <p>Cedula: {{$users->id_user}}</p>
            <p>Telefono: {{$users->tlf}}</p>
            <p>Direccion: {{$users->dir}}</p>
            <p>Fecha de pago: {{date("d-m-Y",strtotime($users->date_pay))}}</p>
            <p>Plan: {{$users->plan_name}}</p>
            <p>Total cancelado: {{$users->monto}}</p>
            <p>Observacion: {{$users->observation}}</p>
        </div>
        @endforeach
    </div>

    <div class="modal_r animate__animated animate__backInUp">
        @foreach($pre_reg as $users)
            <div id="modal_r_{{$i_r++}}">
            <div class="close_tap" onclick="modal_r_f()"></div>
                <form action="{{route('evento.add',$users->id)}}" method="post" class="form_reg">
                    @csrf
                    <div class="form_reg_0">
                        <p class="name item_form_reg">{{$users->full_name}}</p>
                        <div class="container">
                            <div>
                                <p>Ingrese la ip</p>
                                <input type="text" name="ip_pre_reg" class="ip_pre_reg item_form_reg">
                            </div>
                            <div>
                                <p>Ingrese la deuda</p>
                                <input type="number" name="dept" step="any" class="item_dep_reg item_form_reg">
                            </div>
                        </div>

                        <p class="item_form_reg">Fecha de instalacion<br><input type="date" name="insta_day" id="" class="date item_form_reg" value="{{$hoy_format}}"></p>
                        Nota del instalador:
                        <textarea name="observation" cols="30" rows="10" class="comentario item_form_reg"></textarea>
                        <br><br>
                        <input type="submit" class="sub_b item_form_reg" value="Registrar">
                    </div> 
                </form>
            </div>
        @endforeach
    </div>

    <style>
        .support{
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
            align-items: center;
            justify-items: center;
        }

        #switch{
            position: absolute;
            top: 10px;
            left: 10px;
        }
    </style>

    <script>
        function switch_mode(mode){

            let elements = document.getElementsByClassName('second_container');
            let header = document.getElementById('main_container');
            let main_title = document.getElementById('main_title');
            let support = document.getElementById('support');
            let button_switch = document.getElementById('switch');
            let tt = document.getElementById('tt');
            
            if(mode == 1){
                header.style.display = "none";
                main_title.innerHTML = "Soportes - FIBRA";
                support.style.display = "grid";
                tt.innerHTML = "Soportes - FIBRA";
                
                for(let i = 0; i <= document.getElementsByClassName('second_container').length - 1; i++){
                    elements[i].style.display = "none";
                }

                button_switch.setAttribute('onclick','switch_mode(0)')
            }else{
                header.style.display = "grid";
                main_title.innerHTML = "Pre-registro - FIBRA";
                support.style.display = "none";
                tt.innerHTML = "Pre-registro - FIBRA";
                
                for(let i = 0; i <= document.getElementsByClassName('second_container').length - 1; i++){
                    elements[i].style.display = "grid";
                }

                button_switch.setAttribute('onclick','switch_mode(1)')
            }
        }
    </script>

    <button onclick="switch_mode(1)" id="switch">Cambiar</button>

    <div class="support" id="support" style="display:none">
        <p>#</p>
        <p>Nota</p>
        <p>Total</p>
        <p>Fecha</p>
        <p>Borrar</p>
        @foreach($support as $info)
            <p>{{$info->id}}</p>
            <p>{{$info->nota}}</p>
            <p>{{$info->total}}</p>
            <p>{{$info->fecha}}</p>
            @if($info->active == 0)
                <form action="{{route('del_sup',$info->id)}}" method="post">
                    @csrf
                    <input type="submit" value="Borrar">
                </form>
            @else
                <button title="Para borrar: desactive primero el soporte" disabled>Borrar</button>
            @endif
        @endforeach
    </div>
</body>
</html>