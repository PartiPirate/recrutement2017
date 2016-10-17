<?php /*
	Copyright 2015 Cédric Levieux, Parti Pirate

	This file is part of Recrutement.

    Recrutement is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Recrutement is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Recrutement.  If not, see <http://www.gnu.org/licenses/>.
*/
include_once("header.php");
require_once("engine/bo/CandidateBo.php");
require_once("engine/bo/CandidateQuestionBo.php");

$candidateBo = CandidateBo::newInstance($connection, $config);
$candidateQuestionBo = CandidateQuestionBo::newInstance($connection, $config);

$candidate = null;
if ($isConnected) {
	$candidate = $candidateBo->getById($_REQUEST["id"]);
	$candidateQuestions = $candidateQuestionBo->getByFilters(array("cas_candidature_id" => $_REQUEST["id"]));
	
	$tPositions = array();
	
	if ($candidate["can_positions"]) {
		$positions = explode(",", $candidate["can_positions"]);
			
		foreach($positions as $position) {
			$tPositions[] = lang("candidate_position_$position");
		}
	}
		
	$positions =  implode(", ", $tPositions);
	
	$circos = str_replace(",", ", ", $candidate["can_circos"]);
}

?>

<div class="container theme-showcase" role="main">
	<ol class="breadcrumb">
		<li><a href="backoffice.php"><?php echo lang("breadcrumb_backoffice"); ?></a></li>
		<li><a href="candidates.php"><?php echo lang("breadcrumb_candidates"); ?></a></li>
		<li class="active"><?php echo $candidate["can_lastname"]; ?> <?php echo $candidate["can_firstname"]; ?></li>
	</ol>

<?php 
	if ($candidate) {
?>

	<form class="form-horizontal" method="post" action="do_candidate.php">
		<input type="hidden" name="cas_candidature_id" value="<?php echo $candidate["can_id"]; ?>">
		<fieldset>
			<legend>Candidat-e</legend>

			<div class="form-group">
				<label class="col-md-4 control-label" for="firstname">Identité</label>

				<div class="col-md-4">
					<input id="firstname" name="can_firstname" value="<?php echo $candidate["can_firstname"]; ?>" disabled="disabled" type="text" placeholder="Prénom" class="form-control input-md">
					<p class="help-block">Prénom</p>
				</div>

				<div class="col-md-4">
					<input id="lastname" name="can_lastname" value="<?php echo $candidate["can_lastname"]; ?>" disabled="disabled" type="text" placeholder="Nom" class="form-control input-md">
					<p class="help-block">Nom</p>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-4 control-label" for="mail">Correspondance</label>

				<div class="col-md-4">
					<input id="mail" name="can_mail" value="<?php echo $candidate["can_mail"]; ?>" disabled="disabled" type="text" placeholder="Mail" class="form-control input-md">
					<p class="help-block">Mail</p>
				</div>

				<div class="col-md-4">
					<input id="telephone" name="can_telephone" value="<?php echo $candidate["can_telephone"]; ?>" disabled="disabled" type="text" placeholder="Téléphone" class="form-control input-md">
					<p class="help-block">Téléphone</p>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-4 control-label" for="circos">Candidature</label>

				<div class="col-md-4">
					<input id="circos" name="can_circos" value="<?php echo $circos; ?>" disabled="disabled" type="text" placeholder="Circonscriptions" class="form-control input-md">
					<p class="help-block">Circonscription</p>
				</div>

				<div class="col-md-4">
					<input id="positions" name="can_positions" value="<?php echo $positions; ?>" disabled="disabled" type="text" placeholder="Positions" class="form-control input-md">
					<p class="help-block">Position</p>
				</div>
			</div>

		</fieldset>

		<fieldset>
			<legend>Questions au candidat-e</legend>

			<?php 
				foreach($candidateQuestions as $question) {
			?>

			<div class="form-group">
				<label class="col-md-12 control-label" for="question-<?php echo $question["cqu_id"]; ?>"><?php echo utf8_encode($question["cqu_question"]); ?></label>
				<div class="col-md-12">
					<textarea class="form-control answer" id="question-<?php echo $question["cqu_id"]; ?>" name="question-<?php echo $question["cqu_id"]; ?>"><?php echo $question["cas_answer"]; ?></textarea>
				</div>
			</div>

			<?php 
				}
			?>

			<div class="form-group text-center">
				<div class="col-md-12">
					<button  class="btn btn-primary">Enregistrer</button>
				</div>
			</div>

		</fieldset>
	</form>

<?php		
	}
?>

<?php include("connect_button.php"); ?>

</div>

<div class="lastDiv"></div>

<?php include("footer.php");?>

</body>
</html>