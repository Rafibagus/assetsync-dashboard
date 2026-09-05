<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'asset_tag'       => $this->asset_tag,
            'name'            => $this->name,
            'category_name'   => $this->category ? $this->category->name : 'Tanpa Kategori',
            'status'          => $this->status,
            'purchase_cost'   => (int) $this->purchase_cost,
            'formatted_cost'  => 'Rp ' . number_format($this->purchase_cost, 0, ',', '.'),
            'warranty_months' => $this->warranty_months,
            'last_updated'    => $this->updated_at->diffForHumans(),
        ];
    }
}