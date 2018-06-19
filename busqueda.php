<?php
include('conex.php');//Conexion A Base de Datos
sleep(1);
$buscor='';
if(isset($_POST['buscor'])){
  $buscor=$_POST['buscor'];
}
$sqlmod2="SELECT * FROM registros WHERE ced LIKE '$buscor%' OR nom LIKE '$buscor%' OR
	 ape LIKE '$buscor%' OR cor LIKE '$buscor%' OR fec_nac LIKE '$buscor%'
	 OR tel LIKE '$buscor%' LIMIT 5";
$cons2=mysqli_query($enlace,$sqlmod2);
$total2=mysqli_num_rows($cons2);

?>
<?php
if($total2>0 && $buscor!=''){
  echo '<table align="center" width="90%" class="table table-striped table-bordered table-hover table-condensed">';
    echo'<tr>
      <td align="center"><b>Cédula</b></td>
      <td align="center"><b>Nombre</b></td>
      <td align="center" ><b>Apellido</b></td>
      <td align="center" ><b>Teléfono</b></td>
      <td align="center" ><b>Correo</b></td>
      <td align="center" ><b>Edad</b></td>
      <td align="center" ><b>Fecha de Nacimiento</b></td>
      <td align="center" colspan="2" ><b>Opciones</b></td>
      </tr>';
      while($fila2=mysqli_fetch_assoc($cons2)){
        $primario= $fila2['ced'];
        echo "<tr align='center' >";
        echo "<td>".str_replace($buscor,'<strong>'.$buscor.'</strong>',utf8_decode($fila2['ced']))."</td>";
        echo "<td>".str_replace($buscor,'<strong>'.$buscor.'</strong>',ucwords($fila2['nom']))."</td>";
        echo "<td>".str_replace($buscor,'<strong>'.$buscor.'</strong>',ucwords($fila2['ape']))."</td>";
        echo "<td>".str_replace($buscor,'<strong>'.$buscor.'</strong>',utf8_decode($fila2['tel']))."</td>";
        echo "<td>".str_replace($buscor,'<strong>'.$buscor.'</strong>',utf8_decode($fila2['cor']))."</td>";

        echo "<td>aqui va la eda actualisada</td>";
        echo "<td>".str_replace($buscor,'<strong>'.$buscor.'</strong>',utf8_decode($fila2['fec_nac']))."</td>";
        echo  "<td  align='center'><a alt='Eliminar  Registro'
        href='?m=1&eliminar=$primario'><span class='glyphicon glyphicon-trash'></span> Eliminar</a></td>";
        echo  "<td  align='center'><a alt='Editar  Registro  '
        href='?m=1&editar=$primario'><span class='glyphicon glyphicon-pencil'></span> Editar</a></td>";
        echo "</tr>";
      }
      echo "</tr>";
    echo '</table>';
     }else if($total2==0 && $buscor!=''){
       echo'<h2 align="center">NO HAY REGISTROS ENCONTRADOS!</h2>';
     }

?>
