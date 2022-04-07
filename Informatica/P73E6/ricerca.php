<?php
/* Questa pagina è dedicata alla gestione delle ricerche.
/  VEDERE:
   Ajax =>  https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API
            https://developer.mozilla.org/en-US/docs/Web/API/fetch
            https://developer.mozilla.org/en-US/docs/Web/API/Response
   
   SQL => JOIN https://blog.codinghorror.com/a-visual-explanation-of-sql-joins/
*/
session_start();
if(isset($_REQUEST['sc'])) $sc = $_REQUEST['sc']; else $sc=null;
if(!isset($_SESSION['loggato'])) $_SESSION['loggato'] = false;

require("require/funzioni.php");   // contiene funzioni sviluppate per le diverse pagine.
require("require/openPage.php");   // contiene il codice HTML standard di apertura di ogni pagina.

if($_SESSION['loggato']){
   writeMenu();

   switch($sc){
      default:{
         ?>
         <div class="container">
            <div class="row">
               <div class="col">
                  <form method="get" action="ricerca.php">
                     <div class="mb-3">
                        <label>Seleziona la zona</label>
                        <select id="ricerca" name="idZona"></select>
                        <input type="hidden" name="sc" value="resultZona">
                        <input type="submit" class="btn btn-outline-success" value="Search">
                     </div>
                  </form>
               </div>
               <div class="col">
                  <h3 class="text-center">OR</h3>
               </div>
               <div class="col">
                  <form class="d-flex">
                     <input class="form-control me-2" type="search" placeholder="Inserisci il nome del proprietario" name="nomeP">
                     <input type="hidden" name="sc" value="resultProprietario">
                     <button class="btn btn-outline-success" type="submit">Search</button>
                  </form>
               </div>
            </div>
         </div>
         <?php
         break;
      }
      // Case risultato della ricerca partendo da una zona
      case "resultZona":{
         $idZ= $_REQUEST['idZona'];
         // Tabella immobili e relativi proprietari
         $db = new mysqli(DBHOST, DBUSER, DBPASSWORD, DBNAME);
         $sql = "SELECT i.nome, i.via, i.civico, i.metratura, i.piano, i.locali, t.nome AS tipologia, pr.nome AS Proprietario
                  FROM p73e6_immobile AS i
                  LEFT OUTER JOIN p73e6_possiede AS po
                  ON i.id = po.idImmobile
                  LEFT OUTER JOIN p73e6_proprietario AS pr
                  ON po.idProprietario=pr.id
                  JOIN p73e6_tipologia AS t
                  ON i.idTipologia=t.id
                  WHERE i.idZona='$idZ'";
         $rs= $db->query($sql);
         showResultTable($rs, "Immobili");
         $db->close();

         //proprietari dei immobili di una zona cercata
         $db = new mysqli(DBHOST, DBUSER, DBPASSWORD, DBNAME);
         $sql = "SELECT pr.cognome, pr.nome, i.nome AS immobile
               FROM p73e6_immobile AS i, p73e6_zona AS z, p73e6_proprietario AS pr, p73e6_possiede AS po
               WHERE i.idZona=z.id AND z.id='$idZ' AND po.idImmobile=i.id AND po.idProprietario=pr.id";
         $rs= $db->query($sql);
         showResultTable($rs, "Proprietari");
         $db->close();

         echo("<a href=\"ricerca.php\"><input type=\"button\" class=\"btn btn-primary\"value=\"Back\"></a>");
         break;
      }

      // Lista di immobili di un proprietario
      case "resultProprietario":{
         $nome= $_REQUEST['nomeP'];

         $db = new mysqli(DBHOST, DBUSER, DBPASSWORD, DBNAME);
         $sql = "SELECT i.nome AS immobile, i.via, i.civico, i.metratura, i.piano, i.locali, z.nome AS zona, t.nome AS tipologia, pr.nome, pr.cognome
               FROM p73e6_proprietario AS pr, p73e6_possiede AS po, p73e6_immobile AS i, p73e6_zona AS z, p73e6_tipologia AS t
               WHERE pr.nome='$nome' AND po.idProprietario=pr.id AND po.idImmobile=i.id AND i.idZona=z.id AND i.idTipologia=t.id";
         $rs= $db->query($sql);
         showResultTable($rs, "Immobili del proprietaro");
         $db->close();

         echo("<a href=\"ricerca.php\"><input type=\"button\" class=\"btn btn-primary\"value=\"Back\"></a>");
         break;
      }
   }
}
require("require/closePage.php"); // contiene il codice HTML standard di chiusura di ogni pagina.
?>