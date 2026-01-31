# Aplicación de Encuesta para Presupuesto Participativo 2026

## Descripción
Aplicación web desarrollada en Laravel para recopilar opiniones ciudadanas sobre el presupuesto participativo 2026. Incluye formulario público para encuestas y panel administrativo para ver y exportar resultados.

## Características

### Funcionalidades Públicas
- **Landing Page**: Página de inicio con información sobre la encuesta
- **Formulario de Encuesta**: Formulario completo con:
  - Datos sociodemográficos
  - Calificación de obras públicas por colonia
  - Propuestas ciudadanas (hasta 3)
  - Reportes anónimos con evidencia fotográfica
- **Página de Éxito**: Confirmación de envío exitoso

### Panel Administrativo
- **Dashboard**: Estadísticas generales y gráficos
- **Lista de Encuestas**: Vista filtrable y exportable
- **Vista Detallada**: Información completa de cada encuesta
- **Exportación a Excel**: Datos completos en formato Excel

## Tecnologías Utilizadas
- **Backend**: Laravel 12.49.0
- **Base de Datos**: SQLite
- **Autenticación**: Laravel Breeze
- **Frontend**: Tailwind CSS + Alpine.js
- **Panel Admin**: AdminLTE
- **Assets**: Vite
- **Exportación**: Maatwebsite/Excel

## Instalación y Configuración

### Prerrequisitos
- PHP 8.2 o superior
- Composer
- Node.js y npm

### Pasos de Instalación

1. **Clonar y configurar el proyecto**:
```bash
cd presupuesto-app
composer install
npm install
```

2. **Configurar la base de datos**:
```bash
php artisan migrate:fresh --seed
```

3. **Crear usuario administrador**:
```bash
php artisan db:seed --class=AdminSeeder
```

4. **Compilar assets**:
```bash
npm run dev  # Para desarrollo
# o
npm run build  # Para producción
```

5. **Iniciar el servidor**:
```bash
php artisan serve
```

## Credenciales de Acceso

### Usuario Administrador
- **Email**: admin@admin.com
- **Contraseña**: admin123

## URLs de la Aplicación

### Rutas Públicas
- **Inicio**: `http://127.0.0.1:8000/`
- **Formulario**: `http://127.0.0.1:8000/encuesta/crear`

### Panel Administrativo
- **Login**: `http://127.0.0.1:8000/login`
- **Dashboard**: `http://127.0.0.1:8000/admin/dashboard`
- **Encuestas**: `http://127.0.0.1:8000/admin/encuestas`
- **Exportar**: `http://127.0.0.1:8000/admin/export/encuestas`

## Estructura de la Base de Datos

### Tablas Principales
1. **colonias**: Catálogo de colonias/sectores
2. **obras_publicas**: Obras públicas por colonia
3. **encuestas**: Datos sociodemográficos de participantes
4. **propuestas**: Propuestas ciudadanas (máximo 3 por encuesta)
5. **reportes**: Reportes anónimos con evidencia

### Relaciones
- Una colonia tiene muchas obras públicas
- Una encuesta pertenece a una colonia
- Una encuesta puede tener múltiples propuestas y reportes

## Datos Precargados

### Colonias y Sectores
La aplicación incluye las siguientes colonias organizadas por sectores:

**Sector Norte**:
- Antorcha Popular, Azteca, Benito Juárez, Centro Norte, Colinas del Oriente
- División del Norte, El Nogal, El Porvenir, El Progreso, El Refugio, Emiliano Zapata
- Francisco I. Madero, Francisco Villa, Guerrero, Hidalgo Norte, Independencia
- Insurgentes Norte, José María Morelos, La Esperanza, Las Flores, Los Pinos
- Lomas de San Juan, Morelos, Nueva Esperanza, Revolución, Rincón de las Flores
- San Juan Norte, Santa Teresa, Universidad Norte, Valle Verde, Villas del Norte

