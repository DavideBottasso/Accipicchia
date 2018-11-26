<?php
	#parametri passati alla pagina : 	
	#variabili ricevute:
		#nome_giocatore_corrente
		#carta_giocata 
		#numero_round
		#carte_in_mano
		#carta_evento (numero positivo o negativo)

	#costanti:
		#SERV_URL (costante)
		#CARATTERE_SEPARATORE (costante) -> utilizzato per separare i vari campi di un array quando convertito in stringa
		#KEY_IPC_SEM_MAIN (costante) -> key della ipc area contenente il semaforo che decide quale processo eseguirà
		# 				le funzioni del main che riguardano l'aggiornamento dei punteggi dei giocatori.
		#				E' sufficiente che un solo processo del server si occupi di ciò.

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
	define("KEY_IPC_SEM_MAIN", 123);

	#--------------------------------------------------------------------------------------------------------------
	# FUNZIONI
	function aggiorna_n_round($db, $nome_giocatore_corrente, $n_round)
	{
		#prima di aggiornare il DB controllo che non ci siano errori di conteggio nel numero del round.
		#eseguo questo controllo confrontando il numero round passato dal clent del giocatore con il numero
		#round precedente scritto nella tupla del giocatore
		$n_round_prec = sqlite_query($db, "	SELECT n_round
							FROM 	giocatori
							WHERE 	nome_giocatore = '".$nome_giocatore."'" ,
					     		"impossibile aprire il db -2");
					     );
		if($n_round_prec == $n_round)
		{
			#se non ci sono stati errori posso passare all'aggiornamento del DB
			#i campi che vanno aggiornati sono il numero di round, la carta appena giocata e le carte ancora disponibili
			sqlite_query($db, "	UPDATE giocatori
						SET 	carta_giocata = ".$carta_giocata.", n_round = n_round +1
						WHERE 	nome_giocatore = '".$nome_giocatore_corrente."'" , 
						"impossibile aggiornare il DB -3");
			$ret = false;
		}else
			$ret = true;
		}

		return $ret;
	}

	#ATTESA_ALTRI_GIOCATORI()
	#questa funzione contiene un ciclo che esegue interrogazioni continue al DB (non regolate da semaforo perchè
	#sto solo facendo lettura) e che cicla fino a quando il risultato della query non è uguale al numero round
	#passato, ovvero fino a quando tutti i client giocatori non hanno aggiornato la loro tupla del DB allineandosi 
	#con gli altri
	function attesa_altri_giocatori($db, $n_round)
	{
		$n_round_min = sqlite_quesry( $db, "	SELECT 	MIN(n_round)
			  		FROM 	giocatori" ,
					"impossibile aprire il DB -4");
		
)		while($n_round_min != $n_round)
		{
			$n_round_min = sqlite_quesry( $db, "	SELECT 	MIN(n_round)
			  		FROM 	giocatori" ,
					"impossibile aprire il DB -5");
		}	
	}


	#AGGIORNA_PUNTEGGI()
	#questa funzione è eseguita solo dal processo "amministratore" secondo le regole dettate dal semaforo
	#(che assegna questa funzione al primo processo che fa lock sul semaforo).
	#RETURN: nessuno perchè i nuovi punteggi dei vari giocatori vengono scritti direttamente nel db sovrascrivendo
	#quelli precedenti
	function aggiorna_punteggi($db, $carta_evento)
	{	
		#controllo se la carta evento che si trova al centro del tavolo in questo round ha un punteggio positivo 
		#o negativo
		
		$giocatore_punteggio_da_aggiornare;
		
		if($carta_evento > 0)
		{
			$giocatore_punteggio_da_aggiornare = cerca_giocatore_carta_giocata_maggioreMinore($db, "MAX");
		}else
		{
			$giocatore_punteggio_da_aggiornare = cerca_giocatore_carta_giocata_minoreMinore($db, "MIN");
		}	
		
		#query che aggiorna il punteggio del giocatore trovato
		sqlite_query($db, "	UPDATE 	giocatori
					SET     punteggio = punteggio +.$carta_evento.
					WHERE 	nome_giocatore = '".$giocatore_punteggio_da_aggiornare."' ", "impossibile aprire il DB -5");
	}

	#CERCA_GIOCATORE_CARTA_GIOCATA_MAGGIOREMINORE()
	#questa funzione ha due diversi funzionamenti a seconda del valore del parametro $maxMin.
	# - se il valore è "MAX" la funzione restituisce il nome del giocatore che ha giocato la carta con valore massimo
	# - se "MIN" il nome del giocatore che ha giocato la carta con valore minimo
	#in entrambi i casi non vengono considerati le coppie di giocatori che hanno giocato la stessa carta.
	function cerca_giocatore_carta_giocata_maggioreMinore($db, $maxMin)
	{
		$giocatori_da_rimuovere = " ";
		$giocatore = sqlite_query($db, " 	SELECT 	nome_giocatore
							FROM 	giocatori
							WHERE 	punteggio = (	SELECT 	".$maxMin."(punteggio) 
										FROM 	giocatori
									    )", "impossibile aprire il DB -6");
		
		#ciclo fino a quando la query non restituisce un solo giocatore (se ne restituisce due è perchè ci sono 
		#casi di giocatori che hanno giocato una carta con uguale punteggio).
		#Gestisco il caso descritto precedentemente mantenendo un elenco di giocatori da rimuovere dalla query
		#che viene aggiornato ogni volta che il numero degli elementi restituito dalla query è diverso da 1.
		while(count($giocatore)!=1)
		{
			$giocatore = join(costant("CARATTERE_SEPARATORE"), $giocatore);
			$giocatori_da_rimuovere = $giocatori_da_rimuovere.",".$giocatore;
			$giocatore = sqlite_query($db, " SELECT nome_giocatore
							FROM 	giocatori
							WHERE 	punteggio = (	SELECT 	".$maxMin."(punteggio) 
										FROM 	giocatori
									    )
							AND 	nome_giocatore NOT IN(".$giocatori_da_rimuovere.")", "impossibile aprire il DB -7");
		}
		
		return $giocatore;
	}
	
	#--------------------------------------------------------------------------------------
	# MAIN

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
			#controllo che non ci sia un altro processo su questo server che stia eseguendo queste istruzioni
			$sem_id = sem_get(costant("KEY_IPC_SEM_MAIN"));
			if(sem_acquire($sem_id, true))
			{
				#SE L'ITERAZIONE HA RESTITUITO VERO 
				#questo processo sarà il "processo amministratore" ovvero l'unico ad eseguire le operazioni
				#di aggiornamento dei punteggi dei giocatori
				
				#attendo che tutti i giocatori siano allineati allo stesso round 
				attesa_altri_giocatori($db, $n_round);
				
				aggiorna_punteggi($db);
				
				sem_relase($sem_id);
			}	
			
						
		}else
		{
			echo "i punteggi della partita sono stati compromessi";
		}	
	}
	
	
