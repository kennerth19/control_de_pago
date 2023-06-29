<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Cliente;
use App\Models\sort;
use App\Models\pre_reg;
use App\Models\soporte;

class test_api extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {//funcion para traer los clientes del dia de hoy
        $cliente = Cliente::Where('status', '=', "6")->get();
        
        return $cliente;
    }

    public function axios_get_date($inicio, $fin)
    {//funcion para traer los clientes deudores entre 2 fechas especificadas

        $cliente = Cliente::whereBetween('cut', [$inicio, $fin])->orderBy('cut', 'asc')->get();

        return $cliente;
    }

    public function axios_get_bcv()
    {//funcion para traer la tasa actual del dolar BCV

        $bcv = sort::Select('tasa')->Where('id', '=', "1")->get();

        return $bcv;
    }

    public function axios_change_bcv($tasa)
    {//funcion para cambiar la tasa BCV
        $bcv = sort::findOrFail('1');

        $bcv->tasa = $tasa;

        $bcv->save();

        return "tasa cambiada con exito! ". $tasa;
    }

    public function get_id_user($id_user){

        $array_datos = [];

        $datos_completos = 'f';

        $datos = Cliente::Select('*')->Where('id_user', '=', "$id_user")->get();

        foreach($datos as $dato){
            if($dato->id_user != '' && strlen($dato->id_user) > 6 && (int)$dato->id_user < 35000000){
                $datos_completos = 'v';
            }
        }

        $array_datos = [$datos,$datos_completos];

        return $array_datos;
    }

    public function get_23_enero(){
        $server_23_enero = Cliente::Select('tlf')->whereNotNull('tlf')->Where('servidor', '!=', "rancho_grande")->Where('active', '=', 1)->get();

        return $server_23_enero;
    }

    public function get_rancho_grande(){
        $server_rancho_grande = Cliente::Select('tlf')->whereNotNull('tlf')->Where('servidor', '=', "rancho_grande")->Where('active', '=', 1)->get();

        return $server_rancho_grande;
    }

    public function get_cerro(){
        $server_cerro = Cliente::Select('tlf')->whereNotNull('tlf')->Where('servidor', '=', "cerro")->Where('active', '=', 1)->get();

        return $server_cerro;
    }

    public function auto_report(){//Dia de corte, Resta 1 dia, Restan 2 dias

        $server_23_enero = Cliente::Select('full_name','tlf','status')->whereNotNull('tlf')->whereBetween('status', [4, 6])->Where('active', '=', 1)->get();
        //$server_23_enero = Cliente::Select('full_name','tlf','status')->Where('servidor', '=', "23_enero")->whereNotNull('tlf')->get();
        
        return $server_23_enero;
    }

    public function send_confirm(){

        $api = sort::select('api')->get();

        return $api[0]['api'];
    }

    public function change_confirm_0(){ //0 desactivado

        $api = sort::findOrFail(1);

        $api->api = 0;
        
        $api->save();
    }

    public function change_confirm_1(){ //1 activado

        $api = sort::findOrFail(1);

        $api->api = 1;
        
        $api->save();
    }

    public function axios_get_sus(){
        $clientes_sus = Cliente::Select('*')->Where('status', '=', 8)->get();    
        
        return $clientes_sus;
    }

    public function axios_get_desc(){
        $clientes_desc = Cliente::Select('*')->Where('active', '=', 0)->get();    
        
        return $clientes_desc;
    }

    public function lista_fibreros(){
        $lista = Pre_reg::Select('*')->Where('type', '=', 'fb')->get();    

        return $lista;
    }

    public function soporte(Request $request){
        $soporte = new soporte();

        $soporte->total = 10;
        $soporte->nota = $request->nota;
        $soporte->id_user = $request->id_user;    
        $soporte->type = $request->type;
        
        $soporte->save();
    }
}