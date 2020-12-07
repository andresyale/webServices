<?php
/*
 * El siguiente código localiza los productos
 * AGZ    Abril/2020
 */

$response = array();

$Cn = mysqli_connect("localhost","root","","Proyecto")or die ("server no encontrado");
mysqli_set_charset($Cn,"utf8");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $result = mysqli_query($Cn,"SELECT correoTrab,idcita,curp,nomPac,domPac,descripcion,fechaPac,estadocita FROM citas ORDER BY fechaPac");
    if (!empty($result)) {
        if (mysqli_num_rows($result) > 0) {
            while ($res = mysqli_fetch_array($result)){
                $citas = array();
                $citas["success"] = 200;  
                $citas["message"] = "Pacientes encontrados";
                $citas["correoTrab"] = $res["correoTrab"];
                $citas["idcita"] = $res["idcita"];
                $citas["curp"] = $res["curp"];
                $citas["nomPac"] = $res["nomPac"];
                $citas["domPac"] = $res["domPac"];
                $citas["descripcion"] = $res["descripcion"];
                $citas["fecha"] = $res["fechaPac"];
                $citas["estadocita"] = $res["estadocita"];
                
                array_push($response, $citas);
            }
           echo json_encode($response);
        } else {
            $citas = array();
            $citas["success"] = 404;  //No encontro información y el success = 0 indica no exitoso
            $citas["message"] = "Paciente no encontrado";
            array_push($response, $citas);
            echo json_encode($response);
        }
    } else {
        $citas = array();
        $citas["success"] = 404;  //No encontro información y el success = 0 indica no exitoso
        $citas["message"] = "Paciente no encontrado";
        array_push($response, $citas);
        echo json_encode($response);
    }
} else {
    $citas = array();
    $citas["success"] = 400;
    $citas["message"] = "Faltan Datos entrada";
    array_push($response, $citas);
    echo json_encode($response);
}
mysqli_close($Cn);
?>

