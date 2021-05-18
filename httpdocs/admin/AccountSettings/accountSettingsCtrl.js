angular
.module('hris', [])
.controller('AccountSettingsController', function ($scope, $http) {
    // Data Binding
    $scope.currentPassword = '';
    $scope.newPassword = '';
    $scope.confirmNewPassword = '';
    
    // Method Binding
    $scope.cancel_email = CancelEmail;
    $scope.change_email = ChangeEmail;
    $scope.change_password_confirm = ChangePasswordConfirm;
    $scope.checkAvailability = CheckAvailability;
    $scope.save_email = SaveEmail;
    $scope.validatePassword = ValidatePassword;

    // Functions
    function CancelEmail() {
        document.getElementById("enteredEmail").setAttribute("readonly", "_self");
        document.getElementById("editEmailBtn").style.visibility = "visible";
        document.getElementById("cancelEmailBtn").style.visibility = "hidden";
        document.getElementById("saveEmailBtn").style.visibility = "hidden";
    }

    function ChangeEmail() {
        // // if (event.target == modal) {
        //   modal.style.display = "none";
        // //}
        document.getElementById("enteredEmail").removeAttribute("readonly");
        document.getElementById("editEmailBtn").style.visibility = "hidden";
        document.getElementById("cancelEmailBtn").style.visibility = "visible";
        document.getElementById("saveEmailBtn").style.visibility = "visible";
    }

    function ChangePasswordConfirm()   {
        Swal.fire({
            title:'Do you wish to continue?',
            type:'warning',
            icon:'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.value) {
                $('#real_submit_button').click();
            }
        });
    }

    function CheckAvailability() {
        jQuery.ajax({
            url: "check_availability.php",
            data:'currentPassword='+$("#currentPassword").val(),
            type: "POST",
            success: function (data) {
                $("#user-availability-status").html(data);
            },
            error: function () { }
        });
    }

    function SaveEmail() {
        document.getElementById("enteredEmail").setAttribute("readonly", "_self");
        document.getElementById("editEmailBtn").style.visibility = "visible";
        document.getElementById("cancelEmailBtn").style.visibility = "hidden";
        document.getElementById("saveEmailBtn").style.visibility = "hidden";
    }
  

    function ValidatePassword() {
        var currentPassword, newPassword, confirmPassword, output = true;
     
        currentPassword = document.frmChange.currentPassword;
        newPassword = document.frmChange.newPassword;
        confirmPassword = document.frmChange.confirmPassword;
     
        if (!currentPassword.value) {
            currentPassword.focus();
            output = false;
        }
        if (!newPassword.value) {
            newPassword.focus();
            output = false;
        }
        else if (!confirmPassword.value) {
            confirmPassword.focus();
            output = false;
        }
        if (newPassword.value != confirmPassword.value) {
            newPassword.value="";
            confirmPassword.value="";
            newPassword.focus();
            output = false;
        }
        return output;
    }

    $(".revealCurrentPassword").on('click',function() {
        var $pwd = $("#currentPassword");
        if ($pwd.attr('type') === 'password') {
            $pwd.attr('type', 'text');
        } else {
            $pwd.attr('type', 'password');
        }
    });

    $(".revealNewPassword").on('click',function() {
        var $pwd = $("#newPassword");
        if ($pwd.attr('type') === 'password') {
            $pwd.attr('type', 'text');
        } else {
            $pwd.attr('type', 'password');
        }
    });

    $(".revealConfirmNewPassword").on('click',function() {
        var $pwd = $("#confirmNewPassword");
        if ($pwd.attr('type') === 'password') {
            $pwd.attr('type', 'text');
        } else {
            $pwd.attr('type', 'password');
        }
    });

    function myFunction() {
        var x = document.getElementById("currentPassword");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }

    function myFunction2() {
        var z = document.getElementById("newPassword");
   
        if (z.type === "password") {
            z.type = "text";
        } else {
            z.type = "password";
        }
    }

    function myFunction3() {
        var newPass = document.getElementById("confirmNewPassword");
        if (newPass.type === "password") {
            newPass.type = "text";
        } else {
            newPass.type = "password";
        }
    }

    function checkAvailability2() {
        jQuery.ajax({
            url: "check_availability.php",
            data:'currentPassword='+$("#currentPassword2").val(),
            type: "POST",
            success: function(data) {
                $("#user-availability-status2").html(data);
            },
            error: function () { }
        });
    }

    // var modal = document.getElementById("myModal");

    // var btn = document.getElementById("editEmailBtn");

    // var span = document.getElementsByClassName("close")[0];

    // // When the user clicks the button, open the modal 
    // btn.onclick = function() {
    //   modal.style.display = "block";
    // }

    // // When the user clicks on <span> (x), close the modal
    // span.onclick = function() {
    //   modal.style.display = "none";
    // }

    // When the user clicks anywhere outside of the modal, close it
});