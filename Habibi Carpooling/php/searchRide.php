<?php
	session_start(); // Ensure session is started to get the logged-in user
		 // Check if the user is logged in (based on session variable)
	    if (!isset($_SESSION['username'])) {
	        // If not logged in, redirect to the homepage or login page
	        header("Location: ../html/homepage.html"); // Replace with your homepage URL if it's not "index.php"
	        exit; // Stop further code execution to ensure the redirect works
	    }
	

	$userName = $_SESSION['username']; // Get the logged-in username

	// Database connection details
	$host = 'localhost';
	$username = 'root'; // Your MySQL username
	$password = '';     // Your MySQL password (default is blank for WAMP)
	$dbname = 'profiles'; // The name of your database

	// Check if the form is submitted to join a ride
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ride_id'])) {
	    $rideId = $_POST['ride_id']; // Ride ID of the ride the user wants to join

	    try {
	        // Create a PDO connection
	        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
	        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	        // Check if the ride is already taken or not
	        $checkRide = $pdo->prepare("SELECT user_taken FROM rides WHERE ride_id = :ride_id");
	        $checkRide->bindParam(':ride_id', $rideId);
	        $checkRide->execute();
	        $ride = $checkRide->fetch(PDO::FETCH_ASSOC);

	        if ($ride && $ride['user_taken']) {
	            echo "Sorry, this ride has already been taken.";
	        } else {
	            // Update the ride to set the current user as the one taking the ride
	            $sql = "UPDATE rides SET user_taken = :user_taken WHERE ride_id = :ride_id";

	            $stmt = $pdo->prepare($sql);
	            $stmt->bindParam(':user_taken', $userName);
	            $stmt->bindParam(':ride_id', $rideId);

	            if ($stmt->execute()) {
	                echo "You have successfully joined the ride!";
	            } else {
	                echo "Something went wrong. Please try again.";
	            }
	        }
	    } catch (PDOException $e) {
	        echo "Error: " . $e->getMessage();
	    }
	}

	// Get available rides (you can customize this query as per your requirements)
	try {
	    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	    $stmt = $pdo->prepare("SELECT ride_id, from_location, to_location, ride_date, user_taken FROM rides WHERE user_taken IS NULL");
	    $stmt->execute();
	    $rides = $stmt->fetchAll(PDO::FETCH_ASSOC);
	} catch (PDOException $e) {
	    echo "Error: " . $e->getMessage();
	}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search for Rides</title>
</head>
<body>
    <h1>Available Rides</h1>

    <?php if ($rides): ?>
        <table>
            <tr>
                <th>From</th>
                <th>To</th>
                <th>Departure Time</th>
                <th>Action</th>
            </tr>
            <?php foreach ($rides as $ride): ?>
                <tr>
                    <td><?php echo htmlspecialchars($ride['from_location']); ?></td>
                    <td><?php echo htmlspecialchars($ride['to_location']); ?></td>
                    <td><?php echo htmlspecialchars($ride['ride_date']); ?></td>
                    <td>
                        <?php if (!$ride['user_taken']): ?>
                            <form action="searchRide.php" method="POST">
                                <input type="hidden" name="ride_id" value="<?php echo $ride['ride_id']; ?>">
                                <input type="submit" value="Join Ride">
                            </form>
                        <?php else: ?>
                            <span>Ride Taken</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No rides available.</p>
    <?php endif; ?>
</body>
</html>


