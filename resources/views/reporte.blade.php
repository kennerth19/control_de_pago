 <!DOCTYPE html>
 <html lang="en">

 <head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="shortcut icon" sizes="60x60" href="/control_de_pago/public/img/favicon-16x16.png">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
     <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
     <title>Reporte general</title>
 </head>

 <body onload="date_now()">
     @extends('layouts.side_bar')
     <style>
         .header,
         .item {
             display: grid;
             grid-template-columns: 5% 75% 10% 10%;
             text-align: center;
             align-items: center;
             justify-items: center;
         }

         .item:hover {
             background-color: #CECECE;
         }

         .activate {
             background-color: #001BFF;
             color: #fff;
             font-weight: bolder;
         }

         .create {
             background-color: #1ABD35;
             color: #fff;
             font-weight: bolder;
         }

         .pre-reg {
             background-color: #9B6FFA;
             color: #fff;
             font-weight: bolder;
         }

         .desactivate {
             background-color: #FF0000;
             color: #fff;
             font-weight: bolder;
         }

         .modificate {
             background-color: #CECECE;
             color: #fff;
             font-weight: bolder;
         }

         .report_description {
             padding: 5px 0 5px 0;
         }

         .type {
             border-radius: 15px;
             padding: 5px;
         }
     </style>

     <script>
         function date_now() {

             var fecha = new Date();

             let mes = fecha.getMonth() + 1;
             let dia = fecha.getDate();
             let ano = fecha.getFullYear();

             let input_day = document.getElementsByClassName('input_date_now');

             if (dia < 10)
                 dia = '0' + dia;
             if (mes < 10)
                 mes = '0' + mes;

             input_day[0].value = ano + "-" + mes + "-" + dia;
             input_day[1].value = ano + "-" + mes + "-" + dia;
         }
     </script>

     <div class="form-check form-switch position-absolute top-0 m-2">
         <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" onchange="toggle_sc()">
         <label class="form-check-label" id="label_report" for="flexSwitchCheckChecked">Reporte general</label>
     </div>

     <script>
         function toggle_sc() {
             const radio_but = document.getElementById('flexSwitchCheckChecked').checked;
             const label_report = document.getElementById('label_report');

             const screem_1 = document.getElementById('screem_1');
             const screem_2 = document.getElementById('screem_2');

             if (radio_but) {
                 screem_1.classList.replace("on", "off");
                 screem_2.classList.replace("off", "on");
                 label_report.innerHTML = "Reporte de instalaciones";

             } else {
                 screem_1.classList.replace("off", "on");
                 screem_2.classList.replace("on", "off");
                 label_report.innerHTML = "Reporte general";
             }
         }
     </script>
     <div class="on" id="screem_1">
         <div class="m-0 row justify-content-center">
             <div class="col-auto p-5 text-center">
                 <input type="text" id="inp" placeholder="Buscar en reportes" class="inp form-control" value="" placeholder="Buscar" maxlength="34" onkeyup="form_react()">
                 <br>
                 <form>
                    <select onchange="select_val()" name="type_report" id="select_report" class="form-control">
                       <option value="0">Todos los reportes</option>
                       <option value="1">Creacion</option>
                       <option value="2">Activacion</option>
                       <option value="3">Desactivar</option>
                       <option value="4">Modificar</option>
                       <option value="5">Registro de fibreros</option>
                    </select>
                 </form>
             </div>
         </div>
         <div class="contain">
             <div class="header">
                 <p>#</p>
                 <p>Reporte</p>
                 <p>Fecha del evento</p>
                 <p>Tipo</p>
             </div>
         </div>
         @foreach($reporte as $reportes)
         <div>
             <div class="item on" id="item_{{$i}}">
                 @if($reportes->type == 1)
                 <p class="on">{{$reportes->id}}</p>
                 <p class="report_description on">{{$reportes->descripcion}}</p>
                 <p class="on">{{$reportes->created_at}}</p>
                 <p class="create type on m-auto">Reporte tipo Creacion</p>
                 @elseif($reportes->type == 2)
                 <p class="on">{{$reportes->id}}</p>
                 <p class="report_description on">{{$reportes->descripcion}}</p>
                 <p class="on">{{$reportes->created_at}}</p>
                 <p class="activate type on m-auto">Reporte tipo Activacion</p>
                 @elseif($reportes->type == 3)
                 <p class="on">{{$reportes->id}}</p>
                 <p class="report_description on">{{$reportes->descripcion}}</p>
                 <p class="on">{{$reportes->created_at}}</p>
                 <p class="desactivate type on m-auto">Reporte tipo desactivar</p>
                 @elseif($reportes->type == 4)
                 <p class="on">{{$reportes->id}}</p>
                 <p class="report_description on">{{$reportes->descripcion}}</p>
                 <p class="on">{{$reportes->created_at}}</p>
                 <p class="modificate type on m-auto">Reporte tipo Modificar</p>
                 @elseif($reportes->type == 5)
                 <p class="on">{{$reportes->id}}</p>
                 <p class="report_description on">{{$reportes->descripcion}}</p>
                 <p class="on">{{$reportes->created_at}}</p>
                 <p class="pre-reg type on m-auto">Reporte tipo Registro</p>
                 @endif
             </div>
             <div style="display: none;">
                 {{$i++}}
             </div>
         </div>
         @endforeach
     </div>

     <div class="off" id="screem_2">
         <br><br>
         <input type="date" value="2022-12-22" id="inicio" class="input_date_now">
         <input type="date" value="" id="fin" class="input_date_now">

         <button type="button" class="btn btn-primary" onclick="date_interval()">calcular</button>

         <style>
             .screem_2_header,
             .screem_2_item {
                 display: grid;
                 grid-template-columns: 3% 8% 13% 6% 7% 5% 6% 11% 7% 7% 5% 8% 8% 8%;
                 align-items: center;
                 justify-items: center;
                 padding-top: 20px;
                 padding-bottom: 20px;
             }

             .screem_2_item:hover {
                 background-color: #CECECE;
             }
         </style>

         <div class="contain">
             <div class="screem_2_header">
                 <p>#</p>
                 <p>Cliente</p>
                 <p>Direccion</p>
                 <p>Cedula</p>
                 <p>Telefono</p>
                 <p>Plan</p>
                 <p>Tipo de instalacion</p>
                 <p>Observacion</p>
                 <p>Servidor</p>
                 <p>Fecha del contrato</p>
                 <p>Monto</p>
                 <p></p>
                 <p>Acciones</p>
                 <p></p>
             </div>
         </div>

         @foreach($pre_reg as $pre_regs)
         <div class="{{$pre_regs->date_pay}} on all_item">
             <div class="screem_2_item on" id="{{$j}}_table">
                 <p class="on">{{$pre_regs->id}}</p>
                 <p class="on">{{$pre_regs->full_name}}</p>
                 <p class="on">{{$pre_regs->dir}}</p>
                 <p class="on">{{$pre_regs->id_user}}</p>
                 <p class="on">{{$pre_regs->tlf}}</p>
                 <p class="on">{{$pre_regs->plan_name}}</p>
                 @if($pre_regs->type == 'at')
                 <p class="on">Antena</p>
                 @else
                 <p class="on">Fibra</p>
                 @endif
                 <p class="report_description" id="{{$j}}_screem_2">{{$pre_regs->observation}}</p>
                 <p class="on">{{$pre_regs->server}}</p>
                 <p>{{$pre_regs->date_pay}}</p>
                 <p class="on">{{$pre_regs->monto}}$</p>

                 @if(auth()->user()->role == 1 || auth()->user()->role == 2)
                 <a class="btn btn-primary" onclick="show_edit('{{$k++}}')">editar</a>
                 <a href="{{route('cliente.pay_report_add',$pre_regs->id)}}" class="btn btn-primary">Registrar</a>
                 <a href="{{route('cliente.pay_report_del',$pre_regs->id)}}" class="btn btn-danger">X</a>
                 @else
                 <p>Editar</p>
                 <p>Registrar</p>
                 <p>Borrar</p>
                 @endif
             </div>
             <div style="display: none;">
                 {{$j++}}
             </div>
         </div>
         @endforeach
     </div>

     <script>
         function show_edit(id) {
             let form_show = document.getElementById('edit_pre_reg');
             let item = [];

             form_show.style.display = "block";

             for (i = 1; i <= {{$pre_reg_count}}; i++) {

                 item[i] = document.getElementById(i);

                 if (id == i) {
                     item[i].style.display = "grid";
                 } else {
                     item[i].style.display = "none";
                 }
             }
         }
     </script>
     <style>
         .edit_pre_reg {
             position: fixed;
             top: 120px;
             left: 10%;
             background-color: #fff;
             height: 450px;
             width: 430px;
             box-shadow: 6px 13px 19px 0px rgb(0 0 0 / 15%);
             border: #000 solid 1px;
             border-radius: 25px;
             padding: 5px;
             /*{{route('cliente.pay_report_edit',$pre_regs->id)}}*/
         }

         .container_edit {
             display: grid;
             grid-template-columns: 1fr 1fr;
             gap: 15px;
             height: 432px;
             width: 420px;
             align-items: start;
             align-items: center;
             justify-items: stretch;
         }

         .close_button {
             cursor: pointer;
             background-color: #000;
             color: #fff;
             padding: 5px;
             display: inline;
             border: solid 1px #fff;
             border-radius: 4px;
         }

         .close_button:hover {
             background-color: #fff;
             color: black;
             border: solid 1px #000;
         }

         .description_area{
            resize: none;
         }

         .active{
            cursor: all-scroll;
         }
     </style>

     <div class="edit_pre_reg wrapper" id="edit_pre_reg" style="display:none;">
         @foreach($pre_reg as $pre_reg_edit)
         <form method="POST" action="{{route('cliente.pay_report_edit',$pre_reg_edit->id)}}" class="container_edit drag" id="{{$l++}}" style="display:none;">
            @csrf
            <label for="">
                Nombre:
                <br>
                <input type="text" name="name" value="{{$pre_reg_edit->full_name}}">
            </label>
             
            <label for="">
                Direccion:
                <br>
                <input type="text" name="dir" value="{{$pre_reg_edit->dir}}">
            </label>
             
            <label for="">
                Numero de cedula:
                <br>
                <input type="number" name="id_user" minlength="7" maxlength="8" value="{{$pre_reg_edit->id_user}}">
            </label>

            <label for="">
                Numero de telefono:
                <br>
                <input type="text" name="tlf" value="{{$pre_reg_edit->tlf}}">
            </label>

            <label for="">
                Plan:
                <br>
                <select name="plan_name">
                    @foreach($planes as $plan)
                        @if($pre_reg_edit->plan_name == $plan->nombre_de_plan)
                            <option value="{{$plan->nombre_de_plan}}" selected>{{$plan->nombre_de_plan}}</option>
                        @else
                            <option value="{{$plan->nombre_de_plan}}">{{$plan->nombre_de_plan}}</option>
                        @endif
                    @endforeach
                </select>
            </label>

            <label for="">
                Tipo de instalacion:
                <br>
                <select name="type" id="">
                    @if($pre_reg_edit->type == 'fb')
                        <option value="fb" selected>Fibra</option>
                        <option value="at">Antena</option>
                    @else
                        <option value="fb">Fibra</option>
                        <option value="at" selected>Antena</option>
                    @endif
                </select>
            </label>
             
            <label for="">
                Observacion:
                <br>
                <textarea name="observation" id="" cols="15" rows="3" class="description_area">{{$pre_reg_edit->observation}}</textarea>
            </label>
             
            <label for="server">
                Servidor:
                <br>
                <select name="server_a" id="">
                    @if($pre_reg_edit->type == 'fb')
                        <option value="cerro">Cerro</option>
                        <option value="rancho_grande">Rancho grande</option>
                        <option value="23_enero" selected>23 de enero</option>
                        <option value="Default">Default</option>
                    @else
                        <option value="cerro">Cerro</option>
                        <option value="rancho_grande">Rancho grande</option>
                        <option value="23_enero">23 de enero</option>
                        <option value="Default">Default</option>
                    @endif
                </select>
            </label>

             <label for="">
                fecha de instalacion:
                <br>
                <input type="date" name="insta_date" value="{{$pre_reg_edit->date_pay}}">
             </label>

             <label for="">
                Monto cancelado:
                <input type="number" name="total" id="" value="{{$pre_reg_edit->monto}}">
             </label>

             <button onclick="close_tab()" class="close_button">Cerrar</button>
             <input type="submit" class="close_button" value="Editar">
         </form>
         @endforeach
     </div>

     <script>
         function close_tab() {
             window_fixed = document.getElementById('edit_pre_reg').style.display = "none";

             $('.container_edit').submit(function(e) {
                e.preventDefault();
            });
         }
     </script>

     <script>
         function date_interval() {
             const inicio_date = new Date(document.getElementById('inicio').value);
             const fin_date = new Date(document.getElementById('fin').value);

             if (inicio_date == 'Invalid Date' || fin_date == 'Invalid Date') {
                 Swal.fire('Debe ingresar 2 fechas');
             } else if (inicio_date > fin_date) {
                 Swal.fire('Debe ingresar un intervalo valido');
             } else {
                 let inicio = new Date(inicio_date);
                 let fin = new Date(fin_date);

                 let all_item = document.getElementsByClassName('all_item');

                 for (i = 0; i < all_item.length; i++) {
                     all_item[i].classList.replace("on", "off");
                 }

                 while (fin.getTime() >= inicio.getTime()) {
                     inicio.setDate(inicio.getDate() + 1);

                     let dia = inicio.getDate();
                     let mes = (inicio.getMonth() + 1);

                     if (mes <= 9) {
                         mes = '0' + mes;
                     }

                     if (dia <= 9) {
                         dia = '0' + dia;
                     }

                     let fecha_formateada = inicio.getFullYear() + '-' + mes + '-' + dia;

                     let reg_div = document.getElementsByClassName(fecha_formateada);

                     for (i = 0; i < reg_div.length; i++) {
                         if (fecha_formateada == reg_div[i].classList[0]) {
                             reg_div[i].classList.replace("off", "on");
                         } else {
                             reg_div[i].classList.replace("on", "off");
                         }
                     }
                 }
             }
         }

         function select_val() {
             let select_val = document.getElementById("select_report").value;
             let new_array = [];

             for (let i = 1; i <= 100; i++) {

                 new_array[i] = document.getElementById('item_' + i);

                 if (select_val == '1' && new_array[i].innerHTML.includes('Reporte tipo Creacion')) {
                     new_array[i].classList.replace("off", "on");
                 } else if (select_val == '2' && new_array[i].innerHTML.includes('Reporte tipo Activacion')) {
                     new_array[i].classList.replace("off", "on");
                 } else if (select_val == '3' && new_array[i].innerHTML.includes('Reporte tipo desactivar')) {
                     new_array[i].classList.replace("off", "on");
                 } else if (select_val == '4' && new_array[i].innerHTML.includes('Reporte tipo Modificar')) {
                     new_array[i].classList.replace("off", "on");
                 } else if (select_val == '5' && new_array[i].innerHTML.includes('Reporte tipo Registro')) {
                     new_array[i].classList.replace("off", "on");
                 } else if (select_val == '0') {
                     new_array[i].classList.replace("off", "on");
                 } else {
                     new_array[i].classList.replace("on", "off");
                 }
             }
         }

         function form_react() {
             let inp = document.getElementById("inp").value;
             let new_array = [];

             for (let i = 1; i <= {{$reporte_count}}; i++) {
                 new_array[i] = document.getElementById(i);

                 if (new_array[i].innerHTML.toLowerCase().includes(inp.toLowerCase())) {
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
        const wrapper = document.querySelector(".wrapper"),
        header = wrapper.querySelector(".drag");

         function onDrag({
             movementX,
             movementY
         }) {
             let getStyle = window.getComputedStyle(wrapper);
             let leftVal = parseInt(getStyle.left);
             let topVal = parseInt(getStyle.top);
             wrapper.style.left = `${leftVal + movementX}px`;
             wrapper.style.top = `${topVal + movementY}px`;
         }

         header.addEventListener("mousedown", () => {
             header.classList.add("active");
             header.addEventListener("mousemove", onDrag);
         });

         document.addEventListener("mouseup", () => {
             header.classList.remove("active");
             header.removeEventListener("mousemove", onDrag);
         });
     </script>

     <style>
         .off {
             display: none;
         }
     </style>
 </body>

 </html>