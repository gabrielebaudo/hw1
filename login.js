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

    if(input.value.length > 0) {
        input.parentNode.classList.remove('error');
        formStatus.username = false;
    }
    else {
        input.parentNode.classList.add('error');
        formStatus.username = true;
    }
}

function checkPassword(event){
    const input = event.currentTarget;

    if(input.value.length > 0) {
        input.parentNode.classList.remove('error');
        formStatus.pass = false;
    }
    else {
        input.parentNode.classList.add('error');
        formStatus.pass = true;
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


const formStatus = {'username': true, 'pass': true};

document.querySelector(".username input").addEventListener('blur', checkUsername);
document.querySelector(".pass input").addEventListener('blur', checkPassword);
document.querySelector(".pass img").addEventListener('click', showPassword);
document.querySelector("input[type='submit']").addEventListener('click',onSubmit);
