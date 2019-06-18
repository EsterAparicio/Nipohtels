<?php session_start();
$db = mysqli_connect("localhost", "root", "", "hoteles") or die("Error connecting to database: ".mysqli_error());
$errors = array();?>

<!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirm reservation - Nipohtels</title>
    <meta name="description" content="Free Bootstrap Theme by uicookies.com">
    <meta name="keywords" content="free website templates, free bootstrap themes, free template, free bootstrap, free website template">
    
    <link href="https://fonts.googleapis.com/css?family=Crimson+Text:300,400,700|Rubik:300,400,700,900" rel="stylesheet">
    <link rel="stylesheet" href="css/styles-merged.css">
    <link rel="stylesheet" href="css/style.min.css">
    <link rel="stylesheet" href="css/custom.css">

    <!--[if lt IE 9]>
      <script src="js/vendor/html5shiv.min.js"></script>
      <script src="js/vendor/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

  <!-- START: header -->

  <header role="banner" class="probootstrap-header">
    <!-- <div class="container"> -->
        <div class="row">
        <a href="index.php" class="probootstrap-logo visible-xs"><img src="img/logo_sm.png" class="hires" width="120" height="33" alt="Nipohtels logo"></a>
        
        <a href="#" class="probootstrap-burger-menu visible-xs"><i>Menu</i></a>
        <div class="mobile-menu-overlay"></div>

        <nav role="navigation" class="probootstrap-nav hidden-xs">

          <?php if(!isset($_SESSION['login_user'])){ ?>
          <ul class="probootstrap-main-nav">
            <li class="active"><a href="index.html">Home</a></li>
            <li><a href="about.php">About us</a></li>
            <li class="hidden-xs probootstrap-logo-center"><a href="index.php"><img src="img/logo_md.png" class="hires" width="181" height="50" alt="Nipohtels logo"></a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href='' data-toggle="modal" data-target="#modalLoginForm">Login</a></li>
            <li><a href="" data-toggle="modal" data-target="#modalRegisterForm">Register</a>
          </ul>
          <?php } ?>

          <?php if(isset($_SESSION['login_user'])){ ?>
          <ul class="probootstrap-main-nav">
            <li>Welcome <?php echo $_SESSION['name']?><li>
            <li class="active"><a href="index.php">Home</a></li>
            <li><a href="about.php">About us</a></li>
            <li class="hidden-xs probootstrap-logo-center"><a href="index.php"><img src="img/logo_md.png" class="hires" width="181" height="50" alt="Nipohtels logo"></a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href='userpage.php'>My account</a></li>
            <li><a href='logout.php'>Log out</a></li>
          </ul>
          <?php } ?>

            <div class="extra-text visible-xs">
            <a href="#" class="probootstrap-burger-menu"><i>Menu</i></a>
            <h5>Connect With Us</h5>
            <ul class="social-buttons">
              <li><a href="#"><i class="icon-twitter"></i></a></li>
              <li><a href="#"><i class="icon-facebook2"></i></a></li>
              <li><a href="#"><i class="icon-instagram2"></i></a></li>
            </ul>

          </div>
        </nav>
        </div>
    <!-- </div> -->
  </header>
  <!-- END: header -->

  <section class="probootstrap-slider flexslider probootstrap-inner">
    <ul class="slides">
       <li style="background-image: url(img/slider_8.jpg);" class="overlay">
          <div class="container">
            <div class="row">
              <div class="col-md-10 col-md-offset-1">
                <div class="probootstrap-slider-text text-center">
                  <p><img src="img/curve_white.svg" class="seperator probootstrap-animate" alt="Free HTML5 Bootstrap Template"></p>
                  <h1 class="probootstrap-heading probootstrap-animate">Confirm reservation</h1>
                  <div class="probootstrap-animate probootstrap-sub-wrap">All journeys have secret destinations of which the traveler is unaware.</div>
                </div>
              </div>
            </div>
          </div>
        </li>
    </ul>
  </section>
  
  <section class="probootstrap-section">
    <div class="container">
      <div class="row probootstrap-gutter40">
        <div class="col-md-20">
          <?php
    if((!isset($_SESSION['id_reserva']) AND isset($_SESSION['login_user'])) OR (isset($_SESSION['id_reserva']) AND isset($_POST['submitNew']))){
      $db = mysqli_connect("localhost", "root", "", "hoteles") or die("Error connecting to database: ".mysqli_error());
      $varhab = $_POST['habreserva'];
      $varinicio = $_POST['tripStart'];
      $varfin = $_POST['tripEnd'];
      $varpension = $_POST['pension'];
      $varuser = $_SESSION['login_user'];
      mysqli_query($db, "UPDATE habitaciones SET tipo_pensiones_id_pension = '$varpension' WHERE id_habitacion = '$varhab';") or die(mysqli_error($db));
      $querycheck = mysqli_query($db,"SELECT habitaciones_id_habitacion FROM habitaciones_reservas;");
      while($rowcheck = mysqli_fetch_assoc($querycheck)){
              $checkarray[] = $rowcheck;
          }

      //Calcula el número de días que dura la reserva
      $dateinicio = new DateTime($varinicio);
      $dateend = new DateTime($varfin);
      $datediffdays = $dateend->diff($dateinicio)->format("%a");
    

      if(in_array($varhab, $checkarray)){

      $sqlprecio = "UPDATE habitaciones_reservas SET precio_final = (SELECT ((tipos_alojamientos.precio * hoteles.precio_multi) + tipopensiones_hoteles.precio_final_pension) * $datediffdays
      FROM tipos_alojamientos INNER JOIN hoteles INNER JOIN tipopensiones_hoteles INNER JOIN habitaciones
      WHERE habitaciones_reservas.habitaciones_id_habitacion = habitaciones.id_habitacion
      AND habitaciones.tipo_alojamiento_id_alojamiento = tipos_alojamientos.id_tipo_alojamiento
      AND habitaciones.hoteles_id_hotel = tipopensiones_hoteles.hoteles_id_hotel
      AND habitaciones.tipo_pensiones_id_pension = tipopensiones_hoteles.tipo_pensiones_id_pension LIMIT 1)
      WHERE habitaciones_id_habitacion = '$varhab';";
      $queryprecio = mysqli_query($db, $sqlprecio) or die(mysqli_error($db));
      }
      else if(!in_array($varhab, $checkarray)){
        $sqlprecio = mysqli_query($db,"INSERT INTO `habitaciones_reservas` (`habitaciones_id_habitacion`, `reservas_id_reserva`, `precio_final`) VALUES ('$varhab', '1', '');");
        $sqlprecioupdate = "UPDATE habitaciones_reservas SET precio_final = (SELECT ((tipos_alojamientos.precio * hoteles.precio_multi) + tipopensiones_hoteles.precio_final_pension) * $datediffdays
      FROM tipos_alojamientos INNER JOIN hoteles INNER JOIN tipopensiones_hoteles INNER JOIN habitaciones
      WHERE habitaciones_reservas.habitaciones_id_habitacion = habitaciones.id_habitacion
      AND habitaciones.tipo_alojamiento_id_alojamiento = tipos_alojamientos.id_tipo_alojamiento
      AND habitaciones.hoteles_id_hotel = tipopensiones_hoteles.hoteles_id_hotel
      AND habitaciones.tipo_pensiones_id_pension = tipopensiones_hoteles.tipo_pensiones_id_pension LIMIT 1)
      WHERE habitaciones_id_habitacion = '$varhab';";
      $queryprecio = mysqli_query($db, $sqlprecioupdate) or die(mysqli_error($db));
      }


      $price = mysqli_query($db, "SELECT precio_final FROM habitaciones_reservas WHERE habitaciones_id_habitacion = '$varhab';");
      $number = mysqli_query($db, "SELECT num_habitacion FROM habitaciones WHERE id_habitacion = '$varhab';");
      $pension = mysqli_query($db, "SELECT descripcion FROM tipo_pensiones WHERE id_pension = '$varpension';");
      $row = mysqli_fetch_array($price);
      $row2 = mysqli_fetch_array($number);
      $row3 = mysqli_fetch_array($pension);
      $precio = $row['precio_final'];
      $hab = $row2['num_habitacion'];
      $descripcion = $row3['descripcion'];
      ?>
      <section class="table-responsive">
        <table class="user-table-reservations">
          <?php
          echo "<tr><th>Room</th><th>Pension</th><th>Price</th></tr>";
                echo "<tr>";
                echo "<td>".$hab." </td>";             
                echo "<td>".utf8_encode($descripcion)."</td>";
                echo "<td>".$precio." €</td>";
                echo "</tr>";
                echo "</table>";
      ?>
      <div class="row-md-12 align-items-center">
        <div class="col-md-1"></div>
        <div class="col-md-9 text-center">
      <h4>Confirm reservation?</h4>
      <h5>You can add more rooms after confirming the reservation.</h5>
      <form action="confirmreservation.php" method="POST">
        <button type="submit" name="confirmar" class='btn btn-primary'>Confirm</button>
        <input type="hidden" name="habreserva" value="<?php echo $varhab?>">
        <input type="hidden" name="tripStart" value="<?php echo $varinicio ?>">
        <input type="hidden" name="tripEnd" value="<?php echo $varfin ?>">
        <input type="hidden" name="pension" value="<?php echo $varpension ?>">
        <input type="hidden" name="precio" value="<?php echo $precio ?>">
      </form>
    </div>
    </div>
    </section>

    <?php
}
else if(isset($_SESSION['id_reserva']) AND isset($_POST['submitAdd'])){

    $db = mysqli_connect("localhost", "root", "", "hoteles") or die("Error connecting to database: ".mysqli_error());
    $varhab = $_POST['habreserva'];
    $varinicio = $_POST['tripStart'];
    $varfin = $_POST['tripEnd'];
    $varpension = $_POST['pension'];
    $varuser = $_SESSION['login_user'];
    mysqli_query($db, "UPDATE habitaciones SET tipo_pensiones_id_pension = '$varpension' WHERE id_habitacion = '$varhab';") or die(mysqli_error($db));

    //Calcula el número de días que dura la reserva
    $dateinicio = new DateTime($varinicio);
    $dateend = new DateTime($varfin);
    $datediffdays = $dateend->diff($dateinicio)->format("%a");

         if(in_array($varhab, $checkarray)){

      $sqlprecio = "UPDATE habitaciones_reservas SET precio_final = (SELECT ((tipos_alojamientos.precio * hoteles.precio_multi) + tipopensiones_hoteles.precio_final_pension) * $datediffdays
      FROM tipos_alojamientos INNER JOIN hoteles INNER JOIN tipopensiones_hoteles INNER JOIN habitaciones
      WHERE habitaciones_reservas.habitaciones_id_habitacion = habitaciones.id_habitacion
      AND habitaciones.tipo_alojamiento_id_alojamiento = tipos_alojamientos.id_tipo_alojamiento
      AND habitaciones.hoteles_id_hotel = tipopensiones_hoteles.hoteles_id_hotel
      AND habitaciones.tipo_pensiones_id_pension = tipopensiones_hoteles.tipo_pensiones_id_pension LIMIT 1)
      WHERE habitaciones_id_habitacion = '$varhab';";
      $queryprecio = mysqli_query($db, $sqlprecio) or die(mysqli_error($db));
      }
      else if(!in_array($varhab, $checkarray)){
        $sqlprecio = mysqli_query($db,"INSERT INTO `habitaciones_reservas` (`habitaciones_id_habitacion`, `reservas_id_reserva`, `precio_final`) VALUES ('$varhab', '1', '');");
        $sqlprecioupdate = "UPDATE habitaciones_reservas SET precio_final = (SELECT ((tipos_alojamientos.precio * hoteles.precio_multi) + tipopensiones_hoteles.precio_final_pension) * $datediffdays
      FROM tipos_alojamientos INNER JOIN hoteles INNER JOIN tipopensiones_hoteles INNER JOIN habitaciones
      WHERE habitaciones_reservas.habitaciones_id_habitacion = habitaciones.id_habitacion
      AND habitaciones.tipo_alojamiento_id_alojamiento = tipos_alojamientos.id_tipo_alojamiento
      AND habitaciones.hoteles_id_hotel = tipopensiones_hoteles.hoteles_id_hotel
      AND habitaciones.tipo_pensiones_id_pension = tipopensiones_hoteles.tipo_pensiones_id_pension LIMIT 1)
      WHERE habitaciones_id_habitacion = '$varhab';";
      $queryprecio = mysqli_query($db, $sqlprecioupdate) or die(mysqli_error($db));
      }

    $price = mysqli_query($db, "SELECT precio_final FROM habitaciones_reservas WHERE habitaciones_id_habitacion = '$varhab';");
    $number = mysqli_query($db, "SELECT num_habitacion FROM habitaciones WHERE id_habitacion = '$varhab';");
    $pension = mysqli_query($db, "SELECT descripcion FROM tipo_pensiones WHERE id_pension = '$varpension';");
    $row = mysqli_fetch_array($price);
    $row2 = mysqli_fetch_array($number);
    $row3 = mysqli_fetch_array($pension);
    $precio = $row['precio_final'];
    $hab = $row2['num_habitacion'];
    $descripcion = $row3['descripcion'];

    echo "Habitacion: ".$hab." ";
    echo "Pensión: ".utf8_encode($descripcion)." ";
    echo "Precio: ".$precio;
    ?>
    <h4>Add room to the current reservation?</h4>
    <form action="confirmreservation.php" method="POST">
        <button type="submit" name="add" class='btn btn-primary'>Add</button>
        <input type="hidden" name="habreserva" value="<?php echo $varhab?>">
        <input type="hidden" name="tripStart" value="<?php echo $varinicio ?>">
        <input type="hidden" name="tripEnd" value="<?php echo $varfin ?>">
        <input type="hidden" name="pension" value="<?php echo $varpension ?>">
        <input type="hidden" name="precio" value="<?php echo $precio ?>">
      </form>

