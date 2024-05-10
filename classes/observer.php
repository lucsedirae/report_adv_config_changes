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
 * Event observer.
 *
 * This plugin is a fork of the core report_configlog report.
 *
 * @package   report_adv_configlog
 * @copyright 2023 Jon Deavers jondeavers@gmail.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace report_adv_configlog;

use moodle_url;

/**
 * Observer class for when core config logs are created.
 */
class observer {
    /**
     * Observer.
     *
     * @param object $event
     * @return void
     */
    public static function observe_create_config_log(object $event) {
        // Pass id of the event and use that to gather the mdl_logstore_standard_log: other field (json).

        $temp = $event->get_data();

        // See log file for captured event data.
        // Getting created in every.
        file_put_contents('/tmp/configlogs.log', json_encode($temp) . PHP_EOL, FILE_APPEND);

        /**
         * TODO:
         * JS Module
         * Upon saving settings form, store config change log from events
         * Before footer hook to check if on settings page
         * If settings page and changes load AMD and then modal to capture notes
         * Create a status field for the note persistent and when this observers sees a change, mark
         * the persistent record and pending.
         * Then, when the page is confirmed to be an admin settings page, pop the modal and add fields
         * for each persistent record that is marked as pending for a note to be added.
         */
    }
}
