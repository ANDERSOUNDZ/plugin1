# Guía de Administración — [Nombre del Proyecto]

> **Versión del software:** [versión]
> **Fecha:** [YYYY-MM-DD]
> **Audiencia:** Administradores del sistema (lado cliente)

---

## 1. Gestión de Usuarios y Roles

### Roles del sistema

| Rol | Permisos | ¿A quién se asigna? |
|-----|----------|-------------------|
| [Admin] | [lista de permisos] | [descripción] |
| [Editor] | [lista de permisos] | [descripción] |
| [Usuario] | [lista de permisos] | [descripción] |

### Crear un nuevo usuario

1. Acceder a [sección de administración]
2. Completar [formulario con campos]
3. Asignar rol
4. El usuario recibirá un email de invitación

### Modificar permisos de un usuario

1. Buscar usuario en [sección]
2. Editar rol
3. Guardar cambios (los cambios aplican inmediatamente / al próximo login)

### Bloquear / Eliminar usuario

1. Buscar usuario en [sección]
2. Seleccionar "Bloquear" o "Eliminar"
3. Confirmar acción

---

## 2. Configuración del Sistema

### Parámetros editables

| Parámetro | Descripción | Valor actual | ¿Requiere reinicio? |
|-----------|-------------|-------------|-------------------|
| [parámetro] | [descripción] | [valor] | Sí / No |
| [parámetro] | [descripción] | [valor] | Sí / No |

### Cómo modificar la configuración

1. Acceder a [sección de configuración]
2. Localizar el parámetro a modificar
3. Ingresar el nuevo valor
4. Guardar cambios

---

## 3. Mantenimiento Programado

### Ventana de mantenimiento recomendada

- **Día:** [ej: Domingo]
- **Horario:** [ej: 02:00 - 04:00 AM]
- **Frecuencia:** [semanal / mensual]
- **Duración estimada:** [N] horas

### Procedimiento

1. [ ] Notificar a usuarios con al menos [N] días de anticipación
2. [ ] Ejecutar backup completo (ver sección 4)
3. [ ] Poner el sistema en modo mantenimiento
4. [ ] Ejecutar tareas programadas
5. [ ] Verificar integridad post-mantenimiento
6. [ ] Salir de modo mantenimiento
7. [ ] Notificar que el sistema está disponible

---

## 4. Backup y Restore

### Backup automático

| Componente | Frecuencia | Hora | Retención | Destino |
|-----------|-----------|------|-----------|---------|
| Base de datos | [diaria] | [03:00] | [30 días] | [S3 / disco] |
| Archivos | [diaria] | [03:30] | [30 días] | [S3 / disco] |
| Configuración | [semanal] | [domingo 04:00] | [3 meses] | [git / backup] |

### Verificar estado del backup

```bash
# Comando para verificar último backup exitoso
[comando de verificación]

# Comando para listar backups disponibles
[comando de listado]
```

### Restaurar desde backup

```bash
# Paso 1: Identificar el backup a restaurar
[comando]

# Paso 2: Ejecutar restore
[comando de restore]

# Paso 3: Verificar datos restaurados
[comando de verificación]
```

---

## 5. Gestión de Logs

### Acceso a logs

- **Plataforma:** [herramienta]
- **URL:** [URL]
- **Credenciales:** [gestión de acceso]

### Consultas útiles para administradores

```sql
-- Ejemplo: actividad de usuarios en las últimas 24h
[consulta]

-- Ejemplo: errores de autenticación
[consulta]
```

### Política de retención

| Tipo de log | Retención | Acción al vencer |
|------------|-----------|-----------------|
| [tipo] | [N días] | [archivar / eliminar] |
| [tipo] | [N días] | [archivar / eliminar] |

---

## 6. Facturación y Límites (si aplica)

### Planes disponibles

| Plan | Precio | Usuarios | Almacenamiento | Funcionalidades |
|------|--------|----------|---------------|-----------------|
| [plan] | [$] | [N] | [N GB] | [lista] |

### Gestión de suscripciones

1. Acceder a [sección de facturación]
2. Ver plan actual
3. Cambiar de plan / cancelar suscripción

### Límites del sistema

| Recurso | Límite | ¿Qué pasa al alcanzarlo? |
|---------|--------|--------------------------|
| [recurso] | [límite] | [comportamiento] |

---

## 7. Troubleshooting Administrativo

| Problema | Causa probable | Solución |
|----------|---------------|----------|
| No puedo crear usuarios | [causa] | [solución] |
| El backup falla | [causa] | [solución] |
| [problema] | [causa] | [solución] |

---

## 8. Referencias

- [Guía de Despliegue → `deployment-guide.md`]
- [Guía de Operaciones → `operations-guide.md`]
- [Documentación de Seguridad → `security-compliance.md`]
