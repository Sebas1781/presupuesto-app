<?php
// Script temporal para generar datos de educación de prueba
// Esto simulará los datos mientras solucionamos el problema del distrito

// Para distrito 20
$distrito20EducacionData = collect([
    (object)[
        'colonia' => 'Distrito 20 (Ejemplo)',
        'nivel_educativo' => 'Educación Básica',
        'total' => 25
    ],
    (object)[
        'colonia' => 'Distrito 20 (Ejemplo)',
        'nivel_educativo' => 'Educación Media Superior',
        'total' => 15
    ],
    (object)[
        'colonia' => 'Distrito 20 (Ejemplo)',
        'nivel_educativo' => 'Educación Superior',
        'total' => 10
    ]
]);

// Para distrito 5
$distrito5EducacionData = collect([
    (object)[
        'colonia' => 'Distrito 5 (Ejemplo)',
        'nivel_educativo' => 'Educación Básica',
        'total' => 30
    ],
    (object)[
        'colonia' => 'Distrito 5 (Ejemplo)',
        'nivel_educativo' => 'Educación Media Superior',
        'total' => 20
    ],
    (object)[
        'colonia' => 'Distrito 5 (Ejemplo)',
        'nivel_educativo' => 'Educación Superior',
        'total' => 8
    ]
]);

echo "Datos de ejemplo generados\n";
?>