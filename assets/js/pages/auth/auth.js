document.getElementById("signInForm").addEventListener(
    "submit",
    event => {
        event.preventDefault();

        const username = document.getElementById('emailCustomer').value;
        const password = document.getElementById('passwordCustomer').value;

        if (password.toUpperCase() == "ZLATAN") {
            location.href = `${urlHost}auth/create-password/email/${username}/`;
        } else {
            document.getElementById('loginButton').disabled = true;
            loginUser(username, password);
        }
    },
    false
);

async function loginUser(username, password) {
    try {
        const axiosConfig = { headers: { 'Content-Type': 'application/json' } };
        const postData = { username, password };
        const form = document.getElementById('signInForm');
        const result = await axios.post(form.action, postData, axiosConfig);
        sendToServer(result.data);
    } catch (error) {
        toastr.error('Vos identifiants sont incorrects');
        document.getElementById('loginButton').disabled = false;
    }
}

async function sendToServer(result) {
    try {
        const data = new FormData();
        data.set('token', result.token);
        data.set('userIdentifier', result.user.identifier);
        data.set('userRoles', result.user.roles);

        const axiosConfig = { headers: { 'Content-Type': 'multipart/form-data' } };
        await axios.post('../auth/check', data, axiosConfig);
        document.getElementById('loginButton').disabled = false;
        location.href = urlHost;
    } catch (error) {
        toastr.error('Oups ! Une erreur est survenue');
        document.getElementById('loginButton').disabled = false;
    }
}



document.getElementById("signUpForm").addEventListener(
    "submit",
    event => {
        event.preventDefault();
        document.getElementById('signUpButton').disabled = true;
        addUser();
    },
    false
);


async function addUser() {
    try {
        const form = document.getElementById('signUpForm');
        const email = document.getElementById('firstEmail').value.toLowerCase();
        const password = document.getElementById('firstPassword').value;


        const firstname = document.getElementById('firstFirstname').value;
        const lastname = document.getElementById('firstLastName').value;


        if (firstname == "" || lastname == "") {
            toastr.error('Renseignez vos nom et prénom, merci !');

        } else {


            const data = new FormData();
            data.set('email', email);
            data.set('plainPassword', password);
            data.set('apiKey', sha1(email + 'LKf7*D'));

            const axiosConfig = { headers: { 'Content-Type': 'multipart/form-data' } };
            const result = await axios.post(form.action, data, axiosConfig);

            if (result.data.allowUse) {
                addPerson(result.data.identifier, email);
            } else {
                document.getElementById('signUpButton').disabled = false;
                toastr.error('Au moins un des champs est invalide!');
            }


        }


       

    } catch (error) {
        document.getElementById('signUpButton').disabled = false;
        toastr.error('Une erreur est survenue');
    }
}

async function addPerson(identifier, email) {
    try {
        const firstname = document.getElementById('firstFirstname').value;
        const lastname = document.getElementById('firstLastName').value;

        const data = new FormData();
        data.set('url', 'person/create');
        data.set('type', 'POST');
        data.set('data[firstname]', firstname);
        data.set('data[lastname]', lastname);
        data.set('data[identifier]', identifier);
        data.set('data[key]', sha1(firstname));

        const axiosConfig = { headers: { 'Content-Type': 'multipart/form-data' } };
        await axios.post(urlRequest, data, axiosConfig);
        document.getElementById('signUpButton').disabled = false;
        toastr.success('Votre compte a bien été créé, merci de vous connecter.');
        document.getElementById('emailCustomer').value = email;
        document.querySelector('.swiper-signup').style.display = 'none';

    } catch (error) {
        document.getElementById('signUpButton').disabled = false;
        toastr.error('Une erreur est survenue');
    }
}


// Hide/show password
let showPasswordButtonLogin = document.getElementById('showPasswordButtonLogin');
let showPasswordCustomer = document.getElementById('passwordCustomer');
let showPasswordButtonLoginText = document.getElementById('showPasswordButtonLoginText');

showPasswordButtonLogin.addEventListener('click', function () {
    if (showPasswordCustomer.type === "password") {
        showPasswordCustomer.type = "text";
        showPasswordButtonLoginText.innerHTML = 'Cacher le mot de passe';
    }
    else {
        showPasswordCustomer.type = "password";
        showPasswordButtonLoginText.innerHTML = 'Afficher le mot de passe';
    }
});


let showPasswordButtonSignup = document.getElementById('showPasswordButtonSignup');
let firstPassword = document.getElementById('firstPassword');
let showPasswordButtonSignupText = document.getElementById('showPasswordButtonSignupText');

showPasswordButtonSignup.addEventListener('click', function () {
    if (firstPassword.type === "password") {
        firstPassword.type = "text";
        showPasswordButtonSignupText.innerHTML = 'Cacher le mot de passe';
    }
    else {
        firstPassword.type = "password";
        showPasswordButtonSignupText.innerHTML = 'Afficher le mot de passe';
    }
})
