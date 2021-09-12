<?php 
	session_start();
	
	include "../conexion.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "include/script.php" ?>
	<title>Lista clientes Autos Meteoro</title>
</head>
<body>
	<?php include "include/header.php"?>
	<section id="container">
        <?php 
            $busqueda = strtolower($_REQUEST['busqueda']);
            if(empty($busqueda)){
                header("location: lista_clientes.php");
            }
        ?>
        <h1>Lista de clientes</h1>
		<a href="registro_cliente.php" class="btn_usuario">Crear cliente</a>

		<form action="buscar_cliente.php" method="GET" class="form_buscar">
			<input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda; ?>">
			<button type="submit"  class="btn_buscar"><i class="fas fa-search-plus"></i></button>
		</form>
		
		<table>
			<tr>
                <th>Id cliente</th>
				<th>Nombre</th>
                <th>Apellido</th>
                <th>Tipo de identificacion</th>
                <th>Numero identificacion</th>
				<th>Telefono</th>
                <th>Direccion</th>
                <th>Edad</th>
                <th>Fecha Ingreso</th>
				<th>Operaciones</th>
			</tr>
			<?php 

                //PAGINADOR
                

                $sql_registro= mysqli_query($conexion,"SELECT COUNT(*) as total_registro FROM cliente 
                WHERE (id_cliente LIKE'%$busqueda%' OR Nombre LIKE '%$busqueda%' OR Apellido LIKE '%$busqueda%' OR Tipo LIKE '%$busqueda%' OR Identificacion LIKE '%$busqueda%' 
                OR Telefono LIKE '%$busqueda%'OR Direccion LIkE '%$busqueda%' OR Edad LIKE '%$busqueda%' )");
                
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




				$query = mysqli_query($conexion,"SELECT * FROM cliente WHERE (id_cliente LIKE'%$busqueda%' OR Nombre LIKE '%$busqueda%' OR Apellido LIKE '%$busqueda%' OR Tipo LIKE '%$busqueda%' OR Identificacion LIKE '%$busqueda%' 
                OR Telefono LIKE '%$busqueda%'OR Direccion LIkE '%$busqueda%' OR Edad LIKE '%$busqueda%') ORDER BY id_cliente ASC LIMIT $desde, $por_pagina");

				$resultado = mysqli_num_rows($query);

				if($resultado > 0){
					while($data = mysqli_fetch_array($query)){
						?>
						<tr>
                        <td><?php echo $data["id_cliente"] ?></td>
							<td><?php echo $data["Nombre"] ?></td>
							<td><?php echo $data["Apellido"] ?></td>
                            <td><?php echo $data["Tipo"] ?></td>
                            <td><?php echo $data["Identificacion"] ?></td>
                            <td><?php echo $data["Telefono"] ?></td>
                            <td><?php echo $data["Direccion"] ?></td>
                            <td><?php echo $data["Edad"] ?></td>
                            <td><?php echo $data["dateadd"] ?></td>
							<td>
								<a class="link_edit" href="editar_usuario.php?id=<?php echo $data["id_cliente"] ?>">Editar</a>
                                |
                                <?php 
									if($_SESSION['cargo']==1 || $_SESSION['cargo']==3){
								?>
                                <a class="link_delete" href="eliminar_usuario.php?id=<?php echo $data["id_cliente"] ?>">Eliminar</a>
                                <?php }?>
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