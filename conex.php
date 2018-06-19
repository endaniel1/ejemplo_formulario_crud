<?php

$enlace = mysqli_connect("localhost", "root", "") or die("No se ha Podido Conectar a el Servidor");
mysqli_select_db($enlace, 'prueba_formulario') or die("No se ha Podido Selecionar la BD");

?>
