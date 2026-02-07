<?php

namespace Database\Seeders;

use App\Models\Colonia;
use App\Models\ObraPublica;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ColoniasObrasSeederFixed extends Seeder
{
    public function run(): void
    {
        // Desactivar foreign keys para poder truncar
        Schema::disableForeignKeyConstraints();
        ObraPublica::truncate();
        Colonia::truncate();
        Schema::enableForeignKeyConstraints();

        // Datos completos extraídos de la tabla oficial de colonias y obras públicas
        $coloniasConObras = [

            // =====================================================================
            //                         DISTRITO 5
            // =====================================================================

            'ATLAUTENCO / EJIDOS' => [
                'distrito' => 5,
                'obras' => [
                    'REHABILITACIÓN DE PAVIMENTO EN CALLE DALIAS',
                    'REHABILITACIÓN DE PAVIMENTO BUGAMBILIA',
                    'REHABILITACIÓN DE PAVIMENTO EN CALLE GARDENIAS',
                    'PAVIMENTACIÓN DE CALLE CIPRÉS',
                ]
            ],

            'BUENAVISTA XOLOX' => [
                'distrito' => 5,
                'obras' => [
                    'REHABILITACIÓN DE PAVIMENTO CALLE LÓPEZ',
                ]
            ],

            'COLONIA 5 DE MAYO' => [
                'distrito' => 5,
                'obras' => []
            ],

            'COLONIA TEZONTLA' => [
                'distrito' => 5,
                'obras' => [
                    'CENTRO DE REHABILITACIÓN Y TRATAMIENTO DE ADICCIONES PARA JÓVENES',
                ]
            ],

            'ESMERALDA' => [
                'distrito' => 5,
                'obras' => [
                    'CONSTRUCCIÓN DE PLANTA DE TRANSFERENCIA RSU EN AMPLIACIÓN ESMERALDA',
                    'REHABILITACIÓN DE PAVIMENTO VÍA REAL, DE BOULEVARD VALLE SAN PEDRO A CARRETERA',
                ]
            ],

            'GALAXIAS EL LLANO' => [
                'distrito' => 5,
                'obras' => [
                    'CENTRO BIENESTAR Y RESGUARDO ANIMAL',
                ]
            ],

            'HACIENDA OJO DE AGUA' => [
                'distrito' => 5,
                'obras' => [
                    'REHABILITACIÓN INTEGRAL DE CAMELLÓN BOULEVARD OJO DE AGUA DE CAMPO FLORIDO A PASEO ACUEDUCTO',
                ]
            ],

            'HACIENDAS DEL BOSQUE' => [
                'distrito' => 5,
                'obras' => []
            ],

            'HUEYOTENCO' => [
                'distrito' => 5,
                'obras' => [
                    'MEXIBÚS QUETZALCÓATL ADECUACIONES GEOMÉTRICAS, RETORNOS Y CARRETERA FEDERAL MEXICO-PACHUCA',
                    'PROTECCIÓN CIVIL, HANGAR Y HELIPUERTO',
                ]
            ],

            'ISIDRO FABELA' => [
                'distrito' => 5,
                'obras' => []
            ],

            'LA NOPALERA' => [
                'distrito' => 5,
                'obras' => []
            ],

            'LA REDONDA' => [
                'distrito' => 5,
                'obras' => [
                    'CBT 2 E IMSS ADECUACIONES GEOMÉTRICAS, RETORNOS Y CARRETERA FEDERAL MEXICO-PACHUCA',
                ]
            ],

            'LAS FLORES' => [
                'distrito' => 5,
                'obras' => []
            ],

            'LOMA BONITA' => [
                'distrito' => 5,
                'obras' => []
            ],

            'LOMAS DE TECÁMAC' => [
                'distrito' => 5,
                'obras' => [
                    'ZACAZONAPAN ENTRE PONIENTE Y JUAN ESCUTIA',
                ]
            ],

            'LOS HÉROES SAN PABLO' => [
                'distrito' => 5,
                'obras' => []
            ],

            'LOS OLIVOS' => [
                'distrito' => 5,
                'obras' => []
            ],

            'LOS REYES ACOZAC' => [
                'distrito' => 5,
                'obras' => [
                    'PAVIMENTACIÓN Y DRENAJE DE AV LAGO CASPIO. 276 MTS',
                    'PAVIMENTACIÓN Y DRENAJE DE AV LAGO CASPIO. 532 MTS',
                    'PAVIMENTACIÓN Y DRENAJE AV HUMANISMO. 350 MTS',
                    'CORREDOR URBANO Y CICLOVÍA AV 16 SEP - ESTACIÓN XOLOC 405',
                    'LAGUNA DE REGULACIÓN REYES MERCADO',
                    'LAGUNA DE REGULACIÓN REYES EXPLANADA',
                    'LAGUNA DE REGULACIÓN REYES ACOZAHUAC',
                    'EMBELLECIMIENTO CALLE REFORMA. 270',
                    'CENTRO HISTÓRICO',
                    'REHABILITACIÓN AUDITORIO MUNICIPAL',
                    'LÍNEAS DE AGUA PLUVIAL',
                    'PAVIMENTACIÓN Y DRENAJE CALLE TENORIO',
                    'REPAVIMENTACIÓN AV SAN JUAN ENTRE NICOLÁS BRAVO HACÍA PLAZA JUÁREZ',
                    'PAVIMENTACIÓN CALLE MORELOS ENTRE LA CRUZ Y SAN JUAN',
                    'PAVIMENTACIÓN Y DRENAJE CALLE LA CRUZ ENTRE NIÑOS HÉROES Y SAN JUAN',
                    'PAVIMENTACIÓN CALLE HIDALGO ENTRE GUSTAVO BAZ HACÍA PLAZA JUÁREZ',
                    'PAVIMENTACIÓN CALLE LOS PINOS (BARRANCA) ENTRE EL PINO Y FELIPE VILLANUEVA',
                    'PAVIMENTACIÓN AVENIDA CARLOS PELLICER ENTRE AVENIDA FERROCARRIL HIDALGO HACÍA CALLE BARTOLO',
                    'PAVIMENTACIÓN ENTRE MOZOYUCA HACÍA BICENTENARIO',
                    'PAVIMENTACIÓN CALLE SANTA MÓNICA, COLONIA MICHAPA',
                    'PAVIMENTACIÓN CALLE SANTA LUCÍA, COLONIA MICHAPA',
                    'PAVIMENTACIÓN CALLE SANTA ISABEL, COLONIA MICHAPA',
                    'PAVIMENTACIÓN CALLE SANTA RITA, COLONIA MICHAPA',
                    'PAVIMENTACIÓN CALLE SAN JOSÉ, COLONIA MICHAPA',
                ]
            ],

            'PASEOS DE TECAMAC' => [
                'distrito' => 5,
                'obras' => []
            ],

            'PROVENZAL DEL BOSQUE' => [
                'distrito' => 5,
                'obras' => []
            ],

            'RANCHO LA CAPILLA' => [
                'distrito' => 5,
                'obras' => []
            ],

            'RANCHO LA LUZ' => [
                'distrito' => 5,
                'obras' => [
                    'MEXIBÚS LA REDONDA ADECUACIONES GEOMÉTRICAS, RETORNOS Y CARRETERA FEDERAL MEXICO-PACHUCA',
                    'REPAVIMENTACIÓN LA REDONDA',
                ]
            ],

            'SAN FRANCISCO CUAUTLIQUIXCA' => [
                'distrito' => 5,
                'obras' => [
                    'REHABILITACIÓN DE PAVIMENTO (TRAMO 1)',
                    'REHABILITACIÓN DE PAVIMENTO (TRAMO 2)',
                    'PAVIMENTACIÓN (TRAMO 1)',
                    'PAVIMENTACIÓN (TRAMO 2)',
                ]
            ],

            'SAN JERÓNIMO XONACAHUACAN' => [
                'distrito' => 5,
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
                    'EMBELLECIMIENTO DEL CAMINO Y ADECUACIONES VIALES A SAN JERÓNIMO XONACAHUACAN',
                ]
            ],

            'SAN JOSE, EJIDOS DE TECAMAC' => [
                'distrito' => 5,
                'obras' => []
            ],

            'SAN JUAN PUEBLO NUEVO' => [
                'distrito' => 5,
                'obras' => [
                    'PAVIMENTACIÓN CDA RICARDO FLORES MAGÓN',
                    'PAVIMENTACIÓN Y DRENAJE EN CALLE RICARDO FLORES MAGÓN',
                    'PAVIMENTACIÓN PARQUE LOS NOPALITOS',
                    'PAVIMENTACIÓN Y DRENAJE CALLE LOS NOPALITOS',
                    'CÁRCAMO SAN JUAN PUEBLO NUEVO',
                ]
            ],

            'SAN LUCAS XOLOX' => [
                'distrito' => 5,
                'obras' => [
                    'LIENZO CHARRO CENTENARIO',
                    'PAVIMENTACIÓN MOGOTES',
                    'PAVIMENTACIÓN Y DRENAJE CALLE 2 "KÍNDER"',
                    'PAVIMENTACIÓN CALLE 3 "PRIMARIA"',
                    'PAVIMENTACIÓN CALLE 4 "SECUNDARIA"',
                    'PAVIMENTACIÓN Y DRENAJE CALLE "NUEVA SIN NOMBRE"',
                    'ARCO TECHO PRIMARIA REY XÓLOTL',
                    'AULAS Y BARDA PERIMETRAL. SECUNDARIA REY XÓLOTL',
                    'TECHUMBRE. PREPARATORIA CEB 9/29',
                    'CANCHA. SECUNDARIA 331 "JUSTO SIERRA"',
                    'REHABILITACIÓN DE PAVIMENTO CALLE MAGDALENA',
                    'REHABILITACIÓN DE PAVIMENTO CALLE PINOS',
                    'REHABILITACIÓN DE PAVIMENTO AV STA. CRUZ',
                    'PAVIMENTACIÓN Y DRENAJE CALLE CHAPULTEPEC',
                    'PAVIMENTACIÓN CALLE PACHECO',
                    'PAVIMENTACIÓN CALLE DE LA VIRGEN',
                    'REHABILITACIÓN AUDITORIO MUNICIPAL',
                    'CORREDOR URBANO Y CICLOVÍA AV SAN JUAN - ESTACIÓN XOLOC',
                    'ELECTRIFICACIÓN CALLE SOTO Y SANTA CRUZ SAN LÁZARO',
                    'ACCESO XOLOX',
                    'ANDADOR AV SANTA LUCÍA Y PARADERO',
                    'PAVIMENTACIÓN CALLE OBREROS ENTRE HIDALGO HACÍA GUILLERMO PRIETO',
                    'PAVIMENTACIÓN CALLE DE LÓPEZ ENTRE HIDALGO HACÍA ALLENDE',
                    'PAVIMENTACIÓN CALLE PACHECO ENTRE LÓPEZ HACÍA ALLENDE',
                    'PAVIMENTACIÓN CALLE ANDRÉS ROSAS (PREPARATORIA 37)',
                    'PAVIMENTACIÓN CALLE AMEYAL (MANJAR DE XOLOX)',
                ]
            ],

            'SAN MARTIN AZCATEPEC' => [
                'distrito' => 5,
                'obras' => []
            ],

            'SAN MATEO TECALCO' => [
                'distrito' => 5,
                'obras' => []
            ],

            'SAN PABLO TECALCO' => [
                'distrito' => 5,
                'obras' => []
            ],

            'SAN PEDRO ATZOMPA' => [
                'distrito' => 5,
                'obras' => [
                    'REMODELACIÓN INTEGRAL DEL AUDITORIO EJIDAL "EMILIANO ZAPATA"',
                    'REPARACIÓN TECHO DE LECHERÍA LICONSA',
                ]
            ],

            'SAN PEDRO POZOHUACAN' => [
                'distrito' => 5,
                'obras' => [
                    'PAVIMENTACIÓN Y DRENAJE 1A CERRADA DE 16 DE SEPTIEMBRE',
                    'PAVIMENTACIÓN Y DRENAJE DE CALLE MORELOS',
                    'PAVIMENTACIÓN Y DRENAJE DE CALLE MELCHOR OCAMPO',
                    'PAVIMENTACIÓN Y DRENAJE DE CALLE AZTECAS',
                    'PAVIMENTACIÓN CALLE TLALTENANGO',
                    'PAVIMENTACIÓN Y DRENAJE CALLE ZARAGOZA',
                    'PAVIMENTACIÓN CALLE CAPULÍN',
                    'PAVIMENTACIÓN Y DRENAJE DE CALLE CHABACANO',
                    'PAVIMENTACIÓN CALLE CUERNAVACA',
                ]
            ],

            'SANTA CRUZ TECAMAC' => [
                'distrito' => 5,
                'obras' => []
            ],

            'SANTA MARÍA AJOLOAPAN' => [
                'distrito' => 5,
                'obras' => [
                    'PAVIMENTACIÓN Y DRENAJE CALLE SANTA ANA. 320M',
                    'UNIDAD DEPORTIVA AJOLOAPAN',
                    'TECNOLÓGICO NACIONAL DE MÉXICO',
                    'PAVIMENTACIÓN CALLE UNIÓN HACÍA EL TANQUE',
                    'PAVIMENTACIÓN CALLE LIBRAMIENTO HACÍA CARRETERA',
                    'PAVIMENTACIÓN CALLE MORELOS ENTRE REFORMA HACÍA 5 DE MAYO',
                    'PAVIMENTACIÓN CALLE VELÁZQUEZ',
                    'PAVIMENTACIÓN CALLE CIPRÉS ENTRE CENTRO HACÍA 5 DE FEBRERO',
                    'PAVIMENTACIÓN CALLE DE LA CRUZ ENTRE UNIÓN HACÍA ZARAGOZA SAN PEDRO',
                ]
            ],

            'SANTA MARÍA OZUMBILLA' => [
                'distrito' => 5,
                'obras' => [
                    'MEXIBÚS OZUMBILLA ADECUACIONES GEOMÉTRICAS, RETORNOS Y CARRETERA FEDERAL MEXICO-PACHUCA',
                    'REHABILITACIÓN INTEGRAL DEL CENTRO DE BIENESTAR ANIMAL OZUMBILLA',
                    'REHABILITACIÓN INTEGRAL DE OFICINAS DE SERVICIOS PÚBLICOS EN OZUMBILLA',
                    'PANTEÓN MUNICIPAL OZUMBILLA',
                    'TECHUMBRE EN CBT 3',
                ]
            ],

            'SANTO DOMINGO AJOLOAPAN' => [
                'distrito' => 5,
                'obras' => [
                    'PAVIMENTACIÓN CALLE MORELOS HACÍA SAN PEDRO',
                    'PAVIMENTACIÓN CALLE SAN MIGUEL ENTRE MORELOS, LAGO DE CHAPALA Y AVENIDA DEL PANTEÓN',
                    'PAVIMENTACIÓN CALLE PEÑÓN HACÍA LA PREPARATORIA',
                    'PAVIMENTACIÓN CALLE SANTA ANA, SANTA MARÍA CONTINUACIÓN CALLE UNIÓN',
                ]
            ],

            'SIERRA HERMOSA' => [
                'distrito' => 5,
                'obras' => [
                    'REHABILITACIÓN DE C2 A C4',
                    'DEPORTIVO SIERRA HERMOSA 50%',
                    'OBRAS COMPLEMENTARIAS BOULEVARD GEO SIERRA HERMOSA',
                ]
            ],

            'TECÁMAC DE F. VILLANUEVA' => [
                'distrito' => 5,
                'obras' => [
                    'RENOVACIÓN DEL CENTRO HISTÓRICO DE TECÁMAC PARQUE LA SOLEDAD Y SU ENTORNO URBANO',
                    'PAVIMENTACIÓN Y DRENAJE CALLE FELIPE VILLANUEVA 150 M',
                    'PAVIMENTACIÓN Y DRENAJE CALLE MIGUEL HIDALGO',
                    'MEXIBÚS TECÁMAC Y CRUCE LAS ABEJAS ADECUACIONES GEOMÉTRICAS, RETORNOS Y CARRETERA FEDERAL MEXICO-PACHUCA',
                    'PAVIMENTACIÓN CALLE 5 MAYO',
                    'REHABILITACIÓN GLORIETA TECÁMAC',
                ]
            ],

            'VILLA DEL REAL' => [
                'distrito' => 5,
                'obras' => [
                    'MEXIBÚS SAN FRANCISCO ADECUACIONES GEOMÉTRICAS, RETORNOS Y CARRETERA FEDERAL MEXICO-PACHUCA',
                    'LONARIA. PRIMARIA "MARIO MOLINA"',
                ]
            ],

            'VISTA HERMOSA' => [
                'distrito' => 5,
                'obras' => []
            ],

            // =====================================================================
            //                         DISTRITO 20
            // =====================================================================

            'CERRITOS' => [
                'distrito' => 20,
                'obras' => []
            ],

            'HÉROES TECÁMAC' => [
                'distrito' => 20,
                'obras' => [
                    'REHABILITACIÓN DE PAVIMENTO AV BOSQUE DE LOS ABEDULES',
                    'GIMNASIO Y ALBERCA EN BOSQUES DE PORTUGAL',
                    'CENTRO DE SERVICIOS PARA VEHÍCULOS AUTOMOTORES, LA CUCHARA',
                    'REHABILITACIÓN CLÍNICA VETERINARIA BOSQUES DE SAN LUIS',
                    'PARQUE CULTURAL AV OZUMBILLA',
                    'ESCUELA DEL DEPORTE',
                    'BARDA EN CBT 4',
                ]
            ],

            'JARDINES OJO DE AGUA' => [
                'distrito' => 20,
                'obras' => [
                    'REHABILITACIÓN INTEGRAL DE CAMELLÓN BOULEVARD OJO DE AGUA DE LIRIOS A TULIPANES',
                    'REHABILITACIÓN DE PAVIMENTO VÍA REAL, DE BOULEVARD OJO DE AGUA A BOULEVARD VALLE SAN PEDRO',
                    'TIENDA Y TORRE DE AGUA EN DEPORTIVO FABULANDIA FRACCIONAMIENTO',
                ]
            ],

            'LOS ARCOS' => [
                'distrito' => 20,
                'obras' => []
            ],

            'PASEOS DEL BOSQUE' => [
                'distrito' => 20,
                'obras' => []
            ],

            'PUNTA PALERMO' => [
                'distrito' => 20,
                'obras' => []
            ],

            'REAL CARRARA' => [
                'distrito' => 20,
                'obras' => [
                    'LONARIA EN CARRARA',
                ]
            ],

            'REAL CASTELL' => [
                'distrito' => 20,
                'obras' => []
            ],

            'REAL DEL CID' => [
                'distrito' => 20,
                'obras' => []
            ],

            'REAL DEL SOL' => [
                'distrito' => 20,
                'obras' => []
            ],

            'REAL FIRENZE' => [
                'distrito' => 20,
                'obras' => []
            ],

            'REAL GRANADA' => [
                'distrito' => 20,
                'obras' => []
            ],

            'REAL TOSCANA' => [
                'distrito' => 20,
                'obras' => [
                    'LONARIA EN TOSCANA',
                ]
            ],

            'REAL VERONA' => [
                'distrito' => 20,
                'obras' => [
                    'LONARIA EN VERONA',
                ]
            ],

            'VALLE SAN PEDRO URBI' => [
                'distrito' => 20,
                'obras' => []
            ],
        ];

        // Crear todas las colonias con sus obras
        foreach ($coloniasConObras as $nombreColonia => $data) {
            $colonia = Colonia::create([
                'nombre' => $nombreColonia,
                'distrito' => $data['distrito'],
            ]);

            foreach ($data['obras'] as $nombreObra) {
                ObraPublica::create([
                    'nombre' => $nombreObra,
                    'colonia_id' => $colonia->id,
                    'descripcion' => 'Obra pública programada para el presupuesto participativo 2026 en ' . $nombreColonia,
                ]);
            }
        }

        $this->command->info('✅ Se crearon ' . Colonia::count() . ' colonias y ' . ObraPublica::count() . ' obras públicas.');
    }
}
