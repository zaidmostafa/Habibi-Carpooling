<!DOCTYPE html>
<html>
    <head>
        <title>Sign Up</title>
        <link rel="stylesheet" href="habibiStyles.css">
    </head>

    <body>
        <div id="signup">
            <h1>Sign up details:</h1>

            <!-- Form submission will be handled by PHP below -->
            <form action="signUp.php" method="POST">
                <fieldset>
                    <legend>Enter Your Details</legend>

                    Username<br>
                    <input type="text" name="user" required>
                    <br>

                    Password<br>
                    <input type="password" name="password" required>
                    <br>

                    <br>Contact Information</br>
                    Email<br>
                    <input type="email" name="email" required>
                    <br>
                    Telephone<br>
                    <input type="tel" name="telephone" required>
                    <br>

                    <input type="submit" value="Sign Up">
                </fieldset>
            </form>
        </div>
    </body>
</html>

<?php
                // Check if the form is submitted
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    // Sanitize input to prevent XSS or other injection attacks
                    $user = htmlspecialchars($_POST['user']);
                    $password = htmlspecialchars($_POST['password']);
                    $email = htmlspecialchars($_POST['email']);
                    $telephone = htmlspecialchars($_POST['telephone']);

                    // Additional validation (can be customized)
                    if (empty($user) || empty($password) || empty($email) || empty($telephone)) {
                        echo "Please fill in all fields.";
                    } else {
                        // Here you would normally connect to your database to store the data
                        // Example using PDO for database connection

                        try {
                            $host = 'localhost'; // Database host
                            $username = 'root';
                            $dbname = 'profiles'; // Database name
                    

                            // Create a PDO connection
                            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username);
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            // Prepare the SQL query
                            $sql = "INSERT INTO users (username, password, email, telephone) 
                                    VALUES (:user, :password, :email, :telephone)";

                            // Prepare statement
                            $stmt = $pdo->prepare($sql);

                            // Bind values to the query
                            $stmt->bindParam(':user', $user);
                            $stmt->bindParam(':password', $password);
                            $stmt->bindParam(':email', $email);
                            $stmt->bindParam(':telephone', $telephone);

                            // Execute the statement
                            if ($stmt->execute()) {
                                echo "Sign up successful!";
                                header("Location: profile.php");
                                exit;
                            } else {
                                echo "Something went wrong. Please try again.";
                            }
                        } catch (PDOException $e) {
                            // Handle connection errors
                            echo "Error: " . $e->getMessage();
                        }
                    }
                }
            ?>
