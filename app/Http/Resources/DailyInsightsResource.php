<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\SubSymptoms;

class DailyInsightsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $subSymptoms = json_decode($this->sub_symptoms_id,true);
        $selectedSubsymptoms = [];
        if(isset($subSymptoms)){            
            $selectedSubsymptoms = SubSymptoms::whereIn('id', $subSymptoms)
            ->get()
            ->map(function ($item) {
                return $item->title;
            })
            ->toArray();
        }
        
        return [
            'id'                  => $this->id,
            'title'               => $this->title,
            'goal_type'           => $this->goal_type,
            'goal_type_name'      => getGoalType()[$this->goal_type] ?? null,
            'phase'               => getArticleType()[$this->phase] ?? null,
            'sub_symptoms_id'     => $subSymptoms,
            'sub_symptoms_title'  => implode(',',$selectedSubsymptoms) ?? null,
            'created_at'          => $this->created_at,
            'updated_at'          => $this->updated_at,
        ];
    }
}
