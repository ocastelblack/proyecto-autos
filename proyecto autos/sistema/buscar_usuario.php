<?php 
	session_start();
	if(empty($_SESSION['cargo']==1)){
        header('location: ./');
    }
	include "../conexion.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "include/script.php" ?>
	<title>Lista usuarios Autos Meteoro</title>
</head>
<body>
	<?php include "include/header.php"?>
	<section id="container">
        <?php 
            $busqueda = strtolower($_REQUEST['busqueda']);
            if(empty($busqueda)){
                header("location: lista_usuarios.php");
            }
        ?>
        <h1>Lista de usuarios</h1>
		<a href="registro_usuario.php" class="btn_usuario">Crear usuario</a>

		<form action="buscar_usuario.php" method="GET" class="form_buscar">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda; ?>">
			<input type="submit" value="Buscar" class="btn_buscar">
		</form>
		
		<table>
			<tr>
				<th>id_usuario</th>
				<th>Nombre</th>
				<th>Apellido</th>
				<th>Edad</th>
				<th>Direccion</th>
				<th>Telefono</th>
				<th>Correo</th>
				<th>Cargo</th>
				<th>Operaciones</th>
			</tr>
			<?php 

                //PAGINADOR
                $rol='';
                if($busqueda == 'Administrador'){
                    $rol = "OR cargo LIKE '%1%'";
                }else if($busqueda == 'Vendedor'){
                    $rol = "OR cargo LIKE '%2%'";
                }else if($busqueda == 'Supervisor'){
                    $rol = "OR cargo LIKE '%3%'";
                }

                $sql_registro= mysqli_query($conexion,"SELECT COUNT(*) as total_registro FROM usuario 
                WHERE (id_usuario LIKE '%$busqueda%' OR Nombre LIKE '%$busqueda%' OR  Apellido LIKE '%$busqueda%' OR Edad LIKE '%$busqueda%'
                OR Direccion LIKE '%$busqueda%' OR Telefono LIKE '%$busqueda%' OR correo LIKE '%$busqueda%' OR Usuario LIKE '%$busqueda%'
                $rol)");
                
				$resultado_registro = mysqli_fetch_array($sql_registro);
				$total_registro = $resultado_registro['total_registro'];

				$por_pagina = 10;

				if(empty($_GET['pagina'])){
					$pagina=1;
				}else{
					$pagina=$_GET['pagina'];
				}

				$desde= ($pagina-1) * $por_pagina;
				$total_paginas = ceil($total_registro / $por_pagina);




				$query = mysqli_query($conexion,"SELECT u.id_usuario, u.Nombre, u.Apellido, u.Edad, u.Direccion, u.Telefono, u.Correo, c.cargo 
				FROM usuario u INNER JOIN cargo c ON u.cargo = c.id_cargo WHERE (id_usuario LIKE '%$busqueda%' OR Nombre LIKE '%$busqueda%' OR Apellido LIKE '%$busqueda%' OR Edad LIKE '%$busqueda%'
                OR Direccion LIKE '%$busqueda%' OR Telefono LIKE '%$busqueda%' OR correo LIKE '%$busqueda%' OR Usuario LIKE '%$busqueda%'
                OR c.cargo LIKE '%$busqueda%') ORDER BY id_usuario ASC LIMIT $desde, $por_pagina");

				$resultado = mysqli_num_rows($query);

				if($resultado > 0){
					while($data = mysqli_fetch_array($query)){
						?>
						<tr>
							<td><?php echo $data["id_usuario"] ?></td>
							<td><?php echo $data["Nombre"] ?></td>
							<td><?php echo $data["Apellido"] ?></td>
							<td><?php echo $data["Edad"] ?></td>
							<td><?php echo $data["Direccion"] ?></td>
							<td><?php echo $data["Telefono"] ?></td>
							<td><?php echo $data["Correo"] ?></td>
							<td><?php echo $data["cargo"] ?></td>
							<td>
								<a class="link_edit" href="editar_usuario.php?id=<?php echo $data["id_usuario"] ?>">Editar</a>
								<?php 
									if($data["id_usuario"]!=1){
								?>
								|
								<a class="link_delete" href="eliminar_usuario.php?id=<?php echo $data["id_usuario"] ?>">Eliminar</a>
									<?php } ?>
							</td>
						</tr>
			<?php
					}
				}
			?>
        </table>
        
        <?php 
            if($total_registro !=0){

        ?>
		<div class="paginador">
				<ul>
					<?php 
						if($pagina !=1){

						
					?>
					<li><a href="?pagina=<?php echo 1; ?>&busqueda=<?php echo $busqueda; ?>">|<</a></li>
					<li><a href="?pagina=<?php echo $pagina-1; ?>&busqueda=<?php echo $busqueda; ?>"><</a></li>
					<?php
						} 
						for($i=1; $i <= $total_paginas; $i++){
							if($i == $pagina){
								echo '<li class="pageSelected">'.$i.'</li>';
							}else{
								echo '<li><a href="?pagina='.$i.'&busqueda='.$busqueda.'">'.$i.'</a></li>';
							}
						}
						if($pagina != $total_paginas){
					?>


					<li><a href="?pagina=<?php echo $pagina + 1;?>&busqueda=<?php echo $busqueda; ?>">></a></li>
					<li><a href="?pagina=<?php echo $total_paginas; ?>&busqueda=<?php echo $busqueda; ?>">>|</a></li>
						<?php } ?>
				</ul>
        </div>
                        <?php }?>
	</section>
	<?php include "include/footer.php"?>
</body>
</html>