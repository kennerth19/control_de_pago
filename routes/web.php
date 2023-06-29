<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\user;
use App\Http\Controllers\principal;
use App\Http\Controllers\Admin;
use App\fpdf\FPDF;
use App\fpdf\KodePDF;

use App\Models\Cliente;
use App\Models\Reporte;
use App\Models\Pagos;
use App\Models\Imprimir;
use App\Models\Planes;
use App\Models\sort;
use App\Models\sort_report;
use App\Models\Reporte_pagos;
use App\Models\gastos;
use App\Models\pre_reg;
use App\Models\evento_diario;
use App\Models\soporte;

//Ruta del sistema de control de pago
Route::get('/', [user::class, 'index'])->middleware('auth.admin')->name('control');
Route::get('/lista', [user::class, 'list'])->middleware('auth.admin')->name('control.list');

//Agregar un registro
Route::post('/control/', [user::class, 'store'])->middleware('auth.admin')->name('cliente.store');
Route::put('/control/{id}', [user::class, 'update'])->middleware('auth.admin')->name('cliente.update');

//Editar cliente
Route::get('/control/modificar/{id}', [user::class, 'edit'])->middleware('auth.admin')->name('cliente.edit');
Route::put('/control/{id}', [user::class, 'update'])->middleware('auth.admin')->name('cliente.update');

//Activar cliente
Route::get('/lista/{id}', [user::class, 'activate'])->middleware('auth.admin')->name('control.activate');

//Desactivar cliente
Route::get('/control/{id}', [user::class, 'desactivate'])->middleware('auth.admin')->name('control.desactivate');

//Actualizar statuses
Route::get('/actualizar/', [user::class, 'status'])->middleware('auth.admin')->name('cliente.status');
Route::get('/actualizar_server', [user::class, 'server_active'])->middleware('auth.admin')->name('cliente.status_server');

//Reporte
Route::get('/reporte', [user::class, 'report'])->middleware('auth.admin')->name('cliente.report');

//Pagar
Route::get('/control/pagar/{id}/{page?}', [user::class, 'pay_edit'])->middleware('auth.admin')->name('cliente.pay');
Route::put('/pagar/{id}', [user::class, 'pay_update'])->middleware('auth.admin')->name('cliente.pay_update');

//Agregar meses despues del pago
Route::put('/pagar/add_meses/{id}', [user::class, 'update_meses'])->middleware('auth.admin')->name('cliente.update_meses');

//Agregar meses de usuarios sin mes
Route::get('/add/{id}', [user::class, 'add_month'])->middleware('auth.admin')->name('cliente.add_month');

//Reporte de pagos
Route::get('/reporte_pagos', [user::class, 'pay_customer'])->middleware('auth.admin')->name('cliente.pay.customer');

//Editar reporte de pagos
Route::put('/reporte_pag', [user::class, 'report_update'])->middleware('auth.admin')->name('cliente.report_update');

//Reporte de instalaciones (Agregar, editar y borrar)
Route::get('/reporte/add/{id}', [user::class, 'add_user_new'])->middleware('auth.admin')->name('cliente.pay_report_add');
Route::post('/reporte/edit/{id}', [user::class, 'edit_user_new'])->middleware('auth.admin')->name('cliente.pay_report_edit');
Route::get('/reporte/del/{id}', [user::class, 'delete_pre_reg'])->middleware('auth.admin')->name('cliente.pay_report_del');

//Rango de pagos
Route::get('/reporte_rango', [user::class, 'pay_range'])->middleware('auth.admin')->name('cliente.pay.range');

//Evento diario
Route::get('/evento_diario', [user::class, 'evento_diario'])->middleware('auth.admin')->name('evento_diario');
Route::get('/evento_diario/{id}', [user::class, 'evento_diario_delete'])->middleware('auth.admin')->name('evento_diario_delete');
Route::get('/evento_diario_print', [user::class, 'evento_print'])->middleware('auth.admin')->name('evento_diario_print');
Route::post('/evento_diario/add', [user::class, 'add_evento_diario'])->middleware('auth.admin')->name('evento_diario_add');


//Panel administrativo
Route::get('/administrar', [user::class, 'panel'])->middleware('auth.admin')->name('admin.panel');
Route::get('/backup', [user::class, 'backup'])->middleware('auth.admin')->name('admin.backup');
Route::get('/auto_backup', [user::class, 'auto_backup'])->middleware('auth.admin')->name('admin.auto_backup');
Route::get('/restore', [user::class, 'restore_backup'])->middleware('auth.admin')->name('admin.restore');
Route::post('/planes', [user::class, 'plan'])->middleware('auth.admin')->name('admin.plan');
Route::post('/administrar', [user::class, 'api_activate_desactivate'])->middleware('auth.admin')->name('admin.api');

