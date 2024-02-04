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
 * report_adv_config monitors cohort concurrency and limits access based on restrictions.
 *
 * @ report_adv_configlog
 * @copyright 2023 Jon Deavers jondeavers@gmail.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
import ModalForm from 'core_form/modalform';
import Log from 'core/log';
import Config from 'core/config';
import {get_string as getString} from 'core/str';

export const initForm = (linkSelector, formClass) => {
    let buttons = document.querySelectorAll(linkSelector);

    window.console.log(buttons);

    buttons.forEach((button) => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            let limitId = button.dataset.id;
            Log.debug(`Limit ID: ${limitId}`);
            const form = new ModalForm({
                formClass,
                args: limitId ? {limitid: limitId} : {},
                modalConfig: {title: limitId ?
                        getString('editnotes', 'report_adv_configlog') : getString('createnotes', 'report_adv_config')},
                returnFocus: e.currentTarget
            });
            // If necessary extend functionality by overriding class methods, for example:
            form.addEventListener(form.events.FORM_SUBMITTED, (e) => {
                const response = e.detail;
                Log.debug('Form submitted...');
                let redirect = `${Config.wwwroot}/report/adv_configlog/index.php?`;
                let first = false;
                for (let key in response) {
                    if (first) {
                        first = false;
                    } else {
                        redirect = redirect + '&';
                    }
                    redirect = redirect + `${key}=${response[key]}`;
                }
                window.location.assign(redirect);
            });

            // Demo of different events.
            form.addEventListener(form.events.LOADED, () => {
                Log.debug('Modal form loaded');
            });
            form.addEventListener(form.events.NOSUBMIT_BUTTON_PRESSED,
                () => Log.debug('No submit button pressed'));
            form.addEventListener(form.events.CLIENT_VALIDATION_ERROR, () => Log.debug('Client-side validation error'));
            form.addEventListener(form.events.SERVER_VALIDATION_ERROR, () => Log.debug('Server-side validation error'));
            form.addEventListener(form.events.ERROR, (e) => Log.debug('Oopsie - ' + e.detail.message));
            form.addEventListener(form.events.SUBMIT_BUTTON_PRESSED, () => Log.debug('Submit button pressed'));
            form.addEventListener(form.events.CANCEL_BUTTON_PRESSED, () => Log.debug('Cancel button pressed'));

            form.show();
        });
    });
};