<div class="container">
 <h2>Register Here</h2>
<?php if(isset($errorMessage)){?>
 <div class="error"> <p><?php echo $errorMessage;?></p></div>
<?php }
?>
 <form class="form-horizontal" method="post" name="signupForm" id="signupForm">
    <div class="form-group">
        <label for="inputFirstName" class="control-label col-xs-2"><span class="mandatory">*</span>First Name</label>
        <div class="col-xs-3">
            <input type="text" name="inputFirstName" class="form-control" id="inputFirstName" style="text-transform: capitalize;" title="First Name" value="" size="30" maxlength="50" >
        </div>
    </div>
     <div class="form-group">
        <label for="inputLastName" class="control-label col-xs-2"><span class="mandatory">*</span>Last Name</label>
        <div class="col-xs-3">
            <input type="text" name="inputLastName" class="form-control" id="inputLastName" style="text-transform: capitalize;" title="Last Name" value="" size="30" maxlength="50" >
        </div>
    </div>
    <div class="form-group">
        <label for="inputUserName" class="control-label col-xs-2"><span class="mandatory">*</span>User Name</label>
        <div class="col-xs-3">
            <input type="text" name="inputUserName" class="form-control" id="inputUserName" title="User Name" value="" size="30" maxlength="50" >
        </div>
    </div>
    <div class="form-group">
        <label for="inputEmail" class="control-label col-xs-2">Email</label>
        <div class="col-xs-3">
            <input type="text" name="inputEmail" class="form-control" id="inputEmail" title="Email" label="Email" value="" size="30" maxlength="50" >
        </div>
    </div>
    <div class="form-group">
        <label for="inputMobileNumber" class="control-label col-xs-2">Mobile Number</label>
        <div class="col-xs-3">
            <input type="text" name="inputMobileNumber"   class="form-control" id="inputEmail" title="Mobile Number" value="" size="30" maxlength="50" >
        </div>
    </div>
    <div class="form-group">
        <label for="inputPassword" class="control-label col-xs-2"><span class="mandatory">*</span>Password</label>
        <div class="col-xs-3">
            <input name="inputPassword" type="password" class="form-control" id="inputPassword" title="Password" value="" size="30" maxlength="48">
        </div>
    </div>
         <div class="form-group">
        <label for="inputConfirmPassword" class="control-label col-xs-2"><span class="mandatory">*</span>Confirm Password</label>
        <div class="col-xs-3">
            <input name="inputConfirmPassword" type="password" class="form-control" id="inputConfirmPassword" title="Confirm Password" value="" size="30" maxlength="48">
        </div>
    </div>
    <div>
            <p class='error' id="passwordsError"></p>
        </div>

    <div class="form-group">
        <div class="col-xs-offset-2 col-xs-5">
            <button name='submit' type="submit" class="btn btn-primary" value="submit" >Submit</button>
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-offset-2 col-xs-5">
            <label>Already Registerd?</label> <a href="<?php echo commonHelperGetPageUrl('login');?>">Click Here</a>
        </div>
    </div>
</form>
</div>