**Sector Sur**:
- Aeropuerto, Ampliación Los Pinos, Burócratas, Centro Sur, CNOP
- Del Maestro, El Mirador, El Sol, FOVISSSTE, Guadalupe Victoria
- Hidalgo Sur, INFONAVIT, Insurgentes Sur, Jardines de San Juan, La Loma
- La Mesa, La Primavera, Las Américas, Las Brisas, Las Palmas, Los Cipreses
- Los Olivos, Nueva Rosita, Obrera, San Isidro, San Juan Sur, Santa Elena
- Santa Rosa, Siete de Noviembre, Universidad Sur, Villas del Sur, Vista Hermosa

**Sector Este**:
- Arboledas, Bella Vista, Bosques del Valle, Campestre, Del Valle
- El Encanto, El Rosario, Fuentes del Valle, Industrial Este, Jardines del Este
- La Hacienda, Las Quintas, Lomas del Este, Los Álamos, Los Nogales
- Nueva Aurora, Palma Real, Praderas del Este, Residencial Este, Rinconada del Este
- San Antonio Este, San Carlos, San Fernando, San Rafael, Valle del Sol
- Villa Florida, Villa Real, Villas del Campo, Vista del Valle, Zona Industrial Este

**Sector Oeste**:
- Alameda, Buenos Aires, El Carmen, El Paraíso, Flores Magón
- Industrial Oeste, Jardines de Occidente, La Cañada, La Joya, La Libertad
- Las Huertas, Las Torres, Lomas del Oeste, Los Laureles, Los Remedios
- Miraflores, Nueva Esperanza Oeste, Praderas del Oeste, Pueblo Nuevo, San Antonio Oeste
- San Luis, Santa María, Tierra y Libertad, Valle del Oeste, Villa Hermosa
- Villa de las Flores, Villas de Occidente, Vista Alegre, Zona Centro Oeste, Zona Dorada

### Obras Públicas
Cada colonia tiene obras públicas predefinidas como:
- Alumbrado público, Bacheo, Drenaje, Pavimentación
- Parques y jardines, Agua potable, Banquetas, Canchas deportivas
- Mercados, Centros de salud, Escuelas, Bibliotecas, etc.

## Funcionalidades del Panel Admin

### Dashboard
- Contadores de encuestas, propuestas y reportes
- Gráfico de encuestas por colonia
- Tabla de encuestas recientes
- Estadísticas en tiempo real

### Gestión de Encuestas
- Lista paginada con filtros por colonia, género y fechas
- Vista detallada con toda la información
- Exportación completa a Excel
- Búsqueda y ordenamiento

### Exportación de Datos
El archivo Excel incluye:
- Datos sociodemográficos completos
- Calificaciones de obras públicas
- Propuestas detalladas con ubicaciones
- Reportes anónimos
- Rutas de archivos adjuntos

## Archivos Importantes

### Modelos
- `app/Models/Colonia.php`
- `app/Models/ObraPublica.php`
- `app/Models/Encuesta.php`
- `app/Models/Propuesta.php`
- `app/Models/Reporte.php`

### Controladores
- `app/Http/Controllers/HomeController.php`
- `app/Http/Controllers/EncuestaController.php`
- `app/Http/Controllers/Admin/DashboardController.php`
- `app/Http/Controllers/Admin/ExportController.php`

### Vistas
- `resources/views/welcome.blade.php` (Landing)
- `resources/views/encuesta/create.blade.php` (Formulario)
- `resources/views/admin/dashboard.blade.php` (Panel Admin)
- `resources/views/admin/encuestas/index.blade.php` (Lista)
- `resources/views/admin/encuestas/show.blade.php` (Detalle)

### Migraciones
- `database/migrations/*_create_*_table.php`

### Seeders
- `database/seeders/ColoniasSeeder.php`
- `database/seeders/ObrasPublicasSeeder.php`
- `database/seeders/AdminSeeder.php`

## Próximos Pasos

1. **Personalización**: Adaptar colores, logos e información específica
2. **Validaciones**: Agregar validaciones adicionales según necesidades
3. **Reportes**: Crear reportes estadísticos más detallados
4. **Notificaciones**: Sistema de notificaciones para nuevas encuestas
5. **Backup**: Configurar respaldos automáticos de la base de datos

## Soporte

Para cualquier duda o modificación, contactar al desarrollador o revisar la documentación de Laravel en https://laravel.com/docs