<?php } 
if (isset($_POST['confirmar'])) {
    $db = mysqli_connect("localhost", "root", "", "hoteles") or die("Error connecting to database: ".mysqli_error());
    $varhab = $_POST['habreserva'];
    $varinicio = $_POST['tripStart'];
    $varfin = $_POST['tripEnd'];
    $varpension = $_POST['pension'];
    $varuser = $_SESSION['login_user'];
    $precio = $_POST['precio'];
    //$hotelname = $_POST['hotelname'];
        //Crear la reserva
    $reservasql = "INSERT INTO reservas (`fecha_reserva_inicio`,`fecha_reserva_fin`,`usuarios_correo_usuario`,`precio_final_reserva`) VALUES ('$varinicio', '$varfin', '$varuser', '$precio');";
    $reservasquery = mysqli_query($db, $reservasql) or die(mysqli_error($db));

        //Conseguir la id de la reserva para modificarla más tarde
    $reservaidsql = "SELECT id_reserva FROM reservas WHERE fecha_reserva_inicio = '$varinicio' AND usuarios_correo_usuario = '$varuser';";
    $reservaidquery = mysqli_query($db, $reservaidsql) or die(mysqli_error($db));
    $rowreservaid = mysqli_fetch_array($reservaidquery);
    $reservaid = $rowreservaid['id_reserva'];
    $_SESSION['id_reserva'] = $reservaid;

        //Reservar habitación
    $roomsql = "INSERT INTO habitaciones_reservas VALUES ('$varhab', '$reservaid', '$precio');";
    $roomquery = mysqli_query($db, $roomsql) or die(mysqli_error($db));

    echo "Reservation made successfully!";

    //EMAIL DE CONFIRMACIÓN DE RESERVA AL USUARIO
    /*
    $to = $_SESSION['login_user'];
    $subject = "Reserve confirmation - Nipohtels";
    $from = "administradornipohtels@gmail.com";

    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= 'From: '.$from."\r\n".
    'Reply-To: '.$from."\r\n" .
    'X-Mailer: PHP/' . phpversion();

    $message = '<html><body>';
    $message .= '<h1>Dear <?php echo $_SESSION["name"]?></h1>';
    $message .= '<p>You have made a reservation at Nipohtels.</p>';
    $message .= '<p>From: <?php echo $varinicio ?> To: <?php echo $varfin ?></p>';
    $message .= '<p>In our hotel <?php echo $hotelname ?></p>';
    $message .= '<p>You can see your reservation from your account in our website.</p>';
    $message .= '<p>Enjoy the trip!</p>';
    $message .= '</body></html>';

    if(mail($to, $subject, $message, $headers)){
    echo 'We have sent a confirmation e-mail to your mail.';
    } else{
    echo 'There was a problem sending the confirmation e-mail.';
    }*/

    // FIN DEL EMAIL 
    //header('Refresh: 5; URL= index.php');
}
else if (isset($_POST['add'])) {
    $db = mysqli_connect("localhost", "root", "", "hoteles") or die("Error connecting to database: ".mysqli_error());
    $varhab = $_POST['habreserva'];
    $varinicio = $_POST['tripStart'];
    $varfin = $_POST['tripEnd'];
    $varpension = $_POST['pension'];
    $varuser = $_SESSION['login_user'];
    $precio = $_POST['precio'];
    $id_reserva = $_SESSION['id_reserva'];

    //Añadir habitación
    $roomsql = "INSERT INTO habitaciones_reservas VALUES ('$varhab', '$id_reserva', '$precio');";
    $roomquery = mysqli_query($db, $roomsql) or die(mysqli_error($db));

    $reservasql = "SELECT precio_final_reserva FROM reservas WHERE id_reserva = $id_reserva;";
    $pricequery = mysqli_query($db, $reservasql);
    $row = mysqli_fetch_row($pricequery);
    $precio = $precio + $row[0];

    $updatereserva = mysqli_query($db, "UPDATE reservas SET precio_final_reserva = $precio WHERE id_reserva = $id_reserva;");
    echo "Reservation updated! <br>";
    echo "The new price of your reservation is: ".$precio."€";
    header('Refresh: 5; URL= index.php');
} 
elseif (!isset($_SESSION['login_user'])) {
    ?>
        <div class="col-md-12 text-center">
          <h2>Oops! It is necessary to be registered to make reservations.</h2>
          <h1>Are you traveling with us?</h1>
          <h4><a href="" data-toggle="modal" data-target="#modalRegisterForm">Sign up here!</a><br></h4>
          <small>Or log-in <a href="" data-toggle="modal" data-target="#modalLoginForm">here!</a></small>
        </div>
    <?php
} ?>
        </div>
      </div>
    </div>
  </section>

