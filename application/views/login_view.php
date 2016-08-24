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
 $role = $this->customsession->getData( USER_ROLE );
 $message = '';
 if($message){ ?>
        <div align="center"  style="color:green"> <?php  
    echo $message; ?> </div>
  <?php   } ?>
 <form class="form-horizontal" method="post" name="loginFrm" id="loginFrm">
     <div class="form-group">
        <label for="inputEmail" class="control-label col-xs-2">Email</label>
        <div class="col-xs-3">
            <input type="text" name="email" class="form-control" id="inputEmail" title="Email" value="" size="30" maxlength="50" >
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="control-label col-xs-2">Password</label>
        <div class="col-xs-3">
            <input name="password" type="password" class="form-control" id="inputPassword" title="Password" value="" size="30" maxlength="48">
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-offset-2 col-xs-5">
                <label>New User?</label> <a href="register">Register Here</a>
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-offset-2 col-xs-5">
            <button type="submit" class="btn btn-primary" value="submit" onclick="return checkLoginForm();">Login</button>
        </div>
    </div>
</form>
</div>
 <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js"></script>
    <script type="text/javascript" src="<?php echo $js; ?>"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>