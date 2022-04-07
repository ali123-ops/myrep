<?php
/*  #################################
  #  COSTATI DI CONNESSIONE MYSQL #
  #################################*/
  /* Il valore delle costanti deve essere
  cambato in accordo con i dati del mySQL in uso */
define("DBHOST","localhost");
define("DBUSER","root");
define("DBPASSWORD","");
define("DBNAME","gestione_immobili");

/*  ###################
  #  FUNZIONI VARIE #
  ###################*/
/** Visualizza il menu principale di navigazione.
 */
function writeMenu(){
    echo("
    <nav class=\"navbar navbar-expand-lg navbar-light bg-light\">
    <div class=\"container-fluid\">
      <button class=\"navbar-toggler\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#navbarSupportedContent\" aria-controls=\"navbarSupportedContent\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">
        <span class=\"navbar-toggler-icon\"></span>
      </button>
      <div class=\"collapse navbar-collapse\" id=\"navbarSupportedContent\">
        <ul class=\"navbar-nav me-auto mb-2 mb-lg-0\">
          <li class=\"nav-item\">
            <a class=\"nav-link\" aria-current=\"page\" href=\"index.php\">Home</a>
          </li>

          <li class=\"nav-item dropdown\">
            <a class=\"nav-link dropdown-toggle\" href=\"#\" id=\"navbarDropdown\" role=\"button\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">
            Proprietari
            </a>
            <ul class=\"dropdown-menu\" aria-labelledby=\"navbarDropdown\">
              <li><a class=\"dropdown-item\" href=\"proprietari.php?sc=listaProprietari\">Lista Proprietari</a></li>
              <li><a class=\"dropdown-item\" href=\"proprietari.php?sc=nuovoProprietario\">Nuovo Proprietario</a></li>
            </ul>
          </li>

          <li class=\"nav-item\">
            <a class=\"nav-link\" href=\"zone.php\">Zone e Tipologie</a>
          </li>

          <li class=\"nav-item dropdown\">
            <a class=\"nav-link dropdown-toggle\" href=\"#\" id=\"navbarDropdown\" role=\"button\" data-bs-toggle=\"dropdown\" aria-expanded=\"false\">
            Immobili
            </a>
            <ul class=\"dropdown-menu\" aria-labelledby=\"navbarDropdown\">
              <li><a class=\"dropdown-item\" href=\"immobili.php?sc=listaImmobili\">Lista Immobili</a></li>
              <li><a class=\"dropdown-item\" href=\"immobili.php?sc=nuovoImmobile\">Nuovo Immobile</a></li>
            </ul>
          </li>

          <li class=\"nav-item\">
            <a class=\"nav-link\" href=\"ricerca.php\">Ricerca</a>
          </li>

          <li class=\"nav-item\">
            <a class=\"nav-link\" href=\"visualizzapdf.php\">Visualizza PDF</a>
          </li>
          
          <li class=\"nav-item\">
            <a class=\"nav-link\" href=\"index.php?sc=logout\">Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <br />");
}
/**
 * Visualizza sottoforma di tabella una resultSet derivata da una query mySQL.
 * @param object $resultSet Risultato della query da visualizzare.
 * @param string $caption La caption della tabella.
 * @param string $detailPage La pagina da richiamare per i dettagli.
 * @param string $detailCase Costante letterale per il case di dettagli.
 * @param string $editPage La pagina da richiamare per la modifica.
 * @param string $editCase Costante letterale per il case di modifica.
 * @param string $deletePage La pagina da richiamare per la cancellazione di un record.
 * @param string $deleteCase Costante letterale per il case di cancellazione.
 */
function showResultTable($resultSet, $caption=null, $detailPage=null, $detailCase=null, $editPage=null, $editCase=null, $deletePage=null, $deleteCase=null){
  if($resultSet && $resultSet->num_rows){
    $record = $resultSet->fetch_assoc();
    $keys = array_keys($record);
    /*for($i=0; $i<count($keys); $i++)
      echo($keys[$i]." ");*/

    echo("<table class=\"table table-striped\">");
      if($caption) echo("<caption>$caption</caption>");
      echo("<thead>");
        echo("<tr>");
          //echo("<th scope=\"col\">#</th>");
          for($i=0; $i<count($keys); $i++)
            echo("<th scope=\"col\">".$keys[$i]."</th> ");
        echo("</tr>");
      echo("</thead>");
      echo("<tbody>");
      while($record){
        echo("<tr>");
        for($i=0; $i<count($keys); $i++)
          if($record[$keys[$i]]==null){
            echo("<td>--</td>");
          }else  echo("<td>".$record[$keys[$i]]."</td>");
        if($detailPage)
          echo("<td><a href=\"$detailPage?sc=$detailCase&idRecord=".$record[$keys[0]]."\">Details</a></td>");
        if($editPage)
          echo("<td><a href=\"$editPage?sc=$editCase&idRecord=".$record[$keys[0]]."\">Edit</a></td>");
        if($deletePage)
          echo("<td><a href=\"$editPage?sc=$deleteCase&idRecord=".$record[$keys[0]]."\">Delete</a></td>");
        echo("</tr>");
        $record = $resultSet->fetch_assoc();
      }
      echo("</tbody>");
    echo("</table>");
  }
}
?>