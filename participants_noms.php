<form name="add" method="post" action="insert_participants.php" onSubmit="return verif()">
	<div style="margin-top:100px" align="center">
  <p class="titre">Enregistrement des participants</p>
  <table width="60%"  border="0" cellspacing="0" bordercolor="#999999">
	<?php  for($i = 1; $i <= $nb; $i++){?>
	<tr>
      <td><strong>Participant <?php echo $i; ?></strong></td>
      <td><input name="equipe<?php echo $i; ?>" type="text" value=""></td>
  </tr>
	<?php } ?>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td ><input type="submit" name="Submit" value="Valider"></td>
	</tr>
	</table>
  	<p><a href="../index.php"><strong>&lt; Annuler</strong></a></p></div>
	<input type="hidden" name="id" value=<?php echo "$idchamp";?>>
</form>
