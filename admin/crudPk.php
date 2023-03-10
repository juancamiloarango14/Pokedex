<?php
include("template/cabecera.php");
?>

<?php

$txtID=(isset($_POST['txtID']))?$_POST['txtID']:"";
$txtNPokedex=(isset($_POST['txtNPokedex']))?$_POST['txtNPokedex']:"";
$txtNombre=(isset($_POST['txtNombre']))?$_POST['txtNombre']:"";
$txtTipo=(isset($_POST['txtTipo']))?$_POST['txtTipo']:"";
$txtImagen=(isset($_FILES['txtImagen']['name']))?$_FILES['txtImagen']['name']:"";
$txtDescripcion=(isset($_POST['txtDescripcion']))?$_POST['txtDescripcion']:"";

  $accion=(isset($_POST['accion']))?$_POST['accion']:"";
  $accion."<br/>";  
  include("config/bd.php");

switch($accion)
{
    case "Agregar":
        $sentenciaSQL= $conexion->prepare("INSERT INTO pokemon (npokedex, nombre, tipo, imagen, descripcion) VALUES (:npokedex, :nombre,:tipo,:imagen, :descripcion);");       
        $sentenciaSQL->bindParam(':npokedex', $txtNPokedex);
        $sentenciaSQL->bindParam(':nombre', $txtNombre);
        $sentenciaSQL->bindParam(':tipo', $txtTipo);
        $sentenciaSQL->bindParam(':descripcion', $txtDescripcion);
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

        $sentenciaSQL=$conexion->prepare("UPDATE pokemon SET npokedex=:npokedex WHERE id=:id");
        $sentenciaSQL->bindParam(':npokedex', $txtNPokedex);
        $sentenciaSQL->bindParam(':id',$txtID);
        $sentenciaSQL->execute(); 

        $sentenciaSQL=$conexion->prepare("UPDATE pokemon SET nombre=:nombre WHERE id=:id");
        $sentenciaSQL->bindParam(':nombre', $txtNombre);
        $sentenciaSQL->bindParam(':id',$txtID);
        $sentenciaSQL->execute(); 

        $sentenciaSQL=$conexion->prepare("UPDATE pokemon SET tipo=:tipo WHERE id=:id");
        $sentenciaSQL->bindParam(':tipo', $txtTipo);
        $sentenciaSQL->bindParam(':id',$txtID);
        $sentenciaSQL->execute(); 

        $sentenciaSQL=$conexion->prepare("UPDATE pokemon SET descripcion=:descripcion WHERE id=:id");
        $sentenciaSQL->bindParam(':descripcion', $txtDescripcion);
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
            $libro=$sentenciaSQL->fetch(PDO::FETCH_LAZY); 
    
            if(isset($libro["imagen"])&&($libro["imagen"]!="imagen.jpg")){
    
                if(file_exists("../img/".$libro["imagen"])){
    
                    unlink("../img/".$libro["imagen"]);
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
        $txtDescripcion=$pokemon ['descripcion'];

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
<label for="npokedex">Numero en la pokedex:</label>
<input type="text" required class="form-control" value="<?php echo $txtNPokedex?>"  name="txtNPokedex" id="txtNPokedex"  placeholder="Numero en la Pokedex">
</div>

<div class = "form-group">
<label for="nombre">Nombre del Pokemon:</label>
<input type="text" required class="form-control" value="<?php echo $txtNombre?>"  name="txtNombre" id="txtNombre"  placeholder="Nombre del Pokemon">
</div>

<div class = "form-group">
<label for="tipo" class="form-label mt-4">Tipo del Pokemon:</label>
<select multiple="" class="form-control" value="<?php echo $txtTipo?>" name="txtTipo" id="txtTipo" placeholder="Tipo del Pokemon">
<option>Bicho</option>
<option>Dragón</option>
<option>Eléctrico</option>
<option>Hada</option>
<option>Lucha</option>
<option>Fuego</option>
<option>Volador</option>
<option>Fantasma</option>
<option>Planta</option>
<option>Tierra</option>
<option>Hielo</option>
<option>Normal</option>
<option>Veneno</option>
<option>Psíquico</option>
<option>Roca</option>
<option>Acero</option>
<option>Agua</option>
<option>Siniestro</option>

</select>

</div>

<div class = "form-group">
<label for="txtNombre">Imagen del Pokemon:</label>
<?php echo $txtImagen?>


<?php
if ($txtImagen!=""){ ?>

            <img class="img-thumbnail rounded" src="../img/<?php echo $txtImagen;?> " width="50" alt="" srcset="">        

<?php } ?>

<div class = "form-group">
<label for="nombre">Descripcion del Pokemon:</label>
<textarea type="text" required class="form-control" value="<?php echo $txtDescripcion?>"  name="txtDescripcion" id="txtDescripcion"  placeholder="Descripcion del pokemon: "></textarea>
</div>

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
            <th>Id</th>
            <th>N° Pokedex</th>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Imagen</th>
            <th>Descripcion</th>
            <th>Acciones</th>
        </tr>
</thead>

<tbody>
<?php foreach ($listapokemon as $pokemon) { ?>

    <tr>  
            <td><?php echo $pokemon['id']; ?></td>
            <td><?php echo $pokemon['npokedex']; ?></td>
            <td><?php echo $pokemon['nombre']; ?></td>
            <td><?php echo $pokemon['tipo']; ?></td>
            <td>              
            <img class="img-thumbnail rounded" src="../img/<?php echo $pokemon['imagen']; ?>" width="100" alt="" srcset="">
           </td>
            <td><?php echo $pokemon['descripcion']; ?></td>

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