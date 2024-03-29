<?php
include ('../includes/header.php');

?>

<!-- Page -->
  <body class="animaition page-login-v3 layout-full">
<div class="page vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out">>
  <div class="page-content vertical-align-middle">
    <div class="panel">
      <div class="panel-body">
        <div class="brand">
          <img class="brand-img" src="../assets//images/logo-colored.png" alt="...">
          <h2 class="brand-text font-size-18">Remark</h2>
        </div>
        <form method="post" action="#" autocomplete="off">
          <div class="form-group form-material floating" data-plugin="formMaterial">
            <input type="email" class="form-control" name="email" />
            <label class="floating-label">Email</label>
          </div>
          <div class="form-group form-material floating" data-plugin="formMaterial">
            <input type="password" class="form-control" name="password" />
            <label class="floating-label">Password</label>
          </div>
          <div class="form-group clearfix">
            <div class="checkbox-custom checkbox-inline checkbox-primary checkbox-lg float-left">
              <input type="checkbox" id="inputCheckbox" name="remember">
              <label for="inputCheckbox">Remember me</label>
            </div>
            <a class="float-right" href="forgot-password.html">Forgot password?</a>
          </div>
          <button type="submit" class="btn btn-primary btn-block btn-lg mt-40">Sign in</button>
        </form>
        <p>Still no account? Please go to <a href="register-v3.html">Sign up</a></p>
      </div>
    </div>

    <footer class="page-copyright page-copyright-inverse">
      <p>WEBSITE BY Creation Studio</p>
      <p>© 2018. All RIGHT RESERVED.</p>
      <div class="social">
        <a class="btn btn-icon btn-pure" href="javascript:void(0)">
        <i class="icon bd-twitter" aria-hidden="true"></i>
      </a>
        <a class="btn btn-icon btn-pure" href="javascript:void(0)">
        <i class="icon bd-facebook" aria-hidden="true"></i>
      </a>
        <a class="btn btn-icon btn-pure" href="javascript:void(0)">
        <i class="icon bd-google-plus" aria-hidden="true"></i>
      </a>
      </div>
    </footer>
  </div>
</div>
<!-- End Page -->

<?php
include ('../includes/scripts.php')
 ?>
 <script>
   (function(document, window, $){
     'use strict';

     var Site = window.Site;
     $(document).ready(function(){
       Site.run();
     });
   })(document, window, jQuery);
 </script>

</body>
</html>
