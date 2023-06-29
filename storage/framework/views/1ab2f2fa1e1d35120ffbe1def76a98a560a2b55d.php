<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($users->full_name); ?></title>
    <link rel="shortcut icon" sizes="60x60" href="/control_de_pago/public/img/favicon-16x16.png">
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
</head>
<?php if(auth()->user()->role == 1 || auth()->user()->role == 2 && auth()->user()->email == 'antonio' || auth()->user()->email == 'jean' || auth()->user()->email == 'taquilla' || auth()->user()->email == 'marco'): ?>
<style>
    .form{
        display: grid;
        grid-template-columns: 1fr;
        justify-items: start;
    }

    #sub_add_meses{
        border: none;
        background-color: #6e7881;
        color: #fff;
        padding: 10px;
    }

    input {
        border: solid 1px black;
        border-radius: 5px;
    }

    .crear_planes {
        border: 1px solid;
        padding: 5px
    }

    .grid-container {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        padding: 10px 0 0 10px;
        justify-items: center;
    }

    .grid-item {
        text-align: left;
    }

    .contenedor {
        display: grid;
        grid-template-columns: 1fr;
    }

    .item {
        padding: 15px;
        text-align: center;
    }

    th {
        background-color: black;
        color: white;
        border: hidden;
        height: 50px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    td {
        height: 10px;
    }

    .eliminar {
        cursor: pointer;
    }

    #sub {
        cursor: pointer;
        border: solid 1px green;
        border-radius: 4px;
        padding: 2px 7px 2px 7px;
        color: #fff;
        background-color: #000;
    }

    .hidden_form {
        margin: 0;
        padding: 0;
        display: none;
    }

    .advan_form {
        display: grid;
        grid-template-columns: 1fr;
    }

    #h1 {
        display: inline;
    }

    .act_des{
        border: none;
        background-color: #000;
        padding: 5px;
        color: #fff;
        cursor: pointer;
    }

    .modify_pay{
        cursor: pointer;
        border: solid 1px green;
        border-radius: 4px;
        padding: 2px 7px 2px 7px;
        color: #fff;
        background-color: #000;
    }

    .modify_pay:hover , #sub:hover{
        color: #000;
        background-color: #fff;
    }

    #meses, .print_fac{
        color: #000;
    }
</style>

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
</script>

<script>
    document.addEventListener("dragover", function(event) {
        event.preventDefault();
    }, false);
</script>

