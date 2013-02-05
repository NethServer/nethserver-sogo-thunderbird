<!DOCTYPE html>
<html>
	<head>
	  <title>Installazione SOGo Integrator per Thunderbird</title>
	</head>
	<body>

	  <h1>Installazione SOGo Integrator per Thunderbird</h1>
	  <p>
	    Per integrare Thunderbird con SOGo &egrave necessario
	    installare l'addon <b>SOGo Integrator</b> che permette
	    la sincronizzazione di calendari e rubriche.
	  </p>
		
          <h2>Prima dell'installazione</h2>
	  
	  <p>Server</p>
	  <ul>
	    <li> Durante l'installazione, SOGO Integrator cercher&agrave di
	    collegarsi all'indirizzo
	    <a href="https://<?php echo $_SERVER['SERVER_NAME']?>">https://<?php echo $_SERVER['SERVER_NAME']?></a>. 
	    Accertarsi che sia 
	    correttamente risolto e raggiungibile dal PC su cui
	    si sta installando SOGo Integrator.</li>
	  </ul>

	  <p>Account predefinito</p>
          <ul>
	    <li>L'utente utilizzato per la connessione a SOGo
	    sar&agrave lo stesso dell'account IMAP configurato come
	    predefinito su Thunderbird e verr&agrave richiesta la
	    password per tale utente: controllare quindi che l'account
	    predefinito sia quello di NethService.</li>
          </ul>

	  <p>Certificato</p>
          <ul>
            <li>Prima di procedere all'installazione, &egrave
            necessario aggiungere manualmente un'eccezione nei
            certificati riconosciuti da Thunderbird. Cliccare su
            <code>Strumenti &gt; Opzioni &gt; Avanzate &gt;
            Certificati &gt; Mostra certificati &gt; Server &gt;
            Aggiungi eccezione</code>, inserendo l'indirizzo
            <code>https://<?php echo $_SERVER['SERVER_NAME']
            ?></code>.
          </ul>
			

          <h2>Scaricare SOGo Integrator</h2>
	  <p>Scegliere la versione di SOGo Integrator specifica per</p>
	  <ul><?php
	      foreach(glob('./integrator/sogo-integrator-*.xpi') as $xpiFile):

	          $fileName = basename($xpiFile);

		  $matches = array();
		  preg_match('/^sogo-integrator-(\d+).*/', $fileName, $matches);

	          $title = 'Thunderbird ' . $matches[1];

	          printf('<li><a href="%s">%s</a></li>', 
		         htmlspecialchars('./integrator/' . urlencode($fileName)), 
			 htmlspecialchars($title)
	          );

	      endforeach;	  
	  ?></ul>
	  
          <h2>Dopo l'installazione</h2> 
	  <p>
	    Dopo l'installazione Thunderbird verr&agrave riavviato un
	    paio di volte e nel pannello addon compariranno i seguenti
	    componenti:
	  </p>
          <ul>
            <li>Sogo-integrator</li>
            <li>Sogo-connector</li>
            <li>Lightning</li>
	  </ul>
	
	  <p>
	     Maggiori informazioni sono reperibili all'indirizzo <a
	     href="http://docs.nethesis.it/Groupware_SOGo">http://docs.nethesis.it/Groupware_SOGo</a>
	  </p>

	</body>
</html>
