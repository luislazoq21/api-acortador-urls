# API Acortador de URLs - Especificaciones Técnicas
## Patrón MVC con PHP Puro

---

## 📋 Índice
1. [Descripción General](#descripción-general)
2. [Arquitectura MVC](#arquitectura-mvc)
3. [Stack Tecnológico](#stack-tecnológico)
4. [Estructura de Directorios](#estructura-de-directorios)
5. [Base de Datos](#base-de-datos)
6. [Modelos (Models)](#modelos-models)
7. [Controladores (Controllers)](#controladores-controllers)
8. [Vistas (Views)](#vistas-views)
9. [Enrutamiento (Router)](#enrutamiento-router)
10. [Endpoints de la API](#endpoints-de-la-api)
11. [Validaciones](#validaciones)
12. [Seguridad](#seguridad)
13. [Configuración](#configuración)

---

## 📝 Descripción General

Sistema de acortamiento de URLs con enfoque híbrido que permite:
- **Usuarios anónimos**: Acortar URLs sin registro (funcionalidad limitada)
- **Usuarios registrados**: Gestión completa de sus URLs con estadísticas

### Características Principales
- ✅ Acortamiento de URLs largas
- ✅ Redirección automática
- ✅ Conteo de clicks
- ✅ URLs personalizadas (alias custom) para usuarios registrados
- ✅ Gestión completa CRUD para usuarios autenticados
- ✅ Autenticación con JWT
- ✅ API RESTful

---

## 🏗️ Arquitectura MVC

### Componentes del Patrón

**Model (Modelo)**
- Representa los datos y la lógica de negocio
- Interactúa directamente con la base de datos
- Contiene validaciones de datos
- No conoce nada sobre la presentación

**View (Vista)**
- En este caso: Respuestas JSON
- No contiene lógica de negocio
- Solo formatea y presenta datos

**Controller (Controlador)**
- Intermediario entre Model y View
- Maneja las peticiones HTTP
- Invoca métodos del modelo
- Prepara datos para la vista/respuesta
- Maneja la lógica de aplicación

**Router (Enrutador)**
- Mapea URLs a controladores específicos
- Gestiona los verbos HTTP (GET, POST, PUT, DELETE)

---

## 💻 Stack Tecnológico

### Backend
- **PHP**: 8.0 o superior
- **Composer**: Para gestión de dependencias
- **MySQL/MariaDB**: Base de datos

### Librerías
```json
{
  "require": {
    "firebase/php-jwt": "^6.0",
    "vlucas/phpdotenv": "^5.0"
  }
}
```

### Herramientas Recomendadas
- **Postman/Thunder Client**: Testing de API
- **Git**: Control de versiones
- **phpMyAdmin**: Gestión de base de datos

---

## 📁 Estructura de Directorios

```
url-shortener-api/
│
├── app/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── UrlController.php
│   │   └── RedirectController.php
│   │
│   ├── Models/
│   │   ├── User.php
│   │   ├── Url.php
│   │   └── Model.php (clase base)
│   │
│   ├── Middleware/
│   │   └── AuthMiddleware.php
│   │
│   └── Views/
│       └── JsonView.php
│
├── config/
│   ├── database.php
│   └── config.php
│
├── core/
│   ├── Router.php
│   ├── Request.php
│   ├── Response.php
│   └── Database.php
│
├── helpers/
│   ├── Validator.php
│   ├── ShortCode.php
│   └── functions.php
│
├── public/
│   ├── .htaccess
│   └── index.php (punto de entrada)
│
├── logs/
│   └── .gitkeep
│
├── .env.example
├── .env
├── .htaccess
├── composer.json
├── composer.lock
└── README.md
```

### Descripción de Directorios

- **app/**: Contiene la lógica principal (MVC)
- **config/**: Archivos de configuración
- **core/**: Clases fundamentales del framework
- **helpers/**: Funciones y clases auxiliares
- **public/**: Único directorio accesible públicamente
- **logs/**: Registro de errores y actividades

---

## 🗄️ Base de Datos

### Diagrama Entidad-Relación

```
[users] 1 ──── N [urls]
```

### Script de Creación

```sql
-- Base de datos
CREATE DATABASE IF NOT EXISTS url_shortener 
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE url_shortener;

-- Tabla: users
CREATE TABLE users (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB;

-- Tabla: urls
CREATE TABLE urls (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNSIGNED NULL,
    original_url TEXT NOT NULL,
    short_code VARCHAR(10) UNIQUE NOT NULL,
    custom_alias VARCHAR(50) UNIQUE NULL,
    clicks INT UNSIGNED DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_short_code (short_code),
    INDEX idx_user_id (user_id),
    INDEX idx_custom_alias (custom_alias)
) ENGINE=InnoDB;

-- Tabla: url_clicks (opcional - para estadísticas avanzadas)
CREATE TABLE url_clicks (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    url_id INT UNSIGNED NOT NULL,
    clicked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    referer VARCHAR(255) NULL,
    FOREIGN KEY (url_id) REFERENCES urls(id) ON DELETE CASCADE,
    INDEX idx_url_id (url_id),
    INDEX idx_clicked_at (clicked_at)
) ENGINE=InnoDB;
```

---

## 📦 Modelos (Models)

### Clase Base: Model.php

```php
<?php
namespace App\Models;

abstract class Model
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Métodos básicos CRUD
    abstract public function find($id);
    abstract public function all();
    abstract public function create(array $data);
    abstract public function update($id, array $data);
    abstract public function delete($id);
}
```

### User.php

**Responsabilidades:**
- Gestión de usuarios (CRUD)
- Validación de credenciales
- Hash de contraseñas
- Búsqueda por email

**Métodos principales:**
```php
- find($id): User|null
- findByEmail($email): User|null
- create(array $data): User
- validateCredentials($email, $password): bool
- all(): array
```

### Url.php

**Responsabilidades:**
- Gestión de URLs (CRUD)
- Generación de códigos cortos
- Validación de URLs
- Conteo de clicks
- Búsqueda por usuario

**Métodos principales:**
```php
- find($id): Url|null
- findByShortCode($code): Url|null
- findByUser($userId): array
- create(array $data): Url
- update($id, array $data): bool
- delete($id): bool
- incrementClicks($id): bool
- generateUniqueCode(): string
- isShortCodeAvailable($code): bool
```

---

## 🎮 Controladores (Controllers)

### Estructura Base de Controlador

```php
<?php
namespace App\Controllers;

class BaseController
{
    protected function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function errorResponse($message, $statusCode = 400)
    {
        $this->jsonResponse([
            'success' => false,
            'message' => $message
        ], $statusCode);
    }

    protected function successResponse($data, $message = null, $statusCode = 200)
    {
        $response = ['success' => true];
        if ($message) $response['message'] = $message;
        $response['data'] = $data;
        $this->jsonResponse($response, $statusCode);
    }
}
```

### AuthController.php

**Responsabilidades:**
- Registro de usuarios
- Inicio de sesión
- Generación de tokens JWT
- Validación de credenciales

**Endpoints:**
```php
- register(): POST /api/auth/register
- login(): POST /api/auth/login
- me(): GET /api/auth/me (usuario actual)
```

### UrlController.php

**Responsabilidades:**
- Acortar URLs (anónimo/registrado)
- Listar URLs del usuario
- Obtener estadísticas
- Actualizar URLs
- Eliminar URLs
- Crear alias personalizados

**Endpoints:**
```php
- shorten(): POST /api/shorten
- index(): GET /api/urls (solo registrados)
- show($id): GET /api/urls/{id} (solo registrados)
- update($id): PUT /api/urls/{id} (solo registrados)
- delete($id): DELETE /api/urls/{id} (solo registrados)
- stats($id): GET /api/urls/{id}/stats (solo registrados)
```

### RedirectController.php

**Responsabilidades:**
- Redireccionar URLs cortas a originales
- Incrementar contador de clicks
- Registrar estadísticas de clicks
- Mostrar información de URL sin redireccionar

**Endpoints:**
```php
- redirect($shortCode): GET /{shortCode}
- info($shortCode): GET /api/info/{shortCode}
```

---

## 👁️ Vistas (Views)

### JsonView.php

Aunque es una API REST que solo devuelve JSON, seguimos el patrón MVC separando la presentación:

```php
<?php
namespace App\Views;

class JsonView
{
    public static function render($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    public static function success($data, $message = null, $statusCode = 200)
    {
        self::render([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    public static function error($message, $statusCode = 400, $errors = null)
    {
        $response = [
            'success' => false,
            'message' => $message
        ];
        if ($errors) $response['errors'] = $errors;
        self::render($response, $statusCode);
    }
}
```

---

## 🛣️ Enrutamiento (Router)

### Router.php

**Responsabilidades:**
- Mapear rutas a controladores
- Gestionar métodos HTTP
- Extraer parámetros de URL
- Aplicar middleware

**Estructura básica:**
```php
$router = new Router();

// Rutas públicas
$router->post('/api/auth/register', 'AuthController@register');
$router->post('/api/auth/login', 'AuthController@login');
$router->post('/api/shorten', 'UrlController@shorten');
$router->get('/{shortCode}', 'RedirectController@redirect');
$router->get('/api/info/{shortCode}', 'RedirectController@info');

// Rutas protegidas (requieren autenticación)
$router->group(['middleware' => 'auth'], function($router) {
    $router->get('/api/auth/me', 'AuthController@me');
    $router->get('/api/urls', 'UrlController@index');
    $router->get('/api/urls/{id}', 'UrlController@show');
    $router->put('/api/urls/{id}', 'UrlController@update');
    $router->delete('/api/urls/{id}', 'UrlController@delete');
    $router->get('/api/urls/{id}/stats', 'UrlController@stats');
});

$router->dispatch();
```

---

## 🔌 Endpoints de la API

### 1. Autenticación

#### Registro de Usuario
```
POST /api/auth/register
Content-Type: application/json

Request Body:
{
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "password": "password123"
}

Response 201 (Success):
{
    "success": true,
    "message": "Usuario registrado exitosamente",
    "data": {
        "id": 1,
        "name": "Juan Pérez",
        "email": "juan@example.com",
        "created_at": "2025-10-15 10:30:00"
    }
}

Response 400 (Error):
{
    "success": false,
    "message": "Error de validación",
    "errors": {
        "email": "El email ya está registrado",
        "password": "La contraseña debe tener al menos 6 caracteres"
    }
}
```

#### Login
```
POST /api/auth/login
Content-Type: application/json

Request Body:
{
    "email": "juan@example.com",
    "password": "password123"
}

Response 200 (Success):
{
    "success": true,
    "message": "Login exitoso",
    "data": {
        "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
        "token_type": "Bearer",
        "expires_in": 86400,
        "user": {
            "id": 1,
            "name": "Juan Pérez",
            "email": "juan@example.com"
        }
    }
}

Response 401 (Error):
{
    "success": false,
    "message": "Credenciales inválidas"
}
```

#### Obtener Usuario Actual
```
GET /api/auth/me
Authorization: Bearer {token}

Response 200:
{
    "success": true,
    "data": {
        "id": 1,
        "name": "Juan Pérez",
        "email": "juan@example.com",
        "total_urls": 15,
        "total_clicks": 234,
        "created_at": "2025-10-15 10:30:00"
    }
}
```

---

### 2. Acortamiento de URLs

#### Acortar URL (Anónimo)
```
POST /api/shorten
Content-Type: application/json

Request Body:
{
    "url": "https://www.example.com/very/long/url/path"
}

Response 201:
{
    "success": true,
    "message": "URL acortada exitosamente",
    "data": {
        "original_url": "https://www.example.com/very/long/url/path",
        "short_url": "http://tudominio.com/abc123",
        "short_code": "abc123",
        "created_at": "2025-10-15 10:30:00",
        "note": "Esta URL es anónima y no podrá ser gestionada posteriormente"
    }
}
```

#### Acortar URL (Registrado)
```
POST /api/shorten
Authorization: Bearer {token}
Content-Type: application/json

Request Body:
{
    "url": "https://www.example.com/very/long/url/path",
    "custom_alias": "mi-enlace" (opcional)
}

Response 201:
{
    "success": true,
    "message": "URL acortada exitosamente",
    "data": {
        "id": 15,
        "original_url": "https://www.example.com/very/long/url/path",
        "short_url": "http://tudominio.com/mi-enlace",
        "short_code": "mi-enlace",
        "clicks": 0,
        "is_active": true,
        "created_at": "2025-10-15 10:30:00"
    }
}

Response 409 (Alias ya existe):
{
    "success": false,
    "message": "El alias personalizado ya está en uso"
}
```

---

### 3. Gestión de URLs (Solo Registrados)

#### Listar Mis URLs
```
GET /api/urls?page=1&limit=10
Authorization: Bearer {token}

Response 200:
{
    "success": true,
    "data": {
        "urls": [
            {
                "id": 15,
                "original_url": "https://www.example.com/...",
                "short_url": "http://tudominio.com/abc123",
                "short_code": "abc123",
                "clicks": 42,
                "is_active": true,
                "created_at": "2025-10-15 10:30:00",
                "updated_at": "2025-10-15 15:20:00"
            }
        ],
        "pagination": {
            "current_page": 1,
            "per_page": 10,
            "total_pages": 5,
            "total_items": 47
        }
    }
}
```

#### Obtener Una URL Específica
```
GET /api/urls/{id}
Authorization: Bearer {token}

Response 200:
{
    "success": true,
    "data": {
        "id": 15,
        "original_url": "https://www.example.com/...",
        "short_url": "http://tudominio.com/abc123",
        "short_code": "abc123",
        "clicks": 42,
        "is_active": true,
        "created_at": "2025-10-15 10:30:00",
        "updated_at": "2025-10-15 15:20:00"
    }
}

Response 404:
{
    "success": false,
    "message": "URL no encontrada"
}

Response 403:
{
    "success": false,
    "message": "No tienes permiso para acceder a esta URL"
}
```

#### Actualizar URL
```
PUT /api/urls/{id}
Authorization: Bearer {token}
Content-Type: application/json

Request Body:
{
    "original_url": "https://www.nuevo-url.com",
    "is_active": true
}

Response 200:
{
    "success": true,
    "message": "URL actualizada exitosamente",
    "data": {
        "id": 15,
        "original_url": "https://www.nuevo-url.com",
        "short_code": "abc123",
        "clicks": 42,
        "is_active": true
    }
}
```

#### Eliminar URL
```
DELETE /api/urls/{id}
Authorization: Bearer {token}

Response 200:
{
    "success": true,
    "message": "URL eliminada exitosamente"
}

Response 403:
{
    "success": false,
    "message": "No tienes permiso para eliminar esta URL"
}
```

#### Obtener Estadísticas de URL
```
GET /api/urls/{id}/stats
Authorization: Bearer {token}

Response 200:
{
    "success": true,
    "data": {
        "id": 15,
        "original_url": "https://www.example.com/...",
        "short_code": "abc123",
        "total_clicks": 42,
        "created_at": "2025-10-15 10:30:00",
        "last_click": "2025-10-15 20:45:00",
        "clicks_by_day": [
            {
                "date": "2025-10-15",
                "clicks": 15
            },
            {
                "date": "2025-10-14",
                "clicks": 27
            }
        ]
    }
}
```

---

### 4. Redirección (Público)

#### Usar URL Corta (Redirección)
```
GET /{shortCode}

Response 302 (Redirect):
Location: https://www.example.com/original-url
(El navegador redirige automáticamente)

Response 404:
{
    "success": false,
    "message": "URL no encontrada o inactiva"
}
```

#### Obtener Información sin Redireccionar
```
GET /api/info/{shortCode}

Response 200:
{
    "success": true,
    "data": {
        "original_url": "https://www.example.com/...",
        "short_code": "abc123",
        "short_url": "http://tudominio.com/abc123",
        "clicks": 42,
        "is_active": true,
        "created_at": "2025-10-15 10:30:00"
    }
}

Response 404:
{
    "success": false,
    "message": "URL no encontrada"
}
```

---

## ✅ Validaciones

### Validación de URLs

```php
class UrlValidator
{
    public static function validate($url): array
    {
        $errors = [];

        // URL no vacía
        if (empty($url)) {
            $errors['url'] = 'La URL es requerida';
        }

        // URL válida con protocolo
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            $errors['url'] = 'La URL no es válida';
        }

        // Debe tener http o https
        if (!preg_match('/^https?:\/\//', $url)) {
            $errors['url'] = 'La URL debe comenzar con http:// o https://';
        }

        // Longitud máxima
        if (strlen($url) > 2000) {
            $errors['url'] = 'La URL es demasiado larga (máximo 2000 caracteres)';
        }

        return $errors;
    }
}
```

### Validación de Custom Alias

```php
public static function validateAlias($alias): array
{
    $errors = [];

    // Solo alfanuméricos y guiones
    if (!preg_match('/^[a-zA-Z0-9-]+$/', $alias)) {
        $errors['custom_alias'] = 'Solo se permiten letras, números y guiones';
    }

    // Longitud entre 3 y 50 caracteres
    if (strlen($alias) < 3 || strlen($alias) > 50) {
        $errors['custom_alias'] = 'Debe tener entre 3 y 50 caracteres';
    }

    // No puede ser un endpoint reservado
    $reserved = ['api', 'admin', 'auth', 'urls', 'info'];
    if (in_array(strtolower($alias), $reserved)) {
        $errors['custom_alias'] = 'Este alias está reservado';
    }

    return $errors;
}
```

### Validación de Usuarios

```php
class UserValidator
{
    public static function validateRegistration($data): array
    {
        $errors = [];

        // Nombre requerido
        if (empty($data['name'])) {
            $errors['name'] = 'El nombre es requerido';
        }

        // Email válido
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'El email no es válido';
        }

        // Password mínimo 6 caracteres
        if (strlen($data['password']) < 6) {
            $errors['password'] = 'La contraseña debe tener al menos 6 caracteres';
        }

        return $errors;
    }
}
```

---

## 🔒 Seguridad

### 1. Autenticación JWT

```php
// Generación de token
use Firebase\JWT\JWT;

class JWTHandler
{
    private static $secret;
    private static $expiration = 86400; // 24 horas

    public static function generate($userId, $email): string
    {
        $payload = [
            'iss' => $_ENV['BASE_URL'],
            'iat' => time(),
            'exp' => time() + self::$expiration,
            'user_id' => $userId,
            'email' => $email
        ];

        return JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
    }

    public static function verify($token): object|false
    {
        try {
            return JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
        } catch (Exception $e) {
            return false;
        }
    }
}
```

### 2. Hash de Contraseñas

```php
// Al registrar
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Al validar login
if (password_verify($inputPassword, $hashedPassword)) {
    // Login exitoso
}
```

### 3. Prepared Statements

```php
// SIEMPRE usar prepared statements
$stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
```

### 4. Sanitización de Datos

```php
class Sanitizer
{
    public static function cleanUrl($url): string
    {
        return filter_var($url, FILTER_SANITIZE_URL);
    }

    public static function cleanString($string): string
    {
        return htmlspecialchars(strip_tags(trim($string)), ENT_QUOTES, 'UTF-8');
    }
}
```

### 5. CORS Headers

```php
// En index.php o middleware
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
```

### 6. Rate Limiting (Básico)

```php
class RateLimiter
{
    public static function check($identifier, $maxAttempts = 60, $decay = 60): bool
    {
        // Implementación con archivos o Redis
        // Limitar peticiones por IP o usuario
    }
}
```

### 7. Validación de Permisos

```php
// En UrlController
public function update($id)
{
    $url = $this->urlModel->find($id);
    
    if (!$url) {
        return $this->errorResponse('URL no encontrada', 404);
    }
    
    // Verificar que la URL pertenece al usuario autenticado
    if ($url['user_id'] !== $this->currentUser['id']) {
        return $this->errorResponse('No autorizado', 403);
    }
    
    // Continuar con actualización
}
```

---

## ⚙️ Configuración

### Archivo .env

```env
# Base de datos
DB_HOST=localhost
DB_PORT=3306
DB_NAME=url_shortener
DB_USER=root
DB_PASS=

# JWT
JWT_SECRET=tu_clave_secreta_muy_segura_cambiar_en_produccion
JWT_EXPIRATION=86400

# Aplicación
APP_NAME="URL Shortener API"
APP_ENV=development
APP_DEBUG=true
BASE_URL=http://localhost/url-shortener-api

# Configuración de URLs cortas
SHORT_CODE_LENGTH=6
ALLOW_CUSTOM_ALIAS=true
```

### Archivo config/database.php

```php
<?php

return [
    'host' => $_ENV['DB_HOST'],
    'port' => $_ENV['DB_PORT'],
    'database' => $_ENV['DB_NAME'],
    'username' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASS'],
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
```

### Archivo .htaccess (raíz)

```apache
# Redirigir todo al directorio public
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### Archivo public/.htaccess

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Redirigir todo a index.php
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>
```

---

## 📊 Códigos de Estado HTTP

| Código | Significado | Uso |
|--------|-------------|-----|
| 200 | OK | Operación exitosa (GET, PUT) |
| 201 | Created | Recurso creado exitosamente (POST) |
| 204 | No Content | Operación exitosa sin contenido |
| 400 | Bad Request | Datos inválidos o faltantes |
| 401 | Unauthorized | No autenticado (token faltante/inválido) |
| 403 | Forbidden | No autorizado (sin permisos) |
| 404 | Not Found | Recurso no encontrado |
| 409 | Conflict | Conflicto (ej: email duplicado) |
| 422 | Unprocessable Entity | Validación fallida |
| 500 | Internal Server Error | Error del servidor |

---

## 🧪 Testing Manual

### Ejemplos con cURL

#### Registrar usuario
```bash
curl -X POST http://localhost/url-shortener-api/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "password": "password123"
  }'
```

#### Login
```bash
curl -X POST http://localhost/url-shortener-api/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "juan@example.com",
    "password": "password123"
  }'
```

#### Acortar URL (Anónimo)
```bash
curl -X POST http://localhost/url-shortener-api/api/shorten \
  -H "Content-Type: application/json" \
  -d '{
    "url": "https://www.example.com/very/long/url"
  }'
```

#### Acortar URL (Registrado con alias)
```bash
curl -X POST http://localhost/url-shortener-api/api/shorten \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer TU_TOKEN_AQUI" \
  -d '{
    "url": "https://www.example.com/very/long/url",
    "custom_alias": "mi-enlace"
  }'
```

#### Listar mis URLs
```bash
curl -X GET http://localhost/url-shortener-api/api/urls \
  -H "Authorization: Bearer TU_TOKEN_AQUI"
```

#### Obtener estadísticas
```bash
curl -X GET http://localhost/url-shortener-api/api/urls/15/stats \
  -H "Authorization: Bearer TU_TOKEN_AQUI"
```

#### Actualizar URL
```bash
curl -X PUT http://localhost/url-shortener-api/api/urls/15 \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer TU_TOKEN_AQUI" \
  -d '{
    "original_url": "https://www.nuevo-url.com"
  }'
```

#### Eliminar URL
```bash
curl -X DELETE http://localhost/url-shortener-api/api/urls/15 \
  -H "Authorization: Bearer TU_TOKEN_AQUI"
```

#### Obtener info de URL sin redireccionar
```bash
curl -X GET http://localhost/url-shortener-api/api/info/abc123
```

---

## 📝 Reglas de Negocio

### Usuario Anónimo

**Puede:**
- ✅ Acortar URLs (código generado automáticamente)
- ✅ Usar URLs cortas (redirección)
- ✅ Ver información básica de URLs

**No puede:**
- ❌ Crear alias personalizados
- ❌ Ver lista de sus URLs
- ❌ Ver estadísticas detalladas
- ❌ Editar URLs
- ❌ Eliminar URLs

### Usuario Registrado

**Puede:**
- ✅ Todo lo que puede un usuario anónimo
- ✅ Crear alias personalizados
- ✅ Ver todas sus URLs
- ✅ Ver estadísticas detalladas
- ✅ Editar sus URLs
- ✅ Eliminar sus URLs
- ✅ Activar/desactivar URLs

**Restricciones:**
- Solo puede gestionar sus propias URLs
- No puede acceder a URLs de otros usuarios
- Los alias personalizados deben ser únicos globalmente

### Generación de Códigos Cortos

**Reglas:**
- Longitud: 6 caracteres por defecto (configurable)
- Caracteres permitidos: a-z, A-Z, 0-9 (62 combinaciones)
- Debe ser único en la base de datos
- Se genera aleatoriamente
- Si existe, regenerar hasta encontrar uno disponible

**Cálculo de combinaciones posibles:**
```
62^6 = 56,800,235,584 combinaciones posibles
```

### URLs Personalizadas (Alias)

**Reglas:**
- Solo para usuarios registrados
- 3-50 caracteres
- Solo alfanuméricos y guiones (-)
- No puede comenzar o terminar con guión
- Debe ser único globalmente
- No puede ser un endpoint reservado
- Case-insensitive (se guardan en minúsculas)

**Endpoints reservados:**
```php
$reserved = [
    'api',
    'admin',
    'auth',
    'urls',
    'info',
    'login',
    'register',
    'shorten'
];
```

### Contador de Clicks

**Comportamiento:**
- Se incrementa cada vez que alguien accede a `/{shortCode}`
- Se incrementa ANTES de la redirección
- No se incrementa al consultar `/api/info/{shortCode}`
- No importa si el usuario está autenticado o no
- Se puede registrar información adicional (IP, user agent, referer)

### URLs Inactivas

- Los usuarios registrados pueden desactivar sus URLs
- URLs inactivas retornan 404 al intentar acceder
- URLs inactivas siguen apareciendo en la lista del usuario
- URLs inactivas pueden reactivarse

---

## 🚀 Instalación y Configuración

### Requisitos Previos

- PHP 8.0 o superior
- MySQL 5.7 o MariaDB 10.2+
- Composer
- Apache con mod_rewrite habilitado (o Nginx)

### Pasos de Instalación

**1. Clonar el repositorio**
```bash
git clone https://github.com/tu-usuario/url-shortener-api.git
cd url-shortener-api
```

**2. Instalar dependencias**
```bash
composer install
```

**3. Configurar variables de entorno**
```bash
cp .env.example .env
# Editar .env con tus credenciales
```

**4. Crear base de datos**
```bash
mysql -u root -p
CREATE DATABASE url_shortener;
exit;
```

**5. Importar estructura**
```bash
mysql -u root -p url_shortener < database/schema.sql
```

**6. Configurar permisos**
```bash
chmod -R 755 .
chmod -R 777 logs/
```

**7. Configurar virtual host (Opcional)**
```apache
<VirtualHost *:80>
    ServerName url-shortener.local
    DocumentRoot /path/to/url-shortener-api/public
    
    <Directory /path/to/url-shortener-api/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**8. Probar instalación**
```bash
# Usando servidor PHP integrado
php -S localhost:8000 -t public/

# O visitar:
http://localhost/url-shortener-api/api/info/test
```

---

## 📚 Estructura de Respuestas

### Respuesta Exitosa

```json
{
    "success": true,
    "message": "Operación exitosa",
    "data": {
        // Datos de respuesta
    }
}
```

### Respuesta con Error

```json
{
    "success": false,
    "message": "Descripción del error"
}
```

### Respuesta con Errores de Validación

```json
{
    "success": false,
    "message": "Error de validación",
    "errors": {
        "email": "El email ya está registrado",
        "password": "La contraseña debe tener al menos 6 caracteres"
    }
}
```

### Respuesta con Paginación

```json
{
    "success": true,
    "data": {
        "items": [],
        "pagination": {
            "current_page": 1,
            "per_page": 10,
            "total_pages": 5,
            "total_items": 47,
            "has_next": true,
            "has_prev": false
        }
    }
}
```

---

## 🔍 Funcionalidades Adicionales (Opcionales)

Estas características pueden agregarse para mejorar el proyecto:

### 1. Expiración de URLs
- Campo `expires_at` en tabla `urls`
- URLs que expiran automáticamente después de X días
- Solo para usuarios registrados

### 2. Protección con Contraseña
- URLs que requieren contraseña para acceder
- Campo `password` (hasheado) en tabla `urls`

### 3. Estadísticas Avanzadas
- Gráficos de clicks por día/semana/mes
- Origen geográfico de clicks (por IP)
- Dispositivos más usados
- Navegadores más usados

### 4. QR Codes
- Generar QR code automáticamente para cada URL
- Endpoint: `GET /api/urls/{id}/qr`

### 5. Tags/Categorías
- Organizar URLs por categorías
- Filtrar URLs por tag

### 6. API Rate Limiting
- Limitar peticiones por IP
- Límites diferentes para usuarios anónimos vs registrados

### 7. Dashboard Web
- Interfaz web con Vue.js o React
- Visualización de estadísticas
- Gestión de URLs

### 8. Webhooks
- Notificar cuando una URL alcanza X clicks
- Notificar cuando una URL está por expirar

---

## 📖 Documentación de API

### Formato de Documentación

Crear un archivo `API_DOCUMENTATION.md` con:

**Para cada endpoint incluir:**
- URL y método HTTP
- Descripción
- Parámetros requeridos/opcionales
- Headers necesarios
- Ejemplo de request body
- Ejemplos de respuestas (success y errores)
- Códigos de estado HTTP posibles

**Herramientas recomendadas:**
- Postman Collection (exportar y compartir)
- Swagger/OpenAPI
- Insomnia Collection

---

## 🐛 Manejo de Errores

### Logs de Errores

```php
class Logger
{
    public static function error($message, $context = [])
    {
        $date = date('Y-m-d H:i:s');
        $logMessage = "[$date] ERROR: $message\n";
        
        if (!empty($context)) {
            $logMessage .= "Context: " . json_encode($context) . "\n";
        }
        
        $logMessage .= "---\n";
        
        file_put_contents(
            __DIR__ . '/../logs/error.log',
            $logMessage,
            FILE_APPEND
        );
    }
    
    public static function info($message)
    {
        $date = date('Y-m-d H:i:s');
        $logMessage = "[$date] INFO: $message\n";
        
        file_put_contents(
            __DIR__ . '/../logs/info.log',
            $logMessage,
            FILE_APPEND
        );
    }
}
```

### Try-Catch Global

```php
// En index.php
try {
    $router->dispatch();
} catch (Exception $e) {
    Logger::error($e->getMessage(), [
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => $e->getTraceAsString()
    ]);
    
    JsonView::error(
        'Error interno del servidor',
        500
    );
}
```

---

## ✅ Checklist de Desarrollo

### Fase 1: Configuración Base
- [ ] Crear estructura de directorios
- [ ] Configurar Composer
- [ ] Crear archivo .env
- [ ] Configurar base de datos
- [ ] Crear tablas SQL
- [ ] Configurar .htaccess

### Fase 2: Core del Sistema
- [ ] Implementar clase Database
- [ ] Implementar clase Router
- [ ] Implementar clase Request
- [ ] Implementar clase Response/JsonView
- [ ] Implementar helpers de validación

### Fase 3: Autenticación
- [ ] Modelo User
- [ ] AuthController (register, login)
- [ ] Implementar JWT
- [ ] AuthMiddleware
- [ ] Proteger rutas

### Fase 4: Funcionalidad Core
- [ ] Modelo Url
- [ ] Generador de códigos cortos
- [ ] UrlController (shorten - anónimo)
- [ ] UrlController (shorten - registrado)
- [ ] RedirectController (redirect)
- [ ] Contador de clicks

### Fase 5: Gestión de URLs
- [ ] Listar URLs del usuario
- [ ] Obtener URL específica
- [ ] Actualizar URL
- [ ] Eliminar URL
- [ ] Validación de permisos

### Fase 6: Estadísticas
- [ ] Endpoint de estadísticas básicas
- [ ] Endpoint de info pública
- [ ] Tabla de clicks (opcional)

### Fase 7: Validaciones y Seguridad
- [ ] Validaciones de URLs
- [ ] Validaciones de usuarios
- [ ] Sanitización de datos
- [ ] Prepared statements
- [ ] CORS headers
- [ ] Rate limiting (opcional)

### Fase 8: Testing y Documentación
- [ ] Probar todos los endpoints
- [ ] Crear colección de Postman
- [ ] Escribir README.md
- [ ] Escribir API_DOCUMENTATION.md
- [ ] Agregar comentarios en código

### Fase 9: Deployment
- [ ] Optimizar consultas SQL
- [ ] Configurar producción (.env)
- [ ] Probar en servidor
- [ ] Configurar SSL (HTTPS)
- [ ] Backup de base de datos

---

## 📦 Entregables del Proyecto

Para tu portafolio, asegúrate de incluir:

### 1. Código Fuente
- Repositorio en GitHub limpio y organizado
- Commits descriptivos y frecuentes
- Branches para features (opcional)

### 2. Documentación
- README.md completo con:
  - Descripción del proyecto
  - Características principales
  - Tecnologías utilizadas
  - Instrucciones de instalación
  - Screenshots/GIFs (si aplica)
  - Enlace a documentación de API
- API_DOCUMENTATION.md con todos los endpoints

### 3. Base de Datos
- Script SQL de creación
- Diagrama ER (opcional pero recomendado)
- Datos de prueba (seeds)

### 4. Testing
- Colección de Postman exportada
- Ejemplos de uso con cURL

### 5. Demo
- URL del proyecto en producción
- Usuario de prueba
- Video demo (opcional)

---

## 🎯 Criterios de Éxito

Tu proyecto será exitoso si cumple con:

### Funcionalidad
- ✅ Todas las características principales funcionan
- ✅ No hay errores críticos
- ✅ Las validaciones funcionan correctamente
- ✅ La redirección es instantánea

### Código
- ✅ Código limpio y bien organizado
- ✅ Patrón MVC correctamente implementado
- ✅ Nombres descriptivos de variables y funciones
- ✅ Comentarios donde sea necesario
- ✅ No hay código duplicado

### Seguridad
- ✅ Contraseñas hasheadas
- ✅ JWT implementado correctamente
- ✅ Validación de permisos
- ✅ Prepared statements
- ✅ Sanitización de datos

### Documentación
- ✅ README completo y claro
- ✅ API documentada
- ✅ Instalación explicada paso a paso
- ✅ Ejemplos de uso

### Base de Datos
- ✅ Estructura normalizada
- ✅ Índices en columnas apropiadas
- ✅ Relaciones correctas
- ✅ Sin redundancia de datos

---

## 💡 Consejos Finales

1. **Empieza simple**: Implementa primero la funcionalidad básica, luego agrega features
2. **Commit frecuente**: Haz commits pequeños y descriptivos
3. **Prueba constantemente**: No esperes a terminar todo para probar
4. **Documenta mientras desarrollas**: No dejes la documentación para el final
5. **Maneja errores**: Siempre usa try-catch y valida datos
6. **Piensa en seguridad**: Desde el inicio, no como algo adicional
7. **Usa Postman**: Te facilitará mucho el testing
8. **Lee tu código**: Revisa y refactoriza cuando sea necesario

---

## 📞 Soporte y Recursos

### Documentación Oficial
- [PHP Manual](https://www.php.net/manual/es/)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [JWT.io](https://jwt.io/)
- [REST API Guidelines](https://restfulapi.net/)

### Tutoriales Recomendados
- Patrón MVC en PHP
- JWT Authentication
- REST API Best Practices
- PDO y Prepared Statements

---

## 🏁 ¡Listo para Empezar!

Ahora tienes todas las especificaciones necesarias para desarrollar tu API de acortador de URLs con PHP puro y patrón MVC.

**Próximos pasos sugeridos:**
1. Crear la estructura de directorios
2. Configurar Composer y dependencias
3. Crear la base de datos
4. Implementar el sistema de enrutamiento
5. Desarrollar la autenticación
6. Implementar la funcionalidad core

¿Estás listo para comenzar a programar?