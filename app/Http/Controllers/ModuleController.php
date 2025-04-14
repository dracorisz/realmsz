<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ModuleController extends Controller
{
    //
    public function redirectToModule(Request $request, $module)
    {
        $params = $request->all();
        return redirect()->route($module, $params);
    }
}
