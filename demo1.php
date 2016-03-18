<?php

define('INCLUDE_CHECK',true);

require 'connect.php';
require 'functions.php';
// Those two files can be included only if INCLUDE_CHECK is defined


session_name('tzLogin');
// Starting the session

session_set_cookie_params(2*7*24*60*60);
// Making the cookie live for 2 weeks

session_start();

if (isset($_SESSION["id"]))
{
	$session_id = $_SESSION["id"];
}
else
{
	$session_id = null;
}

if (isset($_SESSION["usr"]))
{
	$session_usr = $_SESSION["usr"];
}
else
{
	$session_usr = null;
}

if (isset($_SESSION["msg"]))
{
	$session_msg = $_SESSION["msg"];
}
else
{
	$session_msg = null;
}

if (isset($_POST["submit"]))
{
	$submit = $_POST["submit"];
}
else
{
	$submit = null;
}



if($session_id && !isset($_COOKIE['tzRemember']) && !$_SESSION['rememberMe'])
{
	// If you are logged in, but you don't have the tzRemember cookie (browser restart)
	// and you have not checked the rememberMe checkbox:

	$_SESSION = array();
	session_destroy();
	
	// Destroy the session
}


if(isset($_GET['logoff']))
{
	$_SESSION = array();
	session_destroy();
	
	header("Location: demo.php");
	exit;
}

if($submit=='Login')
{
	// Checking whether the Login form has been submitted
	
	$err = array();
	// Will hold our errors
	
	
	if(!$_POST['username'] || !$_POST['password'])
		$err[] = 'All the fields must be filled in!';
	
	if(!count($err))
	{
		$username = mysql_real_escape_string($_POST['username']);
		$pass = mysql_real_escape_string($_POST['password']);
		$remember = (int)$_POST['rememberMe'];
		
		// Escaping all input data

		$pick="SELECT * FROM accounts where username = '{$username}' limit 1";
		$res = mysqli_query($link,$pick);	
		
		if(!$res)
		{
			$err[] = "No User exists with this Username!";
		}// .mysqli_error($con));
		
		else 
		{
			$row = mysqli_fetch_assoc($res);
			if (!($row["password"] == $pass))
			{
				$err[] = "Wrong Password Entered";
			}
			else
			{
				$_SESSION["usr"] = $row["username"];
			}
		}

	}
	
	if($err)
	$_SESSION['msg']['login-err'] = implode('<br />',$err);
	// Save the error messages in the session

	header("Location: demo.php");
	exit;
}
else if($submit=='Register')
{
	// If the Register form has been submitted
	
	$err = array();
	
	if(strlen($_POST['username'])<4 || strlen($_POST['username'])>32)
	{
		$err[]='Your username must be between 3 and 32 characters!';
	}
	
	if(preg_match('/[^a-z0-9\-\_\.]+/i',$_POST['username']))
	{
		$err[]='Your username contains invalid characters!';
	}
	
	if(!checkEmail($_POST['email']))
	{
		$err[]='Your email is not valid!';
	}
	
	if(!count($err))
	{
		// If there are no errors
		
		$email = mysql_real_escape_string($_POST['email']);
		$user = mysql_real_escape_string($_POST['username']);
		$pass = mysql_real_escape_string($_POST['password']);
		$mobile = mysql_real_escape_string($_POST['mobile']);
		$location = mysql_real_escape_string($_POST['location']);
		$gender = mysql_real_escape_string($_POST['gender']);
		// Escape the input data

		//Chk for Existing Username
		$query = "select * from accounts";
		$r = mysqli_query($link, $query);
		if (!$r) { die("Database query failed"); }
		$counted = mysqli_num_rows($r);
		$free = "free"; // free to enter value in db
		$query = null;
		$r = null;

		for ($count = 1; $count<=$counted; $count++)
		{
			$query = "select * from accounts where id = '{$count}'";
			$r = mysqli_query($link, $query);
			if (!$r) { die("Database query failed"); }
			$usercheck = mysqli_fetch_assoc($r);

			if ($usercheck["username"] == $user)
			{
				$free = null;
				$err[]='This username is already taken!';
				break 1;
			}

			if ($usercheck["email"] == $email)
			{
				$free = null;
				$err[]='This email address already has a username!';
				break 1;
			}

			if ($usercheck["mobile"] == $mobile)
			{
				$free = null;
				$err[]='This mobile number has already been registered!';
				break 1;
			}
		}

		

		$query = null;
		// Escape the input data
		if ($free === "free")	// free to enter values in database
		{	
			$query = "insert into accounts ( ";
			$query .= "username, email, password, location, gender, mobile";
			$query .= ") values ( ";
			$query .= "'{$user}', '{$email}', '{$pass}', '{$location}', '{$gender}', '{$mobile}')";

			$result = mysqli_query($link, $query);
		
			if(mysqli_affected_rows($link))
			{
			//send_mail(	'demo-test@tutorialzine.com', $_POST['email'], 'Registration System Demo - Your New Password', 'Your password is: '.$pass);

				$_SESSION['msg']['reg-success']='';
				$err[] = null;
			}
		}
	}

	if(count($err))
	{
		$_SESSION['msg']['reg-err'] = implode('<br />',$err);
	}	
	
	header("Location: demo.php");
	exit;
}

$script = '';

