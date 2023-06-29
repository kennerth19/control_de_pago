<!DOCTYPE html>
<html lang="en" id="src">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$users->full_name}} MODIFICACION</title>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="shortcut icon" sizes="60x60" href="/control_de_pago/public/img/favicon-16x16.png">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
</head>

<body>
    <style>
        .edit_cliente {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-column-gap: 30px;
            grid-row-gap: 15px;
            background-color: #fff;
            padding: 15px 20px 15px 20px;
            border: solid 1px red;
            box-shadow: 4px 4px 8px black;
            border: 1px solid;
            border-radius: 5px;
            text-align: left;
            font-family: monospace;
            font-style: oblique;
            font-weight: 800;
            align-items: center;
            width: 87%;
            min-width: min-content;
        }

        .entrada_modify {
            padding-top: 5px;
            border: 0;
            border-bottom: 1px solid black;
            outline: none;
            resize: none;
            width: 80%;
        }

        .cliente_submit {
            background-color: #fff;
            border: solid 1px;
            border-radius: 5px;
            padding: 10px;
        }

        .cliente_submit:hover {
            background-color: #000;
            color: #fff;
            cursor: pointer;
        }

        .src_cancel {
            background-color: #fff;
            border: solid 1px;
            border-radius: 5px;
            padding: 10px;
        }

        .src_cancel:hover {
            background-color: #000;
            color: #fff;
            cursor: pointer;
        }

        .src_on{
            display: block;
        }

        .src_off{
            width: 0;
            height: 0;
            display: none;
        }
    </style>

    @if(session('same_id') == "ok")
    <script>
        Swal.fire(
            'Error!',
            'Error: cedula ya registrada en la BD!'
        )
    </script>
    @endif

    <script>
    </script>
    <script>
        function iframe_none() {
            let ventana = document.getElementById("src");
            window.parent.close;
        }

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

        document.addEventListener("dragover", function(event) {
            event.preventDefault();
        }, false);

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
    <form action="{{route('cliente.update',$users)}}" method="POST" class="edit_cliente w-full max-w-lg m-5">
        <h1 class="tittle_edit">Modificacion de cliente</h1>
        <td class="editar">
        @if(auth()->user()->role == 1 || auth()->user()->role == 2)
            @if($users->active == 0)
            <a href="{{ route('control.activate',$users->id) }}" style="justify-self:center;" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">Activar</a>
            @else
            <a href="{{ route('control.desactivate',$users->id) }}" style="justify-self:center;" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">Desactivar</a>
            @endif
        @else
        <p style="color: grey; cursor:pointer">No se puede activar o desactivar</p>
        @endif
        </td>
        @method('put')
        @csrf
        <label>
            Nombre completo: <br>
            <input type="text" class="entrada_modify" name="full_name" value="{{$users->full_name}}">
            @error('full_name')
            <br>
            <small style="color:#FF0000">*{{$message}}</small>
            <br>
            @enderror
        </label>
        <label>
            Cedula: <br>
            <input type="text" class="entrada_modify" maxlength="8" onkeypress="return valideKey(event);" id="val" name="id_user" value="{{$users->id_user}}">
            @error('id_user')
            <br>
            <small style="color:#FF0000">*{{$message}}</small>
            <br>
            @enderror
        </label>
        <label>
            Direccion: <br>
            <input type="text" class="entrada_modify" name="dir" value="{{$users->dir}}">
            @error('dir')
            <br>
            <small style="color:#FF0000">*{{$message}}</small>
            <br>
            @enderror
        </label>
        <label>
            Telefono: <br>
            <input type="text" class="entrada_modify" onkeypress="return valideKey(event);" id="val" name="tlf" value="{{$users->tlf}}">
            @error('tlf')
            <br>
            <small style="color:#FF0000">*{{$message}}</small>
            <br>
            @enderror
        </label>
        <label>
            servidor: <br>
            <select name="servidor" class="entrada_modify">
                @if($users->servidor == 'cerro')
                    <option value="cerro" selected>Cerro</option>
                @else
                    <option value="cerro">Cerro</option>
                @endif

                @if($users->servidor == 'rancho_grande')
                    <option value="rancho_grande" selected>Rancho grande</option>
                @else
                    <option value="rancho_grande">Rancho grande</option>
                @endif

                @if($users->servidor == '23_enero')
                    <option value="23_enero" selected>23 de enero</option>
                @else
                    <option value="23_enero">23 de enero</option>
                @endif

                @if($users->servidor == 'Default')
                    <option value="Default" selected>Default</option>
                @else
                    <option value="Default">Default</option>
                @endif
            </select>
        </label>
        <label>
            ip: <br>
            @if($users->ip == "Default")
            <input type="text" class="entrada_modify" onkeypress="return valideKey(event);" id="val" name="ip" value="">
            @else
            <input type="text" class="entrada_modify" onkeypress="return valideKey(event);" id="val" name="ip" value="{{$users->ip}}">
            @endif
        </label>
        <label>
            Dia de pago: <br>
            <input type="date" class="entrada_modify" name="day" value="{{$users->day}}">
            @error('day')
            <br>
            <small style="color:#FF0000">*{{$message}}</small>
            <br>
            @enderror
        </label>
        <label>
            Dia de corte: <br>
            @if(auth()->user()->email == 'marco' || auth()->user()->email == 'antonio' || auth()->user()->email == 'kennerth' || auth()->user()->email == 'jean')
            <input type="date" class="entrada_modify" name="cut" value="{{$users->cut}}">
            @else
            <p>{{$users->cut}}</p>
            @endif
            @error('cut')
            <br>
            <small style="color:#FF0000">*{{$message}}</small>
            <br>
            @enderror
        </label>
        <label>
            Plan: <br>
            <select name="plan" class="entrada_modify">
                @foreach($planes as $plan)

                @if($users->plan === $plan->nombre_de_plan)
                <option value="{{$plan->nombre_de_plan}}" selected>{{$plan->nombre_de_plan}}</option>
                @else
                <option value="{{$plan->nombre_de_plan}}">{{$plan->nombre_de_plan}}</option>
                @endif

                @endforeach
            </select>
            @error('plan')
            <br>
            <small style="color:#FF0000">*{{$message}}</small>
            <br>
            @enderror
        </label>
        <label>
            Observacion: <br>
            <textarea name="observation" class="entrada_modify">{{$users->observation}}</textarea>
            @error('observation')
            <br>
            <small style="color:#FF0000">*{{$message}}</small>
            <br>
            @enderror
        </label>
        <label>
            Tipo de instalacion <br>
            @if($users->reciever == "220")
            <select name="insta">
                <option value="220" selected>Lite Beam 5AC</option>
                <option value="200">Lite Beam M5</option>
                <option value="100">Fibra con ONU WIFI</option>
                <option value="60">Fibra con ONU BRIDGE</option>
                <option value="Default">Default</option>
            </select>
            @elseif($users->reciever == "200")
            <select name="insta">
                <option value="220">Lite Beam 5AC</option>
                <option value="200" selected>Lite Beam M5</option>
                <option value="100">Fibra con ONU WIFI</option>
                <option value="60">Fibra con ONU BRIDGE</option>
                <option value="Default">Default</option>
            </select>
            @elseif($users->reciever == "100")
            <select name="insta">
                <option value="220">Lite Beam 5AC</option>
                <option value="200">Lite Beam M5</option>
                <option value="100" selected>Fibra con ONU WIFI</option>
                <option value="60">Fibra con ONU BRIDGE</option>
                <option value="Default">Default</option>
            </select>
            @elseif($users->reciever == "60")
            <select name="insta">
                <option value="220">Lite Beam 5AC</option>
                <option value="200">Lite Beam M5</option>
                <option value="100">Fibra con ONU WIFI</option>
                <option value="60" selected>Fibra con ONU BRIDGE</option>
                <option value="Default">Default</option>
            </select>
            @else
            <select name="insta">
                <option value="220">Lite Beam 5AC</option>
                <option value="200">Lite Beam M5</option>
                <option value="100">Fibra con ONU WIFI</option>
                <option value="60">Fibra con ONU BRIDGE</option>
                <option value="Default" selected>Default</option>
            </select>
            @endif
        </label>
        <label>
            Deuda: <br>
            <input type="text" name="debp_t" id="val" onkeypress="return valideKey(event);" value="{{$users->total_debt}}">
        </label>
        <input type="hidden" name="updated_at" value="{{date(now());}}">
        <button id="src_cancel" class="src_cancel" onclick="display_modifiy_none()" style="display: block;">Cerra edición</button>
        <input type="submit" class="cliente_submit" style="justify-self: center;" value="Actualizar cliente">
    </form>
    @if(session('editar') == "ok")
    <script>
        Swal.fire(
            'Editado!',
            'El cliente ha sido editado',
            'con exito'
        )
    </script>
    @elseif(session('editar') == "no")
    <script>
        Swal.fire(
            'Error!',
            'Privilegios insuficientes',
        )
    </script>
    @endif

    <script>
        $('.formulario-editar').submit(function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Estas seguro?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Editar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();

                }
            })
        });

        function display_n() {
            document.getElementById("form_edit").style.display = "none";
        }

        function display_modifiy_none() {
            document.getElementById("src_cancel").style.display = "none";
            document.getElementById("src").remove();

            $('.edit_cliente').submit(function(e) {
                e.preventDefault();
            });
        }
    </script>
</body>

</html>