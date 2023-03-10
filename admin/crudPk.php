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
        $sentenciaSQL= $conexion->prepare("INSERT INTO pokemon (nombre, tipo, imagen) VALUES (:nombre,:tipo,:imagen);");
        $sentenciaSQL->bindParam(':nombre', $txtNombre);
        $sentenciaSQL->bindParam(':tipo', $txtTipo);
        //$sentenciaSQL->bindParam(':imagen', $txtImagen);    


        $fecha=new DateTime();
        $nombreArchivo=($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";

        $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

        if($tmpImagen!=""){

            move_uploaded_file($tmpImagen,"../img/".$nombreArchivo);

        }

        $sentenciaSQL->bindParam(':imagen',$nombreArchivo);
        $sentenciaSQL->execute();     
        header("Location:crudPk.php");           
        break;

    case"Modificar":
    
        $sentenciaSQL=$conexion->prepare("UPDATE pokemon SET nombre=:nombre, tipo=:tipo WHERE id=:id");   
        $sentenciaSQL->bindParam(':nombre',$txtNombre);
        $sentenciaSQL->bindParam(':tipo',$txtTipo);
        $sentenciaSQL->bindParam(':id',$txtID);
        $sentenciaSQL->execute(); 

        if($txtImagen!=""){
        $fecha=new DateTime();
        $nombreArchivo=($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";
        $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

        move_uploaded_file($tmpImagen,"../img/".$nombreArchivo);

        $sentenciaSQL=$conexion->prepare("SELECT imagen FROM pokemon WHERE id=:id");   
        
        $sentenciaSQL->bindParam(':id',$txtID);
        $sentenciaSQL->execute();  
        $pokemon    =$sentenciaSQL->fetch(PDO::FETCH_LAZY); 

        if(isset($libro["imagen"])&&($pokemon["imagen"]!="imagen.jpg")){

            if(file_exists("../img/".$pokemon["imagen"])){

                unlink("../img/".$pokemon["imagen"]);
            }
        }

        $sentenciaSQL=$conexion->prepare("UPDATE pokemon SET imagen=:imagen WHERE id=:id");   
        $sentenciaSQL->bindParam(':imagen',$nombreArchivo);
        $sentenciaSQL->bindParam(':id',$txtID);
        $sentenciaSQL->execute(); 
        }
        header("Location:crudPk.php");
         break;

    case"Cancelar":
        
        header("Location:crudPk.php");

         break;

    case"Seleccionar":

        $sentenciaSQL=$conexion->prepare("SELECT * FROM pokemon WHERE id=:id");   
        $sentenciaSQL->bindParam(':id',$txtID);
        $sentenciaSQL->execute();  
        $pokemon=$sentenciaSQL->fetch(PDO::FETCH_LAZY); 

        $txtNombre=$pokemon ['nombre'];
        $txtTipo=$pokemon ['tipo'];
        $txtImagen=$pokemon ['imagen'];

        break;

    case"Borrar":        
        $sentenciaSQL=$conexion->prepare("DELETE FROM pokemon WHERE id=:id");
        $sentenciaSQL->bindParam(':id',$txtID);
        $sentenciaSQL->execute();    
        header("Location:crudPk.php");

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
<input type="text" required readonly class="form-control" value="<?php echo $txtID?>" name="txtID" id="txtID"  placeholder="ID">
</div>

<div class = "form-group">
<label for="Nombre">Nombre del Pokemon:</label>
<input type="text" class="form-control" value="<?php echo $txtNombre?>"  name="txtNombre" id="txtNombre"  placeholder="Nombre del Pokemon">
</div>

<div class = "form-group">
<label for="Nombre">Tipo del Pokemon:</label>
<input type="text" class="form-control" value="<?php echo $txtTipo?>"  name="txtTipo" id="txtTipo"  placeholder="Tipo del Pokemon">
</div>

<div class = "form-group">
<label for="txtNombre">Imagen del Pokemon:</label>
<input type="file" class="form-control"value="<?php echo $txtImagen?>"  name="txtImagen" id="txtImagen" placeholder="foto del pokemon">
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

                <input type="hidden" name="txtID" id="" value="<?php echo $pokemon['id'];?>" />

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