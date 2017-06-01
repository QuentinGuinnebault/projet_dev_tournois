<?php
require_once('connexion.php');
session_start();
if (!isset($_SESSION['login'])) {
	header ('Location: index.php');
	exit();
}
$login = $_SESSION['login'];
//récupération de l'id utilisateur via son login qui est unique
$requete = $connexion->query("SELECT id FROM membre WHERE login = '$login'");
$requete->setFetchMode(PDO::FETCH_OBJ);
while ($resultat = $requete->fetch())
{
  $idutilisateur=$resultat->id;
}
 ?>
<html>
	<head>
		<title>Liste tournois</title>
		<link href="styles.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div style="margin-top:180px" align="center">
		<p class="titre">- Selectionnez votre tournoi -</p>
		<p align="center">
		<?php
				$sql = $connexion->query("SELECT * FROM championnat WHERE idutilisateur = '$idutilisateur' ORDER BY nom DESC;");
        $sql->setFetchMode(PDO::FETCH_OBJ);
        while ($resultat = $sql->fetch())
        {
					$nom = $resultat->nom;
          $format = $resultat->format;
          $sport = $resultat->sport;
					$idchamp = $resultat->idchamp;
					$ptsvic = $resultat->ptsvic;
					$ptsnul = $resultat->ptsnul;
					$ptsdef = $resultat->ptsdef;

// affichage des tournois de l'utilisateur avec lien vers le tournoi en fonction de son format
					if($format == "championnat"){
	          echo "<p><a href='tirage_champ.php?id=".$idchamp."&amp;vic=".$ptsvic."&amp;nul=".$ptsnul."&amp;def=".$ptsdef."'>$nom - $format - $sport</a><p>";
	        }else if($format == "poule"){
	          echo "<p><a href='poule.php?id=".$idchamp."&amp;vic=".$ptsvic."&amp;nul=".$ptsnul."&amp;def=".$ptsdef."'>$nom - $format - $sport</a><p>";
	        }else{
	          echo "<p><a href='elimination_directe.php?day=1&amp;id=".$idchamp."'>$nom - $format - $sport</a><p>";
	        }
				}
		?>
			<script type="text/javascript">
				document.getElementById("champ").selectedIndex=-1;
			</script>
		</p>
		<p><a href="../membre.php" style = "color: #FF6600"><strong>&lt; Retour accueil</strong></a></p>
	</form></div></body>
  <br />
