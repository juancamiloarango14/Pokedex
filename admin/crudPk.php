<?php
include("template/cabecera.php");
?>

<?php

 if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $type = $_POST['type'];
    $image_url = $_POST['image_url'];
  
    $sql = "INSERT INTO pokemon (name, type, image_url, created_at, updated_at) VALUES (:name, :type, :image_url, NOW(), NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['name' => $name, 'type' => $type, 'description' => $description, 'image_url' => $image_url]);
  }

  
  include("config/bd.php");
  

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

        <tr>  


            <td>                

            <img class="img-thumbnail rounded" src=" " width="50" alt="" srcset="">
        
           </td>

            <td> 
            <form method="POST">

            <input type="hidden" name="txtID" id="" value="<?php echo $pokemon['id'];?>" />

            <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary"/>

            <input type="submit" name="accion" value="Borrar" class="btn btn-danger"/>


                </form>
            
            </td>


        </tr>
 
    </tbody>
</table>



<?php
include("template/pie.php");
?>