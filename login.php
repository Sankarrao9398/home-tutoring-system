<!DOCTYPE html>
<html>
<head>
	<title>Log In</title>
	<link rel="stylesheet" type="text/css" href="css/Navbar.css">
	<link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body style="background:fixed url(./image/bg1.jpeg.jpg); background-size: 100%;">
<header>
<?php
$con = new mysqli('localhost', 'root', '', 'main_db');
if($con->connect_errno > 0){
    die('Unable to connect to database [' . $con->connect_error . ']');
}
?>
<?php
ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
	$utype_db = "";
	$user = "";
} else {
	header("location: index.php");
}
?>
<header>
<nav>
	<div class="logo">
		<img src="./image/logo.jpg.png" alt="Logo Image">
	</div>
	<div class="hamburger">
		<div class="line1"></div>
		<div class="line2"></div>
		<div class="line3"></div>
	</div>
	<ul class="nav-links">
		<?php     
			if($utype_db == "teacher") {
				echo '<li><a href="index.php" >Students</a></li>';
			} else {
				echo '<li><a class="navlink" href="postform.php">Post</a></li>';
				echo '<li><a href="search.php">Search Tutor</a></li>';
			}
		?>
		<?php
			if($user != "") {
				$resultnoti = $con->query("SELECT * FROM applied_post WHERE post_by='$user' AND student_ck='no'");
				$resultnoti_cnt = $resultnoti->num_rows;
				if($resultnoti_cnt == 0) {
					$resultnoti_cnt = "";
				} else {
					$resultnoti_cnt = '('.$resultnoti_cnt.')';
				}
				echo '<div class="btn">
					<li><a href="notification.php">
					<button class="join-button">Notification'.$resultnoti_cnt.'</button></a>
					<a href="profile.php?uid='.$user.'">
					<button class="join-button">'.$uname_db.'</button></a>
					<a href="logout.php">
					<button class="join-button">Logout</button></a></li>';
			} else {
				echo '<a href="login.php"><button class="join-button">Login</button></a>
				<a href="registration.php"><button class="join-button">Register</button></a>';
			}
		?>
		</div>
	</ul>
</nav>
</header>
<div class="login">
	<center>
		<form class="log_form" action="" method="post" onsubmit="return validatePassword()">
			<h2>Log In Your Account</h2>
			<input type="email" name="email" id="email" placeholder="Enter your E-Mail Address" required><br>
			<input type="password" name="password" id="password" placeholder="Enter Password" required><br>
			<input class="sub_button" name="login" id="login" type="submit" value="Log in"><br>
			<h3 id="error-message" style="color:red;"></h3>
			<?php
				error_reporting(0);
				if (isset($_POST['login'])) {
					if (isset($_POST['email']) && isset($_POST['password'])) {
						$user_login = $_POST['email'];
						$user_login = mb_convert_case($user_login, MB_CASE_LOWER, "UTF-8");			
						$password_login = $_POST['password'];
						$password_login_md5 = md5($password_login);
						$result = $con->query("SELECT * FROM user WHERE (email='$user_login') AND pass='$password_login_md5'");
						$num = mysqli_num_rows($result);
						$get_user_email = $result->fetch_assoc();
						$get_user_uname_db = $get_user_email['id'];
						$get_user_type_db = $get_user_email['type'];
						if (mysqli_num_rows($result) > 0) {
							$_SESSION['user_login'] = $get_user_uname_db;
							setcookie('user_login', $user_login, time() + (365 * 24 * 60 * 60), "/");
							$online = 'yes';
							$result = $con->query("UPDATE user SET online='$online' WHERE id='$get_user_uname_db'");
							if($_SESSION['u_post'] == "post") {
								if($get_user_type_db == "teacher") {
									$_REQUEST['teacher'] = "logastchr";
									header('location: checking.php?teacher=logastchr');
								} else {
									header('location: postform.php');
								}
							} elseif ($_REQUEST['pid'] != "") {
								header('location: viewpost.php?pid='.$_REQUEST['pid'].'');
							} else {
								if($get_user_type_db == "teacher") {
									header('location: index.php');
								} else {
									header('location: search.php');
								}
							}
							exit();
						} else {
							echo '<h3 style="color:red;">⚠️Invalid E-mail Or Password Please try again!⚠️</h3>';
						}
					}
				}
			?>
			<a href="registration.php">Create New Account</a><br>
		</form>
	</center>
</div>

<script>
// Password validation function
function validatePassword() {
	const password = document.getElementById('password').value;
	const errorMessage = document.getElementById('error-message');

	// Check for uppercase letter, lowercase letter, number, special character, and minimum length of 8 characters
	const strongPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

	if (!strongPassword.test(password)) {
		errorMessage.textContent = "⚠️ Password must contain at least one uppercase letter, one lowercase letter, one number, one special character, and be at least 8 characters long.";
		return false;
	}
	return true;
}
</script>

<script src="./js/script.js"></script>
</body>
</html>
