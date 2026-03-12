<?php

namespace App\Services;

use App\Models\Product;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class ProductService
{
    /**
     * Obtiene todos los productos de la base de datos.
     */
    public function getAllProducts(): Collection
    {
        try {
            return Product::all();
        } catch (Exception $e) {
            // Registramos el error en los logs de Laravel para auditoría
            Log::error("Error al obtener productos: " . $e->getMessage());
            
            // Lanzamos una excepción con un mensaje controlado
            throw new Exception("No se pudo recuperar la lista de productos en este momento.", 500);
        }
    }

    public function createProduct(array $data): Product
    {
        try {
            return Product::create($data);
        } catch (Exception $e) {
            Log::error("Error al crear producto: " . $e->getMessage());
            throw new Exception("Error interno al intentar guardar el producto.", 500);
        }
    }

    public function getProductById(string $uuid): Product
    {
        return Product::where('product_id', $uuid)->firstOrFail();
    }

    public function updateProduct(string $id, array $data): Product
    {
        try {
            $product = Product::where('product_id', $id)->firstOrFail();
            $product->update($data);
            return $product;
        } catch (Exception $e) {
            Log::error("Error al actualizar producto: " . $e->getMessage());
            throw new Exception("No se pudo actualizar el producto.", 500);
        }
    }

    public function deleteProduct(string $id): bool
    {
        try {
            $product = Product::where('product_id', $id)->firstOrFail();
            return $product->delete();
        } catch (Exception $e) {
            Log::error("Error al eliminar producto: " . $e->getMessage());
            throw new Exception("No se pudo eliminar el producto o no existe.", 500);
        }
    }
}