<?php

namespace App\Modules\Enquiry\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EnquiryCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'company_name' => $this->company_name,
            'company_website' => $this->company_website,
            'designation' => $this->designation,
            'product' => $this->product,
            'quantity' => $this->quantity,
            'certification' => $this->certification,
            'address' => $this->address,
            'alternate_name' => $this->alternate_name,
            'alternate_phone' => $this->alternate_phone,
            'alternate_email' => $this->alternate_email,
            'message' => $this->message,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];
    }
}
