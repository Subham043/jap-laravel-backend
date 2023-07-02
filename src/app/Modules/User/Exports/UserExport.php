<?php

namespace App\Modules\User\Exports;

use App\Modules\Authentication\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserExport implements FromCollection,WithHeadings,WithMapping
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
            'Created_at',
        ];
    }
    public function map($user): array
    {
         return[
             $user->id,
             $user->name,
             $user->email,
             $user->phone,
             $user->created_at,
         ];
    }
    public function collection()
    {
        return User::all();
    }
}
