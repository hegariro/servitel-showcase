<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService) 
    {
        $this->productService = $productService;
    }

    /**
     * @OA\Get(
     *     path="/api/products",
     *     summary="Obtener lista de productos",
     *     tags={"Products"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de productos obtenida con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Product"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="No autorizado"),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No se pudo recuperar la lista de productos en este momento."),
     *             @OA\Property(property="data", type="object", nullable=true)
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        try {
            $products = $this->productService->getAllProducts();
            return response()->json([
                'status' => 'success',
                'data' => $products
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => null
            ], $e->getCode());
        }
    }

    /**
     * @OA\Post(
     *     path="/api/products",
     *     summary="Crear un nuevo producto",
     *     tags={"Products"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_name", "product_type", "unit_of_measurement", "net_content"},
     *             @OA\Property(property="product_name", type="string", maxLength=255, example="Aceite de Oliva"),
     *             @OA\Property(property="product_description", type="string", nullable=true, example="Aceite extra virgen"),
     *             @OA\Property(property="product_type", type="string", enum={"lata", "bolsa", "caja", "botella"}, example="botella"),
     *             @OA\Property(property="unit_of_measurement", type="string", example="ml"),
     *             @OA\Property(property="net_content", type="number", format="float", minimum=0, example=500)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Producto creado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Product created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Product")
     *         )
     *     ),
     *     @OA\Response(response=401, description="No autorizado"),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="The product name field is required."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Error interno al intentar guardar el producto.")
     *         )
     *     )
     * )
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            $product = $this->productService->createProduct($request->validated());
            
            return response()->json([
                'status'  => 'success',
                'message' => 'Product created successfully',
                'data'    => $product
            ], 201);
    
        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    /**
     * @OA\Get(
     *     path="/api/products/{product}",
     *     summary="Obtener un producto por ID",
     *     tags={"Products"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         description="UUID del producto",
     *         @OA\Schema(type="string", format="uuid", example="9d4e8a1b-2c3d-4e5f-6a7b-8c9d0e1f2a3b")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto obtenido con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", ref="#/components/schemas/Product")
     *         )
     *     ),
     *     @OA\Response(response=401, description="No autorizado"),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Producto no encontrado")
     *         )
     *     )
     * )
     */
    public function show(string $product): JsonResponse
    {
        try {
            $productData = $this->productService->getProductById($product);
            return response()->json(['status' => 'success', 'data' => $productData], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Producto no encontrado'], 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/products/{product}",
     *     summary="Actualizar un producto",
     *     tags={"Products"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         description="UUID del producto a actualizar",
     *         @OA\Schema(type="string", format="uuid", example="9d4e8a1b-2c3d-4e5f-6a7b-8c9d0e1f2a3b")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         description="Campos a actualizar (todos son opcionales en PUT)",
     *         @OA\JsonContent(
     *             @OA\Property(property="product_name", type="string", maxLength=255, example="Aceite de Oliva Premium"),
     *             @OA\Property(property="product_description", type="string", nullable=true, example="Aceite extra virgen 500ml"),
     *             @OA\Property(property="product_type", type="string", enum={"lata", "bolsa", "caja", "botella"}, example="botella"),
     *             @OA\Property(property="unit_of_measurement", type="string", example="ml"),
     *             @OA\Property(property="net_content", type="number", format="float", minimum=0, example=750)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producto actualizado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Product updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Product")
     *         )
     *     ),
     *     @OA\Response(response=401, description="No autorizado"),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Product].")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No se pudo actualizar el producto.")
     *         )
     *     )
     * )
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        try {
            $product = $this->productService->updateProduct($id, $request->validated());
        
            return response()->json([
                'status'  => 'success',
                'message' => 'Product updated successfully',
                'data'    => $product
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], $e->getCode()); // O 500 según el error
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{product}",
     *     summary="Eliminar un producto",
     *     tags={"Products"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         description="UUID del producto a eliminar",
     *         @OA\Schema(type="string", format="uuid", example="9d4e8a1b-2c3d-4e5f-6a7b-8c9d0e1f2a3b")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Producto eliminado exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Product deleted successfully")
     *         )
     *     ),
     *     @OA\Response(response=401, description="No autorizado"),
     *     @OA\Response(
     *         response=404,
     *         description="Producto no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Product].")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No se pudo eliminar el producto o no existe.")
     *         )
     *     )
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->productService->deleteProduct($id);
        
            return response()->json([
                'status'  => 'success',
                'message' => 'Product deleted successfully'
            ], 204);

        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }

}
