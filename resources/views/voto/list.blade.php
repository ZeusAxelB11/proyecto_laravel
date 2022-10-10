@extends('plantilla')
@section('content')

<div>
    @if(session()->get('success'))
    <div class="alert alert-success">
        {{ session()->get('success') }}
    </div><br />
    @endif
    <table class="table table-striped">
        <thead>
            <tr>
                <td>ID</td>
                <td>Eleccion</td>
                <td>Casilla</td>
                <td>Candidatos</td>
                <td colspan="2">Action</td>
            </tr>
        </thead>
        <tbody>
            @foreach($votos as $voto)
            <tr>
                <td>{{$voto->id}}</td>
                <td>{{$voto->eleccion->periodo}}</td>
                <td>{{$voto->casilla->ubicacion}}</td>
                <td>
                    <table class="table">
                     @foreach($voto->candidatos as $candidato)
                        <tr>
                            <td>{{$candidato->nombrecompleto}} </td>
                            <td><input type="text" readonly
                            value="{{$candidato->pivot->votos}}"
                            name="candidato_{{$candidato->id}}"  > </td>
                        </tr>
                    @endforeach
                    </table>
                 </td>
                <td><a href="{{ route('voto.edit', $voto->id)}}"
                    class="btn btn-primary">Edit</a></td>
                    <td>
                    <form action="{{ route('voto.destroy', $voto->id)}}"
                        method="post">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit"
                        onclick="return confirm('Esta seguro de borrar {{$voto->id}}')" >Del</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div>
  @endsection
