<!DOCTYPE html>
<html>
<head>
	<title>Tabla</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
</head>
<body>
	<h1>Mostrar Resultados</h1>
	<table class="table table-stripped">
		<tr>
			<th>Orcid</th>
			<th>IdPersona</th>
			<th>Nombre(s)</th>
			<th>Primer Apellido</th>
			<th>Tipo de Persona</th>
		</tr>
		<tr>
			@if(isset($person) && count($person) != 0)
				{{ dd($person) }}
				@foreach($person as $p=>$value)
					@if(count($p))
						<tr>
							<td>{{ $value->idOrcid }}</td>
							<td>{{ $value->idPersona }}</td>
							<td>{{ $value->nombres }}</td>
							<td>{{ $value->primerApellido }}</td>
							<td>{{ $value->tipoPersona }}</td>
						</tr>
					@endif
				@endforeach
            @endif
		</tr>
	</table>
</body>
</html>