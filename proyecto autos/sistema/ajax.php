<?php 
    include "../conexion.php";
    session_start();


    if(!empty($_POST)){

        if($_POST['action'] =='searchCliente'){
            echo "Buscar cliente";
            exit;
        }
    }
    exit;

?>