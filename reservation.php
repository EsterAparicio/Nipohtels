<?php session_start();
$db = mysqli_connect("localhost", "root", "", "hoteles") or die("Error connecting to database: ".mysqli_error());
$errors = array();?>

<!DOCTYPE html>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Available rooms</title>
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
                  <h1 class="probootstrap-heading probootstrap-animate">Book A Room</h1>
                  <div class="probootstrap-animate probootstrap-sub-wrap">Traveling allows you to become so many different versions of yourself.</div>
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
          //Recogemos los datos con los que vamos buscando.
    $querycity = $_POST['searchcity'];
    $queryfecha1 = $_POST['searchdatestart'];
    $queryfecha2 = $_POST['searchdateend'];
    $queryoccupants = $_POST['occupants'];
    $hotelname = "";
    $reservaarray = array();
    $roomarray = array();
      //Esta línea cambia los caracteres especiales de HTML por su código correcpondiente.
        $querycity = htmlspecialchars($querycity);

        //Conseguir las reservas en esas fechas [FUNCIONA, NO TOCAR]
        $queryreserva = mysqli_query($db, "SELECT id_reserva FROM reservas WHERE fecha_reserva_inicio BETWEEN '$queryfecha1' AND '$queryfecha2' OR fecha_reserva_fin BETWEEN '$queryfecha1' AND '$queryfecha2'") or die (mysqli_error($db));
        //$reservastotal = mysqli_fetch_row($queryreserva);
        if(mysqli_num_rows($queryreserva) > '0'){
          while($row2 = mysqli_fetch_assoc($queryreserva)){
              $reservaarray[] = $row2;
          }

          $arr = array_map(function($el){ return $el['id_reserva']; }, $reservaarray);
          //$str = implode('','', $arr);
          $str = "'".implode("','", $arr)."'";
          //print_r($str);

          //Conseguir todas las habitaciones reservadas en esas fechas
          $queryroom = mysqli_query($db, "SELECT habitaciones_id_habitacion FROM habitaciones_reservas WHERE reservas_id_reserva IN ($str);");
          while($row3 = mysqli_fetch_assoc($queryroom)){
              $roomarray[] = $row3;
          }

          $arrroom = array_map(function($el){ return $el['habitaciones_id_habitacion']; }, $roomarray);
          //$str = implode('','', $arr);
          $strroom = "'".implode("','", $arrroom)."'";
        
        }else if (mysqli_num_rows($queryreserva) == '0') {
          $strroom = "'0'";
        }


        $raw_results = mysqli_query($db, "SELECT * FROM hoteles
          LEFT JOIN habitaciones ON habitaciones.hoteles_id_hotel = hoteles.id_hotel
      LEFT JOIN habitaciones_reservas ON habitaciones_reservas.habitaciones_id_habitacion = habitaciones.id_habitacion
            LEFT JOIN reservas ON reservas.id_reserva = habitaciones_reservas.reservas_id_reserva
      WHERE habitaciones.id_habitacion NOT IN ($strroom) AND hoteles.ciudad = '$querycity' AND habitaciones.tipo_alojamiento_id_alojamiento = '$queryoccupants' GROUP BY habitaciones.id_habitacion;");
         
        if(mysqli_num_rows($raw_results) > 0){
             
            while($results = mysqli_fetch_array($raw_results)){
                $hotelname = $results['nombre'];
                echo "<div class='col-md-5 text-center'>";
                ?>
                  <img class="img-thumbnail" height="300" width="450" src="<?php echo 'img/rooms/'.$results['img_path'];?>"/>
                <?php            
                echo "<p><h3>".$results['nombre']."</h3></p><hr/>";
                echo "<h4> City: </h4>".$results['ciudad']."<hr/><h4>Address: </h4>".$results['ubicacion']."<hr/>";
                echo "<h4> Room: </h4>".$results['num_habitacion']."<hr/>";
                echo "<h4> Description: </h4>".utf8_encode($results['descripcion']);
                //echo "<p>".$results['img_path']."</p>";
                ?>
                <!--<img class="room-img" align="left" height="200" width="300" src="<?php echo 'img/rooms/'.$results['img_path'];?>"/>-->

                <hr/>

                <form name="form" action="confirmreservation.php" method="POST">
                    <select name="pension" id="pension">
                        <option value="1">Nothing included</option> 
                        <option value="2">Breakfast</option> 
                        <option value="3">Half board</option>
                        <option value="4">Full board</option> 
                        <option value="5">All included</option> 
                    </select>
                    <input type="hidden" name="habreserva" value="<?php echo $results['id_habitacion']?>">
                    <input type="hidden" name="tripStart" value="<?php echo $queryfecha1 ?>">
                    <input type="hidden" name="tripEnd" value="<?php echo $queryfecha2 ?>">
                    <input type="hidden" name="hotelname" value="<?php echo $hotelname ?>">
                    <?php 
                    if (!isset($_SESSION['id_reserva'])) {
                        echo "<input class='btn btn-primary' type='submit' name='submit' value='Reserve'>";
                    }
                    else if (isset($_SESSION['id_reserva'])){
                        echo "<input class='btn btn-primary' type='submit' name='submitNew' value='New reservation'>";
                        echo "<input class='btn btn-primary' type='submit' name='submitAdd' value='Add  to current reservation'>";
                    }
                    ?>
                </form>
                <?php
                echo "</div>";
            }
        }
        else{
            echo "No available rooms.";
            header('Refresh: 5; URL=index.php');
        }
?>
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