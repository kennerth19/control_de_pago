<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="shortcut icon" sizes="60x60" href="/control_de_pago/public/img/favicon-16x16.png">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <title>Reportes de pago</title>
</head>

<body>
@extends('layouts.side_bar')
    <div class="view_buttons" id="view_buttons">
        <input type="radio" name="toggle" id="a" onchange="toggle_sc()" Checked>
        <input type="radio" name="toggle" id="b" onchange="toggle_sc()">
        <label id="label_report">Reporte general</label>
    </div>

    <script type="text/javascript">
        function valideKey(evt) {

            let code = (evt.which) ? evt.which : evt.keyCode;

            if (code == 8) {
                return true;
            } else if (code == 46) {
                return true;
            } else if (code >= 48 && code <= 57) {
                return true;
            } else {
                return false;
            }
        }

        window.onload = function() {
            let myInput = document.getElementById('d');
            myInput.onpaste = function(e) {
                e.preventDefault();
            }

            myInput.oncopy = function(e) {
                e.preventDefault();
            }
        }

        document.addEventListener("dragover", function(event) {
            event.preventDefault();
        }, false);
    </script>

    <style>
        body {
            background-color: #2C2C2C;
            color: #fff;
        }

        .tittle {
            text-align: center;
        }

        table {
            text-align: center;
        }

        .contenedor {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
        }

        div {
            justify-items: center;
        }

        .pic {
            position: absolute;
            top: 1%;
            right: 1%;
        }

        .main_table {
            border-collapse: collapse;
            border: solid 1px white;
            border-radius: 5px;
            color: #fff;
            font-family: 'calibri';
            font-size: 15px;
        }

        th {
            border: none;
            padding: 10px;
        }

        td {
            border: none;
        }

        tr {
            border: none;
            padding: 10px;
        }

        .main_tr {
            background-color: #2B3336;
            border: solid 1px white;
        }

        .delete_button {
            padding: 5px;
            margin: 5px;
            background-color: transparent;
            color: white;
            font-family: 'Courier New', Courier, monospace;
            font-size: 16px;
            border-radius: 5px;
            border: solid white 1px;
            cursor: pointer;
        }

        .delete_button_2 {
            padding: 4px;
            margin: 5px;
            background-color: transparent;
            color: white;
            font-family: 'Courier New', Courier, monospace;
            border-radius: 5px;
            border: solid white 1px;
            cursor: pointer;
            text-decoration: none;
        }

        .tot {
            border: solid 1px white;
        }

        .style_sel {
            -webkit-appearance: none;
            -moz-appearance: none;
            background: transparent;
            border: none;
            color: #fff;
            font-size: 17px;
            text-align: center;
            outline: none;
            cursor: pointer;
        }

        option {
            background: transparent;
            color: #2C2C2C;
        }

        .fac_gen {
            text-decoration: none;
            color: white;
            padding: 5px;
        }

        .pay_1,
        .pay_3 {
            display: grid;
            grid-template-columns: 1fr;
        }

        #pm_ref_ser{
            display: none;
        }

        .btn-op{
            padding: 8px;
            text-decoration: none;
        }

        .info_me{
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
    </style>

    @if(session('eliminar') == "ok")
    <script>
        Swal.fire(
            'Desactivado!',
            'El pago ha sido desactivado',
            'con exito'
        )
    </script>
    @endif

    @if(session('cambiar') == "ok")
    <script>
        Swal.fire(
            'Desactivado!',
            'El pago ha sido desactivado',
            'con éxito'
        )
    </script>
    @endif

    @if(session('delete') == "ok")
    <script>
        Swal.fire(
            'Desactivado!',
            'El pago ha sido desactivado',
            'con éxito'
        )
    </script>
    @endif

    @if(session('pago') == "ok")
    <script>
        Swal.fire(
            'Pagado!',
            'El pago ha sido realizado con',
            'con éxito'
        )
    </script>
    @endif

    <style>
        .sub_cd{
            border: none;
            background-color: #6e7881;
            color: #fff;
            padding: 10px;
        }
    </style>

    <div class="on" id="screem_1">

    <h1 class="tittle" id="tittle">Pagos</h1>

    <div class="pic" id="pic">
        <label>
            Modo foto<br>
            <input type="radio" name="pic" onclick="pic('yes')">
            <input type="radio" name="pic" onclick="pic('no')">

            <script>
                function pic(mode) {
                    if (mode == "yes") {
                        document.getElementById("pay_calc").style.display = "none";
                        document.getElementById("pay_ser").style.display = "none";
                        document.getElementById("tittle").style.display = "none";
                        document.getElementById("tasa_pic").style.display = "none";
                        document.getElementById("view_buttons").style.display = "none";
                    } else {
                        document.getElementById("pay_calc").style.display = "block";
                        document.getElementById("pay_ser").style.display = "block";
                        document.getElementById("tittle").style.display = "block";
                        document.getElementById("tasa_pic").style.display = "block";
                        document.getElementById("view_buttons").style.display = "block";
                    }
                }
            </script>
        </label>
    </div>

    <div class="contenedor">
        <div class="pay_calc pay_1" id="pay_calc">
            <h1>Calcular pagos</h1>
            <form class="pay_1">
                @csrf
                @if($inicio == "" && $fin == "")
                <label>
                    Desde <input type="date" value="{{date('Y-m-d')}}" required name="inicio" id="">
                </label>
                <label>
                    Hasta <input type="date" value="{{date('Y-m-d')}}" required name="fin" id="">
                </label>
                @else
                <label>
                    Desde <input type="date" value="{{$inicio}}" required name="inicio" id="">
                </label>
                <label>
                    Hasta <input type="date" value="{{$fin}}" required name="fin" id="">
                </label>
                @endif

                <select name="report" class="border border-slate-300 report_ser" required>
                    @if($orden->sort_report == "0")
                    <option value="0" selected>Mostrar todo</option>
                    <option value="1">Mostrar pagos de mensualidades</option>
                    <option value="2">Mostrar pagos de servicios</option>
                    @elseif($orden->sort_report == "1")
                    <option value="0">Mostrar todo</option>
                    <option value="1" selected>Mostrar pagos de mensualidades</option>
                    <option value="2">Mostrar pagos de servicios</option>
                    @elseif($orden->sort_report == "2")
                    <option value="0">Mostrar todo</option>
                    <option value="1">Mostrar pagos de mensualidades</option>
                    <option value="2" selected>Mostrar pagos de servicios</option>
                    @endif
                </select>
                <br>
                <input type="submit" class="sub_pay" value="Calcular">
            </form>
        </div>

        <style>
            .service_pay {
                display: grid;
                grid-template-columns: 1fr 1fr;
                grid-gap: 5px;
                justify-items: center;
            }

            .sub_pay, .btn-op, .false_sub {
                cursor: pointer;
                border: solid 1px;
                border-radius: 4px;
                width: 95px;
                height: 32px;
                color: #fff;
                background-color: #2C2C2C;
            }

            .sub_pay:hover, .btn-op:hover, .false_sub:hover{
                color: #000;
                background-color: #fff;
            }

            .label_op {
                display: grid;
                grid-template-columns: 1fr 1fr 1fr;
            }

            .label_op_2 {
                display: grid;
                grid-template-columns: 1fr;
            }

            .pay_ser_b {
                display: grid;
                grid-template-columns: 1fr;
                align-self: center;
            }
        </style>

        @if(auth()->user()->role == 1 || auth()->user()->role == 2)    
        <div id="pay_ser">
            <h1>Pagos de servicios</h1>
            <form action="{{route('cliente.pay_services')}}" method="post" class="pay_ser_form">
                <div class="service_pay">
                    @csrf
                    @method('put')
                    <label>
                        Cliente<br>
                        <input type="text" name="name" required>
                    </label>
                    <label>
                        Zona<br>
                        <input type="text" name="dir" required>
                    </label>
                    <label class="label_op">
                        <div class="label_op_2">
                            Fibra
                            <input type="radio" name="op" value="fb" id="fb" required onclick="add_op(),bye_i()">
                        </div>
                        <div class="label_op_2">
                            Antena
                            <input type="radio" name="op" value="at" id="at" required onclick="add_op(),bye_f()">
                        </div>
                        <div class="label_op_2">
                            Otros
                            <input type="radio" name="op" value="ot" id="ot" required onclick="remove_op()">
                        </div>
                        <script>
                            function remove_op() {
                                let at_1 = document.querySelector('#at_1').removeAttribute('required', '');
                                let at_2 = document.querySelector('#at_2').removeAttribute('required', '');
                                document.getElementById('sel_plan').disabled = true;
                                document.getElementById('sel_ser').disabled = true;
                                document.getElementById('sel_plan').selectedIndex = "0";
                            }

                            function add_op() {
                                let at_1 = document.querySelector('#at_1').setAttribute('required', '');
                                let at_2 = document.querySelector('#at_2').setAttribute('required', '');
                            }

                            function bye_i(){
                                let option_selected_i = document.getElementsByClassName('inalambrico');
                                let option_selected_1 = document.getElementsByClassName('fibra');
                                document.getElementById('sel_plan').disabled = false;
                                let op_selected_1 = document.getElementsByClassName('op_selected_1').value = '1MB'
                                document.getElementById('sel_ser').disabled = false;
                                
                                document.getElementById('sel_plan').selectedIndex = "0";

                                for(i=0; i<=option_selected_i.length - 1; i++){
                                    console.log(option_selected_i[i])
                                    option_selected_i[i].classList.replace("on", "off");
                                }

                                for(j=0; j<=option_selected_1.length - 1; j++){
                                    console.log(option_selected_1[j])
                                    option_selected_1[j].classList.replace("off", "on");
                                }                    
                            }

                            function bye_f(){
                                let option_selected_f = document.getElementsByClassName('fibra');
                                let option_selected_0 = document.getElementsByClassName('inalambrico');
                                document.getElementById('sel_plan').disabled = false;
                                let op_selected_1 = document.getElementsByClassName('op_selected_1').value = '1MB'
                                document.getElementById('sel_plan').selectedIndex = "0";
                                document.getElementById('sel_ser').disabled = false;

                                for(k=0; k<=option_selected_f.length - 1; k++){
                                    console.log(option_selected_f[k])
                                    option_selected_f[k].classList.replace("on", "off");
                                } 

                                for(l=0; l<=option_selected_0.length - 1; l++){
                                    console.log(option_selected_0[l])
                                    option_selected_0[l].classList.replace("off", "on");
                                }
                            }
                        </script>
                    </label>
                    <label>
                        Plan <br>
                        <select name="plan" id="sel_plan" disabled>
                            <option class="op_selected_1">Seleccione una option</option>
                            @foreach($planes as $plan)
                                @if($plan->type == 0)
                                    <option value="{{$plan->id}}" class="on inalambrico">{{$plan->nombre_de_plan}}</option>
                                @else
                                    <option value="{{$plan->id}}" class="on fibra">{{$plan->nombre_de_plan}}</option>
                                @endif
                            @endforeach
                        </select>
                    </label>
                    <label>
                        Servidor <br>
                        <select name="servidor" id="sel_ser" disabled>
                            <option value="cerro">Cerro</option>
                            <option value="rancho_grande">Rancho grande</option>
                            <option value="santa_cruz">Santa Cruz fibra</option>
                            <option value="23_enero">23 de enero</option>
                            <option value="Default" selected>Default</option>
                        </select>
                    </label>
                    <label>
                        Detalles<br>
                        <input type="text" name="conc" required>
                    </label>
                    <label>
                        Cedula<br>
                        <input type="text" id="at_1" name="at_1">
                    </label>
                    <label>
                        Telefono<br>
                        <input type="text" id="at_2" name="at_2">
                    </label>
                    <label>
                        Fecha de pago<br>
                        <input type="date" name="day" required value="{{date('Y-m-d',strtotime(date('Y-m-d')))}}">
                    </label>
                    <label>
                        <label>
                            Cobrador: <br>
                            <select class="reg" name="reg">
                                <option value="marco escala">Marco Escala</option>
                                <option value="marco antonio">Marco Antonio</option>
                                <option value="yurbi laya">Yurbis Laya</option>
                                <option value="kennerth salazar">Kennerth Salazar</option>
                                <option value="rossana">Rossana</option>
                                <option value="eduardo figueroa">Eduardo Figueroa</option>
                                <option value="eduardo granadillo">Eduardo Granadillo</option>
                                <option value="toto">Toto</option>
                                <option value="tototito">Tototito</option>
                                <option value="taquilla">Taquilla</option>
                                <option value="luis cotico">Cotico</option>
                                <option value="el gordo">El gordo</option>
                                <option value="jean carlos">Jean Carlos</option>
                                <option value="guillermo escala">Guillermo Escala</option>
                            </select>
                        </label>
                    </label>
                    <input type="text" placeholder="Dolar" id="d" name="d" onkeypress="return valideKey(event);">
                    <input type="text" placeholder="Bolivar" id="bs_ser" name="bs" onkeyup="pm_ref_serv()" onkeypress="return valideKey(event);">
                    <input type="text" placeholder="Zelle A" id="d_za" name="za" onkeypress="return valideKey(event);">
                    <input type="text" placeholder="Zelle B" id="d_zb" name="zb" onkeypress="return valideKey(event);">
                    <input type="text" placeholder="Referencia del pagomovil" pattern="^[0-9]{5}$" value="" name="pm_ref_ser" id="pm_ref_ser"><small id="show_alert_ser" style="color:red; display:none;">Referencia del pagomovil obligatoria y debe estar en entre el rango 10000 - 99999*</small>
                    <input type="hidden" name="tasa" value="{{$tasa[0]['tasa'],2}}">

                    <style>
                        #pm_ref_ser, .report_ser{
                            height: fit-content;
                        }
                    </style>

                    <div class="calculadora">
                        <p class="cal_div"></p>
                        <p id="h1_dep"></p>
                        <p id="h2_dep"></p>
                        <p id="h3_dep"></p>
                    </div>
                </div>
                <style>
                    .insta_c{
                        display: grid;
                        grid-template-columns: 1fr 1fr;
                        margin-bottom: 2%;
                    }
                </style>
                <div class="pay_ser_b">
                    <p>¿Cliente instalado?</p>
                    <div class="insta_c">
                        <label>Si<input type="radio" value="0" name="insta_c" required id=""></label>
                        <label>No<input type="radio" value="1" name="insta_c" id=""></label>
                    </div>
                    <input type="submit" class="sub_pay" value="Pagar">
                </div>
            </form>
        </div>
        @else
        <div><p>Privilegios insuficientes para realizar pagos de servicios</p></div>
        @endif

        <div class="pay_3">
            @if($fin == $inicio)
            <h1>{{date('d-m-Y',strtotime($inicio))}}</h1>
            <table border="1" class="main_table" style="background-color:#3C4349;">
                <tr>
                    <th>Dolares</th>
                    <th>Bolivares y PM</th>
                    <th>Zelle A</th>
                    <th>Zelle B</th>
                    <th>Numero de pago</th>
                </tr>
                <tr>
                    <td>{{$sum_d}}$</td>
                    <td>{{$sum_b + $sum_pm}}Bs</td>
                    <td>{{$sum_z}}$</td>
                    <td>{{$sum_z_2}}$</td>
                    <td>PAGOS TOTALES:{{$cuenta}} <br> PAGOS ELIMINADOS {{$cuenta_aux}} <br> PAGOS ACTIVOS {{$cuenta - $cuenta_aux}}</td>
                </tr>
                <tr>
                    <td colspan="6">{{$sum_b}}Bs al cambio: {{round($cambio,2)}}$</td>
                </tr>
            </table>
            @else
            <h1>{{date('d-m-Y',strtotime($inicio))}} y {{date('d-m-Y',strtotime($fin))}}</h1>
            <table border="1">
                <tr>
                    <th>Dolares</th>
                    <th>Bolivares</th>
                    <th>Zelle A</th>
                    <th>Zelle B</th>
                    <th>Numero de pagos</th>
                </tr>
                <tr>
                    <td>{{$sum_d}}$</td>
                    <td>{{$sum_b + $sum_pm}}Bs</td>
                    <td>{{$sum_z}}$</td>
                    <td>{{$sum_z_2}}$</td>
                    <td>{{$cuenta}}</td>
                </tr>
                <tr>
                    <td colspan="6">{{$sum_b}}Bs al cambio: {{round($cambio,2)}}$</td>
                </tr>
                <tr>
                    <td class="tot" colspan="6">Total: {{round($cambio + ($sum_z + $sum_z_2 + $sum_d),2)}}$</td>
                </tr>
            </table>

            @endif <br> <br>
            <div style="padding: 3%;">
                <a href="{{route('cliente.dup_ref')}}" target="_blank" class="btn-op">Ver referencias duplicadas</a>
                <a href="http://190.120.248.192:56001/control_de_pago/public/evento_diario?date={{date('Y-m-d')}}" target="_blank" class="btn-op">Evento del dia</a>
            </div>
            <div id="tasa_pic">
                <form action="{{route('cliente.tasa')}}" class="tasa" method="POST">
                    @csrf
                    @method('put')
                    <label>
                        Cambiar tasa:
                        <input type="text" min="1" max="100" value="{{$tasa[0]['tasa'],2}}" name="tasa">
                    </label>
                    <input type="submit" class="sub_pay" value="Cambiar tasa">
                </form>
            </div>
            <style>
                .dup_pay{
            width: 150px;
        }
            </style>
            <div class="info_me">
                <a href="{{route('cliente.rep_inf')}}" target="_blank" class="btn-op" style="text-align: center;">Imprimir información</a>
                <form action="{{route('cliente.pay_ref')}}" method="GET" target="_blank">
                    @csrf
                    <input type="hidden" name="date_in" id="" value="{{date('d-m-Y',strtotime($inicio))}}">
                    <input type="hidden" name="date_out" id="" value="{{date('d-m-Y',strtotime($fin))}}">
                <input class="btn-op dup_pay" style="text-align: center;" type="submit" value="Ver pagos duplicados">
            </form>
            </div>
        </div>
    </div><br>
    @if($mensaje === "")
    <table border="1" class="main_table">
        <tr class="main_tr">
            <th>#</th>
            <th>Usuario</th>
            <th>Cobrador</th>
            <th>Fecha de pago</th>
            <th>Fecha de corte</th>
            <th>Codigo del pago</th>
            <th>Cliente</th>
            <th>Zona</th>
            <th>Telefono</th>
            <th>Cedula</th>
            <th>Plan</th>
            <th>Concepto</th>
            <th>Pago en dolares</th>
            <th>Pago en Bolivares</th>
            <th>Pagomovil</th>
            <th>Pago en Zelle A</th>
            <th>Pago en Zelle B</th>
            <th>tasa</th>
            <th>total $</th>
            <th colspan="2">Opciones</th>
            <th class="v_1">Verificar</th>
        </tr>
        @foreach($pagos as $pago)
        @continue($pago->paid === 'Sin pagar')
        @if($table % 2 == 0)
        <tr style="background-color:#3C4349;">
            <td>{{$table+1}}</td>
            <td>{{$pago->cobrado_por}}</td>
            <td>
                <form action="{{route('cliente.report_update')}}" method="POST">
                    @csrf
                    @method('put')
                    <input type="hidden" name="val" value="{{$pago->id}}">
                    @if($pago->reg == NULL)
                    <p>No registrado</p>
                    @else
                    @if(auth()->user()->role == 1 || auth()->user()->role == 2)
                    <select class="reg style_sel" name="reg" onchange="this.form.submit()">
                        @if($pago->reg == "marco escala")
                        <option value="marco escala" id="me" selected>Marco Escala</option>
                        @else
                        <option value="marco escala" id="me">Marco Escala</option>
                        @endif

                        @if($pago->reg == "marco antonio")
                        <option value="marco antonio" id="ma" selected>Marco Antonio</option>
                        @else
                        <option value="marco antonio" id="ma">Marco Antonio</option>
                        @endif

                        @if($pago->reg == "yurbis laya")
                        <option value="yurbis laya" id="yl" selected>Yurbis Laya</option>
                        @else
                        <option value="yurbis laya" id="yl">Yurbis Laya</option>
                        @endif

                        @if($pago->reg == "kennerth salazar")
                        <option value="kennerth salazar" id="ks" selected>Kennerth Salazar</option>
                        @else
                        <option value="kennerth salazar" id="ks">Kennerth Salazar</option>
                        @endif

                        @if($pago->reg == "rossana")
                        <option value="rossana" id="ks" selected>Rossana</option>
                        @else
                        <option value="rossana" id="ks">Rossana</option>
                        @endif

                        @if($pago->reg == "eduardo figueroa")
                        <option value="eduardo figueroa" id="ef" selected>Eduardo Figueroa</option>
                        @else
                        <option value="eduardo figueroa" id="ef">Eduardo Figueroa</option>
                        @endif

                        @if($pago->reg == "taquilla")
                        <option value="taquilla" id="ef" selected>Taquilla</option>
                        @else
                        <option value="taquilla" id="ef">Taquilla</option>
                        @endif

                        @if($pago->reg == "eduardo granadillo")
                        <option value="eduardo granadillo" id="eg" selected>Eduardo Granadillo</option>
                        @else
                        <option value="eduardo granadillo" id="eg">Eduardo Granadillo</option>
                        @endif

                        @if($pago->reg == "toto")
                        <option value="toto" id="to" selected>Toto</option>
                        @else
                        <option value="toto" id="to">Toto</option>
                        @endif

                        @if($pago->reg == "tototito")
                        <option value="tototito" id="tt" selected>Tototito</option>
                        @else
                        <option value="tototito" id="tt">Tototito</option>
                        @endif

                        @if($pago->reg == "luis cotico")
                        <option value="luis cotico" id="co" selected>Cotico</option>
                        @else
                        <option value="luis cotico" id="co">Cotico</option>
                        @endif

                        @if($pago->reg == "el gordo")
                        <option value="el gordo" id="g" selected>El gordo</option>
                        @else
                        <option value="el gordo" id="g">El gordo</option>
                        @endif

                        @if($pago->reg == "jean carlos")
                        <option value="jean carlos" id="jc" selected>Jean Carlos</option>
                        @else
                        <option value="jean carlos" id="jc">Jean Carlos</option>
                        @endif

                        @if($pago->reg == "guillermo escala")
                        <option value="guillermo escala" id="ge" selected>Guillermo Escala</option>
                        @else
                        <option value="guillermo escala" id="ge">Guillermo Escala</option>
                        @endif
                    </select>
                    @else
                    <p>{{$pago->reg}}</p>
                    @endif
                    @endif
                </form>
            </td>
            <td>{{$pago->updated_at}}</td>
            @if($pago->cut != NULL)
            <td>{{date("d-m-Y", strtotime($pago->cut))}}</td>
            @else
            <td>N/A</td>
            @endif
            @if($pago->concepto != NULL)
            <td>serv_{{$pago->id}} {{strtoupper($pago->op)}}</td>
            @else
            <td>ptwf_{{$pago->codigo_pago}}</td>
            @endif
            <td>{{$pago->nombre}}</td>
            <td>{{$pago->direccion}}</td>
            @if($pago->tlf == NULL)
            <td>No registrado</td>
            @else
            <td>{{$pago->tlf}}</td>
            @endif
            @if($pago->cedula == NULL)
            <td>No registrado</td>
            @else
            <td>{{$pago->cedula}}</td>
            @endif
            @if($pago->concepto != NULL)
            <td>N/A</td>
            <td>{{$pago->concepto}}</td>
            @else
            <td>{{$pago->mb}}</td>
            <td>N/A</td>
            @endif
            <td>{{round($pago->monto_d + 0)}}$</td>
            <td>
                {{bcdiv($pago->monto_bs, '1', 2) + 0}}BS <br>
                @if($pago->pm_ref != 0 || $pago->pm_ref != NULL)
                Ref: {{$pago->pm_ref}}       
                @endif
            </td>
            <td>{{round($pago->monto_pm + 0)}}bs</td>
            <td>{{round($pago->monto_z_1 + 0)}}$</td>
            <td>{{round($pago->monto_z_2 + 0)}}$</td>
            <td>{{$pago->tasa}}$</td>
            <td>{{round($pago->total_d + 0)}} $</td>
            <td>
                @if($pago->active == 1)
                    @if(auth()->user()->role == 1 || auth()->user()->role == 2)
                    <form action="{{route('cliente.report_delete',$pago->id)}}" class="formulario" method="post">
                        @method('put')
                        @csrf
                        <input type="submit" class="delete_button" value="X">
                    </form>
                    @else
                    <div class="formulario">
                        <button class="delete_button" onclick="alert('Privilegios insuficientes')"></button>
                        </div>
                    @endif
                @else
                    <p style="color:red;" title="Pago desactivado por el usuario: {{$pago->reg_del}}">X</p>
                @endif
            </td>
            <td><a href="{{route('cliente.fac',$pago->id)}}" class="fac_gen">Generar recibo</a></td>
            <td class="v_2"><input type="checkbox"></td>
        </tr>
        <?php $table += 1; ?>
        @elseif($table % 2 == 1)
        <tr style="background-color:#2B3336;">
            <td>{{$table+1}}</td>
            <td>{{$pago->cobrado_por}}</td>
            <td>
                <form action="{{route('cliente.report_update')}}" method="POST">
                    @csrf
                    @method('put')
                    <input type="hidden" name="val" value="{{$pago->id}}">
                    @if($pago->reg == NULL)
                    <p>No registrado</p>
                    @else
                    @if(auth()->user()->role == 1 || auth()->user()->role == 2)
                    <select class="reg style_sel" name="reg" onchange="this.form.submit()">
                        @if($pago->reg == "marco escala")
                        <option value="marco escala" id="me" selected>Marco Escala</option>
                        @else
                        <option value="marco escala" id="me">Marco Escala</option>
                        @endif

                        @if($pago->reg == "marco antonio")
                        <option value="marco antonio" id="ma" selected>Marco Antonio</option>
                        @else
                        <option value="marco antonio" id="ma">Marco Antonio</option>
                        @endif

                        @if($pago->reg == "yurbis laya")
                        <option value="yurbis laya" id="yl" selected>Yurbis Laya</option>
                        @else
                        <option value="yurbis laya" id="yl">Yurbis Laya</option>
                        @endif

                        @if($pago->reg == "kennerth salazar")
                        <option value="kennerth salazar" id="ks" selected>Kennerth Salazar</option>
                        @else
                        <option value="kennerth salazar" id="ks">Kennerth Salazar</option>
                        @endif

                        @if($pago->reg == "rossana")
                        <option value="rossana" id="ks" selected>Rossana</option>
                        @else
                        <option value="rossana" id="ks">Rossana</option>
                        @endif

                        @if($pago->reg == "eduardo figueroa")
                        <option value="eduardo figueroa" id="ef" selected>Eduardo Figueroa</option>
                        @else
                        <option value="eduardo figueroa" id="ef">Eduardo Figueroa</option>
                        @endif

                        @if($pago->reg == "eduardo granadillo")
                        <option value="eduardo granadillo" id="eg" selected>Eduardo Granadillo</option>
                        @else
                        <option value="eduardo granadillo" id="eg">Eduardo Granadillo</option>
                        @endif

                        @if($pago->reg == "toto")
                        <option value="toto" id="to" selected>Toto</option>
                        @else
                        <option value="toto" id="to">Toto</option>
                        @endif

                        @if($pago->reg == "tototito")
                        <option value="tototito" id="tt" selected>Tototito</option>
                        @else
                        <option value="tototito" id="tt">Tototito</option>
                        @endif

                        @if($pago->reg == "luis cotico")
                        <option value="luis cotico" id="co" selected>Cotico</option>
                        @else
                        <option value="luis cotico" id="co">Cotico</option>
                        @endif

                        @if($pago->reg == "taquilla")
                        <option value="taquilla" id="ta" selected>Taquilla</option>
                        @else
                        <option value="taquilla" id="ta">Taquilla</option>
                        @endif

                        @if($pago->reg == "el gordo")
                        <option value="el gordo" id="g" selected>El gordo</option>
                        @else
                        <option value="el gordo" id="g">El gordo</option>
                        @endif

                        @if($pago->reg == "jean carlos")
                        <option value="jean carlos" id="jc" selected>Jean Carlos</option>
                        @else
                        <option value="jean carlos" id="jc">Jean Carlos</option>
                        @endif

                        @if($pago->reg == "guillermo escala")
                        <option value="guillermo escala" id="ge" selected>Guillermo Escala</option>
                        @else
                        <option value="guillermo escala" id="ge">Guillermo Escala</option>
                        @endif
                        @else
                            <p>{{$pago->reg}}</p>
                        @endif
                </form>
            </td>
            @endif

            <td>{{$pago->updated_at}}</td>
            @if($pago->cut != NULL)
            <td>{{date("d-m-Y", strtotime($pago->cut))}}</td>
            @else
            <td>N/A</td>
            @endif
            @if($pago->concepto != NULL)
            <td>serv_{{$pago->id}} {{$pago->op}}</td>
            @else
            <td>ptwf_{{$pago->codigo_pago}} {{strtoupper($pago->op)}}</td>
            @endif
            <td>{{$pago->nombre}}</td>
            <td>{{$pago->direccion}}</td>
            @if($pago->tlf == NULL)
            <td>No registrado</td>
            @else
            <td>{{$pago->tlf}}</td>
            @endif
            @if($pago->cedula == NULL)
            <td>No registrado</td>
            @else
            <td>{{$pago->cedula}}</td>
            @endif
            @if($pago->concepto != NULL)
            <td>N/A</td>
            <td>{{$pago->concepto}}</td>
            @else
            <td>{{$pago->mb}}</td>
            <td>N/A</td>
            @endif
            <td>{{round($pago->monto_d + 0)}}$</td>
            <td>{{bcdiv($pago->monto_bs, '1', 2) + 0}}BS <br>
                @if($pago->pm_ref != 0 || $pago->pm_ref != NULL)
                Ref: {{$pago->pm_ref}}       
                @endif
            </td>
            <td>{{round($pago->monto_pm + 0)}}bs</td>
            <td>{{round($pago->monto_z_1 + 0)}}$</td>
            <td>{{round($pago->monto_z_2 + 0)}}$</td>
            <td>{{$pago->tasa}}$</td>
            <td>{{round($pago->total_d)}}$</td>
            <td style="display: none;">
                <form action="{{route('cliente.report_delete',$pago->id)}}" class="formulario" method="post">
                    @method('put')
                    @csrf
                    <input type="submit" class="delete_button" value="Borrar">
                </form>
            </td>
            <td>
            @if($pago->active == 1)
                @if(auth()->user()->role == 1 || auth()->user()->role == 2)
                    <form action="{{route('cliente.report_delete',$pago->id)}}" class="formulario" method="post">
                        @method('put')
                        @csrf
                        <input type="submit" class="delete_button" value="X">
                    </form>
                    @else
                    <div class="formulario">
                        <button class="delete_button" onclick="alert('Privilegios insuficientes')"></button>
                        </div>
                    @endif
            @else
                <p style="color:red;" title="Pago desactivado por el usuario: {{$pago->reg_del}}">X</p>
            @endif
            </td>
            <td><a href="{{route('cliente.fac',$pago->id)}}" class="fac_gen">Generar recibo</a></td>
            <td class="v_3"><input type="checkbox"></td>
        </tr>
        <?php $table += 1; ?>
        @endif
        @endforeach
    </table>
    @else
    <p>Ingrese un rango valido</p>
    @endif

    </div>

    <script>
        function toggle_sc() {
            const radio_but_a = document.getElementById('a').checked;
            const radio_but_b = document.getElementById('b');
            const label_report = document.getElementById('label_report');

            console.log("radio 1 " + radio_but_a.checked);
            console.log("radio 2 " + radio_but_b.checked);

            const screem_1 = document.getElementById('screem_1');
            const screem_2 = document.getElementById('screem_2');

            if (radio_but_a) {
                console.log("cambiando a vista 2")
                screem_1.classList.replace("off", "on");
                screem_2.classList.replace("on", "off");
                label_report.innerHTML = "Reporte general";
                console.log(document.querySelector(".pic"))

            } else {
                console.log("cambiando a vista 1")
                screem_1.classList.replace("on", "off");
                screem_2.classList.replace("off", "on");
                label_report.innerHTML = "Resumen detallado";
                console.log(document.querySelector(".pic"))
            }
        }
    </script>

    <style>
        .summary{
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: baseline;
        }

        .item_pay{
            border: solid 1px white;
            margin-top: 25px;
            text-align: center;
        }
    </style>

    <div class="off summary" id="screem_2">
        <div class="pic" id="pic">
            <label>
                Modo foto<br>
                <input type="radio" name="pic" onclick="pic('yes')">
                <input type="radio" name="pic" onclick="pic('no')">
            </label>
        </div>
        <div>
            <form action="{{route('cliente.pay.customer')}}">
                <input type="hidden" name="print" value="ok">
                <input type="hidden" value="{{$report_date}}" name="report_date_total">
                <input type="submit" value="Imprimir">
            </form>
        </div>
        <div></div>

        <div class="item_pay"><h4>Marco Escala</h4>
        <table border="1">
            <tr>
                <th>Cliente</th>
                <th>Dolares</th>
                <th>Bolivares</th>
                <th>Zelle A</th>
                <th>Zelle B</th>
                <th>Total</th>
            </tr>
            @foreach($pagos_marco_escala as $marco_escala)
            <tr>
                <td>{{$marco_escala->nombre}}</td>
                <td>{{$marco_escala->monto_d + 0}}$</td>
                <td>{{$marco_escala->monto_bs + 0}}Bs.</td>
                <td>{{$marco_escala->monto_z_1 + 0}}$</td>
                <td>{{$marco_escala->monto_z_2 + 0}}$</td>
                <td>{{$marco_escala->total_d + 0}}$</td>
                <div style="display: none;">{{$total_recaudado[0] += $marco_escala->total_d}}</div>
            </tr>
            @endforeach
            <tr>
                <td colspan="6">Total recaudado: {{$total_recaudado[0]}}$</td>
            </tr>
        </table></div>

        <div class="item_pay"><h4>Marco Antonio</h4>
        <table border="1">
            <tr>
                <th>Cliente</th>
                <th>Dolares</th>
                <th>Bolivares</th>
                <th>Zelle A</th>
                <th>Zelle B</th>
                <th>Total</th>
            </tr>
            @foreach($pagos_marco_antonio as $marco_antonio)
            <tr>
                <td>{{$marco_antonio->nombre}}</td>
                <td>{{$marco_antonio->monto_d + 0}}$</td>
                <td>{{$marco_antonio->monto_bs + 0}}Bs.</td>
                <td>{{$marco_antonio->monto_z_1 + 0}}$</td>
                <td>{{$marco_antonio->monto_z_2 + 0}}$</td>
                <td>{{$marco_antonio->total_d + 0}}$</td>
                <div style="display: none;">{{$total_recaudado[1] += $marco_antonio->total_d}}</div>
            </tr>
            @endforeach
            <tr>
                <td colspan="6">Total recaudado: {{$total_recaudado[1]}}$</td>
            </tr>
        </table></div>

        <div class="item_pay"><h4>Yurbis Laya</h4>
        <table border="1">
            <tr>
                <th>Cliente</th>
                <th>Dolares</th>
                <th>Bolivares</th>
                <th>Zelle A</th>
                <th>Zelle B</th>
                <th>Total</th>
            </tr>
            @foreach($pagos_yurbis_laya as $yurbis_laya)
            <tr>
                <td>{{$yurbis_laya->nombre}}</td>
                <td>{{$yurbis_laya->monto_d + 0}}$</td>
                <td>{{$yurbis_laya->monto_bs + 0}}Bs.</td>
                <td>{{$yurbis_laya->monto_z_1 + 0}}$</td>
                <td>{{$yurbis_laya->monto_z_2 + 0}}$</td>
                <td>{{$yurbis_laya->total_d + 0}}$</td>
                <div style="display: none;">{{$total_recaudado[2] += $yurbis_laya->total_d}}</div>
            </tr>
            @endforeach
            <tr>
                <td colspan="6">Total recaudado: {{$total_recaudado[2]}}$</td>
            </tr>
        </table></div>

        <div class="item_pay"><h4>Kennerth Salazar</h4>
        <table border="1">
            <tr>
                <th>Cliente</th>
                <th>Dolares</th>
                <th>Bolivares</th>
                <th>Zelle A</th>
                <th>Zelle B</th>
                <th>Total</th>
            </tr>
            @foreach($pagos_kennerth_salazar as $kennerth_salazar)
            <tr>
                <td>{{$kennerth_salazar->nombre}}</td>
                <td>{{$kennerth_salazar->monto_d + 0}}$</td>
                <td>{{$kennerth_salazar->monto_bs + 0}}Bs.</td>
                <td>{{$kennerth_salazar->monto_z_1 + 0}}$</td>
                <td>{{$kennerth_salazar->monto_z_2 + 0}}$</td>
                <td>{{$kennerth_salazar->total_d + 0}}$</td>
                <div style="display: none;">{{$total_recaudado[3] += $kennerth_salazar->total_d}}</div>
            </tr>
            @endforeach
            <tr>
                <td colspan="6">Total recaudado: {{$total_recaudado[3]}}$</td>
            </tr>
        </table></div>

        <div class="item_pay"><h4>Maria Primera</h4>
        <table border="1">
            <tr>
                <th>Cliente</th>
                <th>Dolares</th>
                <th>Bolivares</th>
                <th>Zelle A</th>
                <th>Zelle B</th>
                <th>Total</th>
            </tr>
            @foreach($pagos_maria_primera as $maria_primera)
            <tr>
                <td>{{$maria_primera->nombre}}</td>
                <td>{{$maria_primera->monto_d + 0}}$</td>
                <td>{{$maria_primera->monto_bs + 0}}Bs.</td>
                <td>{{$maria_primera->monto_z_1 + 0}}$</td>
                <td>{{$maria_primera->monto_z_2 + 0}}$</td>
                <td>{{$maria_primera->total_d + 0}}$</td>
                <div style="display: none;">{{$total_recaudado[4] += $maria_primera->total_d}}</div>
            </tr>
            @endforeach
            <tr>
                <td colspan="6">Total recaudado: {{$total_recaudado[4]}}$</td>
            </tr>
        </table></div>

        <div class="item_pay"><h4>Jean Carlos</h4>
        <table border="1">
            <tr>
                <th>Cliente</th>
                <th>Dolares</th>
                <th>Bolivares</th>
                <th>Zelle A</th>
                <th>Zelle B</th>
                <th>Total</th>
            </tr>
            @foreach($pagos_jean_carlos as $jean_carlos)
            <tr>
                <td>{{$jean_carlos->nombre}}</td>
                <td>{{$jean_carlos->monto_d + 0}}$</td>
                <td>{{$jean_carlos->monto_bs + 0}}Bs.</td>
                <td>{{$jean_carlos->monto_z_1 + 0}}$</td>
                <td>{{$jean_carlos->monto_z_2 + 0}}$</td>
                <td>{{$jean_carlos->total_d + 0}}$</td>
                <div style="display: none;">{{$total_recaudado[5] += $jean_carlos->total_d}}</div>
            </tr>
            @endforeach
            <tr>
                <td colspan="6">Total recaudado: {{$total_recaudado[5]}}$</td>
            </tr>
        </table></div>

        <div class="item_pay"><h4>Eduardo Figueroa</h4>
        <table border="1">
            <tr>
                <th>Cliente</th>
                <th>Dolares</th>
                <th>Bolivares</th>
                <th>Zelle A</th>
                <th>Zelle B</th>
                <th>Total</th>
            </tr>
            @foreach($pagos_eduardo_figueroa as $eduardo_figueroa)
            <tr>
                <td>{{$eduardo_figueroa->nombre}}</td>
                <td>{{$eduardo_figueroa->monto_d + 0}}$</td>
                <td>{{$eduardo_figueroa->monto_bs + 0}}Bs.</td>
                <td>{{$eduardo_figueroa->monto_z_1 + 0}}$</td>
                <td>{{$eduardo_figueroa->monto_z_2 + 0}}$</td>
                <td>{{$eduardo_figueroa->total_d + 0}}$</td>
                <div style="display: none;">{{$total_recaudado[6] += $eduardo_figueroa->total_d}}</div>
            </tr>
            @endforeach
            <tr>
                <td colspan="6">Total recaudado: {{$total_recaudado[6]}}$</td>
            </tr>
        </table></div>

        <div class="item_pay"><h4>Eduardo Granadillo</h4>
        <table border="1">
            <tr>
                <th>Cliente</th>
                <th>Dolares</th>
                <th>Bolivares</th>
                <th>Zelle A</th>
                <th>Zelle B</th>
                <th>Total</th>
            </tr>
            @foreach($pagos_eduardo_granadillo as $eduardo_granadillo)
            <tr>
                <td>{{$eduardo_granadillo->nombre}}</td>
                <td>{{$eduardo_granadillo->monto_d + 0}}$</td>
                <td>{{$eduardo_granadillo->monto_bs + 0}}Bs.</td>
                <td>{{$eduardo_granadillo->monto_z_1 + 0}}$</td>
                <td>{{$eduardo_granadillo->monto_z_2 + 0}}$</td>
                <td>{{$eduardo_granadillo->total_d + 0}}$</td>
                <div style="display: none;">{{$total_recaudado[7] += $eduardo_granadillo->total_d}}</div>
            </tr>
            @endforeach
            <tr>
                <td colspan="6">Total recaudado: {{$total_recaudado[7]}}$</td>
            </tr>
        </table></div>

        <div class="item_pay"><h4>Otros cobradores</h4>
        <table border="1">
            <tr>
                <th>Cobrador</th>
                <th>Cliente</th>
                <th>Dolares</th>
                <th>Bolivares</th>
                <th>Zelle A</th>
                <th>Zelle B</th>
            </tr>
            @foreach($pagos_otros as $pagos_otro)
            <tr>
                <td>{{$pagos_otro->reg}}</td>
                <td>{{$pagos_otro->nombre}}</td>
                <td>{{$pagos_otro->monto_d + 0}}$</td>
                <td>{{$pagos_otro->monto_bs + 0}}Bs.</td>
                <td>{{$pagos_otro->monto_z_1 + 0}}$</td>
                <td>{{$pagos_otro->monto_z_2 + 0}}$</td>
                <div style="display: none;">{{$total_recaudado[8] += $pagos_otro->total_d}}</div>
            </tr>
            @endforeach
            <tr>
                <td colspan="6">Total recaudado: {{$total_recaudado[8]}}$</td>
            </tr>
        </table></div>

        <div class="item_pay"><h4>Total (BS.) Efectivo y pagomovil recibido</h4>
        <table border="1">
            <tr>
                <th>Cobrador</th>
                <th>Cliente</th>
                <th>Bolivares</th>
            </tr>
            @foreach($pagos_bs as $pago_bs)
            @if($pago_bs->monto_bs != 0)
            <tr>
                <td>{{$pago_bs->reg}}</td>
                <td>{{$pago_bs->nombre}}</td>
                <td>{{$pago_bs->monto_bs + 0}}Bs.</td>
                <div style="display: none;">{{$total_recaudado[9] += $pago_bs->monto_bs}}</div>
            </tr>
            @endif
            @endforeach
            <tr>
                <td colspan="6">Total recaudado: {{$total_recaudado[9]}}Bs</td>
            </tr>
        </table></div>

        <div class="item_pay">
            <h4>Total recaudado por cobrador:</h4>
            <table border="1">
                <tr>
                    <th>Cobrador</th>
                    <th>Dolares</th>
                    <th>Bolivares</th>
                    <th>Zelle</th>
                    <th>Cantidad de pagos</th>
                </tr>
                <tr>
                    <td>Marco Escala</td>
                    <td>{{$total_marco[0] + 0}}$</td>
                    <td>{{$total_marco[1] + 0}}Bs</td>
                    <td>{{$total_marco[2] + 0}}$</td>
                    <td>{{$cantidad_marco}}</td>
                </tr>
                <tr>
                    <td>Marco Antonio</td>
                    <td>{{$total_marco_antonio[0] + 0}}$</td>
                    <td>{{$total_marco_antonio[1] + 0}}Bs</td>
                    <td>{{$total_marco_antonio[2] + 0}}$</td>
                    <td>{{$cantidad_marco_antonio}}</td>
                </tr>
                <tr>
                    <td>Yurbis Laya</td>
                    <td>{{$total_yurbis_laya[0] + 0}}$</td>
                    <td>{{$total_yurbis_laya[1] + 0}}Bs</td>
                    <td>{{$total_yurbis_laya[2] + 0}}$</td>
                    <td>{{$cantidad_yurbis_laya}}</td>
                </tr>
                <tr>
                    <td>Kennerth Salazar</td>
                    <td>{{$total_kennerth_salazar[0] + 0}}$</td>
                    <td>{{$total_kennerth_salazar[1] + 0}}Bs</td>
                    <td>{{$total_kennerth_salazar[2] + 0}}$</td>
                    <td>{{$cantidad_kennerth_salazar}}</td>
                </tr>
                <tr>
                    <td>Maria Primera</td>
                    <td>{{$total_maria_primera[0] + 0}}$</td>
                    <td>{{$total_maria_primera[1] + 0}}Bs</td>
                    <td>{{$total_maria_primera[2] + 0}}$</td>
                    <td>{{$cantidad_maria_primera}}</td>
                </tr>
                <tr>
                    <td>Eduardo Figueroa</td>
                    <td>{{$total_eduardo_figueroa[0] + 0}}$</td>
                    <td>{{$total_eduardo_figueroa[1] + 0}}Bs</td>
                    <td>{{$total_eduardo_figueroa[2] + 0}}$</td>
                    <td>{{$cantidad_eduardo_figueroa}}</td>
                </tr>
                <tr>
                    <td>Jean Carlos</td>
                    <td>{{$total_jean_carlos[0] + 0}}$</td>
                    <td>{{$total_jean_carlos[1] + 0}}Bs</td>
                    <td>{{$total_jean_carlos[2] + 0}}$</td>
                    <td>{{$cantidad_jean_carlos}}</td>
                </tr>
                <tr>
                    <td>Eduardo Granadillo</td>
                    <td>{{$total_eduardo_granadillo[0] + 0}}$</td>
                    <td>{{$total_eduardo_granadillo[1] + 0}}Bs</td>
                    <td>{{$total_eduardo_granadillo[2] + 0}}$</td>
                    <td>{{$cantidad_eduardo_granadillo}}</td>
                </tr>
            </table>
        </div>
    </div>  
