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
use report_adv_configlog\form\notes_form;
use report_adv_configlog\local\data\confignote;

/**
 * Form class for managing config notes.
 *
 * This plugin is a fork of the core report_configlog report.
 *
 * @package   report_adv_configlog
 * @copyright 2023 Jon Deavers jondeavers@gmail.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Hook to add js form if logs are created.
 *
 * @return void
 */
function report_adv_configlog_before_footer() {
    global $PAGE;
    if (is_siteadmin()) {
        // Get persistents with status "logged".
        $logged = confignote::get_records(['status' => confignote::ADV_CONFIGLOG_LOGGED]);

        if (!empty($logged)) {
            // Call AMD.
            $PAGE->requires->js_call_amd(
                    'report_adv_configlog/config_listener',
                    'init', [
                            '[data-action=openform]',
                            notes_form::class,
                    ]
            );
        }
    }
}
