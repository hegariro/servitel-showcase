<?php

namespace App\Http\Controllers;

use App\Services\DogService;
use Exception;
use Illuminate\Http\JsonResponse;

class DogController extends Controller
{
    protected DogService $dogService;

    public function __construct(DogService $dogService)
    {
        $this->dogService = $dogService;
    }

    /**
     * @OA\Get(
     *     path="/api/partner/dogs",
     *     summary="Obtener imagen aleatoria de un perro",
     *     tags={"Partner - Dogs"},
     *     @OA\Response(
     *         response=200,
     *         description="Imagen obtenida exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="message", type="string", example="https://images.dog.ceo/breeds/spaniel-irish/n02102973_158.jpg"),
     *                 @OA\Property(property="status", type="string", example="success")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=502,
     *         description="Error al consumir API externa",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Error al consumir la API externa de Dog CEO.")
     *         )
     *     )
     * )
     */
    public function getRandomDogImage(): JsonResponse
    {
        try {
            $data = $this->dogService->getRandomImage();

            return response()->json([
                'status' => 'success',
                'data'   => $data
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
