<?php

namespace Database\Seeders;

use App\Models\Encuesta;
use App\Models\Colonia;
use Illuminate\Database\Seeder;

class EncuestasTestSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener colonias de distrito 20 y 5
        $colonias20 = Colonia::where('distrito', 20)->get();
        $colonias5 = Colonia::where('distrito', 5)->get();

        $generos = ['Masculino', 'Femenino', 'Otro'];
        $edades = [22, 28, 35, 42, 55, 31, 45, 26, 38, 50];
        $niveles = ['Primaria', 'Secundaria', 'Preparatoria', 'Universidad', 'Posgrado'];
        $estados = ['Soltero', 'Casado', 'Divorciado', 'Viudo', 'Unión libre'];

        // Crear encuestas para distrito 20
        foreach ($colonias20->take(3) as $colonia) {
            for ($i = 0; $i < 5; $i++) {
                Encuesta::create([
                    'colonia_id' => $colonia->id,
                    'genero' => $generos[array_rand($generos)],
                    'edad' => $edades[array_rand($edades)],
                    'nivel_educativo' => $niveles[array_rand($niveles)],
                    'estado_civil' => $estados[array_rand($estados)],
                    'obras_calificadas' => [
                        '1' => rand(1, 5),
                        '2' => rand(1, 5),
                        '3' => rand(1, 5)
                    ],
                    'desea_reporte' => rand(0, 1),
                    // Campos de seguridad
                    'servicio_seguridad' => ['Excelente', 'Bueno', 'Regular', 'Malo'][array_rand(['Excelente', 'Bueno', 'Regular', 'Malo'])],
                    'confia_policia' => ['Mucho', 'Poco', 'Nada'][array_rand(['Mucho', 'Poco', 'Nada'])],
                    'horario_inseguro' => ['Madrugada', 'Noche', 'Tarde', 'Mañana'][array_rand(['Madrugada', 'Noche', 'Tarde', 'Mañana'])],
                    'emergencia_transporte' => rand(1, 10),
                    'caminar_noche' => rand(1, 10),
                    'hijos_solos' => rand(1, 10),
                    'transporte_publico' => rand(1, 10),
                ]);
            }
        }

        // Crear encuestas para distrito 5
        foreach ($colonias5->take(3) as $colonia) {
            for ($i = 0; $i < 5; $i++) {
                Encuesta::create([
                    'colonia_id' => $colonia->id,
                    'genero' => $generos[array_rand($generos)],
                    'edad' => $edades[array_rand($edades)],
                    'nivel_educativo' => $niveles[array_rand($niveles)],
                    'estado_civil' => $estados[array_rand($estados)],
                    'obras_calificadas' => [
                        '4' => rand(1, 5),
                        '5' => rand(1, 5),
                        '6' => rand(1, 5)
                    ],
                    'desea_reporte' => rand(0, 1),
                    // Campos de seguridad
                    'servicio_seguridad' => ['Excelente', 'Bueno', 'Regular', 'Malo'][array_rand(['Excelente', 'Bueno', 'Regular', 'Malo'])],
                    'confia_policia' => ['Mucho', 'Poco', 'Nada'][array_rand(['Mucho', 'Poco', 'Nada'])],
                    'horario_inseguro' => ['Madrugada', 'Noche', 'Tarde', 'Mañana'][array_rand(['Madrugada', 'Noche', 'Tarde', 'Mañana'])],
                    'emergencia_transporte' => rand(1, 10),
                    'caminar_noche' => rand(1, 10),
                    'hijos_solos' => rand(1, 10),
                    'transporte_publico' => rand(1, 10),
                ]);
            }
        }
    }
}
