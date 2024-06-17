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
 * Local functions.
 *
 * This plugin is a fork of the core report_configlog report.
 *
 * @package   report_adv_configlog
 * @copyright 2023 Jon Deavers jondeavers@gmail.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Truncates a string if longer than the configured string truncation length.
 *
 * @param $string
 * @return string
 * @throws dml_exception
 */
function report_adv_configlog_trim_string($string): string {
    if (empty($string)) {
        $string = '';
    }

    $length = get_config('report_adv_configlog', 'truncatechars');
    $enabled = get_config('report_adv_configlog', 'truncatenotes');

    if ($enabled == '1') {
        $string = (mb_strlen($string) > $length) ? mb_substr($string, 0, $length) : $string;
    }
    return $string;
}
