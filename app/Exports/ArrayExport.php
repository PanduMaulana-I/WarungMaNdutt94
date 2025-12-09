<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ArrayExport implements FromArray, WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'order_number',
            'date',
            'customer',
            'status',      // ✅ STATUS DITAMBAHKAN
            'menu',
            'qty',
            'price',
            'subtotal'
        ];
    }
}
