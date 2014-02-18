<!DOCTYPE html>
<html>
	<head>
	  <title>SOGo Integrator installation for Thunderbird</title>
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

	  <h1>SOGo Integrator installation for Thunderbird</h1>
	  <p>
	    SOGo Integrator will configure Thunderbird to use SOGo calendars and phonebooks.
	  </p>
		
          <h2>Before installation</h2>
          <p>Make sure <a href='https://addons.mozilla.org/it/thunderbird/addon/lightning/'>Lightning</a> add-on is already installed.</p>
	  <dl>
	    <dt>Server</dt>
	    <dd>
              During installation, SOGO Integrator will connect to <a href="https://<?php echo $_SERVER['SERVER_NAME']?>">https://<?php echo $_SERVER['SERVER_NAME']?></a>.
              Make sure the name is resolved by your computer.
	    </dd>

	    <dt>Default account</dt>
            <dd>
              Default account for SOGo connection is the one used for IMAP.
            </dd>

	    <dt>Certificate</dt>
            <dd>
              Before proceeding, manually add server certificate to Thunderbird exception list.
              Click on 
              <code>Prefernces &gt; Advanced &gt; View Certificates &gt; Add Exception</code> and
              add the server address <code>https://<?php echo $_SERVER['SERVER_NAME']
              ?></code>.
            </dd>
	  </dl>
                  
          <h2>Download SOGo Integrator</h2>
	  <p>Choose SOGo Integrator release for</p>
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
	  
          <h2>After installation</h2> 
	  <p>
            Thunderbird will reboot a couple of times. At the end, below addons will be installed:
	  </p>
          <ul>
            <li>Sogo-integrator</li>
            <li>Sogo-connector</li>
	  </ul>
	
	  <p>
	     More info at <a href="http://www.sogo.nu">www.sogo.nu</a>
	  </p>

	</body>
</html>
