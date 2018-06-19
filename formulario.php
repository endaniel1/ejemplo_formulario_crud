<?php
include("conex.php");
$mensaje="";
$where="";


$ano = date('Y') - 12;
$ano2 = date('Y') - 6;
//aqui tengo las varibles de la facha actual

function edad_actual($ano_nacimento){
  $datosEdad=explode("-",$ano_nacimento);//aqui divido el año de nacimento

  $edadactual="0".(date('Y')-$datosEdad[0]);//ESTA ES LA EDAD RESTANDOLE EL AÑO ACTUAL

  //ESTA ES LA COMPROBACION PARA VER SI  YA CUMPLIO AÑO
  if($datosEdad[1]<date('m')){
      if ($datosEdad[2]>date('d')) {
        $edadactual="0".($edadactual+1);
      }
  }else if($datosEdad[1]>=date('m')) {
    $edadactual="0".($edadactual+1);
  }
  return $edadactual;
}

if (isset($_POST['ced'])) {
  //echo "Mensaje";
  $cedula=$_POST['ced'];
  $nombre=$_POST['nom'];
  $apellido=$_POST['ape'];
  $telefono=$_POST['tel'];
  $correo=$_POST['cor'];
  $fecha_nacimiento=$_POST['fec_nac'];

  //verifica si no hay un registro igual en la base de datos
  $sql="SELECT * FROM registros WHERE ced='$cedula'";
  $p=mysqli_query($enlace,$sql);
  if(mysqli_num_rows($p) > 0){
    $mensaje= '<h3><b>Este Registro '.$cedula.' ya Existe</b></h3>';
  }else{
    //insetar registro
    $sql="INSERT INTO registros(ced,nom,ape,tel,cor,fec_nac) VALUE('".$cedula."','".$nombre."','".$apellido."','".$telefono."','".$correo."','".$fecha_nacimiento."')";
    if (mysqli_query($enlace, $sql)) {
        $mensaje = '<h3><b>Registro Registrado Satisfactorio.!</b></h3>';
    }else {
      $mensaje = '<h3><b>Error al Registrar.!</b></h3>';
    }
  }
}

//modificaion de los datos
if (isset($_POST['cedM'])) {
    $nombre = $_POST['nom'];
    $apellido = $_POST['ape'];

    $cedula = $_POST['cedM'];
    $fechanacimento = $_POST['fec_nac'];
    $correo=$_POST['cor'];
    $telefono=$_POST['tel'];

    $sql = "UPDATE registros SET nom='$nombre',
    ape='$apellido',tel='$telefono',cor='$correo',fec_nac='$fechanacimento'
    where ced='" . $cedula . "'";

    if (mysqli_query($enlace, $sql)) {
      $mensaje = '<b>Registro Modificado Satisfactoramente.!</b>';
    } else $mensaje = '<b>Error al Modificar</b>';
}
//consultando los datos pra despues modificarlos
if (isset($_REQUEST['editar'])) {
  $sql = "SELECT  *  FROM  registros  WHERE ced='".$_REQUEST['editar']."'";
  $consultaDatos = mysqli_query($enlace, $sql);
  $datosEditar = mysqli_fetch_assoc($consultaDatos);


  $edadactual=edad_actual($datosEditar["fec_nac"]);
  $edita="si";
}

