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
$req['pluginid'] = isset($_GET['plugin']) ? $_GET['plugin'] : false;
$req['platform'] = isset($_GET['platform']) ? $_GET['platform'] : false;
$req['version'] = isset($_GET['version']) ? $_GET['version'] : false;

// HTTP_USER_AGENT can be overriden by 'ua' GET parameter, for debugging purposes:
$req['ua'] = isset($_GET['ua']) ? $_GET['ua'] : $_SERVER['HTTP_USER_AGENT'];

/* CLEANUP if ($req['ua'] === false) { */
/*   $uaMatch = array(); */
/*   preg_match("/Thunderbird\/(\w+)/", , $uaMatch); */

/*   if (count($uaMatch) != 2) { */
/*     header("Content-type: text/plain; charset=utf-8", true, 404); */
/*     exit("Unsupported client.\n"); */
/*   } */

/*   // set appversion requirement: */
/*   $req['appversion'] = $uaMatch[1]; */
/* } */

//
// Parse MANIFEST-* files
//
$headers = array(
    'file',
    'pluginid',
    'version',
    'platform',
    'appmatch',
    'appminver',
    'appmaxver'
    );

$metadata = array();

foreach(glob(SOURCE_DIR . '/MANIFEST-*.tsv') as $manifestFile) {
    $fh = fopen($manifestFile, 'r');  
    while($fields = fgetcsv($fh, 4096, "\t", '"', '\\')) {

        // Skip commented lines:
        if(substr($fields[0], 0, 1) === '#') {
            continue;
        }
    
        // Fill the row hash:
        $row = array();
        foreach($headers as $headerIndex => $headerName) {
            $row[$headerName] = $fields[$headerIndex];
        }
       
        // Check version matches:
        $matchCode = preg_match('|' . $row['appmatch'] . '|', $req['ua']);

        if($matchCode === FALSE) {
            // this is an error, and must be logged to syslog:
            error_log(sprintf("%s invalid perl-regexp pattern: %s", __FILE__, $row['appmatch']));
        } elseif($matchCode === 0) {
            // the pattern does not match, skip the row:
            continue;
        }

        //
        // Check if request matches the current row:
        // 
        foreach(array('pluginid', 'platform') as $key) {

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

        // For sogo-integrator plugin only, forge path and file name
        // where dynamically generated XPI are put by
        // nethserver-sogo-build-integrator action. The naming rule is
        // hardcoded in sprintf() argument. See below:
        if($row['pluginid'] === 'sogo-integrator@inverse.ca') {
            $row['file'] = sprintf('sogo-integrator-%s.xpi', $row['version']);
            $row['path'] = 'integrator';

        } else {
            // default path to pre-packaged files:
            $row['path'] = 'frontends';
        }

        $metadata[] = $row;
}
    fclose($fh);
}

if(count($metadata) === 0) {
    header("Content-type: text/plain; charset=utf-8", true, 404);
    echo "No packages found.\n";
    exit;
}

$updateLinkPrefix = "http://" . $_SERVER["SERVER_NAME"] . "/sogo-thunderbird";
$thunderbirdId = '{3550f703-e582-4d05-9a08-453d09bdfdc6}';

header("Content-type: text/xml; charset=utf-8");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<!DOCTYPE RDF>
<RDF xmlns="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
     xmlns:em="http://www.mozilla.org/2004/em-rdf#">
  <Description about="urn:mozilla:extension:<?php echo htmlspecialchars($req['pluginid']); ?>">
  <em:updates>
    <Seq><?php foreach ($metadata as $item): ?>
    <li>
      <Description>
	<em:version><?php echo htmlspecialchars($item['version']); ?></em:version>
	<em:targetApplication>
	  <Description>
	    <em:id><?php echo htmlspecialchars($thunderbirdId); ?></em:id>
	    <em:minVersion><?php echo htmlspecialchars($item["appminver"]); ?></em:minVersion>
	    <em:maxVersion><?php echo htmlspecialchars($item["appmaxver"]); ?></em:maxVersion>
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
