# report_adv_configlog

Github repository: https://github.com/lucsedirae/report_adv_configlog

This plugin is a fork of Moodle 4.1 report_configlog. It adds the following features:

- Ability to create, edit, and delete notes associated with each report row entry.

## Installation

1. Copy or clone complete directory to Moodle site report directory path: webroot/report/adv_configlog
2. Run command line upgrade or navigate to a new page in site UI as site admin

## Usage

Navigate to site administration -> reports -> advanced config changes. The report will be populated with 
all admin config settings including plugins. To add a note to a setting that does not contain one, click the
gear icon in the row you wish to add the notes on the right side of the report then select edit. The edit
will also allow users to edit existing notes. To delete a note, click the gear icon and select the delete
option. A notification will display confirmation that the note was deleted.

## Contributions
To submit a feature, bugfix, or other change create a pull request in this repository with MOODLE_401_STAGING
set as the target branch.

## Contact

Author  - Jon Deavers
Email   - jonathan.deavers@moodle.com
GitHub  - lucsedirae