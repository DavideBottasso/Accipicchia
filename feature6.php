<?php
	#parametri passati alla pagina : 	
	#int numero di giocatori : CHIAVE='n_gicatori'
	#array associativo con nomi dei giocatori e punteggio : CHIAVE = 'array_giocatori'
	#array con i numeri delle carte ancora disponibili (ogni giocatore riceve una pagina diversa) : CHIAVE = 'array_carte'
	#IMPORTANTE! ricorda che quando il numero di carte è uguale a 0 devo inviare la pagina della feature 7
	
	#RICAVO TUTTI I PARAMETRI DAL VETTORE POST
	$n_giocatori = $_POST['n_giocatori'];
	$array_giocatori = $_POST['']