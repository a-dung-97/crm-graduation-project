<?php

namespace App\Http\Controllers;

use App\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    //

    public function index(Request $request)
    {
        if ($request->query('menu'))
            return ['data' => Menu::whereNull('parent_id')->with('children')->get()];
        else return ['data' => Menu::with(['roles' => function ($query) {
            $query->select('id')->where('company_id', company()->id);
        }])->get()];
    }
}
