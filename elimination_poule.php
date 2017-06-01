<html>
	<head>
		<title>Classements poules</title>
		<link href="styles.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div align="center">
		<p class="titre"><font size='6'>- Phase finale -</font></p>
		<p align="center">

<?php
session_start();
require_once("connexion.php");
require_once 'affiche_matchs.php';


if (isset($_GET['id'])) {
 $_SESSION['championnat'] = $_GET['id'];
 $_SESSION['journee'] = $_GET['day'];
}
// une fois que create_day() et affiche_match() ont été executés, s'affiche alors un formulaire pour l'enregistrement du score des matchs
if (isset($_POST)) {// si le formulaire est envoyé
 $journee = $_SESSION['journee'];
 $championnat = $_SESSION['championnat'];
 $matchs = sizeof($_POST) / 2;
 $keys = array_keys($_POST);
 $nbr_match_winner = 0;// nbr_match_winner permet d'eviter de lancer une nouvelle requête create_day() si le score est un match nul
 for ($i = 0; $i < sizeof($keys); $i=$i+2) {//boucle qui permet le score et les équipes match par match (indentation de + 2 car 2 équipes pour un match)
  //recupération des valeurs entrées par l'utilisateur
  $equipe1 = $keys[$i];
  $equipe2 = $keys[$i + 1];
  $score_equipe1 = ($_POST[$equipe1]);
  $score_equipe2 = ($_POST[$equipe2]);
  //$sql = $connexion->query("UPDATE equipes SET ptselim=ptselim+1 WHERE idequipe = :id");
  if ($score_equipe1 > $score_equipe2) {
   // mise à jour dans la table matchs
   $connexion->query("UPDATE matchs SET resultat1='$score_equipe1', resultat2='$score_equipe2' WHERE equipe1='$equipe1' AND equipe2='$equipe2' AND idchamp='$championnat'");
   // on ajoute +1 à ptselim qui nous servira pour connaitre les équipes qualifiées lors du prochain tour
   $sql = $connexion->query("UPDATE equipes SET ptselim='$journee' WHERE idequipe = '$equipe1'");
   $nbr_match_winner++;// indentation si la requête s'est executée
  } else {
   if ($score_equipe2 > $score_equipe1) {
     //idem
    $connexion->query("UPDATE matchs SET resultat1='$score_equipe1', resultat2='$score_equipe2' WHERE equipe1='$equipe1' AND equipe2='$equipe2' AND idchamp='$championnat'");
    $sql = $connexion->query("UPDATE equipes SET ptselim='$journee' WHERE idequipe = '$equipe2'");
    $nbr_match_winner++;
   }
  }
 }
 //si tous les matchs ont un vainqueur on met à jour la variable day et on reprend create_day() (à la ligne 64) avec deux fois moins d'équipes
 if ($nbr_match_winner == $matchs && $matchs != 0) {
  $_SESSION['journee'] += 1;
  create_day($_SESSION['championnat'], ($_SESSION['journee'])-1);
 }
}

$infos = get_infos_championnat($_SESSION['championnat']);

$gagnants = array(); // création d'un tableau vide où seront insérés les qualifiés
$journee = $_SESSION['journee'];
$championnat = $_SESSION['championnat'];
if ($journee==1) {// si la day=1 on execute cette boucle (de base day=1)
 for ($i = 1; $i <= $infos['nbpoule']; $i++) {
  $sql = "SELECT idequipe FROM equipes WHERE idchamp = ".$_SESSION['championnat']." AND poule = '$i' ORDER by pts DESC, (bp-bc) DESC LIMIT " . $infos['nb_qualif'];
  $winner = $connexion->query($sql)->fetchAll(PDO::FETCH_COLUMN);
  foreach ($winner as $value) {
   array_push($gagnants, $value);// pour chaque poule on insère les qualifiés
  }
 }
 create_day($championnat, $journee, $gagnants);// on execute alors la fonction create_day()
}
else {
 $pts = $journee -1;
 $sql = "SELECT idequipe FROM equipes WHERE idchamp='$championnat' AND ptselim='$pts'";
 $gagnants = $connexion->query($sql)->fetchAll(PDO::FETCH_COLUMN);
}
echo '<pre>';

//lorsque create_day() a été executer on peut passer à l'affichage des matchs
$sql = "SELECT * FROM matchs WHERE idchamp = '$championnat' AND idjournee = '$journee'";
$matchs = $connexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);
// affichage des matchs sous forme de tableau
 echo '<table>';
 echo "<form action=# method='post'>";
 foreach ($matchs as $match) {
  echo affiche_match($match);
 }
 echo '</table>';
 echo '<input type="submit" value="Enregistrer">';
 echo '</form>';
?>

</p></div>
<p align ='center' ><a href="../membre.php"><strong>&lt; Retour accueil</strong></a></p>
</body></html>
