<?php
    session_start(); 

    // Check if the user is logged in (based on session variable)
    if (!isset($_SESSION['username'])) {
        // If not logged in, redirect to the homepage or login page
        header("Location: ../html/homepage.html"); // Replace with your homepage URL if it's not "index.php"
        exit; // Stop further code execution to ensure the redirect works
    }

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Collect the data from the form
        $departure = htmlspecialchars($_POST['departure']);
        $destination = htmlspecialchars($_POST['destination']);
        $date = htmlspecialchars($_POST['date']);
        $passengersInt = htmlspecialchars($_POST['passengers']);
        $passengersListJson = json_encode([]); // Initialize an empty list for passengers

        // Get the username from the session (assuming the user is logged in)
        $username = $_SESSION['username']; // This will hold the logged-in user's username

        // Database connection details
        $host = 'localhost';
        $dbUsername = 'root'; // Your MySQL username
        $dbname = 'profiles'; // The name of your database

        try {
            // Create a PDO connection
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbUsername);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare SQL query to insert ride details into the database
            $sql = "INSERT INTO rides (driver, origin, destination, rideDate, passengersInt, passengersList)
                    VALUES (:driver, :origin, :destination, :rideDate, :passengersInt, :passengersList)";

            // Prepare the statement
            $stmt = $pdo->prepare($sql);

            // Bind parameters to the query
            $stmt->bindParam(':driver', $username); 
            $stmt->bindParam(':origin', $departure);
            $stmt->bindParam(':destination', $destination);
            $stmt->bindParam(':rideDate', $date);
            $stmt->bindParam(':passengersInt', $passengersInt);
            $stmt->bindParam(':passengersList', $passengersListJson);

            // Execute the query
            if ($stmt->execute()) {
                echo "Ride posted successfully!"; // Provide feedback
                // Redirect to the profile page
                header('Location: profile.php');
                exit;
            } else {
                echo "Something went wrong. Please try again."; // Handle failure
            }

        } catch (PDOException $e) {
            // Handle any errors (e.g., database connection failure)
            echo "Error: " . $e->getMessage();
        }
    }
?>

<!DOCTYPE html>
<html>
    <title>Post a ride</title>

    <head>
        <link rel="stylesheet" href="habibiStyles.css">
    </head>

    <body>
        <div id="postRideForm">

            <form action="postRide.php" method="POST">
                <fieldset>
                    <legend>Enter Ride Details</legend>

                    Source<br>
                    <input type="text" name="departure" required>
                    </br>

                    Destination<br>
                    <input type="text" name="destination" required>
                    </br>

                    Departure Time<br>
                    <input type="datetime-local" name="date" required>
                    </br>

                    Passengers<br>
                    <input type="number" name="passengers" required>
                    </br>

                    <input type="submit">

                </fieldset>
            </form>
        </div>
        <button onclick="window.location.href='profile.php'">Back</button></body>
    </body>
</html>
