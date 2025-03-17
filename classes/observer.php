<?php
namespace local_dnivalidation;

defined('MOODLE_INTERNAL') || die();

// Asegúrate de importar la clase moodle_url correctamente
use moodle_url;
use core\notification;
use core\event\base as base_event;

class observer {
    public static function validate_dni(base_event $event) {
        global $DB, $USER;

        $userid = $event->objectid;

        if (!get_config('local_dnivalidation', 'enabled')) {
            return;
        }

        // Obtener el DNI desde user_info_data
        $dni_record = $DB->get_record('user_info_data', ['userid' => $userid, 'fieldid' => 1], 'data');

        if (!$dni_record || empty($dni_record->data)) {
            return; // Si no hay DNI, no validar
        }

        $dni = trim($dni_record->data);

        if (!self::validarDNI($dni)) {
            // No guardamos el DNI inválido en la base de datos.
            $DB->delete_records('user_info_data', ['userid' => $userid, 'fieldid' => 1]);

            // Establecer el mensaje de error en la sesión
            notification::add(get_string('invaliddni', 'local_dnivalidation'), 4); // 4 es el valor para NOTIFY_ERROR

            // Según el tipo de evento, redirigir a la URL adecuada
            if ($event->eventname === '\core\event\user_created') {
                // Para nuevos usuarios, redirigir al formulario básico de edición
                redirect(new moodle_url('/user/edit.php', ['id' => $userid, 'error' => $error]));
            } else {
                // Para usuarios existentes (actualización), redirigir al formulario avanzado
                redirect(new moodle_url('/user/editadvanced.php', ['id' => $userid, 'error' => $error]));
            }        }
    }
    /**
     * Inyecta la validación en el formulario (para registro y edición).
     *
     * Se carga el módulo AMD si la URL actual es de edición de perfil o de registro.
     */
    public static function inject_form_validation(base_event $event) {
        global $PAGE;
        // Obtener la URL de la página.
        $url = $PAGE->url->out_omit_querystring();
        // Verifica si la URL corresponde a un formulario de registro o edición.
        if (strpos($url, '/user/edit') !== false || strpos($url, '/login/signup.php') !== false) {
            // Llama al módulo AMD para la validación en el formulario.
            $PAGE->requires->js_call_amd('local_dnivalidation/dnivalidation', 'init');
        }
    }
    private static function validarDNI($dni) {
        if (!$dni || !is_string($dni)) {
            return false;
        }

        // Comprobación del formato básico del DNI
        if (!preg_match('/^[0-9]{8}[A-Za-z]$/', $dni)) {
            return false;
        }

        $numero = substr($dni, 0, 8);
        $letra = strtoupper(substr($dni, 8, 1));
        $letras = "TRWAGMYFPDXBNJZSQVHLCKE";
        $indice = intval($numero) % 23;

        return ($letra === substr($letras, $indice, 1));
    }
}
