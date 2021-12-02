<?php
session_start();
echo $_SESSION["search"];
session_unset();
?>
<html>
<head>
<title>Result</title>
</head>
<body>
<br/>
<br/>
<a href="index.php">Back to home</a>
</body>
</html>