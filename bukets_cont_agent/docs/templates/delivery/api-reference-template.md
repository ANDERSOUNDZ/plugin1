# API Reference — [Nombre del Proyecto]

> **Versión:** [versión de la API]
> **Fecha:** [YYYY-MM-DD]
> **Base URL:** `[https://api.ejemplo.com/v1]`
> **Audiencia:** Desarrolladores que integran con el sistema
>
> 📝 **¿Cómo se genera este documento?**
> El agente SDLC lo construye durante las Fases 2, 3 y 7.
> En Fase 2 crea los contratos de API (diseño). En Fase 3 los actualiza
> con los endpoints reales implementados. En Fase 7 valida contra el código.
>
> ✅ **¿Qué debe cumplir para estar completo?**
> - Cada endpoint debe tener request y response de ejemplo
> - Los códigos de error deben estar documentados
> - Los ejemplos deben poder copiarse y ejecutarse (curl o similar)

---

## 1. Introducción

[Descripción de la API, qué recursos expone, para qué sirve.]

---

## 2. Autenticación

### Método de autenticación

[Tipo de autenticación: Bearer Token / API Key / JWT / OAuth2]

```
Authorization: Bearer [token]
```

### Cómo obtener un token

[Instrucciones para obtener credenciales de API]

### Ejemplo de autenticación

```
curl -X POST https://api.ejemplo.com/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email": "usuario@ejemplo.com", "password": "********"}'
```

---

## 3. Endpoints

### 3.1 [Recurso 1]

#### `GET /[recurso]`

Listar [recurso]s.

**Parámetros:**

| Nombre | Tipo | Ubicación | Obligatorio | Descripción |
|--------|------|-----------|-------------|-------------|
| page | integer | query | No | Número de página (default: 1) |
| limit | integer | query | No | Elementos por página (default: 20, max: 100) |
| search | string | query | No | Término de búsqueda |

**Response 200:**

```json
{
  "data": [
    {
      "id": "uuid",
      "nombre": "ejemplo",
      "created_at": "2026-01-01T00:00:00Z"
    }
  ],
  "pagination": {
    "page": 1,
    "limit": 20,
    "total": 100
  }
}
```

**Response 401:**

```json
{
  "error": "UNAUTHORIZED",
  "message": "Token inválido o expirado"
}
```

---

#### `POST /[recurso]`

Crear un nuevo [recurso].

**Request body:**

```json
{
  "nombre": "string (requerido)",
  "descripcion": "string (opcional)",
  "estado": "string (requerido, valores: activo|inactivo)"
}
```

**Response 201:**

```json
{
  "id": "uuid",
  "nombre": "ejemplo",
  "estado": "activo",
  "created_at": "2026-01-01T00:00:00Z"
}
```

**Response 400:**

```json
{
  "error": "VALIDATION_ERROR",
  "message": "El campo 'nombre' es obligatorio",
  "details": [
    {
      "field": "nombre",
      "message": "Este campo es obligatorio"
    }
  ]
}
```

---

#### `GET /[recurso]/{id}`

Obtener un [recurso] por ID.

**Parámetros:**

| Nombre | Tipo | Ubicación | Obligatorio | Descripción |
|--------|------|-----------|-------------|-------------|
| id | UUID | path | Sí | ID del recurso |

**Response 200:**

```json
{
  "id": "uuid",
  "nombre": "ejemplo",
  "descripcion": "descripción",
  "estado": "activo",
  "created_at": "2026-01-01T00:00:00Z",
  "updated_at": "2026-01-01T00:00:00Z"
}
```

**Response 404:**

```json
{
  "error": "NOT_FOUND",
  "message": "Recurso con id 'uuid' no encontrado"
}
```

---

#### `PUT /[recurso]/{id}`

Actualizar un [recurso] existente.

**Request body:** (mismos campos que POST, todos opcionales en PUT)

```json
{
  "nombre": "nuevo nombre",
  "estado": "inactivo"
}
```

**Response 200:**

```json
{
  "id": "uuid",
  "nombre": "nuevo nombre",
  "estado": "inactivo",
  "updated_at": "2026-01-01T00:00:00Z"
}
```

---

#### `DELETE /[recurso]/{id}`

Eliminar un [recurso]. (Eliminación lógica / física)

**Response 204:** (sin contenido)

**Response 404:**

```json
{
  "error": "NOT_FOUND",
  "message": "Recurso no encontrado"
}
```

---

### 3.2 [Recurso 2]

[mismo formato que 3.1]

---

### 3.3 [Recurso 3]

[mismo formato que 3.1]

---

## 4. Modelos de Datos (Schemas)

### [Modelo 1]

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | UUID (string) | Identificador único |
| nombre | string | Nombre del recurso |
| estado | enum (activo, inactivo) | Estado actual |
| created_at | datetime (ISO 8601) | Fecha de creación |
| updated_at | datetime (ISO 8601) | Fecha de última actualización |

### [Modelo 2]

[mismo formato]

---

## 5. Códigos de Error

| Código | HTTP Status | Descripción |
|--------|-------------|-------------|
| VALIDATION_ERROR | 400 | Error de validación de datos |
| UNAUTHORIZED | 401 | Token faltante, inválido o expirado |
| FORBIDDEN | 403 | No tiene permisos para esta acción |
| NOT_FOUND | 404 | Recurso no encontrado |
| CONFLICT | 409 | Conflicto con el estado actual |
| RATE_LIMIT | 429 | Demasiadas solicitudes |
| INTERNAL_ERROR | 500 | Error interno del servidor |

---

## 6. Rate Limiting

- **Límite:** [N] solicitudes por minuto
- **Headers de rate limit:**
  - `X-RateLimit-Limit`: [N]
  - `X-RateLimit-Remaining`: [N]
  - `X-RateLimit-Reset`: [timestamp]

---

## 7. Ejemplos de Flujos Completos

### Flujo: [nombre del flujo de negocio]

```
Paso 1: Autenticarse
  POST /auth/login

Paso 2: Listar [recurso]
  GET /[recurso]

Paso 3: Crear [recurso relacionado]
  POST /[recurso-relacionado]

Paso 4: Verificar resultado
  GET /[recurso]/[id]
```

**Código ejemplo (curl):**
```bash
# Paso 1: Login
TOKEN=$(curl -s -X POST https://api.ejemplo.com/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@test.com","password":"pass123"}' | jq -r '.token')

# Paso 2: Listar recursos
curl -s -H "Authorization: Bearer $TOKEN" \
  https://api.ejemplo.com/v1/recursos
```

---

## 8. Cambios entre versiones

| Versión | Fecha | Cambios |
|---------|-------|---------|
| v1.0 | [fecha] | Versión inicial |
| v1.1 | [fecha] | [cambios] |
