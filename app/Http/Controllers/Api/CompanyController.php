<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Http\Requests\CompanyRequest;

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

    public function store(CompanyRequest $request)
    {
        $company = Company::create($request->validated());

        return new CompanyResource($company);
    }

}
