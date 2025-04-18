<?php

namespace App\Imports;

use App\Models\Attendee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AttendeesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Attendee([
            'full_name'     => $row['full_name'],
            'address'       => $row['address'],
            'dob'           => $row['dob'],
            'sex'           => $row['sex'],
            'category'      => $row['category'],
            'phone_number'  => $row['phone_number'],
            'user_id'       => null,
        ]);
    }
}