//Control de gastos
Route::get('/gastos', [user::class, 'bills'])->middleware('auth.admin')->name('admin.bills');
Route::post('/gastos/pay', [user::class, 'pay_bills'])->middleware('auth.admin')->name('admin.pay_bills');
Route::put('/gastos/delete/{id}', [user::class, 'pay_bills_delete'])->middleware('auth.admin')->name('admin.pay_bills_delete');

//Editar planes
Route::get('/planes/modificar/{id}', [user::class, 'plan_edit'])->middleware('auth.admin')->name('plan.edit');
Route::put('/modificar/planes/{id}', [user::class, 'plan_update'])->middleware('auth.admin')->name('plan.update');

//Borrar planes
Route::delete('/planes/borrar/{id}', [user::class, 'plan_delete'])->middleware('auth.admin')->name('plan.delete');

//multipagos
Route::get('/multi', [user::class, 'multi'])->middleware('auth.admin')->name('cliente.multi');

//Activar y desactivar mes
Route::post('/control/act_des/{id}', [user::class, 'pay_act_desc'])->middleware('auth.admin')->name('admin.pay_act_des');

//Eliminar pagos
Route::put('/control/pagar/{id}', [user::class, "pay_delete"])->name('pay.delete');

//Perfil del cliente
Route::get('/perfil/{id}', [user::class, 'profile'])->middleware('auth.admin')->name('cliente.profile');

//Cambiar tasa
Route::put('/reporte_pago', [user::class, 'tasa'])->middleware('auth.admin')->name('cliente.tasa');

//Eliminar reporte
Route::put('/reporte_pagos/{id}', [user::class, 'report_delete'])->middleware('auth.admin')->name('cliente.report_delete');
Route::get('/reporte_pagos/{id}', [user::class, 'report_delete_2'])->middleware('auth.admin')->name('cliente.report_delete_2');

//Pagos de servicios
Route::put('/reporte_pagos/', [user::class, 'pay_services'])->middleware('auth.admin')->name('cliente.pay_services');

//Pagos de deudas
Route::put('/pago/{id}', [user::class, 'pay_debt'])->middleware('auth.admin')->name('cliente.pay_debt');

//Generador de facturas
Route::get('/factura_generada/{id}', [user::class, 'generator_bill'])->middleware('auth.admin')->name('cliente.fac');

//Destacar y quitar destacados
Route::get('/destacated/{id}', [user::class, 'destacated'])->middleware('auth.admin')->name('destacated');

//Ver ref duplicados
Route::get('/reporte_pago/dup', [user::class, 'ref_dup'])->middleware('auth.admin')->name('cliente.dup_ref');

//Ver pagos duplicados
Route::get('/reporte_pago/pay_dup', [user::class, 'pay_dup'])->middleware('auth.admin')->name('cliente.pay_ref');

//Reporte de informacion
Route::get('/reporte_pago/inf', [user::class, 'rep_inf'])->middleware('auth.admin')->name('cliente.rep_inf');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/admin', [Admin::class, 'index'])->middleware('auth.admin')->name('admin');

//Rutas de los fibreros y anteneros
//inicio
Route::get('/lista_fibreros', [principal::class, 'index'])->middleware('auth.admin')->name('lista.fibreros');
Route::get('/lista_antena', [principal::class, 'index_at'])->middleware('auth.admin')->name('lista.anteneros');

//Desactivar o activar registros
Route::post('/evento/{id}', [principal::class, 'act_des'])->middleware('auth.admin')->name('evento.ac.dc');

//Agregar al sistema el usuario nuevo instalado
Route::post('/evento_add/{id}', [principal::class, 'add_new'])->middleware('auth.admin')->name('evento.add');

//Activar boton de instalado o desactivado
Route::post('/lista_fibreros/{id}', [principal::class, 'bolita'])->middleware('auth.admin')->name('fibreros_act_des');

//Agregar o quitar el valor de soporte al ticket
Route::post('/act_sup/{id}', [user::class, 'ac_sup'])->middleware('auth.admin')->name('ac_sup');
Route::post('/des_sup/{id}', [user::class, 'des_sup'])->middleware('auth.admin')->name('des_sup');
Route::post('/del_sup/{id}', [user::class, 'del_sup'])->middleware('auth.admin')->name('del_sup');