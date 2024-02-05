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
 * Config changes report
 * This plugin is a fork of the core report_configlog report.
 *
 * @package   report
 * @subplugin adv_configlog
 * @copyright 2023 Jon Deavers jondeavers@gmail.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core_reportbuilder\system_report_factory;
use core_reportbuilder\local\filters\text;
use report_adv_configlog\form\notes_form;
use report_adv_configlog\reportbuilder\local\systemreports\config_changes;

global $PAGE;

require(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');

// Allow searching by setting when providing parameter directly.
$search = optional_param('search', '', PARAM_TEXT);

admin_externalpage_setup('reportadv_configlog', '', ['search' => $search], '', ['pagelayout' => 'report']);
$pageurl = new moodle_url('/report/adv_configlog/index.php');

// Form construction.
$mform = new notes_form($pageurl);

$PAGE->requires->js_call_amd(
        'report_adv_configlog/notes_crud',
        'initForm',
        ['[data-action=openform]', notes_form::class]
);

// Output
echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('adv_configlog', 'report_adv_configlog'));

// Create out report instance, setting initial filtering if required.
$report = system_report_factory::create(config_changes::class, context_system::instance());
if (!empty($search)) {
    $report->set_filter_values([
            'config_change:setting_operator' => text::IS_EQUAL_TO,
            'config_change:setting_value' => $search,
    ]);
}

echo $report->output();

echo $OUTPUT->footer();
