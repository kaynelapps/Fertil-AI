<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\SectionDataMain;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class SelfCareImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    // Counter for dragondrop value
    protected $dragondropCounter;

    public function __construct()
    {
        $this->dragondropCounter = SectionDataMain::max('dragondrop') + 1;
    }

    public function model(array $row)
    {
        $category = Category::find($row['category_id']);
        if (!$category) {
            return null;
        }

        $goal_type = in_array($row['goal_type'], [0, 1]) ? $row['goal_type'] : 1;
        $is_show_insights = in_array($row['is_show_insights'], [0, 1]) ? $row['is_show_insights'] : 0;
        $status = in_array($row['status'], [0, 1]) ? $row['status'] : 1;

        if (is_null($goal_type) || is_null($is_show_insights) || is_null($status)) {
            return null;
        }

        return new SectionDataMain([
            'title'            => $row['title'],
            'goal_type'        => $goal_type,
            'category_id'      => $row['category_id'],
            'is_show_insights' => $is_show_insights,
            'status'           => $status,
            'dragondrop'       => $this->dragondropCounter++, 
        ]);
    }

    public function rules(): array
    {
        return [
            '*.title'             => 'required|string',
            '*.goal_type'         => ['required', Rule::in([0, 1])],
            '*.category_id'       => 'required|exists:categories,id',
            '*.is_show_insights'  => ['required', Rule::in([0, 1])],
            '*.status'            => ['required', Rule::in([0, 1])],
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.title.required'            => 'The title is required.',
            '*.goal_type.in'              => 'Only 0 or 1 is allowed for goal_type.',
            '*.is_show_insights.in'       => 'Only 0 or 1 is allowed for is_show_insights.',
            '*.status.in'                 => 'Only 0 or 1 is allowed for status.',
            '*.category_id.exists'        => 'The category_id must exist in the categories table.',
        ];
    }
}
