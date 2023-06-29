<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

use App\Http\Controllers\Admin;
use App\fpdf\FPDF;
use App\fpdf\KodePDF;

use Carbon\Carbon;
//use PhpParser\Node\Stmt\Foreach_;

date_default_timezone_set("America/Caracas");

class user extends Controller
{
    public function index(Request $request)
    {
        $hoy_format = Carbon::now()->format('Y-m-d'); // hoy con la fecha formateada
        $planes = Planes::select('*')->cursorPaginate(100);
        $orden = sort::select('*')->Where('id', 'like', "1")->get();
        $nombre = $request->get('find');
        $totales = Cliente::count();
        $clientes_comodin = Cliente::select('*')->get();
        $validacion = 0;
        $var_regs = 0;

        //inicio logica para evaluar la api y la variable de confirmacion

        foreach ($orden as $orden_for) {
            $var_regs = $orden_for->reg;
        }

        if ($hoy_format != $orden_for->last_event) {
            $orden_for->api = 1;
            $orden_for->last_event = $hoy_format;
            $orden_for->save();
        }

        //fin de la logica para evaluar la api y la variable de confirmacion

        //estados
        $s = Cliente::Where('status', '=', "7")->Where('active', '=', "1")->count();
        $p_1 = Cliente::Where('status', '=', "3")->Where('active', '=', "1")->count();
        $p_2 = Cliente::Where('status', '=', "2")->Where('active', '=', "1")->count();
        $r_1 = Cliente::Where('status', '=', "4")->Where('active', '=', "1")->count();
        $r_2 = Cliente::Where('status', '=', "5")->Where('active', '=', "1")->count();
        $sol = Cliente::Where('status', '=', "1")->Where('active', '=', "1")->count();
        $d = Cliente::Where('status', '=', "6")->Where('active', '=', "1")->count();
        $u =  Cliente::Where('active', '=', "0")->count();
        //estados

        $reg = $request->get('by'); //status
        $sort = $request->get('asc'); //asc
        $order = $request->get('status'); //registros
        $type_inst = $request->get('type_inst'); //type_inst tipo de instalacion (Fibra, antena)

        foreach ($orden as $orde)

            if ($request->get('by') == "") {
                $orde->status = $orde->status;
            } else {
                $orde->status = $request->get('by');
            }

        if ($request->get('asc') == "") {
            $orde->sort = $orde->sort;
        } else {
            $orde->sort = $request->get('asc');
        }

        if ($request->get('cuenta') == "") {
            $orde->reg = $orde->reg;
        } elseif ($request->get('cuenta') == "total") {
            $orde->sort = $totales;
        } else {
            $orde->reg = $request->get('cuenta');
        }

        $clientes = Cliente::where('full_name', 'like', "%$nombre%")->orWhere('dir', 'like', "%$nombre%")->orderBy($orde->status, $orde->sort)->limit($orde->reg)->get();

        if ($request->get('type_inst') == "") {
            $orde->sort = $orde->sort;
        } else {
            $orde->sort = $request->get('asc');
            $clientes = Cliente::where('full_name', 'like', "%$nombre%")->orWhere('dir', 'like', "%$nombre%")->orderBy($orde->status, $orde->sort)->Paginate($orde->reg);
        }

        $orde->save();

        $count = new Cliente();
        $cuenta = $count->count();
        $hoy = new Carbon();

        $hoy_1 = Carbon::now()->add(1, 'day')->format('Y-m-d'); // hoy + 1 dia
        $hoy_2 = Carbon::now()->add(2, 'day')->format('Y-m-d'); // hoy + 2 dias
        $hoy_3 = Carbon::now()->add(-1, 'day')->format('Y-m-d'); // hoy -1 dia
        $hoy_4 = Carbon::now()->add(-2, 'day')->format('Y-m-d'); // hoy -2 dias

        //ojo i = tiene que ser igual al primer registro
        //y todos los "id" tienen que ser contiguos

        //funcion que altera a todos los clientes uno por uno $i
        for ($i = 1; $i <= $cuenta; $i++) {
            $cliente = Cliente::findOrFail($i);
            if ($hoy_format == $cliente->cut and $cliente->active == 1) {
                $cliente->status = 6; //dia de corte
            } elseif ($cliente->cut == "0000-00-00" and $cliente->active == 1) {
                $cliente->status = 0; //dia de corte
            } elseif ($hoy_1 == $cliente->cut and $cliente->active == 1) {
                $cliente->status = 4; //resta 1 dia
            } elseif ($hoy_2 == $cliente->cut and $cliente->active == 1) {
                $cliente->status = 5; //restan 2 dias
            } elseif ($hoy_3 == $cliente->cut and $cliente->active == 1) {
                $cliente->status = 3; //prorroga dia 1
            } elseif ($hoy_4 == $cliente->cut and $cliente->active == 1) {
                $cliente->status = 2; //prorroga dia 2
            } elseif ($cliente->cut > $hoy_format and $cliente->active == 1) {
                $cliente->status = 1; //solvente
            } elseif ($cliente->cut < $hoy_format and $cliente->active == 1) {
                $cliente->status = 7; //Requiere suspension
            }

            //$cliente->day_cut = $cliente->day;

            /*
            if($cliente->plan == 'Fibra 10MB'){
                $cliente->type = 'fb';
                $cliente->plan_code = 13;
            }
            */

            //logica para desactivar clientes al cabo de 2 meses suspendidos

            $auto_desactivate = Cliente::findOrFail($i);
            $reporte = new Reporte();

            if ($hoy_format > date("Y-m-d", strtotime($auto_desactivate->cut . " + 2 month")) && $auto_desactivate->active == 1) {
                $auto_desactivate->active = 0;
                //$auto_desactivate->observation = " Se desactivo automaticamente";
                $auto_desactivate->save();

                $reporte->type = 3;
                $reporte->descripcion = "SISTEMA: Se ha desactivado AUTOMATICAMENTE el cliente: $auto_desactivate->full_name";
                $reporte->save();
            }

            /*
            activar si requieres reiniciar la deuda
            
            $cliente->advan = 0;
            $cliente->total_debt_m = 0;
            */

            $cliente->save();

            //logica para cambiar el color del cliente que no tenga numero registrado
            $cadena = $cliente->tlf;

            $str_1 = str_starts_with($cadena, '0412');
            $str_2 = str_starts_with($cadena, '0424');
            $str_3 = str_starts_with($cadena, '0414');
            $str_4 = str_starts_with($cadena, '0416');
            $str_5 = str_starts_with($cadena, '0426');

            if ($str_1 || $str_2 || $str_3 || $str_4 || $str_5) {
                $cliente->data = 1;
                $cliente->save();
            } else {
                $cliente->data = 0;
                $cliente->save();
            }

            //$cliente->total_debt_m = 0;
            //$cliente->advan = 0;
        }

        $fecha = Carbon::now()->format('Y-m-d');
        $count = Cliente::count();

        // $API = new Controller();

        // $API->debug = true;

        // if ($API->connect('190.120.248.225', 'eduardoG', '696969')) {

        //     $API->write("/interface/ip/hotspot/hosts/print/",true);  

        //     var_dump($API);

        //     $READ = $API->read(true);

        //     print_r($API);
        //     $API->disconnect();

        // $API->write('/interface/getall');
        // $READ = $API->read();
        // $ARRAY = $API->parse_response($READ);
        // print_r($ARRAY);
        // $API->disconnect();
        //}

        //columna send_confirm = 0 mensaje no enviado
        //columna send_confirm = 1 mensaje enviado

        $cuenta_id = 0;

        $contador = 1;

        $cuenta_tlf = Cliente::Where('data', '=', 0)->where('active', '=', '1')->count();

        return view('registros', compact('clientes', 'fecha', 'contador', 'var_regs', 'count', 'planes', 'validacion', 'orde', 'cuenta_id', 'totales', 's', 'p_1', 'p_2', 'r_1', 'r_2', 'sol', 'd', 'u', 'hoy_format', 'cuenta_tlf'));
    }

    public function add_month(Request $request, $id)
    {

        $cliente = Cliente::findOrFail($id);

        for ($i = 1; $i <= 12; $i++) {
            $pagar = new Pagos();

            $pagar->full_name = $cliente->full_name;
            $pagar->id_user = $cliente->id_user;
            $pagar->dir = $cliente->dir;
            $pagar->type = "0";
            $pagar->monto_bs = 0;
            $pagar->monto_dollar = 0;
            $pagar->monto_zelle_1 = 0;
            $pagar->monto_zelle_2 = 0;
            $pagar->avance = 0;
            $pagar->mes = $i;
            $pagar->codigo_de_pago;
            $pagar->paid = "Sin pagar";
            $pagar->repa = 1;
            $pagar->usuario = NULL;
            $pagar->comodin = NULL;

            $pagar->save();
        }

        return back();
    }

