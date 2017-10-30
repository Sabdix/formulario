<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class FormController extends Controller
{
	public function index() {
		$licences_json = file_get_contents("http://catalogs.repositorionacionalcti.mx/webresources/licencia");
		$licences_array = json_decode($licences_json);
		return view('welcome', ['licence' =>$licences_array], ['array_licence' => $licences_json]);
	}
	
	public function consultarRepo() {
		ini_set('max_execution_time', 240); //240 segundos = 4 minutos
		$counter = 0;
		$csv;
		
		// Parsear CSV
		if (isset($_FILES['file']) && $_FILES['file']['type'] == "application/vnd.ms-excel") {
			$fileName = $_FILES['file']['tmp_name'];
			$file = fopen($fileName, "r");
			if ($file != null) {
				while($data = fgetcsv($file, 1000, ",")) {
					for ($l = 0; $l < count($data); $l++) {
						$csv[$counter][$l] = $data[$l];
					}
					$counter++;
				}
			}
		}
		// Aqui contamos la cantidad de registros del csv
		$cont = count($csv, 0);
		$people;
		$header_author;
		// Verificamos la ubicación de Identifica
		for ($a = 0; $a < count($csv[0],0); $a++) {
			if (strcmp($csv[0][$a], "Identifica") == 0) {
				$header_author = $a;
				break;
			}
		}
		// Quitar registros Blancos
		for ($p = 1; $p < $cont; $p++) {
			if (strcmp($csv[$p][$header_author], "") !== 0) {
				$people[$csv[$p][$header_author]][$csv[$p][0]] = $csv[$p];
			}
		}
        $counter = 0;
		foreach ($people as $name => $value) {
			$person_array[$counter] = $this->consultPerson($name);
			$counter++;
		}
		// person_array contiene el nombre de todos las personas
		dd($person_array);

		// Obtener Colaboradores
		for ($a = 0; $a < count($csv[0],0); $a++) {
			if (strcmp($csv[0][$a], "Colabora con") == 0) {
				$header_colaborator = $a;
				break;
			}	
		}
		//dd($person_array);

		return view ('repositorios', ['person' => $person_array], ['file'=>$csv] );
    }

    private function consultPerson($name) {
   		$username = "enesmore";
		$password = "enesM05_06";
		$person_array;
		$nametmp = "";
    	// Obtener Personas
		$url = "http://catalogs.repositorionacionalcti.mx/webresources/persona/byNombreCompleto/params;nombre=". str_replace(" ", "%20", $name);
		$request = array(
			'http' => array(
				'method' => "GET",
				'header' => "Authorization: Basic " . base64_encode("$username:$password")
			)
		);
		$context = stream_context_create($request);
		$person_json = file_get_contents($url, false, $context);
		if (strcmp($person_json, "") !== "") {
			$person_array = json_decode($person_json);
			// Hash
			$person[$name] = $person_array;
			$cont_aut_available = count($person[$name]);
			if ($cont_aut_available > 1) {
				$list_to_select;
				for ($i = 1; $i < $cont_aut_available; $i++) {
                    $nametmp .= $person[$name][$i]->nombres . " ";
                    $nametmp .= $person[$name][$i]->primerApellido;
                    if( isset($person[$name][$i]->segundoApellido)){
                        $nametmp .= $person[$name][$i]->segundoApellido;
                    }                        
					$list_to_select[$i] = $nametmp;
					// Regresar lista de nombres cuando definamos qué sucedera cuando hay muchos
				}
			} elseif($cont_aut_available == 1) {
                $nametmp .= $person[$name][0]->nombres . " ";
                $nametmp .= $person[$name][0]->primerApellido;
                if( isset($person[$name][0]->segundoApellido)){
                  	$nametmp .= $person[$name][0]->segundoApellido;
                } 
                return $nametmp;           
                         
			} else {
				return $nametmp = "No se encontro el autor";
			}
		}
		return $nametmp;
    }
}
