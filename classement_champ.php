<html>
	<head>
		<title>Classement</title>
		<link href="styles.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div align="center">
		<p class="titre">- Classement -</p>
		<p align="center">

<?php
require_once('connexion.php');
$championnat = $_GET['id'];
$scores = $_POST['scores'];
//$sql=array();

foreach ($scores as $idJournee => $suite1) {
	foreach ($suite1 as $idEquipe1 => $suite2) {
		foreach ($suite2 as $idEquipe2 => $resultats) {
			$resultat1 = intval($resultats['resultat1']);
			$resultat2 = intval($resultats['resultat2']);
			// On a les info qu'on voulait : $idJournee, $idEquipe1, $idEquipe2, $resultat1, $resultat2
			// Reste à insérer les matchs :
			inserer_match($idJournee, $idEquipe1, $idEquipe2, $resultat1, $resultat2);
			// Reste à ajouter les points
			donner_points($idEquipe1, $idEquipe2, $resultat1, $resultat2);
		}
	}
}

// Traitement terminé reste à afficher la suite

function inserer_match($idJournee, $idEquipe1, $idEquipe2, $resultat1, $resultat2) {

	$connexion = new PDO('mysql:host=localhost;dbname=tournoi', 'root','');
	$sql = $connexion->query("INSERT INTO matchs (idjournee, equipe1, equipe2, resultat1, resultat2) VALUES ('$idJournee', '$idEquipe1', '$idEquipe2', '$resultat1', '$resultat2')");
}

function donner_points($idEquipe1, $idEquipe2, $resultat1, $resultat2) {
	$ptsvic = $_GET['vic'];
	$ptsnul = $_GET['nul'];
	$ptsdef = $_GET['def'];
	$connexion = new PDO('mysql:host=localhost;dbname=tournoi', 'root','');
	if ($resultat1 > $resultat2) {
		// Equipe1 a gagné
		// On recupère les info de l'auipe 1 (nb match fait, bn match gagné, point, etc) puis on les modifie en fonction
		$sql = $connexion->query("UPDATE equipes SET pts=pts+'$ptsvic', nbvictoire=nbvictoire+1, bp=bp+'$resultat1', bc=bc+'$resultat2', nbmatch=nbmatch+1 WHERE idequipe = '$idEquipe1'");
		// Equipe2 a perdu
		// Idem dans l'autre sens
		$sql = $connexion->query("UPDATE equipes SET pts=pts+'$ptsdef', nbdefaite=nbdefaite+1, bp=bp+'$resultat2', bc=bc+'$resultat1', nbmatch=nbmatch+1 WHERE idequipe = '$idEquipe2'");
	}elseif ($resultat2 > $resultat1){
		$sql = $connexion->query("UPDATE equipes SET pts=pts+'$ptsvic', nbvictoire=nbvictoire+1, bp=bp+'$resultat2', bc=bc+'$resultat1', nbmatch=nbmatch+1 WHERE idequipe = '$idEquipe2'");
		$sql = $connexion->query("UPDATE equipes SET pts=pts+'$ptsdef', nbdefaite=nbdefaite+1, bp=bp+'$resultat1', bc=bc+'$resultat2', nbmatch=nbmatch+1 WHERE idequipe = '$idEquipe1'");
	} elseif($resultat1 == $resultat2) {
		// Match nul
	  $sql = $connexion->query("UPDATE equipes SET pts=pts+'$ptsnul', nbnul=nbnul+1, bp=bp+'$resultat1', bc=bc+'$resultat1', nbmatch=nbmatch+1 WHERE idequipe = '$idEquipe1'");
		$sql = $connexion->query("UPDATE equipes SET pts=pts+'$ptsnul', nbnul=nbnul+1, bp=bp+'$resultat1', bc=bc+'$resultat1', nbmatch=nbmatch+1 WHERE idequipe = '$idEquipe2'");
	}
}

//affichage du classement sous forme de tableau
$connexion = new PDO('mysql:host=localhost;dbname=tournoi', 'root','');
$sql = $connexion->query("SELECT * FROM equipes WHERE idchamp = '$championnat' ORDER by pts DESC, bp DESC ");
$sql->setFetchMode(PDO::FETCH_OBJ);
echo '<table width="650" border="3" cellpadding="5" cellspacing="0" bordercolor="#666666">';
echo '<tr bgcolor="#CCCCCC">';
echo '<th> Clt </th>';
echo '<th> Equipe </th>';
echo '<th> V </th>';
echo '<th> N</th>';
echo '<th> D </th>';
echo '<th> BP </th>';
echo '<th> BC </th>';
echo '<th> Points </th>';
echo '</tr>';
$nb=0;
while ($resultat = $sql->fetch())
{
    // variable pour chaque champ
    // nb++ est utile pour afficher la position de l'équipe :1-2-3-4-5-6-7-8
    $nb++;
    $num = $resultat->idequipe;
    $nom = $resultat->nomequipe;
    $vic = $resultat->nbvictoire;
    $nul = $resultat->nbnul;
    $def = $resultat->nbdefaite;
    $bp = $resultat->bp;
    $bc = $resultat->bc;
		$pts = $resultat->pts;
    // affichage du tableau avec les variables enregistrées
    echo'<tr>';
    echo '<td align="center">'.$nb.'</td>';
    echo '<td align="center">'.$nom.'</td>';
    echo '<td align="center">'.$vic.'</td>';
    echo '<td align="center">'.$nul.'</td>';
    echo '<td align="center">'.$def.'</td>';
    echo '<td align="center">'.$bp.'</td>';
    echo '<td align="center">'.$bc.'</td>';
		echo '<td align="center">'.$pts.'</td>';
    echo '</tr>';
    echo '<input name="e[]" type="hidden" value="'.$num.'" />';

  }
echo "</table>";

?>
</p>
<p><a href="../membre.php"><strong>&lt; Retour accueil</strong></a></p>
</div></body>