    public function store(Request $request)
    {

        $request->validate([
            'full_name' => 'required',
            'id_user' => 'required',
            'dir' => 'required',
            'monto_inst' => 'required',
        ]);

        $cliente = new Cliente();
        $reporte = new Reporte();
        $pagos = new Pagos();

        $sort = sort::findOrFail(1);

        date_default_timezone_set("America/Caracas");
        $fecha = date('Y-m-d H:i:s');

        $mes = date("m", strtotime($request->date_c));
        $mes_2 = date('n', strtotime($request->date_c));

        $cedulas = Cliente::select('id_user')->get();

        foreach ($cedulas as $cedula) {

            if ($cedula->id_user == $request->id_user) {

                return redirect()->route('control')->with('registrar', 'oknt');
            }
        }

        $cliente->full_name = $request->full_name;
        $cliente->id_user = $request->id_user;
        $cliente->dir = $request->dir;
        $cliente->tlf = $request->tlf;
        $cliente->observation = $request->observation;
        $cliente->day = $request->date_c;

        if ($request->plan == 'Promocion fibra 3MB' or $request->plan == 'Fibra 5MB' or $request->plan == 'Fibra 8MB' or $request->plan == 'Fibra 12MB' or $request->plan == 'Fibra 2MB') {
            $cliente->type = 'fb';
        } else {
            $cliente->type = 'at';
        }

        $cliente->ip = $request->ip;
        $cliente->servidor = $request->servidor;

        $cliente->reciever = $request->insta;

        if ($request->insta == "220") { //5ac
            $cliente->total_debt = 220 - $request->monto_inst;
        } elseif ($request->insta == "200") { //m5
            $cliente->total_debt = 200 - $request->monto_inst;
        } elseif ($request->insta == "100") { //fibra wifi
            $cliente->total_debt = 100 - $request->monto_inst;
        } elseif ($request->insta == "60") { //fibra lan
            $cliente->total_debt = 60 - $request->monto_inst;
        } else { //default
            $cliente->total_debt = 220 - $request->monto_inst;
        }

        $sort->reg += 1;
        $sort->save();

        for ($i = 1; $i <= 12; $i++) {
            $pagos = new Pagos();
            $pagos->mes = $i;
            $pagos->full_name = $request->full_name;
            $pagos->id_user = $request->id_user;
            $pagos->repa = "1";
            $pagos->paid = "Sin pagar";

            $pagos->save();
        }

        if ($mes == '1' || $mes == '3' || $mes == '5' || $mes == '7' || $mes == '8' || $mes == '10' || $mes == '12') {
            $cliente->cut = date("Y-m-d", strtotime($request->date_c . " + 31 day"));
        } elseif ($mes == '4' || $mes == '6' || $mes == '9' | $mes == '11') {
            $cliente->cut = date("Y-m-d", strtotime($request->date_c . " + 30 day"));
        } elseif ($mes == '2') {
            $cliente->cut = date("Y-m-d", strtotime($request->date_c . " + 28 day"));
        }

        $usuario = auth()->user()->email;
        $reporte->descripcion = "$usuario: Se ha REGISTRADO el cliente: " . $cliente->full_name . ", Cedula: " . $cliente->id_user . " de " . $cliente->dir;
        $reporte->type = 1;
        $reporte->save();

        //Logica para asignarle plan desde formulario

        $plan_asig = Planes::where('id', '=', "$request->plan")->firstOrFail();

        $cliente->plan_code = $plan_asig->id;
        $cliente->plan = $plan_asig->nombre_de_plan;
        $cliente->total = $plan_asig->valor;

        //Fin de la logica para asignarle plan desde formulario

        $cliente->save();

        return redirect()->route('control')->with('registrar', 'ok');
    }

    public function edit($id)
    {
        $users = Cliente::findOrFail($id);
        $planes = Planes::select('*')->cursorPaginate(1000);

        return view('/editar', compact('users', 'planes'));
    }

    public function update(Request $request, $id)
    {
        $edit = Cliente::findOrFail($id);
        $plan = Planes::where('nombre_de_plan', 'like', "$request->plan")->firstOrFail();
        $reporte = new Reporte();
        $fecha_actual = Carbon::now();
        $hoy_format = Carbon::now()->format('d/m/Y h:i:s A');
        $mes = date("m", strtotime($request->day));
        $cliente = $edit->full_name;

        $cuenta = Cliente::count();

        //logica 2.0 para el reporte de modificación de clientes

        $usuario = auth()->user()->email;

        $reporte_final = "";

        /*1*/
        if ($edit->full_name != $request->full_name) {
            $reporte_final .= "El nombre, " . $edit->full_name . " a " . $request->full_name . ", ";
        }
        /*2*/
        if ($edit->id_user != $request->id_user) {
            $reporte_final .= "La cedula, " . $edit->id_user . " a " . $request->id_user . ", ";
        }
        /*3*/
        if ($edit->dir != $request->dir) {
            $reporte_final .= "La direccion, " . $edit->dir . " a " . $request->dir . ", ";
        }
        /*4*/
        if ($edit->tlf != $request->tlf) {
            $reporte_final .= "El telefono, " . $edit->tlf . " a " . $request->tlf . ", ";
        }
        /*5*/
        if ($edit->servidor != $request->servidor) {
            $reporte_final .= "El servidor, " . $edit->servidor . " a " . $request->servidor . ", ";
        }
        /*6*/
        if ($edit->ip != $request->ip) {
            $reporte_final .= "La ip, " . $edit->ip . " a " . $request->ip;
        }
        /*7*/
        if ($edit->day != $request->day) {
            $reporte_final .= "El dia de pago, " . $edit->day . " a " . $request->day . ", ";
        }
        /*8*/
        if ($edit->cut != $request->cut) {
            $reporte_final .= "El dia de corte, " . $edit->cut . " a " . $request->cut . ", "; 
        }
        /*9*/
        if ($edit->plan != $request->plan) {
            $reporte_final .= "la observacion, " . $edit->plan . " a " . $request->plan . ", ";
        }
        /*10*/
        if ($edit->observation != $request->observation) {
            $reporte_final .= "la observacion, " . $edit->observation . " a " . $request->observation . ", ";
        }
        /*11*/
        if ($edit->reciever != $request->insta) {
            $reporte_final .= "El tipo de instalacion, " . $edit->reciever . " a " . $request->insta;
        }
        /*12*/
        if ($edit->total_debt != $request->debp_t) {
            $reporte_final .= "La deuda total, " . $edit->total_debt . " a " . $request->total_debt . ", ";
        }

        $reporte_inicio = "Se le modifico al cliente $edit->full_name :";

        $reporte->descripcion = $usuario . ": " . $reporte_inicio . $reporte_final;

        if ($request->plan == 'Promocion fibra 3MB' or $request->plan == 'Fibra 5MB' or $request->plan == 'Fibra 8MB' or $request->plan == 'Fibra 12MB' or $request->plan == 'Fibra 2MB') {
            $edit->type = 'fb';
        } else {
            $edit->type = 'at';
        }
           
        //fin de la logica 2.0 para el reporte de modificacion de clientes

        $edit->full_name = $request->full_name;
        $edit->dir = $request->dir;
        $edit->observation = $request->observation;
        $edit->reciever = $request->insta;
        //AHHHHHHHHHHHHHHHHHHHHHHHH!

        if (auth()->user()->role == 1 || auth()->user()->role == 2) {

            for ($i = 1; $i <= 12; $i++) {
                $pago = Pagos::where('id_user', 'like', "%$edit->id_user%")->where('mes', 'like', "%$i%")->firstOrFail();
                $pago->full_name = $request->full_name;
                $pago->id_user = $request->id_user;
                $pago->save();
            }

            if($edit->cut != $request->cut){
                $evento_diario = new evento_diario();
                $evento_diario->total_d = 0;
                $evento_diario->total_bs = 0;
                $evento_diario->type = 0;
                $evento_diario->evento = "el usuario: $usuario modifico el dia de corte del cliente: $edit->full_name ($edit->cut) : para la fecha de: $request->cut"; 
                $evento_diario->save();
            }

            $edit->ip = $request->ip;
            $edit->servidor = $request->servidor;
            $edit->total_debt = $request->debp_t;
            $edit->tlf = $request->tlf;
            $edit->day = $request->day;
            if(auth()->user()->email == 'antonio' || auth()->user()->email == 'marco' || auth()->user()->email == 'kennerth' || auth()->user()->email == 'jean'){
                $edit->cut = $request->cut;
            }
            $edit->plan = $plan->nombre_de_plan;
            $edit->plan_code = $plan->id;
            $edit->total = $plan->valor;
            $edit->id_user = $request->id_user;
            $edit->save();
            $reporte->type = 4;
            $reporte->save();
            return back()->with('editar', 'ok');
        } else {
            $edit->ip = $edit->ip;
            $edit->servidor = $edit->servidor;
            $edit->total_debt = $edit->debp_t;
            $edit->tlf = $edit->tlf;
            $edit->day = $edit->day;
            $edit->cut = $edit->cut;
            $edit->plan = $edit->plan;
            $edit->plan_code = $edit->plan_code;
            $edit->total = $edit->total;
            $edit->id_user = $edit->id_user;
            $edit->save();
            $reporte->type = 4;
            $reporte->save();
            return back()->with('editar', 'no');
        }
    }

    public function list()
    {
        $clientes = Cliente::select('*')->where('active', '=', '0')->orderBy('updated_at', 'desc')->get();

        return view('desactivados', compact('clientes'));
    }

    public function desactivate($id)
    {
        $desactivar = Cliente::findOrFail($id);
        $reporte = new Reporte();
        date_default_timezone_set("America/Caracas");
        $fecha = date('Y-m-d H:i:s');

        $desactivar->active = 0;
        $desactivar->status = 8;

        $desactivar->save();

        $usuario = auth()->user()->email;
        $reporte->descripcion = "$usuario: Se ha DESACTIVADO el cliente: " . $desactivar->full_name . ", Cedula: " . $desactivar->id_user . " de " . $desactivar->dir;
        $reporte->type = 3;
        $reporte->save();

        return back();
    }

    public function activate(Request $request, $id)
    {
        $activar = Cliente::findOrFail($id);
        $reporte = new Reporte();
        date_default_timezone_set("America/Caracas");
        $fecha = date('Y-m-d H:i:s');

        $activar->active = 1;
        $activar->status = 7;
        $activar->save();

        $reporte->type = 2;
        $usuario = auth()->user()->email;
        $reporte->descripcion = "$usuario: Se ha ACTIVADO el cliente: " . $activar->full_name . ", Cedula: " . $activar->id_user . " de " . $activar->dir;
        $reporte->save();

        return back();
    }

    public function status()
    {

        for ($i = 1; $i <= 655; $i++) {
            $edit = Cliente::findOrFail($i);
            if ($edit->total == 100) {
                $edit->plan_code = "10";
                $edit->plan = "10MB";
            }
            $edit->save();
        }

        return redirect()->route('control');
    }

    public function report(Request $request)
    {
        $reporte = Reporte::select('*')->orderBy('id', 'desc')->get();
        $reporte_count = Reporte::count();
        $pre_reg = pre_reg::select('*')->orderBy('id', 'desc')->get();
        $pre_reg_count = pre_reg::count();
        $planes = Planes::select('*')->cursorPaginate(1000);

        $i = 1;
        $j = 0;
        $k = 1;
        $l = 1;

        return view('reporte', compact('reporte', 'reporte_count', 'pre_reg', 'pre_reg_count', 'planes', 'i', 'j', 'k', 'l'));
    }

    public function pay_edit($id)
    {
        $users = Cliente::findOrFail($id);
        date_default_timezone_set("America/Caracas");

        $pagos_realizados = Reporte_pagos::select('*')->where('active','=','1')->where('cedula', '=', "$users->id_user")->Paginate(5);
        $support = soporte::get();

        $factura = Reporte_pagos::latest('id')->first();

        $pagos = Pagos::where('full_name', 'like', "$users->full_name")->where('id_user', 'like', "$users->id_user")->Paginate(1000);
        $tasa = sort::select('*')->findOrFail("1");
        $sum_d = 0;
        $sum_b = 0;
        $sum_pm = 0;
        $sum_z = 0;
        $sum_z_2 = 0;

        foreach ($pagos as $pago) {

            $sum_d += $pago->monto_dollar;
            $sum_b += $pago->monto_bs;
            $sum_pm += $pago->monto_pm;
            $sum_z += $pago->monto_zelle;
            $sum_z_2 += $pago->monto_zelle_2;
        }

        $i = 1;
        $months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];

