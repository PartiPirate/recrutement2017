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

$candidates = array();

if ($isConnected) {
	$candidates = $candidateBo->getByFilters(array());
}

?>

<div class="container theme-showcase" role="main">
	<ol class="breadcrumb">
		<li><a href="backoffice.php"><?php echo lang("breadcrumb_backoffice"); ?></a></li>
		<li class="active"><?php echo lang("breadcrumb_candidates"); ?></li>
	</ol>

<?php 
	if (count($candidates)) {
		?>

<div class="text-center">
	<div id="positions" class="btn-group" role="group" aria-label="...">
		<button value="candidate" type="button" class="btn btn-default active">Candidat-e</button>
		<button value="substitute" type="button" class="btn btn-default active">Suppléant-e</button>
		<button value="representative" type="button" class="btn btn-default active">Mandataire</button>
	</div>
	
	<div id="sexes" class="btn-group" role="group" aria-label="...">
		<button value="male" type="button" class="btn btn-default active"><i class="fa fa-mars"></i></button>
		<button value="female" type="button" class="btn btn-default active"><i class="fa fa-venus"></i></button>
	</div>
	
	Nombre de personnes : <span class="found_persons"><?php echo count($candidates); ?></span>
</div>

<br>

<div class="row header">
	<div class="col-md-3">
		Identité (et genre civil)
	</div>
	<div class="col-md-2">
		Téléphone
	</div>
	<div class="col-md-3">
		Mail
	</div>
	<div class="col-md-2">
		Positions
	</div>
	<div class="col-md-2">
		Circonscriptions
	</div>
</div>				
<?php 
	}
?>

<div class="row-striped row-hover">
<?php 	
foreach($candidates as $candidate) {
	$candidateQuestions = $candidateQuestionBo->getByFilters(array("cas_candidature_id" => $candidate["can_id"]));
	
	$answered = 0;
	foreach($candidateQuestions as $question) {
		if ($question["cas_answer"]) {
			$answered++;
		}
	}
	
	if ($answered == 0) {
		$answerClass = "none-answered";
	}
	else if ($answered == count($candidateQuestions)) {
		$answerClass = "all-answered";
	}
	else {
		$answerClass = "some-answered";
	}
	
?>
	<div class="row data <?php echo $answerClass; ?> <?php echo str_replace(",", " ", $candidate["can_positions"]); ?> <?php echo $candidate["can_sex"]; ?>">
		<div class="col-md-3">
			<a href="candidate.php?id=<?php echo $candidate["can_id"]?>"><?php echo $candidate["can_firstname"]; ?> <?php echo $candidate["can_lastname"]; ?></a>
			
			<?php 
				if ($candidate["can_sex"]) {?>
			<i class="fa fa-<?php 
					if ($candidate["can_sex"] == "male") {
						echo "mars";
					}
					else if ($candidate["can_sex"] == "female") {
						echo "venus";
					}
			?>"></i>
			<?php 
				}
			?>
			
		</div>
		<div class="col-md-2">
			<?php echo $candidate["can_telephone"]; ?>
		</div>
		<div class="col-md-3">
			<?php echo $candidate["can_mail"]; ?>
		</div>
		<div class="col-md-2">
			<?php 
				$tPositions = array();
				
				if ($candidate["can_positions"]) {
					$positions = explode(",", $candidate["can_positions"]);
					
					foreach($positions as $position) {
						$tPositions[] = lang("candidate_position_$position");
					}
				}
			
				echo implode(", ", $tPositions); 
			?>
		</div>
		<div class="col-md-2">
			<?php 
				echo str_replace(",", ", ", $candidate["can_circos"]); 
			?>
		</div>
	</div>
	
<?php 	
}
?>
</div>


<?php include("connect_button.php"); ?>

</div>

<div class="lastDiv"></div>

<?php include("footer.php");?>

</body>
</html>