<body>

    <div class="grid-container">
        <div class="grid-item">
            <?php if($users->destacated == 0): ?>
            <script>
                function open_destacated(var_des){
                    if(var_des == "on"){
                        let form_destacated = document.querySelector('.destacated_form').style.display = "block";
                        let button_destacated = document.querySelector('.button_destacated');

                        button_destacated.removeAttribute("onclick");
                        button_destacated.setAttribute("onclick",'open_destacated("off")');
                    }else{
                        let form_destacated = document.querySelector('.destacated_form').style.display = "none";
                        let button_destacated = document.querySelector('.button_destacated');

                        button_destacated.removeAttribute("onclick");
                        button_destacated.setAttribute("onclick",'open_destacated("on")');
                    }
                }
            </script>
                <button onclick="open_destacated('on')" class="button_destacated">Abrir formulario de destacado</button>
                <form action="<?php echo e(route('destacated',$users->id)); ?>" class="destacated_form" style="display: none;">
                    <textarea name="destacated" id="" cols="30" rows="10"></textarea><br>
                    <input type="submit" value="Destacar">
                </form>
            <?php else: ?>
                <a href="<?php echo e(route('destacated',$users->id)); ?>" class="">Quitar destacado</a>
            <?php endif; ?>
            
            <p>Nombre: <?php echo e($users->full_name); ?> <a href="<?php echo e(route('cliente.edit',$users->id)); ?>" class="modify_pay" target="_blank">Modificar</a></p>
            <p>Cedula: <?php echo e($users->id_user); ?></p>
            <p>Telefono: <?php echo e($users->tlf); ?></p>
            <p>Direccion: <?php echo e($users->dir); ?></p>
            <p>Fecha de suspension actual: <?php echo e(date('d-m-Y',strtotime($users->cut))); ?></p>
            <form action="<?php echo e(route('cliente.pay_update',$users)); ?>" class="formulario-pagar" method="POST" class="w-full max-w-lg m-5">
                <?php echo method_field('put'); ?>
                <?php echo csrf_field(); ?>
                <input type="hidden" name="status" value="1">
                <input type="hidden" name="id_user" value="<?php echo e($users->id_user); ?>">
                <input type="hidden" name="plan_ab" value="<?php echo e($users->total); ?>">
                <p>Codigo de cliente: <?php echo e($users->id); ?></p>
                <input type="hidden" name="dir" value="<?php echo e($users->dir); ?>">
                <label>
                    Cobrador:
                    <select name="reg">
                        <option value="marco escala">Marco Escala</option>

                        <?php if(auth()->user()->email == 'antonio'): ?>
                        <option value="marco antonio" selected>Marco Antonio</option>
                        <?php else: ?>
                        <option value="marco antonio">Marco Antonio</option>
                        <?php endif; ?>

                        <option value="yurbi laya">Yurbis Laya</option>

                        <?php if(auth()->user()->email == 'kennerth'): ?>
                            <option value="kennerth salazar" selected>Kennerth Salazar</option>
                        <?php else: ?>
                            <option value="kennerth salazar">Kennerth Salazar</option>
                        <?php endif; ?>
                        
                        <?php if(auth()->user()->email == 'rossana'): ?>
                            <option value="rossana" selected>Rossana</option>
                        <?php else: ?>
                            <option value="rossana">rossana</option>
                        <?php endif; ?>
                        
                        <option value="eduardo figueroa">Eduardo Figueroa</option>
                        <option value="eduardo granadillo">Eduardo Granadillo</option>
                        <option value="toto">Toto</option>
                        <option value="tototito">Tototito</option>
                        <option value="luis cotico">Cotico</option>
                        <option value="el gordo">El gordo</option>

                        <?php if(auth()->user()->email == 'taquilla'): ?>
                            <option value="taquilla" selected>taquilla</option>
                        <?php else: ?>
                            <option value="taquilla">taquilla</option>
                        <?php endif; ?>

                        <?php if(auth()->user()->email == 'jean'): ?>
                            <option value="jean carlos" selected>Jean Carlos</option>
                        <?php else: ?>
                            <option value="jean carlos">Jean Carlos</option>
                        <?php endif; ?>
                        <option value="guillermo escala">Guillermo Escala</option>
                    </select>
                </label><br>

                <label>
                    Dia de pago:
                    <?php if(auth()->user()->email == 'jean'): ?>
                    <?php echo e(date('Y-m-d',strtotime(date('Y-m-d')))); ?>

                    <input type="hidden" name="day" value="<?php echo e(date('Y-m-d',strtotime(date('Y-m-d')))); ?>">
                    <?php else: ?>
                    <input type="date" name="day" value="<?php echo e(date('Y-m-d',strtotime(date('Y-m-d')))); ?>">
                    <?php endif; ?>
                    <?php $__errorArgs = ['day'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <small style="color:#FF0000">*<?php echo e($message); ?></small>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </label>
                <br>
                <label>
                    Tipo de pago:
                    <select name="type_pay" id="type_pay" onchange="type_pay_a()">
                      <option>Seleccione una opcion</option>
                      <option value="d">Dolares</option>
                      <option value="p">Bolivares</option>
                      <option value="b">Pagomovil</option>
                      <option value="za">Zelle A</option>
                      <option value="zb">Zelle B</option>
                    </select>
                    <script>
  function type_pay_a() {
    let x = document.getElementById("type_pay").value;

    console.log(x);

    let type_pay_d = document.getElementById("in_d");

    let type_pay_pm = document.getElementById("bs_pm");
    let pm_ref = document.getElementById("bs_pm");
    let show_alert = document.getElementById("show_alert");

    let type_pay_bs = document.getElementById("bs");
    let type_pay_za = document.getElementById("in_za");
    let type_pay_zb = document.getElementById("in_zb");

    if (x == "d") {//dolares
      type_pay_d.style.display = "inline";
    } /*else {
      type_pay_d.style.display = "none";
    }*/

    if (x == "p") {//pagomovil
      type_pay_pm.style.display = "inline";
      show_alert.style.display = "block";
      pm_ref.style.display = "inline";
    } /*else {
      type_pay_pm.style.display = "none";
      show_alert.style.display = "none";
      pm_ref.style.display = "none";
    }*/

    if (x == "b") {//bolivares
      type_pay_bs.style.display = "inline";
    } /*else {
      type_pay_bs.style.display = "none";
    }*/

    if (x == "za") {//zelle a
      type_pay_za.style.display = "inline";
    } /*else {
      type_pay_za.style.display = "none";
    }*/

    if (x == "zb") {// zelle b
      type_pay_zb.style.display = "inline";
    } /*else {
      type_pay_zb.style.display = "none";
    }*/
  }
</script>
                </label>
                <input type="hidden" name="cut" value="<?php echo e(date('Y-m-d', strtotime($users->cut))); ?>">

                <input type="hidden" name="plan" disabled value="<?php echo e($users->plan); ?>">

                <style>
                    #pm_ref, #pm_ref_dep{
                        display: none;
                    }
                </style>

