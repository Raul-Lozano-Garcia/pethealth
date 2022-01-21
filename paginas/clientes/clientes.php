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
            echo "<title>Clientes</title>";
        }else{
            echo "<title>Mis mascotas</title>";
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

    <section id="clientes">

        <!-- ENVIO LA BUSQUEDA POR LA URL COMO GET YA QUE EN EL TEMA DE FILTROS NO SUPONE NINGUN PELIGRO -->
        <?php
            if($_SESSION["dni"]==="000000000"){
                echo "<a id='agregar' href='insertar_cliente.php'>Insertar nuevo cliente</a>
                
                <form class='busqueda' action='#' method='GET'>
                    <input type='text' name='busqueda' id='busqueda' maxlength='50'>
                    <input type='submit' value='Buscar'>
                    <p>*Nombre del animal, del dueño o teléfono</p>
                </form>";
            }
        ?>

    <?php  
    
        //VEO SI SE HA REALIZADO O NO LA BUSQUEDA PARA HACER LA CONSULTA DE LA BUSQUEDA O DE TODOS LOS CLIENTES
        if(!isset($_GET['busqueda'])){
            if($_SESSION["dni"]==="000000000"){
                $consulta_cliente="SELECT id,cliente.nombre nom_cli,tipo,edad,foto,dueño.nombre nom_due FROM cliente INNER JOIN dueño ON dni_dueño=dni";
            }else{
                $consulta_cliente="SELECT id,cliente.nombre nom_cli,tipo,edad,foto,dueño.nombre nom_due FROM cliente INNER JOIN dueño ON dni_dueño=dni WHERE dni='$_SESSION[dni]'";
            }

            $resultado=$conexion->query($consulta_cliente);
                
        }else{
            $busqueda=trim($_GET['busqueda']);
            $busqueda="%$busqueda%";

            //SELECCIONO LOS NOMBRES, NOMBRES_DUEÑO Y TELEFONOS QUE COINCIDAD TOTAL O PARCIALMENTE CON LA BUSQUEDA
            if($_SESSION["dni"]==="000000000"){
                $consulta_cliente = "SELECT id,cliente.nombre nom_cli,tipo,edad,foto,dueño.nombre nom_due FROM cliente INNER JOIN dueño ON dni_dueño=dni WHERE (cliente.nombre LIKE ? OR dueño.nombre LIKE ? OR telefono LIKE ?)";
            }else{
                $consulta_cliente = "SELECT id,cliente.nombre nom_cli,tipo,edad,foto,dueño.nombre nom_due FROM cliente INNER JOIN dueño ON dni_dueño=dni WHERE (cliente.nombre LIKE ? OR dueño.nombre LIKE ? OR telefono LIKE ?) AND dni='$_SESSION[dni]'";
            }
            
            $resultado=$conexion->prepare($consulta_cliente);
            $resultado->bind_param("sss", $busqueda, $busqueda, $busqueda);
            $resultado->bind_result($id,$nom_cli,$tipo,$edad,$foto,$nom_due);
            $resultado->execute();
            $resultado->store_result();         
        }

        $filas_devueltas=$resultado->num_rows;  

        // VEO SI DEVUELVE ALGUNA FILA PARA EMPEZAR A MOSTRAR O POR EL CONTRARIO DIGO QUE NO HAY NINGUNA
        if($filas_devueltas===0){

            //SI HE REALIZADO LA BUSQUEDA ME MOSTRARÁ UN MENSAJE DISTINTO A SI ME METO DE PRIMERAS EN LA PÁGINA
            if(isset($busqueda)){
                if($_SESSION["dni"]==="000000000"){
                    echo "<h2>No hay clientes que coincidan con la búsqueda</h2>";
                }else{
                    echo "<h2>No tienes mascotas que coincidan con la búsqueda</h2>";
                }
                
            }else{
                if($_SESSION["dni"]==="000000000"){
                    echo "<h2>No hay clientes que mostrar</h2>";
                }else{
                    echo "<h2>No tienes mascotas aún registradas</h2>";
                }
            }
        }else{

            echo "<table>";
            echo "<tr><th>Foto</th><th>Nombre</th><th>Tipo de animal</th><th>Edad (años)</th><th>Nombre del dueño</th></tr>";

            //SI HE BUSCADO MOSTRARÁ LOS RESULTADOS QUE COINCIDAN CON LA BUSQUEDA. SINO MOSTRARÁ TODOS
            if(isset($busqueda)){
                while ($resultado->fetch()){                
                    echo "<tr>
                            <td>
                                <img src='../../assets/imagenes/clientes/$foto' alt='cliente'>
                            </td>
                            <td>$nom_cli</td>
                            <td>$tipo</td>
                            <td>$edad</td>
                            <td>$nom_due</td>";
                            
                            if($_SESSION["dni"]==="000000000"){
                                echo "<td>
                                <form action='modificar_cliente.php' method='POST'>
                                    <input type='hidden' name='id' value='$id'>
                                    <input class='modificar' type='submit' value='Modificar'>
                                </form>
                            </td>";
                            }

                        echo "</tr>";    
                }
                $resultado->close();
            }else{
                while($fila_cliente=$resultado->fetch_array(MYSQLI_ASSOC)){
                    echo "<tr>
                            <td>
                                <img src='../../assets/imagenes/clientes/$fila_cliente[foto]' alt='cliente' width='100px'>
                            </td>
                            <td>$fila_cliente[nom_cli]</td>
                            <td>$fila_cliente[tipo]</td>
                            <td>$fila_cliente[edad]</td>
                            <td>$fila_cliente[nom_due]</td>";
                            if($_SESSION["dni"]==="000000000"){
                                echo "<td>
                                <form action='modificar_cliente.php' method='POST'>
                                    <input type='hidden' name='id' value='$fila_cliente[id]'>
                                    <input class='modificar' type='submit' value='Modificar'>
                                </form>
                            </td>";
                            }
                        echo "</tr>";   
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