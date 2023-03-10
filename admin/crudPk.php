<?php
include("template/cabecera.php");
?>

<?php

$txtID=(isset($_POST['txtID']))?$_POST['txtID']:"";
$txtNPokedex=(isset($_POST['txtNPokedex']))?$_POST['txtNPokedex']:"";
$txtNombre=(isset($_POST['txtNombre']))?$_POST['txtNombre']:"";
$txtTipo=(isset($_POST['txtTipo']))?$_POST['txtTipo']:"";
$txtImagen=(isset($_FILES['txtImagen']['name']))?$_FILES['txtImagen']['name']:"";

  $accion=(isset($_POST['accion']))?$_POST['accion']:"";
  $accion."<br/>";  
  include("config/bd.php");

switch($accion)
{
    case "Agregar":
        $sentenciaSQL= $conexion->prepare("INSERT INTO pokemon (npokedex, nombre, tipo, imagen) VALUES (:npokedex, :nombre,:tipo,:imagen);");
        $sentenciaSQL->bindParam(':npokedex', $txtNPokedex);
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
    
        $sentenciaSQL=$conexion->prepare("UPDATE pokemon SET npokedex=:npokedex, nombre=:nombre, tipo=:tipo WHERE id=:id");
        $sentenciaSQL->bindParam(':npokedex', $txtNPokedex);   
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

        $txtNPokedex=$pokemon ['npokedex'];
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

<!-- <div class = "form-group">
<label for="ID">ID:</label>
<input type="text" required readonly class="form-control" value="<?php echo $txtID?>" name="txtID" id="txtID"  placeholder="ID">
</div> -->

<div class = "form-group">
<label for="Nombre">Numero en la pokedex:</label>
<input type="text" required class="form-control" value="<?php echo $txtNPokedex?>"  name="txtNPokedex" id="txtNPokedex"  placeholder="Numero en la Pokedex">
</div>

<div class = "form-group">
<label for="Nombre">Nombre del Pokemon:</label>
<input type="text" required class="form-control" value="<?php echo $txtNombre?>"  name="txtNombre" id="txtNombre"  placeholder="Nombre del Pokemon">
</div>

<div class = "form-group">
<label for="Nombre">Tipo del Pokemon:</label>
<input type="text" required class="form-control" value="<?php echo $txtTipo?>"  name="txtTipo" id="txtTipo"  placeholder="Tipo del Pokemon">
</div>

<div class = "form-group">
<label for="txtNombre">Imagen del Pokemon:</label>
<?php echo $txtImagen?>

<?php
if ($txtImagen!=""){ ?>

            <img class="img-thumbnail rounded" src="../img/<?php echo $txtImagen;?> " width="50" alt="" srcset="">        

<?php } ?>

<input type="file" class="form-control" name="txtImagen" id="txtImagen" placeholder="nombre del pokemon">
</div>

<div class="btn-group" role="group" aria-label="">
    <button type="submit" name="accion"  <?php echo ($accion=="Seleccionar")?"disabled":""?>  value="Agregar" class="btn btn-success">Agregar</button>
    <button type="submit" name="accion"  value="Modificar" class="btn btn-warning">Modificar</button>
    <button type="submit" name="accion"  value="Cancelar" class="btn btn-info">Cancelar</button>
</div>

</form>
</div>

</div>
</div>



<div class="col-md-7">
<table class="table table-bordered">
<thead>
        <tr>
           <?php //<th>Id</th>?>
            <th>N° Pokedex</th>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Imagen</th>
            <th>Acciones</th>
        </tr>
</thead>

<tbody>
<?php foreach ($listapokemon as $pokemon) { ?>

    <tr>  
            <?php //echo $pokemon['id']; ?>
            <td><?php echo $pokemon['npokedex']; ?></td>
            <td><?php echo $pokemon['nombre']; ?></td>
            <td><?php echo $pokemon['tipo']; ?></td>
            <td>              
            <img class="img-thumbnail rounded" src="../img/<?php echo $pokemon['imagen']; ?>" width="100" alt="" srcset="">
           </td>

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