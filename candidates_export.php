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

<table>
	<tr>
		<th>ID</th>
		<th>Identité</th>
		<th>Genre</th>
		<th>Candidat-e</th>
		<th>Suppléant-e</th>
		<th>Mandataire</th>
		<th>Circonscriptions</th>
		<th>Section</th>
		<th>Tuteur</th>
	</tr>
	<?php
	foreach($candidates as $candidate) {
		$tPositions = array();
		if ($candidate["can_positions"]) {
			$positions = explode(",", $candidate["can_positions"]);
			foreach($positions as $position) {
				$tPositions[$position] = 1;
			}
		}
	?>
  <tr>
		<td><?php echo $candidate["can_id"]?></td>
		<td><?php echo $candidate["can_firstname"]?> <?php echo $candidate["can_lastname"]?></td>
		<td><?php echo $candidate["can_sex"]?></td>
		<td><?php if (isset($tPositions["candidate"])) echo "1"; ?></td>
		<td><?php if (isset($tPositions["substitute"])) echo "1"; ?></td>
		<td><?php if (isset($tPositions["representative"])) echo "1"; ?></td>
		<td><?php echo str_replace(",", ", ", $candidate["can_circos"])?></td>
		<td></td>
		<td></td>
	</tr>
	<?php
	}
	?>
</table>

<?php include("connect_button.php"); ?>

</div>

<div class="lastDiv"></div>

<?php include("footer.php");?>

</body>
</html>
