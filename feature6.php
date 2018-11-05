<?php
	#parametri passati alla pagina : 	
	#variabili ricevute:
		#nome_giocatore_corrente
		#carta_giocata 
		#carta_evento (numero positivo o negativo)

		#servURL (costante)
		#carattereSeparatore (costante) -> utilizzato per separare i vari campi di un array quando convertito in stringa 

		#-------------------------------------------------

		#STRUTTURA ARRAY PASSATO ALLA PAGINA ATTRAVERSO LA FUNZIONE invia_dati:

		#params = array(
			#nome_giocatore_corrente ->char* nome_giocatore_corrente
			#carta_cliccata -> int nCarta
			#carta_evento -> int nCarta  
		#	)	

	
	#PASSO IL NOME DEL GIOCATORE E LA CARTA GIOCATA AL DB, POI ASPETTO 
	require "sqlite3.dll";

	#apro il db
	$db = sqlite_open("partita_accipicchia.db, 066, "impossibile aprire il DB");

	#controllo che l'apertura sia avvenuta con successo
	if($db)
	{
		#in caso positivo aggiorno il la tabella "giocatori" del db
		$carta_giocata = $_POST['carta_giocata']
		$nome_giocatore_corrente = $_POST['nome_giocatore_corrente']
		
		sqlite_query($db, "	UPDATE giocatori
			  		SET 	cartaGiocata = ".$carta_giocata."
			  		WHERE 	nome_giocatore = ".$nome_giocatore_corrente);
