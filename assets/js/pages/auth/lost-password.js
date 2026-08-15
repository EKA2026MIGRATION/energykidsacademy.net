
function validateEmail(email) {
    return String(email)
        .toLowerCase()
        .match(
            /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
        );
};

document.getElementById("lostPassWordForm").addEventListener(
    "submit",
    event => {
        event.preventDefault();

        const email = document.getElementById('firstEmail').value;

        if (validateEmail(email)) {
            document.getElementById('lostPassWordButton').disabled = true;
            getTokenLostPassword(email);
        } else {
            toastr.error("L'adresse email est incorrecte");
        }
    },
    false
);

async function getTokenLostPassword(email) {
    try {
        const axiosConfig = { headers: { 'Content-Type': 'application/json' } };
        const postData = { email };
        const form = document.getElementById('lostPassWordForm');
        const result = await axios.put(form.action, postData, axiosConfig);
        addOptionsToResetPassword(result.data);
        //sendMailLostPassword(email, result.data);
    } catch (error) {
        toastr.error('Aucun compte avec cet email. Vous devez vous inscrire avant de continuer.');
        document.getElementById('lostPassWordButton').disabled = false;
    }
}

async function addOptionsToResetPassword(data) {

    localStorage.setItem('tokenMail', data.token);
    localStorage.setItem('validityEmail', JSON.stringify(data.validity));
    let url = `${urlHost}auth/lost-password?email=${data.data.email}`;

    data.data.phones.map(phone => {
        url = `${url}&phone[]=${phone}`
    })
    window.location.href = url;
}   

function resetPassword() {
    let radioChecked = $('[name=choiceReset]:checked');
    let validity = localStorage.getItem('validityEmail');
    validity = JSON.parse(validity);
    let token = localStorage.getItem('tokenMail');

    if(radioChecked.length == 0) {
        toastr.error('Vous devez choisir une option');
    } else {
        let classRadio = $(radioChecked).attr('class');
        if(classRadio == 'email') {
            sendMailLostPassword($(radioChecked).val(), token, validity);
        } else {
            sendPasswordByPhone($(radioChecked).val(), token);
        }
    }
}

async function sendPasswordByPhone(phone, token) {
    try {
        const data = new FormData();
        data.set('phone', phone);
        data.set('token', token);

        const axiosConfig = { headers: { 'Content-Type': 'multipart/form-data' } };
        await axios.post(`${urlHost}sendSMSNewPassword`, data, axiosConfig);
        toastr.success('Un sms vient de vous être envoyé.');
        document.querySelector('.resetPasswordOptions').style.display = 'none';
        document.querySelector('.resetPasswordOptionsMessage').style.display = 'block';
        document.querySelector('.resetPasswordOptionsButton').style.display = 'block';        
        document.querySelector('.resetPasswordOptionsMessage').innerHTML = `Un sms contenant un nouveau mot
        de passe vient de vous être envoyé.`;

    } catch (error) {
        toastr.error('Une erreur est survenue');
    }
}


async function sendMailLostPassword(email, token, validity) {
    try {
        const data = new FormData();
        data.set('token', token);
        data.set('validity', validity);
        data.set('email', email);

        const axiosConfig = { headers: { 'Content-Type': 'multipart/form-data' } };
        await axios.post(`${urlHost}sendMailLostPassword`, data, axiosConfig);
        toastr.success('Un email vient de vous être envoyé. Pensez à vérifier vos spams');
        document.querySelector('.resetPasswordOptions').style.display = 'none';
        document.querySelector('.resetPasswordOptionsMessage').style.display = 'block';
        document.querySelector('.resetPasswordOptionsMessage').innerHTML = `Un email vous permettant de 
        réinitialiser votre mot de passe vient de vous être envoyé.`;

    } catch (error) {
        toastr.error('Une erreur est survenue');
    }
}

