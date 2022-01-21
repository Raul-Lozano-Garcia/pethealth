<?php
    session_start();

    if(isset($_GET["cerrar_sesion"])){
        if(isset($_COOKIE['mantener'])){
            setcookie("mantener",null,time()-60,"/");
        }
        $_SESSION=array();
        session_destroy();
        header('Location: ../../index.php');
    }else{
        if(isset($_COOKIE["mantener"])){
            $_SESSION["dni"]=$_COOKIE["mantener"];
        }
    
        if(!isset($_SESSION["dni"])){
            header('Location: ../acceder/acceder.php');
        }

    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        if($_SESSION["dni"]==="000000000"){
            echo "<title>Citas</title>";
        }else{
            echo "<title>Mis citas</title>";
        }
    ?>
    <script type="text/javascript" src="../../js/app.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="icon" href="../../assets/imagenes/logo_negro.png" type="image/png"/>
    <link rel="stylesheet" href="../../css/estilos.css">
</head>
<body>

    <!-- ENLAZO PARA PODER USAR FUNCIONES Y ME CONECTO A LA BASE DE DATOS  -->
    <?php
        require_once("../../php/funciones.php");
        $conexion=conectarServidor();
    ?>

    <!-- INSERTO EL HEADER -->
    <?php
        crearHeader('../..');
    ?>

    <main>

    <section id="citas">

    <!-- BORRO LA CITA -->
    <?php
        if(isset($_POST["cliente"]) && isset($_POST["fecha"]) && isset($_POST["hora"])){
            $cliente=$_POST["cliente"];
            $fecha=$_POST["fecha"];
            $hora=$_POST["hora"];
            $consulta_borrar="DELETE FROM citas WHERE cliente='$cliente' AND fecha='$fecha' AND hora='$hora'";
            $conexion->query($consulta_borrar);
            echo "<meta http-equiv='refresh' content='0; url=citas.php'>";
        }
    ?>

    <form class="busqueda" action='#' method='GET'>
        <select name="mes" id="mes">
            <option value="1">Enero</option>
            <option value="2">Febrero</option>
            <option value="3">Marzo</option>
            <option value="4">Abril</option>
            <option value="5">Mayo</option>
            <option value="6">Junio</option>
            <option value="7">Julio</option>
            <option value="8">Agosto</option>
            <option value="9">Septiembre</option>
            <option value="10">Octubre</option>
            <option value="11">Noviembre</option>
            <option value="12">Diciembre</option>
        </select>
        <input type="number" name="anio" id="anio" placeholder="Año" min="1">
        <input type='submit' value='Buscar'>
    </form>

<?php
        
        
        setlocale(LC_ALL, "es-ES.UTF-8");

        //DEVUELVE EN FORMATO NUMERICO LOS DOS
        if(!isset($_GET['anio']) || trim($_GET['anio'])===''){ //Si no le he pasado el año aún, es decir, no se ha hecho ninguna busqueda o he buscado sin meter el año
            $m = strftime("%m", time()); //Devuelve el mes actual el formato XX
            $a = strftime("%Y", time()); //Devuelve el año actual en formato XXXX
        }else{
            $marca=mktime(0,0,0,trim($_GET["mes"]),1,trim($_GET["anio"])); //Saco la marca de tiempo de la busqueda y con ella saco el mes y el año en el mismo formato que arriba
            $m = strftime("%m", $marca); //Devuelve el mes buscado el formato XX
            $a = strftime("%Y", $marca); //Devuelve el año buscado en formato XXXX    
        }

        $marca=mktime(0,0,0,$m,1,$a);

        $dias_mes=date('t', $marca);
        $numero_dia=date('N',$marca);
        $nombre_mes=strftime("%B", $marca);

        //SACO EL DIA DE HOY
        $diaHoy=date("Y-m-j",time());

        echo "<h2>$nombre_mes-$a</h2>"; 

        
        echo "<table id='calendario'>
            <tr>
                <th>L</th>
                <th>M</th>
                <th>X</th>
                <th>J</th>
                <th>V</th>
                <th>S</th>
                <th>D</th>
            </tr>
            <tr>";
            $c=0;
            for ($i=1; $i <= $dias_mes; $i++) {
                if ($c == 7){
                    echo "</tr><tr>";
                    $c=0;
                }
                if ($i == 1) {
                    for ($j=1; $j < ($numero_dia); $j++) {
                        echo "<td></td>";
                        $c++;
                    }
                }

                //VEO SI EL DIA QUE SE VAYA A PINTAR EN LA TABLA HAY CITA O NO
                if($_SESSION["dni"]==="000000000"){
                    $consulta_citas="SELECT fecha FROM citas WHERE fecha='$a-$m-$i'"; 
                }else{
                    $consulta_citas="SELECT fecha FROM citas INNER JOIN cliente ON id=cliente WHERE fecha='$a-$m-$i' AND dni_dueño='$_SESSION[dni]' "; 
                }

                $datos_citas=$conexion->query($consulta_citas);
                $filas_devueltas=$datos_citas->num_rows;

                // RESALTAMOS EL DÍA DE HOY
                if("$a-$m-$i"==$diaHoy){
                    if ($filas_devueltas===0) {
                        echo "<td class='esHoy'>$i</td>";              
                    } else {
                        $fila_cita=$datos_citas->fetch_array(MYSQLI_ASSOC);
                        echo "<td class='esHoy hayCita'><a href='citas.php?busqueda=$fila_cita[fecha]#'>$i</a></td>";
                    }
                }else{
                    if ($filas_devueltas===0) {
                        echo "<td>$i</td>";              
                    } else {
                        $fila_cita=$datos_citas->fetch_array(MYSQLI_ASSOC);
                        echo "<td class='hayCita'><a href='citas.php?busqueda=$fila_cita[fecha]#'>$i</a></td>";
                    }
                }
                $c++;
            }
            echo "</tr>
                </table>"; 
        
?>

<?php

    echo "<div id='pasar'>";
    //PARA EL MES ANTERIOR
    if($m===1){
        $anioAnterior=$a-1;
        $mesAnterior=12;
    }else{
        $anioAnterior=$a;
        $mesAnterior=$m-1;
    }
    echo "<a href='citas.php?mes=$mesAnterior&anio=$anioAnterior'>Anterior</a>";

    //PARA EL MES SIGUIENTE
    if($m===12){
        $anioSiguiente=$a+1;
        $mesSiguiente=1;
    }else{
        $anioSiguiente=$a;
        $mesSiguiente=$m+1;
    }
    echo "<a href='citas.php?mes=$mesSiguiente&anio=$anioSiguiente'>Siguiente</a>";

    echo "</div>";

?>

<form class="busqueda" action='#' method='GET'>
    <input type='text' name='busqueda' id='busqueda' maxlength='20'>
    <input type='submit' value='Buscar'>
    <p>*Nombre de la mascota, servicio o fecha en formato (aaaa-mm-dd)</p>
</form>

<?php
    if($_SESSION["dni"]==="000000000"){
        echo "<a id='agregar' href='insertar_cita.php'>Insertar nueva cita</a>";
    }
?>




<?php  

    //VEO SI SE HA REALIZADO O NO LA BUSQUEDA PARA HACER LA CONSULTA DE ELLA O DEL DÍA DE HOY
    $hoy=date("Y-m-d",time());

    if(isset($_GET['busqueda'])){
        $busqueda=trim($_GET['busqueda']);
        $busqueda="%$busqueda%";
        if($_SESSION["dni"]==="000000000"){
            $consulta_de_busqueda="SELECT cliente,nombre,descripcion,fecha,hora FROM citas INNER JOIN cliente ON cliente.id=citas.cliente INNER JOIN servicio ON servicio.id=citas.servicio WHERE (nombre LIKE ? OR descripcion LIKE ? OR fecha LIKE ?)";
        }else{
            $consulta_de_busqueda="SELECT cliente,nombre,descripcion,fecha,hora FROM citas INNER JOIN cliente ON cliente.id=citas.cliente INNER JOIN servicio ON servicio.id=citas.servicio WHERE (nombre LIKE ? OR descripcion LIKE ? OR fecha LIKE ?) AND dni_dueño='$_SESSION[dni]'";
        }
        $resultado_busqueda=$conexion->prepare($consulta_de_busqueda);
        $resultado_busqueda->bind_param("sss", $busqueda, $busqueda, $busqueda);
        $resultado_busqueda->bind_result($cliente,$nombre,$descripcion,$fecha,$hora);
        $resultado_busqueda->execute();
        $resultado_busqueda->store_result(); 
    }else{
        if($_SESSION["dni"]==="000000000"){
            $consulta_de_busqueda="SELECT cliente,nombre,descripcion,fecha,hora FROM citas INNER JOIN cliente ON cliente.id=citas.cliente INNER JOIN servicio ON servicio.id=citas.servicio WHERE fecha='$hoy'";
        }else{
            $consulta_de_busqueda="SELECT cliente,nombre,descripcion,fecha,hora FROM citas INNER JOIN cliente ON cliente.id=citas.cliente INNER JOIN servicio ON servicio.id=citas.servicio WHERE dni_dueño='$_SESSION[dni]'";
        }
        $resultado_busqueda=$conexion->query($consulta_de_busqueda);
    }
    $filas_devueltas_busqueda=$resultado_busqueda->num_rows; 

    // VEO SI DEVUELVE ALGUNA FILA PARA EMPEZAR A MOSTRAR O POR EL CONTRARIO DIGO QUE NO HAY NINGUNA
    if($filas_devueltas_busqueda===0){
        if(isset($busqueda)){
            echo "<h3>No hay citas que coincidan con la búsqueda</h3>";
        }else{
            echo "<h3>No hay citas que mostrar</h3>";
        }
    }else{

        if(isset($busqueda)){
            echo "<table>";
            echo "<tr><th>Nombre</th><th>Servicio</th><th>Fecha</th><th>Hora</th></tr>";
            while ($resultado_busqueda->fetch()){  
                //FORMATEMOS LA FECHA PARA QUE SEA MÁS ENTENDIBLE
                $fecha_formateada=formatearFecha($fecha);  
                //FORMATEAMOS LA HORA PARA QUE SEA MÁS ENTENDIBLE
                $hora_formateada=formatearHora($hora);       
                echo "<tr>
                        <td>$nombre</td>
                        <td>$descripcion</td>
                        <td>$fecha_formateada</td>
                        <td>$hora_formateada</td>";
                        if($fecha>$hoy && $_SESSION["dni"]==="000000000"){
                            echo "<td>
                                    <form action='#' method='POST'>
                                        <input type='hidden' name='cliente' value='$cliente'>
                                        <input type='hidden' name='fecha' value='$fecha'>
                                        <input type='hidden' name='hora' value='$hora'>
                                        <input class='borrar' type='submit' name='borrar' value='Borrar'>
                                    </form>
                                </td>";
                        }
                echo "</tr>";    
            }
            $resultado_busqueda->close();
            echo "</table>";
        }else{
            echo "<table>";
            echo "<tr><th>Nombre</th><th>Servicio</th><th>Fecha</th><th>Hora</th></tr>";
            while($fila_busqueda=$resultado_busqueda->fetch_array(MYSQLI_ASSOC)){
                //FORMATEMOS LA FECHA PARA QUE SEA MÁS ENTENDIBLE
                $fecha_formateada=formatearFecha($fila_busqueda["fecha"]);
                //FORMATEAMOS LA HORA PARA QUE SEA MÁS ENTENDIBLE
                $hora_formateada=formatearHora($fila_busqueda["hora"]); 
                echo "<tr>
                        <td>$fila_busqueda[nombre]</td>
                        <td>$fila_busqueda[descripcion]</td>
                        <td>$fecha_formateada</td>
                        <td>$hora_formateada</td>";
                echo "</tr>";   
            } 
            echo "</table>";
        }
    }

?>

</section>

</main>


<!-- INSERTO EL FOOTER -->
<?php
    crearFooter('../..');
?>

<!-- ME DESCONECTO DE LA BASE DE DATOS -->
<?php
    $conexion->close();
?>
</body>
</html>