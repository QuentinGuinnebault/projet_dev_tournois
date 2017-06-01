<?php
session_start();
if (!isset($_SESSION['login'])) {
	header ('Location: index.php');
	exit();
}
?>

<html>
<head>
<meta charset="utf-8">
<title>Espace membre</title>
<link href="site/styles.css" rel="stylesheet" type="text/css">
</head>

<body>
	<div style="margin-top:180px" align="center">
	<p class="titre"><font size="6">ESPACE PERSO DE <?php echo ($_SESSION['login']); ?></p>
	<p align="center">

<p><a href="site/nouv_champ.php"><font size="4">- CREER UN NOUVEAU TOURNOI -</a></p>
<p><a href="site/tournois_perso.php">- GERER MES TOURNOIS -</p></br>
<p><a href="deconnexion.php" style="color:#FF6600">Déconnexion</a></p></p></div>
</body>
</html>
