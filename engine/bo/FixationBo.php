<?php /*
	Copyright 2015 Cédric Levieux, Parti Pirate

	This file is part of Personae.

    Personae is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Personae is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Personae.  If not, see <http://www.gnu.org/licenses/>.
*/

class FixationBo {
	var $pdo = null;
	var $galetteDatabase = "";
	var $personaeDatabase = "";
	
	function __construct($pdo, $config = null) {
		if ($config && isset($config["galette"]["db"])) {
			$this->galetteDatabase = $config["galette"]["db"] . ".";
		}
		if ($config && isset($config["personae"]["db"])) {
			$this->personaeDatabase = $config["personae"]["db"] . ".";
		}
		$this->pdo = $pdo;
		
//		error_log(print_r($this, true));
	}

	static function newInstance($pdo, $config = null) {
		return new FixationBo($pdo, $config);
	}

	function getFixation($id) {
		$id = intval($id);

		$filters = array("fix_id" => $id);
		$fixations = $this->getFixations($filters);

		if (count($fixations)) {
			return $fixations[0];
		}

		return null;
	}

	function getFixations($filters = null) {
		$args = array();

		$query = "	SELECT *, IF(fix_id = the_current_fixation_id, 1, 0) as fix_is_current
					FROM  ".$this->personaeDatabase."dlp_fixations \n ";

		$query .= "	LEFT JOIN ".$this->personaeDatabase."dlp_themes ON fix_theme_id = the_id AND fix_theme_type = 'dlp_themes' AND the_deleted = 0 \n ";

//		if ($filters && isset($filters["with_fixation_information"]) && $filters["with_fixation_information"]) {
//			$query .= "	LEFT JOIN dlp_fixations ON fix_id = fix_current_fixation_id";
//		}

		if ($filters && isset($filters["with_fixation_members"]) && $filters["with_fixation_members"]) {
			$query .= "	LEFT JOIN ".$this->personaeDatabase."dlp_fixation_members ON fix_id = fme_fixation_id \n";
			$query .= "	LEFT JOIN ".$this->galetteDatabase."galette_adherents ON fme_member_id = id_adh \n";
		}

		$query .= "	WHERE
						1 = 1 \n";

		if ($filters && isset($filters["fix_id"])) {
			$args["fix_id"] = $filters["fix_id"];
			$query .= " AND fix_id = :fix_id \n";
		}

		if ($filters && isset($filters["fix_next_fixation_date"])) {
			$args["fix_next_fixation_date"] = $filters["fix_next_fixation_date"];
			$query .= " AND fix_next_fixation_date = :fix_next_fixation_date \n";
		}

		if ($filters && isset($filters["fme_member_id"])) {
			$args["fme_member_id"] = $filters["fme_member_id"];
			$query .= " AND fme_member_id = :fme_member_id \n";
		}

		$query .= "	ORDER BY fix_until_date DESC, fix_id DESC ";

		$statement = $this->pdo->prepare($query);
//		echo showQuery($query, $args);
//		error_log(showQuery($query, $args));

		$results = array();

		try {
			$statement->execute($args);
			$results = $statement->fetchAll();

			foreach($results as $index => $line) {
				foreach($line as $field => $value) {
					if (is_numeric($field)) {
						unset($results[$index][$field]);
					}
				}
			}
		}
		catch(Exception $e){
			echo 'Erreur de requète : ', $e->getMessage();
		}

		return $results;
	}
}