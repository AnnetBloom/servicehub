<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CompanyResource;

class ContactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'company_id' => $this->company_id,
            'company' => CompanyResource::make($this->whenLoaded('company')),
            'name' => $this->name,
            'position' => $this->position,
            'phone' => $this->phone,
            'email' => $this->email,
        ];

        // в контроллере можно указать Contact::with('company')->get(); Тогда company попадёт в ответ API. Если связь не загружена, поле company будет пропущено.
    }
}
