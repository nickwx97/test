<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$str = $_POST["search"];
	$pattern = "/<([A-Za-z_{}()\/]+(\s|=)*)+>(.*<[A-Za-z\/>]+)*/i";
	if(preg_match($pattern, $str) == 0){
		$_SESSION["search"] = htmlspecialchars(stripslashes(trim($str)));
		header("Location:result.php");
	}
}
?>
<html>
	<head>
		<title>Search</title>
	</head>
	<body>
		<form method="post" action="index.php">
			<p>Search:</p>
			<input type="text" id="search" name="search" required />
			<input type="submit" name="submit" id="submit" />
	</body>
</html>