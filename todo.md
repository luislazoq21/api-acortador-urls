🎯 FASE 0: Preparación del Entorno
Verificación de Requisitos

[X] Verificar PHP 8.0 o superior instalado
[X] Verificar Composer instalado
[X] Verificar MySQL/MariaDB instalado y funcionando
[X] Verificar Apache con mod_rewrite habilitado
[X] Tener un editor de código (VS Code, PHPStorm, etc.)

Configuración Inicial

[X] Crear directorio principal del proyecto
[X] Inicializar Git en el proyecto
[X] Crear archivo .gitignore
[X] Crear repositorio en GitHub
[X] Conectar repositorio local con remoto
[X] Hacer primer commit

Entregable: Repositorio Git inicializado y conectado a GitHub

🏗️ FASE 1: Estructura del Proyecto
Creación de Directorios

[X] Crear carpeta app/
[X] Crear carpeta app/Controllers/
[X] Crear carpeta app/Models/
[X] Crear carpeta app/Middleware/
[X] Crear carpeta app/Views/
[X] Crear carpeta config/
[X] Crear carpeta core/
[X] Crear carpeta helpers/
[X] Crear carpeta public/
[X] Crear carpeta logs/
[X] Crear carpeta database/

Creación de Archivos Base

[X] Crear archivo composer.json
[X] Crear archivo .env.example
[X] Crear archivo .env
[] Crear archivo .htaccess (raíz) // TODO
[] Crear archivo public/.htaccess
[X] Crear archivo public/index.php
[] Crear archivo README.md // TODO
[X] Crear archivo config/database.php
[X] Crear archivo config/config.php

Commit

[X] Hacer commit: "feat: create project structure"
[X] Push a GitHub

Entregable: Estructura de directorios completa

📦 FASE 2: Configuración de Dependencias
Composer

[X] Configurar composer.json con información del proyecto
[X] Agregar dependencia: firebase/php-jwt
[X] Agregar dependencia: vlucas/phpdotenv
[X] Configurar autoload PSR-4
[X] Ejecutar composer install
[X] Verificar que se creó carpeta vendor/

Variables de Entorno

[X] Configurar variables de base de datos en .env
[X] Configurar JWT_SECRET en .env
[X] Configurar BASE_URL en .env
[X] Configurar variables de aplicación en .env
[X] Copiar configuración a .env.example (sin valores sensibles)

Archivos .htaccess

[X] Configurar redirección a public/ en apache
[X] Configurar rewrite rules en public/.htaccess
[X] Probar que funciona la redirección

Commit

[X] Hacer commit: "feat: configuración composer, dependencias, htaccess y servidor"
[X] Push a GitHub

Entregable: Dependencias instaladas y configuración básica lista

🗄️ FASE 3: Base de Datos
Creación de Base de Datos

[X] Abrir phpMyAdmin o cliente MySQL
[X] Crear base de datos url_shortener
[X] Configurar charset utf8mb4
[X] Crear archivo database/schema.sql

Tabla: users

[X] Diseñar estructura de tabla users
[X] Crear tabla con todos los campos
[X] Agregar índices necesarios
[X] Probar que se creó correctamente

Tabla: urls

[X] Diseñar estructura de tabla urls
[X] Crear tabla con todos los campos
[X] Agregar foreign key a users
[X] Agregar índices necesarios
[X] Probar que se creó correctamente

Tabla: url_clicks (Opcional)

[X] Diseñar estructura de tabla url_clicks
[X] Crear tabla con todos los campos
[X] Agregar foreign key a urls
[X] Agregar índices necesarios

Datos de Prueba

[X] Crear archivo database/seeds.sql
[X] Insertar usuario de prueba
[X] Insertar 3-5 URLs de prueba
[X] Verificar que los datos se insertaron

Commit

[X] Hacer commit: "feat: creación base de datos y datos de prueba"
[X] Push a GitHub

Entregable: Base de datos creada con estructura completa

🔧 FASE 4: Core del Sistema
Clase Database

