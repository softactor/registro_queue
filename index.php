<?php session_start(); 
date_default_timezone_set('Asia/Singapore');
include 'connection/connect.php';
include 'helper/utilities.php';
include 'function/registration_process.php';
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" type="image/x-icon" href="images/icon/port.png" />

        <title>Registro Queue</title>

        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="vendor/bower_components/bootstrap/dist/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="vendor/bower_components/font-awesome/css/font-awesome.min.css">
        <!-- Custom styles for this template -->
        <link href="css/offcanvas.css" rel="stylesheet">
        <link href="css/style.css" rel="stylesheet">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>
        <nav class="navbar navbar-fixed-top navbar-inverse">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Project name</a>
                </div>
            </div><!-- /.container -->
        </nav><!-- /.navbar -->

        <div class="container">

            <div class="row row-offcanvas row-offcanvas-right">

                <div class="col-xs-12 col-sm-9">
                    <div class="jumbotron">
                        <h1>Event Header Image!</h1>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-lg-12 col-md-12 col-sm-12">
                            <?php include 'operation_message.php'; ?>
                            <h2>Heading</h2>
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="email">Name:</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?php if(isset($_POST['name']) && !empty($_POST['name'])){ echo $_POST['name'];} ?>">
                                </div>
                                <div class="form-group">
                                    <label for="pwd">Mobile:</label>
                                    <input type="text" class="form-control" id="mobile" name="mobile" value="<?php if(isset($_POST['mobile']) && !empty($_POST['mobile'])){ echo $_POST['mobile'];} ?>">
                                    <span class="hint_text">Please give only 8 digit of your mobile number. System will add <b>+65</b> by default</span>
                                </div>
                                <input type="submit" name="registration_submit" class="btn btn-primary btn-block" value="Registration">
                            </form>
                        </div><!--/.col-xs-6.col-lg-4-->
                    </div><!--/row-->
                </div><!--/.col-xs-12.col-sm-9-->
            </div><!--/row-->

            <hr>

            <footer>
                <p>Footer Notes.</p>
            </footer>

        </div><!--/.container-->


        <!-- Bootstrap core JavaScript
        ================================================== -->
        <!-- Placed at the end of the document so the pages load faster -->
        <!-- jQuery 3 -->
        <script src="vendor/bower_components/jquery/dist/jquery.min.js"></script>
        <!-- Bootstrap 3.3.7 -->
        <script src="vendor/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="js/offcanvas.js"></script>
    </body>
</html>
