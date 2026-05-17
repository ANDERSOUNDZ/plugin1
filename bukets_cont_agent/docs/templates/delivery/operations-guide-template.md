# Guía de Operaciones — [Nombre del Proyecto]

> **Versión del software:** [versión]
> **Fecha:** [YYYY-MM-DD]
> **Audiencia:** Operadores / Soporte técnico del cliente

---

## 1. Dashboard de Monitoreo

### Acceso al dashboard

- **URL:** [https://monitor.ejemplo.com]
- **Credenciales:** [gestión de acceso]
- **Herramienta:** [Datadog / Grafana / Sentry / etc.]

### Métricas principales a observar

| Métrica | ¿Qué indica? | Umbral normal | Alerta si |
|---------|-------------|---------------|-----------|
| [métrica] | [descripción] | [valor normal] | [valor de alerta] |
| [métrica] | [descripción] | [valor normal] | [valor de alerta] |
| [métrica] | [descripción] | [valor normal] | [valor de alerta] |

---

## 2. Alertas Configuradas

| Alerta | Condición | Canal | Acción |
|--------|-----------|-------|--------|
| [nombre alerta] | [condición] | [email/slack/sms] | [qué hacer] |
| [nombre alerta] | [condición] | [email/slack/sms] | [qué hacer] |

### Gestión de alertas

- **Silenciamiento:** [cómo silenciar una alerta temporalmente]
- **Acknowledgment:** [cómo confirmar que se está atendiendo]
- **Resolución:** [cómo marcar como resuelta]

---

## 3. Gestión de Logs

### Acceso a logs

- **Plataforma:** [ herramienta de logs]
- **URL:** [URL]
- **Consulta básica:** [ejemplo de consulta]
- **Retención:** [N] días

### Consultas útiles

```sql
-- Ejemplo: Buscar errores de las últimas 24 horas
consulta_de_ejemplo

-- Ejemplo: Buscar requests lentas
consulta_de_ejemplo
```

### Niveles de log

| Nivel | Significado | Acción requerida |
|-------|-------------|-----------------|
| ERROR | Fallo que requiere atención | Revisar y actuar |
| WARN | Comportamiento inesperado no crítico | Monitorear |
| INFO | Eventos normales del sistema | Solo referencia |
| DEBUG | Información detallada para debugging | Bajo demanda |

---

## 4. Procedimientos Operativos del Día a Día

### Inicio de turno

1. [ ] Revisar dashboard de monitoreo
2. [ ] Verificar que no hay alertas activas no atendidas
3. [ ] Revisar resumen de incidentes del turno anterior
4. [ ] Confirmar que los backups se ejecutaron correctamente

### Fin de turno

1. [ ] Documentar incidentes del turno
2. [ ] Dejar notas claras para el siguiente turno
3. [ ] Verificar que todas las alertas están atendidas o escaladas

---

## 5. Plan de Backup y Recuperación

### ¿Qué se respalda?

| Componente | Frecuencia | Retención | Método |
|-----------|-----------|-----------|--------|
| Base de datos | [cada N horas] | [N días] | [dump / snapshot] |
| Archivos subidos | [cada N horas] | [N días] | [copia a S3/Glacier] |
| Configuración | [cada cambio] | [N versiones] | [git / backup manual] |

### Procedimiento de backup

```bash
# Backup de base de datos
[comando de backup]

# Verificar integridad del backup
[comando de verificación]
```

### Procedimiento de restore

```bash
# Restaurar base de datos
[comando de restore]

# Verificar datos restaurados
[comando de verificación]
```

### Prueba de restore

**Última prueba exitosa:** [fecha]
**Frecuencia de prueba:** [cada N meses]

---

## 6. Runbook de Incidentes Comunes

### Incidente 1: Servicio caído

**Síntomas:**
- Dashboard muestra [indicador]
- Usuarios reportan [síntoma]
- Alerta [nombre] se dispara

**Pasos:**
1. Verificar estado del servicio: `[comando]`
2. Revisar logs recientes: `[comando]`
3. Intentar reinicio: `[comando]`
4. Si no se resuelve en [N] minutos, escalar a [contacto]

---

### Incidente 2: Base de datos lenta

**Síntomas:**
- Tiempos de respuesta superiores a [N] segundos
- Alertas de CPU/memoria en base de datos

**Pasos:**
1. Identificar queries lentas: `[comando]`
2. Verificar conexiones activas: `[comando]`
3. Revisar tamaño de tablas: `[comando]`
4. Aplicar índice si es necesario: `[comando]`
5. Escalar a DBA si persiste

---

### Incidente 3: Error de autenticación masivo

**Síntomas:**
- Múltiples usuarios reportan "no puedo iniciar sesión"
- Logs muestran [patrón de error]

**Pasos:**
1. Verificar servicio de autenticación: `[comando]`
2. Revisar si hay un patrón (todos los usuarios / solo nuevos)
3. Verificar vigencia de tokens/certificados
4. Consultar [proveedor de auth] si es externo

---

### Incidente 4: Alta carga inesperada

**Síntomas:**
- CPU/memoria por encima del [N]%
- Tiempos de respuesta degradados

**Pasos:**
1. Identificar origen del tráfico: `[comando]`
2. Verificar si es tráfico legítimo o ataque
3. Activar rate limiting si es necesario
4. Escalar infraestructura si es tráfico legítimo
5. Notificar a usuarios si hay degradación

---

## 7. Escalamiento

| Tipo de incidente | Primer nivel | Segundo nivel | Tiempo de escalamiento |
|------------------|-------------|--------------|----------------------|
| Caída del servicio | [equipo/nombre] | [equipo/nombre] | [N] minutos sin resolución |
| Bug crítico | [equipo/nombre] | [equipo/nombre] | [N] horas |
| Problema de seguridad | [equipo/nombre] | [equipo/nombre] | Inmediato |
| Consulta de operación | [equipo/nombre] | [equipo/nombre] | [N] horas |

### Contactos de emergencia

| Contacto | Rol | Teléfono | Email |
|----------|-----|----------|-------|
| [nombre] | [rol] | [teléfono] | [email] |

---

## 8. Mantenimiento Programado

### Ventanas de mantenimiento

- **Frecuencia:** [semanal / mensual / trimestral]
- **Horario recomendado:** [día y hora de menor uso]
- **Tiempo estimado:** [N] horas
- **Notificación previa:** [N] días de anticipación

### Procedimiento de mantenimiento

1. [ ] Notificar a usuarios con [N] días de anticipación
2. [ ] Ejecutar backup completo antes del mantenimiento
3. [ ] Poner el sistema en modo mantenimiento: `[comando]`
4. [ ] Ejecutar tareas de mantenimiento
5. [ ] Ejecutar smoke tests: `[comando]`
6. [ ] Salir de modo mantenimiento: `[comando]`
7. [ ] Verificar que todo funciona correctamente
8. [ ] Notificar a usuarios que el sistema está disponible