[X] Crear archivo core/Database.php
[X] Implementar patrón Singleton
[X] Implementar conexión PDO
[X] Configurar opciones de PDO
[X] Agregar manejo de errores
[X] Probar conexión a base de datos

Clase Request

[X] Crear archivo core/Request.php
[X] Implementar método para obtener método HTTP
[X] Implementar método para obtener URI
[X] Implementar método para obtener body (JSON)
[X] Implementar método para obtener headers
[X] Implementar método para obtener parámetros de URL

Clase Response

[X] Crear archivo core/Response.php
[X] Implementar método para enviar JSON
[X] Implementar método para códigos de estado HTTP
[X] Implementar método para headers CORS
[X] Implementar método para redirecciones

Clase Router

[X] Crear archivo core/Router.php
[] Implementar registro de rutas (GET, POST, PUT, DELETE)
[] Implementar sistema de parámetros en rutas
[] Implementar método dispatch
[] Implementar soporte para middleware
[] Implementar manejo de rutas no encontradas (404)

Archivo index.php

[] Cargar autoloader de Composer
[] Cargar variables de entorno (.env)
[] Inicializar router
[] Registrar todas las rutas
[] Ejecutar dispatch
[] Agregar try-catch global

Commit

[X] Hacer commit: "feat: implementación de clase Database"
[X] Hacer commit: "feat: implementación de clases Request y Response"
[] Hacer commit: "feat: implementación de clase Router"
[] Hacer commit: "feat: implementación index.php como punto de acceso"
[] Push a GitHub

Entregable: Sistema core funcionando (router, database, request/response)

🛠️ FASE 5: Helpers y Utilidades
Helper: Validator

 Crear archivo helpers/Validator.php
 Implementar validación de email
 Implementar validación de URL
 Implementar validación de contraseña
 Implementar validación de alias personalizado
 Implementar validación de campos requeridos

Helper: ShortCode

 Crear archivo helpers/ShortCode.php
 Implementar generación de código aleatorio
 Implementar validación de unicidad
 Implementar lista de palabras reservadas
 Implementar validación de alias custom

Helper: functions.php

 Crear archivo helpers/functions.php
 Implementar función para sanitizar strings
 Implementar función para sanitizar URLs
 Implementar función para generar token JWT
 Implementar función para verificar token JWT
 Implementar función para hash de passwords

JsonView

 Crear archivo app/Views/JsonView.php
 Implementar método render
 Implementar método success
 Implementar método error
 Implementar método con paginación

Commit

 Hacer commit: "feat: implement validation helpers"
 Hacer commit: "feat: implement shortcode generator"
 Hacer commit: "feat: implement utility functions"
 Hacer commit: "feat: implement JsonView"
 Push a GitHub

Entregable: Helpers y utilidades completos

👤 FASE 6: Sistema de Autenticación
Modelo User

 Crear archivo app/Models/Model.php (clase base)
 Crear archivo app/Models/User.php
 Implementar método find (por ID)
 Implementar método findByEmail
 Implementar método create
 Implementar método update
 Implementar método delete
 Implementar método all
 Implementar hash de contraseña en create
 Implementar verificación de contraseña

AuthController - Registro

 Crear archivo app/Controllers/BaseController.php
 Crear archivo app/Controllers/AuthController.php
 Implementar método register
 Validar datos de entrada (nombre, email, password)
 Verificar que email no exista
 Crear usuario en base de datos
 Retornar respuesta JSON

AuthController - Login

 Implementar método login
 Validar datos de entrada (email, password)
 Verificar credenciales
 Generar token JWT
 Retornar token y datos de usuario

AuthController - Usuario Actual

 Implementar método me
 Obtener usuario desde token
 Retornar información del usuario
 Incluir estadísticas básicas (total URLs, total clicks)

AuthMiddleware

 Crear archivo app/Middleware/AuthMiddleware.php
 Verificar presencia del header Authorization
 Extraer token del header
 Validar token JWT
 Verificar que usuario existe
 Agregar usuario al request
 Manejar tokens inválidos o expirados

