<?php

namespace App\Imports;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CategoryImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        return new Category([
            'name'      => trim($row['name'] ?? ''),
            'status'    => (int) trim($row['status'] ?? 1),
            'goal_type' => (int) trim($row['goal_type'] ?? 0),
        ]);
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string',
            'status'    => ['required', Rule::in([0, 1])],
            'goal_type' => ['required', Rule::in([0, 1])],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'status.in'    => 'Only 0 or 1 is allowed for status.',
            'goal_type.in' => 'Only 0 or 1 is allowed for goal type.',
        ];
    }
}