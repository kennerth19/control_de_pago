<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pre_reg;
use App\Models\sort;
use App\Models\Planes;
use Carbon\Carbon;
use App\Models\Cliente;
use App\Models\Pagos;
use App\Models\Reporte;
use App\Models\soporte;

class principal extends Controller
{
    public function index()// esta es para los fibreros
    {
        $hoy_format = Carbon::now()->format('Y-m-d');
        $pre_reg = pre_reg::select('*')->where('type', '=', 'fb')->orderBy('date_pay', 'asc')->get();
        $support = soporte::where('type', '=', 'fb')->get();

        $count = $pre_reg->count();

        $i = 1;
        $j = 1;

        $i_r = 1;

        return view('lista_de_instalaciones', compact('pre_reg', 'i', 'j','i_r' , 'count','hoy_format','support'));
    }

    public function index_at(){
        $hoy_format = Carbon::now()->format('Y-m-d');
        $pre_reg = pre_reg::select('*')->where('type', '=', 'at')->orderBy('date_pay', 'asc')->get();
        $support = soporte::where('type', '=', 'at')->get();

        $count = $pre_reg->count();

        $i = 1;
        $j = 1;

        $i_r = 1;

        return view('lista_de_instalaciones_at', compact('pre_reg', 'i', 'j','i_r' , 'count','hoy_format','support'));
    }

    public function act_des($id)
    {
        $pre_reg = pre_reg::findOrFail($id);

        if ($pre_reg->active == 0) {
            $pre_reg->active = 1;
        } else {
            $pre_reg->active = 0;
        }

        $pre_reg->save();

        return back();
    }

    public function add_new($id, Request $request)
    {
        //se definen variables para la fecha
        $hoy_format = Carbon::now()->format('Y-m-d');
        date_default_timezone_set("America/Caracas");

        //buscamos los registros necesarios de la BD
        $pre_reg = pre_reg::findOrFail($id);
        $plan = Planes::findOrFail($pre_reg->plan);
        $sort = sort::findOrFail(1);

        //comienzo del nuevo cliente
        $cliente = new Cliente();

        $cliente->full_name = $pre_reg->full_name;
        $cliente->id_user = $pre_reg->id_user;
        $cliente->dir = $pre_reg->dir;
        $cliente->ip = $request->ip_pre_reg;
        $cliente->tlf = $pre_reg->tlf;
        $cliente->day = $request->insta_day;
        $cliente->cut = date("Y-m-d", strtotime($request->insta_day . " +  1 month"));
        //especificar tambien si el dia de corte es 29 agregar el 1 a la columna bi
        $cliente->comentario_instalador = $request->observation;
        $cliente->total_debt = $request->dept;
        $cliente->observation = "";
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

        //fin del nuevo cliente

        //comienzo del log
        $log = new Reporte();

        $usuario = auth()->user()->email;
        $log->descripcion = "$usuario: Se ha REGISTRADO de la lista de PRE-REGISTRO el cliente: " . $pre_reg->full_name . ", Cedula: " . $pre_reg->id_user . " de " . $pre_reg->dir;
        $log->type = 5;
        $log->save();
         //fin del log

        return back();
    }

    public function bolita(Request $request, $id){

        $lista_fibreros = pre_reg::findOrFail($id);

        if($lista_fibreros->active == 1){
            $lista_fibreros->active = 0;
        }elseif($lista_fibreros->active == 2){
            $lista_fibreros->active = 1;
        }else{
            $lista_fibreros->active = 2;
        }

        $lista_fibreros->save();

        return back();
    }
}