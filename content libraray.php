<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <!-- Bootstrap CSS Library -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <style>
        body {
            background: url('./image/bg1.jpeg.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
        }

        /* Navbar */
        .navbar {
            background-color: rgba(255, 255, 255, 0.9);
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Custom styling for the login form */
        .login-container {
            margin-top: 50px;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Button Hover Animation */
        .btn-primary {
            transition: background-color 0.3s, transform 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<!-- Navigation bar with Bootstrap -->
<nav class="navbar navbar-expand-lg navbar-light">
    <a class="navbar-brand" href="#">
        <img src="./image/logo.jpg.png" alt="Logo Image" width="100">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" href="index.php">Students</a></li>
            <li class="nav-item"><a class="nav-link" href="postform.php">Post</a></li>
            <li class="nav-item"><a class="nav-link" href="search.php">Search Tutor</a></li>
        </ul>
    </div>
</nav>

<!-- Login Form Container -->
<div class="container d-flex align-items-center justify-content-center">
    <div class="login-container">
        <form action="" method="post" onsubmit="return validatePassword()">
            <h2 class="text-center mb-4">Log In to Your Account</h2>
            <div class="form-group">
                <input type="email" name="email" id="email" class="form-control" placeholder="Enter your E-Mail Address" required>
            </div>
            <div class="form-group">
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password" required>
            </div>
            <button type="submit" name="login" id="login" class="btn btn-primary btn-block">Log in</button>
            <small id="error-message" class="form-text text-danger text-center mt-3"></small>
            <div class="text-center mt-3">
                <a href="registration.php">Create New Account</a>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Password validation function
    function validatePassword() {
        const password = document.getElementById('password').value;
        const errorMessage = document.getElementById('error-message');
        
        const strongPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        
        if (!strongPassword.test(password)) {
            errorMessage.textContent = "⚠️ Password must contain at least one uppercase letter, one lowercase letter, one number, one special character, and be at least 8 characters long.";
            return false;
        }
        return true;
    }
</script>

</body>
</html>
