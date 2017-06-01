<?php
// on teste si le visiteur a soumis le formulaire
if (isset($_POST['inscription']) && $_POST['inscription'] == 'Inscription') {
	// on teste l'existence de nos variables. On teste également si elles ne sont pas vides
	if ((isset($_POST['login']) && !empty($_POST['login'])) && (isset($_POST['pass']) && !empty($_POST['pass'])) && (isset($_POST['pass_confirm']) && !empty($_POST['pass_confirm']))) {
	// on teste les deux mots de passe
	if ($_POST['pass'] != $_POST['pass_confirm']) {
		$erreur = 'Les 2 mots de passe sont différents.';
	}
	else {

       $base = new PDO('mysql:host=localhost;dbname=tournoi', 'root', '');

		// on recherche si ce login est déjà utilisé par un autre membre
		$resultats=$base->query('SELECT count(*) as existe FROM membre WHERE login="'.($_POST['login']).'"');
        $donnees = $resultats->fetch();
        $resultats->closeCursor();
        $data=$donnees['existe'];
        echo $data;

		if ($data == 0) {
		$base->exec('INSERT INTO membre(login, pass_md5) VALUES("'.($_POST['login']).'", "'.($_POST['pass']).'")');

		session_start();
		$_SESSION['login'] = $_POST['login'];
		header('Location: membre.php');
		exit();
		}
		else {
		$erreur = '<strong>Un membre possède déjà ce login.</strong>';
		}
	}
	}
	else {
	$erreur = 'Au moins un des champs est vide.';
	}
}
?>
<html>
<head>
<meta charset="utf-8">
<title>Inscription</title>
<link href="site/styles.css" rel="stylesheet" type="text/css">
</head>

<body>
	<div style="margin-top:180px" align="center">
	<p class="titre">- Inscription à l'espace membre -</p>
	<p align="center">


<form action="inscription.php" method="post">
<table width="40%"  border="0" cellspacing="0" bordercolor="#999999">
<tr><td><strong>Login : </strong></td><td><input type="text" name="login" value="<?php if (isset($_POST['login'])) echo htmlentities(trim($_POST['login'])); ?>"></td>
<tr><td><strong>Mot de passe : </strong></td><td><input type="password" name="pass" value="<?php if (isset($_POST['pass'])) echo htmlentities(trim($_POST['pass'])); ?>"></td>
<tr><td><strong>Confirmation du mot de passe : </strong></td><td><input type="password" name="pass_confirm" value="<?php if (isset($_POST['pass_confirm'])) echo htmlentities(trim($_POST['pass_confirm'])); ?>"></td>
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td ><input type="submit" name="inscription" value="Inscription"></td>
</tr>
</table></form></p></div>

<?php
if (isset($erreur)) echo '<br />',$erreur;
?>
</body>
</html>
