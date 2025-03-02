<?php
defined('MOODLE_INTERNAL') || die();

$observers = [
    [
        'eventname'   => '\core\event\user_updated',
        'callback'    => 'local_dnivalidation\observer::validate_dni',
        'priority'    => 1000, // Baja prioridad, se ejecuta después de otros procesos
        'internal'    => false,
    ],
];