if($session_msg)
{
	// The script below shows the sliding panel on page load
	
	$script = '
	<script type="text/javascript">
	
		$(function(){
		
			$("div#panel").show();
			$("#toggle a").toggle();
		});
	
	</script>';
	
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Smart ScrapBook</title>

	<link rel="stylesheet" type="text/css" href="css/slide.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="css/superslides.css" media="screen"/>
    
    <link rel="stylesheet" type="text/css" href="demo.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="login_panel/css/slide.css" media="screen" />

    <script type="text/javascript" src="js/jquery.min.js"></script>

    <script src="js/slide.js" type="text/javascript"></script>
    <script src="js/jquery.superslides.min.js" type="text/javascript"></script>
    <script src="js/jquery.animate-enhanced.min.js" type="text/javascript"></script>
    
    <!-- PNG FIX for IE6 -->
    <!-- http://24ways.org/2007/supersleight-transparent-png-in-ie6 -->
    <!--[if lte IE 6]>
        <script type="text/javascript" src="login_panel/js/pngfix/supersleight-min.js"></script>
    <![endif]-->
    
    <script src="login_panel/js/slide.js" type="text/javascript"></script>
    
    <?php echo $script; ?>
</head>

<body>

	<div style="background-color:#CCC ; position:relative; height:100px; width:auto; opacity:0.1"></div>

<div id="slides">
    <div class="slides-container">
   
      <img src="images/slide_02.jpg" alt="Cinelli" height="200">
      <img src="images/slide_04.jpg" width="1024" height="200" alt="Surly">
      <img src="images/cinelli-front.jpeg" width="1024" height="683" alt="Cinelli">
      <img src="images/affinity.jpeg" width="1024" height="685" alt="Affinity">
  
    </div>


    <nav class="slides-navigation">
      <a href="#" class="next">Next</a>
      <a href="#" class="prev">Previous</a>
    </nav>
 </div>

<!-- Panel -->
<div id="toppanel">
	<div id="panel">
		<div class="content clearfix">
			<div class="left">
				<h1>Smart ScrapBook</h1>
				<h2>Where you meet yourself</h2>
				<br /> <br />
				<a href="registered.php">View a special member page</a>

				<br /> <br /> <br /> <br /> 
				<a href="logout.php">Log Out</a>
			</div>
            
            
            <?php
			
			if(!$session_id):
			
			?>
            
			<div class="left">
				<!-- Login Form -->
				<form class="clearfix" action="" method="post">
					<h1>Member Login</h1>
                    
                    <?php
						
						if(isset($_SESSION['msg']['login-err']))
						{
							echo '<div class="err">'.$_SESSION['msg']['login-err'].'</div>';
							unset($_SESSION['msg']['login-err']);
						}
					?>
					
					<label class="grey" for="username">Username:</label>
					<input class="field" type="text" name="username" id="username" value="" size="23" />
					<label class="grey" for="password">Password:</label>
					<input class="field" type="password" name="password" id="password" size="23" />
	            	<label><input name="rememberMe" id="rememberMe" type="checkbox" checked="checked" value="1" /> &nbsp;Remember me</label>
        			<div class="clear"></div>
					<input type="submit" name="submit" value="Login" class="bt_login" />
				</form>
			</div>
			<div class="left right">			
				<!-- Register Form -->
				<form action="" method="post">
					<h1>Not a member yet? Sign Up!</h1>		
                    
                    <?php
						
						if(isset($_SESSION['msg']['reg-err']))
						{
							echo '<div class="err">'.$_SESSION['msg']['reg-err'].'</div>';
							unset($_SESSION['msg']['reg-err']);
						}
						
						if(isset($_SESSION['msg']['reg-success']))
						{
							echo '<div class="success">'.$_SESSION['msg']['reg-success'].'</div>';
							unset($_SESSION['msg']['reg-success']);
						}
					?>
                    		
					<label class="grey" for="username">Username:</label>
					<input class="field" type="text" name="username" id="username" value="" size="23" />
					
					<label class="grey" for="password">Password:</label>
					<input class="field" type="password" name="password" id="password" size="23" />

					<label class="grey" for="email">Email:</label>
					<input class="field" type="email" name="email" id="email" size="23" />

					<label class="grey" for="location">Location:</label>
					<input class="field" type="text" name="location" id="location" size="23" />

					<label class="grey" for="mobile">Mobile:</label>
					<input class="field" type="number" name="mobile" id="mobile" size="23" />

					<br>
					<input type="radio" name="gender" value="male">Male
                    <input type="radio" name="gender" value="female">Female
                    <br>

					<label>Your password will be e-mailed to you.</label>
					<input type="submit" name="submit" value="Register" class="bt_register" />
				</form>
			</div>
            
            <?php
			
			else:
			
			?>
            
            <div class="left">
            
            <h1>Members panel</h1>
            
            <p>You can put member-only data here</p>
            <a href="registered.php">View a special member page</a>
            <p>- or -</p>
            <a href="?logoff">Log off</a>
            
            </div>
            
            <div class="left right">
            </div>
            
            <?php
			endif;
			?>
		</div>
	</div> <!-- /login -->	

    <!-- The tab on top -->	
	<div class="tab">
		<ul class="login">
	    	<li class="left">&nbsp;</li>
	        <li>Hello <?php echo $session_usr ? $session_usr : 'Guest';?>!</li>
			<li class="sep">|</li>
			<li id="toggle">
				<a id="open" class="open" href="#"><?php echo $session_id ?'Open Panel':'Log In | Register';?></a>
				<a id="close" style="display: none;" class="close" href="#">Close Panel</a>			
			</li>
	    	<li class="right">&nbsp;</li>
		</ul> 
	</div> <!-- / top -->
	
</div> <!--panel -->

<script>
    $(function() {
      $('#slides').superslides({
        hashchange: true,
        play: 2000
      });

      $('#slides').on('mouseenter', function() {
        $(this).superslides('stop');
        console.log('Stopped')
      });
      $('#slides').on('mouseleave', function() {
        $(this).superslides('start');
        console.log('Started')
      });
    });
  </script>

</body>
</html>
