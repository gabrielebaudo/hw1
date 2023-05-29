function onRefresh(){
    formStatus.name = name.value.length > 0 ? false : true;
    formStatus.lastname = lastname.value.length > 0 ? false : true;
    formStatus.username = username.value.length > 0 ? false : true;
    formStatus.mail = mail.value.length > 0 ? false : true;
}

function checkName(event){
    const input = event.currentTarget;

    if(input.value.length > 0) {
        input.parentNode.classList.remove('error');
        formStatus.name = false;
    }
    else {
        input.parentNode.classList.add('error');
        formStatus.name = true;
    }
}

function checkLastname(event){
    const input = event.currentTarget;

    if(input.value.length > 0) {
        input.parentNode.classList.remove('error');
        formStatus.lastname = false;
    }
    else {
        input.parentNode.classList.add('error');
        formStatus.lastname = true;
    }
}

function checkPass(event) {
    const input = event.currentTarget;
    
    if(input.value.length >= 8) {
        input.parentNode.classList.remove('error');
        formStatus.pass = false;
    }
    else {
        input.parentNode.classList.add('error');
        formStatus.pass = true;
    }
}

function checkConfirm(event) {
    const input = event.currentTarget;
    
    const password = document.querySelector("form input[name='pass']");

    if(input.value === password.value) {
        input.parentNode.classList.remove('error');
        formStatus.confirmpass = false;
    }
    else {
        input.parentNode.classList.add('error');
        formStatus.confirmpass = true;
    }
}

function showPassword(event){
    const clicked = event.currentTarget;
    const input = clicked.parentNode.querySelector("input");
    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
    input.type = type;
    const source = input.getAttribute('type') === 'password' ? 'utility/show.png' : 'utility/hidden.png';
    clicked.src = source;
}

function checkUsername(event){
    const input = event.currentTarget;

    if(!/^[a-z][a-z0-9_]{0,15}$/.test(input.value)) {
        input.parentNode.querySelector('span').textContent = "Sono ammesse solo lettere minuscole, numeri e underscore. Max 15 caratteri";
        input.parentNode.classList.remove("green");
        input.parentNode.classList.add('error');
        formStatus.username = true;
    }
    else {
        input.parentNode.classList.remove('error');
        formStatus.username = false;
        fetch("check_values.php?q=u&value=" + encodeURIComponent(input.value)).then(onResponse).then(onUsername)
    }
}

function checkMail(event) {
    const input = event.currentTarget;

    if(!/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(String(input.value).toLowerCase())) {
        input.parentNode.querySelector('span').textContent = "Il formato dell'email non è corretto";
        input.parentNode.classList.remove("green");
        input.parentNode.classList.add('error');
        formStatus.mail = true;
    }
    else {
        input.parentNode.classList.remove('error');
        formStatus.mail = false;
        fetch("check_values.php?q=e&value=" + encodeURIComponent(input.value)).then(onResponse).then(onEmail);
    }
}

function onResponse(response) {
    return response.json();
}

function onUsername(json) {
    const container = document.querySelector(".username");
    if (json.exist) {
        console.log("Username Occupato");
        container.querySelector("span").textContent = "Nome utente già in uso";
        container.classList.remove("green");
        container.classList.add("error");
    } else {
        console.log("Username Libero");
        container.classList.remove("error");
        container.classList.add("green");
    }
}

function onEmail(json) {
    const container = document.querySelector(".mail");
    if (json.exist) {
        console.log("Email Occupata");
        container.querySelector("span").textContent = "L'email è già in uso";
        container.classList.remove("green");
        container.classList.add("error");
    } else {
        console.log("Email Libera");
        container.classList.remove("error");
        container.classList.add("green");
    }
}

function onSubmit(event){
    event.preventDefault();
    const form = document.querySelector("form");
    const button = form.querySelector("input[type='submit']");
    if(Object.values(formStatus).every(status => status === false)){
        form.submit();
    }
}

//se formStatus è tutta false allora il bottone può diventare attivo
function activeButton(){
    const button = document.querySelector("form").querySelector("input[type='submit']");
    if(Object.values(formStatus).every(status => status === false)){
        button.classList.add("active");
    }
    
}

/* Impostiamo tutti gli status a true (rappresentano un errore), in questo modo
facciamo in modo di non poter inviare il form prima di aver compilato tutti i campi senza errori*/
const formStatus = {'name': true, 'lastname': true, 'username': true, 'mail': true, 'pass': true, 'confirmpass': true};

/* Impostiamo prevent a true in modo da non poter inviare il form fino a quando
tutti gli elementi di formStatus sono false (quindi non ci sono errori) */

const name = document.querySelector(".name input");
name.addEventListener('blur', checkName);
const lastname = document.querySelector(".lastname input");
lastname.addEventListener('blur', checkLastname);
const username = document.querySelector(".username input");
username.addEventListener('blur', checkUsername);
const mail = document.querySelector(".mail input")
mail.addEventListener('blur', checkMail);

document.querySelector(".pass input").addEventListener('blur', checkPass);
document.querySelector(".pass img").addEventListener('click', showPassword);
document.querySelector(".confirmpass input").addEventListener('blur', checkConfirm);
document.querySelector(".confirmpass img").addEventListener('click', showPassword);
document.querySelector("input[type='submit']").addEventListener('click',onSubmit);

/*associamo un listener al tasto submit quando il mouse gli viene passato sopra per 
renderlo attivo se il form è complilato correttamente */
document.querySelector("input[type='submit']").addEventListener('mouseover', activeButton);

//controlla che i campi non siano pieni, serve per quando il php ricarica la pagina e riempie i campi, in quel caso non
//avremmo un evento blur associato, per cui formStatus non diventerebbe mai false
onRefresh();
