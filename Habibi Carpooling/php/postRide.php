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

			<form action = postRde.php method = "POST">
				<fieldset>
					<legend>Enter Ride Details</legend>

					Departure<br>
					<input type = "text" name = "departure" required>
					</br>

					Destination<br>
					<input type = "text" name = "destination" required>
					</br>

					Date<br>
					<input type = "datetime" name = "date" required>
					</br>

					<br>Contact Information</br>
					Email<br>
					<input type = "email" name = "email" required>
					</br>
					Telephone<br>
					<input type = "tel" name = "telephone" required>
					</br>

				</fieldset>
			</form>
			
		</div>

		
	</body>

</html>