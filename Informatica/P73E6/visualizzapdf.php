<?php
/*
Questo file visualizza il form in quale viene selezionato il proprietario per vedere il pdf dei immobili
del proprietario
*/

session_start();
if(!isset($_SESSION['loggato'])) $_SESSION['loggato'] = false;

require('require/funzioni.php');
require('require/openPage.php');

if($_SESSION['loggato']){
    writeMenu();
    ?>
    <form method="get" action="pdf.php">
        <div class="mb-3">
            <label>Seleziona il proprietario</label>
            <select id="selprop" name="idP"></select> <!-- Codice sviluppato in require/package.js per ottenere le opzioni -->
            <input type="hidden" name="sc" value="pdf">
            <input type="submit" class="btn btn-outline-success" value="PDF">
        </div>
    </form>
    <?php
}
require("require/closePage.php");
?>

