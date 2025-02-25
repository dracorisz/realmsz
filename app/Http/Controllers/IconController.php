<?php

namespace App\Http\Controllers;

use App\Models\Icon;

class IconController extends Controller
{  
	public function index()
	{
		return Icon::get();
	}
}
