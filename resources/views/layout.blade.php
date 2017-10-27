@extends('welcome')
@section('content')
<h1>Form</h1>
<div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-6">
        <form method="post" action="{{route( 'formulario' )}}" enctype="multipart/form-data">
            <div class="form-group">
                {!! csrf_field() !!}
                Abrir Archivo .csv: <br>
                <input type="file" name="archivo" accept=".csv">
                <br><br>
                <input type="submit" value="Aceptar">
            </div>
        </form>
    </div>
</div>
@endsection