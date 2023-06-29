<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" sizes="60x60" href="/control_de_pago/public/img/favicon-16x16.png">
    <title>Panel de administrador</title>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
</head>

<body>
    <?php if(auth()->user()->role == 1 || auth()->user()->role == 2): ?>
    <style>
        * {
            justify-items: center;
        }

        .crear_planes {
            padding: 5px;
        }

        .grid-container {
            display: grid;
            grid-template-columns: auto auto;
            padding: 60px;
        }

        .grid-item {
            text-align: center;
            margin: 20px;
        }

        .contenedor {
            display: grid;
            grid-template-columns: 1fr;
            border: solid 1px black;
        }

        .item {
            padding: 15px;
            text-align: center;
        }

        .des {
            resize: none;
        }

        .container_2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            padding-bottom: 15px;
        }

        .conta {
            border: solid 1px black;
            padding: 5px;
        }

        .report_container {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .sub_container {
            display: grid;
            grid-template-columns: 1fr;
        }

        a:link,
        a:visited,
        a:active {
            text-decoration: none;
            padding: 5px 20px 5px 20px;
            border: solid 1px black;
        }

        #generar {
            margin: 5px;
        }

        .api {
            border: solid 1px black;
        }
    </style>
    
    <h1>Bienvenid@ <?php echo e(Auth::user()->email); ?></h1>
    
    <div class="grid-container">
        <div class="grid-item">
            <h1>Listado de planes</h1>
            <table border="1">
                <tr>
                    <th>#</th>
                    <th>Nombre del plan</th>
                    <th>Velocidad</th>
                    <th>Dedicado</th>
                    <th>Tipo</th>
                    <th>Valor</th>
                    <th>Descripcion</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                </tr>
                <?php $__currentLoopData = $record_planes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $planes): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($planes->id); ?></td>
                    <td><?php echo e($planes->nombre_de_plan); ?></td>
                    <td><?php echo e($planes->plan); ?></td>
                    <td><?php echo e($planes->dedicado); ?></td>
                    <?php if($planes->type == 0): ?>
                        <td>Inalambrico</td>
                    <?php else: ?>
                        <td>Fibra</td>
                    <?php endif; ?>
                    <td><?php echo e($planes->valor); ?>$</td>
                    <td><?php echo e($planes->descripcion); ?></td>
                    <td>
                        <a href="<?php echo e(route('plan.edit',$planes->id)); ?>" class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">Modificar</a>
                    </td>
                    <td>
                        <form action="<?php echo e(route('plan.delete',$planes)); ?>" class="formulario-eliminar-plan" method="POST">
                            <?php echo method_field('delete'); ?>
                            <?php echo csrf_field(); ?>
                            <button type="submit">Eliminar plan</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </table>
        </div>
        <div class="grid-item">
            <form action="<?php echo e(route('admin.plan')); ?>" method="POST" class="contenedor crear_planes formulario-crear-plan">
                <h1>Creacion de planes</h1>
                <?php echo csrf_field(); ?>
                <label class="item">
                    Nombre del plan:
                    <input type="text" required name="nombre_de_plan">
                </label>
                <label class="item">
                    Velocidad:
                    <input type="text" required placeholder="Ejemplo: 3MB" name="plan">
                </label>
                <label class="item">
                    Valor del plan:
                    <input type="number" required min="0" name="valor">
                </label>
                <label class="item">
                    tipo de servicio:
                    Inalambrico<input type="radio" checked required name="tipo" value="0">
                    Fibra<input type="radio" required name="tipo" value="1">
                </label>
                <label class="item">
                    tipo de plan:
                    Residencial<input type="radio" checked required name="dedicado" value="0">
                    Dedicado<input type="radio" required name="dedicado" value="1">
                </label>
                <label class="item">
                    Descripcion: <br>
                    <textarea cols="60" rows="10" required class="des" name="descripcion"></textarea>
                </label>
                <input type="submit" class="item" value="Crear plan">
            </form>
        </div>

        <div class="grid-item conta">
            <h1>Copia de seguridad</h1>
            <div class="container_2">
                <div>
                    <h1>Guardar</h1>
                    <form action="<?php echo e(route('admin.backup')); ?>" class="backup" name="backup">
                        <input type="submit" value="Guardar copia de seguridad">
                    </form>
                </div>
                <div>
                    <h1>Restaurar</h1>
                    <form action="<?php echo e(route('admin.restore')); ?>" class="backup-restore" name="restore">
                        <input type="file" name="bd" accept=".sql" /><br><br>
                        <input type="submit" value="Restaurar copia de seguridad">
                    </form>
                </div>
                <div>
                    <h1>Copia de seguridad automatica</h1>
                    <form action="<?php echo e(route('admin.auto_backup')); ?>" class="backup-auto" name="backup-auto">
                        <input type="submit" value="guardar copia de seguridad automatica">
                    </form>
                </div>
            </div>
        </div>

        <div class="grid-item" class="api">

            <?php if($api->api == "1"): ?>
                Servicios del api <span style="color: green; font-weight: bold;">ACTIVADOS</span> <br>
            <?php else: ?>
                Servicios del api <span style="color: grey; font-weight: bold;">DESACTIVADOS</span> <br>
            <?php endif; ?>

            <form action="<?php echo e(route('admin.api')); ?>" method="post">
                <?php echo csrf_field(); ?>
                <label>
                    Activar
                    <input type="radio" name="api" value="1">
                </label>

                <label>
                    Desactivar
                    <input type="radio" name="api" value="0">
                </label>
                
                <input type="submit" value="Confirmar">
            </form>
        </div>
    </div>

    <?php if(session('backup') == "ok"): ?>
    <script>
        Swal.fire(
            'Copia de seguridad!',
            'Base de datos ha sido salvada',
        )
    </script>
    <?php endif; ?>

    <?php if(session('restore-backup') == "ok"): ?>
    <script>
        Swal.fire(
            'Restaurada!',
            'La base de datos ha sido restaurada',
        )
    </script>
    <?php endif; ?>

    <?php if(session('formulario-crear-plan') == "1"): ?>
    <script>
        Swal.fire(
            'Creado!',
            'Se ha creado un nuevo plan',
        )
    </script>
    <?php endif; ?>

    <?php if(session('formulario-eliminar-plan') == "0"): ?>
    <script>
        Swal.fire(
            'Eliminado!',
            'El plan ha sido eliminado',
        )
    </script>
    <?php endif; ?>

    <script>
        $('.backup').submit(function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Guardar copia de seguridad?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Guardar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            })
        });
    </script>

    <script>
        $('.backup-restore').submit(function(e) {
            e.preventDefault();
            Swal.fire({
                title: '<span style="color:red;">CUIDADO</span> este proceso no se puede deshacer',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Restaurar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            })
        });
    </script>
    <script>
        $('.formulario-eliminar-plan').submit(function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Desea eliminar el plan?',
                icon: 'error',
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
        $('.formulario-crear-plan').submit(function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Crear nuevo plan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Crear',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            })
        });

        function display_n(n) {
            if (n == "1") {
                document.getElementById("generar_1").style.display = "none";
            }
            if (n == "2") {
                document.getElementById("generar_2").style.display = "none";
            }
            if (n == "3") {
                document.getElementById("generar_3").style.display = "none";
            }
            if (n == "4") {
                document.getElementById("generar_4").style.display = "none";
            }
        }
    </script>

    <?php else: ?>
        <p>Privilegios insuficientes</p>
    <?php endif; ?>

</body>

</html>
<?php echo $__env->make('layouts.side_bar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\control_de_pago\resources\views/panel_administrador.blade.php ENDPATH**/ ?>