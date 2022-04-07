<?php
/* Questa pagina è dedicata alla gestione di Zone e Tipologie, le operazioni che compie sono:
   - Visualizzazione di tutte le zone presenti nel database.
   - Visualizzazione del form per inserire una nuova zona.
      - Inserimento dei dati di una nuova zona nella rispettiva tabella.
   - Visualizzazione del form per inserire una nuova tipologia.
      - Inserimento di una nuova tipologia nella rispettiva tabella.
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
			$db = new mysqli(DBHOST, DBUSER, DBPASSWORD, DBNAME);
			echo("<div class=\"container\">
				<div class=\"row\">
					<div class=\"col\">");
			$sql = "SELECT id, nome AS 'Nome Zona' FROM p73e6_zona";
			if($rs = $db->query($sql))
				showResultTable($rs,"","","","zone.php","formmodificazona","zone.php","cancellazona");
			else echo("--");
			
			echo("<div class=\"alert alert-success\" role=\"alert\">
					Aggiungi Zona
					</div>
					<form action=\"zone.php\" method=\"get\">
						<div class=\"mb-3\">
							<label for=\"zonaInput\" class=\"form-label\">Nome Zona</label>
							<input class=\"form-control\" type=\"text\" name=\"nomeZona\" placeholder=\"\" aria-label=\"default input example\">
						</div>
						<input type=\"hidden\" name=\"sc\" value=\"addZona\">
						<button type=\"submit\" class=\"btn btn-primary\">Inserisci</button>
					</form>
				</div>
				<div class=\"col\">");
			$sql = "SELECT id, nome AS 'Nome Tipologia' FROM p73e6_tipologia ORDER BY nome ASC";
			if($rs = $db->query($sql))
				showResultTable($rs,"","","","zone.php","formmodificatipologia","zone.php","cancellatipologia");
			else echo("--");
			echo("<div class=\"alert alert-danger\" role=\"alert\">
                    Aggiungi Tipologia
					</div>
					<form action=\"zone.php\" method=\"get\">
						<div class=\"mb-3\">
							<label for=\"zonaInput\" class=\"form-label\">Nome Tipologia</label>
							<input class=\"form-control\" type=\"text\" name=\"nomeTipologia\" placeholder=\"\" aria-label=\"default input example\">
						</div>
						<input type=\"hidden\" name=\"sc\" value=\"addTipologia\">
						<button type=\"submit\" class=\"btn btn-primary\">Inserisci</button>
					</form>
				</div>
            </div>
			</div>");
			$db->close();
			break;
		}
		case "addZona":{
			$n = $_REQUEST['nomeZona'];
			$db = new mysqli(DBHOST, DBUSER, DBPASSWORD, DBNAME);
			$sql = "INSERT INTO p73e6_zona(nome) VALUES('$n')";
			if($db->query($sql))
				echo("<div class=\"alert alert-success\" role=\"alert\">Zona aggiunta.</div>");
			else
				echo("<div class=\"alert alert-danger\" role=\"alert\">Problema.</div>");
			$db->close();
			break;
		}
		case "addTipologia":{
			$n = $_REQUEST['nomeTipologia'];
			$db = new mysqli(DBHOST, DBUSER, DBPASSWORD, DBNAME);
			$sql = "INSERT INTO p73e6_tipologia(nome) VALUES('$n')";
			if($db->query($sql))
				echo("<div class=\"alert alert-success\" role=\"alert\">Tipologia aggiunta.</div>");
			else
				echo("<div class=\"alert alert-danger\" role=\"alert\">Problema.</div>");
			$db->close();
			break;
		}
		case "formmodificazona":{
			$idR = $_REQUEST['idRecord'];
			
			$db = new mysqli(DBHOST, DBUSER, DBPASSWORD, DBNAME);
			$sql = "SELECT * FROM p73e6_zona WHERE  id=$idR";
			$rs = $db->query($sql);
			$record = $rs->fetch_assoc();
			
			echo("
				<form action=\"zone.php\" method=\"get\">
					<div class=\"mb-3\">
						<label for=\"zonaInput\" class=\"form-label\">Nome</label>
						<input class=\"form-control\" type=\"text\" name=\"nomeZona\" placeholder=\"\" aria-label=\"default input example\" value=\"".$record['nome']."\">
					</div>
					<input type=\"hidden\" name=\"idR\" value=\"$idR\">
					<input type=\"hidden\" name=\"sc\" value=\"modificazona\">
					<button type=\"submit\" class=\"btn btn-primary\">Modifica</button>
				</form>
			");		
			break;
		}
		case "modificazona":{
			$idR = $_REQUEST['idR'];
			$nome = $_REQUEST['nomeZona'];
			
			$db = new mysqli(DBHOST, DBUSER, DBPASSWORD, DBNAME);
			$sql = "UPDATE p73e6_zona SET nome='$nome' WHERE id=$idR";
			
			if($db->query($sql))
				echo("<div class=\"alert alert-success\" role=\"alert\">Modificato</div>");
			else
				echo("<div class=\"alert alert-danger\" role=\"alert\">Errore nella modifica</div>");
			$db->close();
			break;
		}
		case "cancellazona":{
			$idR = $_REQUEST['idRecord'];
		
			$db = new mysqli(DBHOST, DBUSER, DBPASSWORD, DBNAME);
			$sql = "DELETE FROM p73e6_zona WHERE id=$idR";
			if($db->query($sql))
				echo("<div class=\"alert alert-success\">Cancellato</div>");
			else{
				echo("<div class=\"alert alert-danger\">Errore</div>");
			}
			break;
		}
		
		case "formmodificatipologia":{
			$idR = $_REQUEST['idRecord'];
			
			$db = new mysqli(DBHOST, DBUSER, DBPASSWORD, DBNAME);
			$sql = "SELECT * FROM p73e6_tipologia WHERE  id=$idR";
			$rs = $db->query($sql);
			$record = $rs->fetch_assoc();
			
			echo("
				<form action=\"zone.php\" method=\"get\">
					<div class=\"mb-3\">
						<label for=\"TipologiaInput\" class=\"form-label\">Nome</label>
						<input class=\"form-control\" type=\"text\" name=\"nomeTipologia\" placeholder=\"\" aria-label=\"default input example\" value=\"".$record['nome']."\">
					</div>
					<input type=\"hidden\" name=\"idR\" value=\"$idR\">
					<input type=\"hidden\" name=\"sc\" value=\"modificatipologia\">
					<button type=\"submit\" class=\"btn btn-primary\">Modifica</button>
				</form>
			");		
			break;
		}
		case "modificatipologia":{
			$idR = $_REQUEST['idR'];
			$nome = $_REQUEST['nomeTipologia'];
			
			$db = new mysqli(DBHOST, DBUSER, DBPASSWORD, DBNAME);
			$sql = "UPDATE p73e6_tipologia SET nome='$nome' WHERE id=$idR";
			
			if($db->query($sql))
				echo("<div class=\"alert alert-success\" role=\"alert\">Modificato</div>");
			else
				echo("<div class=\"alert alert-danger\" role=\"alert\">Errore nella modifica</div>");
			$db->close();
			break;
		}
		case "cancellatipologia":{
			$idR = $_REQUEST['idRecord'];
		
			$db = new mysqli(DBHOST, DBUSER, DBPASSWORD, DBNAME);
			$sql = "DELETE FROM p73e6_tipologia WHERE id=$idR";
			if($db->query($sql))
				echo("<div class=\"alert alert-success\">Cancellato</div>");
			else{
				echo("<div class=\"alert alert-danger\">Errore</div>");
			}
			break;
		}
	}
}
require("require/closePage.php"); // contiene il codice HTML standard di chiusura di ogni pagina.
?>