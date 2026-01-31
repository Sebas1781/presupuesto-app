@extends('adminlte::page')

@section('title', 'Encuestas - Presupuesto Participativo 2026')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Lista de Encuestas</h1>
        <a href="{{ route('admin.export.encuestas', request()->query()) }}" class="btn btn-success">
            <i class="fas fa-download"></i> Exportar a Excel
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Filtros</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.encuestas.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="colonia_id">Colonia</label>
                            <select name="colonia_id" id="colonia_id" class="form-control">
                                <option value="">Todas las colonias</option>
                                @foreach($colonias as $colonia)
                                    <option value="{{ $colonia->id }}" 
                                            {{ request('colonia_id') == $colonia->id ? 'selected' : '' }}>
                                        {{ $colonia->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="genero">Género</label>
                            <select name="genero" id="genero" class="form-control">
                                <option value="">Todos</option>
                                <option value="Mujer" {{ request('genero') == 'Mujer' ? 'selected' : '' }}>Mujer</option>
                                <option value="Hombre" {{ request('genero') == 'Hombre' ? 'selected' : '' }}>Hombre</option>
                                <option value="LGBTTIQ+" {{ request('genero') == 'LGBTTIQ+' ? 'selected' : '' }}>LGBTTIQ+</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_desde">Fecha Desde</label>
                            <input type="date" name="fecha_desde" id="fecha_desde" class="form-control" 
                                   value="{{ request('fecha_desde') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha_hasta">Fecha Hasta</label>
                            <input type="date" name="fecha_hasta" id="fecha_hasta" class="form-control" 
                                   value="{{ request('fecha_hasta') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filtrar
                                </button>
                                <a href="{{ route('admin.encuestas.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Limpiar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Total: {{ $encuestas->total() }} encuestas</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Colonia</th>
                        <th>Género</th>
                        <th>Edad</th>
                        <th>Nivel Educativo</th>
                        <th>Estado Civil</th>
                        <th>Propuestas</th>
                        <th>Reportes</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($encuestas as $encuesta)
                    <tr>
                        <td>{{ $encuesta->id }}</td>
                        <td>{{ $encuesta->colonia->nombre ?? 'N/A' }}</td>
                        <td>{{ $encuesta->genero }}</td>
                        <td>{{ $encuesta->edad }}</td>
                        <td>{{ $encuesta->nivel_educativo }}</td>
                        <td>{{ $encuesta->estado_civil }}</td>
                        <td>
                            <span class="badge badge-info">{{ $encuesta->propuestas->count() }}</span>
                        </td>
                        <td>
                            @if($encuesta->reportes->count() > 0)
                                <span class="badge badge-warning">{{ $encuesta->reportes->count() }}</span>
                            @else
                                <span class="badge badge-secondary">0</span>
                            @endif
                        </td>
                        <td>{{ $encuesta->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.encuestas.show', $encuesta->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">No se encontraron encuestas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            {{ $encuestas->links() }}
        </div>
    </div>
@stop