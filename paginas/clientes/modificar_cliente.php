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
        }else if($_SESSION["dni"]!=="000000000"){
            header('Location: ../acceso_denegado.php');
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar cliente</title>
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

    <section id="insertar_modificar">

    <?php  
        //COMPRUEBO QUE PASE 'ID'. SINO REDIRIJO
        if(isset($_POST['id'])){
            $id=$_POST['id'];

            $consulta="SELECT cliente.nombre nom_cli,tipo,edad,foto,dueño.nombre nom_due,dni FROM cliente INNER JOIN dueño ON dni_dueño=dni WHERE id=$id";      
            $resultado=$conexion->query($consulta);

            $fila_cliente=$resultado->fetch_array(MYSQLI_ASSOC);

            //RECOJO LOS DATOS ANTES DE MODIFICAR EN VARIABLES
            $tipo=$fila_cliente["tipo"];
            $nom_cli=$fila_cliente["nom_cli"];
            $edad=$fila_cliente["edad"];
            $foto_antigua=$fila_cliente["foto"];
            $nom_due=$fila_cliente["nom_due"];
            $dni=$fila_cliente["dni"];

            // SACO UNA LISTA CON LOS DUEÑOS DE LA CLINICA MENOS CON EL QUE YA ESTABA ANTES DE MODIFICAR
            $consulta_duenios="SELECT dni, nombre FROM dueño WHERE dni<>'$dni'";
            $datos_duenios=$conexion->query($consulta_duenios);
            $filas_duenios=$datos_duenios->num_rows;
            
    ?>

    <h1>Editar cliente</h1>
    <form action="#" method="POST" enctype="multipart/form-data">

    <input type='hidden' name="id" value="<?php echo $id; ?>">

    <div>
        <label for="tipo">Tipo de animal</label>
        <input type="text" value="<?php echo $tipo; ?>" name="tipo" id="tipo" maxlength="50">
    </div>
    <div>
        <label for="nombre">Nombre del animal</label>
        <input type="text" value="<?php echo $nom_cli; ?>" name="nombre" id="nombre" maxlength="50">
    </div>
    <div>
        <label for="edad">Edad del animal (años)</label>
        <input type="number" value="<?php echo $edad; ?>" name="edad" id="edad" min="1" max="999">
    </div>
    <div>
        <label for="duenio">Dueño</label>
        <select name="duenio" id="duenio">
            <option value="<?php echo $dni; ?>"><?php echo $nom_due; ?></option>
            <?php
            while ($fila_duenios=$datos_duenios->fetch_array(MYSQLI_ASSOC)){
                echo "<option value='$fila_duenios[dni]'>$fila_duenios[nombre]</option>";
            }
            ?>
        </select>
    </div>
    <div>
    <label for="foto_nueva">Subir nueva foto</label>
        <input type="file" name="foto_nueva" id="foto_nueva" lang="es">
    </div>
    <input type="submit" value="Actualizar" name="enviar" id="enviar">
    <input type="reset" value="Volver por defecto" name="borrar" id="borrar">
    </form>

    <?php

        }else{
            echo "<div id='acceso_denegado'>
                    <h1>Error. Este cliente no existe</h1>
                    <div>
                        <img src='../../assets/imagenes/cargando.gif' alt='cargando'>
                    </div>
                </div>";
            echo "<meta http-equiv='refresh' content='2; url=clientes.php'>";
        }   

        if(isset($_POST['enviar'])){

            $consulta_insercion="UPDATE cliente SET tipo=?, nombre=?, edad=?, foto=?, dni_dueño=? WHERE id=?";
            $resultado_insercion=$conexion->prepare($consulta_insercion);
        
            //SI LOS CAMPOS LOS DEJA VACIOS O NO CUMPLEN CON LOS REQUISITOS LE DEJO LO QUE TUVIESE ANTES DE MODIFICAR
            if(trim($_POST['tipo'])!="" && strlen(trim($_POST['tipo']))<=50){
                $tipo=trim($_POST['tipo']);
            }

            if(trim($_POST['nombre'])!="" && strlen(trim($_POST['nombre']))<=50){
                $nom_cli=trim($_POST['nombre']);
            }

            if(trim($_POST['edad'])!="" && is_numeric(trim($_POST['edad']))){
                $edad=trim($_POST['edad']);
            }
            
            if(trim($_POST['duenio'])!=""){
                $dni=trim($_POST['duenio']);
            }         
        
            //SI HA INSERTADO UNA IMAGEN VÁLIDA SE COJE ESA. SINO SE LE DEJA LA QUE TENIA
            if($_FILES['foto_nueva']['tmp_name']!=""){

                $extension_imagen=extension_imagen($_FILES['foto_nueva']['type']);
                if($extension_imagen===''){
                    $foto_final=$foto_antigua;
                }else{
                    
                    //BORRO LA QUE YA TENÍA
                    unlink("../../assets/imagenes/clientes/$foto_antigua");

                    //COPIO LA IMAGEN CON NAME "FOTO_NUEVA"
                    $nombre_temporal_imagen=$_FILES['foto_nueva']['tmp_name'];
                    $nombre_imagen="$id".$extension_imagen;
                    move_uploaded_file($nombre_temporal_imagen,"../../assets/imagenes/clientes/$nombre_imagen");
                    $foto_final=$nombre_imagen;
                }
            }else{
                $foto_final=$foto_antigua;
            }

            $resultado_insercion->bind_param("ssissi", $tipo, $nom_cli, $edad, $foto_final, $dni, $id);  
            $resultado_insercion->execute();
            $resultado_insercion->close();
            echo "<meta http-equiv='refresh' content='0; url=clientes.php'>";   
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
