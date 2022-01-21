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
    <title>Noticias</title>
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

    <section id="noticiasPagina">

    <a id="agregar" href="insertar_noticia.php">Insertar nueva noticia</a>

    <?php  
            //NÚMERO DE NOTICIAS POR PÁGINA  
            $resultados_por_pagina = 4; 

            //NÚMERO TOTAL DE RESULTADOS DE LA BASE DE DATOS
            $consulta_noticia="SELECT id FROM noticia";
            $resultado=$conexion->query($consulta_noticia);        
            $filas_devueltas=$resultado->num_rows;  

            // VEO SI DEVUELVE ALGUNA FILA PARA EMPEZAR A MOSTRAR O POR EL CONTRARIO DIGO QUE NO HAY NINGUNA
            if($filas_devueltas===0){
                echo "<h2>No hay noticias que mostrar</h2>";     
            }else{
                //NUMERO DE PÁGINAS  
                $numero_de_paginas = ceil ($filas_devueltas / $resultados_por_pagina);  

                //VEMOS EN QUE PÁGINA ESTAMOS AHORA MISMO. POR DEFECTO SERÁ LA PRIMERA
                if(!isset($_GET['pagina'])) {  
                    $pagina = 1;  
                }else { 
                    $pagina=trim($_GET['pagina']);

                    //SI ME METEN ALGO QUE NO ES UN NUMERO POR LA URL LA VARIABLE PÁGINA LA IGUALO A 0 PARA DECIR QUE NO HAY NOTICIAS
                    if(!is_numeric($pagina)){
                        $pagina = 0;  
                    }
                } 

                //DETERMINO EL LÍMITE POR ABAJO DONDE EMPIEZA A SACAR RESULTADOS. ES DECIR, SACA DE LA 1 A LA 4, Y LUEGO DE LA 5 A LA 8...El NÚMERO QUE DA SON LOS RESULTADOS QUE SE SALTA ANTES DE MOSTRAR
                $limite = ($pagina-1) * $resultados_por_pagina;           

                //CONSULTA DE LOS RESULTADOS POR PÁGINA   
                $consulta_por_pagina = "SELECT id,titulo,contenido,imagen,fecha_publicacion FROM noticia LIMIT ?,?";  
                $resultado=$conexion->prepare($consulta_por_pagina);
                $resultado->bind_param("ii", $limite, $resultados_por_pagina);
                $resultado->bind_result($id,$titulo,$contenido,$imagen,$fecha_publicacion);
                $resultado->execute();
                $resultado->store_result();
                
                $filas_devueltas=$resultado->num_rows;  

                // VEO SI DEVUELVE ALGUNA FILA PARA EMPEZAR A MOSTRAR O POR EL CONTRARIO DIGO QUE NO HAY NINGUNA
                if($filas_devueltas===0){
                    echo "<h2>No hay noticias que mostrar</h2>";          
                }else{
                    //MUESTRO LOS RESULTADOS 
                   echo "<div id='lista_noticias'>"; 
                    while($resultado->fetch()){

                        //FORMATEMOS LA FECHA PARA QUE SEA MÁS ENTENDIBLE
                        $fecha_formateada=formatearFecha($fecha_publicacion);

                        // SI EL CONTENIDO TIENE MAS DE 20 PALABRAS LO ACORTO. SINO LO MUESTRO TAL CUAL
                        if (str_word_count($contenido, 0) > 20) {
                            $texto = acortarPalabras($contenido);

                            echo "<article class='noticia'>
                                    <div>
                                        <img src='../../assets/imagenes/noticias/$imagen' alt='noticia'>
                                    </div>
                                    <h3>$titulo</h3>
                                    <span>$fecha_formateada</span>
                                    <p>$texto</p>
                                    <form action='noticia.php' method='POST'>
                                        <input type='hidden' name='id' value='$id'>
                                        <input type='submit' value='Ver noticia completa'>
                                    </form>
                                </article>";
                        }else{
                            echo "<article class='noticia'>
                                    <div>
                                        <img src='../../assets/imagenes/noticias/$imagen' alt='noticia'>
                                    </div>
                                    <h3>$titulo</h3>
                                    <span>$fecha_formateada</span>
                                    <p>$contenido</p>                      
                                    <form action='noticia.php' method='POST'>
                                        <input type='hidden' name='id' value='$id'>
                                        <input type='submit' value='Ver noticia completa'>
                                    </form>
                                </article>";
                        }
                    }
                    
                    echo "</div>"; 

                    //ENLACES DE LA PAGINACIÓN  
                    echo "<div id='paginacion'>";
                    for($pagina = 1; $pagina<= $numero_de_paginas; $pagina++) {  
                        echo "<a href='noticias.php?pagina=$pagina'>$pagina</a>"; 
                    }
                    echo "</div>";
                }
                $resultado->close();            
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