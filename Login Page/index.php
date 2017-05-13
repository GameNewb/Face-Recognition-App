<?php 
/* programming_project_2.php
 * Kyle del Castillo
 * Saira Montermoso
 * Luis Rios
 * CS 160
 * Facial Recognition
 */
    /* Main page with two forms: sign up and log in */
    require 'CreateDb.php';
    session_start();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Technotronix</title>
  <script src="https://use.fontawesome.com/3feed0bc38.js"></script>
  <?php include 'css/css.html';?>
</head>
    
    <?php 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') 
    {
        if (isset($_POST['login'])) { //user logging in

            require 'login.php'; // require the login file

        }

        elseif (isset($_POST['register'])) { //user registering

            require 'register.php'; // require the register file

        }
    }
    ?>

    <body>
        
        <div class="banner">
            <a href="/">App Name</a>
        </div>
        <div class="form">
            <img src="css/image/login.png">
            <ul class="tab-group">
                <li class="tab active"><a href="#signup">Sign Up</a></li>
                <li class="tab"><a href="#login">Login</a></li>
            </ul>
            
            <div class="tab-content">
                
                <div id="signup">
                    <h1>Sign Up for Free</h1>
          
                      <form action="index.php" method="post" autocomplete="off">
                          
                          <div class="top-row">
                            <div class="field-wrap">
                              <i class="fa fa-user-o" aria-hidden="true"></i>
                              <label>
                                First Name<span class="req">*</span>
                              </label>
                             
                              <input type="text" required autocomplete="off" name='firstname' />
                            </div>

                            <div class="field-wrap">
                              <i class="fa fa-user-o" aria-hidden="true"></i>
                              <label>
                                Last Name<span class="req">*</span>
                              </label>
                              <input type="text" required autocomplete="off" name='lastname' />
                            </div>
                          </div>

                          <div class="field-wrap">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                            <label>
                              Email Address<span class="req">*</span>
                            </label>
                            <input type="email" required autocomplete="off" name='email' />
                          </div>
                          
                          <div class="field-wrap">
                            <i class="fa fa-id-card" aria-hidden="true"></i>
                            <label>
                              Username <span class="req">*</span>
                            </label>
                            <input type="username" required autocomplete="off" name='username' />
                          </div>

                          <div class="field-wrap">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                            <label>
                              Set A Password<span class="req">*</span>
                            </label>
                            <input type="password" required autocomplete="off" name='password'/>
                          </div>

                          <button type="submit" class="button button-block" name="register" />Register</button>

                      </form>
                </div>
            
                <div id="login">   
                  <h1>Welcome Back!</h1>

                      <form action="index.php" method="post" autocomplete="off">

                        <div class="field-wrap">
                            <i class="fa fa-id-card" aria-hidden="true"></i>
                            <label>
                              E-mail Address<span class="req">*</span>
                            </label>
                            <input type="email" required autocomplete="off" name="email"/>
                        </div>

                      <div class="field-wrap">
                          <i class="fa fa-lock" aria-hidden="true"></i>
                          <label>
                              Password<span class="req">*</span>
                          </label>
                          <input type="password" required autocomplete="off" name="password"/>
                      </div>

                      <p class="forgot"><a href="forgot.php">Forgot Password?</a></p>

                      <button class="button button-block" name="login" />Log In</button>

                      </form>
                </div>
        
            </div> <!-- tab-content -->
        </div> <!-- /form -->
        <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
        <script src="js/index.js"></script>
    </body>
</html> 
