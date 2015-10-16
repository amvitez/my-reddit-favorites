<!DOCTYPE html>
	<head>
        <meta charset="utf-8">
        <title>My Reddit Favorites</title>
        <link rel="stylesheet" href="css/normalize.css">
		<link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
	<body>
		<?php 
			$username = "";
			$usernameErr = $passwordErr = "";

			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$username = test_input($_POST["username"]);
				$password = test_input($_POST["password"]);
				$confirmPassword = test_input($_POST["confirmPassword"]);
									
				//validate password and confirm password
				if (strlen($username) < 5 || strlen($username) > 16)
					$error = "Username must be between 5 and 16 characters.";
				else if (strlen($password) < 8 || strlen($password) > 20)
					$error = "Password must be between 8 and 20 characters.";
				else if($password != $confirmPassword)
					$error = "Confirm password does not match.";
				else{
					require 'db.php';

					try {
						$sql = $db->prepare("SELECT * FROM user WHERE username = :username");
						$sql->bindParam(':username', $username);
						$sql->execute();
						$result = $sql->fetch(PDO::FETCH_ASSOC);
				
						// If that username is already in the DB, print an error to the user
						if($result){
							$username = "";
							$error = "Username already in use.";
						}
						else{
							$code = md5($username.time());
							$options = [
								'cost' => 12,
							];
							$encryptedPassword = password_hash($password, PASSWORD_BCRYPT, $options);
							$sql = $db->prepare("INSERT INTO user (username, password, code)
								VALUES
									(:username,
									:password,
									:code)");
							$sql->bindParam(':username', $username);
							$sql->bindParam(':password', $encryptedPassword);
							$sql->bindParam(':code', $code);
							$sql->execute();
							$sql = $db->prepare("SELECT LAST_INSERT_ID()");
							$sql->execute();
							$userID = $sql->fetchColumn();
							
							if($userID){
								session_start();
								$_SESSION['userID'] = $userID;
								
								header("Location: main.php");
							}
							else{
								$error = "Error occurred";
							}
						}
					} catch(Exception $e){
						echo $e;
						exit;
					}
				}
			}
			 
			function test_input($data) {
			   $data = trim($data);
			   $data = stripslashes($data);
			   $data = htmlspecialchars($data);
			   return $data;
			}
		?>
		<div>
			<?php if (isset($error)) { echo $error; } ?>
			<h1 class="center">My Reddit Favorites</h1>
			<h2 class="center">Sign Up</h2>
			<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<input type="text" placeholder="Username" name="username" value="<?php echo $username; ?>" required><br>
				<input type="password" placeholder="Password" name="password" required><br>
				<input type="password" placeholder="Confirm Password" name="confirmPassword" required><br>
				<input type="submit" value="Sign Up">
			</form>
			<p class="center">Already have an account? <a class="bold" href="index.php">Sign In</a></p>
		</div>
	</body>
</html>