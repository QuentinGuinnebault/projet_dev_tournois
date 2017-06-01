<?php
// on teste si le visiteur a soumis le formulaire de connexion
if (isset($_POST['connexion']) && $_POST['connexion'] == 'Connexion') {
	if ((isset($_POST['login']) && !empty($_POST['login'])) && (isset($_POST['pass']) && !empty($_POST['pass']))) {

		$base = new PDO('mysql:host=localhost;dbname=tournoi', 'root', '');

		// on teste si une entrée de la base contient ce couple login / pass
		$resultats=$base->query('SELECT count(*) as existe FROM membre WHERE login="'.($_POST['login']).'" AND pass_md5="'.($_POST['pass']).'"');
		$donnees = $resultats->fetch();
		$resultats->closeCursor();
		$data=$donnees['existe'];
		//echo $data;

		// si on obtient une réponse, alors l'utilisateur est un membre
		if ($data == 1) {
			session_start();
			$_SESSION['login'] = $_POST['login'];
			echo '<META http-equiv="refresh" content="0; URL=membre.php">';
			//header('Location: membre.php');
			exit();
		}
		// si on ne trouve aucune réponse, le visiteur s'est trompé soit dans son login, soit dans son mot de passe
		elseif ($data == 0) {
			$erreur = 'Compte non reconnu.';
		}
		else {
			$erreur = 'Probème dans la base de données : plusieurs membres ont les mêmes identifiants de connexion.';
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
	<title>Accueil</title>
	<link href="site/styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<div style="margin-top:180px" align="center">
	<p class="titre">- Acceuil -</p>
	<div align="center">
		<form action="index.php" method="post">
	<table width="30%"  border="0" cellspacing="0" bordercolor="#999999">
	<tr><td><strong>Login : </strong></td><td><input type="text" name="login" value="<?php if (isset($_POST['login'])) echo htmlentities(trim($_POST['login'])); ?>"></td>
	<tr><td><strong>Mot de passe : </strong></td><td><input type="password" name="pass" value="<?php if (isset($_POST['pass'])) echo htmlentities(trim($_POST['pass'])); ?>"></td>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td ><input type="submit" name="connexion" value="Connexion"></td>
	</tr>
	</table>
	</form>
	<a href="inscription.php">Vous inscrire</a></p></div></div>
<?php
if (isset($erreur)) echo '<br /><br />',$erreur;
?>
</body>
</html>