<section class="probootstrap-half">
    <div class="image" style="background-image: url(img/slider_2.jpg);"></div>
    <div class="text">
      <div class="probootstrap-animate fadeInUp probootstrap-animated">
        <h2 class="mt0">Travel</h2>
        <p><img src="img/curve_white.svg" class="seperator" alt="Free HTML5 Bootstrap Template"></p>
        <div class="row">
          <div class="col-md-6">
            <p>I should like to rise and go where the golden apples grow;   
            Where below another sky parrot islands anchored lie, and, watched by cockatoos and goats, Lonely Crusoes building boats;
            Where in sunshine reaching out eastern cities, miles about, are with mosque and minaret among sandy gardens set, and the rich goods from near and far hang for sale in the bazaar;   
            Where the Great Wall round China goes, and on one side the desert blows, and with bell and voice and drum cities on the other hum;   
            Where are forests, hot as fire, wide as England, tall as a spire, full of apes and cocoa-nuts and the negro hunters’ huts;
            </p>
          </div>
          <div class="col-md-6">
            <p>Where the knotty crocodile lies and blinks in the Nile, and the red flamingo flies hunting fish before his eyes;   
              Where in jungles, near and far, man-devouring tigers are, lying close and giving ear lest the hunt be drawing near, or a comer-by be seen swinging in a palanquin;
              Where among the desert sands some deserted city stands, all its children, sweep and prince, grown to manhood ages since, not a foot in street or house, not a stir of child or mouse, and when kindly falls the night, in all the town no spark of light.   
              There I’ll come when I’m a man with a camel caravan;
              Light a fire in the gloom of some dusty dining-room;   
              See the pictures on the walls, heroes, fights and festivals;   
              And in a corner find the toys of the old Egyptian boys.
              </p>    
          </div>
        </div>
        <h6><i>- Robert Louis Stevenson</i></h6>
      </div>
    </div>
  </section>

  <!-- START: footer -->
  <footer role="contentinfo" class="probootstrap-footer">
    <div class="container">
      <div class="row">
        <div class="col-md-4">
          <div class="probootstrap-footer-widget">
            <p class="mt40"><img src="img/logo_sm.png" class="hires" width="120" height="33" alt="Free HTML5 Bootstrap Template by uicookies.com"></p>
            <p>A weekend in the heavenly' bed makes it easier to come back to earth. Or so they say. We, on the contrary, think that a weekend in any of our beds will make it almost impossible to go back to normal life. But, who are we to judge? That's something only you can do.</p>
            <p><a href="about.php" class="link-with-icon">Learn More <i class=" icon-chevron-right"></i></a></p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="probootstrap-footer-widget">
          </div>
        </div>
        <div class="col-md-4">
          <div class="probootstrap-footer-widget">
            <h3>Contact</h3>
            <ul class="probootstrap-contact-info">
              <li><i class="icon-location2"></i> <span>198 West 21th Street, Suite 721 New York NY 10016</span></li>
              <li><i class="icon-mail"></i><span>administradornipohtels@gmail.com</span></li>
              <li><i class="icon-phone2"></i><span>+123 456 7890</span></li>
            </ul>
            
          </div>
        </div>
      </div>
      <div class="row mt40">
        <div class="col-md-12 text-center">
          <ul class="probootstrap-footer-social">
            <li><a href=""><i class="icon-twitter"></i></a></li>
            <li><a href=""><i class="icon-facebook"></i></a></li>
            <li><a href=""><i class="icon-instagram2"></i></a></li>
          </ul>
          <p>
            <small>&copy; 2019 Ester Aparicio Rivero. All Rights Reserved. <br> Designed &amp; Developed by Ester Aparicio Rivero</small>
          </p>
          
        </div>
      </div>
    </div>
  </footer>
  <!-- END: footer -->

  <!-- MODAL FORM LOG-IN -->

  <div class="modal fade" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100 font-weight-bold">Sign in</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="index.php">
        <?php include('errors.php'); ?>
      <div class="modal-body mx-3">
        <div class="md-form mb-5">
          <i class="fas fa-envelope prefix grey-text"></i>
          <input type="email" id="defaultForm-email" class="form-control validate" name="email">
          <label data-error="wrong" data-success="right" for="defaultForm-email">Your email</label>
        </div>

        <div class="md-form mb-4">
          <i class="fas fa-lock prefix grey-text"></i>
          <input type="password" id="defaultForm-pass" class="form-control validate" name="password">
          <label data-error="wrong" data-success="right" for="defaultForm-pass">Your password</label>
        </div>

      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button type="submit" class="btn btn-default" name="login_user">Login</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- END: MODAL FORM LOG-IN -->

