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
            echo "<title>Dueños</title>";
        }else{
            echo "<title>Mis datos</title>";
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

    <section id="duenios">

    <!-- ENVIO LA BUSQUEDA POR LA URL COMO GET YA QUE EN EL TEMA DE FILTROS NO SUPONE NINGUN PELIGRO -->
    <?php
    if($_SESSION["dni"]==="000000000"){
        echo "<form class='busqueda' action='#' method='GET'>
            <input type='text' name='busqueda' id='busqueda' maxlength='50'>
            <input type='submit' value='Buscar'>
            <p>*Nombre del dueño, nick o teléfono</p>
        </form>

        <a id='agregar' href='insertar_duenio.php'>Insertar nuevo dueño</a>";
    }
    ?>

    <?php  
    
        //VEO SI SE HA REALIZADO O NO LA BUSQUEDA PARA HACER LA CONSULTA DE LA BUSQUEDA O DE TODOS LOS DUEÑOS
        if(!isset($_GET['busqueda'])){
            if($_SESSION["dni"]==="000000000"){
                $consulta_duenio="SELECT dni,nombre,telefono,nick FROM dueño WHERE dni<>'$_SESSION[dni]'";
            }else{
                $consulta_duenio="SELECT dni,nombre,telefono,nick FROM dueño WHERE dni='$_SESSION[dni]'";
            }
            
            $resultado=$conexion->query($consulta_duenio);

        }else{
            $busqueda=trim($_GET['busqueda']);
            $busqueda="%$busqueda%";

            //SELECCIONO LOS NOMBRES, NICKS Y TELEFONOS QUE COINCIDAD TOTAL O PARCIALMENTE CON LA BUSQUEDA
            if($_SESSION["dni"]==="000000000"){
                $consulta_duenio = "SELECT dni,nombre,telefono,nick FROM dueño WHERE (nombre LIKE ? OR nick LIKE ? OR telefono LIKE ?) AND dni<>'$_SESSION[dni]'";
            }else{
                $consulta_duenio = "SELECT dni,nombre,telefono,nick FROM dueño WHERE (nombre LIKE ? OR nick LIKE ? OR telefono LIKE ?) AND dni='$_SESSION[dni]'";
            }

            $resultado=$conexion->prepare($consulta_duenio);
            $resultado->bind_param("sss", $busqueda, $busqueda, $busqueda);
            $resultado->bind_result($dni,$nombre,$telefono,$nick);
            $resultado->execute();
            $resultado->store_result();         
        }

        $filas_devueltas=$resultado->num_rows;  

        // VEO SI DEVUELVE ALGUNA FILA PARA EMPEZAR A MOSTRAR O POR EL CONTRARIO DIGO QUE NO HAY NINGUNA
        if($filas_devueltas===0){

            //SI HE REALIZADO LA BUSQUEDA ME MOSTRARÁ UN MENSAJE DISTINTO A SI ME METO DE PRIMERAS EN LA PÁGINA
            if(isset($busqueda)){
                echo "<h2>No hay dueños que coincidan con la búsqueda</h2>";
            }else{
                echo "<h2>No hay dueños que mostrar</h2>";
            }
        }else{

            echo "<table>";
            echo "<tr><th>DNI</th><th>Nombre</th><th>Teléfono</th><th>Nick</th></tr>";

            //SI HE BUSCADO MOSTRARÁ LOS RESULTADOS QUE COINCIDAN CON LA BUSQUEDA. SINO MOSTRARÁ TODOS
            if(isset($busqueda)){
                while ($resultado->fetch()){                
                    echo "<tr>
                            <td>$dni</td>
                            <td>$nombre</td>";

                            // SI NO HA INTRODUCIDO NINGUN TELEFONO PONEMOS UN MENSAJE DE QUE NO HAY
                            if($telefono===null){
                                echo "<td>Pendiente</td>";
                            }else{
                                echo "<td>$telefono</td>";
                            }  

                    echo    "<td>$nick</td>
                                <td>
                                    <form action='modificar_duenio.php' method='POST'>
                                        <input type='hidden' name='dni' value='$dni'>
                                        <input class='modificar' type='submit' value='Modificar'>
                                    </form>
                                </td>
                            
                        </tr>";    
                }
                $resultado->close();
            }else{
                while($fila_duenio=$resultado->fetch_array(MYSQLI_ASSOC)){
                    echo "<tr>
                            <td>$fila_duenio[dni]</td>
                            <td>$fila_duenio[nombre]</td>"; 
                            
                            // SI NO HA INTRODUCIDO NINGUN TELEFONO PONEMOS UN MENSAJE DE QUE NO HAY
                            if($fila_duenio["telefono"]===null){
                                echo "<td>Pendiente</td>";
                            }else{
                                echo "<td>$fila_duenio[telefono]</td>";
                            }        

                    echo    "<td>$fila_duenio[nick]</td>
                            <td>
                            <form action='modificar_duenio.php' method='POST'>
                                <input type='hidden' name='dni' value='$fila_duenio[dni]'>
                                <input class='modificar' type='submit' value='Modificar'>
                            </form>
                        </td>
                    
                    </tr>";   
                } 
            }

            echo "</table>";
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