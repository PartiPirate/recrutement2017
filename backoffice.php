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
require_once("engine/bo/CandidateAnswerBo.php");

$candidateBo = CandidateBo::newInstance($connection, $config);
$candidateAnswerBo = CandidateAnswerBo::newInstance($connection, $config);

$sexPositionStats = $candidateBo->getStats();
$stats = $candidateAnswerBo->getStats();

?>

<div class="container theme-showcase" role="main">
	<ol class="breadcrumb">
		<li class="active"><?php echo lang("breadcrumb_backoffice"); ?></li>
	</ol>

<?php if ($isConnected) {?>
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Répartition genre/position</div>
	
			<table class="table">
				<thead>
					<tr>
						<th>Genre</th>
						<th>Position</th>
						<th class="text-right">Nombre de personnes</th>
					</tr>
				</thead>
				<tbody>
<?php 	foreach($sexPositionStats as $stat) { ?>
					<tr>
						<td><?php echo $stat["can_sex"] ? lang("common_sex_" . $stat["can_sex"]) : "-"; ?></td>
						<td><?php echo $stat["cpo_position"] ? lang("candidate_position_" . $stat["cpo_position"]) : "-"; ?></td>
						<td class="text-right"><?php echo $stat["number_of_persons"]; ?></td>
					</tr>
<?php	} ?>			
				</tbody>
			</table>
		</div>
	</div>

	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Contacts</div>
	
			<table class="table">
				<thead>
					<tr>
						<th>Status</th>
						<th class="text-right">Nombre de personnes</th>
					</tr>
				</thead>
				<tbody>

					<tr>
						<td>Contactés</td>
						<td class="text-right"><?php echo $stats["contacted"]; ?></td>
					</tr>

					<tr>
						<td>&Agrave; contacter</td>
						<td class="text-right"><?php echo $stats["to_be_contacted"]; ?></td>
					</tr>

					<tr>
						<td>-</td>
						<td class="text-right"><?php echo $stats["contacted"] + $stats["to_be_contacted"]; ?></td>
					</tr>

				</tbody>
			</table>
		</div>
	</div>




	<?php }?>

<?php include("connect_button.php"); ?>

</div>

<div class="lastDiv"></div>

<?php include("footer.php");?>

</body>
</html>