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
require_once("engine/bo/CandidateQuestionBo.php");
require_once("engine/bo/CandidateAnswerBo.php");
require_once("engine/utils/SessionUtils.php");
//require_once("engine/utils/LogUtils.php");

session_start();

if (SessionUtils::getUserId($_SESSION)) {
	// We sanitize the request fields
	xssCleanArray($_REQUEST);
	
	$connection = openConnection();
	
	$candidateId = $_REQUEST["cas_candidature_id"];
	
	$candidateQuestionBo = CandidateQuestionBo::newInstance($connection, $config);
	$candidateAnswerBo = CandidateAnswerBo::newInstance($connection, $config);
	$candidateQuestions = $candidateQuestionBo->getByFilters(array("cas_candidature_id" => $_REQUEST["cas_candidature_id"]));
	
	//$answers = array();
	
	// Foreach on question-ID
	foreach($candidateQuestions as $question) {
		$answer = array();
		
		$answer["cas_candidature_id"] = $candidateId;
		$answer["cas_question_id"] = $question["cqu_id"];
		$answer["cas_id"] = $question["cas_id"];
		
		$answer["cas_answer"] = $_REQUEST["question-" . $question["cqu_id"]];
		
	//	$answers[] = $answer;
	
		$candidateAnswerBo->save($answer);
	}
}

if (isset($_REQUEST["ajax"])) {
	$data = array();
	$data["ok"] = "ok";
	
	echo json_encode($data);
}
else {
	header('Location: candidate.php?id=' . $_REQUEST["cas_candidature_id"]);
}
?>