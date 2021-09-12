<?php 
	session_start();
	if(empty($_SESSION['cargo']==1 || $_SESSION['cargo']==3)){
        header('location: ./');
    }
	include "../conexion.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "include/script.php" ?>
	<title>Lista Proveedores Autos Meteoro</title>
</head>
<body>
	<?php include "include/header.php"?>
	<section id="container">
        <?php 
            $busqueda = strtolower($_REQUEST['busqueda']);
            if(empty($busqueda)){
                header("location: lista_proveedor.php");
            }
        ?>
        <h1><i class="far fa-building"></i> Lista de Proveedores</h1>
		<a href="registro_proveedor.php" class="btn_usuario">Crear Proveedor</a>

		<form action="buscar_proveedor.php" method="GET" class="form_buscar">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda; ?>">
			<button type="submit"  class="btn_buscar"><i class="fas fa-search-plus"></i></button>
		</form>
		
		<table>
			<tr>
                <th>Id Proveedor</th>
				<th>Nombre Proveedor</th>
                <th>Nombre Asesor</th>
				<th>Telefono Proveedor</th>
                <th>Direccion Proveedor</th>
                <th>Fecha Ingreso al sistema</th>
				<th>Operaciones</th>
			</tr>
			<?php 

                //PAGINADOR
				$sql_registro= mysqli_query($conexion,"SELECT COUNT(*) as total_registro FROM proveedor 
                WHERE(Id_Proveedor LIKE '%$busqueda%' OR Nombre_Proveedor LIKE '%$busqueda%' OR Nombre_Asesor LIKE '%$busqueda%' OR Telefono_Proveedor LIKE '%$busqueda%'
                OR Direccion_Proveedor LIKE '%$busqueda%' OR dateadd LIKE '%$busqueda%')");


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




				$query = mysqli_query($conexion,"SELECT * FROM proveedor WHERE(Id_Proveedor LIKE '%$busqueda%' OR Nombre_Proveedor LIKE '%$busqueda%' OR Nombre_Asesor LIKE '%$busqueda%' OR Telefono_Proveedor LIKE '%$busqueda%'
                OR Direccion_Proveedor LIKE '%$busqueda%' OR dateadd LIKE '%$busqueda%') ORDER BY Id_Proveedor ASC LIMIT $desde, $por_pagina");

				$resultado = mysqli_num_rows($query);

				if($resultado > 0){
					while($data = mysqli_fetch_array($query)){
                            $formato = 'Y-m-d H:i:s';
                            $fecha = DateTime::createFromFormat($formato,$data["dateadd"]);
						?>
						<tr>
							<td><?php echo $data["Id_Proveedor"] ?></td>
							<td><?php echo $data["Nombre_Proveedor"] ?></td>
							<td><?php echo $data["Nombre_Asesor"] ?></td>
                            <td><?php echo $data["Telefono_Proveedor"] ?></td>
                            <td><?php echo $data["Direccion_Proveedor"] ?></td>
                            <td><?php echo $fecha->format('d-m-Y')?></td>
							<td>
								<a class="link_edit" href="editar_proveedor.php?id=<?php echo $data["Id_Proveedor"] ?>"><i class="far fa-edit"></i>Editar</a>
								|
								<?php 
									if($_SESSION['cargo']==1 || $_SESSION['cargo']==3){
								?>
								<a class="link_delete" href="eliminar_proveedor.php?id=<?php echo $data["Id_Proveedor"] ?>"><i class="far fa-trash-alt"></i>Eliminar</a>
									<?php }?>
							</td>
						</tr>
			<?php
					}
				}
			?>
		</table>
		<div class="paginador">
				<ul>
					<?php 
						if($pagina !=1){

						
					?>
					<li><a href="?pagina=<?php echo 1; ?>"><i class="fas fa-step-backward"></i></a></li>
					<li><a href="?pagina=<?php echo $pagina-1; ?>"><i class="fas fa-caret-left"></i></a></li>
					<?php
						} 
						for($i=1; $i <= $total_paginas; $i++){
							if($i == $pagina){
								echo '<li class="pageSelected">'.$i.'</li>';
							}else{
								echo '<li><a href="?pagina='.$i.'">'.$i.'</a></li>';
							}
						}
						if($pagina != $total_paginas){
					?>


					<li><a href="?pagina=<?php echo $pagina + 1;?>"><i class="fas fa-caret-right"></i></a></li>
					<li><a href="?pagina=<?php echo $total_paginas; ?>"><i class="fas fa-step-forward"></i></a></li>
						<?php } ?>
				</ul>
		</div>
	</section>
	<?php include "include/footer.php"?>
</body>
</html>