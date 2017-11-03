<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class FormController extends Controller
{
    private $response;
    private $fileOriginal;

    function __construct(){
        $this->response = new \stdClass();
    }
    
	public function index() {
		$licences_json = file_get_contents("http://catalogs.repositorionacionalcti.mx/webresources/licencia");
		$licences_array = json_decode($licences_json);
		return view('welcome', ['licence' =>$licences_array], ['array_licence' => $licences_json]);
	}
	
	public function consultarRepo(Request $request) {
		ini_set('max_execution_time', 240); //240 segundos = 4 minutos
		$counter = 0;
		$csv;
		
//----------------------------------------- Parsear CSV --------------------------------------------------------
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
		} else {
          dd("No se ha seleccionado un arhivo, favor de seleccionarlo");  
        }
        
//-------------------------------- Aqui contamos la cantidad de registros del csv -------------------------------
		$cont = count($csv, 0);
        
//-------------------------------- Obteniendo ubicaciones dentro del csv ----------------------------------------
        // Obtenemos la ubicación de Identifica o autor
        $header_author;
		for ($a = 0; $a < count($csv[0],0); $a++) {
			if (strcmp($csv[0][$a], "Identifica") == 0) {
				$header_author = $a;
				break;
			}
		}
        
        //Obtenemos la ubicación de la fecha de colecta | registro
        $header_date;
        for ($a = 0; $a < count($csv[0],0); $a++) {
			if (strcmp($csv[0][$a], "Fecha") == 0) {
				$header_date = $a;
				break;
			}
		}
            
        //Obtenemos la ubicación de los colaboradores
        //$counter = 1;
        $header_colaborator;
		for ($a = 0; $a < count($csv[0],0); $a++) {
			if (strcmp($csv[0][$a], "Colectado con") == 0) {
				$header_colaborator = $a;
				break;
			}	
		}
        
        //Obtenemos la posición del resumen o descripcion
        for ($a = 0; $a < count($csv[0],0); $a++) {
			if (strcmp($csv[0][$a], "Observaciones") == 0) {
				$header_description = $a;
				break;
			}	
		}
        
//---------------------------- Quitar registros Blancos y factorizamos los registros por autor -----------------------
 		for ($p = 1; $p < $cont; $p++) {
			if (strcmp($csv[$p][$header_author], "") !== 0) {
				$people[$csv[$p][$header_author]][$csv[$p][0]] = $csv[$p];
			}
		}
        
//-----------------------------------------Obtener metadatos ----------------------------------------------------------
         //-------------------------------metadatos generales--------------------------------------------
        
        //Obtenmos el titulo (del dataset seleccionado en el formulario de welcome.blade)
        $title  = $request->input('title');
        
        //Obtemos el editor (del login de un inicio)
        $publisher = "";
        
         //Obtenemos la condicion de licencia, seleccionada desde un inicio en el formulario
        $rights = $request->input('rights');
        if(strcmp($rights, "Elige una") != 0){
            $this->response->rights = $rights;
        } else {
            dd("No se eligio el tipo de licencia, favor de seleccionarla");
        }
        
        //----------------------------metadatos por registro---------------------------------------------
        $counter = 0;
        //dd(fgetcsv($file, 1000, ","));
        $file = fopen($fileName, "r");
        if ($file != null) {
			while($data = fgetcsv($file, 1000, ",")) {
                if($counter != 0){//nos saltamos la primer linea
                    for ($l = 0; $l < count($data); $l++) {
                        $csv[$counter][$l] = $data[$l];
                        // Autor
                        if($l == $header_author){
                            $author = $data[$l];
                        }
                
                        //Identificador del recurso , pendiente a hacerlo
        
                        //Fecha de colecta | registro
        
                        //Año *pendiente
            
                        //Colaboradores
        
                        //Identificador relacionado, pendiente
        
                        //Resumen o descripcion
                        
                    
				    }
                }
                $counter++;
            }   
        }
        
        
// Consulta a Conacyt de autores
        $counter = 1;
		foreach ($people as $name => $value) {
			$person_array[$this->consultPerson(trim($name))][$csv[$counter][0]] = "Si";
			$counter++;
		}
        // person_array contiene el nombre de todos las personas ya buscadas
        
// __________________________________________________________________________________
		// Obtener Colaboradores

		// Obtenemos la lista de colaboradores
        $counter = 1;
		foreach ($people as $name => $field) {
			for ($i=0; $i < count($people[$name],0); $i++) {
				$colaborator_array[$counter] = explode(",", $people[$name][$counter][$header_colaborator]);
				$counter++;
			}
		}
		// Quitamos los colaboradores repetidos
		$colaborator_people;
		foreach ($colaborator_array as $id => $array) {
			for ($r=0; $r < count($array); $r++) {
				$colaborator_people[$array[$r]] = 0;
			}
		}
		// COnsulta a Conacyt
		$counter = 0;
		foreach ($colaborator_people as $name => $val) {
			$colaborator_collection[$counter] = $this->consultPerson(trim($name));
			$counter++;
		}
		//dd($colaborator_collection);
		// colaborator_collection contiene el nombre de los colaboradores ya buscados
        
        $this->response->person = $person_array;
        $this->response->file = $csv;
        $this->response->title = $title;
        $this->response->author = $author;
		return view ('repositorios', ['response' => $this->response]);
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
                    if( isset($person[$name][$i]->primerApellido)){
                        $nametmp .= $person[$name][$i]->primerApellido;
                    }
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
