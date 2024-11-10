
    function showPassword(field){
        const passwordField = document.getElementById(field)
        if(passwordField){
            passwordField.type = passwordField.type === 'password' ? 'text' : 'password'
        }
    }


