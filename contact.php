<?php session_start();
$errors = array();
    $db = mysqli_connect('localhost','root','','hoteles')
        or die('Error connecting to MySQL server.');
        //Contraseña cuenta administrador: adminpass

 //LOGIN
    if (isset($_POST['login_user'])) {
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if (empty($email)) {
      array_push($errors, "Email is required");
    }
    if (empty($password)) {
      array_push($errors, "Password is required");
    }

    if (count($errors) == 0) {
      $password = md5($password);
      $query = "SELECT * FROM usuarios WHERE correo='$email' AND password='$password'";
      $results = mysqli_query($db, $query) or die(mysql_error());
      $resultsarr = mysqli_fetch_array($results);
      if (mysqli_num_rows($results) == 1) {
        $_SESSION['login_user'] = $email;
        $_SESSION['name'] = $resultsarr['nombre'];
        $_SESSION['tipo_usuario'] = $resultsarr['tipo_usuario'];
        //$_SESSION['success'] = "You are now logged in";
        //header('location: index.php');
      }else {
        array_push($errors, "Wrong username/password combination");
      }
    }
  }

  // END: LOGIN

  //REGISTER
  $name = "";
      $surname = "";
    $email    = "";
    $errors = array();


    if (isset($_POST['submit_register'])) {
      //Guardamos las variables del formulario
  $name = mysqli_real_escape_string($db, $_POST['name']);
  $surname = mysqli_real_escape_string($db, $_POST['surname']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

  //Comprobamos que los campos estén completos
  if (empty($name)) { array_push($errors, "Name is required"); }
  if (empty($surname)) { array_push($errors, "Surnames are required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
  array_push($errors, "The two passwords do not match");
  }

  //Comprobamos que el usuario no se repita
  $user_check_query = "SELECT * FROM usuarios WHERE correo ='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { //Si ya existe
    if ($user['correo'] === $email) {
      array_push($errors, "That email is already in the database");
    }
  }

  // Si no hay errores se registra al usuario
  if (count($errors) == 0) {
    $password = md5($password_1); //MD5 sirve para encriptar las contraseñas

    $query = "INSERT INTO usuarios (correo, apellidos, nombre, password, tipo_usuario) 
          VALUES('$email', '$surname', '$name', '$password', '2')";
    mysqli_query($db, $query);
    $_SESSION['login_user'] = $email;
    $_SESSION['name'] = $name;
    $_SESSION['success'] = "You are now logged in";
  }
}
  ?>
<!DOCTYPE html>

<html lang="en">
  <head>
    <?php
    $db = mysqli_connect('localhost','root','','hoteles')
    or die('Error connecting to MySQL server.');
    ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nipohtels Index</title>
    <meta name="description" content="Free Bootstrap Theme by uicookies.com">
    <meta name="keywords" content="free website templates, free bootstrap themes, free template, free bootstrap, free website template">
    
    <link href="https://fonts.googleapis.com/css?family=Crimson+Text:300,400,700|Rubik:300,400,700,900" rel="stylesheet">
    <link rel="stylesheet" href="css/custom.css" type="text/css">
    <link rel="stylesheet" href="css/styles-merged.css" type="text/css">
    <link rel="stylesheet" href="css/style.min.css" type="text/css">

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

          <?php if(isset($_SESSION['login_user']) && $_SESSION['tipo_usuario'] == '2'){ ?>
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
          <?php if(isset($_SESSION['login_user']) && $_SESSION['tipo_usuario'] == '1'){ ?>
          <ul class="probootstrap-main-nav">
            <li>Welcome <?php echo $_SESSION['name']?><li>
            <li class="active"><a href="index.php">Home</a></li>
            <li><a href="about.php">About us</a></li>
            <li class="hidden-xs probootstrap-logo-center"><a href="index.php"><img src="img/logo_md.png" class="hires" width="181" height="50" alt="Nipohtels logo"></a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href='adminpage.php'>Admin Tools</a></li>
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
       <li style="background-image: url(img/slider_7.jpg);" class="overlay">
          <div class="container">
            <div class="row">
              <div class="col-md-10 col-md-offset-1">
                <div class="probootstrap-slider-text text-center">
                  <p><img src="img/curve_white.svg" class="seperator probootstrap-animate" alt="Free HTML5 Bootstrap Template"></p>
                  <h1 class="probootstrap-heading probootstrap-animate">Contact</h1>
                  <div class="probootstrap-animate probootstrap-sub-wrap">I’m in love with cities I’ve never been to and people I’ve never met.</div>
                </div>
              </div>
            </div>
          </div>
        </li>
    </ul>
  </section>

<section class="probootstrap-section">
    <div class="container">
      <div class="row probootstrap-gutter60">
        <div class="col-md-8">
          <h2 class="mt0">Contact Form</h2>
          <form action="mailto:administradornipohtels@gmail.com" method="post" class="probootstrap-form">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="fname">First Name</label>
                  <input type="text" class="form-control" id="fname" name="fname">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="lname">Last Name</label>
                  <input type="text" class="form-control" id="lname" name="lname">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="form-group">
              <label for="subject">Subject</label>
              <input type="text" class="form-control" id="subject" name="subject">
            </div>
            <div class="form-group">
              <label for="message">Message</label>
              <textarea cols="30" rows="10" class="form-control" id="message" name="message"></textarea>
            </div>
            <div class="form-group">
              <input type="submit" class="btn btn-primary btn-lg" id="submit" name="submit" value="Send Message">
            </div>
          </form>
        </div>
        <div class="col-md-4">
          <h2 class="mt0">Get In Touch</h2>
          <ul class="probootstrap-contact-info">
            <li><i class="icon-location2"></i> <span>198 West 21th Street, Suite 721 New York NY 10016</span></li>
            <li><i class="icon-mail"></i><span>administradornipohtels@gmail.com</span></li>
            <li><i class="icon-phone2"></i><span>+123 456 7890</span></li>
          </ul>
          <h2>Feedback</h2>
          <p>Same as the little gifts the travelers left to us in our first little small home, these are our gifts in this day and age. Being able to make people happy enough so they want to contact us and tell us how amazing their journey was.</p>
          <p><a href="#" class="btn btn-primary" role="button">Send Message</a></p>
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