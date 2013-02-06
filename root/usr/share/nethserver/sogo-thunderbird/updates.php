<?php
/* 
 *  Copyright (C) 2006-2012 Inverse inc.
 *
 * Author: Wolfgang Sourdeau <wsourdeau@inverse.ca>
 *         Francis Lachapelle <flachapelle@inverse.ca>
 *
 *  Copyright (C) 2013 Nethesis srl
 *
 * Author: Federico Simoncelli <federico@nethesis.it>
 *         Davide Principi <davide.principi@nethesis.it>
 *
 * This file is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2, or (at your option)
 * any later version.
 *
 * This file is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; see the file COPYING.  If not, write to
 * the Free Software Foundation, Inc., 59 Temple Place - Suite 330,
 * Boston, MA 02111-1307, USA.
 */

define('SOURCE_DIR', '/usr/share/nethserver/sogo-frontends');

//
// Build the requirements:
// 
$req['plugin'] = isset($_GET['plugin']) ? $_GET['plugin'] : false;
$req['platform'] = isset($_GET['platform']) ? $_GET['platform'] : false;
$req['appversion'] = isset($_GET['appversion']) ? $_GET['appversion'] : false;
$req['version'] = isset($_GET['version']) ? $_GET['version'] : false;
$req['application'] = 'thunderbird';

//
// If missing, infer appversion from user-agent:
//
if ($req['appversion'] === false) {
  $uaMatch = array();
  preg_match("/Thunderbird\/(\w+)/", $_SERVER['HTTP_USER_AGENT'], $uaMatch);

  if (count($uaMatch) != 2) {
    header("Content-type: text/plain; charset=utf-8", true, 404);
    exit("Unsupported client.\n");
  }

  // set appversion requirement:
  $req['appversion'] = $uaMatch[1];
}

//
// Parse MANIFEST-* files
//

$headers = array(
		 'plugin',
		 'file',
		 'version',
		 'platform',
		 'application',
		 'minversion',
		 'maxversion'
);

$metadata = array();

foreach(glob(SOURCE_DIR . '/MANIFEST-*.tsv') as $manifestFile) {
  $fh = fopen($manifestFile, 'r');  
  while($fields = fgetcsv($fh, 4096, "\t", '"', '\\')) {
    $row = array();
    foreach($headers as $headerIndex => $headerName) {
      $row[$headerName] = $fields[$headerIndex];
    }

    //
    // Check if request matches the current row:
    // 

    foreach(array('plugin', 'application', 'platform') as $key) {

      // skip field check if field is not requested:
      if($req[$key] === false) {
	continue;
      }

      // skip field check if value is '*' (matches anything)
      if($row[$key] === '*') {
	continue;
      }

      // skip row if field does not match request:
      if($req[$key] != $row[$key]) {
	   continue 2;
      }      
    }

    // For sogo-integrator plugin only, set path to "integrator",
    // where dynamically generated XPI are put by
    // nethserver-sogo-build-integrator action:
    if($row['plugin'] === 'sogo-integrator@inverse.ca') {
      $row['path'] = 'integrator';
    } else {
      // default path to pre-packaged files:
      $row['path'] = 'frontends';
    }

    $metadata[] = $row;
  }
  fclose($fh);
}

#header("Content-type: text/plain; charset=utf-8");
#print_r($req);
#print_r($metadata);
#exit;

if(count($metadata) === 0) {
    header("Content-type: text/plain; charset=utf-8", true, 404);
    echo "No packages found.\n";
    exit;
}

$updateLinkPrefix = "https://" . $_SERVER["SERVER_NAME"] . "/sogo-thunderbird";
$thunderbirdId = '{3550f703-e582-4d05-9a08-453d09bdfdc6}';

header("Content-type: text/xml; charset=utf-8");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<!DOCTYPE RDF>
<RDF xmlns="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
     xmlns:em="http://www.mozilla.org/2004/em-rdf#">
  <Description about="urn:mozilla:extension:<?php echo htmlspecialchars($req['plugin']); ?>">
  <em:updates>
    <Seq><?php foreach ($metadata as $item): ?>
    <li>
      <Description>
	<em:version><?php echo htmlspecialchars($item['version']); ?></em:version>
	<em:targetApplication>
	  <Description>
	    <em:id><?php echo htmlspecialchars($thunderbirdId); ?></em:id>
	    <em:minVersion><?php echo htmlspecialchars($item["minversion"]); ?></em:minVersion>
	    <em:maxVersion><?php echo htmlspecialchars($item["maxversion"]); ?></em:maxVersion>
	    <em:updateLink><?php 
                          echo htmlspecialchars(implode('/', array(
								   $updateLinkPrefix, 
								   $item['path'], 
								   $item['file'])
							)); 
	    ?></em:updateLink>
	  </Description>
	</em:targetApplication>
      </Description>
    </li>
    <?php endforeach; ?></Seq>
  </em:updates>
</Description>
</RDF>
