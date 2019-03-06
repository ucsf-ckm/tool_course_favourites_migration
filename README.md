# Course Favourites Migration

Migration tool for transferring user course favourites the 
[course favourites block](https://github.com/ucsf-ckm/moodle-block-course_favourites) 
into Moodle 3.6's new favourites subsystem.


## Installation

### Via Git
* use a command line interface of your choice on the destination system (server with Moodle installation)
* switch to the moodle local folder: `cd /path/to/moodle/admin/tool/`
* `git clone https://github.com/ucsf-ckm/tool_course_favourites_migration.git course_favourites_migration`

## Via download
* download zip file from github: https://github.com/ucsf-ckm/tool_course_favourites_migration/archive/master.zip
* unpack zip file to `/path/to/moodle/admin/tool/`
* rename directory `tool_course_favourites_migration` to `course_favourites_migration`

## Usage

This tool only has a command line interface.  
From your application's web root directory, invoke the migration process by running:  

`php admin/tool/course_favourites_migration/cli/migrate_course_favourites.php`


## Copyright and License

This is Open Source Software, published under the GPL v3.

Copyright (c) 2019 The Regents of the University of California.
