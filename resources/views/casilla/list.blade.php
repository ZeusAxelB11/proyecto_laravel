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
        {{session()->get('success')}}
    </div><br>
    @endif
    <table class="table table-striped">
        <thead>
            <tr>
                <td>ID</td>
                <td>UBICACION</td>
                <td colspan="2">Action</td>
            </tr>
        </thead>
        <tbody>
            @foreach($casillas as $casilla)
            <tr>
                <td>{{$casilla->id}}</td>
                <td>{{$casilla->ubicacion}}</td>
                <td><a href="{{route('casilla.edit', $casilla->id)}}" class="btn btn-primary">Edit</a></td>
                <td>
                    <form action="{{route('casilla.destroy', $casilla->id)}}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit" onclick="return confirm('ESta seguro de borrar {{$casilla->ubicacion}}')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <input type="button" class="btn btn-info" value ="Hazme click" onclick="showMessage('Test for function showMessage using jQuery');">
    </input>
</div>
<br>
<div><a href="http://localhost:8888/casilla/pdf" class="btn btn-dark bg-dark">PDF VIEW</a></div>
@endsection
