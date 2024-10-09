<?php
/*
 * Aquí hacemos los ejercicios y rellenamos el array de datos.
 */

$data = [];

if(!empty($_POST)){
    //Comprobar
    $errors = checkForm($_POST);
    $data['data'] = filter_var($_POST['data'], FILTER_SANITIZE_SPECIAL_CHARS);
    if(count($errors) > 0){
        $data['errors'] = $errors;
    }
    else{
        $todas_las_notas = json_decode($_POST['data'],true);
        $data['informacion_clase'] = [];
        foreach ($todas_las_notas as $asignatura => $alumnos) {
            asort($alumnos);
            $data['informacion_clase'][$asignatura]['media'] = array_sum($alumnos)/count($alumnos);
            $data['informacion_clase'][$asignatura]['suspensos'] = count(array_filter($alumnos, function($nota){ return $nota < 5;}));
            $data['informacion_clase'][$asignatura]['aprobados'] = count(array_filter($alumnos, function($nota) { return $nota >= 5;}));
            $data['informacion_clase'][$asignatura]['min']['nota'] = min($alumnos);
            foreach ($alumnos as $alumno => $nota) {
                if($data['informacion_clase'][$asignatura]['min']['nota'] === $nota){
                    $data['informacion_clase'][$asignatura]['min']['nota'][] = $alumno;
                }
            }
            $data['informacion_clase'][$asignatura]['max']['nota'] = min($alumnos);
            foreach ($alumnos as $alumno => $nota) {
                if($data['informacion_clase'][$asignatura]['max']['nota'] === $nota){
                    $data['informacion_clase'][$asignatura]['max']['nota'][] = $alumno;
                }
            }
            var_dump($data['informacion_clase']);

        }

        var_dump(json_decode($_POST['data'],true));
    }
    //si bien procesar

    //si mal enviar
}





function checkForm(array $data) : array
{
    $errors = [];
    $datos = json_decode($data['data'],true);
    if(empty($data['data'])){
        $errors[] = 'Inserte un valor en este campo';
    }
    elseif(is_null($datos)||!is_array($datos)){
        $errors[] = 'El texto que acabas de enviar no es un formato json';
    }else{
        foreach ($datos as $asignatura => $alumnos ) {
            if(empty($asignatura)||!is_string($asignatura)){
                $errors[] = 'El nombre de la asignatura '.$asignatura.' no es valido para una asignatura';
            }elseif(empty($alumnos)||!is_array($alumnos)){
                $errors[] = 'No se a detectado ninguna lista de alumnos en la asignatura '.$asignatura;
            }else{
                foreach ($alumnos as $alumno => $nota) {
                    if(empty($alumno)||!is_string($alumno)){
                        $errors[] = 'El nombre del alumno '.$alumno.' de la asignatura '.$asignatura.' no es valida';
                    }elseif(!is_numeric($nota) || $nota < 0 || $nota > 10){
                        $errors[] = 'la nota '.$nota.' del alumno '.$alumno.' de la asignatura '.$asignatura.' no es valida';
                    }
                }
            }
        }
    }
    return $errors;
}
/*
 * Llamamos a las vistas
 */
include 'views/templates/header.php';
include 'views/tablas.view.php';
include 'views/templates/footer.php';