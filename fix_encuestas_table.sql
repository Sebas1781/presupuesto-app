-- =====================================================================
-- Script SQL para corregir errores en la tabla encuestas
-- Base de datos: presupuesto
-- Fecha: 2026-02-07
-- =====================================================================
-- IMPORTANTE: Ejecutar en producción ANTES de hacer push del nuevo código
-- =====================================================================

-- Seleccionar la base de datos
USE presupuesto;

-- 1. Hacer que la columna obras_calificadas permita valores NULL
--    Motivo: Necesario para colonias sin obras públicas
--    El código ya maneja el caso NULL pero la BD no lo permitía
ALTER TABLE `encuestas` MODIFY COLUMN `obras_calificadas` JSON NULL;

-- 2. Cambiar el tipo de la columna edad de INT a VARCHAR
--    Motivo: La columna fue creada como INT por error en la migración original
--    pero debe ser VARCHAR para guardar textos como "De 50 a 59 años"
ALTER TABLE `encuestas` MODIFY COLUMN `edad` VARCHAR(255) NOT NULL;

-- Verificar que los cambios se aplicaron correctamente
DESCRIBE encuestas;

-- Mensaje de confirmación
SELECT 'Cambios aplicados correctamente' AS resultado;
