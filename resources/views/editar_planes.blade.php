<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar plan de {{$planes->plan}}</title>
    <link rel="shortcut icon" sizes="60x60" href="/control_de_pago/public/img/favicon-16x16.png">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
</head>

<body>
    
<a href="{{route('control')}}" style="padding: 5px;">Inicio</a>
    <form action="{{route('plan.update',$planes)}}" class="formulario-plan" method="POST">
        @method('put')
        @csrf
        <label>
            Nombre del plan
            <input type="text" name="nombre_de_plan" id="" value="{{$planes->nombre_de_plan}}">
        </label>
        <br>
        <label>
            Plan
            <input type="text" name="plan" id="" placeholder="ejemplo 3MB" value="{{$planes->plan}}">
        </label>
        <br>
        <label>
            Dedicado:
            Si:<input type="radio" name="dedicado" id="" value="1">
            No:<input type="radio" checked name="dedicado" id="" value="0">
        </label>
        <br>
        <label>
            Valor
            <input type="number" name="valor" id="" value="{{$planes->valor}}">
        </label>
        <br>
        <label>
            Descripcion
            <textarea name="descripcion" id="" cols="30" rows="10">{{$planes->descripcion}}</textarea>
        </label>
        <br>
        <input type="submit" value="Editar plan">
    </form>

    @if(session('formulario-plan') == "ok")
        <script>
            Swal.fire(
                'Editado!',
                'El plan ha sido editado',
                'con exito'
            )
        </script>
    @endif

    <script>
        $('.formulario-plan').submit(function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Estas seguro?',
                text: "Se realizaran los cambios en el plan",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Editar',
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