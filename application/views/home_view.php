<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Welcome to Chess</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<!--<link rel="stylesheet" href="css/bootstrap-theme.min.css">-->
</head>
<body>
<div class="container">
<h2>CHESS</h2>
<div id="refbtns" align="right">
 <a href="login" class="btn btn-primary">Login</a>
 
 <div id="refbtns" align="left">
        <a href='<?php echo commonHelperGetPageUrl('signup', USER_TRAINEE);?>'>Trainee Registration</a>
        <a href='<?php echo commonHelperGetPageUrl('signup', USER_TRAINER);?>'>Trainer Registration</a>
 </div>   
        
<!-- <a href="register" class="btn btn-primary">Signup</a>-->
 </div>
</div>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
 
</html>

