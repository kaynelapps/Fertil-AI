<?php

namespace App\Exports;

use App\Models\Sections;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class infoSectionExport implements FromCollection,WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Sections::all();
    }
    public function headings(): array
    {
        return [
            'title',
            'goal_type',
            'category_id',
            'description',
            'status',
        ];
    }
    public function map($section): array
    {
        return [
            $section->title,
            strval($section->goal_type), 
            strval($section->category_id),   
            $section->description,
            strval($section->status),    
        ];
    }
}
