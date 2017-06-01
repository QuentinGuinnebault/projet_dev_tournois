<?php
require_once('connexion.php');
?>

<head>
	<title>Poules</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="styles.css" rel="stylesheet" type="text/css">
</head>
<p class="titre">- Tirage au sort des poules -</p>

<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<script>
$(document).ready(function(){
	$('input[type="text"]').each(function(){
		$(this).val(Math.floor(Math.random() * 5) + 0);
	});
})
</script>

<?php

$championnat = $_GET['id'];

$sql = $connexion->query("SELECT * FROM championnat WHERE idchamp= '$championnat'");
$sql->setFetchMode(PDO::FETCH_OBJ);
while ($resultat = $sql->fetch()){
$nbpoule = $resultat->nbpoule;
$nb_parpoule = $resultat->nb_parpoule;
$nb_qualif = $resultat->nb_qualif;
}

$ptsvic = $_GET['vic'];
$ptsnul = $_GET['nul'];
$ptsdef = $_GET['def'];
?>

<!-- affichage des poules en fonction du nombre de poules "$nbpoule"-->
<form method='post' action='classement_poule.php?id=<?php echo $championnat ?>&amp;nb_qualif=<?php echo $nb_qualif ?>&amp;nbpoule=<?php echo$nbpoule ?>&amp;vic=<?php echo $ptsvic ?>&amp;nul=<?php echo $ptsnul ?>&amp;def=<?php echo $ptsdef ?>&amp;nb_qualif=<?php echo $nb_qualif ?>'>;
<?php
for  ($n = 1; $n <= $nbpoule; $n++ ) {
	?> <hr size=2 align=center width="100%" /><?php
  ${"nomequipe" . $n} = array();
  ${"idequipe" . $n} = array();
  $req = "SELECT * FROM equipes WHERE idchamp= '$championnat' AND poule = '$n'";
  foreach  ($connexion->query($req) as $row) {
      ${"nomequipe" . $n}[] = $row['nomequipe'];
      ${"idequipe" . $n}[] = $row['idequipe'];
  }
  $sortie2 = '<div align="center"><table width="auto" border="3" cellpadding="5" cellspacing="0" bordercolor="#0099CC"><tr><th align="center"> POULE NUMERO '.$n.'</th></tr>';
  for($j = 0; $j<$nb_parpoule; $j++){
    $sortie2.='<tr><td align="center">'.${"nomequipe" . $n}[$j].'</td></tr>';
  }
  $sortie2.='</table></div>';
  echo $sortie2;
	// affichage des matchs graçe aux fonctions journees_affiche et journees_calcule
  echo '<p>';
  echo journees_affiche ( journees_calcule ($nb_parpoule), ${"nomequipe" . $n}, ${"idequipe" . $n});
  echo '</p>';
}
?>
</br><p align='center'><input type='submit' name='Submit' value='Enregistrer les resultats' /></p></form>


<?php

// Voir "classement.php" pour l'explication des fonctions
function journees_calcule ($nb_equipe)
 {
 	// Si le nombre d'equipes est impair
 	// on passe au nombre pair juste apres
 	if ( ($nb_equipe % 2) == 1 ) $nb_equipe++;
 	// Moitie des equipes
 	$moitie=$nb_equipe/2;
 	// Tableau de sortie a deux dimensions : journee, matchs
 	$tableau=array();
 	for ($j=1; $j<=$nb_equipe-1; $j++)
 	{
 		$tableau[$j]=array();
 		$tableau[$j]["D"]=array();   // Domicile
 		$tableau[$j]["E"]=array();   // Exterieur
 		// Traitement particulier de la premiere ligne
 		$tableau[$j]["D"][1]=$j;
 		$tableau[$j]["E"][1]=$nb_equipe;
 		for ($m=2; $m<=$moitie; $m++)
 		{
 			$dom=$j+$m-1;
 			if ( $dom >= $nb_equipe ) $dom++;
 			$ext=$nb_equipe+$j-$m;
 			if ( $ext >= $nb_equipe ) $ext++;
 			$tableau[$j]["D"][$m]= ( $dom % $nb_equipe );
 			$tableau[$j]["E"][$m]= ( $ext % $nb_equipe );
 		}
 	}
 	// On cree journee a partir de tableau
 	// en "melangeant" les colonnes (tableau[journee])
 	$journee=array();
 	for ($j=1; $j<=$nb_equipe-1; $j++)
 	{
 		$journee[$j]=array();
 		$journee[$j]["D"]=array();
 		$journee[$j]["E"]=array();
 	}
 	// 1ere journee
 	$journee[1]=$tableau[1];
 	$journee[2]=$tableau[$nb_equipe-1];
 	// autres journees
 	for ($j=2; $j<=$nb_equipe-1; $j++)
 	{
 		if ( ($j % 2) == 0)
 		{
 			// Cas $j pair
 			$indice = ($nb_equipe / 2) - ( ($j -2)/2 );
 		}
 		else
 		{
 			// Cas $j impair
 			$indice = $nb_equipe - ( ($j-1) / 2 );
 		}
 		$journee[$j]=$tableau[$indice];
 	}
 	// On balaye les journees paires
 	// pour alterner Domicile-Exterieur pour l'equipe $nb_equipe
 	// donc uniquement pour le 1er match de chaque journee
 	for ($j=1; $j<=$nb_equipe-1; $j++)
 	{
 		// Journee paire
 		if ( ($j % 2) == 0 )
 		{
 			// On recupere les valeurs
 			$dom=$journee[$j]["D"][1];
 			$ext=$journee[$j]["E"][1];
 			// On inverse
 			$journee[$j]["D"][1]=$ext;
 			$journee[$j]["E"][1]=$dom;
 		}
 	}
 	return $journee;
 }

 function journees_affiche ($journee, $tab, $tab2)
 {
 	// Nombre de journees
 	$nb_jour=sizeof($journee);
 	// Largeur des colonnes
 	$width=round(100/$nb_jour);
 	// Nombre de matches ( (nombre de journees +1) / 2)
 	$nb_match=($nb_jour+1)/2;


 	$sortie="<table align='center' width='80%' border='1'>";
 	$sortie .= "   <tr>";
 	for ($j=1; $j<=$nb_jour; $j++)
 	{
 		$sortie .= "     <td colspan='3' align='center' width='auto'><b>Journee ".$j."</b></td>\n";
 	}
 	$sortie .= "   </tr>";
 	// Affichage des matches
 	for ($m=1; $m<=$nb_match; $m++)
 	{
 		$sortie .= "   <TR>";
 		for ($j=1; $j<=$nb_jour; $j++)
 		{
 			$sortie .= "<td align='center' width='auto'>".$tab[$journee[$j]["D"][$m]-1]." - ".$tab[$journee[$j]["E"][$m]-1]."</td>
 			<td align='center'><input style='width: 25px;' type='text' patter='^abandon|[0-9]+$' name='scores[".$j."][".$tab2[$journee[$j]["D"][$m]-1]."][".$tab2[$journee[$j]["E"][$m]-1]."][resultat1]' required ></td>
 			<td align='center'><input style='width: 25px;' type='text' patter='^abandon|[0-9]+$' name='scores[".$j."][".$tab2[$journee[$j]["D"][$m]-1]."][".$tab2[$journee[$j]["E"][$m]-1]."][resultat2]' required ></td>";

 		}
 		$sortie .= "   </tr>";
 	}
 	// Fermeture de la table
 	$sortie .= "</table>";
 	return $sortie;

 }
  ?>
