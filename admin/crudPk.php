<?php
include("template/cabecera.php");
?>

<?php

$txtID=(isset($_POST['txtID']))?$_POST['txtID']:"";
$txtNombre=(isset($_POST['txtNombre']))?$_POST['txtNombre']:"";
$txtTipo=(isset($_POST['txtTipo']))?$_POST['txtTipo']:"";
$txtImagen=(isset($_FILES['txtImagen']['name']))?$_FILES['txtImagen']['name']:"";

  $accion=(isset($_POST['accion']))?$_POST['accion']:"";
  $accion."<br/>";  
  include("config/bd.php");

switch($accion)
{
    case "Agregar":
        $sentenciaSQL=$conexion->prepare("INSERT INTO pokemon(id,nombre,tipo,imagen) VALUES (NULL, 'Bulbasaur', 'planta', 'bulbasaur.jpg');");
        $sentenciaSQL->execute();

    break;


}
$sentenciaSQL=$conexion->prepare("SELECT * FROM pokemon");
$sentenciaSQL->execute();
$listapokemon=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC); 

?>

<div class="col-md-5">    

<div class="card">
<div class="card-header">
        Datos de Pokemon
</div>

<div class="card-body">
<form method="POST" enctype="multipart/form-data">

<div class = "form-group">
<label for="ID">ID:</label>
<input type="text" required readonly class="form-control"   name="id" id="id"  placeholder="ID">
</div>

<div class = "form-group">
<label for="Nombre">Name Pokemon:</label>
<input type="text" required class="form-control"name="name" id="name"  placeholder="Nombre del Pokemon">
</div>

<div class = "form-group">
<label for="Nombre">Type Pokemon:</label>
<input type="text" required class="form-control"  name="type" id="type"  placeholder="Tipo del Pokemon">
</div>

<div class = "form-group">
<label for="txtNombre">Imagen:</label>
<input type="file" class="form-control" value=" name="image_url" id="image_url" placeholder="foto del pokemon">
</div>

<div class="btn-group" role="group" aria-label="">
    <button type="submit" name="accion" value="Agregar" class="btn btn-success">Agregar</button>
    <button type="submit" name="accion" value="Modificar" class="btn btn-warning">Modificar</button>
    <button type="submit" name="accion" value="Cancelar" class="btn btn-info">Cancelar</button>
</div>

</form>
</div>

</div>
</div>



<div class="col-md-7">
<table class="table table-bordered">
<thead>
        <tr>
            <th>Id</th>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Imagen</th>
            <th>Acciones</th>
        </tr>
</thead>

<tbody>
<?php foreach ($listapokemon as $pokemon) { ?>

    <tr>  
            <td><?php echo $pokemon['id']; ?></td>
            <td><?php echo $pokemon['nombre']; ?></td>
            <td><?php echo $pokemon['tipo']; ?></td>
            <td><?php echo $pokemon['imagen']; ?></td>

            <td>
                <form method="post">

                <input type="hidden" name="txtID" id="" value="<?php echo $libro['id'];?>" />

                <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary"/>

                <input type="submit" name="accion" value="Borrar" class="btn btn-danger"/>

                </form>
            
            </td>


        </tr>
        <?php }?>
  </tbody>
</table>



<?php
include("template/pie.php");
?>