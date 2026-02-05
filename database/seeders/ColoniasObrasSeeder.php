<?php

namespace Database\Seeders;

use App\Models\Colonia;
use App\Models\ObraPublica;
use Illuminate\Database\Seeder;

class ColoniasObrasSeeder extends Seeder
{
    public function run(): void
    {
        // Datos completos de colonias y obras públicas de Tecámac
        $coloniasConObras = [
            // Distrito 20 - San Juan Pueblo Nuevo
            'SAN JUAN PUEBLO NUEVO' => [
                'distrito' => 20,
                'obras' => [
                    'PAVIMENTACIÓN CDA RICARDO FLORES MAGÓN',
                    'PAVIMENTACIÓN Y DRENAJE EN CALLE RICARDO FLORES MAGÓN',
                    'PAVIMENTACIÓN PARQUE LOS NOPALITOS',
                    'PAVIMENTACIÓN Y DRENAJE CALLE LOS NOPALITOS',
                    'CÁRCAMO SAN JUAN PUEBLO NUEVO'
                ]
            ],

            // Distrito 20 - San Lucas Xolox Ejidal
            'EJIDAL SAN LUCAS XOLOX' => [
                'distrito' => 20,
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

            // Distrito 20 - San Lucas Xolox Centro
            'SAN LUCAS XOLOX' => [
                'distrito' => 20,
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
                'PAVIMENTACIÓN CALLE VAL IZCALLI B5 COLOX'
                ]
            ],

            // Distrito 5 - Los Reyes Acozac
            'LOS REYES ACOZAC' => [
                'distrito' => 5,
                'obras' => [
                    'PAVIMENTACIÓN Y DRENAJE DE AV LAGO CASPIO. 276 MTS',
                    'PAVIMENTACIÓN Y DRENAJE DE AV LAGO CASPIO. 532 MTS',
                    'PAVIMENTACIÓN Y DRENAJE AV HUARIQUIO. 350 MTS',
                    'CORREDOR URBANO Y CICLOVÍA AV 16 SEP - ESTACIÓN XOLOC 4G5',
                    'LAGUNA DE REGULACIÓN REYES MERCADO',
                    'LAGUNA DE REGULACIÓN REYES IGLESIA',
                    'LAGUNA DE REGULACIÓN REYES ACOLZAHUAC',
                    'EMBELLECIMIENTO CALLE REFORMA',
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

            // Distrito 5 - Santa María Ajoloapan
            'SANTA MARÍA AJOLOAPAN' => [
                'distrito' => 5,
                'obras' => [
                    'PAVIMENTACIÓN Y DRENAJE CALLE SANTA ANA. 320M',
                    'UNIDAD DEPORTIVA AJOLOAPAN',
                    'TECNOLÓGICO NACIONAL DE MÉXICO',
                    'PAVIMENTACIÓN CALLE UNIÓN HACIA EL TANQUE',
                    'PAVIMENTACIÓN CALLE LIBRAMENTO HACIA CARRETERA',
                    'PAVIMENTACIÓN CALLE MORELOS ENTRE REFORMA HACIA S DE MAYO',
                    'PAVIMENTACIÓN CALLE VELÁZQUEZ',
                    'PAVIMENTACIÓN CALLE CIPRÉS ENTRE CENTRO HACIA S DE FEBRERO',
                    'PAVIMENTACIÓN CALLE DE LA CRUZ ENTRE UNIÓN HACIA ZARAGOZA SAN PEDRO'
                ]
            ],

            // Distrito 5 - Santo Domingo Ajoloapan
            'STO. DOMINGO AJOLOAPAN' => [
                'distrito' => 5,
                'obras' => [
                    'PAVIMENTACIÓN CALLE MORELOS HACIA SAN PEDRO',
                    'PAVIMENTACIÓN CALLE SAN MIGUEL ENTRE MORELOS, LAGO DE CHAPALA Y AVENIDA DEL PANTEÓN',
                    'PAVIMENTACIÓN CALLE PEÑÓN HACIA LA PREPARATORIA',
                    'PAVIMENTACIÓN CALLE SANTA ANA, SANTA MARÍA, CONTINUACIÓN CALLE UNIÓN',
                    'PAVIMENTACIÓN Y DRENAJE 1A CERRADA DE 16 DE SEPTIEMBRE'
                ]
            ],

            // Distrito 20 - San Pedro Pozohuacan
            'SAN PEDRO POZOHUACAN' => [
                'distrito' => 20,
                'obras' => [
                    'PAVIMENTACIÓN Y DRENAJE DE CALLE MORELOS',
                    'PAVIMENTACIÓN Y DRENAJE DE CALLE MELCHOR OCAMPO',
                    'PAVIMENTACIÓN Y DRENAJE DE CALLE AZTECA',
                    'PAVIMENTACIÓN CALLE TLALTENANGO',
                    'PAVIMENTACIÓN Y DRENAJE DE CALLE ZARAGOZA',
                    'PAVIMENTACIÓN CALLE CAPULÍN',
                    'PAVIMENTACIÓN Y DRENAJE DE CALLE CHABACANO',
                    'PAVIMENTACIÓN CALLE CUERVAYACAN'
                ]
            ],

            // Distrito 20 - San Jerónimo Xonacahuacan
            'SAN JERÓNIMO XONACAHUACAN' => [
                'distrito' => 20,
                'obras' => [
                    'UNIDAD DEPORTIVA SAN JERÓNIMO XONACAHUACAN',
                    'DRENAJE EN CALLE ABIÑO PASO TÉLLEZ ESQUINA TECOLUCO SÓLO',
                    'PAVIMENTACIÓN Y DRENAJE DE CALLE ABIÑO PASO TÉLLEZ',
                    'PAVIMENTACIÓN AVENIDA SAN JUAN (PASANDO AUTOPISTA)',
                    'PAVIMENTACIÓN CALLE MORAS ESQUINA AVENIDA JUÁREZ',
                    'PAVIMENTACIÓN CALLE LAS TORRES',
                    'PAVIMENTACIÓN CALLE SAN GASPAR ANA',
                    'PAVIMENTACIÓN CERRADA MARTÍNEZ',
                    'PAVIMENTACIÓN CALLE SAN GASPAR',
                    'EMBELLECIMIENTO DEL CAMINO 7 ADECUACIONES VIALES A SAN JERÓNIMO XONACAHUACAN'
                ]
            ],

            // Distrito 5 - La Redonda
            'LA REDONDA' => [
                'distrito' => 5,
                'obras' => [
                    'CFE 3 PTOS ADECUACIONES GEOMÉTRICAS, RETOÑOS Y CARRETERA FEDERAL MÉXICO-PACHUCA'
                ]
            ],

            // Distrito 5 - Tecámac de F. Villanueva
            'TECÁMAC DE F. VILLANUEVA' => [
                'distrito' => 5,
                'obras' => [
                    'MEXIBÚS LA REDONDA ADECUACIONES GEOMÉTRICAS, RETOÑOS Y CARRETERA FEDERAL MÉXICO-PACHUCA',
                    'REPAVIMENTACIÓN LA REDONDA',
                    'RENOVACIÓN DEL CENTRO HISTÓRICO DE TECÁMAC PARQUE LA SOLEDAD Y SU ENTORNO URBANO',
                    'PAVIMENTACIÓN Y DRENAJE CALLE FELIPE VILLANUEVA 150 M',
                    'PAVIMENTACIÓN Y DRENAJE CALLE MIGUEL HIDALGO',
                    'CENTRO BIENESTAR Y RESGUARDO ANIMAL',
                    'MEXIBÚS TECÁMAC Y CRUCE LAS AMÉRICAS ADECUACIONES GEOMÉTRICAS, RETOÑOS Y CARRETERA FEDERAL MÉXICO-PACHUCA',
                    'PAVIMENTACIÓN CALLE 5 DE MAYO',
                    'REHABILITACIÓN GLORIETA TECÁMAC',
                    'MEXIBÚS CUETZALCÓATL ADECUACIONES GEOMÉTRICAS, RETOÑOS Y CARRETERA FEDERAL MÉXICO-PACHUCA'
                ]
            ],

            // Distrito 5 - Colonia Hueyotenco
            'COLONIA HUEYOTENCO' => [
                'distrito' => 5,
                'obras' => [
                    'PROTECCIÓN CIVIL HANGAR Y HELIPUERTO',
                    'REHABILITACIÓN DE CPZ'
                ]
            ],

            // Distrito 5 - Ex Hacienda Serra Hermosa
            'EX HACIENDA SERRA HERMOSA' => [
                'distrito' => 5,
                'obras' => [
                    'DEPORTIVO SERRA HERMOSA 50%',
                    'MEXIBÚS SAN FRANCISCO ADECUACIONES GEOMÉTRICAS, RETOÑOS Y CARRETERA FEDERAL MÉXICO-PACHUCA',
                    'CON URB VILLAS DEL REAL LONARIA, PRIMARIA, SAN MIGUEL',
                    'OBRAS COMPLEMENTARIAS BOULEVARD GEO SERRA HERMOSA'
                ]
            ],

            // Distrito 20 - San Francisco Cuauhtliquixca
            'SAN FCO CUAUHTLIQUIXCA' => [
                'distrito' => 20,
                'obras' => [
                    'REHABILITACIÓN DE PAVIMENTO CALLE PRINCIPAL',
                    'PAVIMENTACIÓN AVENIDA CENTRAL',
                    'MEJORAMIENTO DE DRENAJE'
                ]
            ],

            // Distrito 20 - Atlautenco / Ejidos
            'ATLAUTENCO / EJIDOS' => [
                'distrito' => 20,
                'obras' => [
                    'REHABILITACIÓN DE PAVIMENTO',
                    'PAVIMENTACIÓN CALLE PRINCIPAL',
                    'MEJORAMIENTO DE ESPACIOS PÚBLICOS'
                ]
            ],

            // Distrito 5 - Santa María Ozumbilla
            'STA. MARÍA OZUMBILLA' => [
                'distrito' => 5,
                'obras' => [
                    'MEXIBÚS OZUMBILLA ADECUACIONES GEOMÉTRICAS, RETOÑOS Y CARRETERA FEDERAL MÉXICO-PACHUCA',
                    'REHABILITACIÓN INTEGRAL DEL CENTRO DE BIENESTAR ANIMAL OZUMBILLA',
                    'REHABILITACIÓN INTEGRAL DE OFICINAS DE SERVICIOS PÚBLICOS EN OZUMBILLA',
                    'PANTEÓN MUNICIPAL OZUMBILLA',
                    'TECHUMBRE EN PLAZA'
                ]
            ],

            // Distrito 5 - Ojo de Agua
            'OJO DE AGUA' => [
                'distrito' => 5,
                'obras' => [
                    'REHABILITACIÓN INTEGRAL DEL CAMELLÓN BOULEVARD OJO DE AGUA DE LINDOS A TULIPANES',
                    'REHABILITACIÓN INTEGRAL DEL CAMELLÓN BOULEVARD OJO DE AGUA DE CAMPO FLORIDO A ACUEDUCTO',
                    'REHABILITACIÓN DE PAVIMENTO VÍA REAL, DE BOULEVARD OJO DE AGUA A BOULEVARD VALLE SAN PEDRO',
                    'TIENDA Y TORRE DE AGUA EN DEPORTIVO FABULANDIA FRACCIONAMIENTO'
                ]
            ],

            // Distrito 5 - Real Verona
            'REAL VERONA' => [
                'distrito' => 5,
                'obras' => [
                    'LONARIA EN VERONA'
                ]
            ],

            // Distrito 5 - Lomas de Tecámac
            'LOMAS DE TECÁMAC' => [
                'distrito' => 5,
                'obras' => [
                    'ZACAZONAPAN ENTRE PONIENTE Y JUAN ESCUTIA'
                ]
            ],

            // Distrito 5 - Esmeralda
            'ESMERALDA' => [
                'distrito' => 5,
                'obras' => [
                    'REHABILITACIÓN DE PAVIMENTO VÍA REAL, DE BOULEVARD VALLE SAN PEDRO A CARRETERA',
                    'AMPLIACIÓN ESMERALDA CONSTRUCCIÓN DE SANTA DE TRANSFERENCIA RSU EN AMPLIACIÓN ESMERALDA',
                    'CENTRO DE REHABILITACIÓN Y TRATAMIENTO DE ADICCIONES PARA JÓVENES'
                ]
            ],

            // Distrito 5 - Héroes Tecámac
            'HÉROES TECÁMAC' => [
                'distrito' => 5,
                'obras' => [
                    'REHABILITACIÓN DE PAVIMENTO AV BOSQUE DE LOS ABEDULES',
                    'GIMNASIO Y ALBERCA EN BOSQUES DE PORTUGAL',
                    'CENTRO DE SERVICIOS PARA VEHÍCULOS AUTOMOTORES, LA CUCHARA',
                    'REHABILITACIÓN CLÍNICA VETERINARIA BOSQUES DE SAN LUIS',
                    'PARQUE CULTURAL AV OZUMBILLA',
                    'ESCUELA DEL DEPORTE',
                    'BARDA EN CEB 4'
                ]
            ],

            // Distrito 20 - San Pedro Atzompa
            'SAN PEDRO ATZOMPA' => [
                'distrito' => 20,
                'obras' => [
                    'REMODELACIÓN INTEGRAL DEL AUDITORIO EJIDAL "EMILIANO ZAPATA"',
                    'REPARACIÓN TECHO DE LECHERÍA LICONSA'
                ]
            ]

            // Colonia Hueyotenco
            'COLONIA HUEYOTENCO' => [
                'PROTECCIÓN CIVIL HANGAR Y HELIPUERTO',
                'REHABILITACIÓN DE CPZ'
            ],

            // Otras colonias importantes
            'EX HACIENDA SERRA HERMOSA' => [
                'DEPORTIVO SERRA HERMOSA 50%',
                'MEXIBÚS SAN FRANCISCO ADECUACIONES GEOMÉTRICAS, RETOÑOS Y CARRETERA FEDERAL MÉXICO-PACHUCA',
                'CON URB VILLAS DEL REAL LONARIA, PRIMARIA, SAN MIGUEL',
                'OBRAS COMPLEMENTARIAS BOULEVARD GEO SERRA HERMOSA'
            ],

            'SAN FCO CUAUHTLIQUIXCA' => [
                'REHABILITACIÓN DE PAVIMENTO CALLE PRINCIPAL',
                'PAVIMENTACIÓN AVENIDA CENTRAL',
                'MEJORAMIENTO DE DRENAJE'
            ],

            'ATLAUTENCO / EJIDOS' => [
                'REHABILITACIÓN DE PAVIMENTO',
                'PAVIMENTACIÓN CALLE PRINCIPAL',
                'MEJORAMIENTO DE ESPACIOS PÚBLICOS'
            ],

            'STA. MARÍA OZUMBILLA' => [
                'MEXIBÚS OZUMBILLA ADECUACIONES GEOMÉTRICAS, RETOÑOS Y CARRETERA FEDERAL MÉXICO-PACHUCA',
                'REHABILITACIÓN INTEGRAL DEL CENTRO DE BIENESTAR ANIMAL OZUMBILLA',
                'REHABILITACIÓN INTEGRAL DE OFICINAS DE SERVICIOS PÚBLICOS EN OZUMBILLA',
                'PANTEÓN MUNICIPAL OZUMBILLA',
                'TECHUMBRE EN PLAZA'
            ],

            'OJO DE AGUA' => [
                'REHABILITACIÓN INTEGRAL DEL CAMELLÓN BOULEVARD OJO DE AGUA DE LINDOS A TULIPANES',
                'REHABILITACIÓN INTEGRAL DEL CAMELLÓN BOULEVARD OJO DE AGUA DE CAMPO FLORIDO A ACUEDUCTO',
                'REHABILITACIÓN DE PAVIMENTO VÍA REAL, DE BOULEVARD OJO DE AGUA A BOULEVARD VALLE SAN PEDRO',
                'TIENDA Y TORRE DE AGUA EN DEPORTIVO FABULANDIA FRACCIONAMIENTO'
            ],

            'REAL TOSCANA' => [
                'LONARIA EN TOSCANA'
            ],

            'REAL CARRARA' => [
                'LONARIA EN CARRARA'
            ],

            'REAL VERONA' => [
                'LONARIA EN VERONA'
            ],

            'LOMAS DE TECÁMAC' => [
                'ZACAZONAPAN ENTRE PONIENTE Y JUAN ESCUTIA'
            ],

            'ESMERALDA' => [
                'REHABILITACIÓN DE PAVIMENTO VÍA REAL, DE BOULEVARD VALLE SAN PEDRO A CARRETERA',
                'AMPLIACIÓN ESMERALDA CONSTRUCCIÓN DE SANTA DE TRANSFERENCIA RSU EN AMPLIACIÓN ESMERALDA',
                'CENTRO DE REHABILITACIÓN Y TRATAMIENTO DE ADICCIONES PARA JÓVENES'
            ],

            'HÉROES TECÁMAC' => [
                'REHABILITACIÓN DE PAVIMENTO AV BOSQUE DE LOS ABEDULES',
                'GIMNASIO Y ALBERCA EN BOSQUES DE PORTUGAL',
                'CENTRO DE SERVICIOS PARA VEHÍCULOS AUTOMOTORES, LA CUCHARA',
                'REHABILITACIÓN CLÍNICA VETERINARIA BOSQUES DE SAN LUIS',
                'PARQUE CULTURAL AV OZUMBILLA',
                'ESCUELA DEL DEPORTE',
                'BARDA EN CEB 4'
            ],

            'SAN PEDRO ATZOMPA' => [
                'REMODELACIÓN INTEGRAL DEL AUDITORIO EJIDAL "EMILIANO ZAPATA"',
                'REPARACIÓN TECHO DE LECHERÍA LICONSA'
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

        // Colonias adicionales sin obras específicas (para completar el censo)
        $coloniasSinObras = [
            'AMPLIACIÓN SANTO DOMINGO' => 5,
            'COLONIA ISIDRO FABELA' => 20,
            'COLONIA LA NOPALERA' => 20,
            'FRACC GALAXIAS EL LLANO' => 5,
            'PASEOS DE TECAMAC' => 5,
            'RANCHO LA LUZ' => 20,
            'FRACC REAL DE SAN GERMÁN DEL BOSQUE' => 5,
            'COLONIA SAN JOSÉ EJIDOS DE TECAMAC' => 20,
            'COLONIA EJIDOS DE TECAMAC' => 20,
            'COLONIA 6 DE MAYO' => 5,
            'COLONIA SAN MARTÍN AZCATEPEC' => 20,
            'PUEBLO SAN PABLO TECALCO' => 5,
            'FRACC REAL DE TECALCO' => 5,
            'FRACC VILLA DEL REAL 1ERA Y 2DA SECCIÓN' => 5,
            'COLONIA LOS OLIVOS' => 5,
            'FRACC VILLA DEL REAL 3RA A 4TA SECCIÓN' => 5,
            'CONJUNTO URBANO LAS FLORES' => 5,
            'VISTA HERMOSA' => 20,
            'LOMAS DE OZUMBILLA' => 5,
            'CONJUNTO URBANO EL TEJOCOTE JIQUIPILCO' => 20,
            'COLONIA AMPLIACIÓN OZUMBILLA' => 5,
            'FRACC PROVENZAL DEL BOSQUE' => 5
        ];

        foreach ($coloniasSinObras as $nombreColonia => $distrito) {
            Colonia::create([
                'nombre' => $nombreColonia,
                'distrito' => $distrito,
            ]);
        }
    }
}
