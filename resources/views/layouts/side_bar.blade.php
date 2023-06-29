<!DOCTYPE html>
<html lang="en">

<body>
    <style>
        body{
            padding-left: 1%;
        }

        .ul {
            position: fixed;
            top: 0;
            left: -280px;
            list-style: none;
            background-color: black;
            margin: 0;
            padding: 5px;
            width: 285px;
            height: -webkit-fill-available;
            color: white;
            transition: left 1.2s;
            z-index: 2100;
        }

        .ul:hover {
            left: -0.4%;
            background-color: black;
        }

        .li {
            display: block;
            color: white;
            margin: 20px;
            padding: 5px;
            width: 250px;
            border-radius: 5px;
            background-repeat: no-repeat;
            background-position-y: center;
            background-size: 28px 28px;
            text-align: center;
            background-image: url("/control_de_pago/public/img/sidebar/a.png");
        }

        .a, .submit_input{
            text-decoration: none;
            color: #fff;
        }

        .li:hover {
            cursor: pointer;
            transform: scale(1.1);
        }

        .date_bar:hover {
            background-color: black;
            color: white;
            outline: none;
            cursor: default;
            transform: scale(1);
        }

        .ab {
            position: absolute;
            bottom: 2%;
            cursor: pointer;
            background-image: url("/control_de_pago/public/img/sidebar/e.png");
        }

        .user {
            background-image: url("/control_de_pago/public/img/sidebar/userw.png");
        }

        .user:hover {
            background-image: url("/control_de_pago/public/img/sidebar/userw.png");
            background-color: black;
            color: white;
            outline: none;
            transform: scale(1);
        }

        .home {
            background-image: url("/control_de_pago/public/img/sidebar/homew.png");
        }

        .pay {
            background-image: url("/control_de_pago/public/img/sidebar/payw.png");
        }

        .cross {
            background-image: url("/control_de_pago/public/img/sidebar/crossw.png");
        }

        .mp {
            background-image: url("/control_de_pago/public/img/sidebar/mpw.png");
        }

        .sum {
            background-image: url("/control_de_pago/public/img/sidebar/resw.png");
        }

        .ico_li {
            width: 20px;
            height: 20px;
            float: right;
        }

        .sidebar_resume {
            position: relative;
            width: 180px;
            height: 240px;
            left: 25%;
            top: -20px;
            background-color: #000;
        }

        .submit_input{
            background: transparent;
        }
    </style>
    
    <ul class="ul" id="ul">
        <li class="li user"><a href="{{route('admin.panel')}}" class="loggine a" alt="Administrar">Bienvenid@ {{ Auth::user()->email }}</a></li>
        <li class="li date_bar">Fecha del servidor: {{date("d-m-Y",strtotime(date('Y-m-d')))}}</li>
        <li class="li home"><a href="{{route('control')}}" class="a">Inicio</a><img class="ico_li" src='/control_de_pago/public/img/sidebar/ab.png'></li>
        <li class="li pay"><a href="{{ route('cliente.report') }}" class="a">Ver reporte general</a><img class="ico_li" src='/control_de_pago/public/img/sidebar/ab.png'></li>
        <li class="li cross"><a href="{{route('control.list')}}" class="a">Desactivados</a><img class="ico_li" src='/control_de_pago/public/img/sidebar/ab.png'></li>
        <li class="li mp"><a href="{{ route('cliente.pay.customer') }}" class="a">Ver reportes de pago</a><img class="ico_li" src='/control_de_pago/public/img/sidebar/ab.png'></li>
        <li class="li mp"><a href="{{ route('admin.bills') }}" class="a">Reporte de gastos</a><img class="ico_li" src='/control_de_pago/public/img/sidebar/ab.png'></li>
        <li class="li ab">
            <form id="logout-form" action="{{ route('logout') }}" style="position: absolute;" method="POST">
                @csrf
                <input type="submit" class="submit_input" style="position: relative; left: 70%; cursor: pointer;" value="Cerrar sesión">
            </form>
            <img class="ico_li" src='/control_de_pago/public/img/sidebar/ab.png'>
        </li>
    </ul>
</body>

</html>