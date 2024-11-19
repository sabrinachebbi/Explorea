
    function showPassword(field){
        // / Récupère le champ de mot de passe par son ID
        const passwordField = document.getElementById(field)
        // Si le champ existe, alterne entre 'text' et 'password'
        if(passwordField){
            passwordField.type = passwordField.type === 'password' ? 'text' : 'password'
        }
    }


