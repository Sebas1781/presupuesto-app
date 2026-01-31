<?php

namespace Database\Seeders;

use App\Models\Colonia;
use App\Models\ObraPublica;
use Illuminate\Database\Seeder;

class ColoniasObrasSeeder extends Seeder
{
    public function run(): void
    {
        // Lista completa de colonias proporcionada por el usuario
        $todasLasColonias = [
            'PUEBLO SAN JUAN PUEBLO NUEVO',
            'PUEBLO SAN LUCAS XOLOX, COLONIA EJIDAL',
            'PUEBLO SAN LUCAS XOLOX, COLONIA CENTRO',
            'PUEBLO REYES ACOZAC, COLONIA CENTRO',
            'PUEBLO REYES ACOZAC, BARRIO SANTO TOMÁS',
            'PUEBLO REYES ACOZAC, BARRIO LA PALMA',
            'PUEBLO SANTA MARÍA ACOLPAN',
            'PUEBLO SANTO DOMINGO ACOLPAN',
            'PUEBLO SAN PEDRO POZOTHUACAN',
            'COLONIA AMPLIACIÓN SANTO DOMINGO PUEBLO SAN JERÓNIMO XONACAHUACÁN',
            'PUEBLO SAN JERÓNIMO XONACAHUACÁN',
            'FRACC RANCHO LA CAPILLA',
            'TECAMAC CENTRO',
            'COLONIA ISIDRO FABELA',
            'COLONIA LA NOPALERA',
            'FRACC GALAXIAS EL LLANO, PASEOS DE TECAMAC Y RANCHO LA LUZ',
            'COLONIA EJIDAL',
            'FRACC REAL DE SAN GERMÁN DEL BOSQUE',
            'COLONIA SAN JOSÉ EJIDOS DE TECAMAC',
            'COLONIA EJIDOS DE TECAMAC',
            'COLONIA 6 DE MAYO',
            'COLONIA SAN JOSÉ HUEYOTETENCO',
            'COLONIA SAN MARTÍN AZCATEPEC',
            'PUEBLO SAN PABLO TECALCO',
            'FRACC REAL DE TECALCO',
            'FRACC VILLA DEL REAL 1ERA Y 2DA SECCIÓN',
            'COLONIA LOS OLIVOS',
            'FRACC VILLA DEL REAL 3RA A 4TA SECCIÓN',
            'CONJUNTO URBANO LAS FLORES',
            'VISTA HERMOSA',
            'LOMAS DE OZUMBILLA',
            'CONJUNTO URBANO EL TEJOCOTE JIQUIPILCO',
            'PUEBLO SANTA MARÍA OZUMBILLA',
            'COLONIA AMPLIACIÓN OZUMBILLA',
            'COLONIA LOMAS DE SAN PEDRO (ATZOMPA)',
            'FRACC PROVENZAL DEL BOSQUE',
            'PUEBLO SAN PEDRO ATZOMPA',
            'HACIENDA OJO DE AGUA',
            'EL GALÁN',
            'COLONIA SANTA CRUZ TECAMAC',
            'COLONIA LOMA BONITA',
            'COLONIA LA ESMERALDA',
            'COLONIA AMPLIACIÓN ESMERALDA',
            'COLONIA LOMAS DE TECAMAC',
            'CONJUNTO URBANO LOS HÉROES SAN PABLO',
            'OZUMBILLA',
            'FRACC JARDINES OJO DE AGUA',
            'FRACC REAL TOSCANA',
            'FRACC REAL DEL SOL',
            'FRACC REAL FIRENZE',
            'COLONIA CERRITOS',
            'FRACC VALLE SAN PEDRO URBI',
            'CONJUNTO URBANO EL BOSQUE',
            'FRACC REAL VERONA',
            'FRACC HÉROES OZUMBILLA',
            'FRACC PUNTA PALERMO',
            'FRACC REAL DEL CID',
            'FRACC HÉROES TECAMAC SECCIÓN FLORES, MARGARITO F AYALA',
            'FRACC HÉROES TECAMAC SECCIÓN FLORES',
            'FRACC HÉROES TECAMAC SECCIÓN CUMBRES',
            'FRACC HÉROES TECAMAC SECCIÓN BOSQUES',
            'FRACC HÉROES TECAMAC SEXTA SECCIÓN',
            'FRACC REAL CASTELL'
        ];

        // Colonias que SÍ tienen obras públicas disponibles
        $coloniasConObras = [
            'PUEBLO SAN LUCAS XOLOX, COLONIA EJIDAL' => [
                'REHABILITACIÓN DE PAVIMENTO',
                'PAVIMENTACIÓN',
                'MEJORAS EN INFRAESTRUCTURA'
            ],
            'PUEBLO SAN LUCAS XOLOX, COLONIA CENTRO' => [
                'PAVIMENTACIÓN CALLE AMEYAL I',
                'CONSTRUCCIÓN DE PLANTA DE TRANSFERENCIA',
                'REHABILITACIÓN DE PAVIMENTO'
            ],
            'PUEBLO REYES ACOZAC, COLONIA CENTRO' => [
                'AV LAGO CASPIO 276 MTS',
                'LAGUNA DE REGULACIÓN REYES EXPLANADA',
                'LAGUNA DE REGULACIÓN REYES ACOZAC'
            ],
            'PUEBLO REYES ACOZAC, BARRIO SANTO TOMÁS' => [
                'PAVIMENTACIÓN CALLE 3 "INDEPEND"',
                'PAVIMENTACIÓN CALLE 3 "PRIMARIA"',
                'REHABILITACIÓN CLÍNICA VETERINARIA'
            ],
            'PUEBLO SANTA MARÍA ACOLPAN' => [
                'PERFORACIÓN EN SUSTITUCIÓN DEL POZO PROFUNDO',
                'PAVIMENTACIÓN CALLE SANTA ANA 320M',
                'ESCUELA PREPARATORIA OFICIAL 383'
            ],
            'PUEBLO SANTO DOMINGO ACOLPAN' => [
                'PAVIMENTACIÓN CALLE PRINCIPAL',
                'MEJORAMIENTO DE DRENAJE',
                'REHABILITACIÓN DE ESPACIOS PÚBLICOS'
            ],
            'PUEBLO SAN PEDRO POZOTHUACAN' => [
                'PAVIMENTACIÓN CALLE',
                'PAVIMENTACIÓN AV BOSQUE DE LOS ABEDULES',
                'REHABILITACIÓN DE PAVIMENTO AV BOSQUE'
            ],
            'PUEBLO SAN JERÓNIMO XONACAHUACÁN' => [
                'PAVIMENTACIÓN CALLE PRINCIPAL',
                'MEJORAS EN ADECUACIONES GEOMÉTRICAS',
                'RETORNOS Y CARRETERA FEDERAL MÉXICO-PACHUCA'
            ],
            'PUEBLO SAN JUAN PUEBLO NUEVO' => [
                'CÁRCAMO SAN JUAN PUEBLO NUEVO',
                'REHABILITACIÓN DE PAVIMENTO AV STA. CRUZ',
                'MEJORAMIENTO DE ESPACIOS PÚBLICOS'
            ],
            'TECAMAC CENTRO' => [
                'PAVIMENTACIÓN DE VÍAS PRINCIPALES',
                'MEJORAMIENTO DE PARQUES Y JARDINES',
                'REHABILITACIÓN DE MERCADO MUNICIPAL'
            ],
            'COLONIA EJIDOS DE TECAMAC' => [
                'PAVIMENTACIÓN DE CALLES SECUNDARIAS',
                'CONSTRUCCIÓN DE BANQUETAS',
                'MEJORAMIENTO DE ALUMBRADO PÚBLICO'
            ],
            'COLONIA SAN JOSÉ EJIDOS DE TECAMAC' => [
                'REHABILITACIÓN DE PAVIMENTOS',
                'CONSTRUCCIÓN DE CANCHAS DEPORTIVAS',
                'MEJORAMIENTO DE DRENAJE PLUVIAL'
            ]
        ];

        // Crear TODAS las colonias
        foreach ($todasLasColonias as $nombreColonia) {
            $colonia = Colonia::create([
                'nombre' => $nombreColonia,
                'descripcion' => 'Colonia del municipio de Tecámac'
            ]);

            // Solo crear obras para las colonias que están en el array de obras
            if (isset($coloniasConObras[$nombreColonia])) {
                foreach ($coloniasConObras[$nombreColonia] as $nombreObra) {
                    ObraPublica::create([
                        'colonia_id' => $colonia->id,
                        'nombre' => $nombreObra,
                        'descripcion' => 'Obra pública para mejorar la infraestructura de la colonia'
                    ]);
                }
            }
            // Las demás colonias no tendrán obras (están en desarrollo o no aplican)
        }
    }
}
