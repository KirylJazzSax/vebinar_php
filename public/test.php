<?php
session_start();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Документ без названия</title>
</head>

<body>

<?php
require_once('../lib/Debug.class.php');
Debug::WiewSessCook();
?>

</body>
</html>