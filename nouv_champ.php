<?php
session_start();
if (!isset($_SESSION['login'])) {
    header ('Location: index.php');
    exit();
}
require_once('connexion.php');

?>
<html>
<head>
    <script type="text/javascript" src="check.js"></script>
    <title>Création d'un championnat</title>
    <meta http-equiv="Content-Type" charset="utf-8">
    <link href="styles.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="add" method="post" action="participants.php" onSubmit="return verif()">
  <div style="margin-top:100px" align="center">
    <p class="titre">Création d'un championnat </p>
        <table width="60%"  border="0" cellspacing="0" bordercolor="#999999">
            <tr>
                <td><strong>Sport</strong></td>
                <td>
                    <select name="sport" id="sport">
                        <option value="foot">Football</option>
                        <option value="tennis">Tennis</option>
                        <option value="rugby">Rugby</option>
                        <option value="hand">Handball</option>
                        <option value="volley">Volley-Ball</option>
                        <option value="basket">Basketball</option>
                        <option value="bad">Badmington</option>
                        <option value="ping-pong">Tennis de table</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong>Nom du tournoi</strong></td>
                <td><input name="nom_champ" type="text" value=""></td>
            </tr>
            <tr>
                <td><strong>Catégorie</strong></td>
                <td>        <select name="categorie">
                        <option>Seniors</option>
                        <option>F&eacute;minines</option>
                        <option>Juniors</option>
                        <option>Benjamins</option>
                        <option>Cadets</option>
                        <option>Minimines</option>
                    </select></td>
            </tr>
            <tr>
                <td><strong>Calendrier</strong></td>
                <td>        <select name="calendrier">
                        <option>Aller</option>
                        <option>Retour</option>
                    </select></td>
            </tr>
            <tr>
                <td><strong>Format de la compétition</strong></td>
                <td>
                    <select name="format" id="format" onchange="iscoched()">
                        <option value="championnat" selected>Championnat</option>
                        <option value="poule">Poules + elimination directe</option>
                        <option value="elimination">Elimination directe</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong>Nombre de participants</strong></td>
                <td>
                    <input type="text" name="nb" id="nb">
                </td>
            </tr>
            <tr>
                <td><strong>Nombre de participants</strong></td>
                <td>
                    <select name="nbr_equip" id="nbr_equip" disabled>
                        <option value="8">8</option>
                        <option value="16">16</option>
                        <option value="32">32</option>
                        <option value="64">64</option>
                        <option value="128">128</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong>Points pour la victoire</strong></td>
                <td>
                  <input type="number" name="ptsvic" id="ptsvic">
                </td>
            </tr>
            <tr>
                <td><strong>Point(s) pour le match nul</strong></td>
                <td>
                  <input type="number" name="ptsnul" id="ptsnul">
                </td>
            </tr>
            <tr>
                <td><strong>Point(s) pour la defaite</strong></td>
                <td>
                  <input type="number" name="ptsdef" id="ptsdef">
                </td>
            </tr>
            <tr>
                <td><strong>Nombre de poules</strong></td>
                <td>
                  <input type="number" name="nbpoule" id="nbpoule" disabled>
                </td>
            </tr>
            <tr>
                <td><strong>Nombre de participants par poule</strong></td>
                <td>
                  <input type="number" name="nb_parpoule" id="nb_parpoule" disabled>
                </td>
            </tr>
            <tr>
                <td><strong>Nombre de qualifiés par poule</strong></td>
                <td>
                  <input type="number" name="nb_qualif" id="nb_qualif" disabled>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td ><input type="submit" name="Submit" value="Valider"></td>
            </tr>
        </table>
        <p><a href="../membre.php"><strong>&lt; Retour accueil</strong></a></p>
</form>
</div>
</body>
<footer>

</footer>
</html>
