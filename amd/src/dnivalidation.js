define([], function () {
  return {
    init: function () {
      document.addEventListener('DOMContentLoaded', function () {
        // Selecciona el formulario; asumimos que su id es "mform1"
        var form = document.querySelector('form#mform1');
        if (!form) {
          return;
        }

        // Buscar el campo DNI; se asume que el name del campo es "dni"
        var dniField = form.querySelector('[name="dni"]');
        if (!dniField) {
          return;
        }

        // Crear un contenedor para el mensaje de error, si no existe
        var errorContainer = document.createElement('div');
        errorContainer.id = 'dni-error';
        errorContainer.style.color = 'red';
        errorContainer.style.marginTop = '5px';
        // Insertar el contenedor de error justo después del campo DNI
        dniField.parentNode.insertBefore(errorContainer, dniField.nextSibling);

        form.addEventListener('submit', function (e) {
          // Limpiar cualquier mensaje de error previo
          errorContainer.innerText = '';

          var dni = dniField.value.trim();

          // Validar formato: 8 dígitos seguidos de una letra
          var regex = /^[0-9]{8}[A-Za-z]$/;
          if (!regex.test(dni)) {
            errorContainer.innerText = M.util.get_string('invaliddni', 'local_dnivalidation');
            e.preventDefault();
            return false;
          }

          // Validar que la letra de control sea correcta
          var numberPart = dni.substr(0, 8);
          var letterPart = dni.substr(8, 1).toUpperCase();
          var letters = "TRWAGMYFPDXBNJZSQVHLCKE";
          var index = parseInt(numberPart, 10) % 23;
          var expectedLetter = letters.charAt(index);

          if (letterPart !== expectedLetter) {
            errorContainer.innerText = M.util.get_string('invaliddni', 'local_dnivalidation');
            e.preventDefault();
            return false;
          }
        });
      });
    }
  };
});
