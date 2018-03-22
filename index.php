<?php 
session_start();
require_once('./inc/connection.php');
?>

<?php
	if (isset($_POST['submit'])) {
		$errors = array();
	 	if (!isset($_POST['email']) || strlen(trim($_POST['email']))<1 ) {

	 		$errors[]='Username is missing or Invalid';
	 		
	 	}

	 	if (!isset($_POST['password']) || strlen(trim($_POST['password']))<1 ) {

	 		$errors[]='Password is missing or Invalid';
	 		
	 	}

	 	if (empty($errors)) {
	 		$email = mysqli_real_escape_string($connection,$_POST['email']);
	 		$password = mysqli_real_escape_string($connection,$_POST['password']);
	 		$hashed_password = sha1($password);

	 		$query = "SELECT * FROM userdb
	 					WHERE email = '{$email}'
	 					AND password = '{$hashed_password}' LIMIT 1 ";
	 		$result_set = mysqli_query($connection,$query);

	 		if ($result_set) {
	 			if (mysqli_num_rows($result_set)==1) {
	 				$user = mysqli_fetch_assoc($result_set);
	 				$_SESSION['id'] = $user['id'];
	 				$_SESSION['first_name'] = $user['first_name'];
	 				$query = "UPDATE userdb SET last_login = NOW()";
	 				$query .= "WHERE id = {$_SESSION['id']} LIMIT 1";
	 				$result_set = mysqli_query($connection,$query);
	 				if (!$result_set) {
	 					die('DAtabase update failed');
	 				}
	 				header('Location: users.php');
	 			} else {
	 				$errors[] ="Invalid Username or Password"; 
	 			}
	 			
	 		}else{
	 			$errors[] = "Database query failed";
	 		}
	 	}
	 	
	 } 
	
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Log In - User Management System</title>
	<link rel="stylesheet" href="css/main.css">
</head>
<body>
	<div class = 'login'>
		<form action="index.php" method="post">
			<fieldset>
				<legend><h1>Log In</h1></legend>
				<?php
					if (isset($errors) && !empty($errors))
					 {
						echo '<p class = "error" > Invalid Username / Password</p>';

					}
				  ?>
				<?php 
					if (isset($_GET['logout'])) {
						echo '<p class = "info" > Successfully logged out from the system </p>';
					}
				?>
				<p>
					<label for="">Username:</label>
					<input type="text" name="email" id="" placeholder="email address">
				</p>
				<p>
					<label for="">Password:</label>
					<input type="password" name="password" id="" placeholder="password">
				</p>
				<p>
					 <button type="submit" name="submit">Log In</button>
				</p>
			</fieldset>
		</form>
	</div>
</body>
</html>

<?php mysqli_close($connection);?>
