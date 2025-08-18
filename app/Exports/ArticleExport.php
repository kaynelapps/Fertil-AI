<?php

namespace App\Exports;

use App\Models\Article;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ArticleExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Article::all();
    }

    public function headings(): array
    {
        return [
            'name',
            'expert_id',
            'description',
            'goal_type',
            'article_type',
            'weeks',
            'type',
            'status',
        ];
    }

    public function map($article): array
    {
        return [
            $article->name,
            strval($article->expert_id),  
            $article->description,
            strval($article->goal_type),  
            strval($article->article_type),  
            strval($article->weeks),  
            $article->type,
            strval($article->status),    
        ];
    }
}
