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
 * Report settings
 *
 * This plugin is a fork of the core report_configlog report.
 *
 * @package  report_adv_configlog
 * @copyright 2023 Jon Deavers jondeavers@gmail.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

// External page setup.
$ADMIN->add('reports', new admin_externalpage('report_adv_configlog', get_string('pluginname', 'report_adv_configlog'),
        "$CFG->wwwroot/report/adv_configlog/index.php"));

if ($hassiteconfig) {
    $ADMIN->add('reports', new admin_category('report_adv_configlog_settings', get_string('pluginname', 'report_adv_configlog')));
    $settingspage = new admin_settingpage('managereportadvconfiglog', get_string('managereportsettings', 'report_adv_configlog'));

    // Plugin settings.
    $setting = new admin_setting_configcheckbox('report_adv_configlog/enablepopup',
            new lang_string('enablepopup', 'report_adv_configlog'),
            new lang_string('enablepopup_desc', 'report_adv_configlog'),
            0);
    $settingspage->add($setting);

    $ADMIN->add('reports', $settingspage);
}
