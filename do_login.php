<?php /*
	Copyright 2015 Cédric Levieux, Parti Pirate

	This file is part of Congressus.

    Congressus is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Congressus is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Congressus.  If not, see <http://www.gnu.org/licenses/>.
*/
include_once("config/database.php");
require_once("engine/utils/FormUtils.php");
//require_once("engine/utils/LogUtils.php");
require_once("engine/bo/GaletteBo.php");
require_once("engine/bo/FixationBo.php");
require_once("engine/authenticators/GaletteAuthenticator.php");

session_start();

// We sanitize the request fields
xssCleanArray($_REQUEST);

$connection = openConnection();
$galetteAuthenticator = GaletteAuthenticator::newInstance($connection, $config["galette"]["db"]);
$fixationBo = FixationBo::newInstance($connection, $config);

$login = $_REQUEST["login"];
$password = $_REQUEST["password"];
//$ajax = isset("")

$data = array();

if ($login == $config["administrator"]["login"] && $password == $config["administrator"]["password"]) {
	$_SESSION["administrator"] = true;
	$data["ok"] = "ok";

	addLog($_SERVER, $_SESSION, null, array("result" => "administrator"));
	
	header('Location: administration.php');
	exit();
}

$member = $galetteAuthenticator->authenticate($login, $password);
if ($member) {
	error_log("LOGIN CORRECT");
//	error_log(print_r($member, true));

	$filters = array();
	$filters["with_fixation_members"] = true;
	$filters["fme_member_id"] = $member["id_adh"];
	
	$fixations = $fixationBo->getFixations($filters);
	
	$isElection = false;
	
	foreach($fixations as $fixation) {
//		error_log(print_r($fixation, true));
		
		// 28 is the current election team theme ID in personae
		if ($fixation["fix_theme_id"] == 28) {
			$isElection = true;
			break;
		}
	}
	
	if ($isElection) {
		$data["ok"] = "ok";
		$connectedMember = array();
		$connectedMember["pseudo_adh"] = GaletteBo::showIdentity($member);
		$connectedMember["id_adh"] = $member["id_adh"];
	
		$_SESSION["user"] = json_encode($connectedMember);
		$_SESSION["userId"] = $member["id_adh"];
		
		error_log(print_r($connectedMember, true));
		error_log("IN ELECTION");
	}
	else {
		$data["ko"] = "ko";
		$data["message"] = "error_bad_group";
	
		error_log("BAD GROUP");
	}
	
	//	addLog($_SERVER, $_SESSION, null, array("result" => "ok"));
}
else {
	$data["ko"] = "ko";
	$data["message"] = "error_login_bad";
//	addLog($_SERVER, $_SESSION, null, array("result" => "ko"));
}

session_write_close();

/*
$referer = $_SERVER["HTTP_REFERER"];

if ($referer) {
	header("Location: $referer");
}
else {
	header("Location: index.php");
}
*/

if (isset($data["ok"]) && $_POST["referer"]) {
	header('Location: ' . $_POST["referer"]);
}
else if (!isset($data["ok"]) && $_POST["referer"]) {
	header('Location: connect.php?error=' . $data["message"] . "&referer=" . urlencode($_POST["referer"]));
}
else {
	echo json_encode($data);
}
?>