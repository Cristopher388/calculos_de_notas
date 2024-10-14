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
        $todas_las_notas = json_decode($_POST['data'], true);
        $data['informacion_clase'] = [];
        $alumnos = [];
        $alumnosSuspensos = [];
        foreach ($todas_las_notas as $asignatura => $alumnos) {
            $sumatorio = 0;
            $suspensos = 0;
            $aprobados = 0;
            $notaMax = -1;
            $notaMin = 11;
            $alumnosMax = [];
            $alumnosMin = [];
            foreach ($alumnos as $alumno => $notas) {
                $nota = round((array_sum($notas))/count($notas),2);
                $sumatorio += $nota;
                if(!isset($alumnosSuspensos[$alumno])){
                    $alumnosSuspensos[$alumno] = 0;
                }
                $alumnos[$alumno][$asignatura] = $nota;
                if ($nota < 5){
                    $alumnosSuspensos[$alumno]++;
                    $suspensos++;
                }else{
                    $aprobados++;
                }
                if($notaMax < $nota){
                    $notaMax = $nota;
                }
                if($notaMin > $nota){
                    $notaMin = $nota;
                }
            }
            foreach ($alumnos as $alumno => $notas) {
                $nota = round(array_sum($notas)/count($notas),2);
                if($notaMax == $nota){
                    $data['informacion_clase'][$asignatura]['max']['alumnos'][] = $alumno;
                }
                if($notaMin == $nota){
                    $data['informacion_clase'][$asignatura]['min']['alumnos'][] = $alumno;
                }
            }
            $data['informacion_clase'][$asignatura]['media'] = $sumatorio/count($alumnos);
            $data['informacion_clase'][$asignatura]['aprobados'] = $aprobados;
            $data['informacion_clase'][$asignatura]['suspensos'] = $suspensos;
            $data['informacion_clase'][$asignatura]['max']['nota'] = $notaMax;
            $data['informacion_clase'][$asignatura]['min']['nota'] = $notaMin;


        }

        $data['todoAprobado'] = [];
        $data['promocionan'] = [];
        $data['noPromocionan'] = [];
        foreach ($alumnosSuspensos as $alumno => $suspensos){
            if($suspensos == 0){
                $data['todoAprobado'][] = $alumno;
            }else if($suspensos == 1){
                $data['promocionan'][] = $alumno;
            }else{
                $data['noPromocionan'][] = $alumno;
            }
        }
    }
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
                foreach ($alumnos as $alumno => $notas) {
                    if(empty($alumno)||!is_string($alumno)){
                        $errors[] = 'El nombre del alumno '.$alumno.' de la asignatura '.$asignatura.' no es valida';
                    }else{
                        foreach ($notas as $nota) {
                            if(!is_numeric($nota) || $nota < 0 || $nota > 10){
                                $errors[] = 'la nota '.$nota.' del alumno '.$alumno.' de la asignatura '.$asignatura.' no es valida';
                            }
                        }
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