<?php

namespace App\Imports;

use App\Models\Article;
use App\Models\HealthExpert;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ArticleImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $expertId = $row['expert_id'] ?? null;
        if (empty($expertId)) {
            $expert = HealthExpert::first(); 
            $expertId = $expert ? $expert->id : null;
        }

        $weeks = $row['weeks'] ?? null;
        if (empty($weeks)) {
            $weeks = rand(1, 50); 
        }

        return new Article([
            'name'          => $row['name'] ?? null,
            'expert_id'     => $expertId,
            'description'   => $row['description'] ?? null,
            'goal_type'     => $row['goal_type'] ?? null,
            'article_type'  => $row['article_type'] ?? 0,
            'weeks'         => $weeks,
            'type'          => $row['type'] ?? 'free',
            'status'        => $row['status'] ?? null,
        ]);
    }

}
