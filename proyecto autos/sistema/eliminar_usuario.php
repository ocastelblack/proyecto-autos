<?php 
    session_start();
	if(empty($_SESSION['cargo']==1)){
        header('location: ./');
    }
    include "../conexion.php";
    
    if(!empty($_POST)){
        $idusuario = $_POST['id_usuario'];

        $query_delete = mysqli_query($conexion,"DELETE FROM usuario WHERE id_usuario=$idusuario");

        if($query_delete){
            header("location: lista_usuarios.php");
        }else{
            echo "Error al eliminar";
        }
    }

    if(empty($_REQUEST['id']) || $_REQUEST['id']==1){
        header("location: lista_usuarios.php");
    }else{

        $idusuario=$_REQUEST['id'];

        $query= mysqli_query($conexion, "SELECT u.Nombre, u.Apellido, u.Correo, u.Usuario, c.cargo FROM usuario u INNER JOIN cargo c ON u.cargo = c.id_cargo
        WHERE u.id_usuario = $idusuario");

        $resultado=mysqli_num_rows($query);

        if($resultado >0){
            while($data= mysqli_fetch_array($query)){
                $nombre = $data['Nombre'];
                $apellido = $data['Apellido'];
                $correo = $data['Correo'];
                $usuario = $data['Usuario'];
                $cargo = $data['cargo'];
            }
        }else{
            header("location: lista_usuarios.php");
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "include/script.php" ?>
	<title>Eliminar Usuario</title>
</head>
<body>
	<?php include "include/header.php"?>
	<section id="container">
		<div class="dato_eliminar">
            <i class="fas fa-user-times fa-7x" style="color:red"></i>
            </br>
            <h2>¿Esta seguro de eliminar el siguiente registro?</h2>
            <p>Nombre: <span><?php echo $nombre; ?></span></p>
            <p>Apellido: <span><?php echo $apellido; ?></span></p>
            <p>Correo: <span><?php echo $correo; ?></span></p>
            <p>Cargo: <span><?php echo $cargo; ?></span></p>

            <form method="POST" action="">
                <input type="hidden" name="id_usuario" value="<?php echo $idusuario; ?>">
                <a href="lista_usuarios.php" class="btn_cancelar"><i class="fas fa-ban"></i>Cancelar</a>
                <button type="submit" class="btn_ok"><i class="fas fa-trash"></i>Eliminar</button>
            </form>

        </div>
	</section>
	<?php include "include/footer.php"?>
</body>
</html>