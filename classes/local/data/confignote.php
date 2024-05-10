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
 * Form class for managing config notes.
 *
 * This plugin is a fork of the core report_configlog report.
 *
 * @package   report_adv_configlog
 * @copyright 2023 Jon Deavers jondeavers@gmail.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace report_adv_configlog\local\data;

use lang_string;

/**
 * Config note peristent class.
 */
class confignote extends base {
    /**
     * Advanced config log plugin table.
     */
    const TABLE = 'advconfiglog';
    const ADV_CONFIGLOG_LOGGED = 'logged';
    const ADV_CONFIGLOG_SYNCED = 'synced';

    /**
     * Define persistent properites.
     *
     * @return array[]
     */
    protected static function define_properties() {
        return [
                'configid' => [
                        'type' => PARAM_INT,
                        'null' => NULL_NOT_ALLOWED,
                ],
                'status' => [
                        'type' => PARAM_TEXT,
                        'null' => NULL_NOT_ALLOWED,
                        'choices' => [
                                self::ADV_CONFIGLOG_LOGGED,
                                self::ADV_CONFIGLOG_SYNCED,
                        ],
                ],
                'notes' => [
                        'type' => PARAM_TEXT,
                        'null' => NULL_NOT_ALLOWED,
                ],
        ];
    }
}
