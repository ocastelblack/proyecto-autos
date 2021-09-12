<?php
    session_start();
	
    include "../conexion.php";
    if(!empty($_POST)){
        $alert='';
        if(empty($_POST['nombre']) || empty($_POST['apellido']) || empty($_POST['identificacion']) || empty($_POST['telefono'])
        || empty($_POST['direccion']) || empty($_POST['edad'])){
            $alert='<p class="msg_error">Todos los campos son obligatorios.</p>';
        }else{
            $idcliente = $_POST['id'];
            $nombre    = $_POST['nombre'];
            $apellido  = $_POST['apellido'];
            $tipo      = $_POST['tipo'];
            $identificacion = $_POST['identificacion'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];
            $edad = $_POST['edad'];

            $result = 0;

            if(is_numeric($identificacion) and $identificacion !=0){
                $query = mysqli_query($conexion,"SELECT * FROM cliente WHERE (Identificacion='$identificacion' AND id_cliente !='$idcliente')");
                $result = mysqli_fetch_array($query);
                
            }

            if($result > 0){
                $alert='<p class="msg_error">El numero de identificacion ya existe ingrese otro </p>';
            }else{

                if($identificacion == ''){
                    $identificacion = 0;
                }
                $sql_update=mysqli_query($conexion,"UPDATE cliente SET Nombre='$nombre', Apellido='$apellido',Tipo='$tipo' ,Identificacion='$identificacion', 
                Telefono='$telefono', Direccion='$direccion', Edad='$edad' WHERE id_cliente=$idcliente");

                if($sql_update){
                    $alert='<p class="msg_guardar">Cliente Actualizado</p>';
                }else{
                    $alert='<p class="msg_error">Error al Actualizar Cliente</p>';
                }
            }
        }
    }
    //MOSTRAR DATOS
    if(empty($_GET['id'])){
        header('Location: lista_clientes.php');
    }
    
    $idcliente = $_GET['id'];

    $sql=mysqli_query($conexion, "SELECT * FROM cliente WHERE id_cliente=$idcliente");

    $resultado_sql = mysqli_num_rows($sql);

    if($resultado_sql==0){
        header('Location: lista_clientes.php');
    }else{
        $option='';
        while($data=mysqli_fetch_array($sql)){

            $idcliente = $data['id_cliente'];
            $nombre = $data['Nombre'];
            $apellido = $data['Apellido'];
            $tipo = $data['Tipo'];
            $identificacion = $data['Identificacion'];
            $telefono = $data['Telefono'];
            $direccion = $data['Direccion'];
            $edad = $data['Edad'];
            
            

        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "include/script.php" ?>
	<title>Actualizar Cliente</title>
</head>
<body>
	<?php include "include/header.php"?>
	<section id="container">

        <div class="form_registro">
        <h1><i class="far fa-edit"></i> Actualizar Cliente</h1>
        <hr>
            <div class="alerta"><?php echo isset($alert) ? $alert: '';?></div>
            <form action="" method="POST">
            <input type="hidden" name="id" value="<?php echo $idcliente; ?>">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="nombre" placeholder="Nombre" value="<?php echo $nombre; ?>">
            <label for="apellido">Apellido</label>
            <input type="text" name="apellido" id="apellido" placeholder="Apellido" value="<?php echo $apellido; ?>">
            <label for="tipo">Tipo identificacion</label>
            <select name="tipo" id="tipo" class="notItemOne">
                    <option value="<?php  echo $tipo?>" selected><?php  echo $tipo?></option>
                    <option value="Cedula ciudadania">Cedula ciudadania</option>
                    <option value="Cedula Extranjera">Cedula Extranjera</option>
                    <option value="Tarjeta de identidad">Tarjeta de identidad</option>
                    <option value="Pasaporte">Pasaporte</option>
            </select>
            <label for="identificacion">Numero identificacion</label>
            <input type="text" name="identificacion" id="identificacion" placeholder="Numero identificacion" value="<?php echo $identificacion; ?>">
            <label for="telefono">Telefono</label>
            <input type="text" name="telefono" id="telefono" placeholder="Telefono" value="<?php echo $telefono; ?>">
            <label for="direccion">Direccion</label>
            <input type="text" name="direccion" id="direccion" placeholder="Direccion" value="<?php echo $direccion; ?>">
            <label for="edad">Edad</label>
            <input type="text" name="edad" id="edad" placeholder="Edad" value="<?php echo $edad; ?>">

            <button type="submit" class="btn_Guardar"><i class="far fa-edit"></i> Actualizar Cliente</button>
         </form>
        </div>      
	</section>
	<?php include "include/footer.php"?>
</body>
</html>