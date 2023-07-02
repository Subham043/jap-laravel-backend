<?php

namespace App\Modules\Enquiry\Exports;

use App\Modules\Enquiry\Models\Enquiry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EnquiryExport implements FromCollection,WithHeadings,WithMapping
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
            'Created_at',
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
        return Enquiry::all();
    }
}
