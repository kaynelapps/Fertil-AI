<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Sections;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class InfoSectionImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        $category = Category::find($row['category_id']);
        if (!$category) {
            return null;
        }

        $goal_type = in_array($row['goal_type'], [0, 1]) ? $row['goal_type'] : 1;
        $status = in_array($row['status'], [0, 1]) ? $row['status'] : 1;

        return new Sections([
            'title'        => $row['title'],
            'goal_type'    => $goal_type ?? 0,
            'category_id'  => $row['category_id'],
            'description'  => $row['description'],
            'status'       => $status ?? 1,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.title'        => 'required|string',
            '*.goal_type'    => ['required', Rule::in([0, 1])],
            '*.category_id'  => 'required|exists:categories,id',
            '*.status'       => ['required', Rule::in([0, 1])],
        ];
    }
}
