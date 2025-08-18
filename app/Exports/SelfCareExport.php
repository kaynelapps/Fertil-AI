<?php

namespace App\Exports;

use App\Models\SectionDataMain;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SelfCareExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return SectionDataMain::all();
    }

    public function headings(): array
    {
        return [
            'title',
            'goal_type',
            'category_id',
            'is_show_insights',
            'status',
        ];
    }

    public function map($category): array
    {
        return [
            $category->title,
            strval($category->goal_type),  
            strval($category->category_id),  
            strval($category->is_show_insights),  
            strval($category->status),    
        ];
    }
}
