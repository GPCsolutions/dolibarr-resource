<?php
/* Copyright (C) 2007-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) ---Put here your own copyright and developer email---
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *   	\file       dev/skeletons/skeleton_page.php
 *		\ingroup    mymodule othermodule1 othermodule2
 *		\brief      This file is an example of a php page
 *					Put here some comments
 */

//if (! defined('NOREQUIREUSER'))  define('NOREQUIREUSER','1');
//if (! defined('NOREQUIREDB'))    define('NOREQUIREDB','1');
//if (! defined('NOREQUIRESOC'))   define('NOREQUIRESOC','1');
//if (! defined('NOREQUIRETRAN'))  define('NOREQUIRETRAN','1');
//if (! defined('NOCSRFCHECK'))    define('NOCSRFCHECK','1');			// Do not check anti CSRF attack test
//if (! defined('NOSTYLECHECK'))   define('NOSTYLECHECK','1');			// Do not check style html tag into posted data
//if (! defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL','1');		// Do not check anti POST attack test
//if (! defined('NOREQUIREMENU'))  define('NOREQUIREMENU','1');			// If there is no need to load and show top and left menu
//if (! defined('NOREQUIREHTML'))  define('NOREQUIREHTML','1');			// If we don't need to load the html.form.class.php
//if (! defined('NOREQUIREAJAX'))  define('NOREQUIREAJAX','1');
//if (! defined("NOLOGIN"))        define("NOLOGIN",'1');				// If this page is public (can be called outside logged session)

// Change this following line to use the correct relative path (../, ../../, etc)
$res=0;
if (! $res && file_exists("../main.inc.php")) $res=@include '../main.inc.php';
if (! $res && file_exists("../../main.inc.php")) $res=@include '../../main.inc.php';					// to work if your module directory is into dolibarr root htdocs directory
if (! $res && file_exists("../../../main.inc.php")) $res=@include '../../../main.inc.php';			// to work if your module directory is into a subdir of root htdocs directory

if (! $res) die("Include of main fails");
// Change this following line to use the correct relative path from htdocs
dol_include_once('/resource/class/resource.class.php');
dol_include_once('/resource/class/html.formresource.class.php');

// Load traductions files requiredby by page
$langs->load("companies");
$langs->load("other");

// Get parameters
$id				= GETPOST('id','int');
$action			= GETPOST('action','alpha');

$start			= GETPOST('start','int');
$end			= GETPOST('end','int');
$fk_resource 	= GETPOST('fk_resource');



/***************************************************
* VIEW
*
* Put here all code to build page
****************************************************/
$morecss=array(
	"/resource/js/fullcalendar/fullcalendar.css",
	"/resource/inc/multiselect/css/ui.multiselect.css"
);

$morejs=array(
	"/resource/js/fullcalendar/fullcalendar.js",
	"/resource/inc/multiselect/js/ui.multiselect.js"
);

$title = $langs->trans('ResourcePlaning');

llxHeader('',$title,'','','','',$morejs,$morecss,0,0);

$form=new Form($db);


print '<script type="text/javascript" language="javascript">
jQuery(document).ready(function() {
	$("#calendar").fullCalendar({
	header: {
		left: \'prev,next today\',
		center: \'title\',
		right: \'month,agendaWeek,agendaDay\'
	},
	defaultView: \'agendaWeek\',
    eventSources: [

        // your event source
        {
            url: "'.dol_buildpath('/resource/core/ajax/resource_action.json.php',1).'",
            type: "POST",
            data: {
                fk_resource: '.(is_array($fk_resource)?json_encode($fk_resource):'"'.$fk_resource.'"').'
            },
            error: function() {
                alert("there was an error while fetching events!");
            },
        }


    ]

});
});
</script>';

print '<script type="text/javascript">
$(document).ready(function () {
	$.extend($.ui.multiselect.locale, {
		addAll:"'.$langs->transnoentities("AddAll").'",
		removeAll:"'.$langs->transnoentities("RemoveAll").'",
		itemsCount:"'.$langs->transnoentities("ItemsCount").'"
	});
	$(function(){
	  $(".multiselect").multiselect({sortable: false, searchable: false});
	});
});
</script>';


print '<div class="fichecenter">';

print_fiche_titre($langs->trans('PlanningOfAffectedResources'));

print '<div class="fichethirdleft">';

$formresource = new FormResource($db);
$out = '<form action="'.$_SERVER["PHP_SELF"].'" method="POST">';
$out.= '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
$out.= $formresource->select_resource_list_multi($fk_resource);
$out.= '<p><input type="submit" class="button" value="'.$langs->trans("Search").'"> </p>&nbsp; &nbsp; ';
$out.= '</form>';

print $out;

print '</div>';
print '<div class="fichetwothirdright">';
print '<div class="ficheaddleft">';
print '<div id="calendar"></div>';

print '</div>';
print '</div>';

print '</div>';



// End of page
llxFooter();
$db->close();
?>
