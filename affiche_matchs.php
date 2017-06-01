<?php
require_once 'connexion.php';
function affiche_match($infos) {// fonction d'affichage html qui prend en entrée les 2 équipes d'un match
    global $connexion;
    $sql = "SELECT nomequipe FROM equipes WHERE idequipe = :id";
    $tmp = $connexion->prepare($sql);
    $tmp->execute(array(':id' => $infos['equipe1']));
    $nomequipe1 = $tmp->fetch(PDO::FETCH_COLUMN);
    $tmp->execute(array(':id' => $infos['equipe2']));
    $nomequipe2 = $tmp->fetch(PDO::FETCH_COLUMN);
    $html = "";
    $html .= "<tr><td align='center'>".$nomequipe1."</td>";
    $html .= "<td><input style='width: 25px;' type='text' name='".$infos['equipe1']."' value='".$infos['resultat1']."'/> </td>";// on récupère le nom de l'équipe1 via le name de l'input ainsi que la valeur du resultat de l'equipe 1 qui sera ajouté dans la table matchs
    $html .= "<td><input style='width: 25px;' type='text' name='".$infos['equipe2']."' value='".$infos['resultat2']."'/> </td>";// on récupère le nom de l'équipe2 via le name de l'input ainsi que la valeur du resultat de l'equipe 2 qui sera ajouté dans la table matchs
    $html .= "<td align='center'>".$nomequipe2."</td></tr>";
    return $html;
}

function create_day($idchamp, $journee, $gagnants=null)
{
    global $connexion;
    if ($gagnants == null) {
        $sql = "SELECT idequipe FROM equipes WHERE idchamp='$idchamp' AND ptselim='$journee'";
        $gagnants = $connexion->query($sql)->fetchAll(PDO::FETCH_COLUMN);
        $journee++;
    }

    if (sizeof($gagnants) > 2) {
        $nbr_tour = (sizeof($gagnants) / 2);// lorsque le nombre total de qualifiè est divisible par 2 (si ce n'est pas une finale)

        for ($i = 0; $i < $nbr_tour; $i++) {// boucle qui attribue de manière aléatoire les matchs (pour un quart de finale il y aura donc 4 tours)

            $alea = rand(1, sizeof($gagnants) - 1);
            $equipe1 = $gagnants[0];// l'équipe1 est toujours la première de la liste
            $equipe2 = $gagnants[$alea];// l'équipe 2 est une equipe aléatoire
            $sql = "INSERT INTO matchs (equipe1, equipe2, idjournee, idchamp) VALUES ('$equipe1', '$equipe2', '$journee', '$idchamp')";// on insert le match dans la table "matchs"
            $connexion->query($sql);
            unset($gagnants[0]);// on retire l'equipe1
            unset($gagnants[$alea]);// on retire l'equipe2
            $gagnants = reorganise_array($gagnants);// appel de la fonction reorganise_array() qui repositionne la première valeur à la clé 0

        }
    } else {//s'il ne reste plus que 2 équipes il n'y a plus besoin d'attribuer les matchs aléatoirement
        $equipe1 = $gagnants[0];
        $equipe2 = $gagnants[1];
        $sql = "INSERT INTO matchs (equipe1, equipe2, idjournee, idchamp) VALUES ('$equipe1', '$equipe2', '$journee', '$idchamp')";
        $connexion->query($sql);
    }
}
function reorganise_array($tab) {
    $result = array();
    foreach ($tab as $value) {
        array_push($result, $value);
    }
    return $result;
}


function get_infos_championnat($id) {// récupère les infos de la table "championnat" selon l'idchamp
    $sql = "SELECT * FROM championnat WHERE idchamp = ".$id;
    global $connexion;
    $infos = $connexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    return $infos[0];
}
