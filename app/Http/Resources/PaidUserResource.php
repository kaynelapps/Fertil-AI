<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaidUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'id'                   => $this->id,
            'product_identifier'   => $this->product_identifier,
            'purchase_date'        => dateAgoFormate($this->purchase_date,true),
            'amount'               => $this->amount .' '.$this->currency,
            'store'                => $this->store,
            'store_transaction_id' => $this->store_transaction_id,
            'original_app_user_id' => $this->original_app_user_id,
            'created_at'           => $this->created_at,
            'updated_at'           => $this->updated_at,
        ];
    }
}
