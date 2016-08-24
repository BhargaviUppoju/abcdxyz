<html>
    <head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Login Page</title>
<!--<link href="css/style.css" rel="stylesheet" type="text/css">-->
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<!--<link rel="stylesheet" href="css/bootstrap-theme.min.css">-->
</head>
<body>
<div class="container">
 <h2>Login Page</h2>
 <?php 
 if(isset($errors) && !empty($errors)){ ?>
 <div id="errors" style="color:red"> <?php echo $errors; ?></div> 
  <?php } ?>

 <form class="form-horizontal" method="post" name="loginForm" id="loginForm">
     <div class="form-group">
        <label for="inputUname" class="control-label col-xs-2">Username</label>
        <div class="col-xs-3">
            <input type="text" name="username" class="form-control" id="inputUname" title="Username" label="Username" value="" size="30" maxlength="50" >
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="control-label col-xs-2">Password</label>
        <div class="col-xs-3">
            <input name="password" type="password" class="form-control" id="inputPassword" title="Password" label="Password" value="" size="30" maxlength="48">
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-offset-2 col-xs-5">
            <label>New User?</label> <a href="<?php echo commonHelperGetPageUrl('signup',USER_TRAINEE) ?>">Register Here</a>
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-offset-2 col-xs-5">
            <button type="submit" class="btn btn-primary" value="submit" name="LoginSubmit" id="submitlogin">Login</button>
        </div>
    </div>
</form>
</div>
 <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</body>
</html>