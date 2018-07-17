<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Log;
class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("index");
    }

   public function save_json(Request $r)
   {
        $json=json_encode($r->rows);
        $file = fopen('rows.json', 'w');
        fwrite($file,$json);
        fclose($file);
   }
   public function fetch_json()
   {

        $file=fopen("rows.json","r");
        $size=filesize("rows.json");
        $json=json_decode(fread($file,$size));
        Log::debug($json);
        if (empty($json)) {
           $json=[];
        }
       return response()->json($json);
   }
}
