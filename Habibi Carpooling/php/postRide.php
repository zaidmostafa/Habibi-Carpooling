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

		    // Get the username from the session (assuming the user is logged in)
		    $userName = $_SESSION['username']; // This will hold the logged-in user's username

		    // Database connection details
		    $host = 'localhost';
		    $dbUsername = 'root'; // Your MySQL username
		    $dbPassword = ''; // Your MySQL password (default is blank for WAMP)
		    $dbname = 'profiles'; // The name of your database

		    try {
		        // Create a PDO connection
		        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbUsername, $dbPassword);
		        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		        // Prepare SQL query to insert ride details into the database
		        $sql = "INSERT INTO rides (posted_by, from_location, to_location, ride_date)
		                VALUES (:posted_by, :from_location, :to_location, :ride_date)";

		        // Prepare the statement
		        $stmt = $pdo->prepare($sql);

		        // Bind parameters to the query
		        $stmt->bindParam(':posted_by', $userName); // Bind the logged-in user's username, not 'root'
		        $stmt->bindParam(':from_location', $departure);
		        $stmt->bindParam(':to_location', $destination);
		        $stmt->bindParam(':ride_date', $date);

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
	<title>
		Post a ride
	</title>

	<head>
		<link rel = "stylesheet" href = "habibiStyles.css">
	</head>

	<body>
		<div id = "postRideForm">

			<form action = postRide.php method = "POST">
				<fieldset>
					<legend>Enter Ride Details</legend>

					Source<br>
					<input type = "text" name = "departure" required>
					</br>

					Destination<br>
					<input type = "text" name = "destination" required>
					</br>

					Departure Time<br>
					<input type = "datetime-local" name = "date" required>
					</br>

					<!--<br>Contact Information</br>
					Email<br>
					<input type = "email" name = "email" required>
					</br>
					Telephone<br>
					<input type = "tel" name = "telephone" required>
					</br>-->

					<input type = 'submit'>

				</fieldset>
			</form>
		</div>
	</body>
</html>