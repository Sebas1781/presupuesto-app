<?php

namespace Database\Seeders;

use App\Models\Colonia;
use App\Models\ObraPublica;
use Illuminate\Database\Seeder;

class ColoniasObrasSeederFixed extends Seeder
{
    public function run(): void
    {
        // Limpiar datos existentes
        ObraPublica::truncate();
        Colonia::truncate();

        // Datos completos de colonias y obras públicas de Tecámac según la documentación oficial
        $coloniasConObras = [
            // DISTRITO 20
            'SAN JUAN PUEBLO NUEVO' => [
                'distrito' => 5,  // CAMBIADO: era 20, ahora es 5
                'obras' => [
                    'PAVIMENTACIÓN CDA RICARDO FLORES MAGÓN',
                    'PAVIMENTACIÓN Y DRENAJE EN CALLE RICARDO FLORES MAGÓN',
                    'PAVIMENTACIÓN Y DRENAJE PARQUE LOS NOPALITOS',
                    'PAVIMENTACIÓN Y DRENAJE CALLE LOS NOPALITOS',
                    'CÁRCAMO SAN JUAN PUEBLO NUEVO'
                ]
            ],

            'EJIDAL SAN LUCAS XOLOX' => [
                'distrito' => 5,  // CAMBIADO: era 20, ahora es 5
                'obras' => [
                    'LIENZO CHARRO CENTENARIO',
                    'PAVIMENTACIÓN MOGOTES',
                    'PAVIMENTACIÓN Y DRENAJE CALLE 2 "KINDER"',
                    'PAVIMENTACIÓN CALLE 3 "PRIMARIA"',
                    'PAVIMENTACIÓN CALLE 4 "SECUNDARIA"',
                    'PAVIMENTACIÓN Y DRENAJE CALLE "NUEVA SIN NOMBRE"',
                    'ARCO TECHO PRIMARIA REY XÓLOTL',
                    'AULAS Y BARDA PERIMETRAL, SECUNDARIA REY XÓLOTL',
                    'TECHUMBRE. PREPARATORIA CEB 9/29',
                    'CANCHA. SECUNDARIA 331 "JUSTO SIERRA"',
                    'REHABILITACIÓN DE PAVIMENTO CALLE MAGDALENA',
                    'REHABILITACIÓN DE PAVIMENTO CALLE PINOS',
                    'REHABILITACIÓN DE PAVIMENTO AV STA. CRUZ'
                ]
            ],

            'SAN LUCAS XOLOX' => [
                'distrito' => 5,  // CAMBIADO: era 20, ahora es 5
                'obras' => [
                    'PAVIMENTACIÓN Y DRENAJE CALLE CHAPULTEPEC',
                    'PAVIMENTACIÓN CALLE PACHECO',
                    'PAVIMENTACIÓN CALLE DE LA VIRGEN',
                    'REHABILITACIÓN AUDITORIO MUNICIPAL',
                    'REHABILITACIÓN DE PAVIMENTO CALLE LÓPEZ',
                    'CORREDOR URBANO Y CICLOVÍA AV SAN JUAN - ESTACIÓN XOLOC',
                    'ELECTRIFICACIÓN CALLE SOTO Y SANTA CRUZ SAN LÁZARO',
                    'ACCESO XOLOX',
                    'ANDADOR AV SANTA LUCÍA Y PARADERO',
                    'PAVIMENTACIÓN CALLE OBREROS ENTRE HIDALGO HACIA GUILLERMO PRIETO',
                    'PAVIMENTACIÓN CALLE DE LÓPEZ ENTRE HIDALGO HACIA ALLENDE',
                    'PAVIMENTACIÓN CALLE PACHECO ENTRE LÓPEZ HACIA ALLENDE',
                    'PAVIMENTACIÓN CALLE ANDRÉS ROSAS (PREPARATORIA 37)',
                    'PAVIMENTACIÓN CALLE AMEYAL (MANUALE DE COLOX)',
                    'PAVIMENTACIÓN Y DRENAJE DE AV LAGO CASPIO. 276 MTS'
                ]
            ],

            'LOS REYES ACOZAC' => [
                'distrito' => 5,  // CAMBIADO: era 20, ahora es 5
                'obras' => [
                    'PAVIMENTACIÓN Y DRENAJE DE AV LAGO CASPIO. 532 MTS',
                    'PAVIMENTACIÓN Y DRENAJE AV HUARIQUIO. 350 MTS',
                    'CORREDOR URBANO Y CICLOVÍA AV 16 SEP - ESTACIÓN XOLOC 4G5',
                    'LAGUNA DE REGULACIÓN REYES MERCADO',
                    'LAGUNA DE REGULACIÓN REYES IGLESIA',
                    'LAGUNA DE REGULACIÓN REYES ACOZAHUAC',
                    'EMBELLECIMIENTO CALLE REFORMA 270',
                    'CENTRO HISTÓRICO',
                    'REHABILITACIÓN AUDITORIO MUNICIPAL',
                    'LÍNEAS DE AGUA PLUVIAL',
                    'PAVIMENTACIÓN Y DRENAJE CALLE TENORIO',
                    'REPAVIMENTACIÓN AV SAN JUAN ENTRE NICOLÁS BRAVO HACIA PLAZA JUÁREZ',
                    'PAVIMENTACIÓN CALLE MORELOS ENTRE LA CRUZ Y SAN JUAN',
                    'PAVIMENTACIÓN Y DRENAJE CALLE LA CRUZ ENTRE NIÑOS HÉROES Y SAN JUAN',
                    'PAVIMENTACIÓN CALLE HIDALGO ENTRE GUSTAVO BAZ HACIA PLAZA JUÁREZ',
                    'PAVIMENTACIÓN CALLE LOS PINOS (BARRANCA) ENTRE EL PINO Y FELIPE VILLANUEVA',
                    'PAVIMENTACIÓN AVENIDA CARLOS PELLICER ENTRE AVENIDA FERROCARRIL HIDALGO HACIA CALLE BARTOLO',
                    'PAVIMENTACIÓN ENTRE MOZTOC LUCÍA HACIA BICENTENARIO',
                    'PAVIMENTACIÓN CALLE SANTA MÓNICA, COLONIA MICHAPA',
                    'PAVIMENTACIÓN CALLE SANTA LUCÍA, COLONIA MICHAPA',
                    'PAVIMENTACIÓN CALLE SANTA ISABEL, COLONIA MICHAPA',
                    'PAVIMENTACIÓN CALLE SANTA RITA, COLONIA MICHAPA',
                    'PAVIMENTACIÓN CALLE SAN JOSÉ, COLONIA MICHAPA'
                ]
            ],

            'SANTA MARÍA AJOLOAPAN' => [
                'distrito' => 5,  // CAMBIADO: era 20, ahora es 5
                'obras' => [
                    'PAVIMENTACIÓN Y DRENAJE CALLE SANTA ANA. 320M',
                    'UNIDAD DEPORTIVA AJOLOAPAN',
                    'TECNOLÓGICO NACIONAL DE MÉXICO',
                    'PAVIMENTACIÓN CALLE UNIÓN HACIA EL TANQUE',
                    'PAVIMENTACIÓN CALLE LIBRAMENTO HACIA CARRETERA',
                    'PAVIMENTACIÓN CALLE MORELOS ENTRE REFORMA HACIA 5 DE MAYO',
                    'PAVIMENTACIÓN CALLE VELÁZQUEZ',
                    'PAVIMENTACIÓN CALLE CIPRÉS ENTRE CENTRO HACIA 5 DE FEBRERO',
                    'PAVIMENTACIÓN CALLE DE LA CRUZ ENTRE UNIÓN HACIA ZARAGOZA SAN PEDRO'
                ]
            ],

            'STO. DOMINGO AJOLOAPAN' => [
                'distrito' => 5,  // CAMBIADO: era 20, ahora es 5
                'obras' => [
                    'PAVIMENTACIÓN CALLE MORELOS HACIA SAN PEDRO',
                    'PAVIMENTACIÓN CALLE SAN MIGUEL ENTRE MORELOS, LAGO DE CHAPALA Y AVENIDA DEL PANTEÓN',
                    'PAVIMENTACIÓN CALLE PEÑÓN HACIA LA PREPARATORIA',
                    'PAVIMENTACIÓN CALLE SANTA ANA, SANTA MARÍA, CONTINUACIÓN CALLE UNIÓN',
                    'PAVIMENTACIÓN Y DRENAJE 1A CERRADA DE 16 DE SEPTIEMBRE'
                ]
            ],

            'SAN PEDRO POZOHUACAN' => [
                'distrito' => 5,  // CAMBIADO: era 20, ahora es 5
                'obras' => [
                    'PAVIMENTACIÓN Y DRENAJE DE CALLE MORELOS',
                    'PAVIMENTACIÓN Y DRENAJE DE CALLE HIDALGO DE OCAMPO',
                    'PAVIMENTACIÓN Y DRENAJE DE CALLE AZTECAS',
                    'PAVIMENTACIÓN CALLE TLATLEQUISO',
                    'PAVIMENTACIÓN CALLE CAPULÍN',
                    'PAVIMENTACIÓN Y DRENAJE DE CALLE CHABACANO',
                    'PAVIMENTACIÓN CALLE CUENTLA XV-5'
                ]
            ],

            'SAN JERÓNIMO XONACAHUACAN' => [
                'distrito' => 5,  // CAMBIADO: era 20, ahora es 5
                'obras' => [
                    'UNIDAD DEPORTIVA SAN JERÓNIMO XONACAHUACAN',
                    'DRENAJE EN CALLE ABINO PASO TÉLLEZ ESQUINA PALO SOLO',
                    'PAVIMENTACIÓN Y DRENAJE DE CALLE ABINO PASO TÉLLEZ',
                    'PAVIMENTACIÓN AVENIDA SAN JUAN (PASANDO AUTOPISTA)',
                    'PAVIMENTACIÓN CALLE MORAS ESQUINA AVENIDA JUÁREZ',
                    'PAVIMENTACIÓN CALLE LAS TORRES',
                    'PAVIMENTACIÓN CALLE SANTA ANA',
                    'PAVIMENTACIÓN CERRADA MARTÍNEZ',
                    'PAVIMENTACIÓN CALLE SAN GASPAR',
                    'EMBELLECIMIENTO DEL CAMINO Y ADECUACIONES VIALES A SAN JERÓNIMO XONACAHUACAN'
                ]
            ],

            'LA REDONDA' => [
                'distrito' => 5,  // CAMBIADO: era 20, ahora es 5
                'obras' => [
                    'CBT 2 E IMSS ADECUACIONES GEOMÉTRICAS, RETORNOS Y CARRETERA FEDERAL MÉXICO-PACHUCA',
                    'MIEMBROS LA REDONDA ADECUACIONES GEOMÉTRICAS, RETORNOS Y CARRETERA FEDERAL MÉXICO-PACHUCA'
                ]
            ],

            'LA REDONDA RANCHO LA LUZ' => [
                'distrito' => 5,  // CAMBIADO: era 20, ahora es 5
                'obras' => [
                    'REPAVIMENTACIÓN LA REDONDA',
                    'RENOVACIÓN DEL CENTRO HISTÓRICO DE TECÁMAC PARQUE LA SOLEDAD Y SU ENTORNO URBANO'
                ]
            ],

            'TECÁMAC DE F. VILLANUEVA' => [
                'distrito' => 5,  // CAMBIADO: era 20, ahora es 5
                'obras' => [
                    'PAVIMENTACIÓN Y DRENAJE CALLE FELIPE VILLANUEVA 160 M',
                    'PAVIMENTACIÓN Y DRENAJE CALLE MIGUEL HIDALGO',
                    'CENTRO BIENESTAR Y RESGUARDO ANIMAL',
                    'MIEMBROS TECÁMAC Y CRUCE LAS ARENAS ADECUACIONES GEOMÉTRICAS, RETORNOS Y CARRETERA FEDERAL MÉXICO-PACHUCA',
                    'PAVIMENTACIÓN CALLE 5 MAYO',
                    'REHABILITACIÓN GLORIETA TECÁMAC',
                    'MIEMBROS QUETALCOATL ADECUACIONES GEOMÉTRICAS, RETORNOS Y CARRETERA FEDERAL MÉXICO-PACHUCA',
                    'PROYECTO COLECTIVO EL HANGAR Y HELIPRESTO'
                ]
            ],

            'COLONIA HUEYOTENCO' => [
                'distrito' => 5,  // CAMBIADO: era 20, ahora es 5
                'obras' => [
                    'REHABILITACIÓN DE C2 X C4',
                    'DEPORTIVO DE HUEYOTENCO HERMOSA 50%'
                ]
            ],

            'EX HACIENDA SIERRA HERMOSA' => [
                'distrito' => 5,  // CAMBIADO: era 20, ahora es 5
                'obras' => [
                    'MIEMBROS SAN FRANCISCO ADECUACIONES GEOMÉTRICAS, RETORNOS Y CARRETERA FEDERAL MÉXICO-PACHUCA',
                    'LOMARIA, PRIMARIA "MARIO MOLINA"',
                    'OBRAS COMPLEMENTARIAS BOULEVARD GEO SIERRA HERMOSA'
                ]
            ],

            'CON URBE VILLAS DEL REAL' => [
                'distrito' => 5,  // CAMBIADO: era 20, ahora es 5
                'obras' => [
                    'REHABILITACIÓN DE PAVIMENTO',
                    'REHABILITACIÓN DE PAVIMENTO'
                ]
            ],

            'SAN FCO CUAUTLIQUIXCA' => [
                'distrito' => 5,  // CAMBIADO: era 20, ahora es 5
                'obras' => [
                    'PAVIMENTACIÓN',
                    'PAVIMENTACIÓN'
                ]
            ],

            'ATLAHUTENCO / EJIDOS' => [
                'distrito' => 5,  // CAMBIADO: era 20, ahora es 5
                'obras' => [
                    'REHABILITACIÓN DE PAVIMENTO',
                    'REHABILITACIÓN DE PAVIMENTO',
                    'PAVIMENTACIÓN',
                    'PAVIMENTACIÓN'
                ]
            ],

            'STA. MARÍA OZUMBILLA' => [
                'distrito' => 5,  // CAMBIADO: era 20, ahora es 5
                'obras' => [
                    'MIEMBROS OZUMBILLA ADECUACIONES GEOMÉTRICAS, RETORNOS Y CARRETERA FEDERAL MÉXICO-PACHUCA',
                    'REHABILITACIÓN INTEGRAL DEL CENTRO DE BIENESTAR ANIMAL OZUMBILLA',
                    'REHABILITACIÓN INTEGRAL DE OFICINAS DE SERVICIOS PÚBLICOS EN OZUMBILLA',
                    'PANTEÓN MUNICIPAL OZUMBILLA',
                    'TECHUMBRE CET 3'
                ]
            ],

            'OJO DE AGUA' => [
                'distrito' => 5,  // CAMBIADO: era 20, ahora es 5
                'obras' => [
                    'REHABILITACIÓN INTEGRAL DE CAMELLÓN BOULEVARD OJO DE AGUA',
                    'REHABILITACIÓN INTEGRAL DE CAMELLÓN BOULEVARD OJO DE AGUA',
                    'REHABILITACIÓN DE PAVIMENTO VÍA REAL DE BOULEVARD OJO DE AGUA',
                    'TIENDA Y TORRE DE AGUA EN DEPORTIVO A FACULTADES FINANCIAMIENTO'
                ]
            ],

            'SAN PEDRO ATZOMPA' => [
                'distrito' => 5,  // CAMBIADO: era 20, ahora es 5
                'obras' => [
                    'REMODELACIÓN INTEGRAL DEL AUDITORIO EJIDAL "EMILIANO ZAPATA"',
                    'REPARACIÓN TECHO DE LECHERÍA LICONSA'
                ]
            ],

            // DISTRITO 5
            'REAL TOSCANA' => [
                'distrito' => 20,  // CAMBIADO: era 5, ahora es 20
                'obras' => [
                    'LOMARIA EN TOSCANA'
                ]
            ],

            'REAL CABRERRA' => [
                'distrito' => 20,  // CAMBIADO: era 5, ahora es 20
                'obras' => [
                    'LOMARIA EN CABRRERA'
                ]
            ],

            'REAL VERONA' => [
                'distrito' => 20,  // CAMBIADO: era 5, ahora es 20
                'obras' => [
                    'LOMARIA EN VERONA',
                    'ZOCALAMIENTO ENTRE PONIENTE Y JUAN ESCUTIA'
                ]
            ],

            'LOMAS DE TECÁMAC' => [
                'distrito' => 20,  // CAMBIADO: era 5, ahora es 20
                'obras' => [
                    'REHABILITACIÓN DE PAVIMENTO VÍA REAL DE BOULEVARD VALLE SUPERFICIE A CARRETERA'
                ]
            ],

            'ESMERALDA' => [
                'distrito' => 20,  // CAMBIADO: era 5, ahora es 20
                'obras' => [
                    'CONSTRUCCIÓN DE PLANTA DE TRANSFERENCIA RSU EN AMPLIACIÓN ESMERALDA'
                ]
            ],

            'AMPLIACIÓN ESMERALDA' => [
                'distrito' => 20,  // CAMBIADO: era 5, ahora es 20
                'obras' => [
                    'CENTRO DE REHABILITACIÓN Y TRATAMIENTO DE ADICCIONES PARA JÓVENES'
                ]
            ],

            'COLONIA TEZONTLA' => [
                'distrito' => 20,  // CAMBIADO: era 5, ahora es 20
                'obras' => [
                    'REHABILITACIÓN DE PAVIMENTO AV BOSQUE DE LOS ACÉFILOS'
                ]
            ],

            'HÉROES TECÁMAC' => [
                'distrito' => 20,  // CAMBIADO: era 5, ahora es 20
                'obras' => [
                    'GIMNASIO ALBERCA EN BOSQUES DE PORTUGAL',
                    'CENTRO DE SERVICIOS PARA VEHÍCULOS AUTOMOTORES LA CHICHARRA',
                    'REHABILITACIÓN CLÍNICA VETERINARIA BOSQUES DE SAN LUIS',
                    'PARQUE NATURAL AV OZUMBILLA',
                    'ESCUELA DEL DEPORTE',
                    'BARDA EN CBT 4'
                ]
            ]
        ];

        // Crear todas las colonias con obras
        foreach ($coloniasConObras as $nombreColonia => $data) {
            $colonia = Colonia::create([
                'nombre' => $nombreColonia,
                'distrito' => $data['distrito'],
            ]);

            // Crear obras públicas para cada colonia
            foreach ($data['obras'] as $nombreObra) {
                ObraPublica::create([
                    'colonia_id' => $colonia->id,
                    'nombre' => $nombreObra,
                    'descripcion' => 'Obra pública programada para el presupuesto participativo 2026 en ' . $nombreColonia
                ]);
            }
        }

        // Colonias adicionales (algunas pueden no tener obras específicas aún)
        $coloniasSinObras = [
            'AMPLIACIÓN SANTO DOMINGO' => 20,  // CAMBIADO: era 5, ahora es 20
            'COLONIA ISIDRO FABELA' => 5,         // CAMBIADO: era 20, ahora es 5
            'COLONIA LA NOPALERA' => 5,           // CAMBIADO: era 20, ahora es 5
            'FRACC GALAXIAS EL LLANO' => 20,      // CAMBIADO: era 5, ahora es 20
            'PASEOS DE TECAMAC' => 20,            // CAMBIADO: era 5, ahora es 20
            'RANCHO LA LUZ' => 5,                 // CAMBIADO: era 20, ahora es 5
            'FRACC REAL DE SAN GERMÁN DEL BOSQUE' => 20,  // CAMBIADO: era 5, ahora es 20
            'COLONIA SAN JOSÉ EJIDOS DE TECAMAC' => 5,    // CAMBIADO: era 20, ahora es 5
            'COLONIA EJIDOS DE TECAMAC' => 5,              // CAMBIADO: era 20, ahora es 5
            'COLONIA 6 DE MAYO' => 20,                     // CAMBIADO: era 5, ahora es 20
            'COLONIA SAN MARTÍN AZCATEPEC' => 5,          // CAMBIADO: era 20, ahora es 5
            'PUEBLO SAN PABLO TECALCO' => 20,              // CAMBIADO: era 5, ahora es 20
            'FRACC REAL DE TECALCO' => 20,                 // CAMBIADO: era 5, ahora es 20
            'FRACC VILLA DEL REAL 1ERA Y 2DA SECCIÓN' => 20,  // CAMBIADO: era 5, ahora es 20
            'COLONIA LOS OLIVOS' => 20,                    // CAMBIADO: era 5, ahora es 20
            'FRACC VILLA DEL REAL 3RA A 4TA SECCIÓN' => 20,   // CAMBIADO: era 5, ahora es 20
            'CONJUNTO URBANO LAS FLORES' => 20,            // CAMBIADO: era 5, ahora es 20
            'VISTA HERMOSA' => 5,                          // CAMBIADO: era 20, ahora es 5
            'LOMAS DE OZUMBILLA' => 20,                    // CAMBIADO: era 5, ahora es 20
            'CONJUNTO URBANO EL TEJOCOTE JIQUIPILCO' => 5, // CAMBIADO: era 20, ahora es 5
            'COLONIA AMPLIACIÓN OZUMBILLA' => 20,         // CAMBIADO: era 5, ahora es 20
            'FRACC PROVENZAL DEL BOSQUE' => 20             // CAMBIADO: era 5, ahora es 20
        ];

        foreach ($coloniasSinObras as $nombreColonia => $distrito) {
            // Solo crear si no existe ya
            if (!Colonia::where('nombre', $nombreColonia)->exists()) {
                Colonia::create([
                    'nombre' => $nombreColonia,
                    'distrito' => $distrito,
                ]);
            }
        }
    }
}
