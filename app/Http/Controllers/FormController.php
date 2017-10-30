<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class FormController extends Controller
{
	public function index() {
		$licencias_json = file_get_contents("http://catalogs.repositorionacionalcti.mx/webresources/licencia");
		$licencias_array = json_decode($licencias_json);
		return view('welcome', ['licencia' =>$licencias_array], ['array_licencia' => $licencias_json]);
	}
	
	public function consultarRepo() {
		ini_set('max_execution_time', 240); //240 segundos = 4 minutos
		$username = "enesmore";
		$password = "enesM05_06";
		$contador = 0;
		$csv;
		
		// Parsear CSV
		if (isset($_FILES['archivo']) && $_FILES['archivo']['type'] == "application/vnd.ms-excel") {
			$fileName = $_FILES['archivo']['tmp_name'];
			$file = fopen($fileName, "r");
			if ($file != null) {
				while($data = fgetcsv($file, 1000, ",")) {
					for ($l = 0; $l < count($data); $l++) {
						$csv[$contador][$l] = $data[$l];
					}
					$contador++;
				}
			}
		}
		// Aqui contamos la cantidad de registros del csv
		$cont = count($csv, 0);
		$people;
		$encabezado_autor;
		// Verificamos la ubicación de Identifica
		for ($a = 0; $a < count($csv[0],0); $a++) {
			if (strcmp($csv[0][$a], "Identifica") == 0) {
				$encabezado_autor = $a;
				break;
			}
		}

		for ($p = 1; $p < $cont; $p++) {
			if (strcmp($csv[$p][$encabezado_autor], "") !== 0) {
				$people[$csv[$p][$encabezado_autor]][$csv[$p][0]] = $csv[$p];
			}
		}
        $persona_array;
		foreach ($people as $name => $value) {
			// Obtener Personas

			$url = "http://catalogs.repositorionacionalcti.mx/webresources/persona/byNombreCompleto/params;nombre=". str_replace(" ", "%20", $name);
			$request = array(
				'http' => array(
					'method' => "GET",
					'header' => "Authorization: Basic " . base64_encode("$username:$password")
				)
			);
			$context = stream_context_create($request);
			$personas_json = file_get_contents($url, false, $context);
			if (strcmp($personas_json, "") !== "") {
				$persona_array = json_decode($personas_json);
				$person[$name] = $persona_array;
				$cont_aut_available = count($person[$name]);
				if ($cont_aut_available > 1) {
					$list_to_select;
					for ($i = 1; $i < $cont_aut_available; $i++) {
                        $nombre = "";
                        $nombre .= $person[$name][$i]->nombres . " ";
                        $nombre .= $person[$name][$i]->primerApellido;
                        if( isset($person[$name][$i]->segundoApellido)){
                            $nombre .= $person[$name][$i]->segundoApellido;
                        }                        
						$list_to_select[$i] = $nombre;
					}
				}
			}
		}
		dd($person);
		return view ('repositorios', ['persona' => $persona_array], ['file'=>$csv] );
    }
}
