<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="habibiStyles.css"> <!-- Your stylesheet -->
</head>
<body>
    <div id="login">
        <h1>Login</h1>
        
        <!-- Display error message if exists -->
        <?php if (!empty($errorMessage)): ?>
            <div class="error-message">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="login.php" method="POST">
            <fieldset>
                <legend>Enter Your Login Details</legend>

                Username<br>
                <input type="text" name="username" required><br><br>

                Password<br>
                <input type="password" name="password" required><br><br>

                <input type="submit" value="Login">
            </fieldset>
        </form>

        <!--Allow the user to sign up, if they don't have an account-->
        <p>Don't have an account? <a href="signUp.php">Sign up here</a>.</p>
    </div>
</body>
</html>

<?php
// Start the session to handle user login status
session_start();

    //To access the database 'profiles', with WAMP
    $host = 'localhost'; 
    $dbname = 'profiles'; 
    $username = 'root'; 

    try {
        // Create a PDO connection
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }

    // Initialize error message
    $errorMessage = "";

    // Handle the form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitize user input
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);

        // Validate input
        if (empty($username) || empty($password)) {
            $errorMessage = "Please enter both username and password.";
        } else {
            // Query the database for the user by username
            $sql = "SELECT username, password FROM users WHERE username = :username";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            // Check if user exists
            if ($stmt->rowCount() > 0) {
                // Fetch the user's record
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Verify the password
                if ($password == $user['password']) {
                    // Password is correct, start the session and log the user in
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];

                    // Redirect to the homepage or dashboard
                    header('Location: profile.php');
                    exit;
                } else {
                    $errorMessage = "Invalid username or password.";
                }
            } else {
                $errorMessage = "Invalid username or password.";
            }
        }
    }
?>


