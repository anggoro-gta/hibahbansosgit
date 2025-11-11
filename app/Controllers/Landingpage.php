<?php

namespace App\Controllers;

use App\Models\usersModel;

class Landingpage extends BaseController
{
    protected $usersModel;    

    public function __construct()
    {
        $this->usersModel = new usersModel();
    }

    public function index()
    {
        $data = [
            'tittle' => 'Landing Page',
        ];

        return view('landing/landingpageview', $data);
    }   
    
}
