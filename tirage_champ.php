<?php
session_start();

if (!isset($_SESSION['login'])) {
	header ('Location: index.php');
	exit();
}

$championnat = $_GET['id'];
$connexion = new PDO('mysql:host=localhost;dbname=tournoi', 'root','');
$sql = $connexion->query("SELECT * FROM championnat WHERE idchamp = '$championnat'");
$sql->setFetchMode(PDO::FETCH_OBJ);
while ($resultat = $sql->fetch())
{
	$nb = $resultat->nbequipes;
	$nom = $resultat->nom;
}

?>
<html>
	<head>
		<meta charset="utf-8">
		<title>Liste tournois</title>
		<link href="styles.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<div align="center">
		<p class="titre">- Journées championnat : <?php echo $nom ?>-</p>
		<p align="center">

<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<script>
$(document).ready(function(){
	$('input[type="text"]').each(function(){
		$(this).val(Math.floor(Math.random() * 5) + 0);
	});
});
</script>
<?php

if ( $nb > 0 )
{
	echo "Nombre d'equipes : ".$nb."<br>\n";
	echo "Matchs aller uniquement<br>\n";
	echo journees_affiche ( journees_calcule ($nb), $championnat );
}

echo "</body></html>\n";
// Principe :
//   - pour n equipes (n pair)
//   - constituer un tableau T1 de 2 colonnes et n/2 lignes
//   - remplir le tableau en commencant en haut a gauche dans le sens anti-horaire
//      1   n
//      2  n-1
//      3  n-2
//       ...
//    n/2  n/2 +1
//   - a partir de T1, on cree T2 en gardant n fixe en haut a droite
//  et en faisant tourner les autres d'un cran dans le sens horaire
//      2     n
//      3     1
//      4     n-1
//         ...
//    n/2 +1  n/2 +2
//   - on repete l'operation pour avoir n-1 tableaux
//   - la correspondance entre les tableaux Ti et les journees de championnat Ji
//  est alors :
//     J1   -> T1
//     J2   -> Tn/2
//     J3   -> Tn-1
//     J4   -> Tn/2-1
//     J5   -> Tn-2
//     J6   -> Tn/2-2
//       ...
//     Jn-2 -> T2
//     Jn-1 -> Tn/2+1
//   - pour les journees paires, pour le 1er match de la journee, on inverse
//  Domicile et Exterieur (donc pour l'equipe n). Sinon, elle ferait tous ses matchs aller
//  a l'exterieur...

//------------------------------------
// Fonction journees_calcule
//
// Parametres : nb_equipe = nombre d'equipes
// Retour     : tableau des journees a 3 dimensions
//              pour une journee j, le match m donne
//              $tableau[$j]["D"][$m] recoit $tableau[$j]["E"][$m]
//
// Calcule les journees
//
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
//
//------------------------------------

//------------------------------------
// Fonction journees_affiche
//
// Parametres : tableau des journees a 3 dimensions
//              pour une journee j, le match m donne
//              $tableau[$j]["D"][$m] recoit $tableau[$j]["E"][$m]
// Retour     : chaine d'affichage des journees (table)
//
// Genere la table d'affichage des journess
//
function journees_affiche ($journee, $id_championnat)
{

	$ptsvic = $_GET['vic'];
	$ptsnul = $_GET['nul'];
	$ptsdef = $_GET['def'];
	$connexion = new PDO('mysql:host=localhost;dbname=tournoi', 'root','');
	$sql = $connexion->query("SELECT * FROM equipes WHERE idchamp = '$id_championnat'");
	$sql->setFetchMode(PDO::FETCH_OBJ);
	$nomequipes=array();
	while ($resultat = $sql->fetch())
	{
		$idequipes[] = $resultat->idequipe;
		$nomequipes[] = $resultat->nomequipe;
	}
	// Nombre de journees
	$nb_jour=sizeof($journee);
	// Largeur des colonnes
	$width=round(100/$nb_jour);
	// Nombre de matches ( (nombre de journees +1) / 2)
	$nb_match=($nb_jour+1)/2;


	$sortie="<form method='post' action='classement_champ.php?id=".$id_championnat."&amp;vic=".$ptsvic."&amp;nul=".$ptsnul."&amp;def=".$ptsdef."'><table width='auto' border='1'>";
	$sortie .= "   <tr>";
	for ($j=1; $j<=$nb_jour; $j++)
	{
		$sortie .= "     <td colspan='3' align='center' width='auto'><b>J ".$j."</b></td>\n";
	}
	$sortie .= "   </tr>";
	// Affichage des matches
	for ($m=1; $m<=$nb_match; $m++)
	{
		$sortie .= "   <TR>";
		for ($j=1; $j<=$nb_jour; $j++)
		{
			//recuperation des résultats des matchs dans la variable scores.
			$sortie .= "<td align='center' width='auto'>".$nomequipes[$journee[$j]["D"][$m]-1]." - ".$nomequipes[$journee[$j]["E"][$m]-1]."</td>
			<td align='center' width='auto'><input style='width: 25px;' type='text' name='scores[".$j."][".$idequipes[$journee[$j]["D"][$m]-1]."][".$idequipes[$journee[$j]["E"][$m]-1]."][resultat1]' required ></td>
			<td align='center' width='auto'><input style='width: 25px;' type='text' name='scores[".$j."][".$idequipes[$journee[$j]["D"][$m]-1]."][".$idequipes[$journee[$j]["E"][$m]-1]."][resultat2]' required ></td>";

		}
		$sortie .= "   </tr>";
	}
	$sortie .= "</table><p><input type='submit' name='Submit' value='Enregistrer les resultats' /></p></form>";
	return $sortie;

}

?>
</p></div></body></html>
