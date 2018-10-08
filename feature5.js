/* variabili definite in PHP:
    n_giocatori
    nomi_giocatori 
    punteggio_giocatori
    nome_giocatore_corrente
    carte_in_mano
    
    servURL (costante)
	
	
	-------------------------------------------------
	STRUTTURA ARRAY PASSATO ALLA PAGINA ATTRAVERSO LA FUNZIONE invia_dati:
	
	params = array(
		n_giocatori -> int n
		nomi_giocatori -> array(char* nomi giocatori)
		punteggio_giocatori -> array(punteggio_giocatori)
		nome_giocatore_corrente ->char* nome_giocatore_corrente
		carta_cliccata -> int nCarta
		carte_in_mano -> array(carte)
	)	
*/    

//<script>

	/*TEST PHP*/
	<?php
		$n_giocatori = 3;
		$nomi_giocatori = array("giocatore1"=>1, "giocatore2"=>2, "giocatore3"=>3);
		$punteggio_giocatori
		$nome_giocatore_corrente
		$carte_in_mano
		
		$servURL 
	?>	

	function invia_dati(servURL, params, method) 
	{
    method = method || "post"; // il metodo POST è usato di default
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", servURL);
    for(var key in params) {
        var hiddenField = document.createElement("input");
        hiddenField.setAttribute("type", "hidden");
        hiddenField.setAttribute("name", key);
        hiddenField.setAttribute("value", params[key]);
        form.appendChild(hiddenField);
    }
    document.body.appendChild(form);
    form.submit();
	}
	
	
	function avvia_prossimo_round(cartaCliccata)   /**questa funzione verrà richiamata scrivendo onclick="avvia_prossimo_round(this)"
                                                            ATTENZIONE: l'attributo name degli oggetti html che rappresentano le carte deve corrispondere al numero della carta 
                                                        **/
	{
        /*copio i valori delle variabili ricevute tramite PHP in un vettore da inviare dinuovo al server per richiedere la prossima pagina*/
		var params = new Array();
		params['n_giocatori'] = <?php echo $n_giocatori; ?>;
		
		var giocatori = new array();
		var i;
        for()
		
		params['punteggio_giocatori'] = new array();
		
        params['nome_giocatore_corrente'] = <?php echo $nome_giocatore_corrente; ?>;
        
        
        /* ricavo il numero della carta selezionata*/
        params['carta_cliccata'] = cartaCliccata.name;
        
        params['carte_in_mano'] = new array();
		params['carte_in_mano'].forEach(phpArrayToJS(item, index){
				item = <?php echo $carte_in_mano[index]?>;
		});
        
        var servURL = <?php echo servURL?>;
        
        invia_dati(servURL, params, post); 
	}
	
//</script>	
	