<!-- MODAL FORM REGISTER -->

<div class="modal fade" id="modalRegisterForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header text-center">
        <h4 class="modal-title w-100 font-weight-bold">Sign up</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="index.php">
        <?php include('errors.php'); ?>
      <div class="modal-body mx-3">
        <div class="md-form mb-5">
          <i class="fas fa-user prefix grey-text"></i>
          <input type="text" id="orangeForm-name" class="form-control validate" name="name">
          <label data-error="wrong" data-success="right" for="redForm-name">Your name</label>
        </div>
        <div class="md-form mb-5">
          <i class="fas fa-user prefix grey-text"></i>
          <input type="text" id="orangeForm-name" class="form-control validate" name="surname">
          <label data-error="wrong" data-success="right" for="redForm-name">Your surname</label>
        </div>
        <div class="md-form mb-5">
          <i class="fas fa-envelope prefix grey-text"></i>
          <input type="email" id="orangeForm-email" class="form-control validate" name="email">
          <label data-error="wrong" data-success="right" for="redForm-email">Your email</label>
        </div>

        <div class="md-form mb-4">
          <i class="fas fa-lock prefix grey-text"></i>
          <input type="password" id="orangeForm-pass" class="form-control validate" name="password_1">
          <label data-error="wrong" data-success="right" for="redForm-pass">Your password</label>
        </div>
        <div class="md-form mb-4">
          <i class="fas fa-lock prefix grey-text"></i>
          <input type="password" id="orangeForm-pass" class="form-control validate" name="password_2">
          <label data-error="wrong" data-success="right" for="redForm-pass">Repeat your password</label>
        </div>
      </div>
      <div class="modal-footer d-flex justify-content-center">
        <button type="submit" class="btn btn-deep-orange" name="submit_register">Sign up</button>
      </div>
    </form>
    </div>
  </div>
</div>

<!-- END: MODAL FORM REGISTER -->
  
  <script src="js/scripts.min.js"></script>
  <script src="js/main.min.js"></script>
  <script src="js/custom.js"></script>

  </body>
</html>