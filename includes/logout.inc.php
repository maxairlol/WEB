<?php
	if(isset($_POST['submit'])) {
		session_start();
		session_unset();
		session_destroy();
		
		setcookie('u_login', '', time()-60*60*24*365, '/');
		setcookie('u_pwd', '', time()-60*60*24*365, '/');

		header("Location: ../index.php");
		exit();
	}
?>