//eliminar estudiante
if (isset($_REQUEST['eliminar'])) {
  $sql = "DELETE  FROM registros  WHERE ced='".$_REQUEST['eliminar']."'";
  if (mysqli_query($enlace, $sql)) {
    $mensaje = '<h3><b>Registro Eliminado Satisfactoramente.!</b></h3>';

  } else $mensaje = '<h3><b>Error al Eliminar</b></h3>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Formulario de Prueba</title>
    <!-- css -->
  <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/estilo.css" rel="stylesheet" type="text/css">
  <style>
 input:required:invalid, input:focus:invalid {
 background-image: url(img/invalido.png);
 background-position: right top; background-repeat: no-repeat;
 }
 input:required:valid {background-image: url(img/valido.png);
 background-position: right top;
 background-repeat: no-repeat;
 }
 </style>

  <!-- js -->
  <script src="js/jquery-3.1.1.js"></script>
  <script src="js/jquery-ui.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      var ANO = (new Date).getFullYear();//año actual
      var MES = (new Date).getMonth();//mes actual
      var DIA = (new Date).getMonth();//dia actual

      ///calcular la edad mediante la fecha de nacimiento
    	$('#fec_nac').change(function() {

           var ano_reco=$('#fec_nac').val().split('-');
    	  	 var ano_reco1=ano_reco[0];//AÑOS DE NACIMIENTO
    	     var ano_reco2=ano_reco[1];//MES DE NACIMIENTO
    	  	 var ano_reco3=ano_reco[2];//DIA DE NACIMIENTO
    		  //si el mes es el mismo pero el día inferior aun no ha cumplido años, le quitaremos un año al actual
    		  if(ano_reco2<MES && ano_reco3>DIA){
             ANO-1
           }

    			//si el mes es superior al actual tampoco habrá cumplido años, por eso le quitamos un año al actual
    			  if(ano_reco2>MES){
              ANO-1
            }
    				//ya no habría mas condiciones, ahora simplemente restamos los años y mostramos el resultado como su edad
    				var edad=Number(ANO-ano_reco1);//variable de la edad
    				if(edad>=6&&edad <=9){
              edad='0'+edad;//concadeno con un cero si es menor q 9
            }
            if(edad>12||edad<=0){
              edad='00';
            }
            //aqui asignamos el valor de el campo que tenga el id eda
            $('#eda').val(edad);

        });
        //COMIENZO DE BUSQUEDA
        var resp2=$('.Tabla_Busqueda').html();//guardo el valor de la tablas con la class="guardar"
        //DESABILITA EL FORMULARIO
       $('#form2').submit(function(e){
         //alert("esta enviando un sumt");
         e.preventDefault();
       });

       //si empiezo a escribir en el input
       $('#buscor').keyup(function(){
         //alert("esta escribiendo algo");
         var envio=$('#buscor').val();//OBTIENE EL VALOR DEL IMPUT

        //DONDE GUARDO LA IMAGEN
   			$('.Tabla_Busqueda').html('<h2><img src="img/cargando.gif" width="10%"  alt="Cargando"/>cargando </h2>');
   			$.ajax({
   				type:'POST',
   				url:'busqueda.php',
   				data:('buscor='+envio),
          success: function(resp){
            if(resp!=""){
              	$('.Tabla_Busqueda').html(resp);
              }else{
                $('.Tabla_Busqueda').html(resp2);
              }
            }
        })
      });

      $('#nom, #ape').keyup(function(){
        var x = capitalize($(this).val());

        $(this).val(x);


        function capitalize(s){
            return s.toLowerCase().replace( /(^|\s)([A-zÀ-ú])/g, function(a){
              return a.toUpperCase();
            });
        };
      });
    });

  </script>
