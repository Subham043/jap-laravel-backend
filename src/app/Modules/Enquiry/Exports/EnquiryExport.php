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
            'Company name',
            'Company website',
            'Designation',
            'Product',
            'Quantity',
            'Gst',
            'Certification',
            'Address',
            'Alternate name',
            'Alternate phone',
            'Alternate email',
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
                $enquiry->company_name,
                $enquiry->company_website,
                $enquiry->designation,
                $enquiry->product,
                $enquiry->quantity,
                $enquiry->gst,
                $enquiry->certification,
                $enquiry->address,
                $enquiry->alternate_name,
                $enquiry->alternate_phone,
                $enquiry->alternate_email,
                $enquiry->message,
                $enquiry->created_at,
         ];
    }
    public function collection()
    {
        return Enquiry::all();
    }
}
