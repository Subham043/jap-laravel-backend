<?php

namespace App\Modules\Contact\Exports;

use App\Modules\Contact\Models\Contact;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ContactExport implements FromCollection,WithHeadings,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array{
        return[
            'Id',
            'Name',
            'Email',
            'Phone',
            'Message',
            'Created at',
        ];
    }
    public function map($enquiry): array
    {
         return[
                $enquiry->id,
                $enquiry->name,
                $enquiry->email,
                $enquiry->phone,
                $enquiry->message,
                $enquiry->created_at,
         ];
    }
    public function collection()
    {
        return Contact::all();
    }
}
