<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\UpdateContactRequest;

class ContactController extends Controller
{
    public function index()
    {
        return ContactResource::collection(
            Contact::query()
                ->with('company')
                ->orderBy('name')
                ->paginate(1)
        );
    }

    public function show(Contact $contact)
    {
        $contact->load('company');

        return new ContactResource($contact);
    }

    public function store(ContactRequest $request)
    {
        $contact = Contact::create($request->validated());

        $contact->load('company');

        return new ContactResource($contact);
    }

    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $contact->update($request->validated());

        $contact->load('company');

        return new ContactResource($contact);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return response()->noContent();
    }
}
