<?php
/* 
 * Copyright (C) 2016 Javier Samaniego García <jsamaniegog@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Gestion du formulaire de configuration plugin nebackup
 * Reçoit les informations depuis un formulaire de configuration
 * Renvoie sur la page de l'item traité
 */
global $DB, $CFG_GLPI;
// Récupération du fichier includes de GLPI, permet l'accès au cœur
include ("../../../inc/includes.php");

if (!Session::haveRight("entity", UPDATE)) {
    Session::addMessageAfterRedirect(__("No permission", "nebackup"), false, ERROR);
    HTML::back();
} else {
    echo __("Saving configuration...", 'nebackup');
}

try {
    $config = new PluginNebackupEntity();

    if ($config->setEntityData($_POST) == false) {
        HTML::back();
    }
    
} catch (Exception $e) {
    Session::addMessageAfterRedirect("Error on save", false, ERROR);
    HTML::back();
}

Session::addMessageAfterRedirect(__("Configuration saved", "nebackup"), false, INFO);

HTML::back();