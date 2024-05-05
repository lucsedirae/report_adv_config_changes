<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Config changes report edit note form
 *
 * This plugin is a fork of the core report_configlog report.
 *
 * @package   report_adv_configlog
 * @copyright 2023 Jon Deavers jondeavers@gmail.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use report_adv_configlog\form\notes_form;
use report_adv_configlog\local\data\confignote;

require(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

global $PAGE, $OUTPUT, $DB;

// Page setup.
$configid = required_param('configid', PARAM_INT);
$parenturl = new moodle_url('/report/adv_configlog/index.php');
$pageurl = new moodle_url('/report/adv_configlog/edit.php');
$setting = $DB->get_record('config_log', ['id' => $configid])->name;

admin_externalpage_setup('reportadv_configlog', '', ['configid' => $configid], '', ['pagelayout' => 'admin']);

// Form construction.
$notesform = new notes_form(new moodle_url('/report/adv_configlog/edit.php'), ['configid' => $configid]);
$toform = new stdClass();
$toform->configid = $configid;

// Get existing note to update if it exists.

if ($existingnote = confignote::get_record(['configid' => $configid])) {
    $toform->notes = $existingnote->notes;
}

$notesform->set_data($toform);

// Form handling.
if ($notesform->is_cancelled()) {
    redirect($parenturl);
} else if ($fromform = $notesform->get_data()) {
    $fromform = $notesform->get_data();

    $data = new stdClass();
    $data->configid = $configid;
    $data->notes = $fromform->notes;
    $confignote = new confignote(0, $data);
    if (empty($existingnote)) {
        $confignote->create();
    } else {
        $confignote->id = $existingnote->id;
        $confignote->update();
    }

    redirect($parenturl);
} else {
    // Output.
    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('edit', 'report_adv_configlog', $setting));
    echo "<hr/>";

    $notesform->display();

    echo $OUTPUT->footer();
}