<label>
    <input type="text" id="in_d" min="1" max="<?php echo e($users->total); ?>" name="d" value="" placeholder="Dolares" onkeypress="return valideKey(event);" style="display:none;"/>
    <input type="text" id="bs_pm" min="1" max="<?php echo e($users->total); ?>" name="bs_pm" value="" placeholder="Bolivares (efectivo)" onkeyup="tasa()" onkeypress="return valideKey(event);" style="display: none;"/>
    <input type="text" id="bs" min="1" max="<?php echo e($users->total); ?>" name="bs" value="" placeholder="Pagomovil" onkeyup="tasa()" onkeypress="return valideKey(event);" style="display: none";/>
    <input type="text" id="in_za" min="1" max="<?php echo e($users->total); ?>" name="za" value="" placeholder="Zelle A" onkeypress="return valideKey(event);" style="display: none;"/>
    <input type="text" id="in_zb" min="1" max="<?php echo e($users->total); ?>" name="zb" value="" placeholder="Zelle B" onkeypress="return valideKey(event);" style="display: none;"/>
    <input type="text" placeholder="Referencia del pagomovil" pattern="^[0-9]{5}$" value="" name="pm_ref" id="pm_ref" onkeypress="pm_ref()" style="display: none;"/>
    <small id="show_alert" style="color: red; display: none;">Referencia del pagomovil obligatoria y debe estar en entre el rango 10000 - 99999*</small>
