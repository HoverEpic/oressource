<?php
/*
  Oressource
  Copyright (C) 2014-2017  Martin Vert and Oressource devellopers

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU Affero General Public License as
  published by the Free Software Foundation, either version 3 of the
  License, or (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU Affero General Public License for more details.

  You should have received a copy of the GNU Affero General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


session_start();

require_once '../core/composants.php';
require_once '../core/session.php';
require_once '../core/requetes.php';

function types_sorties_id(PDO $bdd, int $id): array {
  $sql = 'SELECT id, nom, couleur, visible, description, timestamp FROM type_sortie WHERE id = :id';
  return fetch_id($bdd, $sql, $id);
}

if (is_valid_session() && (strpos($_SESSION['niveau'], 'k') !== false)) {
  require_once '../moteur/dbconfig.php';
  require_once 'tete.php';
  $props = array_merge(['h1' => 'Gestion de la typologie des sorties hors-boutique',
    'type' => 'type de sortie',
    'endpoint' => 'type_sortie'
  ], types_sorties_id($bdd, $_POST['id']));
  ?>
  <?= config_types3_page_modif($props) ?>
  <?php
  require_once 'pied.php';
} else {
  header('Location: ../moteur/destroy.php');
}
?>