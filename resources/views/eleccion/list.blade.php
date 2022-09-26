@extends('plantilla')
@section('content')
<style>
    .uper {
    margin-top: 40px;
    }
</style>

<div class="uper">
    @if(session()->get('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div>
    <br />
    @endif
<table class="table table-striped">
<thead>
        <tr>
            <td>ID</td>
            <td>PERIODO</td>
            <td>FECHA</td>
            <td>FECHAA DE APERTURA</td>
            <td>HORA DE APERTURA</td>
            <td>FECHA DE CIERRE</td>
            <td>HORA DE CIERRE</td>
            <td>OBSERVACIONES</td>
            <td colspan="2">ACTION</td>
        </tr>
    </thead>
    <tbody>
        @foreach($elecciones as $eleccion)
        <tr>
            <td>{{$eleccion->id}}</td>
            <td>{{$eleccion->periodo}}</td>
            <td>{{$eleccion->fecha->format('d/m/Y')}}</td>
            <td>{{$eleccion->fechaapertura->format('d/m/Y')}}</td>
            <td>{{$eleccion->horaapertura->format('H:i')}}</td>
            <td>{{$eleccion->fechacierre->format('d/m/Y')}}</td>
            <td>{{$eleccion->horacierre->format('H:i')}}</td>
            <td>{{$eleccion->observaciones}}</td>
            <td><a href="{{ route('eleccion.edit', $eleccion->id)}}"
            class="btn btn-primary">Edit</a></td>
            <td>
            <form action="{{ route('eleccion.destroy', $eleccion->id)}}"
            method="post">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger" type="submit"
            onclick="return confirm('Esta seguro de borrar {{$eleccion->periodo}}')" >Del</button>
            </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>    
<div>
@endsection