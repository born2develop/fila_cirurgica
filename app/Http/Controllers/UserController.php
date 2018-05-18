<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Adldap\AdldapInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * @var Adldap
     */
    protected $ldap;
    
    /**
     * Constructor.
     *
     * @param AdldapInterface $adldap
     */
    public function __construct(AdldapInterface $ldap)
    {
        $this->ldap = $ldap;
    }
    
    /**
     * Displays the all LDAP users.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        //$users = $this->ldap->search()->users()->limit(3)->get();
        //$users = $this->ldap->search()->where('samaccountname', '=', '04994226199')->limit(3)->get();

        $permissoes = collect(DB::table('formulario.opc_permission')->select('*')->get())
            ->map(function($x){ return (array) $x; })->toArray();


        return view('usuarios_acesso')->with([
            'permissoes' => $permissoes
        ]);
    }

    public function dataAjaxUsers(Request $request)
    {
        $input = $request->all();

        /*$user = $this->ldap->search()
        ->where('samaccountname', '=', $input['usuario'])->limit(1)->get();
        */

        $user = DB::table('formulario.users')
            ->where('username', '=', $input['usuario'])
            ->get(['name', 'username', 'cod_permission', 'id']);


        return response()->json($user);
    }
    
    /**
     * Displays the specified LDAP user.
     *
     * @return \Illuminate\View\View
     */
    public function setPermission(Request $request)
    {
        $permission = $request->all()->permission;

        
    }
}
