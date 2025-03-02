<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) { // Asegurar que el usuario tiene permisos de administración
    $settings = new admin_settingpage('local_dnivalidation', get_string('pluginname', 'local_dnivalidation'));

    // Añadir la página de configuración dentro de "localplugins"
    $ADMIN->add('localplugins', $settings);

    // Agregar la opción para habilitar o deshabilitar la validación de DNI
    $settings->add(new admin_setting_configcheckbox(
        'local_dnivalidation/enabled',
        get_string('enabled', 'local_dnivalidation'),
        get_string('enableddesc', 'local_dnivalidation'),
        1 // Activado por defecto
    ));
}
