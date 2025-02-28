const form = document.getElementById('form__registerUser');
const inputs = document.querySelectorAll('#form__registerUser input');
const expression = {
    nom_usu: /^[a-zA-Z0-9]{4,20}$/, // Para el nombre de usuario
    contra: /^[a-zA-Z0-9]{8,12}$/, // Para la contraseña
    email: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/ // Para el email
};

const fields = {
    nom_usu: false,
    contra: false,
    contra2: false,
    email: false
};

const validateForm = (e) => {
    switch (e.target.name) {
        case "nom_usu":
            validateField(expression.nom_usu, e.target, 'nom_usu');
            break;
        case "contra":
            validateField(expression.contra, e.target, 'contra');
            validatePass2();
            break;
        case "contra2":
            validatePass2();
            break;
        case "email":
            validateField(expression.email, e.target, 'email');
            break;
    }
};

const validateField = (regex, input, field) => {
    if (regex.test(input.value)) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        fields[field] = true;
    } else {
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        fields[field] = false;
    }
};

const validatePass2 = () => {
    const pass = document.getElementById('contra');
    const pass2 = document.getElementById('contra2');

    if (pass.value !== pass2.value) {
        pass2.classList.remove('is-valid');
        pass2.classList.add('is-invalid');
        fields['contra2'] = false;
    } else {
        pass2.classList.remove('is-invalid');
        pass2.classList.add('is-valid');
        fields['contra2'] = true;
    }
};

inputs.forEach((input) => {
    input.addEventListener('input', validateForm);
    input.addEventListener('blur', validateForm);
});

form.addEventListener('submit', (e) => {
    if (!Object.values(fields).every(field => field === true)) {
        e.preventDefault();
        alert("Por favor complete el formulario correctamente.");
    }
});
