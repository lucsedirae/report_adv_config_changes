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
 * Handles note data migration.
 *
 * This plugin is a fork of the core report_configlog report.
 *
 * @package   report_adv_configlog
 * @copyright 2023 Jon Deavers jondeavers@gmail.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace report_adv_configlog\local;

use report_adv_configlog\local\data\confignote;

/**
 * Handler for notes persistent class.
 */
class note_handler {
    /**
     * @var int config log table id.
     */
    private $objectid;

    /**
     * @var int config log table userid
     */
    private $userid;
    private array $note;

    /**
     * Constructor
     *
     * @param array $note
     */
    public function __construct(array $note) {
        $this->objectid = $note['objectid'];
        $this->userid = $note['userid'];
        $this->note = $note;
    }

    /**
     * Execute handler.
     *
     * @return void
     * @throws \coding_exception
     * @throws \core\invalid_persistent_exception
     */
    public function run() {
        global $PAGE;

        echo "NOTE" . PHP_EOL;
        print_r($this->note);
        echo PHP_EOL;

        if (($PAGE->pagelayout === 'admin') && ($this->userid == 0)) {
            $persistent = new confignote(0);
            $persistent->set('configid', $this->objectid);
            $persistent->set('status', confignote::ADV_CONFIGLOG_LOGGED);
            $persistent->set('notes', '');

            $persistent->create();
        }
    }
}
