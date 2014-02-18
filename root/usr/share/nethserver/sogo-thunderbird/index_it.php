<!DOCTYPE html>
<html>
	<head>
	  <title>Installazione SOGo Integrator per Thunderbird</title>
            <style type="text/css">
              body{margin:0;padding:0;background:#FFFFFF;font-family:Arial, Helvetica, sans-serif;font-size:15px;color:#555; margin-left: 10px;}
              h1, h2, h3{margin:0;padding:0;font-weight:normal;color:#555;}
              h1{font-size:2.0em;}
              h2{font-size:1.5em;}
              h3{font-size:1.3em;}
              p, ul, ol{margin-top:0;line-height:180%;}
              a{text-decoration:underline;}
            </style>
	</head>
	<body>

	  <h1>Installazione SOGo Integrator per Thunderbird</h1>
	  <p>
	    Per integrare Thunderbird con SOGo &egrave necessario
	    installare l'addon <b>SOGo Integrator</b> che permette
	    la sincronizzazione di calendari e rubriche.
	  </p>
		
          <h2>Prima dell'installazione</h2>
          <p>Assicurarsi di aver installato il componente aggiuntivo <a href='https://addons.mozilla.org/it/thunderbird/addon/lightning/'>Lightning</a>.</p>
	  <dl>
	    <dt>Server</dt>
	    <dd>
	      Durante l'installazione, SOGO Integrator cercher&agrave di
	      collegarsi all'indirizzo
	      <a href="https://<?php echo $_SERVER['SERVER_NAME']?>">https://<?php echo $_SERVER['SERVER_NAME']?></a>. 
	      Accertarsi che sia 
	      correttamente risolto e raggiungibile dal PC su cui
	      si sta installando SOGo Integrator.
	    </dd>

	    <dt>Account predefinito</dt>
            <dd>
	      L'utente utilizzato per la connessione a SOGo
	      sar&agrave lo stesso dell'account IMAP configurato come
	      predefinito su Thunderbird e verr&agrave richiesta la
	      password per tale utente: controllare quindi che l'account
	      predefinito sia quello di NethService.
            </dd>

	    <dt>Certificato</dt>
            <dd>
              Prima di procedere all'installazione, &egrave
              necessario aggiungere manualmente un'eccezione nei
              certificati riconosciuti da Thunderbird. Cliccare su
              <code>Strumenti &gt; Opzioni &gt; Avanzate &gt;
              Certificati &gt; Mostra certificati &gt; Server &gt;
              Aggiungi eccezione</code>, inserendo l'indirizzo
              <code>https://<?php echo $_SERVER['SERVER_NAME']
              ?></code>.
            </dd>
	  </dl>
                  
          <h2>Scaricare SOGo Integrator</h2>
	  <p>Scegliere la versione di SOGo Integrator specifica per</p>
	  <ul><?php
	      foreach(glob('./integrator/sogo-integrator-*.xpi') as $xpiFile):

	          $fileName = basename($xpiFile);

		  $matches = array();
		  preg_match('/^sogo-integrator-([^-]+).*\.xpi/', $fileName, $matches);

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
	  </ul>
	
	  <p>
	     Maggiori informazioni sono reperibili all'indirizzo <a
	     href="http://docs.nethesis.it/Groupware_SOGo">http://docs.nethesis.it/Groupware_SOGo</a>
	  </p>

	</body>
</html>
