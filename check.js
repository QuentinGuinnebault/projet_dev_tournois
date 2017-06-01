function iscoched() {
    var answer=document.getElementById("format");
    if(answer[answer.selectedIndex].value=="championnat"){
        document.getElementById("nb").disabled = false;
        document.getElementById("nbr_equip").disabled = true;
        document.getElementById("ptsvic").disabled = false;
        document.getElementById("ptsnul").disabled = false;
        document.getElementById("ptsdef").disabled = false;
        document.getElementById("nbpoule").disabled = true;
        document.getElementById("nb_parpoule").disabled = true;
        document.getElementById("nb_qualif").disabled = true;
    } else if(answer[answer.selectedIndex].value=="elimination"){
      document.getElementById("nb").disabled = true;
      document.getElementById("nbr_equip").disabled = false;
      document.getElementById("ptsvic").disabled = true;
      document.getElementById("ptsnul").disabled = true;
      document.getElementById("ptsdef").disabled = true;
      document.getElementById("nbpoule").disabled = true;
      document.getElementById("nb_parpoule").disabled = true;
      document.getElementById("nb_qualif").disabled = true;
    }else{
      document.getElementById("nb").disabled = false;
      document.getElementById("nbr_equip").disabled = true;
      document.getElementById("ptsvic").disabled = false;
      document.getElementById("ptsnul").disabled = false;
      document.getElementById("ptsdef").disabled = false;
      document.getElementById("nbpoule").disabled = false;
      document.getElementById("nb_parpoule").disabled = false;
      document.getElementById("nb_qualif").disabled = false;
  }
}

function verif (){
  var answer=document.getElementById("format");
  if(answer[answer.selectedIndex].value=="championnat"){
    if(document.add.nom_champ.value == "")
    {
      alert("Veuillez remplir le champ Nom du tournoi");
      document.add.nom_champ.focus();
      return false;
    }
    if(document.add.nb.value == "")
    {
      alert("Veuillez indiquer le nombre de participants");
      document.add.nb.focus();
      return false;
    }
    if(document.add.ptsvic.value == "")
    {
      alert("Veuillez indiquer les points d'une victoire");
      document.add.ptsvic.focus();
      return false;
    }
    if(document.add.ptsnul.value == "")
    {
      alert("Veuillez indiquer le(s) point(s) d'un match nul");
      document.add.ptsnul.focus();
      return false;
    }
    if(document.add.ptsdef.value == "")
    {
      alert("Veuillez indiquer le(s) point(s) d'une defaite");
      document.add.ptsdef.focus();
      return false;
    }
  }else if(answer[answer.selectedIndex].value=="poule"){
    if(document.add.nom_champ.value == "")
    {
      alert("Veuillez remplir le champ Nom du tournoi");
      document.add.nom_champ.focus();
      return false;
    }
    if(document.add.nb.value == "")
    {
      alert("Veuillez indiquer le nombre de participants");
      document.add.nb.focus();
      return false;
    }
    if(document.add.ptsvic.value == "")
    {
      alert("Veuillez indiquer les points d'une victoire");
      document.add.ptsvic.focus();
      return false;
    }
    if(document.add.ptsnul.value == "")
    {
      alert("Veuillez indiquer le(s) point(s) d'un match nul");
      document.add.ptsnul.focus();
      return false;
    }
    if(document.add.ptsdef.value == "")
    {
      alert("Veuillez indiquer le(s) point(s) d'une defaite");
      document.add.ptsdef.focus();
      return false;
    }
    if(document.add.nbpoule.value == "")
    {
      alert("Veuillez indiquer le nombre de poules");
      document.add.nbpoule.focus();
      return false;
    }
    if(document.add.nb_parpoule.value == "")
    {
      alert("Veuillez indiquer le nombre de participants poules");
      document.add.nb_parpoule.focus();
      return false;
    }
    if(document.add.nb_qualif.value == "")
    {
      alert("Veuillez indiquer le nombre de qualifiés par poule");
      document.add.nb_qualif.focus();
      return false;
    }
  }else{
    if(document.add.nom_champ.value == "")
    {
      alert("Veuillez remplir le champ Nom du tournoi");
      document.add.nom_champ.focus();
      return false;
    }
  }
}
