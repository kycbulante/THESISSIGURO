<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
    
<head>
        <title>WEB-BASED TIMEKEEPING AND PAYROLL SYSTEM USING FINGERPRINT BIOMETRICS</title><meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="css/bootstrap.min.css" />
		<link rel="stylesheet" href="css/bootstrap-responsive.min.css" />
        <link rel="stylesheet" href="css/maruti-login.css" />
</head>


    <body>
        <center><br>
            <h1>WEB-BASED TIMEKEEPING AND PAYROLL SYSTEM USING FINGERPRINT BIOMETRICS</h1>
            <h2><small>WEB-BASED TIMEKEEPING AND PAYROLL SYSTEM USING FINGERPRINT BIOMETRICS</small></h2>

            <br>
        <div id="loginbox">            
            
            <form method = "post" id="loginform" class="form-vertical" action="LoginControl.php">
				 <div class="control-group normal_text"> <h3>Login</h3></div>
                 
                 <?php
                            if (isset($_SESSION['status'])) {
                            echo $_SESSION['status'];
                            unset($_SESSION['status']);
                            }
                            ?>
                
               <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                        

                            
                           
                        <span class="add-on"><i class="icon-user"></i></span><input type="text" placeholder="Username" id="admID" name="adminUser" autofocus="autofocus"/>
                            
                        </div>
                    </div>
                </div>

                
                <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                        <span class="add-on"><i class="icon-lock"></i></span><input type="password" id="admPASS" name="adminPass" placeholder="Password" />
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <span class="pull-left"><a href="#" class="flip-link btn btn-inverse" id="to-recover">Forgot password?</a></span>
                    <span class="pull-right"><input style="background:#0c5383;" type="submit" class="btn btn-success" value="Login" name="login_btn" /></span>
                </div>
            </form>
            

            
            <form id="recoverform" action="#" class="form-vertical">
				<p class="normal_text">Enter your e-mail address below and we will send you instructions how to recover a password.</p>
				
                    <div class="controls">
                        <div class="main_input_box">
                            <span class="add-on"><i class="icon-envelope"></i></span><input type="text" placeholder="E-mail address" />
                        </div>
                    </div>
               
                <div class="form-actions">
                    <span class="pull-left"><a href="#" class="flip-link btn btn-inverse" id="to-login">&laquo; Back to login</a></span>
                    <span class="pull-right"><input type="submit" class="btn btn-info" value="Recover" /></span>
                </div>
            </form>
         
         
        </div>
        
        <script src="js/jquery.min.js"></script>  
        <script src="js/maruti.login.js"></script> 
    </body>

</html>
