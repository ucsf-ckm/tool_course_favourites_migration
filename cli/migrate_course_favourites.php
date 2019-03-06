<?php
/**
 * CLI script for migrating course favourites.
 *
 * @package  tool_course_favourites_migration
 * @category cli
 */

use core_favourites\service_factory;

define('CLI_SCRIPT', true);

require(__DIR__.'/../../../../config.php');
require_once($CFG->libdir.'/clilib.php');


/** @var \moodle_database $DB*/
global $DB;

// this may take a while and consume quite a bit of memory...
@set_time_limit(0);
raise_memory_limit(MEMORY_HUGE);

cli_heading('Course Favourites Migration');

cli_writeln('Started migration.');

$dbman = $DB->get_manager();

$legacy_table = 'block_course_favourites';

if (! $dbman->table_exists($legacy_table)) {
    cli_error('Legacy course favourites table does not exist in the database.');
}

$legacy_favourites = $DB->get_records($legacy_table);

cli_writeln('Loaded course favourites for ' . count($legacy_favourites) . ' users.');

$counter = 0;

foreach($legacy_favourites as $legacy_favourite) {
    $user_id = $legacy_favourite->userid;
    $user = $DB->get_record('user', ['id' => $user_id]);
    if (! $user) {
        cli_error("No user with id = {$user_id} found, skipping.");
        continue;
    }

    $service = service_factory::get_service_for_user_context(\context_user::instance($user_id));
    $course_ids = explode(',', trim($legacy_favourite->sortorder));

    foreach($course_ids as $course_id) {

        $course_id = intval($course_id, 10);

        $course = $DB->get_record('course', ['id' => $course_id]);
        if (! $course) {
            cli_problem("No course with id = {$course_id} found, skipping.");
            continue;
        }

        if ($service->favourite_exists('core_course', 'courses', $course_id,
            \context_course::instance($course_id))) {
            cli_problem("Course with id = {$course_id} has already been set as favourite for user with id = {$user_id}.");
            continue;
        }

        $service->create_favourite('core_course', 'courses', $course_id,
            \context_course::instance($course_id));

        $counter++;
    }

    unset($service);
}

cli_writeln("Finished migration, {$counter} course favourites were moved.");
