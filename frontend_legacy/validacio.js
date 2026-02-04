document.addEventListener("DOMContentLoaded", function() {
    
    // Seleccionem el formulari i els camps
    const form = document.getElementById('contactForm');
    const nom = document.getElementById('nom');
    const email = document.getElementById('email');
    const assumpte = document.getElementById('assumpte');
    const missatge = document.getElementById('missatge');

    //  Afegim 'novalidate' per desactivar els missatges automàtics del navegador
    // (Així controlem nosaltres com es mostren)
    form.setAttribute('novalidate', true);

    // Funció per personalitzar missatges d'error (Basada en els apunts)
    function getCustomErrorMessage(input) {
        const validity = input.validity;

        if (validity.valid) {
            return ''; // No hi ha error
        }

        // valueMissing: L'atribut 'required' no es compleix
        if (validity.valueMissing) {
            return 'Aquest camp és obligatori.';
        }

        // tooShort: L'atribut 'minlength' no es compleix
        if (validity.tooShort) {
            return `El text és massa curt. Ha de tenir almenys ${input.minLength} caràcters.`;
        }

        // typeMismatch: El tipus (ex: email) no coincideix (HTML5 bàsic)
        if (validity.typeMismatch) {
            return 'El format no és vàlid (validació HTML5).';
        }

        // customError: Error posat manualment amb setCustomValidity()
        if (validity.customError) {
            return input.validationMessage; // Retorna el missatge que hem posat nosaltres
        }

        return 'Dada no vàlida.';
    }

    // Funció per mostrar l'error al costat de l'input
    function showError(input, errorId) {
        const errorSpan = document.getElementById(errorId);
        const message = getCustomErrorMessage(input);
        
        errorSpan.textContent = message;
        
        if (message) {
            errorSpan.style.display = 'block';
            input.classList.add('input-error'); // Opcional: per estils CSS
        } else {
            errorSpan.style.display = 'none';
            input.classList.remove('input-error');
        }
    }

    // Escoltador de l'event SUBMIT
    form.addEventListener('submit', (event) => {
        
        // Resetegem validacions personalitzades abans de comprovar res
        email.setCustomValidity('');

        // Validació amb Expressions Regulars (Regex) per a l'Email
        // Patró: text + @ + text + . + text
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        // Si hi ha text i no compleix la Regex, marquem error personalitzat
        if (email.value !== '' && !emailRegex.test(email.value)) {
            // Això activa la propietat validity.customError = true
            email.setCustomValidity('El format del correu no és vàlid (Regex JS).');
        }

        // Comprovem la validesa global del formulari
        // checkValidity() retorna false si algun camp falla (required, minlength o customValidity)
        if (!form.checkValidity()) {
            // Aturem l'enviament
            event.preventDefault();
            
            // Mostrem els errors de cada camp
            showError(nom, 'error-nom');
            showError(email, 'error-email');
            showError(assumpte, 'error-assumpte');
            showError(missatge, 'error-missatge');
        }
    });

    // Opcional: Netejar errors mentre l'usuari escriu (input event)
    [nom, email, assumpte, missatge].forEach(input => {
        input.addEventListener('input', function() {
            // Limpiamos custom validity al escribir para que re-evalue
            if (this === email) this.setCustomValidity('');
            
            // Busquem l'ID de l'error dinàmicament (ex: error-nom)
            const errorId = 'error-' + this.id;
            showError(this, errorId);
        });
    });

});