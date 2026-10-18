<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CompanyResource;
use App\Models\Company;

class CompanyController extends Controller
{
    public function index()
    {
        return CompanyResource::collection(
            Company::query()
                ->orderBy('name')
                ->get()
        );
    }

    public function show(Company $company)
    {
        return new CompanyResource($company);
    }

}
