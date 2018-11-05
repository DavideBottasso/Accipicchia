<?php
	#parametri passati alla pagina : 	
	#variabili definite in PHP:
		#n_giocatori
		#giocatori (array associativo che utilizza il nome dei giocatori come chiave e salva il loro punteggio)
		#nome_giocatore_corrente
		#carte_in_mano (array di booleani)
		#carta_evento (numero positivo o negativo)
		#carte_evento_rimanenti (array di numeri sia negativi che positivi convertito in stringa)

		#servURL (costante)
		#carattereSeparatore (costante) -> utilizzato per separare i vari campi di un array quando convertito in stringa 

		#-------------------------------------------------

		#STRUTTURA ARRAY PASSATO ALLA PAGINA ATTRAVERSO LA FUNZIONE invia_dati:

		#params = array(
			#n_giocatori -> int n
			#giocatori-> TUTTO SOTTO FORMA DI STRINGA DA SPLITTARE array(nomi_giocatori, punteggio_giocatori)
			#nomi_giocatori -> array(char* nomi giocatori)
			#nome_giocatore_corrente ->char* nome_giocatore_corrente
			#carta_cliccata -> int nCarta
			#carte_in_mano -> array(carte)
			#carta_evento -> int nCarta  
			#carte_evento_rimanenti -> array di interi sotto forma di stringa
		#	)	

	
	#PASSO IL NOME DEL GIOCATORE E LA CARTA GIOCATA AL DB, POI ASPETTO 
	
