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

namespace report_adv_configlog\form;

defined('MOODLE_INTERNAL') || die();

global $CFG;

use context;
use context_system;
use core_form\dynamic_form;
use lang_string;
use moodle_url;
use report_adv_configlog\local\data\confignote;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * Form class for notes.
 */
class listener_notes_form extends dynamic_form {
    /**
     * Retrieves context.
     *
     * @return context
     * @throws \dml_exception
     */
    protected function get_context_for_dynamic_submission(): context {
        return context_system::instance();
    }

    /**
     * Process submission.
     *
     * @return void
     * @throws \coding_exception
     */
    public function process_dynamic_submission() {
        $formdata = (array) $this->get_data();

        file_put_contents('/tmp/formdata.json', json_encode($formdata) . PHP_EOL, FILE_APPEND);

        foreach ($formdata as $key => $value) {
            $configid = (int) filter_var($key, FILTER_SANITIZE_NUMBER_INT);

            $record = confignote::get_record(['configid' => $configid]);
            $record->set('notes', $value);
            $record->set('status', confignote::ADV_CONFIGLOG_SYNCED);
            $record->update();
        }
    }

    /**
     * Set data.
     *
     * @return void
     */
    public function set_data_for_dynamic_submission(): void {

    }

    /**
     * Get url.
     *
     * @return moodle_url
     */
    protected function get_page_url_for_dynamic_submission(): moodle_url {
        global $PAGE;
        return $PAGE->url;
    }

    /**
     * Check access.
     *
     * @return void
     */
    protected function check_access_for_dynamic_submission(): void {
        // TODO: Check for is admin here...
    }

    /**
     * Definition.
     *
     * @return void
     * @throws \coding_exception
     * @throws \dml_exception
     */
    protected function definition() {
        global $DB;
        $mform = $this->_form;

        $records = confignote::get_records(['status' => confignote::ADV_CONFIGLOG_LOGGED]);
        foreach ($records as $record) {
            $configid = $record->get('configid');
            $configname = $DB->get_field('config_log', 'name', ['id' => $configid]);

            // TODO: Create a setting that allows this heading to be toggled on and off.
            $mform->addElement('header', 'configname', $configname);

            $label = new lang_string('notesfield', 'report_adv_configlog') . ' ' . $configname;
            $mform->addElement('textarea', 'notes' . $configid, $label,
                    'wrap="virtual" rows="5" cols="40"');
            $mform->setType('textarea', PARAM_TEXT);
        }
    }
}
