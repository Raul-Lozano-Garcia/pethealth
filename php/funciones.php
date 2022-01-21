<?php
    function conectarServidor(){
        $conexion=new mysqli("localhost","root","","veterinaria");
        $conexion->set_charset("utf8");  
        return $conexion;        
    }

    function formatearFecha($fecha){
        $timestamp=strtotime($fecha);
        $fechaFormateada=date('d/m/Y',$timestamp);
        return $fechaFormateada;
    }

    function formatearHora($hora){
        $timestamp=strtotime($hora);
        $horaFormateada=date('H:i',$timestamp);
        return $horaFormateada;
    }

    function extension_imagen($tipo_imagen){
        $extension="";
        switch($tipo_imagen){
            case "image/jpeg": $extension=".jpg";
            break;
            case "image/png": $extension=".png";
            break;
        }
        return $extension;
    }

    function acortarPalabras($frase){
        $words = str_word_count($frase, 2);
        $pos = array_keys($words);
        $texto = substr($frase, 0, $pos[20]) . '...';
        return $texto;
    }

    function siguienteId($tabla){
        $conexion=conectarServidor();

        $consulta_id="SELECT AUTO_INCREMENT FROM information_schema.TABLES where TABLE_SCHEMA='veterinaria' and TABLE_NAME='$tabla'"; 
        $datos_id=$conexion->query($consulta_id);
        $id_siguiente=$datos_id->fetch_array(MYSQLI_ASSOC);
        $resultado=$id_siguiente['AUTO_INCREMENT'];
        return $resultado;

        $conexion->close();
    }

    function crearHeader($ruta){
        echo "<header>
                    <a href='$ruta/index.php' id='logo'>
                        <div>
                            <img src='$ruta/assets/imagenes/logo_blanco.png'>
                        </div>
                        <span>PetHealth</span>
                    </a>         
                <nav>
                    <ul>
                        <li><a href='$ruta/index.php'>Inicio</a></li>";

                        if(isset($_SESSION['dni']) && $_SESSION['dni']==="000000000"){ 

                            echo "<li><a href='$ruta/paginas/duenios/duenios.php'>Dueños</a></li>
                            <li><a href='$ruta/paginas/clientes/clientes.php'>Clientes</a></li>
                            <li><a href='$ruta/paginas/testimonios/testimonios.php'>Testimonios</a></li>
                            <li><a href='$ruta/paginas/noticias/noticias.php'>Noticias</a></li>
                            <li><a href='$ruta/paginas/citas/citas.php'>Citas</a></li>";


                        //LAS PAGINAS QUE CARGA SI ES UN USUARIO NORMAL SON LAS MISMAS PERO CAMBIANDO EL CONTENIDO
                        }else if(isset($_SESSION['dni']) && $_SESSION['dni']!=="000000000"){
                            echo "<li><a href='$ruta/paginas/clientes/clientes.php'>Mis mascotas</a></li>
                            <li><a href='$ruta/paginas/duenios/duenios.php'>Mis datos</a></li>
                            <li><a href='$ruta/paginas/citas/citas.php'>Mis citas</a></li>";
                        }

                        echo "<li><a href='$ruta/paginas/productos/productos.php'>Productos</a></li>
                        <li><a href='$ruta/paginas/servicios/servicios.php'>Servicios</a></li>";

                        if(isset($_SESSION['dni'])){
                            echo "<li><a href='$ruta/index.php?cerrar_sesion'>Salir</a></li>";
                        }else{
                            echo "<li><a href='$ruta/paginas/acceder/acceder.php'>Acceder</a></li>";
                        }

                    echo "</ul>
                </nav>
                <button id='hamburguesa' aria-label='boton menu hamburguesa'>
                    <span class='hamburger'></span>
                </button>
            </header>";
    }

    function crearFooter($ruta){
        echo "<footer>
                    <div>
                        <div>
                            <img src='$ruta/assets/imagenes/logo_blanco.png' alt='logo'>
                        </div> 
                        <ul>
                            <li>C/Falsa nº12</li>
                            <li>Granada, España</li>
                            <li>958 123 456</li>
                        </ul>
                        <ul>
                            <li>De Lunes a Viernes:<br>10:00 a 13:30 y de 17:00 a 20:30</li>
                            <li>Sábados:<br>10:30 a 13:00</li>
                            <li>A partir de las 21:00 se considera horario de Urgencias.</li>
                        </ul>
                        <ul>
                            <li><a href='#'><i class='fab fa-facebook-f'></i></a></li>
                            <li><a href='#'><i class='fab fa-instagram'></i></a></li>
                            <li><a href='#'><i class='fab fa-twitter'></i></a></li>
                        </ul>
                    </div>
                    <ul>
                        <li><a href='#'>FAQ</a></li>
                        <li><a href='#'>Política de cookies</a></li>
                        <li><a href='#'>Política de privacidad</a></li>
                        <li><a href='#'>Términos del servicio</a></li>
                    </ul>
                    <small>&copy; Copyright 2021, PetHealth</small>
                </footer>";
    }
?>