<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NDCExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data)->map(function ($item) {
            return [
                'NDC Code' => $item['ndc_code'],
                'Brand Name' => $item['brand_name'] ?? '-',
                'Generic Name' => $item['generic_name'] ?? '-',
                'Labeler Name' => $item['labeler_name'] ?? '-',
                'Product Type' => $item['product_type'] ?? '-',
                'Source' => $item['source'],
            ];
        });
    }

    public function headings(): array
    {
        return [
            'NDC Code',
            'Brand Name',
            'Generic Name',
            'Labeler Name',
            'Product Type',
            'Source'
        ];
    }
}

