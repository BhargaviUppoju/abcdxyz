$(document).ready(function(){   
   $('#signupForm').validate({
        rules: {
            inputFirstName: {
                required: true,
                firstName: true,
                maxlength: 50
            },
            inputLastName: {
                required: true,
                firstName: true,
                maxlength: 50
            },
            inputUserName:{
                required: true,
                 minlength: 6,
                 maxlength: 50
            },
            inputEmail: {
                email: true
            },
            inputMobileNumber:{
                 number: true,
                 minlength: 6,
                 maxlength: 50
            },
            inputPassword: {
                required: true,
                 minlength: 6
            },
            inputConfirmPassword: {
                required: true,
                 minlength: 6
            }

        },
        messages: {
            inputFirstName: {
                required: "Please enter first name",
                maxlength: "Please enter not more than 50 characters"
            },
            inputLastName: {
                required: "Please enter Last name",
                maxlength: "Please enter not more than 50 characters"
            },
            inputUserName: {
                required: "Please enter user name",
                maxlength: "Please enter not more than 50 characters",
                minlength: "Please enter minimum 6 characters"
            },
            inputEmail: {
                 email: "Please enter valid email"
            },
            inputMobileNumber: {
                number: "Please enter valid mobile number",
                maxlength: "Please enter not more than 50 characters",
                minlength: "Please enter minimum 6 characters"
            },
            inputPassword: {
                required: "Please enter password",
                minlength: "Please enter minimum 6 characters"
            },
            inputConfirmPassword: {
                required: "Please enter confirm password",
                minlength: "Please enter minimum 6 characters"
            }
        },
        submitHandler: function (form) {
            if ($(form).valid())
            {
               if($('#inputPassword').val()==$('#inputConfirmPassword').val()){
                   $('#passwordsError').text('');
                  form.submit(); 
               }else{
                   $('#passwordsError').text('Entered passwords should be matched.');
               }
               
            }
            return false;
        }

    });
    
        $.validator.addMethod("firstName", function (value, element) {
        return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
    }, "Letters and numbers are allowed");
    
    
});    
    