Rutas de Autenticación

 Registrar ruta POST /api/auth/register
 Registrar ruta POST /api/auth/login
 Registrar ruta GET /api/auth/me (con middleware)
 Probar todas las rutas con Postman/cURL

Testing de Autenticación

 Probar registro con datos válidos
 Probar registro con email duplicado
 Probar registro con datos inválidos
 Probar login con credenciales correctas
 Probar login con credenciales incorrectas
 Probar /me con token válido
 Probar /me sin token
 Probar /me con token inválido

Commit

 Hacer commit: "feat: implement User model"
 Hacer commit: "feat: implement user registration"
 Hacer commit: "feat: implement user login and JWT"
 Hacer commit: "feat: implement auth middleware"
 Push a GitHub

Entregable: Sistema de autenticación completo y funcionando

🔗 FASE 7: Funcionalidad Core - Modelo URL
Modelo Url

 Crear archivo app/Models/Url.php
 Implementar método find (por ID)
 Implementar método findByShortCode
 Implementar método findByUser (URLs de un usuario)
 Implementar método create
 Implementar método update
 Implementar método delete
 Implementar método incrementClicks
 Implementar método para verificar propiedad
 Implementar paginación en findByUser

Testing del Modelo

 Probar creación de URL anónima
 Probar creación de URL con usuario
 Probar búsqueda por short_code
 Probar búsqueda por usuario
 Probar actualización de URL
 Probar eliminación de URL
 Probar incremento de clicks

Commit

 Hacer commit: "feat: implement Url model"
 Push a GitHub

Entregable: Modelo URL completo

🎮 FASE 8: Controlador de URLs
UrlController - Acortar URL (Anónimo)

 Crear archivo app/Controllers/UrlController.php
 Implementar método shorten
 Detectar si usuario está autenticado
 Validar URL de entrada
 Generar código corto único
 Guardar en base de datos (user_id = NULL)
 Retornar respuesta con URL acortada

UrlController - Acortar URL (Registrado)

 Extender método shorten para usuarios registrados
 Permitir alias personalizado
 Validar alias (formato y unicidad)
 Guardar con user_id del usuario autenticado
 Retornar respuesta más completa

UrlController - Listar URLs

 Implementar método index
 Verificar autenticación (middleware)
 Obtener URLs del usuario autenticado
 Implementar paginación
 Ordenar por fecha de creación (más recientes primero)
 Retornar lista con metadata de paginación

UrlController - Obtener URL Específica

 Implementar método show
 Verificar autenticación
 Obtener URL por ID
 Verificar que pertenece al usuario
 Retornar información completa

UrlController - Actualizar URL

 Implementar método update
 Verificar autenticación
 Obtener URL por ID
 Verificar propiedad (que pertenece al usuario)
 Validar nuevos datos
 Actualizar en base de datos
 Retornar URL actualizada

UrlController - Eliminar URL

 Implementar método delete
 Verificar autenticación
 Obtener URL por ID
 Verificar propiedad
 Eliminar de base de datos
 Retornar confirmación

UrlController - Estadísticas

 Implementar método stats
 Verificar autenticación
 Obtener URL por ID
 Verificar propiedad
 Obtener estadísticas (clicks totales, último click, etc.)
 Si existe tabla url_clicks, obtener clicks por día
 Retornar estadísticas completas

Rutas de URLs

 Registrar ruta POST /api/shorten (pública)
 Registrar ruta GET /api/urls (protegida)
 Registrar ruta GET /api/urls/{id} (protegida)
 Registrar ruta PUT /api/urls/{id} (protegida)
 Registrar ruta DELETE /api/urls/{id} (protegida)
 Registrar ruta GET /api/urls/{id}/stats (protegida)

Testing de UrlController

 Probar acortar URL sin autenticación
 Probar acortar URL con autenticación
 Probar acortar URL con alias custom
 Probar acortar URL con alias duplicado
 Probar listar URLs del usuario
 Probar paginación
 Probar obtener URL específica
 Probar actualizar URL propia
 Probar actualizar URL de otro usuario (debe fallar)
 Probar eliminar URL propia
 Probar eliminar URL de otro usuario (debe fallar)
 Probar obtener estadísticas

