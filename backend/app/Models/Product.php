<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     required={"product_id", "product_name", "product_type", "unit_of_measurement", "net_content"},
 *     @OA\Property(property="product_id", type="string", format="uuid", example="9d4e8a1b-2c3d-4e5f-6a7b-8c9d0e1f2a3b"),
 *     @OA\Property(property="product_name", type="string", example="Aceite de Oliva"),
 *     @OA\Property(property="product_description", type="string", nullable=true, example="Aceite extra virgen 500ml"),
 *     @OA\Property(property="product_type", type="string", enum={"lata", "bolsa", "caja", "botella"}, example="botella"),
 *     @OA\Property(property="unit_of_measurement", type="string", example="ml"),
 *     @OA\Property(property="net_content", type="number", format="float", example=500),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Product extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'product_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'product_name',
        'product_description',
        'product_type',
        'unit_of_measurement',
        'net_content',
    ];

    /**
    * Get the route key for the model.
    */
    public function getRouteKeyName(): string
    {
        return 'product_id';
    }
}
