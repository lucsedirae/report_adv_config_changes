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

namespace report_adv_configlog\reportbuilder\local\systemreports;

use context_system;
use core_reportbuilder\local\report\action;
use report_adv_configlog\reportbuilder\local\entities\config_change;
use core_reportbuilder\system_report;
use core_reportbuilder\local\entities\user;
use report_adv_configlog\reportbuilder\local\entities\config_note;
use stdClass;

/**
 * Config changes system report class implementation
 *
 * This plugin is a fork of the core report_configlog report.
 *
 * @package   report_adv_configlog
 * @copyright 2023 Jon Deavers jondeavers@gmail.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class config_changes extends system_report {

    /**
     * Initialise report, we need to set the main table, load our entities and set columns/filters
     */
    protected function initialise(): void {
        // Our main entity, it contains all of the column definitions that we need.
        $entitymain = new config_change();
        $entitymainalias = $entitymain->get_table_alias('config_log');

        $this->set_main_table('config_log', $entitymainalias);
        $this->add_entity($entitymain);

        $entitynotes = new config_note();
        $notestablealias = $entitynotes->get_table_alias('advconfiglog');
        $this->add_entity($entitynotes->add_join(
                "LEFT JOIN {advconfiglog} {$notestablealias} ON {$notestablealias}.configid = {$entitymainalias}.id"
        ));

        // We can join the "user" entity to our "main" entity using standard SQL JOIN.
        $entityuser = new user();
        $entityuseralias = $entityuser->get_table_alias('user');
        $this->add_entity($entityuser
                ->add_join("LEFT JOIN {user} {$entityuseralias} ON {$entityuseralias}.id = {$entitymainalias}.userid")
        );

        // Now we can call our helper methods to add the content we want to include in the report.
        $this->add_columns();
        $this->add_filters();

        // Add actions for notes.
        $this->add_base_fields("{$entitymainalias}.id");
        $this->add_base_fields("{$notestablealias}.id AS noteid");

        $url = new \moodle_url('/report/adv_configlog/edit.php', ['configid' => ':id']);
        $icon = new \pix_icon('t/edit', get_string('edit'));
        $action = new action($url, $icon);
        $this->add_action($action);

        $url = new \moodle_url('/report/adv_configlog/index.php', ['noteid' => ':noteid', 'delete' => 1]);
        $icon = new \pix_icon('t/delete', get_string('delete'));
        $action = new action($url, $icon);
        $action->add_callback(function($row) {
            if (empty($row->noteid)) {
                return false;
            }
            return true;
        });
        $this->add_action($action);

        // Set if report can be downloaded.
        $this->set_downloadable(true, get_string('pluginname', 'report_adv_configlog'));
    }

    /**
     * Validates access to view this report
     *
     * @return bool
     */
    protected function can_view(): bool {
        return has_capability('moodle/site:config', context_system::instance());
    }

    /**
     * Adds the columns we want to display in the report
     *
     * They are all provided by the entities we previously added in the {@see initialise} method, referencing each by their
     * unique identifier
     */
    protected function add_columns(): void {
        $columns = [
                'config_change:timemodified',
                'user:fullnamewithlink',
                'config_change:plugin',
                'config_change:setting',
                'config_change:newvalue',
                'config_change:oldvalue',
                'config_note:notes',
        ];

        $this->add_columns_from_entities($columns);

        // Default sorting.
        $this->set_initial_sort_column('config_change:timemodified', SORT_DESC);

        // Custom callback to show 'CLI or install' in fullname column when there is no user.
        if ($column = $this->get_column('user:fullnamewithlink')) {
            $column->add_callback(static function(string $fullname, stdClass $row): string {
                return $fullname ?: get_string('usernone', 'report_adv_configlog');
            });
        }
    }

    /**
     * Adds the filters we want to display in the report
     *
     * They are all provided by the entities we previously added in the {@see initialise} method, referencing each by their
     * unique identifier
     */
    protected function add_filters(): void {
        $filters = [
                'config_change:setting',
                'config_change:value',
                'config_change:oldvalue',
                'user:fullname',
                'config_change:timemodified',
        ];

        $this->add_filters_from_entities($filters);
    }
}
