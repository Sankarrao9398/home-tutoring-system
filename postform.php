<?php
include("inc/connection.inc.php");

ob_start();
session_start();
if (!isset($_SESSION['user_login'])) {
    $user = "";
    $utype_db = "";
} else {
    $user = $_SESSION['user_login'];
    $result = $con->query("SELECT * FROM user WHERE id='$user'");
    $get_user_name = $result->fetch_assoc();
    $uname_db = $get_user_name['fullname'];
    $utype_db = $get_user_name['type'];
}

// Declaring variables
$f_loca = "";
$f_class = "";
$f_dead = "";
$f_sal = "";
$f_sub = "";
$f_uni = "";
$f_medi = "";

if (isset($_SESSION['u_post'])) {
    $f_loca = $_SESSION['f_loca'];
    $f_dead = $_SESSION['f_dead'];
    $f_sal = $_SESSION['f_sal'];
    $f_uni = $_SESSION['f_uni'];
}

// Posting
if (isset($_POST['submit'])) {
    $f_loca = $_POST['location'];
    $f_dead = $_POST['deadline'];
    $f_sal = $_POST['sal_range'];

    // Create session for all fields
    $_SESSION['f_loca'] = $f_loca;
    $_SESSION['f_class'] = $f_class;
    $_SESSION['f_dead'] = $f_dead;
    $_SESSION['f_sal'] = $f_sal;
    $_SESSION['f_uni'] = $f_uni;

    try {
        if (empty($_POST['sub_list'])) {
            throw new Exception('Subject cannot be empty');
        }
        if (empty($_POST['class_list'])) {
            throw new Exception('Class cannot be empty');
        }
        if (empty($_POST['medium_list'])) {
            throw new Exception('Medium cannot be empty');
        }
        if (empty($_POST['sal_range'])) {
            throw new Exception('Salary range cannot be empty');
        }
        if (empty($_POST['location'])) {
            throw new Exception('Location cannot be empty');
        }

        if (($user != "") && ($utype_db != "teacher")) {
            $d = date("Y-m-d");
            $sublist = implode(',', $_POST['sub_list']);
            $classlist = implode(',', $_POST['class_list']);
            $mediumlist = implode(',', $_POST['medium_list']);
            $result = $con->query("INSERT INTO post (postby_id, subject, class, medium, salary, location, p_university, deadline) VALUES ('$user', '$sublist', '$classlist', '$mediumlist', '$_POST[sal_range]', '$_POST[location]', '$_POST[p_university]', '$_POST[deadline]')");

            // Success message
            $success_message = '
            <div class="signupform_content">
                <h2><font face="bookman">Post successful!</font></h2>
                <div class="signupform_text" style="font-size: 18px; text-align: center;"></div>
            </div>';

            // Destroy all sessions
            session_destroy();
            session_start();
            $_SESSION['user_login'] = $user;
            header("Location: index.php");
        } else {
            $_SESSION['u_post'] = "post";
            header("Location: login.php");
        }
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}

// Get subject list
include_once("inc/listclass.php");
$list_check = new checkboxlist();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Make Post</title>
    <link rel="stylesheet" type="text/css" href="css/post.css">
    <link rel="stylesheet" type="text/css" href="css/Navbar.css">
    <style>
        .sb, .cls, .md {
            color: rgb(16,17,16);
        }
    </style>
</head>
<body class="body1" style="background:fixed url(./image/bg1.jpeg.jpg); background-size: 100%;">
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
            if ($utype_db == "teacher") {
                echo '<li><a href="index.php">Students</a></li>';
            } else {
                echo '<li><a class="navlink" href="postform.php">Post</a></li>';
                echo '<li><a href="search.php">Search Tutor</a></li>';
              
            }
            ?>
            <?php
            if ($user != "") {
                $resultnoti = $con->query("SELECT * FROM applied_post WHERE post_by='$user' AND student_ck='no'");
                $resultnoti_cnt = $resultnoti->num_rows;
                if ($resultnoti_cnt == 0) {
                    $resultnoti_cnt = "";
                } else {
                    $resultnoti_cnt = '(' . $resultnoti_cnt . ')';
                }
                echo '<div class="btn"><li><a href="notification.php"><button class="join-button">Notification' . $resultnoti_cnt . '</button></a>
                <a href="profile.php?uid=' . $user . '"><button class="join-button">' . $uname_db . '</button></a>
                <a href="logout.php"><button class="join-button">Logout</button></a>';
            } else {
                echo '<a href="login.php"><button class="join-button">Login</button></a>
                <a href="registration.php"><button class="join-button">Register</button></a></div></li>';
            }
            ?>
        </ul>
    </nav>
</header>
<div class="post" style="height: 120px;"></div>
<center>
    <div class="post_form" style="background-color: rgba(17, 207, 245, 0.832);">
        <h1>Make Your Post</h1>
        <?php
        echo '<div class="signup_error_msg">';
        if (isset($error_message)) {
            echo $error_message;
        }
        echo '</div>';
        ?>
        <form action="#" method="post">
            <p>You can Select More Than One Subject</p>
            <div class="subject">
                <h3>Subject</h3>
                <div class="sb">
                    <?php $list_check->sublist(); ?>
                </div>
            </div>
            <div class="class">
                <h3>Class</h3>
                <div class="cls">
                    <?php $list_check->classlist(); ?>
                </div>
            </div>
            <div class="medium">
                <h3>Medium</h3>
                <div class="md">
                    <?php $list_check->mediumlist(); ?>
                </div>
            </div>
            <div class="salary">
                <h3>Select Salary</h3>
                <div class="slr">
                    <select name="sal_range">
                        <?php if ($f_sal != "") echo '<option value="' . $f_sal . '">' . $f_sal . '</option>'; ?>
                        <option value="None">None</option>
                        <option value="1000-2000">1000-2000</option>
                        <option value="2000-5000">2000-5000</option>
                        <option value="5000-10000">5000-10000</option>
                        <option value="10000-15000">10000-15000</option>
                        <option value="15000-25000">15000-25000</option>
                    </select>
                </div>
                <div class="location">
                    <h3>Location</h3>
                    <div class="lc">
                        <?php echo '<input type="text" id="location" name="location" value="' . $f_loca . '" placeholder="Rajam, Andhra Pradesh">'; ?>
                    </div>
                </div>
                <div class="deadline">
                    <h3>Deadline till you need</h3>
                    <div class="dl">
                        <p><?php echo '<input name="deadline" type="date" id="datepicker" value="' . $f_dead . '">'; ?></p>
                    </div>
                </div>
                <input type="submit" name="submit" class="sub_button" value="Post" />
            </div>
        </form>
    </div>
</center>
<script src="./js/script.js"></script>
</body>
</html>