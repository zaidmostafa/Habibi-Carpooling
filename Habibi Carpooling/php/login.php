<?php
    // Start the session to handle user login status
    session_start();

    // Database connection details
    $host = 'localhost'; // Database host
    $dbname = 'profiles'; // Database name
    $myUsername = 'root'; // Database username
    $myPassword = ''; // Database password

    // Create a MySQLi connection & check for connection error
    $conn = new mysqli($host, $myUsername, $myPassword, $dbname);
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // Initialize error message
    $errorMessage = "";

    // Handle the form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        // Sanitize user input
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);

        // Get the database for the user by username
        $sql = "SELECT username, password FROM users WHERE username = ?";

        // Prepare the SQL statement
        if ($stmt = $conn->prepare($sql)) {
            // Bind the username parameter to the query
            $stmt->bind_param("s", $username);

            // Execute the query
            $stmt->execute();

            // Bind result variables
            $stmt->bind_result($db_username, $db_password);

            // Check if the user exists and fetch the result
            if ($stmt->fetch()) {
                // Verify the password
                if ($password == $db_password) {
                    // Password is correct, start the session and log the user in
                    $_SESSION['username'] = $db_username;

                    // Redirect to the homepage or dashboard
                    header('Location: profile.php');
                    exit;
                } else {
                    $errorMessage = "Invalid username or password.";
                }
            } else {
                $errorMessage = "Invalid username or password.";
            }

            // Close the statement
            $stmt->close();
        } else {
            $errorMessage = "Error preparing statement: " . $conn->error;
        }
        
    }

    // Close the database connection
    $conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="habibiStyles.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Sour+Gummy:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Display the error message if there is one -->
    <?php if (!empty($errorMessage)): ?>
        <div id="warning" style="color: red;">
            <?php echo $errorMessage; ?>
        </div>
    <?php endif; ?>

    <div id="login">
        

        <!-- Login Form -->
        <form id="loginForm" action="login.php" method="POST">
            <fieldset>
                <legend>Enter Your Login Details</legend>

                <!-- <label id="textid">Username</label><br> -->
                <input type ="text" id="text" name="username" placeholder="Username" required><br><br>

                <!-- <label id="textid">Password</label><br> -->
                <input type="password" id="text" name="password" placeholder="Password" required><br><br>

                <input type="submit" id="loginButton" value="Login">
            </fieldset>
        </form>

        <p>Don't have an account? <a href="signUp.php">Sign up here</a>.</p>
    </div>
</body>
</html>
