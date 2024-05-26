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

    public function process_dynamic_submission() {
        $formdata = $this->get_data();

        return [
                'message' => 'limitsavesuccess',
                'messagetype' => 'success',
            // TODO:Break up and adjust individual data in formdata.
                'data' => $formdata,
        ];
    }

    public function set_data_for_dynamic_submission(): void {
        // TODO: Implement set_data_for_dynamic_submission() method.
    }

    protected function get_page_url_for_dynamic_submission(): moodle_url {
        global $PAGE;

        return $PAGE->url;
    }

    protected function check_access_for_dynamic_submission(): void {
        // TODO: Implement check_access_for_dynamic_submission() method.
    }

    protected function definition() {
        $mform = $this->_form;

        // Hidden Id.
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'configid');
        $mform->setType('configid', PARAM_INT);

        $mform->addElement('textarea', 'notes', new lang_string('notes', 'report_adv_configlog'),
                'wrap="virtual" rows="5" cols="40"');
        $mform->setType('textarea', PARAM_TEXT);

        $this->add_action_buttons();
    }
}