Commit

 Hacer commit: "feat: implement URL shortening (anonymous)"
 Hacer commit: "feat: implement URL shortening (authenticated)"
 Hacer commit: "feat: implement URL management (list, show)"
 Hacer commit: "feat: implement URL management (update, delete)"
 Hacer commit: "feat: implement URL statistics"
 Push a GitHub

Entregable: Gestión completa de URLs funcionando

🔀 FASE 9: Sistema de Redirección
RedirectController - Redirección

 Crear archivo app/Controllers/RedirectController.php
 Implementar método redirect
 Recibir shortCode de la URL
 Buscar URL en base de datos por short_code
 Verificar que URL existe y está activa
 Incrementar contador de clicks
 Registrar información del click (opcional: IP, user agent, etc.)
 Realizar redirección 302 a URL original
 Manejar URL no encontrada (404)

RedirectController - Información

 Implementar método info
 Recibir shortCode de la URL
 Buscar URL en base de datos
 Retornar información sin incrementar clicks
 Incluir: URL original, clicks, fecha de creación
 NO incluir información sensible (user_id, etc.)

Rutas de Redirección

 Registrar ruta GET /{shortCode} (debe ser la última ruta)
 Registrar ruta GET /api/info/{shortCode}
 Asegurar que no colisione con otras rutas

Registro de Clicks (Opcional)

 Si usas tabla url_clicks, implementar registro detallado
 Capturar IP del visitante
 Capturar User Agent
 Capturar Referer
 Guardar en tabla url_clicks

Testing de Redirección

 Probar redirección con código válido
 Verificar que incrementa el contador
 Probar redirección con código inválido
 Probar info de URL válida
 Probar info de URL inválida
 Verificar que info NO incrementa clicks

Commit

 Hacer commit: "feat: implement URL redirection"
 Hacer commit: "feat: implement URL info endpoint"
 Hacer commit: "feat: implement click tracking" (si aplica)
 Push a GitHub

Entregable: Sistema de redirección completo y funcionando

🔒 FASE 10: Seguridad y Validaciones
Validaciones Generales

 Revisar validación de todos los inputs
 Implementar sanitización de datos
 Verificar uso de prepared statements en TODAS las consultas
 Agregar validación de longitud de campos
 Implementar escape de caracteres especiales

Seguridad de Contraseñas

 Verificar uso de password_hash con BCRYPT
 Verificar uso de password_verify
 Nunca retornar contraseñas en respuestas

JWT

 Verificar tiempo de expiración configurado
 Verificar secret key segura y en .env
 Implementar manejo de tokens expirados
 Implementar manejo de tokens inválidos

Headers de Seguridad

 Configurar CORS correctamente
 Agregar header Content-Type: application/json
 Agregar headers de seguridad (opcional pero recomendado)

Validación de Permisos

 Revisar que solo el dueño puede editar sus URLs
 Revisar que solo el dueño puede eliminar sus URLs
 Revisar que solo el dueño puede ver estadísticas detalladas

Manejo de Errores

 Implementar try-catch en puntos críticos
 Implementar logs de errores
 Nunca exponer información sensible en errores
 Retornar mensajes de error apropiados

Rate Limiting (Opcional)

 Implementar límite de peticiones por IP
 Implementar diferentes límites para anónimos vs registrados
 Retornar código 429 (Too Many Requests)

Testing de Seguridad

 Intentar SQL injection en todos los endpoints
 Intentar acceder a URLs de otros usuarios
 Intentar usar tokens inválidos
 Intentar usar tokens expirados
 Probar XSS en campos de texto
 Verificar que passwords no se retornan

Commit

 Hacer commit: "security: improve input validation"
 Hacer commit: "security: implement proper error handling"
 Hacer commit: "security: add rate limiting" (si aplica)
 Push a GitHub

Entregable: Aplicación segura y validada

