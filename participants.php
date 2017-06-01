<?php
require_once('connexion.php');
session_start();
if (!isset($_SESSION['login'])) {
	header ('Location: index.php');
	exit();
}
$login = $_SESSION['login'];


$requete = $connexion->query("SELECT id FROM membre WHERE login = '$login'");
$requete->setFetchMode(PDO::FETCH_OBJ);
while ($resultat = $requete->fetch())
	$idutilisateur=$resultat->id;

// on récupère les variables des input entrées par l'utilisateur
$sport = $_POST['sport'];
$nom = $_POST['nom_champ'];
$categorie = $_POST['categorie'];
$calendrier = $_POST['calendrier'];
$format = $_POST['format'];

if($format == "poule" || $format == "championnat"){
	$ptsvic = $_POST['ptsvic'];
	$ptsnul = $_POST['ptsnul'];
	$ptsdef = $_POST['ptsdef'];
}

//si l'utilisateur a choisi le mode élimination directe le nombre de participants va récupérer la valeur 'nbr_equip'
if ($format == "elimination"){
	$nb = $_POST['nbr_equip'];
}else{
	$nb = $_POST['nb'];
}

//si l'utilisateur a choisi le mode poule on recupère les données des poules et on les ajoute à la base avec les autres input
if ($format == 'poule'){
	$nbpoule = $_POST['nbpoule'];
	$nb_parpoule = $_POST['nb_parpoule'];
	$nb_qualif = $_POST['nb_qualif'];
	$connexion->exec("INSERT INTO championnat (nom, categorie, variable, nbequipes, idutilisateur, sport, format, ptsvic, ptsnul, ptsdef, nbpoule, nb_parpoule, nb_qualif) VALUES ('$nom', '$categorie','$calendrier','$nb', '$idutilisateur', '$sport','$format', '$ptsvic', '$ptsnul', '$ptsdef', '$nbpoule', '$nb_parpoule', '$nb_qualif')");
}else if ($format == "championnat"){
// si le format est un championnat on ajoute les données
	$connexion->exec("INSERT INTO championnat (nom, categorie, variable, nbequipes, idutilisateur, sport, format, ptsvic, ptsnul, ptsdef) VALUES ('$nom', '$categorie','$calendrier','$nb', '$idutilisateur', '$sport','$format', '$ptsvic', '$ptsnul', '$ptsdef')");
}else{
	// pareil si le format est une élimination directe
	$connexion->exec("INSERT INTO championnat (nom, categorie, variable, nbequipes, idutilisateur, sport, format) VALUES ('$nom', '$categorie','$calendrier','$nb', '$idutilisateur', '$sport','$format')");
}

//on recupère l'identifiant du tournoi qui servira de clé étrangère pour l'insertion des participants
$sql = $connexion->query("SELECT idchamp FROM championnat WHERE nom = '$nom' AND sport = '$sport' AND categorie = '$categorie' ");
$sql->setFetchMode(PDO::FETCH_OBJ);
while ($resultat = $sql->fetch()){
	$idchamp = $resultat->idchamp;
}
?>

<html>
<head>
	<title>Cr�ation d'un tournoi</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
include("participants_noms.php");
?>
</body>
</html>
