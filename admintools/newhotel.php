<?php
    session_start();
    $db = mysqli_connect('localhost','root','','hoteles')
      or die('Error connecting to MySQL server.'); 
    $errors = array();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register new hotel - Admintools</title>
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
            <li><a href="about.php">About us</a></li>
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
            <li><a href="about.php">About us</a></li>
            <li class="hidden-xs probootstrap-logo-center"><a href="index.php"><img src="img/logo_md.png" class="hires" width="181" height="50" alt="Nipohtels logo"></a></li>
            <li><a href="../contact.php">Contact</a></li>
            <li><a href='userpage.php'>My account</a></li>
            <li><a href='logout.php'>Log out</a></li>
          </ul>
          <?php } ?>
          <?php if(isset($_SESSION['login_user']) && $_SESSION['tipo_usuario'] == '1'){ ?>
          <ul class="probootstrap-main-nav">
            <li>Welcome <?php echo $_SESSION['name']?><li>
            <li><a href="../index.php">Home</a></li>
            <li><a href="../about.php">About us</a></li>
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
       <li style="background-image: url(../img/slider_9.jpg);" class="overlay">
          <div class="container">
            <div class="row">
              <div class="col-md-10 col-md-offset-1">
                <div class="probootstrap-slider-text text-center">
                  <p><img src="../img/curve_white.svg" class="seperator probootstrap-animate" alt="Free HTML5 Bootstrap Template"></p>
                  <h1 class="probootstrap-heading probootstrap-animate">Create new hotel</h1>
                  <div class="probootstrap-animate probootstrap-sub-wrap">We wander for distraction, but we travel for fulfillment.</div>
                </div>
              </div>
            </div>
          </div>
        </li>
    </ul>
  </section>

  <section class="table-responsive">
  <div class="col-md-12 text-center" style="width: 200px; height: 100%; position: relative; left: 850px; margin-top: 50px; margin-bottom: 50px;">
      <form method="POST" action="newhotel.php" enctype="multipart/form-data">
    <?php include('../errors.php');?>
    <div class="input-group">
        <label>Name:</label>
        <input type="text" name="name" >
      </div>
      <div class="input-group">
        <label>Address:</label>
        <input type="text" name="ubicacion">
      </div>
      <div class="input-group">
        <label>City:</label>
        <input type="text" name="ciudad">
      </div>
      <div class="input-group">
        <label>Stars:</label>
        <input type="number" name="estrellas" onblur="checkStars()" id="stars">
      </div>
      <div class="input-group">
        <label>Price multiplier:</label>
        <input type="number" step="0.1" name="precio">
      </div>
      <div class="input-group">
        <label>Image:</label>
        <input type="file" name="image">
      </div>
      <div class="input-group">
        <button type="submit" class="btn" name="new_hotel" id="submithotel">Register hotel</button>
      </div>
  </form>
  <?php 
      if (isset($_POST['new_hotel'])) {
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $ubicacion = mysqli_real_escape_string($db, $_POST['ubicacion']);
    $ciudad = mysqli_real_escape_string($db, $_POST['ciudad']);
    $estrellas = mysqli_real_escape_string($db, $_POST['estrellas']);
    $precio = mysqli_real_escape_string($db, $_POST['precio']);

    if (empty($name)) {
      array_push($errors, "A name for the hotel is required");
    }
    if (empty($ubicacion)) {
      array_push($errors, "A postal direction for the hotel is required");
    }
    if (empty($ciudad)) {
      array_push($errors, "A city for the hotel is required");
    }
    if (empty($estrellas)) {
      array_push($errors, "An official calification for the hotel is required");
    }
    if (empty($precio)) {
      array_push($errors, "A cost for the hotel is required");
    }

        /*Prueba de subir imagen*/
        /*Esto funciona, no tocar*/
    define ('SITE_ROOT', realpath(dirname(__FILE__)));
    $imagefile = $_FILES['image'];
    $imagename = $imagefile['name'];
    $path = "/../img/hotels/" . basename($imagename);
  //if (move_uploaded_file($imagefile['tmp_name'], $path)) {
    if (move_uploaded_file($_FILES['image']['tmp_name'], SITE_ROOT.$path)) {
      //echo "Se ha subio bien la imagen ninia";
  } else {
      array_push($errors, "Hay algo mal con esa imagen...");
  }
  if (empty($imagefile)) {
      array_push($errors, "An image for the hotel is required");
    }

    if (count($errors) == 0) {
      $query = "INSERT INTO hoteles (`id_hotel`, `nombre`, `ubicacion`, `ciudad`, `estrellas`, `path_img`, `precio_multi`) VALUES ('', '$name','$ubicacion', '$ciudad', '$estrellas', '$imagename', '$precio')";
      $results = mysqli_query($db, $query) or die(mysql_error());
      echo "Hotel created";
      //header('Refresh: 5; URL=../adminpage.php');
  }
}?>
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
              <p><i>"Chilling out on the bed in your hotel room watching television, while wearing your own pajamas, is sometimes the best part of a vacation."</i></p>
              <h6>- Laura Marano</h6>
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
              <p><i>"Food is for eating, and good food is to be enjoyed... I think food is, actually, very beautiful in itself."</i></p>
              <h6>- Delia Smith</h6>
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
              <p><i>"I get out of the taxi and it's probably the only city which in reality looks better than on the postcards, New York."</i></p>
              <h6>- Milos Forman</h6>
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
          <h3 class="heading-with-icon"><i class="icon-heart2"></i> <span>Our love for you</span></h3>
          <p><i>"It's not so much that I don't like traveling, it's just that I love being home. I love being able to spend time with my friends."</i></p>
          <h6>- Andy Roddick</h6>
        </div>
        <div class="col-md-4 col-sm-6 col-xs-12 probootstrap-animate">
          <h3 class="heading-with-icon"><i class="icon-rocket"></i> <span>Wishes for improvement</span></h3>
          <p><i>"Aim for the moon. If you miss, you may hit a star."</i></p>
          <h6>- W. Clement Stone</h6>
        </div>
        <div class="clearfix visible-sm-block"></div>
        <div class="col-md-4 col-sm-6 col-xs-12 probootstrap-animate">
          <h3 class="heading-with-icon"><i class="icon-image"></i> <span>Seeking new inspirations</span></h3>
          <p><i>"Wherever you look there are inspirations, books, literature, paintings, landscapes, everything. Just living is an inspiration."</i></p>
          <h6>- Gavin Rossdale</h6>
        </div>
        <div class="clearfix visible-lg-block visible-md-block"></div>
        <div class="col-md-4 col-sm-6 col-xs-12 probootstrap-animate">
          <h3 class="heading-with-icon"><i class="icon-briefcase"></i> <span>The pleasure of literature</span></h3>
          <p><i>"The world is a book, and those who do not travel read only a page."</i></p>
          <h6>- Saint Augustine</h6>
        </div>
        <div class="clearfix visible-sm-block"></div>
        <div class="col-md-4 col-sm-6 col-xs-12 probootstrap-animate">
          <h3 class="heading-with-icon"><i class="icon-chat"></i> <span>Always sincere</span></h3>
          <p><i>"A lie can travel half way around the world while the truth is putting on its shoes."</i></p>
          <h6>- Charles Spurgeon</h6>
        </div>
        <div class="col-md-4 col-sm-6 col-xs-12 probootstrap-animate">
          <h3 class="heading-with-icon"><i class="icon-colours"></i> <span>LGBTQ+</span></h3>
          <p><i>"Because of my fighting for LGBT rights, I have seen the possibility of change. And that gives me heart to believe that it is possible to effect change."</i></p>
          <h6>- Sandi Toksvig</h6>
        </div>
        <div class="clearfix visible-lg-block visible-md-block visible-sm-block"></div>
      </div>
    </div>
  </section>

<section class="probootstrap-half">
    <div class="image" style="background-image: url(../img/slider_2.jpg);"></div>
    <div class="text">
      <div class="probootstrap-animate fadeInUp probootstrap-animated">
        <h2 class="mt0">Travel</h2>
        <p><img src="../img/curve_white.svg" class="seperator" alt="Free HTML5 Bootstrap Template"></p>
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
            <p class="mt40"><img src="../img/logo_sm.png" class="hires" width="120" height="33" alt="Free HTML5 Bootstrap Template by uicookies.com"></p>
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
  <script type="text/javascript">
    function checkStars(){
      if( document.getElementById('stars').value > 5 || document.getElementById('stars').value < 1){
        array_push($errors, "The 'Stars' parameter must be a number between 1 and 5");
      }
    }
  </script>
  
 
  <script src="../js/scripts.min.js"></script>
  <script src="../js/main.min.js"></script>
  <script src="../js/custom.js"></script>

  </body>
</html>