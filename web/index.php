<!DOCTYPE html>
	<head>
        <meta charset="utf-8">
        <title>My Reddit Favorites</title>
        <link rel="stylesheet" href="css/normalize.css">
		<link rel="stylesheet" type="text/css" href="css/style.css">
    </head>
	<body>
		<?php
			session_start();
			session_unset(); 
			session_destroy(); 

			$usernameErr = $passwordErr = "";
			$username = $password = "";

			if ($_SERVER["REQUEST_METHOD"] == "POST") {
			   $("#submit-sign-in").attr("disabled", true);
			   
			   $username = $_POST["username"];
			   
			   if ($username == "") {
				 $usernameErr = "Username is required";
			   } else {
				 $username = test_input($_POST["username"]);
			   }
			   
			   if (strlen($_POST["password"]) == 0) {
				 $passwordErr = "Password is required";
			   } else {
				 $password = test_input($_POST["password"]);
			   }
			   
			   if ($usernameErr == "" && $passwordErr == ""){
					require 'db.php';

					try {			
						// Check for username to make sure it exists
						$sql = $db->prepare("SELECT * FROM user WHERE username=:username");
						$sql->bindParam(':username', $username);
						$sql->execute();
						$result = $sql->fetch(PDO::FETCH_ASSOC);
						
						if(!$result){
							$error = "Username not found.";
						}
						
						// Validate the password the user entered
						if (!isset($error)){	
							if(!password_verify($password, $result['password'])){
								$error = "Invalid password";
							}
							
							if ($error == ""){
								$userID = $result['userID'];
								
								session_start();
								$_SESSION['userID'] = $userID;
								
								header("Location: main.php");
							}
						}
					} catch(Exception $e){
						echo $e;
						exit;
					}
			   }
			   
			   $("#submit-sign-in").attr("disabled", false);
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
			<h2 class="center">Sign In</h2>
			<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<input type="text" placeholder="Username" name="username" value="<?php echo $username; ?>" required><?php echo $usernameErr; ?>
				<br>
				<input type="password" placeholder="Password" name="password" required><?php echo $passwordErr; ?>
				<br>
				<input type="submit" id="submit-sign-in" value="Sign In">
			</form>
			<p class="center">Don't have an account? <a class="bold" href="register.php">Sign up</a></p>
		</div>
	</body>
</html>