<!DOCTYPE html>
<html>
	<head>
		<title>Formulario</title>
		<link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
	</head>
	<body>
		<h1>Formulario</h1>
		<div class="row">
			<div class="col-sm-1"></div>
			<div class="col-sm-6">
				<form method="post" action="{{route( 'mostrar' )}}" enctype="multipart/form-data">
					<fieldset>
						<legend>Archivo</legend>
						{!! csrf_field() !!}
						Abrir Archivo .csv: <br>
						<input type="file" name="file" accept=".csv" id="file">
						<br><br>
					</fieldset>
					<fieldset>
                        <legend>Formulario</legend>
                        Título <br>
                        <input type="text" name="title" required><br> Condiciones de Licencia: <br>
                        <select required name="rights" id="rights" onchange="mostrar(this.value)">
                            <option selected="0">Elige una</option>
                            @if(isset($licencia))
                            @forelse($licence as $l)
                            <option value="{{$l->idLicencia}}">{{$l->descCorta}}</option>
                            @empty
                            <option>No se pudieron obtener las licencias</option>
                            @endforelse
                            @endif
                        </select><br><br>
                        @if(isset($licence))
                        @forelse($licence as $l)
                        <span class="desc" id="{{$l->idLicencia}}" style="display: none">{{$l->descripcion}}</span>
                        @empty
                        <span>No se encontro</span>
                        @endforelse
                        @endif
                        <br><br>
                        <input type="submit" name="Enviar" value="Enviar">
                    </fieldset>

				</form>
			</div>
		</div>
	</body>

	<script type="text/javascript">
    function mostrar(val) {
    	var value = "#"+val;
    	$(document).ready(function() {
    	$(".desc").hide();
    	$(value).show();
    	});
    }
    </script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>

	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
</html>