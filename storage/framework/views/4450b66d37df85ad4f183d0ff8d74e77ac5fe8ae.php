<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de pago</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nanum+Myeongjo&display=swap" rel="stylesheet">
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('css/tuerquita.css')); ?>" rel="stylesheet">
    <script src="<?php echo e(asset('js/filter.js')); ?>"></script>
    <link href="css/registros.css" rel="stylesheet">
    <link rel="shortcut icon" sizes="60x60" href="/control_de_pago/public/img/favicon-16x16.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Nanum+Myeongjo&family=Nunito&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
</head>

<body id="body" style="background-image: url('/control_de_pago/public/img/background.jpg'); background-size: cover;">

    <style>
        .new_sec{
            position: absolute;
            display: grid;
            grid-template-columns: 10% 26% 13% 11% 2%;
            align-items: center;
            justify-content: end;
            top: 20%;
            right: 5%;
            width: 400px;
        }

        .add_new_user_sec{
            width: 35px;
            height: 35px;
        }

        #asc, #by, .ty, .can{
            box-shadow: #000 1px 0px 6px;
        }

        #asc{
            width: 12px;
            border-radius: 0 15px 15px 0;
        }

        #by{
            border-radius: 15px 0px 0px 15px;
        }

        .on{
            display: block;
        }

        .off{
            display: none;
        }
    </style>

    <div class="new_sec">
        <div class="add_new_user_sec">
            <?php if(auth()->user()->role == 1 || auth()->user()->role == 2): ?>
                <button style="cursor: pointer;" onclick="display_cn()" class="">
                    <label style="cursor: pointer;" class="">
                        <img src="img/new.png" style="cursor: pointer;" class="">
                    </label>
                </button>
            <?php else: ?>
                <button style="height: 88px;"></button>
            <?php endif; ?>
        </div>
        <form>
            <select class="" name="by" id="by" onchange="this.form.submit()" onchange="status()">
                <?php if($orde->status == "id"): ?>
                <option value="id" select>Id</option>
                <?php else: ?>
                <option value="id">Id</option>
                <?php endif; ?>
                <?php if($orde->status == "full_name"): ?>
                <option value="full_name" selected>Nombre</option>
                <?php else: ?>
                <option value="full_name">Nombre</option>
                <?php endif; ?>
                <?php if($orde->status == "dir"): ?>
                <option value="dir" selected>Direccion</option>
                <?php else: ?>
                <option value="dir">Direccion</option>
                <?php endif; ?>
                <?php if($orde->status == "day"): ?>
                <option value="day" selected>Fecha de pago</option>
                <?php else: ?>
                <option value="day">Fecha de pago</option>
                <?php endif; ?>
                <?php if($orde->status == "cut"): ?>
                <option value="cut" selected>Fecha de corte</option>
                <?php else: ?>
                <option value="cut">Fecha de corte</option>
                <?php endif; ?>
                <?php if($orde->status == "plan"): ?>
                <option value="plan" selected>Plan</option>
                <?php else: ?>
                <option value="plan">Plan</option>
                <?php endif; ?>
                <?php if($orde->status == "status"): ?>
                <option value="status" selected>Estado</option>
                <?php else: ?>
                <option value="status">Estado</option>
                <?php endif; ?>
                <?php if($orde->status == "destacated_com"): ?>
                <option value="destacated_com" selected>Destacados</option>
                <?php else: ?>
                <option value="destacated_com">Destacados</option>
                <?php endif; ?>
            </select>
        </form>

        <script>
            function select_type_insta() {
                let select_val = document.getElementById("select_type").value;
                let new_array = [];

                for (let i = 1; i <= <?php echo e($var_regs); ?>; i++) { //OJO AQUI CUANDO TERMINES, TIENES QUE CAMBIAR ESE 1276 POR LA VARIABLE CANTIDAD DE CLIENTES
                
                    new_array[i] = document.getElementById(i);
                    console.log(new_array[i])
                
                    if (select_val == 'fb' && new_array[i].innerHTML.includes('123_fb')) {
                        new_array[i].classList.replace("off", "on");
                    } else if (select_val == 'at' && new_array[i].innerHTML.includes('123_at')) {
                        new_array[i].classList.replace("off", "on");
                    } else if (select_val == 'all') {
                        new_array[i].classList.replace("off", "on");
                    } else {
                        new_array[i].classList.replace("on", "off");
                    }
                }
            }
        </script>
        <form class="ty">
            <select name="type_inst" onchange="select_type_insta()" id="select_type">
                <option value="all">Todos</option>
                <option value="fb">Fibra</option>
                <option value="at">Antena</option>
            </select>
        </form>
        <form class="can">
            <select name="cuenta" class="" id="cuenta" onchange="this.form.submit()" onchange="count()">
                <?php if($orde->reg == "50"): ?>
                <option value="50" selected>50</option>
                <?php else: ?>
                <option value="50">50</option>
                <?php endif; ?>
                <?php if($orde->reg == "100"): ?>
                <option value="100" selected>100</option>
                <?php else: ?>
                <option value="100">100</option>
                <?php endif; ?>
                <?php if($orde->reg == "200"): ?>
                <option value="200" selected>200</option>
                <?php else: ?>
                <option value="200">200</option>
                <?php endif; ?>
                <?php if($orde->reg == "300"): ?>
                <option value="300" selected>300</option>
                <?php else: ?>
                <option value="300">300</option>
                <?php endif; ?>
                <?php if($orde->reg == $totales): ?>
                <option value="<?php echo e($totales); ?>" selected>Todos</option>
                <?php else: ?>
                <option value="<?php echo e($totales); ?>">Todos</option>
                <?php endif; ?>
            </select>
        </form>
        <form class="">
            <select class="" name="asc" id="asc" onchange="this.form.submit()" onchange="asc_desc()">
                <?php if($orde->sort == "asc"): ?>
                <option value="asc" selected>↑</option>
                <?php else: ?>
                <option value="asc">↑</option>
                <?php endif; ?>
                <?php if($orde->sort == "desc"): ?>
                <option value="desc" selected>↓</option>
                <?php else: ?>
                <option value="desc">↓</option>
                <?php endif; ?>
            </select>
        </form>
    </div>

    <style>
        .find {
            width: 100%;
            border: solid 1px #000;
            border-radius: 10px;
            margin-right: 20px;
            padding-left: 10px;
            background-image: url('/control_de_pago/public/img/lupa.png');
            background-repeat: no-repeat;
            background-position: 98% 70%;
        }

        .find:focus {
            background-image: url('');
        }

        .resume {
            position: absolute;
            top: 10%;
            right: 9%;
            width: 150px;
            height: 150px;
        }

        .deuda {
            font-weight: bold;
        }

        .enviar {
            width: -webkit-fill-available;
            text-align: center;
        }

        .btn-cerrar {
            border: #000 solid 1px;
            padding: 4px 6px 4px 6px;
            padding: 10px;
            border-radius: 5px;
            font-weight: 100;
            font-style: normal;
            text-align: center;
        }

        .btn-cerrar:hover {
            cursor: pointer;
            color: #fff;
            background-color: #000;
        }

        .destacated {
            width: 30px;
            margin: auto;
            cursor: pointer;
        }

        .ista_type_help {
            background-image: url('/control_de_pago/public/img/insta_help.png');
            background-repeat: no-repeat;
            background-size: contain;
            background-position-x: right;
            background-size: 18px 18px;
        }

        .ista_type_help:hover {
            cursor: pointer;
        }
    </style>

    <?php if(session('registrar') == "ok"): ?>
    <script>
        Swal.fire(
            'Registrado!',
            'Se ha registrado un nuevo cliente',
            'con exito'
        )
    </script>
    <?php endif; ?>

    <script type="text/javascript">
        function valideKey(evt) {

            let code = (evt.which) ? evt.which : evt.keyCode;

            if (code == 8) {
                return true;
            } else if (code >= 48 && code <= 57) {
                return true;
            } else {
                return false;
            }
        }

        window.onload = function() {
            let myInput = document.getElementById('val');
            myInput.onpaste = function(e) {
                e.preventDefault();
            }

            myInput.oncopy = function(e) {
                e.preventDefault();
            }
        }
    </script>

    <ul class="ul" id="hover"  onmouseover="rotate(0)" onmouseleave="rotate(1)">
        <li class="li user"><a href="<?php echo e(route('admin.panel')); ?>" class="loggine" alt="Administrar">Bienvenid@ <?php echo e(Auth::user()->email); ?></a></li>
        <li class="li date_bar">Fecha del servidor: <?php echo e(date("d-m-Y",strtotime(date('Y-m-d')))); ?></li>
        <li class="li home"><a href="<?php echo e(route('control')); ?>">Inicio</a><img class="ico_li" src='/control_de_pago/public/img/sidebar/ab.png'></li>
        <li class="li pay"><a href="<?php echo e(route('cliente.report')); ?>">Ver reporte general</a><img class="ico_li" src='/control_de_pago/public/img/sidebar/ab.png'></li>
        <li class="li cross"><a href="<?php echo e(route('control.list')); ?>">Desactivados</a><img class="ico_li" src='/control_de_pago/public/img/sidebar/ab.png'></li>
        <li class="li mp"><a href="<?php echo e(route('cliente.pay.customer')); ?>">Ver reportes de pago</a><img class="ico_li" src='/control_de_pago/public/img/sidebar/ab.png'></li>
        <li class="li mp"><a href="<?php echo e(route('admin.bills')); ?>">Reporte de gastos</a><img class="ico_li" src='/control_de_pago/public/img/sidebar/ab.png'></li>
        <li class="li sum" onmouseover="show_resume()" onmouseout="unshow_resume()">
            Resumen
            <img class="ico_li" src='/control_de_pago/public/img/sidebar/ab.png'>
        </li>
        <li class="li ab">
            <form id="logout-form" action="<?php echo e(route('logout')); ?>" style="position: absolute;" method="POST">
                <?php echo csrf_field(); ?>
                <input type="submit" style="position: relative; left: 70%; cursor: pointer;" value="Cerrar sesión">
            </form>
            <img class="ico_li" src='/control_de_pago/public/img/sidebar/ab.png'>
        </li>
        <li>
            <div>
                <div class="sidebar_resume" style="display: none;">
                    <p class="font-bold">Desactivados: <?php echo e($u); ?></p>
                    <p class="text-red-500 font-bold">Suspendidos: <?php echo e($s); ?></p>
                    <p class="text-orange-500 font-bold">Prorroga 2 dias: <?php echo e($p_2); ?></p>
                    <p class="text-orange-500 font-bold">Prorroga 1 dias: <?php echo e($p_1); ?></p>
                    <p class="text-orange-500 font-bold">Resta 1 dia: <?php echo e($r_1); ?></p>
                    <p class="text-orange-500 font-bold">Restan 2 dias: <?php echo e($r_2); ?></p>
                    <p class="text-orange-500 font-bold">Dia de corte: <?php echo e($d); ?></p>
                    <p class="text-green-500 font-bold">Solventes: <?php echo e($sol); ?></p>
                    <p>Clientes sin numero: <?php echo e($cuenta_tlf); ?></p>
                    <p>Activos totales: <?php echo e($count - $u); ?></p>
                </div>
            </div>
        </li>
    </ul>

    <script>
        function show_resume() {
            let resume = document.querySelector(".sidebar_resume");
            resume.style.display = "block"
            resume.style.left = '280px';
            resume.style.top = '-85px';
        }

        function unshow_resume() {
            let resume = document.querySelector(".sidebar_resume").style.display = "none";
        }
    </script>

    <div class="header_index">
        <a href="<?php echo e(route('control')); ?>" class="header_index_1" style="padding-left: 25px;"><span class="header_index_1_span_1">CONTROL DE </span><span class="header_index_1_span_2">PAGOS</span></a>
        <form action="" class="header_index_1_form">
            <input type="text" id="inp" class="find inp header_index_2" value="" placeholder="Buscar cliente" name="find" maxlength="34" onkeyup="form_react()" />
            <button type="submit" disabled hidden aria-hidden="true"></button>
        </form>
        <img src='/control_de_pago/public/img/tuerquita.png' id="tuerquita" class="add_new header_index_3" onclick="rotate(0)">
    </div>

    <script type="text/javascript">
        function rotate(value_ac){
            let tuerquita = document.querySelector(".add_new");

            if(value_ac == 0){
                document.querySelector('.ul').classList.replace('ul','hovered');
                tuerquita.setAttribute("onclick", "rotate(1)");
            }else{
                document.querySelector('.hovered').classList.replace('hovered','ul');
                tuerquita.setAttribute("onclick", "rotate(0)");
            }

            tuerquita.animate([{transform: 'rotate(360deg)'}], {duration: 1000});
        }
    </script>

    <div class="form_new absolute left-1/2 transform -translate-x-1/2" id="form_new" style="display: none;">
        <form action="<?php echo e(route('cliente.store')); ?>" method="POST" class="registro">
            <?php echo csrf_field(); ?>
            <label>
                Nombre completo
                <input type="text" name="full_name" class="entrada" placeholder="Introduzca su nombre" value="<?php echo e(old('full_name')); ?>">
            </label>
            <label>
                Cedula
                <br>
                <input type="text" id="id_user" name="id_user" class="entrada" placeholder="Introduzca su cedula" maxlength="8" value="<?php echo e(old('id_user')); ?>" onkeypress="return valideKey(event);">
                <br>
                <small id="validacion" style="color: red; display:none;">Cedula registrada</small>
            </label>
            <label>
                Dirección
                <input type="text" name="dir" class="entrada" placeholder="Introduzca su Direccion" value="<?php echo e(old('dir')); ?>">
            </label>
            <label>
                Telefono
                <br>
                <input type="text" id="tlf" name="tlf" class="entrada" placeholder="Introduzca un telefono" onkeypress="return valideKey(event);" value="<?php echo e(old('tlf')); ?>">
            </label>
            <label>
                Fecha de instalación
                <input type="date" name="date_c" class="entrada" value="<?php echo e(date('Y-m-d',strtotime(date('Y-m-d')))); ?>">
            </label>
            <label>
                IP Address <br>
                <input type="text" name="ip" class="entrada" placeholder="Introduzca la ip" value="<?php echo e(old('ip')); ?>">
            </label>
            <label>
                Observación
                <textarea class="entrada" name="observation"></textarea>
            </label>
            <style>
                .two_container{
                    display: grid;
                    grid-template-columns: auto auto;
                }
            </style>
            <div style="margin: auto;" class="two_container">
            <label>
                Servidor
                <select name="servidor">  
                        <option value="cerro">Cerro</option>
                        <option value="rancho_grande">Rancho grande</option>
                        <option value="23_enero">23 de enero</option>
                        <option value="Default" selected>Default</option>
                    </select>
                </label>

                <label>
                Plan
                <select name="plan" class="">
                <?php $__currentLoopData = $planes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($plan->id); ?>"><?php echo e($plan->nombre_de_plan); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
                </label>
            </div>
            <label>
                <span class="ista_type_help" title="Precios: 
