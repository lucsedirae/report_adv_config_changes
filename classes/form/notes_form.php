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
 * This plugin is a fork of the core report_configlog report.
 *
 * @package   report
 * @subplugin adv_configlog
 * @copyright 2023 Jon Deavers jondeavers@gmail.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace report_adv_configlog\form;

use lang_string;
use report_adv_configlog\local\data\confignote;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

use context;
use context_system;
use core_form\dynamic_form;
use moodle_url;
use report_adv_configlog\reportbuilder\local\entities\config_note;

/**
 * Form class for limits.
 */
class notes_form extends dynamic_form {
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
     * Performs capability check.
     *
     * @return void
     * @throws \dml_exception
     * @throws \required_capability_exception
     */
    protected function check_access_for_dynamic_submission(): void {
        require_capability('moodle/site:config', context_system::instance());
    }

    /**
     * Processes formdata for submission with dynamic form.
     *
     * @return array
     */
    public function process_dynamic_submission() {
        $formdata = $this->get_data();

        if (!empty($formdata->id)) {
            $id = $formdata->id;
        }

        $confignote = new confignote($id, $formdata);
        $confignote->save();

        return [
                'message' => 'notessavesuccess',
                'messagetype' => 'success',
        ];
    }

    /**
     * Sets the data to the dynamic form from the JS passed args.
     */
    public function set_data_for_dynamic_submission(): void {
        // TODO.
    }

    /**
     * Gets the URL for the dynamic form submission.
     */
    protected function get_page_url_for_dynamic_submission(): moodle_url {
        return new moodle_url('/report/adv_configlog/index.php');
    }

    /**
     * Form definition.
     *
     * @return void
     */
    protected function definition() {
        $mform = $this->_form;

        // Hidden Id.
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'configid');
        $mform->setType('configid', PARAM_INT);

        $mform->addElement('textarea', 'notes', new lang_string('notes', 'report_adv_configlog'),
                'wrap="virtual" rows="20" cols="50"');
        $mform->setType('textarea', PARAM_TEXT);
    }
}

