<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Cid;

class SearchController extends Controller {

	public function search(Request $request){
		/*DB::select("select public.dblink_connect('postgres', 'fdw_dbaghu');");
		$cids = Cid::select('dsc_cid', 'cod_cid')
			->where('dsc_cid','LIKE','%'.$term.'%')
	        ->take(10)
	        ->get(['dsc_cid', "dsc_cid"]);

		DB::select("select public.dblink_disconnect('postgres');");
		*/
		DB::select("select public.dblink_connect('postgres', 'fdw_dbaghu');");

		$cids = Cid::take(10)
	        	->get(["cod_cid as id", 'dsc_cid as text']);

		DB::select("select public.dblink_disconnect('postgres');");

		return view('smartsearch')->with('cids', $cids);
	}

	public function autocomplete(Request $request) {
			// Get data input from user
        $term = $request->q;

        if (empty($term)) {
            return \Response::json(["id"=>0,'text' => 'Não existe dados para esta pesquisa']);
        }
		// Connecetion to AGHU postgres user
        DB::select("select public.dblink_connect('postgres', 'fdw_dbaghu');");

		$cids = Cid::search($term)->limit(10)
	        ->get(["cod_cid as id", 'dsc_cid as text'])->toArray();

		DB::select("select public.dblink_disconnect('postgres');");

        return response()->json($data);
	}

	public function dataAjax(Request $request)
    {

    	$cids = [];


        if($request->has('q')){

            $search = $request->q;

            DB::select("select public.dblink_connect('postgres', 'fdw_dbaghu');");

			$cids = Cid::where('dsc_cid','LIKE',"%$search%")
				->take(10)
	        	->get(["cod_cid as id", 'dsc_cid as text']);

			DB::select("select public.dblink_disconnect('postgres');");

        }

        return response()->json($cids);
    }

}