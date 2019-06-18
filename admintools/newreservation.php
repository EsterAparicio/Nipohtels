<?php session_start();
    $db = mysqli_connect('localhost','root','','hoteles')
      or die('Error connecting to MySQL server.'); 
    $errors = array();
    if (isset($_POST['new_reservation'])) {

                $hotelname = mysqli_real_escape_string($db, $_POST['hotelname']);
                $correo = mysqli_real_escape_string($db, $_POST['correo']);
                $datestart = mysqli_real_escape_string($db, $_POST['datestart']);
                $dateend = mysqli_real_escape_string($db, $_POST['dateend']);
                $number = mysqli_real_escape_string($db, $_POST['roomnumber']);
                $rooms = array();
                $filter_results = array();

                for ($i = 0; $i < $_POST['roomnumber']; $i++) {
                       $rooms[] = $_POST['habitacion'.$i];
                }

                $roomsStr = join("', '", $rooms);

                if (empty($hotelname)) {
                  array_push($errors, "It's necessary to know the name of the hotel to proceed with the reservation.");
                }
                if (empty($correo)) {
                  array_push($errors, "It's necessary to know the user to proceed with the reservation.");
                }
                //Conseguir el id del hotel via nombre
                $queryhotel = "SELECT id_hotel FROM hoteles WHERE nombre = '$hotelname'";
                $resultshotel = mysqli_query($db, $queryhotel) or die(mysqli_error($db));
                $row = mysqli_fetch_row($resultshotel);
                $idhotel = $row[0];

                //Conseguir la id de las reservas que SÍ están hechas en estas fechas
                $queryreservas = "SELECT id_reserva FROM reservas WHERE fecha_reserva_inicio BETWEEN '$datestart' AND '$dateend' AND fecha_reserva_fin BETWEEN '$datestart' AND '$dateend'";

                $resultsreserva = mysqli_query($db, $queryreservas) or die(mysqli_error($db));
                $row2 = mysqli_fetch_row($resultsreserva) ;
                $row2Str = join("', '", $row2);

                //Escoger las habitaciones que NO pertenecen a esas reservas
                $queryhabs = "SELECT habitaciones_id_habitacion FROM habitaciones_reservas WHERE reservas_id_reserva NOT IN ('$row2Str')";
                $resultshabs = mysqli_query($db, $queryhabs) or die(mysqli_error($db));
                $row3 = mysqli_fetch_row($resultshabs);
                $row3Str = join("', '", $row3);

                $raw_results = "SELECT num_habitacion FROM habitaciones WHERE id_habitacion IN ('$row3Str')";
                $results_raw_results = mysqli_query($db, $raw_results) or die(mysqli_error($db));
                $row4 = mysqli_fetch_row($results_raw_results);
                //$row4Str = join("', '", $row4);

                if(mysqli_num_rows($results_raw_results) > 0){
                    $queryfinal = "SELECT id_habitacion FROM habitaciones WHERE hoteles_id_hotel = '$idhotel' AND num_habitacion IN ('$roomsStr')";
                    $resultsfinal = mysqli_query($db, $queryfinal) or die(mysqli_error($db));
                    $rowfinal = mysqli_fetch_row($resultsfinal);
                    $rowfinalStr = join("', '", $rowfinal);
                }

                else if(mysqli_num_rows($results_raw_results) < 0) {
                      array_push($errors, "There are no avaiable rooms between those dates.");
                  }

                  if (count($errors) == 0) {
                    $query = mysqli_query($db, "INSERT INTO reservas (`fecha_reserva_inicio`, `fecha_reserva_fin`, `usuarios_correo_usuario`) VALUES ('$datestart', '$dateend','$correo')")
                      or die(mysqli_error($db));

                      $query2 = mysqli_query($db, "SELECT id_reserva FROM reservas WHERE fecha_reserva_inicio = '$datestart' AND fecha_reserva_fin = '$dateend' AND usuarios_correo_usuario = '$correo'")
                      or die(mysqli_error($db));

                      $rowidreserva = mysqli_fetch_row($query2);

                      $query3 = mysqli_query($db, "INSERT INTO habitaciones_reservas (`habitaciones_id_habitacion`, `reservas_id_reserva`) VALUES (('$rowfinalStr'),'$rowidreserva[0]')")
                      or die(mysqli_error($db));

                    echo "Reservation complete";
                }
            }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Reservation - AdminTools</title>
    <meta name="description" content="Free Bootstrap Theme by uicookies.com">
    <meta name="keywords" content="free website templates, free bootstrap themes, free template, free bootstrap, free website template">
    
    <link href="https://fonts.googleapis.com/css?family=Crimson+Text:300,400,700|Rubik:300,400,700,900" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles-merged.css">
    <link rel="stylesheet" href="../css/style.min.css">
    <link rel="stylesheet" href="../css/custom.css">

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
        <a href="../index.php" class="probootstrap-logo visible-xs"><img src="../img/logo_sm.png" class="hires" width="120" height="33" alt="Nipohtels logo"></a>
        
        <a href="#" class="probootstrap-burger-menu visible-xs"><i>Menu</i></a>
        <div class="mobile-menu-overlay"></div>

        <nav role="navigation" class="probootstrap-nav hidden-xs">

          <?php if(!isset($_SESSION['login_user'])){ ?>
          <ul class="probootstrap-main-nav">
            <li class="active"><a href="index.php">Home</a></li>
            <li><a href="about.html">About us</a></li>
            <li class="hidden-xs probootstrap-logo-center"><a href="index.php"><img src="img/logo_md.png" class="hires" width="181" height="50" alt="Nipohtels logo"></a></li>
            <li><a href="../contact.php">Contact</a></li>
            <li><a href='' data-toggle="modal" data-target="#modalLoginForm">Login</a></li>
            <li><a href="" data-toggle="modal" data-target="#modalRegisterForm">Register</a>
          </ul>
          <?php } ?>

          <?php if(isset($_SESSION['login_user']) && $_SESSION['tipo_usuario'] == '2'){ ?>
          <ul class="probootstrap-main-nav">
            <li>Bienvenidx <?php echo $_SESSION['name']?><li>
            <li class="active"><a href="index.php">Home</a></li>
            <li><a href="about.html">About us</a></li>
            <li class="hidden-xs probootstrap-logo-center"><a href="index.php"><img src="img/logo_md.png" class="hires" width="181" height="50" alt="Nipohtels logo"></a></li>
            <li><a href="../contact.php">Contact</a></li>
            <li><a href='userpage.php'>My account</a></li>
            <li><a href='logout.php'>Log out</a></li>
          </ul>
          <?php } ?>
          <?php if(isset($_SESSION['login_user']) && $_SESSION['tipo_usuario'] == '1'){ ?>
          <ul class="probootstrap-main-nav">
            <li>Bienvenidx <?php echo $_SESSION['name']?><li>
            <li><a href="../index.php">Home</a></li>
            <li><a href="../about.html">About us</a></li>
            <li class="hidden-xs probootstrap-logo-center"><a href="../index.php"><img src="../img/logo_md.png" class="hires" width="181" height="50" alt="Nipohtels logo"></a></li>
            <li><a href="../../contact.php">Contact</a></li>
            <li class="active"><a href='../adminpage.php'>Admin Tools</a></li>
            <li><a href='../logout.php'>Log out</a></li>
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
       <li style="background-image: url(../img/slider_4.jpg);" class="overlay">
          <div class="container">
            <div class="row">
              <div class="col-md-10 col-md-offset-1">
                <div class="probootstrap-slider-text text-center">
                  <p><img src="../img/curve_white.svg" class="seperator probootstrap-animate" alt="Free HTML5 Bootstrap Template"></p>
                  <h1 class="probootstrap-heading probootstrap-animate">About Atlantis</h1>
                  <div class="probootstrap-animate probootstrap-sub-wrap">Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</div>
                </div>
              </div>
            </div>
          </div>
        </li>
    </ul>
  </section>

  <section class="table-responsive">
  <div class="col-md-12 text-center" style="width: 200px; height: 100%; position: relative; left: 850px; margin-top: 50px; margin-bottom: 50px;">
      <form method="POST" action="newreservation.php">
    <?php include('../errors.php') ?>
    <div class="input-group">
        <label>Hotel (Nombre):</label>
        <input type="text" name="hotelname">
      </div>
    <div class="input-group">
        <label>Usuario (Email):</label>
        <input type="text" name="correo">
      </div>
      <div class="input-group">
        <label>Fecha inicio:</label>
      <input type="date" name="datestart" id="datestart" />
      </div>
      <div class="input-group">
        <label>Fecha fin:</label>
        <input type="date" name="dateend" id="dateend" />
      </div>
      <div class="input-group">
        <label>Número de habitaciones a reservar:</label>
        <input type="text" name="roomnumber" id="roomnumber" />
        <a href="#" onclick="addRooms()">Add rooms</a>
      </div>
      <div id="roomselect"/>
  </form>
    </div>
  </section>

  <section class="probootstrap-section probootstrap-section-dark">
    <div class="container">
      <div class="row">
        <div class="col-md-12 text-center">
          <h2 class="mt0">Why Choose Us?</h2>
          <p class="mb50"><img src="../img/curve.svg" class="svg" alt="Free HTML5 Bootstrap Template"></p>
        </div>
        <div class="col-md-4">
          <div class="service left-icon left-icon-sm probootstrap-animate">
            <div class="icon">
              <i class="icon-check"></i>
            </div>
            <div class="text">
              <h3>1+ Million Hotel Rooms</h3>
              <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
              <p><a href="#" class="link-with-icon">Learn More <i class=" icon-chevron-right"></i></a></p>
            </div>  
          </div>
        </div>
        <div class="col-md-4">
          <div class="service left-icon left-icon-sm probootstrap-animate">
            <div class="icon">
              <i class="icon-check"></i>
            </div>
            <div class="text">
              <h3>Food &amp; Drinks</h3>
              <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
              <p><a href="#" class="link-with-icon">Learn More <i class=" icon-chevron-right"></i></a></p>
            </div>  
          </div>
        </div>
        <div class="col-md-4">
          <div class="service left-icon left-icon-sm probootstrap-animate">
            <div class="icon">
              <i class="icon-check"></i>
            </div>
            <div class="text">
              <h3>Airport Taxi</h3>
              <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
              <p><a href="#" class="link-with-icon">Learn More <i class=" icon-chevron-right"></i></a></p>
            </div>  
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <section class="probootstrap-section">
    <div class="container">
      <div class="row">
        <div class="col-md-8 col-md-offset-2 mb50 text-center probootstrap-animate">
          <h2 class="mt0">More Features</h2>
          <p class="mb30"><img src="../img/curve.svg" class="svg" alt="Free HTML5 Bootstrap Template"></p>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4 col-sm-6 col-xs-12 probootstrap-animate">
          <h3 class="heading-with-icon"><i class="icon-heart2"></i> <span>Countries Vokalia</span></h3>
          <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
          <p><a href="#" class="link-with-icon">Learn More <i class=" icon-chevron-right"></i></a></p>
        </div>
        <div class="col-md-4 col-sm-6 col-xs-12 probootstrap-animate">
          <h3 class="heading-with-icon"><i class="icon-rocket"></i> <span>Large Language Ocean</span></h3>
          <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
          <p><a href="#" class="link-with-icon">Learn More <i class=" icon-chevron-right"></i></a></p>
        </div>
        <div class="clearfix visible-sm-block"></div>
        <div class="col-md-4 col-sm-6 col-xs-12 probootstrap-animate">
          <h3 class="heading-with-icon"><i class="icon-image"></i> <span>Bookmarksgrove Right</span></h3>
          <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
          <p><a href="#" class="link-with-icon">Learn More <i class=" icon-chevron-right"></i></a></p>
        </div>
        <div class="clearfix visible-lg-block visible-md-block"></div>
        <div class="col-md-4 col-sm-6 col-xs-12 probootstrap-animate">
          <h3 class="heading-with-icon"><i class="icon-briefcase"></i> <span>Live the Blind Texts</span></h3>
          <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
          <p><a href="#" class="link-with-icon">Learn More <i class=" icon-chevron-right"></i></a></p>
        </div>
        <div class="clearfix visible-sm-block"></div>
        <div class="col-md-4 col-sm-6 col-xs-12 probootstrap-animate">
          <h3 class="heading-with-icon"><i class="icon-chat"></i> <span>Behind the Word Mountains</span></h3>
          <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
          <p><a href="#" class="link-with-icon">Learn More <i class=" icon-chevron-right"></i></a></p>
        </div>
        <div class="col-md-4 col-sm-6 col-xs-12 probootstrap-animate">
          <h3 class="heading-with-icon"><i class="icon-colours"></i> <span>Separated They Live</span></h3>
          <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
          <p><a href="#" class="link-with-icon">Learn More <i class=" icon-chevron-right"></i></a></p>
        </div>
        <div class="clearfix visible-lg-block visible-md-block visible-sm-block"></div>
      </div>
    </div>
  </section>
  

  <section class="probootstrap-section probootstrap-section-dark">
    <div class="container">
      <div class="row mb30">
        <div class="col-md-8 col-md-offset-2 probootstrap-section-heading text-center">
          <h2>Explore our Services</h2>
          <p class="lead">Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
          <p><img src="../img/curve.svg" class="svg" alt="Free HTML5 Bootstrap Template"></p>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="service left-icon probootstrap-animate">
            <div class="icon">
              <img src="../img/flaticon/svg/001-building.svg" class="svg" alt="Free HTML5 Bootstrap Template by uicookies.com">
            </div>
            <div class="text">
              <h3>1+ Million Hotel Rooms</h3>
              <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
              <p><a href="#" class="link-with-icon">Learn More <i class=" icon-chevron-right"></i></a></p>
            </div>  
          </div>
        </div>
        <div class="col-md-4">
          <div class="service left-icon probootstrap-animate">
            <div class="icon">
              <img src="../img/flaticon/svg/003-restaurant.svg" class="svg" alt="Free HTML5 Bootstrap Template by uicookies.com">
            </div>
            <div class="text">
              <h3>Food &amp; Drinks</h3>
              <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
              <p><a href="#" class="link-with-icon">Learn More <i class=" icon-chevron-right"></i></a></p>
            </div>  
          </div>
        </div>
        <div class="col-md-4">
          <div class="service left-icon probootstrap-animate">
            <div class="icon">
              <img src="../img/flaticon/svg/004-parking.svg" class="svg" alt="Free HTML5 Bootstrap Template by uicookies.com">
            </div>
            <div class="text">
              <h3>Airport Taxi</h3>
              <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts.</p>
              <p><a href="#" class="link-with-icon">Learn More <i class=" icon-chevron-right"></i></a></p>
            </div>  
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="probootstrap-half">
    <div class="image" style="background-image: url(../img/slider_2.jpg);"></div>
    <div class="text">
      <div class="probootstrap-animate fadeInUp probootstrap-animated">
        <h2 class="mt0">Best 5 Star hotel</h2>
        <p><img src="img/curve_white.svg" class="seperator" alt="Free HTML5 Bootstrap Template"></p>
        <div class="row">
          <div class="col-md-6">
            <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>    
          </div>
          <div class="col-md-6">
            <p>A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.</p>    
          </div>
        </div>
        <p><a href="#" class="link-with-icon white">Learn More <i class=" icon-chevron-right"></i></a></p>
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
            <p>A small river named Duden flows by their place and supplies it with the necessary regelialia. It is a paradisematic country, in which roasted parts of sentences fly into your mouth.</p>
            <p><a href="#" class="link-with-icon">Learn More <i class=" icon-chevron-right"></i></a></p>
          </div>
        </div>
        <div class="col-md-4">
      <div class="row mt40">
        <div class="col-md-12 text-center">
          <ul class="probootstrap-footer-social">
            <li><a href=""><i class="icon-twitter"></i></a></li>
            <li><a href=""><i class="icon-facebook"></i></a></li>
            <li><a href=""><i class="icon-instagram2"></i></a></li>
          </ul>
          <p>
            <small>&copy; 2017 <a href="https://uicookies.com/" target="_blank">uiCookies:Atlantis</a>. All Rights Reserved. <br> Designed &amp; Developed by <a href="https://uicookies.com/" target="_blank">uicookies.com</a> Demo Images: Unsplash.com &amp; Pexels.com</small>
          </p>
          
        </div>
      </div>
    </div>
  </footer>
  <!-- END: footer -->

    <script type='text/javascript'>

    var endDate = new Date();
    endDate.setDate(endDate.getDate()+7);
    document.getElementById('datestart').valueAsDate = new Date();
    document.getElementById('dateend').valueAsDate = endDate;

        function addRooms() {
            // Number of inputs to create
            var number = document.getElementById("roomnumber").value;
            // Container <div> where dynamic content will be placed
            var container = document.getElementById("roomselect");
            // Clear previous contents of the container
            while (container.hasChildNodes()) {
                container.removeChild(container.lastChild);
            }
            for (i=0;i<number;i++){
                // Append a node with a random text
                container.appendChild(document.createTextNode("Habitación " + (i+1) + " Number "));
                // Create an <input> element, set its type and name attributes
                var input = document.createElement("input");
                input.type = "text";
                input.name = "habitacion" + i;
                container.appendChild(input);
                // Append a line break 
                container.appendChild(document.createElement("br"));
            }
            var button = document.createElement("button");
            button.type = "submit";
            button.name = "new_reservation";
            button.id = "submitreservation";
            button.value = "Add";
            button.className = "btn btn-default"
            button.innerHTML = "Add";
            container.appendChild(button);
        }
    </script>
  
 
  <script src="../js/scripts.min.js"></script>
  <script src="../js/main.min.js"></script>
  <script src="../js/custom.js"></script>

  </body>
</html>