</div>
    <script>
        $('.formulario').submit(function(e) {
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
    </script>

    <script>
        $('.tasa').submit(function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Cambiar valor de la tasa BCV?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            })
        });
    </script>

    <script>
        $('.service_pay').submit(function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Estas seguro?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Pagar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            })
        });
    </script>

    <style>
    .off{
        display: none;
    }
    </style>

    <script>

        $('.pay_ser_form').submit(function(e) {
        
        let form_1 = document.getElementById('d').value;
        let form_2 = document.getElementById('bs_ser').value;
        let form_3 = document.getElementById('d_za').value;
        let form_4 = document.getElementById('d_zb').value;
        
        e.preventDefault();
        Swal.fire({
            title: '¿Realizar pago?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Pagar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (parseFloat(form_1) > 0 || parseFloat(form_2) > 0 || parseFloat(form_3) > 0 || parseFloat(form_4) > 0) {
                if (result.isConfirmed) {
                    this.submit();
                }else{
                    e.preventDefault();
                }
            }else {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Debe especificar un monto',
                })
            }
        })
        });

    
        function pm_ref_serv(){
            let bs_ser = document.getElementById('bs_ser');
            let pm_ref_ser = document.getElementById('pm_ref_ser');
            let show_alert_ser = document.getElementById('show_alert_ser');
            let h1_dep = document.querySelector("#h1_dep");
            let h2_dep = document.querySelector("#h2_dep");
            let h3_dep = document.querySelector("#h3_dep");
            let cal_div = document.querySelector(".cal_div");

            let tasa = parseFloat({{$tasa[0]['tasa']}});

            let cambio = parseFloat(bs_ser.value / tasa);
            let iva = parseFloat(bs_ser.value * 0.16 / tasa);
            let total_d = parseFloat(bs_ser.value * 0.16) + parseFloat(bs_ser.value);

            if(bs_ser.value > 0){
                pm_ref_ser.style.display = "block"
                show_alert_ser.style.display = "block"
                pm_ref_ser.setAttribute("required", "");
            }else{
                pm_ref_ser.style.display = "none";
                pm_ref_ser.value = "";
                show_alert_ser.style.display = "block"
                pm_ref_ser.removeAttribute("required", "");
            }

            if(bs_ser.value > 0){
                h1_dep.innerHTML = "Al cambio BCV: " + (bs_ser.value / tasa).toFixed(2)  + "$";
                h2_dep.innerHTML = "16% de Iva: " + parseFloat(bs_ser.value * 0.16).toFixed(2) + "Bs." + " (" + parseFloat(bs_ser.value * 0.16 / tasa).toFixed(2) + "$)";
                h3_dep.innerHTML = "Total: " + parseFloat(total_d) + "Bs";
                cal_div.innerHTML = "Calculadora";
            }else{
                h1_dep.innerHTML = "";
                h2_dep.innerHTML = "";
                h3_dep.innerHTML = "";
                cal_div.innerHTML = "";
            }
        }
    </script>

</body>

</html>