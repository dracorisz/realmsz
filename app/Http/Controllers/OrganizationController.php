<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\User;

class OrganizationController extends Controller
{
    public function getOrganizations()
	{
		return Organization::get();
	}

    public function getMembers(Request $request)
	{
		return User::where('organization_id', $request->organization_id)->get();
	}
}
