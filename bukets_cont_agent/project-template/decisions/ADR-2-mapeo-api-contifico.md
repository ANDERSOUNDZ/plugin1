# ADR #2 — Mapeo de API Contifico

**Fecha:** 2026-05-17
**Estado:** Aceptada

---

## Contexto

La API de Contifico v2 tiene endpoints especificos para gestionar productos, categorias, bodegas, personas, documentos (facturacion SRI), cobros y movimientos de inventario. Basado en la documentacion `API_GUIDE.md` del usuario y pruebas en vivo realizadas, debemos mapear cada operacion del plugin a un endpoint de Contifico.

## Decision

Usar la API REST de Contifico v2 con autenticacion via header `Authorization: API_KEY`. Cada operacion del plugin se mapea a un endpoint especifico:

| Operacion | Endpoint Contifico | Metodo HTTP |
|-----------|-------------------|-------------|
| Test conexion | `/api/v2/producto/?result_size=1` | GET |
| Listar categorias | `/api/v2/categoria/` | GET |
| Listar productos | `/api/v2/producto/?page=N&result_size=N` | GET |
| Stock por bodega | `/api/v2/producto/{id}/stock/` | GET |
| Crear movimiento inventario | `/api/v2/movimiento-inventario/` | POST |
| Crear documento (factura) | `/api/v2/documento/` | POST |
| Estado documento SRI | `/api/v2/documento/estado/{id}` | GET |
| Registrar cobro | `/api/v2/documento/{id}/cobro/` | POST |
| Buscar persona | `/api/v2/persona/?search=` | GET |
| Crear persona | `/api/v2/persona/?pos={token}` | POST |

### Formateo de fechas (segun API_GUIDE.md)
- Documentos: `dd/mm/aaaa`
- Consultas (query params): `AAAA-MM-DD`
- Productos (fecha_creacion): `dd/mm/aaaa`

### Datos verificados en vivo
- **API Key**: Funciona (probada exitosamente)
- **Categoria Bukets**: `N5wboyBV8T3WexEO` — "Productos Bukets Test"
- **Bodega Bukets**: `Q9pdBBgwDS52d8KE` — "Bodega Bukets Test"
- **Producto BUK001**: `0pZeVAZA4EtoQdGW` — Stock 87

## Alternativas consideradas

- **API Siigo Colombia**: No compatible con SRI Ecuador (usa DIAN colombiana).
- **Integracion directa via SQL/ODBC**: Contifico no ofrece acceso directo a BD.

## Consecuencias

### Positivas
- API REST estandar, facil de implementar con `wp_remote_request()`
- Documentacion disponible (API_GUIDE.md) y probada en vivo
- Puerto/Interface definido permite cambiar de implementacion sin tocar el Core

### Negativas
- Dependencia externa de la disponibilidad de la API de Contifico
- Sin SLA formal documentado
- Rate limit no documentado

### Riesgos
- Cambios en la API de Contifico sin previo aviso (mitigado via Puerto/Interface)

## Revisable cuando
Contifico publique una nueva version de su API o cambie los endpoints actuales.
