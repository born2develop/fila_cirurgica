<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Adldap\AdldapInterface;

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
        $users = $this->ldap->search()->where('memberof', '=', 'CN=HUB - Fila Cirurgica Gestao,OU=Grupos,OU=HUB,DC=Ebserh-HUB,DC=unb,DC=br')->limit(3)->get();
        
        return $users;
    }
    
    /**
     * Displays the specified LDAP user.
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = $this->ldap->search()->findByGuid($id);
        
        return view('users.show', compact('user'));
    }
}