        $date = "2022-01-01"; //?
        return view('/pagar', compact('users', 'pagos', 'tasa', 'sum_d', 'sum_b', 'sum_pm', 'sum_z', 'sum_z_2', 'date', 'factura', 'pagos_realizados', 'support', 'i', 'months'));
    }

    public function pay_act_desc(Request $request, $id)
    {

        $pagos = Pagos::findOrFail($id);
        $reporte = new Reporte();

        if ($request->get('de')) {
            $pagos->paid = "Sin pagar";
            $reporte->descripcion = auth()->user()->email . ": Se ha desactivado el mes $pagos->mes del cliente: $pagos->full_name";
            $reporte->type = 3;
        } elseif ($request->get('ac')) {
            $pagos->paid = "Pagado";
            $reporte->descripcion = auth()->user()->email . ": Se ha activado el mes $pagos->mes del cliente: $pagos->full_name";
            $reporte->type = 1;
        }

        $pagos->save();
        $reporte->save();

        return back();
    }

    public function ac_sup($id)
    {
        $soporte = soporte::findOrFail($id);
        $cliente = Cliente::findOrFail(576);

        $soporte->active = 1;

        $cliente->total_deb_support += $soporte->total;

        $soporte->save();
        $cliente->save();

        return back();
    }

    public function des_sup($id)
    {
        $soporte = soporte::findOrFail($id);
        $cliente = Cliente::findOrFail(576);

        $soporte->active = 0;

        $cliente->total_deb_support -= $soporte->total;

        $soporte->save();
        $cliente->save();

        return back();
    }

    public function del_sup($id)
    {
        $soporte = soporte::find($id);

        if ($soporte->active == 0) {
            $soporte->delete();
            return back();
        } else {
            return back()->with('msg', 'ok');
        }
    }

    public function pay_update(Request $request, $id)
    {
        date_default_timezone_set("America/Caracas");

        $mes = date('n', strtotime($request->day));

        $edit = Cliente::findOrFail($id);
        $pagos = Pagos::where('id_user', 'like', "$edit->id_user")
            ->where('full_name', 'like', "$edit->full_name")
            ->where('mes', 'like', "%$mes%")
            ->firstOrFail();
        $tasa = sort::select('*')->findOrFail("1");
        $same = false;

        $referencias = Reporte_pagos::select('pm_ref')->where('active', '=', '1')->where('pm_ref', '!=', NULL)->get();

        foreach ($referencias as $referencia) {
            if ($referencia->pm_ref == $request->pm_ref) {
                $same = true;
                break;
            }
        }

        $reporte_pago = new Reporte_pagos();

        $reporte_pago->cobrado_por = auth()->user()->email;
        $reporte_pago->fecha_pago = date("Y-m-d", strtotime($request->day));
        $reporte_pago->reg = $request->reg;
        $reporte_pago->comodin = date("Y-m-d", strtotime($request->day));
        $reporte_pago->codigo_pago = $pagos->id;
        $reporte_pago->nombre = $edit->full_name;
        $reporte_pago->direccion = $edit->dir;
        $reporte_pago->cut = date('Y-m-d', strtotime($request->cut));
        $reporte_pago->mb = $edit->plan;
        $reporte_pago->monto_d = $request->d;
        $reporte_pago->monto_bs = $request->bs;
        $reporte_pago->monto_pm = $request->bs_pm;
        $reporte_pago->monto_z_1 = $request->za;
        $reporte_pago->monto_z_2 = $request->zb;
        $reporte_pago->pm_ref = $request->pm_ref;
        $reporte_pago->tasa = $tasa->tasa;
        $reporte_pago->cedula = $pagos->id_user;
        $reporte_pago->tlf = $edit->tlf;
        $reporte_pago->total_d = round(($reporte_pago->monto_d + $reporte_pago->monto_z_1 + $reporte_pago->monto_z_2) + ($reporte_pago->monto_bs / $tasa->tasa), 2);
        $api_pay = $reporte_pago->total_d;

        if ($same != true) {
            $reporte_pago->save();
        }

        $fecha_actual = Carbon::now();

        $edit->comentario_instalador = $request->comentario_instalador;
        $edit->observation = $request->observation;
        $edit->advan = $reporte_pago->total_d - $edit->total;
        $edit->aux = "";
        $edit->debtor = "";
        $edit->multi = 0;

        $edit->total_debt_m -= $reporte_pago->total_d;

        if ($same != true) {
            $edit->save();
        }
        $edit->save();

        if ($edit->total_debt_m <= 0) {
            $pagos->paid = "Pagado";
            $pagos->repa = "1";
            $pagos->dir = $request->dir;
            $pagos->type = $request->pago;
            $pagos->dir = $edit->dir;
            $pagos->date = date("Y-m-d", strtotime($request->day));
            $pagos->updated_at = date("Y-m-d", strtotime($request->day));
            $pagos->comodin = date("Y-m-d", strtotime($request->day));
            $pagos->usuario = auth()->user()->email;

            $edit->day = date("Y-m-d", strtotime($request->day));

            $pagos->monto_dollar = $request->d;
            $pagos->monto_bs = $request->bs;
            $pagos->monto_pm = $request->bs_pm;
            $pagos->monto_zelle_1 = $request->za;
            $pagos->monto_zelle_2 = $request->zb;

            $plan_value = $request->total;

            $pay = ($request->d  + $request->za + $request->zb) + ($request->bs / $tasa->tasa) + ($request->bs_pm / $tasa->tasa) + ($request->total_ab);

            $rest = (($request->d  + $request->za + $request->zb) + ($request->bs / $tasa->tasa)) - $request->plan_ab + ($request->total_ab);

            if ($pay > $request->plan_ab) {
                ($edit->total_debt_m = $edit->total - $rest);
            } else {
                $edit->total_debt_m = $edit->total;
            }

            if ($same != true) {
                $pagos->save();
                $edit->save();
            }

            $edit->debtor = date("Y-m-d", strtotime(date('Y-m-d')));
        }

        $edit->cut = $request->cut;

        if ($same != true) {
            $edit->save();
        }

        $evento_diario = new evento_diario();
        if ($request->d > 0 && $request->bs == NULL || $request->bs == 0) { //solo dolares
            $evento_diario->evento = "El cliente: " . $edit->full_name . " cancelo " . $request->d . "$ de su mensualidad";
        } elseif ($request->bs > 0 && $request->d == NULL || $request->d == 0) { // solo bolivares
            $evento_diario->evento = "El cliente: " . $edit->full_name . " cancelo " . $request->bs . "Bs de su mensualidad";
        } else {
            $evento_diario->evento = "El cliente: " . $edit->full_name . " cancelo " . $request->d . "$ y " . $request->bs . "Bs de su mensualidad";
        }

        $evento_diario->fecha = date("Y-m-d", strtotime($request->day)); //arreglar esto
        $evento_diario->date_print = date("Y-m-d", strtotime($request->day));
        $evento_diario->total_d = $request->d;
        $evento_diario->total_bs = $request->bs;

        if ($request->reg == 'kennerth salazar' || $request->reg == 'rossana' || $request->reg == 'marco antonio') {
            if ($same != true) {
                $evento_diario->save();
            }
        } else {
            echo "Bazinga";
        }

        if ($same != true) {
            return back()->with('form', 'ok');
        } else {
            return back()->with('ref', 'ok');
        }
    }

    public function update_meses(Request $request){
        $cliente = Cliente::findOrFail($request->usuario);
        
        $cliente->cut = date("Y-m-d", strtotime($cliente->cut . " + ".$request->add_meses." month"));
        $cliente->save();

        return back()->with('pagado', 'ok');
    }

    public function pay_customer(Request $request)
    {
        date_default_timezone_set("America/Caracas");
        $mensaje = "";
        $tasa = sort::select('tasa')->Where('id', 'like', "1")->get();
        $orden = sort_report::select('*')->findOrFail("1");
        $planes = Planes::select('*')->get();

        $table = 0;
        $pagos = "";
        $var_report_0 = "";
        $var_report_1 = "";
        $var_report_2 = "";

        if ($request->report == "0") { //todos
            $var_report_0 = "id";
            $var_report_1 = ">";
            $var_report_2 = "0";
            $orden->sort_report = $request->report;
        } elseif ($request->report == "1") { //mes
            $var_report_0 = "concepto";
            $var_report_1 = "=";
            $var_report_2 = NULL;
            $orden->sort_report = $request->report;
        } elseif ($request->report == "2") { //servicio
            $var_report_0 = "concepto";
            $var_report_1 = "!=";
            $var_report_2 = NULL;
            $orden->sort_report = $request->report;
        } else {
            $var_report_0 = "id";
            $var_report_1 = ">";
            $var_report_2 = "0";
        }

        $orden->save();

        $sum_d = 0;
        $sum_b = 0;
        $sum_pm = 0;
        $sum_z = 0;
        $sum_z_2 = 0;
        $inicio = "";
        $fin = "";

        if ($request->inicio > $request->fin) {
            $mensaje = "Ingrese un rango valido";
            $rango = "";
            $cuenta = "";
        } else {
            $pagos = Reporte_pagos::whereBetween('fecha_pago', [$request->inicio, $request->fin])->where($var_report_0, $var_report_1, $var_report_2)->orderBy('created_at', 'desc')->get();
            $pagos_aux = Reporte_pagos::whereBetween('fecha_pago', [$request->inicio, $request->fin])->where($var_report_0, $var_report_1, $var_report_2)->where('active', '=', '0')->orderBy('created_at', 'desc')->get();
            $cuenta = $pagos->count();
            $cuenta_aux = $pagos_aux->count();

            foreach ($pagos as $pago) {

                if ($pago->active == 1) {
                    $sum_d += $pago->monto_d;
                    $sum_b += $pago->monto_bs;
                    $sum_pm += $pago->monto_pm;
                    $sum_z += $pago->monto_z_1;
                    $sum_z_2 += $pago->monto_z_2;
                }
            }
            $inicio = $request->inicio;
            $fin = $request->fin;
        }

        $total_do = Reporte_pagos::whereBetween('fecha_pago', [Carbon::now(), Carbon::now()])->get();

        if ($request->report == "0") { //todo report = 0
            $total_do = Reporte_pagos::whereBetween('fecha_pago', [$request->inicio, $request->fin])->orderBy('updated_at', 'desc')->get();
        } elseif ($request->report == "1") { //mensualidades report = 0 report = 1
            $total_do = Reporte_pagos::whereBetween('fecha_pago', [$request->inicio, $request->fin])->where('cut', '!=', NULL)->orderBy('updated_at', 'desc')->get();
        } elseif ($request->report == "2") { //servicios report = 2
            $total_do = Reporte_pagos::whereBetween('fecha_pago', [$request->inicio, $request->fin])->where('cut', '=', NULL)->orderBy('updated_at', 'desc')->get();
        }

        $sum_t = 0;

        foreach ($total_do as $total) {
            $sum_t += $total->total_d;
        }

        foreach ($orden as $orde)

            //Para sacar la sumatoria y division del cambio exacto
            $change = Reporte_pagos::whereBetween('fecha_pago', [$request->inicio, $request->fin])->get();

        $cambio = 0;
        foreach ($change as $changes) {
            if ($changes->active == 1) {
                $cambio += $changes->monto_bs / $changes->tasa;
            }
        }

        //Pagos por cobrador (Para resumen)
        $pagos_marco_escala = Reporte_pagos::whereBetween('fecha_pago', [$request->inicio, $request->fin])->where('reg', '=', 'marco escala')->where($var_report_0, $var_report_1, $var_report_2)->orderBy('created_at', 'desc')->get();
        $pagos_marco_antonio = Reporte_pagos::whereBetween('fecha_pago', [$request->inicio, $request->fin])->where('reg', '=', 'marco antonio')->where($var_report_0, $var_report_1, $var_report_2)->orderBy('created_at', 'desc')->get();
        $pagos_yurbis_laya = Reporte_pagos::whereBetween('fecha_pago', [$request->inicio, $request->fin])->where('reg', '=', 'yurbi laya')->where($var_report_0, $var_report_1, $var_report_2)->orderBy('created_at', 'desc')->get();
        $pagos_kennerth_salazar = Reporte_pagos::whereBetween('fecha_pago', [$request->inicio, $request->fin])->where('reg', '=', 'kennerth salazar')->where($var_report_0, $var_report_1, $var_report_2)->orderBy('created_at', 'desc')->get();
        $pagos_maria_primera = Reporte_pagos::whereBetween('fecha_pago', [$request->inicio, $request->fin])->where('reg', '=', 'maria primera')->where($var_report_0, $var_report_1, $var_report_2)->orderBy('created_at', 'desc')->get();
        $pagos_jean_carlos = Reporte_pagos::whereBetween('fecha_pago', [$request->inicio, $request->fin])->where('reg', '=', 'jean carlos')->where($var_report_0, $var_report_1, $var_report_2)->orderBy('created_at', 'desc')->get();
        $pagos_eduardo_figueroa = Reporte_pagos::whereBetween('fecha_pago', [$request->inicio, $request->fin])->where('reg', '=', 'eduardo figueroa')->where($var_report_0, $var_report_1, $var_report_2)->orderBy('created_at', 'desc')->get();
        $pagos_eduardo_granadillo = Reporte_pagos::whereBetween('fecha_pago', [$request->inicio, $request->fin])->where('reg', '=', 'eduardo granadillo')->where($var_report_0, $var_report_1, $var_report_2)->orderBy('created_at', 'desc')->get();
        $pagos_otros = Reporte_pagos::whereBetween('fecha_pago', [$request->inicio, $request->fin])->where('reg', '!=', 'eduardo granadillo')->where('reg', '!=', 'eduardo figueroa')->where('reg', '!=', 'jean carlos')->where('reg', '!=', 'maria primera')->where('reg', '!=', 'kennerth salazar')->where('reg', '!=', 'yurbis laya')->where('reg', '!=', 'yurbi laya')->where('reg', '!=', 'marco antonio')->where('reg', '!=', 'marco escala')->where('reg', '!=', 'eduardo granadillo')->where($var_report_0, $var_report_1, $var_report_2)->orderBy('created_at', 'desc')->get();
        $pagos_bs = Reporte_pagos::whereBetween('fecha_pago', [$request->inicio, $request->fin])->orderBy('created_at', 'desc')->get();

        //inicio de la seccion para mostrar la lista rapida de cobros
        //Primer usuario: Marco Escala
        //1 para dolares, 2 para bolivares y 3 para ambos zelles
        $total_marco = [0, 0, 0];
        $total_marco_antonio = [0, 0, 0];
        $total_yurbis_laya = [0, 0, 0];
        $total_kennerth_salazar = [0, 0, 0];
        $total_maria_primera = [0, 0, 0];
        $total_jean_carlos = [0, 0, 0];
        $total_eduardo_figueroa = [0, 0, 0];
        $total_eduardo_granadillo = [0, 0, 0];

        $cantidad_marco = $pagos_marco_escala->count();
        $cantidad_marco_antonio = $pagos_marco_antonio->count();
        $cantidad_yurbis_laya = $pagos_yurbis_laya->count();
        $cantidad_kennerth_salazar = $pagos_kennerth_salazar->count();
        $cantidad_maria_primera = $pagos_maria_primera->count();
        $cantidad_jean_carlos = $pagos_jean_carlos->count();
        $cantidad_eduardo_figueroa = $pagos_eduardo_figueroa->count();
        $cantidad_eduardo_granadillo = $pagos_eduardo_granadillo->count();

        foreach ($pagos_marco_escala as $marco) {
            $total_marco[0] += $marco->monto_d;
            $total_marco[1] += $marco->monto_bs;
            $total_marco[2] += $marco->monto_z_1 + $marco->monto_z_2;
        }

        foreach ($pagos_marco_antonio as $marco_antonio) {
            $total_marco_antonio[0] += $marco_antonio->monto_d;
            $total_marco_antonio[1] += $marco_antonio->monto_bs;
            $total_marco_antonio[2] += $marco_antonio->monto_z_1 + $marco_antonio->monto_z_2;
        }

        foreach ($pagos_yurbis_laya as $yurbis_laya) {
            $total_yurbis_laya[0] += $yurbis_laya->monto_d;
            $total_yurbis_laya[1] += $yurbis_laya->monto_bs;
            $total_yurbis_laya[2] += $yurbis_laya->monto_z_1 + $yurbis_laya->monto_z_2;
        }

        foreach ($pagos_kennerth_salazar as $kennerth_salazar) {
            $total_kennerth_salazar[0] += $kennerth_salazar->monto_d;
            $total_kennerth_salazar[1] += $kennerth_salazar->monto_bs;
            $total_kennerth_salazar[2] += $kennerth_salazar->monto_z_1 + $kennerth_salazar->monto_z_2;
        }

        foreach ($pagos_maria_primera as $maria_primera) {
            $total_maria_primera[0] += $maria_primera->monto_d;
            $total_maria_primera[1] += $maria_primera->monto_bs;
            $total_maria_primera[2] += $maria_primera->monto_z_1 + $maria_primera->monto_z_2;
        }

        foreach ($pagos_jean_carlos as $jean_carlos) {
            $total_jean_carlos[0] += $jean_carlos->monto_d;
            $total_jean_carlos[1] += $jean_carlos->monto_bs;
            $total_jean_carlos[2] += $jean_carlos->monto_z_1 + $jean_carlos->monto_z_2;
        }

        foreach ($pagos_eduardo_figueroa as $eduardo_figueroa) {
            $total_eduardo_figueroa[0] += $eduardo_figueroa->monto_d;
            $total_eduardo_figueroa[1] += $eduardo_figueroa->monto_bs;
            $total_eduardo_figueroa[2] += $eduardo_figueroa->monto_z_1 + $eduardo_figueroa->monto_z_2;
        }

        foreach ($pagos_eduardo_granadillo as $eduardo_granadillo) {
            $total_eduardo_granadillo[0] += $eduardo_granadillo->monto_d;
            $total_eduardo_granadillo[1] += $eduardo_granadillo->monto_bs;
            $total_eduardo_granadillo[2] += $eduardo_granadillo->monto_z_1 + $eduardo_granadillo->monto_z_2;
        }
        //fin de la seccion para mostrar la lista rapida de cobros

        $total_recaudado = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        $report_date = $request->inicio;

        if ($request->get('print') == 'ok') {

            date_default_timezone_set("America/Caracas");
            $hoy_format = Carbon::now()->format('d/m/Y h:i:s A');

            $pdf = new KodePDF('P', 'mm', array(80, 140));

            $pdf->AddPage();
            $pdf->SetTitle('Reporte');
            $pdf->SetAuthor('Ken');
            $pdf->SetCreator('FPDF');
            $pdf->Ln();
            $pdf->renderTitle('Informática  Express C.A');
            $pdf->Ln();
            $pdf->renderText_date('Fecha y hora: ' . $hoy_format);
            $pdf->Ln();

            //Consultas para el total del ticket impreso
            $total_ticket_marco_escala = Reporte_pagos::where('fecha_pago', $request->get('report_date_total'))->where('reg', '=', 'marco escala')->where($var_report_0, $var_report_1, $var_report_2)->orderBy('created_at', 'desc')->get();
            $total_ticket_marco_antonio = Reporte_pagos::where('fecha_pago', $request->get('report_date_total'))->where('reg', '=', 'marco antonio')->where($var_report_0, $var_report_1, $var_report_2)->orderBy('created_at', 'desc')->get();
            $total_ticket_yurbis_laya = Reporte_pagos::where('fecha_pago', $request->get('report_date_total'))->where('reg', '=', 'yurbi laya')->where($var_report_0, $var_report_1, $var_report_2)->orderBy('created_at', 'desc')->get();
            $total_ticket_kennerth_salazar = Reporte_pagos::where('fecha_pago', $request->get('report_date_total'))->where('reg', '=', 'kennerth salazar')->where($var_report_0, $var_report_1, $var_report_2)->orderBy('created_at', 'desc')->get();
            $total_ticket_maria = Reporte_pagos::where('fecha_pago', $request->get('report_date_total'))->where('reg', '=', 'maria primera')->where($var_report_0, $var_report_1, $var_report_2)->orderBy('created_at', 'desc')->get();
            $total_ticket_jean_carlos = Reporte_pagos::where('fecha_pago', $request->get('report_date_total'))->where('reg', '=', 'jean carlos')->where($var_report_0, $var_report_1, $var_report_2)->orderBy('created_at', 'desc')->get();
            $total_ticket_eduardo_figueroa = Reporte_pagos::where('fecha_pago', $request->get('report_date_total'))->where('reg', '=', 'eduardo figueroa')->where($var_report_0, $var_report_1, $var_report_2)->orderBy('created_at', 'desc')->get();
            $total_ticket_eduardo_granadillo = Reporte_pagos::where('fecha_pago', $request->get('report_date_total'))->where('reg', '=', 'eduardo granadillo')->where($var_report_0, $var_report_1, $var_report_2)->orderBy('created_at', 'desc')->get();
            $total_ticket_otros = Reporte_pagos::where('fecha_pago', $request->get('report_date_total'))->where('reg', '!=', 'eduardo granadillo')->where('reg', '!=', 'eduardo figueroa')->where('reg', '!=', 'jean carlos')->where('reg', '!=', 'maria primera')->where('reg', '!=', 'kennerth salazar')->where('reg', '!=', 'yurbis laya')->where('reg', '!=', 'yurbi laya')->where('reg', '!=', 'marco antonio')->where('reg', '!=', 'marco escala')->where('reg', '!=', 'eduardo granadillo')->where($var_report_0, $var_report_1, $var_report_2)->orderBy('created_at', 'desc')->get();
            $total_ticket_bs = Reporte_pagos::where('fecha_pago', $request->get('report_date_total'))->orderBy('created_at', 'desc')->get();
            $total_ticket_dolar = Reporte_pagos::where('fecha_pago', $request->get('report_date_total'))->orderBy('created_at', 'desc')->get();
            //fin de consultas para el total del ticket impreso

            $m_s = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

            //Calculando la sumatoria del total
            foreach ($total_ticket_marco_escala as $marco_escala) {
                $m_s[0] += $marco_escala->monto_d;
            }

            foreach ($total_ticket_marco_antonio as $marco_antonio) {
                $m_s[1] += $marco_antonio->monto_d;
            }

            foreach ($total_ticket_yurbis_laya as $yurbis_laya) {
                $m_s[2] += $yurbis_laya->monto_d;
            }

            foreach ($total_ticket_kennerth_salazar as $ticket_kennerth_salazar) {
                $m_s[3] += $ticket_kennerth_salazar->monto_d;
            }

            foreach ($total_ticket_maria as $ticket_maria) {
                $m_s[4] += $ticket_maria->monto_d;
            }

            foreach ($total_ticket_jean_carlos as $ticket_jean_carlos) {
                $m_s[5] += $ticket_jean_carlos->monto_d;
            }

            foreach ($total_ticket_eduardo_figueroa as $eduardo_figueroa) {
                $m_s[6] += $eduardo_figueroa->monto_d;
            }

            foreach ($total_ticket_eduardo_granadillo as $eduardo_granadillo) {
                $m_s[7] += $eduardo_granadillo->monto_d;
            }

            foreach ($total_ticket_otros as $ticket_otros) {
                $m_s[8] += $ticket_otros->monto_d;
            }

            foreach ($total_ticket_bs as $ticket_ticket_bs) {
                $m_s[9] += $ticket_ticket_bs->monto_bs;
            }

            foreach ($total_ticket_dolar as $ticket_dolar) {
                $m_s[10] += $ticket_dolar->monto_d;
            }
            //fin de calculando la sumatoria del total

            //Area de recaudos por cobrador
            $pdf->renderText_ln('-----------------------------------------------------');
            $pdf->renderText_dir('Recaudos de Marco Escala: ' . $m_s[0] . '$');
            $pdf->renderText_ln('-----------------------------------------------------');
            $pdf->renderText_dir('Recaudos de Marco Antonio: ' . $m_s[1] . '$');
            $pdf->renderText_ln('-----------------------------------------------------');
            $pdf->renderText_dir('Recaudos de Yurbis Laya: ' . $m_s[2] . '$');
            $pdf->renderText_ln('-----------------------------------------------------');
            $pdf->renderText_dir('Recaudos de Kennerth Salazar: ' . $m_s[3] . '$');
            $pdf->renderText_ln('-----------------------------------------------------');
            $pdf->renderText_dir('Recaudos de Maria Primera: ' . $m_s[4] . '$');
            $pdf->renderText_ln('-----------------------------------------------------');
            $pdf->renderText_dir('Recaudos de Jean Carlos: ' . $m_s[5] . '$');
            $pdf->renderText_ln('-----------------------------------------------------');
            $pdf->renderText_dir('Recaudos de Eduardo Figueroa: ' . $m_s[6] . '$');
            $pdf->renderText_ln('-----------------------------------------------------');
            $pdf->renderText_dir('Recaudos de Eduardo Granadillo: ' . $m_s[7] . '$');
            $pdf->renderText_ln('-----------------------------------------------------');
            $pdf->renderText_dir('Recaudos de otros cobradores: ' . $m_s[8] . '$');
            $pdf->renderText_ln('-----------------------------------------------------');
            $pdf->renderText_dir('Recaudos de totales en Bs.: ' . $m_s[9] . 'Bs.');
            $pdf->renderText_ln('-----------------------------------------------------');
            $pdf->renderText_dir('Recaudos de totales en dolares.: ' . $m_s[10] . '$.');
            $pdf->renderText_ln('-----------------------------------------------------');
            //fin de area de recaudos por cobrador

            $pdf->Output('D', 'resumen.pdf');
        }

        return view('/reporte_pagos', compact('pagos', 'cantidad_marco', 'cantidad_marco_antonio', 'cantidad_yurbis_laya', 'cantidad_kennerth_salazar', 'cantidad_maria_primera', 'cantidad_eduardo_figueroa', 'cantidad_eduardo_granadillo', 'cantidad_jean_carlos', 'report_date', 'pagos_bs', 'total_recaudado', 'pagos_otros', 'pagos_marco_escala', 'pagos_marco_antonio', 'pagos_yurbis_laya', 'pagos_kennerth_salazar', 'pagos_maria_primera', 'pagos_jean_carlos', 'pagos_eduardo_figueroa', 'pagos_eduardo_granadillo', 'planes', "inicio", "cambio", "fin", 'table', 'tasa', 'orden', 'mensaje', 'cuenta', 'sum_d', 'sum_b', 'sum_pm','sum_z', 'sum_z_2', 'sum_t', 'total_marco', 'total_marco_antonio', 'total_yurbis_laya', 'total_kennerth_salazar', 'total_maria_primera', 'total_jean_carlos', 'total_eduardo_figueroa', 'total_eduardo_granadillo', 'cuenta_aux'))->with('eliminar', 'ok')->with('cambiar', 'ok')->with('pago', 'ok');
    }

    public function pay_range(Request $request)
    {
        $mensaje = "";
        $sum = 0;

        if ($request->inicio > $request->fin) {
            $mensaje = "Ingrese un rango valido";
            $rango = "";
            $cuenta = "";
            $imprimir = "";
        } else {
            $rango = Cliente::whereBetween('cut', [$request->inicio, $request->fin])->paginate(1000);

            foreach ($rango as $rangos) {

                $sum += $rangos->total;
            }

            $cuenta = $rango->count();
        }

        return view('rango_pagos', compact('rango', 'cuenta', 'mensaje', 'sum'));
    }

    public function panel(Request $request)
    {
        $record_planes = Planes::select('*')->cursorPaginate(1000);
        $api = sort::select('*')->findOrFail("1");
        return view('panel_administrador', compact('record_planes', 'api'))->with('backup', 'ok')->with('restore-backup', 'ok')->with('formulario-crear-plan', '1')->with('formulario-eliminar-plan', '0');
    }

    public function plan(Request $request)
    {
        $planes = new Planes();
        $fecha_actual = Carbon::now();

        $planes->nombre_de_plan = $request->nombre_de_plan;
        $planes->plan = $request->plan;
        $planes->valor = $request->valor;
        $planes->dedicado = $request->dedicado;
        $planes->type = $request->tipo;
        $planes->descripcion = $request->descripcion;

        $planes->save();

        $reporte = new Reporte();

        $usuario = auth()->user()->email;
        $reporte->descripcion = "$usuario: Se ha REGISTRADO el plan: " . $request->nombre_de_plan . " descripcion: " . $request->descripcion;
        $reporte->type = 1;

        $reporte->save();

        return redirect()->route('admin.panel');
    }

    public function plan_edit($id)
    {
        $planes = Planes::findOrFail($id);

        return view('/editar_planes', compact('planes'));
    }

    public function plan_update(Request $request, $id)
    {
        $cuenta = Cliente::count();

        $planes = Planes::where('nombre_de_plan', '=', "$request->plan")->firstOrFail();

        $planes->nombre_de_plan = $request->nombre_de_plan;
        $planes->plan = $request->plan;
        $planes->valor = $request->valor;
        $planes->dedicado = $request->dedicado;
        $planes->descripcion = $request->descripcion;
        $planes->save();

        for ($i = 1; $i <= $cuenta; $i++) {
            $clientes = Cliente::findOrFail($i);
            if ($clientes->plan_code == $planes->id) {
                $clientes->plan = $planes->nombre_de_plan;
                $clientes->total = $planes->valor;
                $clientes->plan_code = $planes->id;
                $clientes->save();
            }
        }

        return redirect()->route('admin.panel')->with('update', 'ok');
    }

    public function plan_delete($id)
    {
        $planes = Planes::findOrFail($id);

        $planes->delete();

        $cuenta = Cliente::count();

        for ($i = 1; $i <= $cuenta; $i++) {
            $cliente = Cliente::findOrFail($i);
            if ($cliente->plan_code == $planes->id) {
                $cliente->plan = "3MB";
                $cliente->total = 30;
                $cliente->plan_code = 3;
                $cliente->save();
            }
        }

        return redirect()->route('admin.panel');
    }

    public function multi(Request $request)
    {

        $nombre = $request->get('find');

        $clientes = Cliente::where('full_name', 'like', "%$nombre%")
            ->whereBetween('status', [2, 7])
            ->orderBy('cut', 'asc')
            ->Paginate(600);

        $planes = Planes::select('*')->cursorPaginate(600);
        $count = $clientes->count();

        return view('multi', compact('planes', 'clientes', 'count'))->with('pagado', 'ok');
    }

    public function pay_delete(Request $request, $id)
    {
        date_default_timezone_set("America/Caracas");

        $pagos = Pagos::where('id_user', 'like', "%$id%")->where('mes', 'like', "%$request->mes%")->firstOrFail();
        $reporte_pagos = Reporte_pagos::where('codigo_pago', 'like', $pagos->id)->firstOrFail();
        $clientes = Cliente::where('id_user', 'like', "%$id%")->firstOrFail();

        $reporte = new Reporte();

        $usuario = auth()->user()->email;
        $fecha = Carbon::now()->format('Y-m-d');

        $reporte->descripcion = "$usuario: Pago eliminado de: " . $clientes->full_name . ", Cedula: " . $clientes->id_user;
        $reporte->type = 3;
        $reporte->save();

        $clientes->cut = date("Y-m-d", strtotime($clientes->cut . " - 1 month"));
        $clientes->save();

        $pagos->paid = "Sin pagar";
        $pagos->monto_dollar = 0;
        $pagos->monto_bs = 0;
        $pagos->monto_zelle_1 = 0;
        $pagos->monto_zelle_2 = 0;
        $pagos->comodin = NULL;
        $pagos->date = NULL;

        $pagos->save();

        $reporte_pagos->delete();

        return back()->with('eliminar', 'ok');
    }

    public function backup()
    {
        date_default_timezone_set("America/Caracas");

        $stringT = $date = date("d_m_Y_h-i-s_a");

        shell_exec('cd C:\xampp\mysql\bin && mysqldump -u root clientes > C:\Users\Admin\Desktop\Respaldos_BD\PTO_WIFI_BACKUP-' . $stringT . '.sql"');

        return back()->with('backup', 'ok');
    }

    public function restore_backup(Request $request)
    {
        $bd_name = $request->bd;

        $command = "cd C:/xampp/mysql/bin && mysql -u root clientes < C:/Users/Admin/Desktop/Respaldos_BD/$bd_name";

        shell_exec($command);

        return back()->with('restore-backup', 'ok');
    }

    public function api_activate_desactivate(Request $request)
    {

        $sort = sort::select('*')->findOrFail("1");

        $sort->api = $request->api;

        $sort->save();

        return back();
    }

    public function profile($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('perfil', compact("cliente"));
    }

    public function tasa(Request $request)
    {
        $tasa = sort::select('*')->findOrFail("1");

        $tasa->tasa = $request->tasa;

        $tasa->save();

        return back();
    }

    public function report_delete($id)
    {
        $reporte_pagos = Reporte_pagos::findOrFail($id);
        $reporte_pagos->active = 0;
        $reporte_pagos->reg_del = auth()->user()->email;
        $reporte_pagos->save();

        $reporte = new Reporte();
        $reporte->descripcion = auth()->user()->email . ": desactivo el pago de " . $reporte_pagos->nombre . ", que fue registrado para el dia: " . $reporte_pagos->fecha_pago;
        $reporte->type = 4;
        $reporte->save();

        return back()->with('delete', 'ok');
    }

    public function report_delete_2($id)
    {
        $reporte_pagos = Reporte_pagos::findOrFail($id);
        $reporte_pagos->active = 0;
        $reporte_pagos->reg_del = auth()->user()->email;
        $reporte_pagos->save();

        $reporte = new Reporte();
        $reporte->descripcion = auth()->user()->email . ": desactivo el pago de " . $reporte_pagos->nombre . ", que fue registrado para el dia: " . $reporte_pagos->fecha_pago;
        $reporte->type = 4;
        $reporte->save();

        return back()->with('delete', 'ok');
    }

    public function pay_services(Request $request)
    {
        $reporte_pago = new Reporte_pagos();

        $reporte_pago->cobrado_por = auth()->user()->email;
        $reporte_pago->fecha_pago = date("Y-m-d", strtotime($request->day));
        $reporte_pago->comodin = date("Y-m-d", strtotime($request->day));
        $reporte_pago->nombre = $request->name;
        $reporte_pago->direccion = $request->dir;
        $reporte_pago->concepto = $request->conc;

        $reporte_pago->op = $request->op;
        $reporte_pago->cedula = $request->at_1; //cedula
        $reporte_pago->tlf = $request->at_2; //telefono

        $reporte_pago->reg = $request->reg;
        $reporte_pago->monto_d = $request->d;
        $reporte_pago->monto_bs = $request->bs;
        $reporte_pago->monto_z_1 = $request->za;
        $reporte_pago->monto_z_2 = $request->zb;
        $reporte_pago->pm_ref = $request->pm_ref_ser;
        $reporte_pago->tasa = $request->tasa;
        $reporte_pago->total_d = ($request->d + $request->za + $request->zb) + ($request->bs / $request->tasa);

        $reporte_pago->save();

        if ($request->pm_ref_ser == NULL) {
            $evento_diario = new evento_diario();
            if ($request->d > 0 && $request->bs == NULL || $request->bs == 0) { //solo dolares
                $evento_diario->evento = "Instalacion: " . $request->name . " | " . $request->conc;
            } elseif ($request->bs > 0 && $request->d == NULL || $request->d == 0) { // solo bolivares
                $evento_diario->evento = "Instalacion: " . $request->name . " | " . $request->conc;
            } else {
                $evento_diario->evento = "Instalacion: " . $request->name . " | " . $request->conc;
            }

            $evento_diario->fecha = date("Y-m-d", strtotime($request->day)); //arreglar esto
            $evento_diario->date_print = date("Y-m-d", strtotime($request->day));
            $evento_diario->total_d = $request->d;
            $evento_diario->total_bs = $request->bs;

            if ($request->reg == 'kennerth salazar' || $request->reg == 'rossana' || $request->reg == 'marco antonio') {
                $evento_diario->save();
            } else {
                echo "Bazinga";
            }

        } else {
            echo "No";
        }

        if ($request->op != 'ot') {

            $pre_reg = new pre_reg();

            $pre_reg->full_name = $request->name;
            $pre_reg->dir = $request->dir;
            $pre_reg->type = $request->op;

            $plan_request = Planes::findOrFail($request->plan);
            $pre_reg->plan_name = $plan_request->nombre_de_plan;
            $pre_reg->plan = $request->plan;
            $pre_reg->id_user = $request->at_1;
            $pre_reg->tlf = $request->at_2;
            $pre_reg->active = $request->insta_c;
            $pre_reg->date_pay = date("Y-m-d", strtotime($request->day));
            $pre_reg->observation = $request->conc;
            $pre_reg->server = $request->servidor;
            $pre_reg->monto = ($request->d + $request->za + $request->zb) + ($request->bs / $request->tasa);

            $pre_reg->save();
        }

        return back()->with('pagado', 'ok');
    }

    public function pay_debt(Request $request, $id)
    {
        date_default_timezone_set("America/Caracas");

        $edit = Cliente::findOrFail($id);
        $pagos = Pagos::where('id_user', 'like', "$edit->id_user")
            ->where('full_name', 'like', "$edit->full_name")
            ->firstOrFail();

        $tasa = sort::select('*')->findOrFail("1");
        $reporte_pago = new Reporte_pagos();

        $reporte_pago->cobrado_por = auth()->user()->email;
        $reporte_pago->fecha_pago = date("Y-m-d", strtotime($request->day));
        $reporte_pago->comodin = date("Y-m-d", strtotime($request->day));
        $reporte_pago->nombre = $request->name;
        $reporte_pago->direccion = $request->dir;
        $reporte_pago->concepto = $request->conc;
        $reporte_pago->monto_d = $request->d;
        $reporte_pago->monto_bs = $request->bs;
        $reporte_pago->monto_z_1 = $request->za;
        $reporte_pago->monto_z_2 = $request->zb;
        $reporte_pago->pm_ref = $request->pm_ref_dep;
        $reporte_pago->tasa = $request->tasa;
        $reporte_pago->total_d = $request->d + $request->za + $request->zb + ($request->bs / $tasa->tasa);
        $reporte_pago->save();

        $edit->total_debt = $edit->total_debt - $reporte_pago->total_d;

        if ($request->pm_ref_ser == NULL) { //si no es un pagomovil

            $evento_diario = new evento_diario();

            if ($request->d > 0 && $request->bs == NULL || $request->bs == 0) { //solo dolares
                $evento_diario->evento = "El cliente: " . $request->name . " cancelo " . $request->d . "$ de la deuda de su instalacion";
            } elseif ($request->bs > 0 && $request->d == NULL || $request->d == 0) { // solo bolivares
                $evento_diario->evento = "El cliente: " . $request->name . " cancelo " . $request->bs . "Bs de la deuda de su instalacion";
            } else {
                $evento_diario->evento = "El cliente: " . $request->name . " cancelo " . $request->d . "$ y " . $request->bs . "Bs de la deuda de su instalacion";
            }

            $evento_diario->fecha = date("Y-m-d", strtotime($request->day)); //arreglar esto
            $evento_diario->date_print = date("Y-m-d", strtotime($request->day));
            $evento_diario->total_d = $request->d;
            $evento_diario->total_bs = $request->bs;

            if ($request->reg == 'kennerth salazar' || $request->reg == 'rossana' || $request->reg == 'marco antonio') {
                $evento_diario->save();
            } else {
                echo "Bazinga";
            }
        } elseif ($request->d > 0 && $request->bs > 0 && $request->pm_ref_ser != NULL) { // si se paga en dolares y pagomovil
            $evento_diario = new evento_diario();
            $evento_diario->evento = "El cliente: " . $request->name . " cancelo " . $request->d . "$ de la deuda de su instalacion";
            $evento_diario->fecha = date("Y-m-d", strtotime($request->day)); //arreglar esto
            $evento_diario->date_print = date("Y-m-d", strtotime($request->day));
            $evento_diario->total_d = $request->d;
            $evento_diario->total_bs = $request->bs;

            if ($request->reg == 'kennerth salazar' || $request->reg == 'rossana' || $request->reg == 'marco antonio') {
                $evento_diario->save();
            } else {
                echo "Bazinga";
            }
        }

        $edit->save();

        return back()->with('pagado', 'ok');
    }

    public function auto_backup()
    {
        date_default_timezone_set("America/Caracas");

        while (true) {

            $stringT = date("d_m_Y_h-i-s_a");

            shell_exec('cd C:\xampp\mysql\bin && mysqldump -u root clientes > C:\Users\Admin\Desktop\Respaldos_BD\PTO_WIFI_BACKUP-' . $stringT . '.sql"');

            sleep(32400);
        }
    }

    public function bills(Request $request)
    {
        $inicio = $request->inicio;
        $fin = $request->fin;

        $var_report_0 = "";
        $var_report_1 = "";
        $var_report_2 = "";

        if ($request->type == "0") { //Todos
            $var_report_0 = "id";
            $var_report_1 = ">";
            $var_report_2 = "0";
        } elseif ($request->type == "1") { //Inversion
            $var_report_0 = "type";
            $var_report_1 = "=";
            $var_report_2 = '0';
        } elseif ($request->type == "2") { //Gasto
            $var_report_0 = "type";
            $var_report_1 = "=";
            $var_report_2 = '1';
        } else {
            $var_report_0 = "id";
            $var_report_1 = ">";
            $var_report_2 = "0";
        }

        $gastos = gastos::whereBetween('comodin', [$request->inicio, $request->fin])->where($var_report_0, $var_report_1, $var_report_2)->orderBy('date', 'asc')->get();
        $tasa = sort::select('*')->Where('id', 'like', "1")->get();

        foreach ($tasa as $cambio);

        $cuenta = $gastos->count();

        $sum_bs = 0;
        $sum_d = 0;

        foreach ($gastos as $bill) {
            $sum_d += $bill->dollar;
            $sum_bs += $bill->bs;
        }

        $n = 0;

        return view('gastos', compact('gastos', 'sum_bs', 'sum_d', 'cuenta', 'cambio', 'n', 'inicio', 'fin'));
    }

    public function pay_bills(Request $request)
    {
        date_default_timezone_set("America/Caracas");

        $tasa = sort::select('*')->Where('id', 'like', "1")->get();

        foreach ($tasa as $cambio);

        $pay_bill = new gastos;

        $pay_bill->concepto = $request->concepto;
        $pay_bill->type = $request->type;
        $pay_bill->dollar = $request->d;
        $pay_bill->bs = $request->bs;
        $pay_bill->comodin = $request->comodin;
        $pay_bill->total = $request->d + ($request->bs / $cambio->tasa);

        $pay_bill->save();

        return back();
    }

    public function pay_bills_delete($id)
    {
        $gastos = gastos::findOrFail($id);

        $gastos->delete();

        return back();
    }

    public function report_update(Request $request)
    {
        $reporte_pago = Reporte_pagos::findOrFail($request->val);

        $reporte_pago->reg = $request->reg;

        $reporte_pago->save();

        return back();
    }

    public function generator_bill($id)
    {
        date_default_timezone_set("America/Caracas");

        $reporte_bill = reporte_pagos::findOrFail($id);

        if ($reporte_bill->codigo_pago != NULL) {
            $cliente = Cliente::select('*')->Where('full_name', 'like', "$reporte_bill->nombre")->firstOrFail();
        }

        $pdf = new KodePDF('P', 'mm', array(80, 140));
        $pdf->AddPage();
        $hoy_format = Carbon::now()->format('d/m/Y h:i:s A');
        $hoy_format_2 = Carbon::now()->format('d_m_Y_h:i:s_A');

        if ($reporte_bill->codigo_pago == NULL) {
            $fac_name = $hoy_format_2 . '_' . $reporte_bill->nombre; //servicio
        } else {
            $fac_name = $hoy_format_2 . '_' . $cliente->full_name; //mensualidad
        }

        // config document
        if ($reporte_bill->codigo_pago == NULL) {
            $pdf->SetTitle('Nota de entrega SERV_' . $reporte_bill->id . ' ' . $reporte_bill->op);
        } else {
            $pdf->SetTitle('Nota de entrega PTWF_' . $reporte_bill->id);
        }

        $pdf->SetAuthor('Ken');
        $pdf->SetCreator('FPDF');
        $pdf->Ln();
        $pdf->renderTitle('Informática  Express C.A');
        $pdf->Ln();

        $pdf->renderText_date('Fecha y hora: ' . $hoy_format);

        $pdf->Ln();

        if ($reporte_bill->codigo_pago == NULL) {
            $pdf->renderText('Nota de entrega SERV_' . $reporte_bill->id . ' ' . $reporte_bill->op);
            $pdf->renderText_dir('R.I.F J-40522311-1 Calle Bermúdez  C/C Carabobo Nivel P.B Local 03 Puerto Cabello Edo. Carabobo Zona postal 2050');
        } else {
            $pdf->renderText('Nota de entrega PTWF_' . $reporte_bill->id . ' ' . $reporte_bill->op);
            $pdf->renderText_dir('R.I.F J-40522311-1 Calle Bermúdez  C/C Carabobo Nivel P.B Local 03 Puerto Cabello Edo. Carabobo Zona postal 2050');
        }

        if ($reporte_bill->op == 'fb') {
            $pdf->renderText_dir('Tecnicos de instalacion, fallas, reclamos o soportes contactar con los siguientes numeros: 0424-4196944 y 0412-5387339, Cobrador ' . $reporte_bill->reg);
        } else {
            $pdf->renderText_dir('Tlf de cobranza: 0412-9679181 04244710322');
        }

        $pdf->renderText_dir('Pagomovil: Tlf: 0412-7520078 C.I:10249850 Provincial (0108)');
        $pdf->renderText_ln('-----------------------------------------------------');

        if ($reporte_bill->codigo_pago == NULL) {
            $pdf->renderText('Cliente: ' . $reporte_bill->nombre);
            $pdf->renderText_dir('Dirección : ' . $reporte_bill->direccion);
            $pdf->renderText('Cedula de identidad: ' . $reporte_bill->cedula);
            $pdf->renderText('Telefono: ' . $reporte_bill->tlf);
        } else {
            $pdf->renderText('Cliente: ' . $cliente->full_name);
            $pdf->renderText_dir('Dirección: ' . $cliente->dir);
            $pdf->renderText('Cedula de identidad: ' . $cliente->id_user);
            $pdf->renderText('Telefono: ' . $cliente->tlf);
        }

        $pdf->renderText_ln('-----------------------------------------------------');

        if ($reporte_bill->codigo_pago == NULL) {
            $pdf->renderText_dir($reporte_bill->concepto);
        } else {
            $pdf->renderText('Pago de mensualidad y soporte:');
            $pdf->renderText_plan('(Plan: ' . $cliente->plan . ' Total: ' .  round($cliente->total * $reporte_bill->tasa, 2) . 'Bs, Tasa actual BCV: ' . $reporte_bill->tasa . 'Bs.)');

            $pdf->renderText_plan('Servicio de internet solvente hasta: ' . date("d-m-Y", strtotime($cliente->cut)));
            $pdf->renderText_dir($cliente->observation);
        }

        $pago_id = Reporte_pagos::select('*')->Where('cedula', '=', $reporte_bill->cedula)->firstOrFail();
        $support = soporte::select('*')->where('id_user', '=', $pago_id->cedula)->get();

        $sort = sort::findOrFail(1);

        $total_support = 0;

        $pdf->renderText_ln('-----------------------------------------------------');

        foreach ($support as $support_item) {
            if ($support_item->active == 1) {
                $pdf->renderText_plan($support_item->nota);
                $total_support += $support_item->total;
            }
        }

        $pdf->renderText_ln('-----------------------------------------------------');

        if ($reporte_bill->codigo_pago != NULL && $cliente->id == 619 || $reporte_bill->codigo_pago != NULL && $cliente->id == 730 || $reporte_bill->codigo_pago != NULL && $cliente->id == 885 || $reporte_bill->codigo_pago != NULL && $cliente->id == 225 || $reporte_bill->codigo_pago != NULL && $cliente->id == 1217 || $reporte_bill->codigo_pago != NULL && $cliente->id == 692 || $reporte_bill->codigo_pago != NULL && $cliente->id == 306 || $reporte_bill->codigo_pago != NULL && $cliente->id == 1127 || $reporte_bill->codigo_pago != NULL && $cliente->id == 900) {
            $pdf->renderTitle_n('TOTAL CANCELADO REF: ' . $reporte_bill->monto_d + $total_support);
        } else {
            $total_support * $sort->tasa;
            $pdf->renderTitle_n('TOTAL CANCELADO: ' . round(($reporte_bill->total_d * $reporte_bill->tasa) + ($total_support), 2) . 'Bs.');
        }

        if ($reporte_bill->op == 'fb') {
            $pdf->renderText_ln('-----------------------------------------------------');
            $pdf->renderText_dir_mt('La instalacion solo cubre 80Mts de cable desde su router hasta la caja de fibra mas cercana, en caso de excedente se cobrara un extra');
        }

        if ($reporte_bill->codigo_pago == NULL) {
            $pdf->Output('D', $fac_name . '_SERV_' . $reporte_bill->id . '.pdf');
        } else {
            $pdf->Output('D', $fac_name . '_PTWF_' . $reporte_bill->codigo_pago . '.pdf');
        }
    }

    public function destacated(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        if ($cliente->destacated == 0) { //0 no destacado
            $cliente->destacated_com = $request->destacated;
            $cliente->destacated = 1;
        } else {
            $cliente->destacated = 0;
            $cliente->destacated_com = "";
        }

        $cliente->save();

        return back();
    }

    public function server_active(Request $request)
    {
        $hoy_format = Carbon::now()->format('Y-m-d');
        $cliente = Cliente::findOrFail($request->id);

        if ($request->get('server_active') == '1') {
            $cliente->server_active = 1;
        } else {
            $cliente->server_active = 0;
        }

        $cliente->last_cut_act = $hoy_format;

        $cliente->save();

        return back();
    }

    public function delete_pre_reg($id)
    {
        $pre_reg = pre_reg::findOrFail($id);

        $pre_reg->delete();

        return back();
    }

    public function add_user_new($id)
    {
        $hoy_format = Carbon::now()->format('Y-m-d');
        date_default_timezone_set("America/Caracas");

        $pre_reg = pre_reg::findOrFail($id);

        $plan = Planes::findOrFail($pre_reg->plan);
        $sort = sort::findOrFail(1);

        $cliente = new Cliente();

        $cliente->full_name = $pre_reg->full_name;
        $cliente->id_user = $pre_reg->id_user;
        $cliente->dir = $pre_reg->dir;
        $cliente->tlf = $pre_reg->tlf;
        $cliente->day = $hoy_format;
        $cliente->cut = date("Y-m-d", strtotime($hoy_format . " +  1 month"));
        //especificar tambien si el dia de corte es 29 agregar el 1 a la columna bi
        $cliente->observation = $pre_reg->observation;
        $cliente->servidor = $pre_reg->server;
        $cliente->type = $pre_reg->type;

        $cliente->plan = $plan->nombre_de_plan;
        $cliente->total = $plan->valor;
        $cliente->plan_code = $plan->id;

        for ($i = 1; $i <= 12; $i++) {
            $pagar = new Pagos();

            $pagar->full_name = $pre_reg->full_name;
            $pagar->id_user = $pre_reg->id_user;
            $pagar->dir = $pre_reg->dir;
            $pagar->type = "0";
            $pagar->monto_bs = 0;
            $pagar->monto_dollar = 0;
            $pagar->monto_zelle_1 = 0;
            $pagar->monto_zelle_2 = 0;
            $pagar->avance = 0;
            $pagar->mes = $i;
            $pagar->codigo_de_pago;
            $pagar->paid = "Sin pagar";
            $pagar->repa = 1;
            $pagar->usuario = NULL;
            $pagar->comodin = NULL;

            $pagar->save();
        }

        $sort->reg += 1;

        $sort->save();
        $cliente->save();
        $pre_reg->delete();

        return back();
    }

    public function edit_user_new(Request $request, $id)
    {
        $pre_reg = pre_reg::findOrFail($id);
        $planes = planes::select('plan')->where('plan_name', '=', $request->plan_name);

        foreach ($planes as $plan_id) {
            $pre_reg->plan = $plan_id->plan;
        }

        $pre_reg->full_name = $request->name;
        $pre_reg->dir = $request->dir;
        $pre_reg->tlf = $request->tlf;
        $pre_reg->id_user = $request->id_user;
        $pre_reg->plan_name = $request->plan_name;
        $pre_reg->type = $request->type;
        $pre_reg->observation = $request->observation;
        $pre_reg->server = $request->server_a;
        $pre_reg->date_pay = $request->insta_date;
        $pre_reg->monto = $request->total;

        $pre_reg->save();

        return back();
    }

    public function pay_dup(Request $request)
    {
        $date_in = date("Y-m-d", strtotime($request->date_in));
        $date_out = date("Y-m-d", strtotime($request->date_out));

        $pagos_dup = DB::table('reporte_pagos')
            ->select('nombre', 'cedula','direccion')
            ->whereBetween('fecha_pago', [$date_in, $date_out])
            ->groupBy('cedula', 'nombre','direccion')
            ->havingRaw('COUNT(cedula) > 1')
            ->get();

        return $pagos_dup;
    }

    public function ref_dup()
    {
        //funciona apartir del dia indicado en la siguiente linea de codigo
        $reg_dup = Reporte_pagos::select('*')->where('pm_ref', '!=', 'NULL')->where('monto_bs', '!=', 'NULL')->where('fecha_pago', ">=", '2023-01-01')->get();
        $ref_i = [];
        $var_rep = [];
        $j = 0;
        $k = 0;

        foreach ($reg_dup as $reg_du_aux) {
            $ref_i[$j] = $reg_du_aux->pm_ref;
            $j++;
        }

        foreach ($reg_dup as $reg_du) {
            $k = 0;
            for ($i = 0; $i <= count($reg_dup) - 1; $i++) {

                if ($reg_du->pm_ref != NULL) {

                    if ($reg_du->pm_ref == $ref_i[$i]) {
                        //echo $i+1 . " Evaluar: $reg_du->pm_ref , numero para iteracion: $ref_i[$i]:Repetido! <br>";
                        $k += 1;
                        if ($k >= 2) {
                            $var_rep[$i] = $reg_du->pm_ref;
                        }
                    } else {
                        $var_rep[$i] = NULL;
                        //echo $i+1 . " Evaluar: $reg_du->pm_ref , numero para iteracion: $ref_i[$i]<br>";
                    }
                }
            }
        }

        //Mostrar las referencias repetidas
        for ($l = 0; $l <= count($var_rep) - 1; $l++) {
            if ($var_rep[$l] != NULL) {
                echo $var_rep[$l] . "<br>";
            }
        }
    }

    public function evento_diario(Request $request)
    {
        date_default_timezone_set("America/Caracas");
        $date = $request->get('date');
        $hoy = date('Y-m-d', strtotime(date('Y-m-d')));
        $date_print = date('Y-m-d', strtotime($request->date_print));

        $date = $request->get('date');

        $evento_diario = evento_diario::select('*')->where('date_print', 'like', "%$date%")->where('type', '=', 1)->get();
        $evento_diario_type = evento_diario::select('*')->where('date_print', 'like', "%$date%")->where('type', '=', 0)->get();
        $evento_diario_total = evento_diario::select('*')->where('total_d', '>', 0)->where('date_print', 'like', "%$date%")->get();

        $total_d = 0;
        $total_bs = 0;
        $total_sin_resto = 0;

        $i = 0;
        $j = 0;
        $k = 1;

        foreach ($evento_diario as $evento) {
            $total_d += $evento->total_d;
            $total_bs += $evento->total_bs;
            $j += 1;
        }

        foreach ($evento_diario_total as $evento_total) {
            $total_sin_resto += $evento_total->total_d;
        }

        return view('evento_diario', compact('evento_diario', 'evento_diario_type', 'total_d', 'total_bs', 'total_sin_resto', 'i', 'j', 'k', 'date', 'hoy'));
    }

    public function evento_diario_delete($id)
    {
        $evento_diario = evento_diario::findOrFail($id);

        $evento_diario->delete();

        return back()->with('delete', 'ok');
    }

    public function add_evento_diario(Request $request)
    {
        date_default_timezone_set("America/Caracas");
        $evento_diario = new evento_diario();

        $evento_diario->evento = $request->evento;
        $evento_diario->fecha = $request->fecha;
        $evento_diario->date_print = $request->date_print;
        $evento_diario->total_d = $request->total_d;
        $evento_diario->total_bs = $request->total_bs;

        $evento_diario->save();

        return back()->with('add', 'ok');
    }

    public function evento_print(Request $request)
    {
        $hoy_format = Carbon::now()->format('d/m/Y h:i:s A');

        $evento_diario_print = evento_diario::select('*')->where('date_print', '=', $request->print)->where('type', '=', 1)->get();
        $evento_diario_print_type = evento_diario::select('*')->where('date_print', '=', $request->print)->where('type', '=', 0)->get();
        $total_d_print = 0;
        $total_bs_print = 0;
        $k = 1;
        $l = 1;

        $pdf = new KodePDF('P', 'mm', array(80, 140));
        $pdf->AddPage();
        $pdf->SetTitle('Reporte');
        $pdf->SetAuthor('Ken');
        $pdf->SetCreator('FPDF');
        $pdf->renderTitle('Informática  Express C.A');
        $pdf->Ln();
        $pdf->renderText_date('Fecha y hora: ' . $hoy_format);
        $pdf->Ln();

        foreach ($evento_diario_print as $diario) {
            $total_d_print += $diario->total_d;
            $total_bs_print += $diario->total_bs;
            $pdf->renderText_ln('-----------------------------------------------------');

            $pdf->renderText_dir("$k: $diario->evento");
            $k += 1;
        }

        $pdf->renderText_ln('-----------------------------------------------------');
        $pdf->renderText_dir("Total Dolares: $total_d_print $");
        $pdf->renderText_dir("Total Bolivares: $total_bs_print bs.");
        
        foreach ($evento_diario_print_type as $diario) {
            $pdf->renderText_ln('-----------------------------------------------------');
            $pdf->renderText_dir("$l: $diario->evento");
            $l += 1;
        }

        $pdf->Output('D', 'evento_diario.pdf');

        return $evento_diario_print;
    }

    public function rep_inf()
    {
        $hoy_format = Carbon::now()->format('d/m/Y h:i:s A');
        $hoy_pet = Carbon::now()->format('Y/m/d');
        $ayer = new Carbon('yesterday');

        //1
        $sum_1 = 0;

        //2
        $sum_bs_2 = 0;
        $sum_d_2 = 0;
        $sum_za_2 = 0;
        $sum_zb_2 = 0;

        //6
        $gasto_d = 0;
        $gasto_bs = 0;

        $evento_d = evento_diario::select('total_d')->where('date_print', '=', $hoy_pet)->where('total_d', '<=', 0)->get();
        $evento_bs = evento_diario::select('total_bs')->where('date_print', '=', $hoy_pet)->where('total_d', '<=', 0)->get();

        $reporte = Reporte_pagos::select('*')->where('pm_ref', '!=', 'NULL')->where('fecha_pago', ">=", '2023-05-18')->get();

        $rep_1 = Reporte_pagos::select('monto_bs')->where('active', '=', 1)->where('fecha_pago', ">=", '2023-05-18')->get();

        $office = Reporte_pagos::select('monto_bs', 'monto_d', 'monto_z_1', 'monto_z_2')->where('active', '=', 1)->where('fecha_pago', "=", $hoy_pet)->get();

        $ayer_pay = Reporte_pagos::select('monto_bs', 'monto_d', 'monto_z_1', 'monto_z_2')->where('active', '=', 1)->where('fecha_pago', "=", $ayer)->get();

        foreach ($rep_1 as $for_1) {
            $sum_1 += $for_1->monto_bs;
        }

        foreach ($office as $for_2) {
            $sum_bs_2 += $for_2->monto_bs;
            $sum_d_2 += $for_2->monto_d;
            $sum_za_2 += $for_2->monto_z_1;
            $sum_zb_2 += $for_2->monto_z_2;
        }

        foreach ($evento_d as $eventos_d) {
            $gasto_d += $eventos_d->total_d;
        }

        foreach ($evento_bs as $eventos_bs) {
            $gasto_bs += $eventos_bs->total_bs;
        }

        $reporte->count();

        $pdf = new KodePDF('P', 'mm', array(80, 140));
        $pdf->AddPage();
        $pdf->SetTitle('Reporte');
        $pdf->SetAuthor('Ken');
        $pdf->SetCreator('FPDF');
        $pdf->renderTitle('Informática  Express C.A');
        $pdf->Ln();
        $pdf->renderText_date('Fecha y hora: ' . $hoy_format);
        $pdf->Ln();

        $pdf->renderText_dir('1: Monto en bolivares del dia de hoy: ' . $sum_1 . "Bs.");
        $pdf->Ln();
        $pdf->renderText_dir('2: Monto recaudado en la oficina: ');
        $pdf->renderText_dir('BOLIVARES: ' . $sum_bs_2 . "Bs.");
        $pdf->renderText_dir('DOLARES: ' . $sum_d_2 . "$");
        $pdf->renderText_dir('ZELLE A: ' . $sum_za_2 . "$");
        $pdf->renderText_dir('ZELLE B: ' . $sum_zb_2 . "$");
        $pdf->Ln();
        $pdf->renderText_dir('3: pagos efectuados el dia de hoy: ' . $reporte->count());
        $pdf->Ln();
        $pdf->renderText_dir('4: pagos efectuados el dia de ayer: ' . $ayer_pay->count());
        $pdf->Ln();
        $pdf->renderText_dir('5: Sumatoria de pagos de ayer y hoy: ' . $reporte->count() + $ayer_pay->count());
        $pdf->Ln();
        $pdf->renderText_dir('6: Gastos del dia de hoy: ');
        $pdf->renderText_dir('BOLIVARES: ' . $gasto_bs * -1 . "Bs.");
        $pdf->renderText_dir('DOLARES: ' . $gasto_d * -1 . "$");

        $pdf->Output('D', 'informe_diario.pdf');
    }
}
