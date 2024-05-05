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

class observer {
    public static function observe_create_config_log($event) {
        // Pass id of the event and use that to gather the mdl_logstore_standard_log: other field (json)

        $temp = $event->get_data();

        $temp2 = $temp['other'];

        $url = new moodle_url('../report/adv_configlog/edit.php', ['configid' => $event->objectid]);
        redirect($url);
    }
}
