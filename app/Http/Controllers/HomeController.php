<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Adldap\AdldapInterface;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    protected $ldap;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AdldapInterface $ldap)
    {
        $this->middleware('auth');
        $this->ldap = $ldap;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wheres = [
            'samaccountname' => Auth::user()->username,
            'memberof' => 'CN=HUB - Fila Cirurgica Gestao,OU=Grupos,OU=HUB,DC=Ebserh-HUB,DC=unb,DC=br'
        ];

        $permission = $this->ldap->search()->where('samaccountname', '=', Auth::user()->username)->limit(1)->get()[0]['attributes']['memberof'];

        in_array('CN=HUB - Fila Cirurgica Gestao,OU=Grupos,OU=HUB,DC=Ebserh-HUB,DC=unb,DC=br', $permission) ? $permission = 'admin' : $permission = null;

        return view('home');
    }
}