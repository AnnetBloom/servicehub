<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Http\Resources\SiteResource;
use App\Http\Requests\StoreSiteRequest;
use App\Http\Requests\UpdateSiteRequest;

class SiteController extends Controller
{
    public function index()
    {
        return SiteResource::collection(
            Site::query()
                ->with('company')
                ->orderBy('name')
                ->paginate(15)
            );
    }

    public function show(Site $site)
    {
        $site->load('company');

        return SiteResource::make($site);
    }

    public function store(StoreSiteRequest $request) 
    {
        $site = Site::create($request->validated());

        $site->load('company');

        return SiteResource::make($site);
    }

    public function update(UpdateSiteRequest $request, Site $site)
    {
        $site->update($request->validated());

        $site->load('company');

        return SiteResource::make($site);
    }

    public function destroy(Site $site)
    {
        $site->delete();

        return response()->noContent();
    }
}