</label>    
                    <br>
                <style>
                    .calculadora{
                        border: solid 1px black;
                        width: fit-content;
                        border-radius: 5px;
                        font-size: small;
                    }
                </style>
                    <br>
                    <div class="calculadora">
                        <h1 class="calculadora_div"></h1>
                        <h1>Tasa actual: <?php echo e($tasa->tasa); ?>Bs.</h1>
                        <h1 id="h1"></h1>
                        <h1 id="h2"></h1>
                        <h1 id="h3"></h1>
                    </div>

                    <script>
                        function tasa() {
                            let bs = document.querySelector("#bs");
                            let show_alert = document.querySelector("#show_alert");
                            

                            let h1 = document.querySelector("#h1");
                            let h2 = document.querySelector("#h2");
                            let h3 = document.querySelector("#h3");
                            let calculadora_div = document.querySelector(".calculadora_div");

                            let input_ref = document.getElementById('pm_ref');

                            if(bs.value > 0){
                                input_ref.style.display = "block";
                                input_ref.setAttribute("required", "");
                                show_alert.style.display = "block";
                            }else{
                                input_ref.style.display = "none";
                                input_ref.value = "";
                                input_ref.removeAttribute("required", "");
                                show_alert.style.display = "none";
                            }
                            
                            let cambio = parseFloat((bs.value / <?php echo e($tasa->tasa); ?>).toFixed(2));
                            let iva = parseFloat(((bs.value * 0.16) / <?php echo e($tasa->tasa); ?>).toFixed(2));
                            let total_bs = parseFloat(cambio + iva);
                            let total_d = parseFloat(bs.value * 0.16) + parseFloat(bs.value);

                            if(bs.value > 0){
                                h1.innerHTML = "Al cambio BCV: " + (bs.value / <?php echo e($tasa->tasa); ?>).toFixed(2) + "$";
                                h2.innerHTML = "16% de Iva: " + (bs.value * 0.16).toFixed(2) + "Bs." + " (" + ((bs.value * 0.16) / <?php echo e($tasa->tasa); ?>).toFixed(2) + " $)";
                                h3.innerHTML = "Total: " + total_d.toFixed(2) + "Bs";
                                calculadora_div.innerHTML = "Calculadora";
                            }else{
                                h1.innerHTML = "";
                                h2.innerHTML = "";
                                h3.innerHTML = "";
                                calculadora_div.innerHTML = "";
                            }
                        }
                    </script>

                    <script>
                        window.onload = function() {
                            let myInput_1 = document.getElementById('in_d');
                            let myInput_2 = document.getElementById('bs');
                            let myInput_3 = document.getElementById('in_za');
                            let myInput_4 = document.getElementById('in_zb');

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

                            myInput_4.onpaste = function(e) {
                                e.preventDefault();
                            }

                            myInput_4.oncopy = function(e) {
                                e.preventDefault();
                            }
                        }
                    </script>

                <style>
                    .textarea{
                        display: grid;
                        grid-template-columns: 1fr 1fr;
                    }
                </style>

                <div class="textarea">

                <label>
                    <br>
                    Nota del pago(Se vera reflejado en el ticket)
                    <textarea class="border border-slate-300" name="observation" style="resize:none;" id="" cols="25" rows="5"><?php echo e($users->observation); ?></textarea>
                    <?php $__errorArgs = ['observation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>

                    <small style="color:#FF0000">*<?php echo e($message); ?></small>

                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </label>

                <label>
                    <br>
                    Notas del instalador (No se vera reflejado en el ticket)
                    <textarea class="border border-slate-300" name="comentario_instalador" style="resize:none;" id="" cols="25" rows="5"><?php echo e($users->comentario_instalador); ?></textarea>
                </label>

                </div>

                <br>
                <label>
                    Fecha de suspensión (Despues del pago): <?php echo e(date('d-m-Y',strtotime($users->cut .'+ 1 month'))); ?>

                </label>
                <br><br>
                <label>
                    <p>El cliente tiene una deuda total por soportes de : <?php echo e($users->total_deb_support); ?> <br> Nota: este valor del soporte se vera reflejado en el ticket</p>
                </label>
                <p>El monto del plan de <?php echo e($users->plan); ?> es: <?php echo e($users->total); ?>$</p>
                <?php if($users->total_debt_m <= 0): ?> <p>El cliente ha abonado un total de: 0$</p>
                    <p>El total a pagar es: <span style="color: red;"><?php echo e($users->total - $users->total_debt_m); ?>$</span></p>
                    <?php else: ?>
                    <p>El cliente ha abonado un total de: <?php echo e(round($users->total - $users->total_debt_m ,2)); ?>$</p>
                    <p>El total a pagar es: <span style="color: red;"><?php echo e(round($users->total - ($users->total - $users->total_debt_m),2)); ?>$</span></p>
                    <?php endif; ?>

                    <input type="hidden" name="total_ab" value="<?php echo e($users->total - $users->total_debt_m); ?>">

                    <?php if($users->active == 1): ?>
                        <input type="submit" id="sub" value="Pagar">
                    <?php else: ?>
                        <p>Estatus del cliente: <span style="color: red;">DESACTIVADO</span></p>
                    <?php endif; ?>
                    
            </form>
        </div>

        <div class="grid-item">
            <table class="tabla text-center z-10 rounded border-teal-700" style="box-shadow: 2px 2px 5px #5b8dea;">
                <tr>
                    <th class="border border-slate-300 p-2" style="box-shadow: 1px 1px 2px #5b8dea;">Mes</th>
                    <th class="border border-slate-300 p-2" style="box-shadow: 1px 1px 2px #5b8dea;">Estado</th>
                    <th></th>
                </tr>
                <?php $__currentLoopData = $pagos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pago): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <?php if($pago->mes == 1): ?>
                    <td>Enero</td>
                    <?php elseif($pago->mes == 2): ?>
                    <td>Febrero</td>
                    <?php elseif($pago->mes == 3): ?>
                    <td>Marzo</td>
                    <?php elseif($pago->mes == 4): ?>
                    <td>Abril</td>
                    <?php elseif($pago->mes == 5): ?>
                    <td>Mayo</td>
                    <?php elseif($pago->mes == 6): ?>
                    <td>Junio</td>
                    <?php elseif($pago->mes == 7): ?>
                    <td>Julio</td>
                    <?php elseif($pago->mes == 8): ?>
                    <td>Agosto</td>
                    <?php elseif($pago->mes == 9): ?>
                    <td>Septiembre</td>
                    <?php elseif($pago->mes == 10): ?>
                    <td>Octubre</td>
                    <?php elseif($pago->mes == 11): ?>
                    <td>Noviembre</td>
                    <?php elseif($pago->mes == 12): ?>
                    <td>Diciembre</td>
                    <?php endif; ?>
                    <?php if($pago->paid == "Pagado" and $pago->repa === "1"): ?>
                    <td class="border border-slate-300" style="color:green"><?php echo e($pago->paid); ?></td>
                    <td>
                        <form id="form_ac" method="post" action="<?php echo e(route('admin.pay_act_des',$pago->id)); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="submit" class="act_des" name="de" value="Desactivar" onclick="this.form.submit()">
                        </form>
                    </td>
                    <?php elseif($pago->paid == "Sin pagar" and $pago->repa === "1"): ?>
                    <td style="color:red"><?php echo e($pago->paid); ?></td>
                    <td>
                        <form id="form_ac" method="post" action="<?php echo e(route('admin.pay_act_des',$pago->id)); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="submit" class="act_des" name="ac" value="Activar" onclick="this.form.submit()">
                        </form>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </table>
            <input type="radio" name="radio" id="radio_1" onchange="nuke()">
            <input type="radio" name="radio" id="radio_2" checked onchange="nuke()">
            <a href="<?php echo e(route('cliente.add_month',$users->id)); ?>" style="display:none;" id="meses">Agregar meses</a>
            <script>
                function nuke(){
                    const radio_1 =  document.getElementById('radio_1').checked;
                    const radio_2 =  document.getElementById('radio_2').checked;
                    
                    if(radio_2){
                        const meses =  document.getElementById('meses').style.display = 'none';
                    }else{
                        const meses =  document.getElementById('meses').style.display = 'block';
                    }
                }
            </script>
        </div>

        <style>
            .deuda_pagos{
                display: grid;
                grid-template-columns: 1fr;
                height: fit-content;
                justify-items: center;
            }

            #calculadora{
                display: none;
            }
        </style>

        <div class="deuda_pagos">
            <div>
            <?php if($users->total_debt == 0 or $users->total_debt < 0): ?> <h1>Deuda del cliente</h1>
                <p>El cliente esta solvente con el pago de su instalacion</p>
                <?php else: ?>
                <p>El cliente tiene una deuda de <span style="color: red;"><?php echo e(round($users->total_debt,2)); ?>$</span></p>
                <form action="<?php echo e(route('cliente.pay_debt',$users->id)); ?>" class="service_pay" method="post">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('put'); ?>
                    <input type="hidden" name="name" value="<?php echo e($users->full_name); ?>">
                    <input type="hidden" name="dir" value="<?php echo e($users->dir); ?>">
                    <label>
                        Concepto<br>
                        <input type="text" name="conc" value="Pago de <?php echo e(round($users->total_debt,2)); ?>$ de la instalacion" required>
                    </label><br>
                    <label>
                    Cobrador:
                    <select name="reg">
                        <option value="marco escala">Marco Escala</option>

                        <?php if(auth()->user()->email == 'antonio'): ?>
                        <option value="marco antonio" selected>Marco Antonio</option>
                        <?php else: ?>
                        <option value="marco antonio">Marco Antonio</option>
                        <?php endif; ?>

                        <option value="yurbi laya">Yurbis Laya</option>
                        <?php if(auth()->user()->email == 'kennerth'): ?>
                            <option value="kennerth salazar" selected>Kennerth Salazar</option>
                        <?php else: ?>
                            <option value="kennerth salazar">Kennerth Salazar</option>
                        <?php endif; ?>
                        <option value="rossana">Rossana</option>
                        <option value="eduardo figueroa">Eduardo Figueroa</option>
                        <option value="eduardo granadillo">Eduardo Granadillo</option>
                        <option value="toto">Toto</option>
                        <option value="tototito">Tototito</option>
                        <option value="luis cotico">Cotico</option>
                        <option value="el gordo">El gordo</option>
                        <option value="jean carlos">Jean Carlos</option>
                        <option value="guillermo escala">Guillermo Escala</option>
                    </select>
                </label>
                    <br>
                    <label>
                        Fecha de pago<br>
                        <input type="date" name="day" required value="<?php echo e(date('Y-m-d',strtotime(date('Y-m-d')))); ?>">
                    </label>
                    <br>
                    <label>
                        Monto<br>
                        <input type="text" placeholder="Dolar" name="d" id="d_d">
                        <input type="text" placeholder="Bolivar" name="bs" id="d_b" class="bs_deb" onkeyup="ref_deb()">
                        <input type="text" placeholder="Zelle A" name="za" id="d_za">
                        <input type="text" placeholder="Zelle B" name="zb" id="d_zb">
                        <input type="number" placeholder="Referencia del pagomovil" pattern="^[0-9]{5}$" value="" name="pm_ref_dep" id="pm_ref_dep"><small id="show_alert_dep" style="color:red; display:none;">Referencia del pagomovil obligatoria y debe estar en entre el rango 10000 - 99999*</small>
                    </label>

                    <div class="calculadora" id="calculadora">
                        <h1 class="cal_div"></h1>
                        <h1 id="h1_dep"></h1>
                        <h1 id="h2_dep"></h1>
                        <h1 id="h3_dep"></h1>
                    </div>

                        <script>
                            function ref_deb() {
                                let h1_dep = document.querySelector("#h1_dep");
                                let h2_dep = document.querySelector("#h2_dep");
                                let h3_dep = document.querySelector("#h3_dep");
                                let cal_div = document.querySelector(".cal_div");

                                let show_alert_dep = document.querySelector("#show_alert_dep");

                                let calcu = document.querySelector('#calculadora');
                                
                                let bs_deb = document.querySelector(".bs_deb");
                                let input_ref_deb = document.getElementById('pm_ref_dep');

                                if(bs_deb.value > 0){
                                    input_ref_deb.style.display = "block"
                                    show_alert_dep.style.display = "block"
                                    input_ref_deb.setAttribute("required", "");
                                }else{
                                    input_ref_deb.style.display = "none";
                                    show_alert_dep.style.display = "none"
                                    input_ref_deb.value = "";
                                    input_ref_deb.removeAttribute("required", "");
                                }

                                let cambio = parseFloat((bs_deb.value / <?php echo e($tasa->tasa); ?>).toFixed(2));
                                let iva = parseFloat(((bs_deb.value * 0.16) / <?php echo e($tasa->tasa); ?>).toFixed(2));
                                let total_bs = parseFloat(cambio + iva);
                                let total_d = parseFloat(bs_deb.value * 0.16) + parseFloat(bs_deb.value);

                                if(bs_deb.value > 0){
                                    h1_dep.innerHTML = "Al cambio BCV: " + (bs_deb.value / <?php echo e($tasa->tasa); ?>).toFixed(2) + "$";
                                    h2_dep.innerHTML = "16% de Iva: " + (bs_deb.value * 0.16).toFixed(2) + "Bs." + " (" + ((bs_deb.value * 0.16) / <?php echo e($tasa->tasa); ?>).toFixed(2) + "$)";
                                    h3_dep.innerHTML = "Total: " + total_d.toFixed(2) + "Bs";
                                    cal_div.innerHTML = "Calculadora";
                                    calcu.style.display = "block";
                                }else{
                                    h1_dep.innerHTML = "";
                                    h2_dep.innerHTML = "";
                                    h3_dep.innerHTML = "";
                                    cal_div.innerHTML = "";
                                    calcu.style.display = "none";
                                }
                            }
                        </script>

                        <input type="hidden" name="tasa" value="<?php echo e($tasa->tasa); ?>">
                    
                    <br>
                    <?php if($users->active == 1): ?>
                        <input type="submit" class="modify_pay" value="Pagar">
                    <?php else: ?>
                        <p>Estatus del cliente: <span style="color: red;">DESACTIVADO</span></p>
                    <?php endif; ?>
                </form>
                <?php endif; ?>
            </div>
                <div class="pagos_hechos">
                    <p>Pagos realizados</p>
                    <table border="1">
                        <tr>
                            <th>Monto $</th>
                            <th>Monto Bs</th>
                            <th>Zelle A</th>
                            <th>Zelle B</th>
                            <th>Fecha de pago</th>
                        </tr>
                    <?php $__currentLoopData = $pagos_realizados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $realizado): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($realizado->monto_d+0); ?></td>
                            <td><?php echo e($realizado->monto_bs+0); ?></td>
                            <td><?php echo e($realizado->monto_z_1+0); ?></td>
                            <td><?php echo e($realizado->monto_z_2+0); ?></td>
                            <td><?php echo e($realizado->fecha_pago); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </table>  
                </div>
            <?php echo $pagos_realizados->links(); ?>

        </div>
        <div></div>

        <style>
            .soportes{
                display: grid;
                grid-template-columns: 1fr;
                justify-items: center;
                align-items: center;
            }
        </style>
        
        <div class="soportes">
                <h1>Soportes</h1>
                <table border="1" style="text-align: center;">
                    <tr>
                        <th>#</th>
                        <th>Cedula</th>
                        <th>Nota</th>
                        <th>Total</th>
                        <th>Fecha del soporte</th>
                        <th>Op</th>
                        <th>Borrar</th>
                    </tr>
                <?php $__currentLoopData = $support; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $support_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($users->id_user == $support_item->id_user): ?>
                    <tr>
                        <td><?php echo e($i++); ?></td>
                        <td><?php echo e($support_item->id_user); ?></td>
                        <td><?php echo e($support_item->nota); ?></td>
                        <td><?php echo e($support_item->total+0); ?>$</td>
                        <td><?php echo e($support_item->fecha); ?></td>
                        <?php if($support_item->active == 0 ): ?>
                            <td>
                                <form action="<?php echo e(route('ac_sup',$support_item->id)); ?>" method="post">
                                    <?php echo csrf_field(); ?>
                                    <input type="submit" value="Activar">
                                </form>
                            </td>
                        <?php elseif($support_item->active == 1): ?>
                            <td>
                                <form action="<?php echo e(route('des_sup',$support_item->id)); ?>" method="post">
                                    <?php echo csrf_field(); ?>
                                    <input type="submit" value="Desactivar">
                                </form>
                            </td>
                        <?php endif; ?>
                        <td>
                            <form action="<?php echo e(route('del_sup',$support_item->id)); ?>" method="post">
                                <?php echo csrf_field(); ?>
                                <input type="submit" value="Borrar">
                            </form>
                        </td>
                    </tr>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </table>  
            </div>
        </div>
    </div>

    <?php if(session('eliminar') == "ok"): ?>
    <script>
        Swal.fire(
            'Eliminado!',
            'El pago ha sido eliminado',
            'con exito'
        )
    </script>
    <?php endif; ?>

    <form action="<?php echo e(route('cliente.update_meses',$users->id)); ?>" method="post" class="form" id="meses_form" style="display:none">
        <?php echo method_field('put'); ?>
        <?php echo csrf_field(); ?>
        <input type="hidden" value="<?php echo e($users->id); ?>" name="usuario">
        <label for="">
            abono
            <input type="radio" name="ab" id="lb_1" checked onclick="check()">
        </label>

        <label for="">
            mensualidad completa
            <input type="radio" name="ab" id="lb_2" onclick="check()">
        </label>

        <input type="number" name="add_meses" id="add_meses" value="1" disabled>

        <input type="submit" id="sub_add_meses" value="Agregar meses">
    </form>

    <?php if(session('msg') == 'ok'): ?>
    <script>
        Swal.fire(
            'Error!',
            'Desactive el soporte antes de eliminarlo',
        )
    </script>
    <?php endif; ?>

    <?php if(session('pagado') == "ok"): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Pago efectuado con exito',
            color: '#000',
            footer: '<a href="<?php echo e(route("cliente.fac",$factura)); ?>" class="print_fac">Imprimir factura</a>',
        })
    </script>
    <?php endif; ?>

    <?php if(session('ref') == "ok"): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Pago no procesado! referencia duplicada',
            color: '#000',
        })
    </script>
    <?php endif; ?>

    <script>
        $('.formulario-eliminar').submit(function(e) {
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
        $('.formulario-pagar').submit(function(e) {

            let form_1 = document.getElementById('in_d').value;
			let form_2 = document.getElementById('bs').value;
			let form_3 = document.getElementById('in_za').value;
			let form_4 = document.getElementById('in_zb').value;
            let form_5 = document.getElementById('bs_pm').value;

            e.preventDefault();
            
            Swal.fire({
                title: '¿Realizar pago?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Pagar',
                cancelButtonText: 'Cancelar',
                
            }).then((result) => {
                if (parseFloat(form_1) > 0 || parseFloat(form_2) > 0 || parseFloat(form_3) > 0 || parseFloat(form_4) > 0 || parseFloat(form_5) > 0) {
                    if (result.isConfirmed) {
                        this.submit();
                    }else{
                        e.preventDefault();
                    }
                }else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'El formulario no debe quedar vacio',
                    })
					
				}
            })
        });

        $('.service_pay').submit(function(e) {

            let form_1 = document.getElementById('d_d').value;
            let form_2 = document.getElementById('d_b').value;
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
                cancelButtonText: 'Cancelar',
                
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
                        text: 'El formulario no debe quedar vacio',
                    })
                }
            })
        });
    </script>

    <script>
        function si() {
            document.getElementById("avance").style.display = "block";
        }

        function no() {
            document.getElementById("avance").style.display = "none";
            let advan = document.querySelector('#ran');

            advan.value = "";
        }
    </script>
    <?php else: ?>
        <p>Privilegios insuficientes</p>
    <?php endif; ?>

    <style>
        .page-link, .active, .page-item{
            color: #fff;
            background-color: #000;
        }

        .page-item.active .page-link{
            background-color: #000;
            color: #fff;
            border-color: #fff;
        }

    </style>

