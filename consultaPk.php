<?php
include("template/cabecera.php");
include("admin/config/bd.php");
?>


<form method="post" action="index.php">
<input type="text" name="search" placeholder="Buscar...">
<select name="type">
  <option value="">Todos</option>
  <option value="Agua">Agua</option>
  <option value="Fuego">Fuego</option>
  <option value="Planta">Planta</option>
  <option value="Eléctrico">Eléctrico</option>
</select>
<button type="submit">Buscar</button>
</form>

<div class="results">
  <?php
  if (isset($_POST['search']) || isset($_POST['type'])) {
    $search = $_POST['search'];
    $type = $_POST['type'];

    // Consulta a la base de datos
    $sql = "SELECT * FROM pokemon WHERE name LIKE '%$search%' OR type='$type'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      // Mostramos los resultados
      while($row = $result->fetch_assoc()) {
        echo '<div class="pokemon">';
        echo '<img src="img/' . $row['image'] . '">';
        echo '<h2>' . $row['name'] . '</h2>';
        echo '<p>Tipo: ' . $row['type'] . '</p>';
        echo '</div>';
      }
    } else {
      echo 'No se encontraron resultados';
    }
  }
  ?>
</div>



<?php
include("template/pie.php");
?>