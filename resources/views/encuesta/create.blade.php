<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuesta Presupuesto Participativo 2026</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">Encuesta Presupuesto Participativo 2026</h1>
                <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">
                    Volver al Inicio
                </a>
            </div>
        </div>
    </header>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">
                Formulario de Encuesta
            </h2>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('encuesta.store') }}" enctype="multipart/form-data" x-data="encuestaForm()">
                @csrf

                <!-- Datos Sociodemográficos -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6 text-red-600">DATOS SOCIODEMOGRÁFICOS</h3>

                    <!-- Colonia -->
                    <div class="mb-6">
                        <label for="colonia_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Colonia / Comunidad *
                        </label>
                        <select id="colonia_id" name="colonia_id" x-model="coloniaSeleccionada" @change="cargarObras()" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccione una colonia...</option>
                            @foreach($colonias as $colonia)
                                <option value="{{ $colonia->id }}">{{ $colonia->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Género -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Género *</label>
                            <select name="genero" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Seleccione...</option>
                                <option value="Mujer">Mujer</option>
                                <option value="Hombre">Hombre</option>
                                <option value="LGBTTIQ+">LGBTTIQ+</option>
                            </select>
                        </div>

                        <!-- Edad -->
                        <div>
                            <label for="edad" class="block text-sm font-medium text-gray-700 mb-2">Edad *</label>
                            <input type="number" id="edad" name="edad" min="1" max="120" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6 mt-6">
                        <!-- Nivel Educativo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nivel Educativo *</label>
                            <select name="nivel_educativo" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Seleccione...</option>
                                <option value="Ninguno">Ninguno</option>
                                <option value="Preescolar">Preescolar</option>
                                <option value="Primaria">Primaria</option>
                                <option value="Secundaria">Secundaria</option>
                                <option value="Media Superior">Media Superior</option>
                                <option value="Superior">Superior</option>
                                <option value="Posgrado">Posgrado</option>
                            </select>
                        </div>

                        <!-- Estado Civil -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estado Civil *</label>
                            <select name="estado_civil" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Seleccione...</option>
                                <option value="Soltero/a">Soltero/a</option>
                                <option value="Casado/a">Casado/a</option>
                                <option value="Divorciado/a">Divorciado/a</option>
                                <option value="Separado/a">Separado/a</option>
                                <option value="Viudo/a">Viudo/a</option>
                                <option value="Concubinato">Concubinato</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Obra Pública -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6 text-red-600">OBRA PÚBLICA</h3>

                    <div class="bg-green-50 p-4 rounded-md mb-6">
                        <p class="text-sm text-green-700">
                            <strong>Se despliega el listado de las obras conforme a la Colonia seleccionada</strong>
                        </p>
                        <p class="text-sm text-gray-600 mt-2">
                            Califique la prioridad de estas obras públicas de 0 a 5 (donde 1 es poco urgente y 5 es muy urgente).
                        </p>
                    </div>

                    <div x-show="obrasPublicas.length > 0" class="space-y-4">
                        <template x-for="obra in obrasPublicas" :key="obra.id">
                            <div class="border border-gray-200 rounded-md p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="font-medium text-gray-800" x-text="obra.nombre"></h4>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-gray-500">Calificación (0-5):</span>
                                        <input type="number" :name="'obras_calificadas[' + obra.id + ']'" min="0" max="5"
                                               class="w-16 px-2 py-1 border border-gray-300 rounded text-center focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </div>
                                </div>
                                <p class="text-sm text-gray-600" x-text="obra.descripcion"></p>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Mis Propuestas -->
                <div class="mb-8" x-data="{ mostrarPropuestas: false }">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6 text-red-600">MIS PROPUESTAS (Máximo 2 propuestas)</h3>

                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" x-model="mostrarPropuestas" class="mr-2">
                            <span class="text-sm font-medium text-gray-700">¿Tienes alguna otra propuesta? Agrégala AQUÍ</span>
                        </label>
                    </div>

                    <div x-show="mostrarPropuestas" class="space-y-8">
                        <!-- Propuesta 1 -->
                        <div class="border border-gray-300 rounded-lg p-6">
                            <h4 class="font-semibold text-gray-800 mb-4">Propuesta 1</h4>

                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de propuesta</label>
                                    <select name="propuestas[0][tipo_propuesta]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Seleccione...</option>
                                        <option value="Obra">Obra</option>
                                        <option value="Bacheo">Bacheo</option>
                                        <option value="Luminaria">Luminaria</option>
                                        <option value="Apoyo">Apoyo</option>
                                        <option value="Seguridad pública">Seguridad pública</option>
                                        <option value="Bienestar animal">Bienestar animal</option>
                                        <option value="Recolección de RSU">Recolección de RSU</option>
                                        <option value="Otra">Otra</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nivel de prioridad</label>
                                    <select name="propuestas[0][nivel_prioridad]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Seleccione...</option>
                                        <option value="Urgente">Urgente</option>
                                        <option value="Alta">Alta</option>
                                        <option value="Normal">Normal</option>
                                        <option value="Baja">Baja</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Personas beneficiadas</label>
                                <select name="propuestas[0][personas_beneficiadas]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Seleccione...</option>
                                    <option value="Toda la comunidad">Toda la comunidad</option>
                                    <option value="Algunos vecinos">Algunos vecinos</option>
                                    <option value="Yo u otra persona">Yo u otra persona</option>
                                </select>
                            </div>

                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Agrega una fotografía como referencia</label>
                                <input type="file" name="propuestas[0][fotografia]" accept="image/*"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Localiza el punto exacto de tu propuesta 📍 (Google maps)</label>
                                <input type="text" name="propuestas[0][ubicacion]" placeholder="URL de Google Maps o descripción de la ubicación"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Descripción breve de tu propuesta</label>
                                <textarea name="propuestas[0][descripcion_breve]" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>
                        </div>

                        <!-- Propuesta 2 -->
                        <div class="border border-gray-300 rounded-lg p-6">
                            <h4 class="font-semibold text-gray-800 mb-4">Propuesta 2</h4>

                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de propuesta</label>
                                    <select name="propuestas[1][tipo_propuesta]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Seleccione...</option>
                                        <option value="Obra">Obra</option>
                                        <option value="Bacheo">Bacheo</option>
                                        <option value="Luminaria">Luminaria</option>
                                        <option value="Apoyo">Apoyo</option>
                                        <option value="Seguridad pública">Seguridad pública</option>
                                        <option value="Bienestar animal">Bienestar animal</option>
                                        <option value="Recolección de RSU">Recolección de RSU</option>
                                        <option value="Otra">Otra</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nivel de prioridad</label>
                                    <select name="propuestas[1][nivel_prioridad]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Seleccione...</option>
                                        <option value="Urgente">Urgente</option>
                                        <option value="Alta">Alta</option>
                                        <option value="Normal">Normal</option>
                                        <option value="Baja">Baja</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Personas beneficiadas</label>
                                <select name="propuestas[1][personas_beneficiadas]" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Seleccione...</option>
                                    <option value="Toda la comunidad">Toda la comunidad</option>
                                    <option value="Algunos vecinos">Algunos vecinos</option>
                                    <option value="Yo u otra persona">Yo u otra persona</option>
                                </select>
                            </div>

                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Agrega una fotografía como referencia</label>
                                <input type="file" name="propuestas[1][fotografia]" accept="image/*"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Localiza el punto exacto de tu propuesta 📍 (Google maps)</label>
                                <input type="text" name="propuestas[1][ubicacion]" placeholder="URL de Google Maps o descripción de la ubicación"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Descripción breve de tu propuesta</label>
                                <textarea name="propuestas[1][descripcion_breve]" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reporte Anónimo -->
                <div class="mb-8" x-data="{ deseaReporte: false }">
                    <h3 class="text-xl font-semibold text-gray-800 mb-6 text-red-600">REPORTE ANÓNIMO</h3>

                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="desea_reporte" value="1" x-model="deseaReporte" class="mr-2">
                            <span class="text-sm font-medium text-gray-700">¿Deseas realizar algún reporte anónimo?</span>
                        </label>
                    </div>

                    <div x-show="deseaReporte">
                        <p class="text-sm text-gray-600 mb-6 italic">
                            No me encuentro en una situación de emergencia médica o peligro de muerte en este momento
                            (Este formulario no es monitoreado 24/7. Si tienes una emergencia, no uses este sitio, llama al 911 inmediatamente)
                        </p>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de reporte</label>
                            <select name="tipo_reporte" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Seleccione...</option>
                                <option value="Maltrato infantil">Maltrato infantil</option>
                                <option value="Violencia de género">Violencia de género</option>
                                <option value="Violencia a persona con discapacidad">Violencia a persona con discapacidad</option>
                                <option value="Maltrato animal">Maltrato animal</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Descripción del problema</label>
                            <textarea name="descripcion_reporte" rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Enviar evidencia (opcional) 📄</label>
                            <input type="file" name="evidencia_reporte" accept="image/*,application/pdf"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Localiza el punto exacto de la ubicación (opcional) 📍</label>
                            <input type="text" name="ubicacion_reporte" placeholder="URL de Google Maps o descripción de la ubicación"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Botón de envío -->
                <div class="text-center">
                    <button type="submit"
                            class="bg-blue-600 text-white font-semibold px-8 py-3 rounded-lg hover:bg-blue-700 transform hover:scale-105 transition-all duration-300 shadow-lg">
                        Enviar Encuesta
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function encuestaForm() {
            return {
                coloniaSeleccionada: '',
                obrasPublicas: [],

                async cargarObras() {
                    if (this.coloniaSeleccionada) {
                        try {
                            const response = await fetch(`/encuesta/obras-por-colonia/${this.coloniaSeleccionada}`);
                            this.obrasPublicas = await response.json();
                        } catch (error) {
                            console.error('Error cargando obras:', error);
                            this.obrasPublicas = [];
                        }
                    } else {
                        this.obrasPublicas = [];
                    }
                }
            }
        }
    </script>
</body>
</html>
