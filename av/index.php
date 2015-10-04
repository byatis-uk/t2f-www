<?

// PHP ANTI-VIRUS v1.0.2
// Written by FujitsuBoy (aka Keyboard Artist)

// INSTALL explains the installation procedure.
// Please see COPYING for licensing information.

// If you have any comments or suggestions please let me know:
// keyboardartist@users.sourceforge.net


// default configuration
$CONFIG = Array();
$CONFIG['debug'] = 0;
$CONFIG['scanpath'] = $_SERVER['DOCUMENT_ROOT'];
$CONFIG['extensions'] = Array();

// attempt to load configuration file
@include("config.php");

// declare variables
$report = '';

// output html headers
renderhead();

// set counters
$dircount = 0;
$filecount = 0;
$infected = 0;

// load virus defs from flat file
if (!check_defs('virus.def'))
	trigger_error("Virus.def vulnerable to overwrite, please change permissions", E_USER_ERROR);
$defs = load_defs('virus.def', $debug);

// scan specified root for specified defs
file_scan($CONFIG['scanpath'], $defs, $CONFIG['debug']);

// output summary
echo '<h1>Scan Completed</h2>';
echo '<div id=summary>';
echo '<p><strong>Scanned folders:</strong> ' . $dircount . '</p>';
echo '<p><strong>Scanned files:</strong> ' . $filecount . '</p>';
echo '<p class=r><strong>Infected files:</strong> ' . $infected . '</p>';
echo '</div>';

// output full report
echo $report;


function file_scan($folder, $defs, $debug) {
	// hunts files/folders recursively for scannable items
	global $dircount, $report;
	$dircount++;
	if ($debug)
		$report .= '<p class="d">Scanning folder $folder ...</p>';
	if ($d = @dir($folder)) {
		while (false !== ($entry = $d->read())) {
			$isdir = @is_dir($folder.'/'.$entry);
			if (!$isdir and $entry!='.' and $entry!='..') {
				virus_check($folder.'/'.$entry,$defs,$debug);
			} elseif ($isdir  and $entry!='.' and $entry!='..') {
				file_scan($folder.'/'.$entry,$defs,$debug);
			}
		}
		$d->close();
	}
}

function virus_check($file, $defs, $debug) {
	global $filecount, $infected, $report, $CONFIG;

	// find scannable files
	$scannable = 0;
	foreach ($CONFIG['extensions'] as $ext) {
		if (substr($file,-3)==$ext)
			$scannable = 1;
	}

	// compare against defs
	if ($scannable) {
		// affectable formats
		$filecount++;
		$data = file($file);
		$data = implode('\r\n', $data);
		$clean = 1;
		foreach ($defs as $virus) {
			if (strpos($data, $virus[1])) {
				// file matches virus defs
				$report .= '<p class="r">Infected: ' . $file . ' (' . $virus[0] . ')</p>';
				$infected++;
				$clean = 0;
			}
		}
		if (($debug)&&($clean))
			$report .= '<p class="g">Clean: ' . $file . '</p>';
	}
}

function load_defs($file, $debug) {
	// reads tab-delimited defs file
	$defs = file($file);
	$counter = 0;
	$counttop = sizeof($defs);
	while ($counter < $counttop) {
		$defs[$counter] = explode('	', $defs[$counter]);
		$counter++;
	}
	if ($debug)
		echo '<p>Loaded ' . sizeof($defs) . ' virus definitions</p>';
	return $defs;
}

function check_defs($file) {
	// check for >755 perms on virus defs
	clearstatcache();
	$perms = substr(decoct(fileperms($file)),-2);
	if ($perms > 55)
		return false;
	else
		return true;
}

function renderhead() {
?>

<html>
<head>
<title>Virus scan</title>
<style type="text/css">
h1 {
	font-family: arial;
}

p {
	font-family: arial;
	padding: 0;
	margin: 0;
	font-size: 10px;
}

.g {
	color: #009900;
}

.r {
	color: #990000;
	font-weight: bold;
}

.d {
	color: #ccc;
}

#summary {
	border: #333 solid 1px;
	background: #f0efca;
	padding: 10px;
	margin: 10px;
}

#summary p {
	font-size: 12px;
}
</style>
</head>

<body>

<?
}
?>

</body>
</html>