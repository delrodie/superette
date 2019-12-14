/**
 * Test de l'existence de la valeur
 * si valeur est supérieure ou egale a 0 alors garder la valeur
 * sinon affecter la valeur 0
 */
function existence(number) {
    if (number > 0){ return number; }
    else { return 0; }
}

/**
 * Calcul de la monnaie
 */
function calculMonnaie(total = null, verse = null) {
    if (total){ montantTotal = existence(total);}else{montantTotal = 0;}
    if (verse){ montantVerse = existence(verse);}else {montantVerse = 0;}
    var resultat = verse - total;

    return resultat;
}

/**
 * calcul de la remise
 */
function calculRemise(total = null, remise = null) {
    if (total){ montantTotal = existence(total);}else{montantTotal = 0;}
    if (remise){ montantVerse = existence(remise);}else {montantVerse = 0;}
    var reduction = total*remise*0.01; console.log(reduction);
    var resultat = total - reduction;

    return resultat;
}

// Traitement du formulaire selon la part assurance
function verses(){
    var verse = parseFloat(document.getElementById("facturation").elements["facturation_verse"].value.replace('%',''));
    var total = parseFloat(document.getElementById("facturation").elements["facturation_total"].value.replace('%',''));

    // Calcul de la monnaie
    var monnaieARendre = calculMonnaie(total,verse);


    if (!isNaN(verse)) {
        document.getElementById("facturation").elements["facturation_monnaie"].value = monnaieARendre;
        document.getElementById("montant_total").elements["total"].value = monnaieARendre;

    }else{
        alert("La remise saisie est incorrecte. Mettez uniquement des chiffres sans %")
    }
}

function reductions() {
    //var verse = parseFloat(document.getElementById("facturation").elements["facturation_verse"].value.replace('%',''));
    var remise = parseFloat(document.getElementById("facturation").elements["facturation_reduction"].value.replace('%',''));
    var total = parseFloat(document.getElementById("facturation").elements["interne"].value.replace('%',''));

    // Calcul de la monnaie
    var reduction = calculRemise(total,remise);


    if (!isNaN(remise)) {
        //document.getElementById("facturation").elements["facturation_monnaie"].value = new Intl.NumberFormat("ci-CI").format(monnaieARendre);
        document.getElementById("facturation").elements["facturation_total"].value = reduction;

    }else{
        alert("La remise saisie est incorrecte. Mettez uniquement des chiffres sans %")
    }
}
