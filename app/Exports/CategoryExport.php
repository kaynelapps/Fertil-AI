<?php

namespace App\Exports;

use App\Models\Category;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CategoryExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Category::all();
    }

    public function headings(): array
    {
        return [
            'name',
            'description',
            'goal_type',
            'status',
        ];
    }

    public function map($category): array
    {
        return [
            $category->name,
            $category->description,
            strval($category->goal_type),  
            strval($category->status),    
        ];
    }
}
