<?php
session_start();
if (!isset($_SESSION['login'])) {
	header ('Location: index.php');
	exit();
}
require_once('connexion.php'); 

$idchamp = $_POST['id'];// recuperation de l'idchamp

// requête sql pour récuperer les données du championnat notament celle des poules pour la répartition des équipes
$sql = $connexion->query("SELECT * FROM championnat WHERE idchamp= '$idchamp'");
$sql->setFetchMode(PDO::FETCH_OBJ);
$resultat = $sql->fetch();
$nom = $resultat->nom;
$nb = $resultat->nbequipes;
$format = $resultat->format;
$nbpoule = $resultat->nbpoule;
$nb_parpoule = $resultat->nb_parpoule;
$nb_qualif = $resultat->nb_qualif;

// insertion du nom des équipes
$equipes=array();
$sqli=array();
for($j=1;$j<=$nb;$j++)
{
	$equipes[$j] = $_POST['equipe'.$j];
	$sqli[$j] = $connexion->query("INSERT INTO equipes (nomequipe, idchamp) VALUES ('".$equipes[$j]."', '$idchamp')");
}


// si ce sont des poules, algorithme qui attribue aléatoirement des poules aux équipes
if ($format == 'poule') {
	$nb_joueur_poule = 1;
	$numero_poule = 1;
	while ($nb_joueur_poule <= $nb_parpoule AND $numero_poule <= $nbpoule) {
		$sql = "SELECT idequipe FROM equipes WHERE idchamp = '$idchamp' AND poule = 0";
		$ids = $connexion->query($sql)->fetchAll(PDO::FETCH_COLUMN);
		$alea = rand(0, sizeof($ids)-1);
		$idequipe = $ids[$alea];
		$sql = "UPDATE equipes SET poule='$numero_poule' WHERE idequipe = '$idequipe'";
		$connexion->query($sql);
		$nb_joueur_poule++;
		if ($nb_joueur_poule > $nb_parpoule) {
			$nb_joueur_poule = 1;
			$numero_poule++;
		}
	}
}
?>


<html>
<head>
	<title>Tournoi cree</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<body>
<div align="center">
	<br />
	<br />
	<p class="titre">Tournoi cree</p>
	<img src="ping.jpg">
	</p>
	<a href="../membre.php">Retourner la page d'accueil</a>
</div>
</body>
</html>