</head>
<body>
  <div class="container">
    <div class="row">
         <div class="col-lg-12 text-center">
             <h2><span class="glyphicon glyphicon-book"></span> Registro </h2>
             <hr class="star-primary">
         </div>
      </div>
    <div class="row">
      <form action="?m=1" method="post" id="form1" class="form1">
          <table width="50%" border="0" align="center" cellpadding="5" cellspacing="5" id="tabla">
            <tr>
              <td colspan="2" align="center">
              <?php echo $mensaje; ?>
              </td>
            </tr>
            <tr>
              <td width="30%">
                  <div align="right" style="padding-right:4%;"><b>Cédula:</b></div>
              </td>
              <td>
                <input type="text" name="ced" class="form-control placeholder-no-fix" style="width:80%;" maxlength="10" required pattern="[0-9]{8,10}" placeholder="Escriba su Cédula"
                <?php if (isset($edita)) {
                  echo 'value="' . $datosEditar['ced'] . '"';
                  echo " disabled ";
                } ?>>
                <?php if (isset($edita)) echo '<input  name="cedM"  type="hidden" id="cedM"
                value="' . $datosEditar['ced'] . '" />'; ?>
              </td>
            </tr>
            <tr>
              <td width="30%">
                <div align="right" style="padding-right:4%;"><b>Nombre:</b></div>
              </td>
              <td>
                <input type="text" name="nom" id="nom" class="form-control placeholder-no-fix" style="width:80%;" maxlength="20" placeholder="Escriba su Nombre" required pattern="[a-z A-ZñÑáéíóúÁÉÍÓÚ]{3,20}"
                <?php
                  if (isset($edita)) echo 'value="' . $datosEditar['nom'] . '"';
                ?>/>
              </td>
            </tr>
            <tr>
              <td width="30%">
                  <div align="right" style="padding-right:4%;"><b>Apellido:</b></div>
              </td>
              <td>
                <input type="text" name="ape" id="ape" class="form-control placeholder-no-fix" style="width:80%;" maxlength="20" required pattern="[a-z A-ZñÑáéíóúÁÉÍÓÚ]{3,20}" placeholder="Escriba su Apellido"
                <?php
                  if (isset($edita)) echo 'value="' . $datosEditar['ape'] . '"';
                ?>/>
              </td>
            </tr>
            <tr>
              <td width="30%">
                  <div align="right" style="padding-right:4%;"><b>Teléfono:</b></div>
              </td>
              <td>
                <input type="text" name="tel" class="form-control placeholder-no-fix" style="width:80%;" maxlength="15" required pattern="^[0]\d{10}$" placeholder="Teléfono"
                <?php
                  if (isset($edita)) echo 'value="' . $datosEditar['tel'] . '"';
                ?>/>
              </td>
            </tr>
            <tr>
              <td width="30%">
                  <div align="right" style="padding-right:4%;"><b>Correo:</b></div>
              </td>
              <td>
                <input type="email" name="cor" class="form-control placeholder-no-fix" style="width:80%;" maxlength="30" required pattern="[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*@[a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,5}" placeholder="Correo Electrónico"
                <?php
                  if (isset($edita)) echo 'value="' . $datosEditar['cor'] . '"';
                ?>
                />
              </td>
            </tr>
            <tr>
              <td width="30%">
                  <div align="right" style="padding-right:4%;"><b>Fecha de Nacimiento:</b></div>
              </td>
              <td>
                <input type="date" class="form-control placeholder-no-fix" name="fec_nac" style="width:80%;" required  id="fec_nac"
                <?php
                  echo 'min="' . $ano . '-01-01" ';
                  echo 'step="' . $ano . '-01-01" ';
                  echo 'max="' . $ano2 . '-12-31" ';
                  if (isset($edita)) echo 'value="' . $datosEditar['fec_nac'] . '"';
                 ?>/>
              </td>
            </tr>
            <tr>
              <td width="30%">
                  <div align="right" style="padding-right:4%;"><b>Edad:</b></div>
              </td>
              <td>
                <input type="text" name="eda" id="eda" class="form-control placeholder-no-fix" style="width:80%;" maxlength="2" required  placeholder="Edad" readonly
                <?php
                  if (isset($edita)) echo 'value="' . $edadactual . '"';
                ?>/>
              </td>
            </tr>
            <tr>
              <td align="right">
                  <button type="submit" name="button" id="button" class="btn btn-success btn-md" value="Enviar"><span class="glyphicon glyphicon-send"></span> Enviar Registro</button>
              </td>
              <td align="left">
                <button type="reset" class="btn btn-danger btn-md"><span class="glyphicon glyphicon-remove-circle"></span> Borrar Datos</button>
              </td>
            </tr>
          </table>
      </form>
    </div>

    <div align="center" class="row">
      <form action="?m=1" method="post" name="form2" id="form2">
        <h3>Opciones de Búsquedas</h3>
          <input id="buscor" autocomplete="off" class="form-control placeholder-no-fix" style="width:30%;" name="valor" type="text" placeholder="Escriba para buscar" />
      </form>
    </div>


  </div>
  <div class="Tabla_Busqueda">
    <?php
        if (!isset($_REQUEST['pg'])) $n_pag = 1; else $n_pag=$_REQUEST['pg'];
        $cantidad=25;
        $inicial = ($n_pag-1) * $cantidad;
        //Fin del Limite para la cantidad q queremos filtar

        $sqlP = "SELECT * FROM registros";
        $consulta = mysqli_query($enlace,$sqlP) or die ("Error");
        //fin de las consulta total de los registros

        $cant_registros =mysqli_num_rows($consulta);
        $paginado = round($cant_registros / $cantidad,PHP_ROUND_HALF_EVEN);
        //fin de lo q queremos paginar

        $sqlP = "SELECT * FROM registros LIMIT $inicial,$cantidad ".$where."";
        $consulta = mysqli_query($enlace,$sqlP) or die ("Error");
        $cant_registros2 =mysqli_num_rows($consulta);
        //hasta aqui es la consulta limitada

        echo "</br><center><font color='#0db8ca'><b>Cantidad de Registrados: ".$cant_registros." - Límite Mostrado: Del
        ".($inicial+1)." al ".($inicial + $cant_registros2)."</b></font></center><br>";
        if($cant_registros==""){
        echo '<table border="1" align="center" style="border-radius:8px;" width="60%">
          <tr>
            <td align="center" colspan="2" style="color: #FFF; background-color:#3e89d5 ;font-family: Gadget, sans-serif;"><b>No Hay Registros</b></td>
          </tr>';
        	}else{
        $sqlP = "SELECT * FROM registros LIMIT $inicial,$cantidad ".$where."";
        $cons=mysqli_query($enlace,$sqlP);
        echo '<table align="center" width="60%" class="table table-striped table-bordered table-hover table-condensed guardar">
        <tr>
          <td align="center"><b>Cédula</b></td>
          <td align="center"><b>Nombre</b></td>
          <td align="center"><b>Apellido</b></td>
          <td align="center"><b>Teléfono</b></td>
          <td align="center"><b>Correo</b></td>
          <td align="center"><b>Fecha de Nacimiento</b></td>
          <td align="center"><b>Edad</b></td>
          <td align="center" colspan="2" ><b>Opciones</b></td>
        </tr>';
        while($datos=mysqli_fetch_assoc($cons)){
        $primario= $datos['ced'];
        echo "<tr align='center' >";
        echo "<td>".$datos['ced']."</td>";
        echo "<td>".ucwords($datos['nom'])."</td>";
        echo "<td>".ucwords($datos['ape'])."</td>";
        echo "<td>".$datos['tel']."</td>";
        echo "<td>".$datos['cor']."</td>";
        echo "<td>".$datos['fec_nac']."</td>";

        echo "<td>".edad_actual($datos["fec_nac"])."</td>";

        echo  "<td  align='center'><a alt='Eliminar  Registro'
        href='?m=1&eliminar=$primario'><span class='glyphicon glyphicon-trash'></span> Eliminar</a></td>";
        echo  "<td  align='center'><a alt='Editar  Registro  '
        href='?m=1&editar=$primario'><span class='glyphicon glyphicon-pencil'></span> Editar</a></td>";
        echo "</tr>";
        }
        echo "<tr align='center' ><td colspan=11 >";
        if($n_pag>1){
        	echo '<a href="formulario.php?pg='.($n_pag-1).'"> Anterior <a/>&nbsp;';
        	}
        for ($a=1;$a<=$paginado;$a++){
        	if($a==$n_pag){
        	//echo $a.' ';
        	}
        	else{
        echo '<a href="formulario.php?pg='.$a.'">'.$a.'<a/>&nbsp;';
        }
        }
        if($n_pag < $paginado)
        {
        	echo '<a href="formulario.php?pg='.($n_pag+1).'"> Siguente <a/>&nbsp;';
        	}
        echo "</td></tr>";

        	}
    ?>
  </div>
  <!-- Bootstrap Core JavaScript -->
  <script src="js/bootstrap.min.js"></script>
</body>
</html>
