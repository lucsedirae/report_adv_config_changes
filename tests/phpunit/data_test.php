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
 * Test suite for persistent data handler.
 *
 * @package   report_adv_configlog
 * @copyright 2023 Jon Deavers jondeavers@gmail.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace report_adv_configlog\phpunit;

use report_adv_configlog\local\data\confignote;

/**
 * Tests for note data handler.
 */
class data_test extends \advanced_testcase {
    /**
     * Tests creation of confignote persistent.
     *
     * @covers \report_adv_configlog\local\note_handler
     * @return void
     * @throws \core\invalid_persistent_exception
     * @throws coding_exception
     * @throws dml_exception
     */
    public function test_create_confignote() {
        global $DB;
        $this->resetAfterTest();

        $data = new stdClass();
        $data->configid = 54321;
        $data->status = confignote::ADV_CONFIGLOG_LOGGED;
        $data->notes = 'Test note text';

        $persistent = new confignote(0, $data);
        $persistent->create();

        $test = $DB->get_record(confignote::TABLE, ['configid' => 54321]);
        self::assertEquals('logged', $test->status);
    }

    /**
     * Tests updating of confignote persistent.
     *
     * @covers \report_adv_configlog\local\note_handler
     * @return void
     * @throws \core\invalid_persistent_exception
     * @throws coding_exception
     */
    public function test_update_confignote() {
        $this->resetAfterTest();

        $persistent = confignote::get_record(['configid' => 12345]);

        self::assertEquals('logged', $persistent->get('status'));

        $persistent->set('status', confignote::ADV_CONFIGLOG_SYNCED);
        $persistent->update();

        self::assertEquals('synced', $persistent->get('status'));
    }

    /**
     * Tests deletion of confignote persistent.
     *
     * @covers \report_adv_configlog\local\note_handler
     * @return void
     * @throws coding_exception
     * @throws dml_exception
     */
    public function test_delete_confignote() {
        global $DB;
        $this->resetAfterTest();

        $persistent = confignote::get_record(['configid' => 12345]);

        self::assertEquals('logged', $persistent->get('status'));

        $persistent->delete();

        $test = $DB->get_record(confignote::TABLE, ['configid' => 12345]);
        self::assertEmpty($test);
    }

    /**
     * Tests get_logged_notes persistent method.
     *
     * @covers \report_adv_configlog\local\note_handler
     * @return void
     * @throws \core\invalid_persistent_exception
     * @throws coding_exception
     */
    public function test_get_logged_notes() {
        $this->resetAfterTest();

        for ($x = 0; $x < 9; $x++) {
            $data = new stdClass();
            $data->configid = 10000 . $x;
            $data->status = confignote::ADV_CONFIGLOG_LOGGED;
            $data->notes = 'Test note text:' . ' ' . $x;

            $persistent = new confignote(0, $data);
            $persistent->create();
        }

        $logged = confignote::get_notes_by_status(confignote::ADV_CONFIGLOG_LOGGED);

        self::assertCount(10, $logged);
    }

    /**
     * Set up
     *
     * @return void
     * @throws \core\invalid_persistent_exception
     * @throws coding_exception
     * @throws dml_exception
     */
    protected function setUp(): void {
        global $DB;
        // Clear notes created during install.
        // As far as I can tell, this is test suite only and does not
        // occur on install of site/plugin. Needs further testing to
        // confirm.
        $DB->delete_records(confignote::TABLE, ['status' => confignote::ADV_CONFIGLOG_LOGGED]);

        // Placeholder for setup script.
        $data = new stdClass();
        $data->configid = 12345;
        $data->status = confignote::ADV_CONFIGLOG_LOGGED;
        $data->notes = 'Test note text';

        $persistent = new confignote(0, $data);
        $persistent->create();
    }
}