<?php if(session('form') == "ok"): ?>
        <script>
            Swal.fire({
            title: "<strong>¿Agregar meses?</strong>",
            showCancelButton: true,
            cancelButtonText:'Cerrar',
            showConfirmButton: false,
            html: `<form action="<?php echo e(route('cliente.update_meses',$users->id)); ?>" method="post" id="meses_form">
                  <?php echo method_field('put'); ?>
                  <?php echo csrf_field(); ?>
                  <input type="hidden" value="<?php echo e($users->id); ?>" name="usuario">

                  <input type="number" name="add_meses" id="add_meses" value="1">

                  <input type="submit" id="sub_add_meses" value="Agregar meses">
              </form>`
        });
            let radio_1_form = document.getElementById('lb_1');
            let radio_2_form = document.getElementById('lb_2');

            let meses_form = document.getElementById('add_meses');
            let sub_add_meses = document.getElementById('sub_add_meses');

            function check(){
                if(radio_2_form.checked == true){
                    meses_form.disabled = false;
                    sub_add_meses.disabled = false;
                }else{
                    meses_form.disabled = true;
                    sub_add_meses.disabled = true;
                }
            }
        </script>
    <?php endif; ?>
</body>

</html>
<?php echo $__env->make('layouts.side_bar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\control_de_pago\resources\views//pagar.blade.php ENDPATH**/ ?>