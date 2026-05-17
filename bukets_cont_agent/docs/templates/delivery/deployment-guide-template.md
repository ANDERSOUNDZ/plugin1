# Guía de Despliegue — [Nombre del Proyecto]

> **Versión del software:** [versión]
> **Fecha:** [YYYY-MM-DD]
> **Audiencia:** DevOps / Administradores de sistemas del cliente

---

## 1. Requisitos de Infraestructura

### Mínimos recomendados

| Recurso | Especificación |
|---------|---------------|
| CPU | [N] cores |
| RAM | [N] GB |
| Disco | [N] GB SSD |
| Sistema operativo | [Linux Ubuntu 22.04 / etc.] |
| Conexión a internet | [velocidad] |
| Dominio | [nombre de dominio requerido] |
| SSL/TLS | Certificado para [dominio] |

### Dependencias externas

| Servicio | Versión | ¿Gestionado por el cliente? | Notas |
|----------|---------|---------------------------|-------|
| [Base de datos] | [versión] | Sí / No | [notas] |
| [Cache] | [versión] | Sí / No | [notas] |
| [Servicio externo] | [versión] | Sí / No | [notas] |

---

## 2. Variables de Entorno

| Variable | Descripción | Ejemplo | Obligatoria |
|----------|-------------|---------|-------------|
| `DATABASE_URL` | URL de conexión a la base de datos | `postgres://user:pass@host:5432/db` | Sí |
| `JWT_SECRET` | Secreto para firmar tokens JWT | [generado automáticamente] | Sí |
| `API_PORT` | Puerto del servidor API | `3000` | No (default: 3000) |
| `LOG_LEVEL` | Nivel de logging | `info` | No |
| `CORS_ORIGINS` | Orígenes permitidos para CORS | `https://app.ejemplo.com` | Sí |
| `SENTRY_DSN` | DSN de Sentry para monitoreo de errores | `https://...@sentry.io/...` | No |

---

## 3. Despliegue Inicial

### Opción 1: Despliegue manual

```bash
# 1. Clonar el repositorio
git clone [url-del-repositorio]
cd [nombre-del-proyecto]

# 2. Configurar variables de entorno
cp .env.example .env
# Editar .env con los valores correctos

# 3. Instalar dependencias
npm install  # o: pip install, bundle install, etc.

# 4. Ejecutar migraciones de base de datos
npm run migrate  # o: rails db:migrate, alembic upgrade head

# 5. Iniciar la aplicación
npm start  # o: rails server, uvicorn main:app
```

### Opción 2: Despliegue con Docker

```bash
# 1. Construir la imagen
docker build -t [nombre-imagen]:[tag] .

# 2. Iniciar con docker-compose
docker-compose up -d
```

### Opción 3: Despliegue con CI/CD (recomendado)

[Descripción del pipeline automático]

---

## 4. Pipeline CI/CD

### Disparadores

| Evento | Acción |
|--------|--------|
| Push a `main` | Despliegue automático a producción |
| Push a `staging` | Despliegue automático a staging |
| Pull request | Tests + lint, sin despliegue |

### Pasos del pipeline

```
[Push] → [Lint] → [Unit Tests] → [Integration Tests]
      → [Build] → [Deploy Staging] → [E2E en Staging]
      → [Aprobación manual] → [Deploy Production]
      → [Smoke Tests] → [Monitoreo Activo]
```

### Ver pipeline actual

[URL del pipeline CI/CD o archivo de configuración]

---

## 5. Estrategia de Despliegue

**Tipo:** [Blue-Green / Rolling / Canary / Recreación]

[Descripción de cómo funciona la estrategia elegida]

---

## 6. Plan de Rollback

### Condiciones de activación

[Qué métricas o eventos disparan un rollback]

### Procedimiento

```bash
# Paso 1: Identificar la versión anterior estable
# Última versión estable: [versión]

# Paso 2: Ejecutar rollback
[comando de rollback]

# Paso 3: Verificar que la versión anterior está activa
[comando de verificación]

# Paso 4: Ejecutar smoke tests
[comando de smoke tests]
```

**Responsable:** [nombre o rol]
**Tiempo estimado:** [N] minutos
**Comunicación:** [a quién notificar]

---

## 7. Verificación Post-Despliegue

### Smoke tests automáticos

```bash
# Verificar que la aplicación responde
curl -f https://[dominio]/health

# Verificar base de datos
curl -f https://[dominio]/health/database

# Verificar servicios externos
curl -f https://[dominio]/health/external-services
```

### Checklist de verificación manual

- [ ] La aplicación carga correctamente en el navegador
- [ ] El login funciona con credenciales de prueba
- [ ] Las funcionalidades críticas del MVP operan normalmente
- [ ] Las métricas de monitoreo muestran el nuevo despliegue activo
- [ ] Los logs no muestran errores nuevos
- [ ] La base de datos está accesible y las migraciones se ejecutaron

---

## 8. Mantenimiento

### Actualización de dependencias

```bash
# Ver dependencias desactualizadas
npm outdated  # o: pip list --outdated

# Actualizar
npm update    # o: pip install --upgrade
```

### Renovación de certificados SSL

[Procedimiento para renovar certificados SSL]

### Limpieza de logs y datos temporales

[Procedimiento de limpieza]

---

## 9. Troubleshooting de Despliegue

| Problema | Causa probable | Solución |
|----------|---------------|----------|
| [problema] | [causa] | [solución] |
| [problema] | [causa] | [solución] |
