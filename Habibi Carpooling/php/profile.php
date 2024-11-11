    <?php
	session_start();
	 // Check if the user is logged in (based on session variable)
	    if (!isset($_SESSION['username'])) {
	        // If not logged in, redirect to the homepage or login page
	        header("Location: ../html/homepage.html"); // Replace with your homepage URL if it's not "index.php"
	        exit; // Stop further code execution to ensure the redirect works
	    }

	// Assuming the user is logged in and their username is stored in the session
	$userName = $_SESSION['username'];

	// Connect to the database (adjust connection settings as needed)
	$host = 'localhost';
	$username = 'root';
	$dbname = 'profiles';

	try {
	    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username);
	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	    // Fetch rides posted by the user
	    $ridesPostedStmt = $pdo->prepare("SELECT * FROM rides WHERE posted_by = :username");
	    $ridesPostedStmt->bindParam(':username', $userName);
	    $ridesPostedStmt->execute();
	    $ridesPosted = $ridesPostedStmt->fetchAll(PDO::FETCH_ASSOC);

	    // Fetch rides taken by the user
	    $ridesTakenStmt = $pdo->prepare("SELECT * FROM rides WHERE user_taken = :username");
	    $ridesTakenStmt->bindParam(':username', $userName);
	    $ridesTakenStmt->execute();
	    $ridesTaken = $ridesTakenStmt->fetchAll(PDO::FETCH_ASSOC);

	} catch (PDOException $e) {
	    echo "Error: " . $e->getMessage();
	}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Profile</title>
        <link rel="stylesheet" href="habibiStyles.css">
    </head>
    <body>

        <div id="profile">
            <h1>Welcome, <?php echo htmlspecialchars($userName); ?>!</h1>

              <!-- Search Bar -->
            <h2>Search for a Ride</h2>
            <form action="searchRide.php" method="GET">
                <input type="text" name="search" placeholder="Enter a destination" required>
                <input type="submit" value="Search">
            </form>

            <!-- Button to Post a New Ride -->
            <h2>Post a Ride</h2>
            <form action="postRide.php" method="GET">
                <input type="submit" value="Post a New Ride">
            </form>

         <!-- Display Rides Posted by the User -->
            <h2>Rides Posted</h2>
            <?php if (count($ridesPosted) > 0): ?>
                <ul>
                    <?php foreach ($ridesPosted as $ride): ?>
                        <li>
                            <strong>Ride ID: <?php echo $ride['ride_id']; ?></strong><br>
                            Date: <?php echo $ride['ride_date']; ?><br>
                            From: <?php echo $ride['from_location']; ?><br>
                            To: <?php echo $ride['to_location']; ?><br>
                            Riders: <?php echo $ride['user_taken']; ?><br>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>You have not posted any rides yet.</p>
            <?php endif; ?>

            <!-- Display Rides Taken by the User -->
            <h2>Rides Taken</h2>
            <?php if (count($ridesTaken) > 0): ?>
                <ul>
                    <?php foreach ($ridesTaken as $ride): ?>
                        <li>
                            <strong>Ride ID: <?php echo $ride['ride_id']; ?></strong><br>
                            Date: <?php echo $ride['ride_date']; ?><br>
                            From: <?php echo $ride['from_location']; ?><br>
                            To: <?php echo $ride['to_location']; ?><br>
                 			Riders: <?php echo $ride['user_taken']; ?><br>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>You have not taken any rides yet.</p>
            <?php endif; ?>

        </div>

    </body>
</html>

