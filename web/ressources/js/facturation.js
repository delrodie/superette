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

// Traitement du formulaire selon la part assurance
function verse(){
    var verse = parseFloat(document.getElementById("facturation").elements["facturation_verse"].value.replace('%',''));
    var total = parseFloat(document.getElementById("facturation").elements["facturation_total"].value.replace('%',''));

    // Calcul de la monnaie
    var monnaieARendre = calculMonnaie(total,verse);


    if (!isNaN(verse)) {
        document.getElementById("facturation").elements["facturation_monnaie"].value = new Intl.NumberFormat("ci-CI").format(monnaieARendre);

    }else{
        alert("La remise saisie est incorrecte. Mettez uniquement des chiffres sans %")
    }
}
