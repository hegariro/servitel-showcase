# Servitel Showcase - API Backend

Este proyecto es una API construida con Laravel 12, diseñada para el showcase de Servitel. Incluye una arquitectura robusta, documentación con Swagger y está completamente contenedorizada con Docker.

## 🛠️ Requisitos Previos

- [Docker](https://www.docker.com/products/docker-desktop) instalado.
- [Make](https://www.gnu.org/software/make/) (opcional, pero recomendado para usar los comandos automatizados).

## 🚀 Instalación y Ejecución

El proyecto utiliza un `Makefile` para simplificar la configuración inicial. Sigue estos pasos para poner en marcha el sistema:

1.  **Clonar el repositorio** (si aún no lo has hecho).
2.  **Inicializar el proyecto**:
    ```bash
    make init
    ```
    Este comando construirá las imágenes, levantará los contenedores, generará la clave de la aplicación (`APP_KEY`) y ejecutará las migraciones.

3.  **Acceso a la aplicación**:
    - La API estará disponible en: `http://localhost:8080`
    - Documentación Swagger: `http://localhost:8080/api/documentation`

## ⚙️ Configuración

Las variables de entorno se gestionan en el archivo `backend/.env`. Al ejecutar `make init`, se crea automáticamente a partir de `.env.example`.

### Variables clave:
- `DB_DATABASE`: Nombre de la base de datos (por defecto: `backend`).
- `DB_USERNAME`: Usuario de la base de datos (por defecto: `root`).
- `DB_PASSWORD`: Contraseña de la base de datos.
- `APP_URL`: URL base de la aplicación.

## 🗄️ Base de Datos y Migraciones

El proyecto utiliza MySQL como motor de base de datos. Puedes gestionar la base de datos con los siguientes comandos:

- **Ejecutar migraciones**: 
  ```bash
  make migrate
  ```
- **Ejecutar seeders** (datos de prueba): 
  ```bash
  make seed
  ```
- **Reiniciar base de datos** (borra todo y vuelve a migrar/seed): 
  ```bash
  make migrate-fresh
  ```

## 🔌 Endpoints Disponibles

La API utiliza Laravel Sanctum para la autenticación. A continuación se listan los endpoints principales:

### Productos
- `GET /api/products` - Listado de productos.
- `POST /api/products` - Crear un producto.
- `GET /api/products/{id}` - Ver detalle de un producto.
- `PUT/PATCH /api/products/{id}` - Actualizar un producto.
- `DELETE /api/products/{id}` - Eliminar un producto.

### Partners (Externos)
- `GET /api/partner/dogs` - Obtiene una imagen aleatoria de un perro de un servicio externo.

## 🛠️ Otros Comandos Útiles

- `make up`: Levanta los servicios.
- `make down`: Detiene y elimina los contenedores.
- `make logs`: Visualiza los logs en tiempo real.
- `make swagger`: Regenera la documentación de Swagger.
- `make shell`: Entra a la terminal del contenedor de Laravel.
- `make get-token`: Genera un token de acceso para el usuario `admin@servitel.dev`.
