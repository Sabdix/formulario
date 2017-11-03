<!DOCTYPE html>
<html>

<head>
    <title>Tabla</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
</head>

<body>
    <h1>Mostrar Resultados</h1>
    <table class="table table-stripped"> <!--Tabla base donde mostramos los metadatos que hemos recolectado-->
        <tr>
            <th>Identificador del recurso</th>
            <th>Autor</th>
            <th>Titulo</th>
            <th>Editor</th>
            <th>Año de publicación</th>
            <th>Colaboladores</th>
            <th>Fecha</th>
            <th>Identificador relacionado</th>
            <th>Licencia</th>
            <th>Descripción</th>
        </tr>
        <!--Este tr se tiene que meter en un foreach-->
        <tr>
            @if(isset($response->identifier))
                <td>{{$response->identifier}}</td>
            @else
                <td>No se econtro id</td>
            @endif
            
            @if(isset($response->author))
                <td>{{$response->author}}</td>
            @else
                <td>No se econtro autor</td>
            @endif
            
            @if(isset($response->title))
                <td>{{$response->title}}</td>
            @else
                <td>No se econtro titulo</td>
            @endif
            
            @if(isset($response->publisher))
                <td>{{$response->publisher}}</td>
            @else
                <td>No se econtro publisher</td>
            @endif
            
            @if(isset($response->year))
                <td>{{$response->year}}</td>
            @else
                <td>No se econtro year</td>
            @endif
            
            @if(isset($response->colabolator))
                <td>{{$response->colabolato}}</td>
            @else
                <td>No se econtro colabolador</td>
            @endif
            
            @if(isset($response->date))
                <td>{{$response->date}}</td>
            @else
                <td>No se econtro fecha</td>
            @endif
            
            @if(isset($response->id_related))
                <td>{{$response->id_related}}</td>
            @else
                <td>No se econtro id relacionado</td>
            @endif
            
            @if(isset($response->rights))
                <td>{{$response->rights}}</td>
            @else
                <td>No se econtro licencia</td>
            @endif
            
            @if(isset($response->description))
                <td>{{$response->description}}</td>
            @else
                <td>No se econtro descripcion</td>
            @endif
        <tr>
    </table>
</body>

</html>
