<?php

/*
  Oressource
  Copyright (C) 2014-2018  Martin Vert and Oressource devellopers

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

require_once('../core/session.php');
require_once('../core/requetes.php');

/*
 * Fonction qui permet de telecharger un fichier via son nom,
 * Il est possible de specifer un nom d'attachement.
 * Il faut renseigner le type MIME complet.
 *
 * Documentation des types mimes sur MDN:
 * https://developer.mozilla.org/fr/docs/Web/HTTP/Basics_of_HTTP/MIME_types/Complete_list_of_MIME_types
 */

function telechargement(string $pathTofile, string $type, string $attachementName = "") {
  $size = filesize($pathTofile);
  $name = $attachementName === "" ? $pathTofile : $attachementName;
  header("Content-Type: $type;  charset=utf-8");
  header("Content-disposition: attachment; filename=\"$name\"");
  header("Pragma: public");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Cache-Control: private", false);
  header("Content-Length: $size");
  ob_start();
  readfile($pathTofile);
  ob_end_flush();
}

if (is_valid_session() && is_allowed_config()) {
  require_once('../moteur/dbconfig.php');
  $struct = structure($bdd)['nom'];

  $exportPath = '../mysql/';
  chdir($exportPath);

  $exportFileName = 'sauvegarde_oressource';
  $fileExtention = '.sql';

  $exportPathServer = $exportFileName . $fileExtention;
  $worked = exec("mysqldump --opt --host=$host --user=$user --password=$pass $base > \"$exportPathServer\"");

  $fileZip = $exportFileName . '_' . $struct . '.zip';
  $worked |= exec("zip \"$fileZip\" \"$exportPathServer\"");
  unlink($exportPathServer);

  switch ($worked) {
    case 0:
      $AttachName = $exportFileName . '_' . $struct . '_' . date("d-m-Y_H-i-s") . '.zip';
      telechargement($fileZip, 'application/zip', $AttachName);
      break;
    case 1:
      header("Location:structures.php?err=Probleme pendant l'export du fichier");
      break;
    case 2:
      header("Location:structures.php?err=Probleme pendant l'export de la base");
      break;
  }
} else {
  header('Location:../moteur/destroy.php');
}
