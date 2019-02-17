<?php
	session_start();
	
	//COOOKIES
	function setCookies(){
		if (isset($_POST['remember'])) {
				/* Set cookie to last 1 year */
				setcookie('u_login', $_POST['login'], time()+60*60*24*365, '/');
				setcookie('u_pwd', password_hash(($_POST['pwd']), PASSWORD_DEFAULT), time()+60*60*24*365, '/');
			} else {
				/* Cookie expires when browser closes */
				setcookie('u_login', $_POST['login'], false, '/');
				setcookie('u_pwd', password_hash(($_POST['pwd']), PASSWORD_DEFAULT), false, '/');
			}
	}

	if(isset($_POST['submit'])) {
		
		include_once 'db.inc.php';
		
		$login = mysqli_real_escape_string($conn, $_POST['login']);
		$pwd = mysqli_real_escape_string($conn, $_POST['pwd']);
		
		//Error handlers
		//Check if inputs are empty
		
		if(empty($login) || empty($pwd)) {
			header("Location: ../index.php?index=empty");
			exit();
		} else {
			$sql = "SELECT * FROM users WHERE user_login='$login' OR user_email='$login';";
			$result = mysqli_query($conn, $sql);
			$resultCheck = mysqli_num_rows($result);
			
			if($resultCheck < 1) {
				header("Location: ../index.php?login=error");
				exit();
			} else {
				if($row = mysqli_fetch_assoc($result)) {
					//De-hashing the password
					$hashedPwdCheck = password_verify($pwd,$row['user_pass']);
					if($hashedPwdCheck == false) {
						header("Location: ../index.php?login=error");
						exit();
					} elseif($hashedPwdCheck == true) {
						//Log in the user here
						$_SESSION['u_id'] = $row['user_id'];
						$_SESSION['u_first'] = $row['user_first'];
						$_SESSION['u_last'] = $row['user_last'];
						$_SESSION['u_email'] = $row['user_email'];
						$_SESSION['u_login'] = $row['user_login'];
						setCookies();
						header("Location: ../index.php?login=success");
						exit();
					}
				}		
			}
		}
		
	} else {
		header("Location: ../index.php?index=empty");
		exit();
	}
	
?>