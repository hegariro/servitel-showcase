<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 * title="Servitel API",
 * version="1.0.0",
 * description="Documentación de la API de microservicios Servitel para la gestión de productos.",
 * @OA\Contact(
 * email="admin@servitel.dev"
 * )
 * )
 * @OA\Server(
 * url="http://localhost:8080",
 * description="Servidor Local de Desarrollo"
 * )
 * @OA\SecurityScheme(
 * type="http",
 * securityScheme="bearerAuth",
 * scheme="bearer",
 * bearerFormat="JWT"
 * )
 */
abstract class Controller
{
    //
}
