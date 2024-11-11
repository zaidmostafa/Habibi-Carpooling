<?php
    session_start(); 

    // Check if the user is logged in (based on session variable)
    if (!isset($_SESSION['username'])) {
        // If not logged in, redirect to the homepage or login page
        header("Location: ../html/homepage.html"); // Replace with your homepage URL if it's not "index.php"
        exit; // Stop further code execution to ensure the redirect works
    }

    // Get the logged-in username
    $username = $_SESSION['username'];

    // Connect to the database (adjust connection settings as needed)
    $host = 'localhost';
    $dbUsername = 'root';
    $dbname = 'profiles';

    try {
        // Create a PDO connection
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbUsername);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Ensure the username is JSON-encoded (wrap it in an array)
        $usernameJson = json_encode([$username]);

        // Initialize $ridesTaken and $ridesPosted as empty arrays before the queries
        $ridesTaken = [];
        $ridesPosted = [];

        // Fetch rides posted by the user
        $ridesPostedStmt = $pdo->prepare("SELECT * FROM rides WHERE driver = :username");
        $ridesPostedStmt->bindParam(':username', $username);
        $ridesPostedStmt->execute();
        $ridesPosted = $ridesPostedStmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch rides taken by the user using JSON_CONTAINS
        $ridesTakenStmt = $pdo->prepare("SELECT * FROM rides WHERE JSON_CONTAINS(passengersList, :usernameJson)");
        $ridesTakenStmt->bindParam(':usernameJson', $usernameJson);
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
            <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>

              <!-- Search Bar -->
            <h2>Search for a Ride</h2>
            <form action="searchRide.php" method="GET">
                <input type="text" name="search" placeholder="Search by: origin, destination, date, etc." required>
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
                            <strong>Ride ID: <?php echo $ride['rideID']; ?></strong><br>
                            Date: <?php echo $ride['rideDate']; ?><br>
                            From: <?php echo $ride['origin']; ?><br>
                            To: <?php echo $ride['destination']; ?><br>
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
                            <strong>Ride ID: <?php echo $ride['rideID']; ?></strong><br>
                            Date: <?php echo $ride['rideDate']; ?><br>
                            From: <?php echo $ride['origin']; ?><br>
                            To: <?php echo $ride['destination']; ?><br>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>You have not taken any rides yet.</p>
            <?php endif; ?>

        </div>

        <button onclick="window.location.href='../html/homepage.html'">Logout</button></body>

    </body>

</html>
