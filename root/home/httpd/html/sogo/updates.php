<?php
/* Federico Simoncelli <federico@nethesis.it> */

$pluginid = @$_REQUEST['plugin'];
$platform = @$_REQUEST['platform'];
$appversion = @$_REQUEST['appversion'];

/* plugins constants */
$plugins_filename = array(
	"sogo-connector@inverse.ca" => "sogo-connector-*.xpi",
	"sogo-integrator@inverse.ca" => "sogo-integrator-*-sogo.xpi",
	"{e2fda1a4-762b-4020-b5ad-a41df1933103}" => "$platform/lightning-*-inverse.xpi",
);
$plugins_vermatch = array(
	"sogo-connector@inverse.ca" => "/sogo-connector-(.*)\.xpi$/",
	"sogo-integrator@inverse.ca" => "/sogo-integrator-(.*)-sogo\.xpi$/",
	"{e2fda1a4-762b-4020-b5ad-a41df1933103}" => "/lightning-(.*)-inverse\.xpi$/",
);

/* appversion based on user-agent */
if (empty($appversion)) {
	preg_match("/Thunderbird\/(\w+)/", $_SERVER['HTTP_USER_AGENT'], $expmatch);

	if (count($expmatch) != 2) {
		header("Content-type: text/plain; charset=utf-8", true, 404);
	        exit("Unsupported application.\n");
	}

	$appversion = $expmatch[1];
}

/* check plugin request */
if (!array_key_exists($pluginid, $plugins_filename)) {
	header("Content-type: text/plain; charset=utf-8", true, 404);
	exit("Plugin not found.\n");
}

if ($appversion[0] == '2') { /* Thunderbird 2 */
	$plugins_globpath = "thunderbird2/" . $plugins_filename[$pluginid];
	$plugins = array(
		"minVersion" => "1.5",
		"maxVersion" => "2.0.*"
	);
} else if ($appversion[0] == '3') { /* Thunderbird 3 */
	$plugins_globpath = "thunderbird3/" . $plugins_filename[$pluginid];
	$plugins = array(
		"minVersion" => "3.0",
		"maxVersion" => "3.1.*"
	);
} else if ($appversion == '10') { /* Thunderbird 10 */
	$plugins_globpath = "thunderbird10/" . $plugins_filename[$pluginid];
	$plugins = array(
		"minVersion" => "10.0",
		"maxVersion" => "10.1.*"
	);
} else {
	header("Content-type: text/plain; charset=utf-8", true, 404);
        exit("Unsupported application.\n");
}

$plugins["updateLink"] = array();

foreach (glob($plugins_globpath) as $pluginpath) {
	if (preg_match($plugins_vermatch[$pluginid], $pluginpath, $expmatch) > 0) {
		$plugins["updateLink"][$expmatch[1]] = $pluginpath;
	}
}

$plugins_baseurl = "http://" . $_SERVER["HTTP_HOST"] . "/sogo-plugins";

header("Content-type: text/xml; charset=utf-8");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<!DOCTYPE RDF>
<RDF xmlns="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
     xmlns:em="http://www.mozilla.org/2004/em-rdf#">
  <Description about="urn:mozilla:extension:<?php echo $pluginid; ?>">
    <em:updates>
      <Seq>
<?php foreach ($plugins["updateLink"] as $version => $filepath) { ?>
        <li>
          <Description>
            <em:version><?php echo $version; ?></em:version>
            <em:targetApplication>
              <Description>
                <em:id>{3550f703-e582-4d05-9a08-453d09bdfdc6}</em:id>
                <em:minVersion><?php echo $plugins["minVersion"]; ?></em:minVersion>
                <em:maxVersion><?php echo $plugins["maxVersion"]; ?></em:maxVersion>
                <em:updateLink><?php echo $plugins_baseurl . "/" . $filepath; ?></em:updateLink>
              </Description>
            </em:targetApplication>
          </Description>
        </li>
<?php } ?>
      </Seq>
    </em:updates>
  </Description>
</RDF>
