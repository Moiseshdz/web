<?php session_start();?>
<!doctype html>
<html lang="es">
  <head>
    <title>Mandaditos</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="css/main.css" rel="stylesheet" title="main">
    <script src="https://use.fontawesome.com/6cf334f329.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="Mandaditos">
    <link rel="apple-touch-icon" href="src/log.jpg">
  </head>
  <body>
  <div class="form-modal">
  <?php  
if (isset($_POST['Btnlogin'])){

    
      if (isset($_SESSION['user_id'])) {
        header('Location:');
      }
      require 'conn/database.php';
    
      if (!empty($_POST['nombreuser']) && !empty($_POST['password'])) {
        $records = $conn->prepare('SELECT * FROM users WHERE nombreuser = :nombreuser');
        $records->bindParam(':nombreuser', $_POST['nombreuser']);
        $records->execute();
        $results = $records->fetch(PDO::FETCH_ASSOC);
       

        if (($results) > 0 && password_verify($_POST['password'], $results['password'])) {
          $_SESSION['user_id'] = $results['id'];
         
          header("Location:sesion/perfil.php?=".$_SESSION['user_id']);
    
        } else {
            echo "
            <center>
                <div class='notificacion'>
                     <span class='closebtn' onclick='this.parentElement.style.display='none';'></span> 
                     <strong>Ups! </strong>Credenciales no validas!! 
                </div>
            </center> <p></p>";
          //header("refresh:1.5; url=index.php");

    if(!empty($message)): 
     endif; 
    
    
         }
      }

}

?>
    <div class="form-toggle">
        <button id="login-toggle" onclick="toggleLogin()">Sesión</button>
        <button id="signup-toggle" onclick="toggleSignup()">Registrar</button>
    </div>

<center>
    <img src="src/logo.jpg" class="logo">
</center>

    <div id="login-form">
        <form method="POST" action="index.php">
            <input  type="username" name="nombreuser" placeholder="Usuario.."/>
            <input  type="password" name="password"   placeholder="Contraseña.."/>
            <button type="submit"   name="Btnlogin" class="btn login">Aceptar</button>
            <p><a href="javascript:void(0)">¿Olvidaste tu contraseña?</a></p>
            <hr/>
        
        </form>
    </div>

    <div id="signup-form">
        <form method="POST" action="index.php" enctype="multipart/form-data">
            <input type="lname"    name="nombre"    placeholder="Nombre.."    required/>
            <input type="fname"    name="apellido"  placeholder="Apellido.."  required/>
            <input type="email"    name="correo"    placeholder="Correo.."    required/>
            <input type="username" name="nombreuser"placeholder="Usuario.."   required/>
            <input type="password" name="password"  placeholder="Contraseña"  required/>
            <textarea              name="direccion" placeholder="Direccion.." required></textarea>
            <input type="number"   name="telefono"  placeholder="Telefono"  required/>
            <label>Foto de perfil</label>
            <input type="file"     name="avatar"    required accept="image/*"/>
            <button type="submit" class="btn signup" name="btnR">Crear</button>
            <p>Hacer clic en <strong>crear</strong> significa que está de acuerdo con nuestros <a href="javascript:void(0)">términos de servicios</a>.</p>
            <hr/>
           
        
        </form>
        
    </div>

    <?php  
include "conn/conn.php";
date_default_timezone_set('America/Mexico_City');
if(isset($_POST['btnR'])){
 
    $nombre     = $_POST['nombre'];
    $apellido   = $_POST['apellido'];
    $correo     = $_POST['correo'];
    $nombreuser = $_POST['nombreuser'];
    $password   = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $direccion  = $_POST['direccion'];
    $telefono   = $_POST['telefono'];
    $avatar     = $_FILES['avatar']['name'];
    $ruta       = $_FILES['avatar']['tmp_name'];
    $destino    = "avatar/".$avatar;
    copy($ruta,$destino);

   $duplicado = "SELECT * FROM users WHERE nombre = '$nombre' OR apellido = '$apellido' OR correo = '$correo' 
   OR nombreuser='$nombreuser' OR telefono='$telefono' ";
   $resultado = mysqli_query($conn,$duplicado);
   if(mysqli_num_rows($resultado) > 0){

       echo "
    <center>
     <div class='notificacion'>
          <span class='closebtn' onclick='this.parentElement.style.display='none';'></span> 
          <strong>Ups! </strong>Este usuario ya existe!! 
     </div>
    </center>";
      
    header("refresh:1.5; url=index.php");
    
   }else{
    $mysqli = "INSERT INTO users (nombre,apellido,correo,nombreuser,password,direccion,telefono,avatar) 
    VALUES('$nombre','$apellido','$correo','$nombreuser','$password','$direccion','$telefono','$destino')";
    if(mysqli_query($conn,$mysqli)){

        echo "
    <center>
     <div class='notificacionBien'>
          <span class='closebtn' onclick='this.parentElement.style.display='none';'></span> 
          <strong>Genial! </strong>Cuenta creada con éxito!! 
     </div>
    </center>
        ";
        
    }else{
        echo "
    <center>
        <div class='notificacion'>
             <span class='closebtn' onclick='this.parentElement.style.display='none';'></span> 
             <strong>Ups! </strong>Este usuario no se pudo registrar!! 
        </div>
    </center>";
    header("refresh:1.5; url=index.php");

    }
    mysqli_close($conn);
 }
}
?>
</div>



<script src="js/main.js"></script>
  </body>
</html>