liteBeam 5AC: 220$
Lite Beam M5: 200$
Fibra con ONU WIFI: 100$
Fibra con ONU BRIDGE: 60$
Noria: 30$
Noria con Router: 65$
Edificios: Sin precios
Default: 220$">Tipo de instalacion &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><br>
                <select name="insta">
                    <option value="200">Lite Beam 5AC</option>
                    <option value="200">Lite Beam M5</option>
                    <option value="100">Fibra con ONU WIFI</option>
                    <option value="60">Fibra con ONU BRIDGE</option>
                    <option value="30">Noria</option>
                    <option value="65">Noria Router</option>
                    <option value="0">Edificios</option>
                    <option value="Default" selected>Default</option>
                </select>
            </label>
            <label>
                Monto <br>
                <input type="number" name="monto_inst" class="entrada" placeholder="Monto de instalacion" value="<?php echo e(old('monto_inst')); ?>">
            </label>
            <label>
                <input type="submit" class="enviar" value="Registrar cliente">
            </label>
            <label>
                <p onclick="display_n()" class="btn-cerrar">Cerrar</p>
            </label>
        </form>
    </div>

    <a href="#body">
        <img src="img/ciel.png" class="img fixed m-5 right-0 bottom-0 w-12 h-12">
    </a>
    <style>
        .hide_if {
            background-color: transparent;
            width: -webkit-fill-available;
            height: -webkit-fill-available;
            position: fixed;
            z-index: 210;
            top: 0;
        }
    </style>

    <div onclick="iframe()" id="hide_if" class="hide_if" style="display: none;"></div>

    <iframe src="" id="src" crossorigins class="edit absolute left-1/2" style="display: none;"></iframe>

    <script>
        function iframe() {
            let iframe = document.getElementById("src").style.display = "none";
            let iframe_if = document.getElementById("hide_if").style.display = "none";
        }
    </script>

    <style>
        .main_container {
            position: absolute;
            background-color: #fff;
            top: 30%;
            left: 10px;
            width: 99%;
            padding: 0;
            margin: 0;
            gap: 0;
        }  

        .container_id{
                display: grid;
                grid-template-columns: 1fr;
            }

            .forma{
                width: 20px;
                height: 20px;
                border-radius: 50%;
                cursor: pointer;
            }

        .child_container:hover{
            background-color: #E3E3E3;
        }

        .acciones:hover{
            background-color: #0fc4da;
        }

        .child_container_header{
            background: #000;
            color: #fff;
            font-size: large;
            padding: 5px;
            height: 60px;
            border-radius: 5px 5px 0 0;
        }
        
        @media (min-width: 480px) {
            .child_container{
                display: grid;
                grid-template-columns: 3% 10% 22% 10% 10% 10% 5% 10% 10% 9%;
                grid-gap: 1px;
                align-items: center;
                text-align: center;
                border: solid 1px #E3E3E3;
                font-weight: 700;
            }

            .child_container_header{
                display: grid;
                grid-template-columns: 3% 10% 22% 10% 10% 10% 5% 10% 20% 0%;
                align-items: center;
                text-align: center;
                border: solid 1px #E3E3E3;
            }

            .main_item_1{
                padding: 20px 0 20px 0;
            }
        } 
        
        @media (min-width: 350px) and (max-width: 480px) {
            .child_container_header{
                display: grid;
                grid-template-columns: 37% 16% 18% 29%;
                align-items: center;
                text-align: center;
                border: solid 1px #E3E3E3;
            }

            #tuerquita{
                height: 37px;
                margin-left: 60px;
            }

            .start_destacated{
                display: grid;
                grid-template-columns: 22% 78%;
            }

            .child_container{
                display: grid;
                grid-template-columns: 38% 15% 15% 15% 15%;
                grid-gap: 1px;
                text-align: center;
                border: solid 1px #E3E3E3;
                font-weight: 700;
                word-break: break-all;
                align-items: center;
                justify-content: center;
            }

            .form_new{
                transform: translate(-178px, 20%);
            }

            .new_sec{
                width: 400px;
                right: 1%;
            }

            .div_main{
                display: none;
            }

            .main_container {
                position: absolute;
                background-color: #fff;
                top: 30%;
                width: 95%;
            }

            .child_container_header{
                background: #000;
                color: #fff;
                font-size: large;
                height: 60px;
            }
            
            .main_item_1, .main_item_3, .main_item_4, .main_item_5, .main_item_7, .main_header_item_1, .main_header_item_3, .main_header_item_4, .main_header_item_5, .main_header_item_7{
                display: none;
            }

            .main_item_2{
                padding: 15px 0 15px 0;
                word-break: break-all;
            }

            .main_item_9{
                font-size: 14px;
            }

            .header_index{
                display: grid;
                grid-template-columns: 40% 40% 10%;
                background-color: #000;
                align-items: center;
                justify-items: center;
                color: #ffff;
                position: fixed;
                z-index: 2000;
                width: 100%;
                height: 88px;
            }

            .header_index_1_form{
                width: 100%;
            }

            .header_index_1{
                justify-self: baseline;
                padding-left: 10px;
            }

            .header_index_2{
                color: #000;
            }

            .header_index_3{
                cursor: pointer;
            }

            .header_index_1_span_1, .header_index_1_span_2{
                font-family: 'verdana', Courier, monospace;
                font-weight: 800;
                font-size: 20px;
            }

            .header_index_1_span_2{
                color: #000;
                text-shadow: 2px 2px 0 #ffffff, 2px -2px 0 #ffffff, -2px 2px 0 #ffffff, -2px -2px 0 #ffffff, 2px 0px 0 #ffffff, 0px 2px 0 #ffffff, -2px 0px 0 #ffffff, 0px -2px 0 #ffffff;
            }
        } 

        .status_gen {
            font-weight: bold;
            color: #fff;
            height: -webkit-fill-available;
            cursor: default;
        }

        .status_1{
            background-color: #27d719;
        }

        .status_2{
            background-color: #ff9d10;
        }

        .status_3{
            background-color: #ff0000;
        }

        .status_4{
            background-color: #000;
            color: #fff;
        }

        .main_plan{
            font-size: 20px;
            font-weight: bolder;
        }

        .acciones{
            background-color: #15dcf4;
            color: #fff;
            height: -webkit-fill-available;
            padding-top: 25%;
            padding-bottom: 25%;
        }

        .start_destacated{
            display: grid;
            grid-template-columns: 40% 60%;
        }
    </style>

    //tabla principal
    <div class="main_container border-teal-700" id="main_table" style="box-shadow: 2px 2px 5px #5b8dea; background-color: #fff;border-collapse: inherit; border-radius:15px;">
        <div class="child_container_header">
            <p class="main_header_item_1">#</p>
            <p class="main_header_item_2">Nombre</p>
            <p class="main_header_item_3">Direccion</p>
            <p class="main_header_item_4">Cedula</p>
            <p class="main_header_item_5">Ip</p>
            <p class="main_header_item_6">Dia de corte</p>
            <p class="main_header_item_7">Plan</p>
            <p class="main_header_item_8">Estado</p>
            <p class="main_header_item_9">Acciones</p>
        </div>

        <?php $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="child_container on" id="<?php echo e($contador); ?>">
            <div class="div_main main_item_1 container_id">
                <div class="main_item_1_server">
                    <p><?php echo e($contador++); ?></p>
                    <p style="display:none"><?php echo e($cliente->tlf); ?></p>
                    <p style="display:none">123_<?php echo e($cliente->type); ?></p>

                    <?php if($cliente->server_active == 1): ?>
                        <form method="get" action="<?php echo e(route('cliente.status_server')); ?>">
                            <input type="hidden" name="id" value="<?php echo e($cliente->id); ?>">
                            <input type="hidden" name="server_active" value="0">
                            <?php if(auth()->user()->role == 1 || auth()->user()->role == 2): ?>
                                <input class="forma server_status_red" style="background-color: #ff0000;" type="submit" value="" title="<?php echo e(date('d-m-Y',strtotime($cliente->last_cut_act))); ?>">
                            <?php else: ?>
                                <input class="forma server_status_red" style="background-color: #ff0000;" title="<?php echo e(date('d-m-Y',strtotime($cliente->last_cut_act))); ?>">
                            <?php endif; ?>
                    </form>
                    <?php else: ?>
                        <form method="get" action="<?php echo e(route('cliente.status_server')); ?>">
                            <input type="hidden" name="id" value="<?php echo e($cliente->id); ?>">
                            <input type="hidden" name="server_active" value="1">
                            <?php if(auth()->user()->role == 1 || auth()->user()->role == 2): ?>
                                <input class="forma server_status_green" style="background-color: #27d719;" type="submit" value="" title="<?php echo e(date('d-m-Y',strtotime($cliente->last_cut_act))); ?>">
                            <?php else: ?>
                                <input class="forma server_status_green" style="background-color: #27d719;" title="<?php echo e(date('d-m-Y',strtotime($cliente->last_cut_act))); ?>">
                            <?php endif; ?>
                            
                        </form>
                    <?php endif; ?>
                </div>
        </div>
            <?php if($cliente->destacated == 1): ?>
                <?php if($cliente->total_debt > 0 && $cliente->data == 0): ?> 
                    <p class="main_item_2 start_destacated"><img class="destacated" title="<?php echo e($cliente->destacated_com); ?>" src='/control_de_pago/public/img/start.png'><span style="color: #ff0000; cursor: pointer;" title="El cliente tiene una deuda de: <?php echo e($cliente->total_debt); ?>$ y su numero telefonico no esta registrado"><?php echo e(ucwords(strtolower($cliente->full_name))); ?></span></p>
                <?php elseif($cliente->total_debt > 0): ?>
                    <p class="main_item_2 start_destacated"><img class="destacated" title="<?php echo e($cliente->destacated_com); ?>" src='/control_de_pago/public/img/start.png'><span style="color: #ff0000; cursor: pointer;" title="El cliente tiene una deuda de: <?php echo e($cliente->total_debt); ?>$"><?php echo e(ucwords(strtolower($cliente->full_name))); ?></span></p>
                <?php elseif($cliente->data == 0): ?>
                    <p class="main_item_2 start_destacated"><img class="destacated" title="<?php echo e($cliente->destacated_com); ?>" src='/control_de_pago/public/img/start.png'><span style="color: #ff0000; cursor: pointer;" title="Numero telefonico no registrado"><?php echo e(ucwords(strtolower($cliente->full_name))); ?></span></p>
                <?php else: ?>
                    <p class="main_item_2 start_destacated"><img class="destacated" title="<?php echo e($cliente->destacated_com); ?>" src='/control_de_pago/public/img/start.png'><span><?php echo e(ucwords(strtolower($cliente->full_name))); ?></span></p>
                <?php endif; ?>
            <?php else: ?>
                <?php if($cliente->total_debt > 0 && $cliente->data == 0): ?>
                    <p class="main_item_2" style="color: #ff0000; cursor: pointer;" title="El cliente tiene una deuda de: <?php echo e($cliente->total_debt); ?>$ y su numero telefonico no esta registrado"><?php echo e(ucwords(strtolower($cliente->full_name))); ?></p>
                <?php elseif($cliente->total_debt > 0): ?>
                    <p class="main_item_2" style="color: #ff0000; cursor: pointer;" title="El cliente tiene una deuda de: <?php echo e($cliente->total_debt); ?>$"><?php echo e(ucwords(strtolower($cliente->full_name))); ?></p>
                <?php elseif($cliente->data == 0): ?>
                    <p class="main_item_2" style="color: #ff0000; cursor: pointer;" title="Numero telefonico no registrado"><?php echo e(ucwords(strtolower($cliente->full_name))); ?></p>
                <?php else: ?>
                    <p class="main_item_2"><?php echo e(ucwords(strtolower($cliente->full_name))); ?></p>
                <?php endif; ?>
            <?php endif; ?>
            <p class="main_item_3"><?php echo e(ucwords(strtolower($cliente->dir))); ?></p>
            <p class="main_item_4"><?php echo e(ucwords(strtolower($cliente->id_user))); ?></p> 
            <a href="https://<?php echo e($cliente->ip); ?>" class="main_item_5" target="_blank"><?php echo e($cliente->ip); ?></a>
            <p class="main_item_6"><?php echo e(date("d-m-Y",strtotime($cliente->cut))); ?></p>
            <p class="main_plan main_item_7"><?php echo e($cliente->plan); ?></p>
            <?php if($cliente->status == 1): ?>
                <button class="status_gen status_1 main_item_9">Solvente</button>
            <?php elseif($cliente->status == 2): ?>
                <button class="status_gen status_2 main_item_9">Prorroga día 2</button>
            <?php elseif($cliente->status == 3): ?>
                <button class="status_gen status_2 main_item_9">Prorroga día 1</button>
            <?php elseif($cliente->status == 4): ?>
                <button class="status_gen status_2 main_item_9">Resta 1 día</button>
            <?php elseif($cliente->status == 5): ?>
                <button class="status_gen status_2 main_item_9">Resta 2 días</button>
            <?php elseif($cliente->status == 6): ?>
                <button class="status_gen status_3 main_item_9">Dia de corte</button>
            <?php elseif($cliente->status == 7 and $cliente->active == 1): ?>
                <button class="status_gen status_3 main_item_9">Requiere suspension</button>
            <?php elseif($cliente->active == 0): ?>
                <button class="status_gen status_3 main_item_9">Desactivado</button>
            <?php elseif($cliente->status == 0): ?>
                <button class="status_gen status_4 main_item_9">Fecha incorrecta</button>
            <?php endif; ?>
                <a href="<?php echo e(route('cliente.pay',$cliente->id)); ?>" class="acciones main_item_10" target="_blank">Pagar</a>
                <button onclick="iframe_src(<?php echo e($cliente->id); ?>)" class="acciones main_item_11">Modificar</button>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <script>
        //3 funciones para ordenamiento de clientes: estado, cantidad, ascendente y descendente
        function status() {
            let by = document.getElementById("by");
            console.log("Se ha detectado un cambio en la funcion status " + by.value);
        }

        function count() {
            let count = document.getElementById("cuenta");
            console.log("Se ha detectado un cambio en la funcion cuenta " + count.value);
        }

        function asc_desc() {
            let asc_desc = document.getElementById("asc");
            console.log("Se ha detectado un cambio en la funcion asc_desc " + asc_desc);
        }
    </script>

    <script>
        function form_react() {
            let inp = document.getElementById("inp").value;
            let new_array = [];

            for (let i = 1; i <= <?php echo e($var_regs); ?>; i++) {

                new_array[i] = document.getElementById(i);

                if (new_array[i].innerHTML.toLowerCase().normalize('NFD').replace(/([aeio])\u0301|(u)[\u0301\u0308]/gi,"$1$2").normalize().includes(inp.toLowerCase())) {
                    new_array[i].classList.replace("off", "on");
                } else {
                    new_array[i].classList.replace("on", "off");
                }

                if (inp == "") {
                    new_array[i].classList.replace("off", "on");
                }
            }
        }
    </script>

    <script>
        function iframe_src(id) {
            let src = document.getElementById("src").src = "http://190.120.248.192:56001/control_de_pago/public/control/modificar/" + id;
            document.getElementById("src").style.display = "block";
            document.getElementById("form_new").style.display = "none";
            document.getElementById("src").style.zIndex = "220";
            document.getElementById("hide_if").style.display = "block";
        }
    </script>

    <script>
        document.body.addEventListener("keydown", function(event) {
            if (event.code === 'Escape' || event.keyCode === 27) {
                document.getElementById("form").style.display = "none";
                document.getElementById("form_new").style.display = "none";
            }
        });

        function display_b(id) {
            document.getElementById("form").style.display = "block";
            document.getElementById("form_new").style.display = "none";
            console.log(id);
        }

        function display_n() {
            document.getElementById("form_new").style.display = "none";

            $('.registro').submit(function(e) {
                e.preventDefault();
            });
        }
        function display_cn() {
            document.getElementById("form_new").style.display = "block";
            document.getElementById("src").style.display = "none";
        }

        $("#find").focus();
    </script>

    <script>
        $('.registro').submit(function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Registrar cliente nuevo?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            })
        });
    </script>

    <?php if(session('registrar') == "oknt"): ?>
    <script>
        document.getElementById("form_new").style.display = "block";
        document.getElementById("validacion").style.display = "block";
        console.log("Error");
    </script>
    <?php endif; ?>

    <script>
        window.onload = function() {
            let myInput = document.getElementById('id_user');
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

    <script>
        window.onload = function() {
            let myInput = document.getElementById('tlf');
            myInput.onpaste = function(e) {
                e.preventDefault();
            }

            myInput.oncopy = function(e) {
                e.preventDefault();
            }
        }
    </script>

    <script>
        $("#confirm").click(function(e) {
            //e.preventDefault();
            //var linkURL_logout = $(this).attr("href");
            //warnBeforeRedirect(linkURL);
        });

        function warnBeforeRedirect(linkURL_logout) {
            swal({
                title: "¿Cerrar sesion?",
                showCancelButton: true,
                type: "warning",
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                cancelButtonText: 'Cancelar',
            }, function(confirm) {
                if (confirm) {
                    console.log('confirmado');
                    window.location.href = linkURL;
                    document.getElementById('logout-form').submit();
                }
            })
        }
    </script>

    <style>
        .off {
            display: none;
        }
    </style>
</body>

</html><?php /**PATH C:\xampp\htdocs\control_de_pago\resources\views/registros.blade.php ENDPATH**/ ?>