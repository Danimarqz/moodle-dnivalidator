<?php
defined('MOODLE_INTERNAL') || die();

$observers = [
    [
        'eventname'   => '\core\event\user_updated',
        'callback'    => 'local_dnivalidation\observer::validate_dni',
        'priority'    => 1000, // Baja prioridad, se ejecuta después de otros procesos
        'internal'    => false,
    ],
    [
        'eventname'   => '\core\event\user_created',
        'callback'    => 'local_dnivalidation\observer::validate_dni',
        'priority'    => 1000,
        'internal'    => false,
    ],
    // Nuevo observer para inyectar la validación en el formulario.
    [
        'eventname'   => '\core\event\page_viewed',
        'callback'    => 'local_dnivalidation\observer::inject_form_validation',
        'priority'    => 999,
        'internal'    => false,
    ],
];
