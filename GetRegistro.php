<?php
/*
 * El siguiente código localiza un usuario
 *  Ale y andres proyecto citas Nov/2020
 */

$response = array();
$usuario = array();
$Cn = mysqli_connect("localhost","root","","Proyecto")or die ("server no encontrado");
mysqli_set_charset($Cn,"utf8");

// Checa que le este llegando por el método POST el correo


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $objArray = json_decode(file_get_contents("php://input"),true);
    if(empty($objArray)){
        $usuario["success"]= 400;
        $usuario["message"]= "Faltan datos";
        array_push($response,$usuario);
        echo json_encode($response);
    }
    else{
    $correo=$objArray['correo'];
    $contrasena=$objArray['contrasena'];
    //$nomT=$objArray['nomTrab'];
    
    $result = mysqli_query($Cn,"SELECT * from registro WHERE correo = '$correo'");
    
    if (!empty($result)) {
        if (mysqli_num_rows($result) > 0) {
            $res = mysqli_fetch_array($result);
                if ($contrasena == $res["contrasena"]){
                    $result = mysqli_query($Cn,"SELECT correoTrab,idcita,curp,nomPac,domPac,descripcion,fechaPac,estadocita FROM citas WHERE correoTrab = '$correo' ORDER BY fechaPac");
                    if (mysqli_num_rows($result) > 0) {
                        if (!empty($result)) {
                        while ($res = mysqli_fetch_array($result)){
                            $citas = array();
                            $citas["success"] = 200;  
                            $citas["message"] = "La contraseña coincide y Regresa las citas";
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
            $citas["message"] = "La contraseña no coincide";
            array_push($response, $citas);
            echo json_encode($response);
            }           
        }
    }

}
}else{
    $citas = array();
    $citas["success"] = 400;
    $citas["message"] = "Error metodo incorrecto";
    array_push($response, $citas);
    echo json_encode($response); 
}
mysqli_close($Cn);
?>