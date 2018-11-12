<?php
	#parametri passati alla pagina : 	
	#variabili ricevute:
		#nome_giocatore_corrente
		#carta_giocata 
		#numero_round
		#carte_in_mano
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
	
	#DEFINE
	define("CARATTERE_SEPARATORE", ";");
	define("SERV_URL", "");

	#--------------------------------------------------------------------------------------------------------------
	# FUNZIONI
	function aggiorna_n_round($db, $nome_giocatore_corrente, $n_round)
	{
		#prima di aggiornare il DB controllo che non ci siano errori di conteggio nel numero del round
		$n_round_prec = sqlite_query($db, "	SELECT n_round
							FROM 	giocatori
							WHERE 	nome_giocatore = '".$nome_giocatore."'" ,
					     		"impossibile aprire il db -2"
					     );
		if($n_round_prec == $n_round)
		{
			#se non ci sono stati errori posso passare all'aggiornamento del DB
			#i campi che vanno aggiornati sono il numero di round, la carta appena giocata e le carte ancora disponibili
			sqlite_query($db, "	UPDATE giocatori
						SET 	carta_giocata = ".$carta_giocata.", n_round = n_round +1
						WHERE 	nome_giocatore = '".$nome_giocatore_corrente."'" , 
						"impossibile aggiornare il DB");
			$ret = false;
		}else
			$ret = true;
		}

		return $ret;
	}

	function attesa_altri_giocatori($db, $n_round)
	{
		while(sqlite_quesry( $db, "	SELECT 	MIN(n_round)
			  		FROM 	giocatori" ,
					"impossibile aprire il DB 2") != $
	}	      

	
	#--------------------------------------------------------------------------------------
	# MAIN

	#PASSO IL NOME DEL GIOCATORE E LA CARTA GIOCATA AL DB, POI ASPETTO 
	require "sqlite3.dll";
	$db = sqlite_open("partita_accipicchia.db, 066, "impossibile aprire il DB");   #apro il db

	#controllo che l'apertura sia avvenuta con successo
	if($db)
	{
		#in caso positivo aggiorno il la tabella "giocatori" del db 
		$carta_giocata = $_POST['carta_giocata'];
		$nome_giocatore_corrente = $_POST['nome_giocatore_corrente'];
		$n_round = $_POST['n_round'];
		
		#prima di aggiornare il DB controllo che non ci siano errori di conteggio nel numero del round
		#se la funzione sotto ritorna vero, ci sono errori
		if(!aggiorna_n_round($db, $nome_giocatore, $n_round))	
		{			
			#attendo che tutti i giocatori siano allineati allo stesso round 
			attesa_altri_giocatori($db, $n_round);			
		}else
		{
			echo "i punteggi della partita sono stati compromessi";
		}	
	}
	
	
