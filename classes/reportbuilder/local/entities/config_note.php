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
 * Config note entity class implementation
 *
 * This plugin is a fork of the core report_configlog report.
 *
 * @package   report_adv_configlog
 * @copyright 2023 Jon Deavers jondeavers@gmail.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace report_adv_configlog\reportbuilder\local\entities;

use lang_string;
use core_reportbuilder\local\entities\base;
use core_reportbuilder\local\report\column;
use report_adv_configlog\local\data\notes;

/**
 * Configuration note entity class.
 */
class config_note extends base {

    /**
     * Database tables that this entity uses and their default aliases
     *
     * @return array
     */
    protected function get_default_table_aliases(): array {
        return ['advconfiglog' => 'acl',
                'config_log' => 'cfgl',
        ];
    }

    /**
     * The default title for this entity
     *
     * @return lang_string
     */
    protected function get_default_entity_title(): lang_string {
        return new lang_string('entityconfignote', 'report_adv_configlog');
    }

    /**
     * Initialize the entity
     *
     * @return base
     */
    public function initialise(): base {
        $tablealias = $this->get_table_alias('advconfiglog');

        $notescolumn = new column(
                'notes',
                new lang_string('notes', 'report_adv_configlog'),
                $this->get_entity_name()
        );

        $notescolumn->add_joins($this->get_joins());
        $notescolumn->add_field("{$tablealias}.notes");
        $notescolumn->add_field("{$tablealias}.id");
        $notescolumn->add_field("{$tablealias}.configid");
        $notescolumn->set_type(column::TYPE_LONGTEXT);
        $notescolumn->set_is_sortable(true);
        $notescolumn->add_callback(static function(?string $notes, $row): string {
            $output = "<div class='adv_configlog_notes'>";
            $output .= format_text($notes, FORMAT_PLAIN);
            $output .= "</div > ";
            return $output;
        });

        $this->add_column($notescolumn);
        return $this;
    }
}