📝 FASE 11: Documentación y Testing
README.md

 Agregar título y descripción del proyecto
 Agregar badges (si aplica)
 Listar características principales
 Listar tecnologías utilizadas
 Escribir sección de requisitos previos
 Escribir instrucciones de instalación paso a paso
 Escribir instrucciones de configuración
 Agregar ejemplos de uso básicos
 Agregar screenshots o GIFs (opcional)
 Agregar enlace a documentación de API
 Agregar sección de contribución
 Agregar licencia

Documentación de API

 Crear archivo API_DOCUMENTATION.md
 Documentar todos los endpoints de autenticación
 Documentar todos los endpoints de URLs
 Documentar endpoint de redirección
 Para cada endpoint incluir:

 Método HTTP y URL
 Descripción
 Headers necesarios
 Parámetros requeridos y opcionales
 Ejemplo de request
 Ejemplo de response exitosa
 Ejemplos de errores comunes
 Códigos de estado posibles



Colección de Postman

 Crear colección en Postman
 Agregar todos los endpoints
 Configurar variables de entorno (base_url, token)
 Agregar ejemplos de request/response
 Exportar colección JSON
 Incluir en repositorio

Archivo .env.example

 Verificar que incluye todas las variables
 Verificar que NO tiene valores sensibles
 Agregar comentarios explicativos

Comentarios en Código

 Revisar y agregar comentarios en código complejo
 Agregar docblocks en clases principales
 Agregar docblocks en métodos públicos
 Explicar lógica no obvia

Testing Manual Completo

 Crear usuario nuevo
 Login con usuario
 Acortar URL sin autenticación
 Acortar URL con autenticación
 Acortar URL con alias custom
 Listar URLs del usuario
 Ver detalles de una URL
 Actualizar una URL
 Eliminar una URL
 Ver estadísticas de URL
 Usar URL corta (redirección)
 Ver info de URL corta
 Probar todos los casos de error

Logs

 Crear archivo logs/.gitkeep
 Verificar que logs/ está en .gitignore
 Verificar que se están guardando errores

Limpieza de Código

 Eliminar código comentado innecesario
 Eliminar console.log o var_dump de debug
 Verificar indentación consistente
 Verificar nombres descriptivos de variables
 Eliminar código duplicado (refactorizar)

Commit Final

 Hacer commit: "docs: complete README documentation"
 Hacer commit: "docs: add API documentation"
 Hacer commit: "docs: add Postman collection"
 Hacer commit: "refactor: code cleanup and comments"
 Push a GitHub

Entregable: Proyecto completamente documentado

🚀 FASE 12: Deployment y Presentación
Preparación para Producción

 Revisar archivo .env para producción
 Configurar APP_ENV=production
 Configurar APP_DEBUG=false
 Usar credenciales de producción
 Cambiar JWT_SECRET por uno más seguro

Optimización

 Revisar consultas SQL (agregar índices si falta)
 Implementar caché (opcional)
 Minimizar consultas a BD donde sea posible
 Probar rendimiento

Deployment (Opcional)

 Elegir hosting (Heroku, Railway, DigitalOcean, etc.)
 Configurar base de datos remota
 Subir código a servidor
 Configurar variables de entorno
 Probar que funciona en producción
 Configurar SSL/HTTPS

Video Demo (Opcional pero recomendado)

 Grabar video mostrando funcionalidad
 Mostrar registro de usuario
 Mostrar acortamiento de URL
 Mostrar redirección funcionando
 Mostrar gestión de URLs
 Subir a YouTube o similar
 Agregar link en README

Presentación en Portafolio

 Agregar proyecto a tu sitio web/portafolio
 Incluir descripción breve
 Incluir tecnologías usadas
 Incluir screenshots
 Incluir links a GitHub y demo (si aplica)
 Destacar características principales

LinkedIn/Redes

 Publicar proyecto en LinkedIn
 Compartir experiencia de desarrollo
 Usar hashtags apropiados (#PHP #API #WebDevelopment)
 Agregar al perfil de GitHub

Commit Final

 Hacer commit: "chore: prepare for production"
 Crear tag de versión: v1.0.0
 Push con tags
 Crear release en GitHub

Entregable: Proyecto desplegado y presentado