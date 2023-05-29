async function load_post(){
    liked = await fetch("get_liked.php").then(onResponse);
    fetch("load_content.php?q=post").then(onResponse).then(onPostJson);
}

function onResponse(response) {
    return response.json();
}

function onPostJson(json) {
    if (json.length == 0) {
        return;
    }

    //Memorizzo il footer in modo da aggiungere gli elementi prima di lui e non in coda al body col normale append
    const footer = document.querySelector('footer');

    for(const post in json) {
        //Iteriamo anche la lista dei liked per capire se quel post è tra i piaciuti
        for(const item in liked){
            if(json[post].id === liked[item].post_id){
                const box = document.createElement('section');
                box.classList.add("box");
                box.dataset.post_id = json[post].id;

                const username = document.createElement('span');
                username.classList.add("username");
                username.textContent = json[post].author;

                const data = document.createElement('span');
                data.classList.add("date");
                data.textContent = json[post].date;

                const content = document.createElement('div');
                content.classList.add("content");
                content.textContent = json[post].content;

                const like = document.createElement('img');
                like.src = "utility/redlike.png";
                like.dataset.like = "like";
                like.addEventListener("click", onLike);

                const line = document.createElement('div');
                line.classList.add("line");

                const commenti = document.createElement('span');
                commenti.textContent = "Commenti";

                const comment_container = document.createElement('div');
                comment_container.classList.add("comment-container");

                const line2 = document.createElement('div');
                line2.classList.add("line");

                const comment_form = document.createElement('form');
                comment_form.method = "post";
                comment_form.name = "comment";

                const comment_bar = document.createElement('textarea');
                comment_bar.id = "comment-insert";
                comment_bar.type = "text";
                comment_bar.placeholder = "Inserisci il tuo commento";
                comment_bar.name = "comment-insert";

                const post_id = document.createElement('input');
                post_id.name = "post_id";
                post_id.type = "hidden";
                post_id.value = json[post].id;

                const media = document.createElement('input');
                media.name = "media";
                media.type = "hidden";
                media.value = '0';

                const gif_source = document.createElement('input');
                gif_source.name = "gif-source";
                gif_source.type = "hidden";

                const gif = document.createElement('img');
                gif.src = "utility/gif.png";
                gif.addEventListener('click', onGifClick);

                const comment = document.createElement('input');
                comment.id = "submit";
                comment.type = "image";
                comment.src = "utility/submit.png";

                box.appendChild(username);
                box.appendChild(data);
                box.appendChild(content);
                box.appendChild(like);
                box.appendChild(line);
                box.appendChild(commenti);
                box.appendChild(comment_container);

                box.append(line2);
                comment_form.append(comment_bar);
                comment_form.append(gif);
                comment_form.append(comment);

                comment_form.append(post_id);
                comment_form.append(media);
                comment_form.append(gif_source);


                const gif_search = document.createElement('div');
                gif_search.id = "gif-search";

                const search_bar = document.createElement('input');
                search_bar.id = "searchbar";
                search_bar.type = "text";
                search_bar.placeholder = "Cerca la tua gif";

                const submit_gif = document.createElement('input');
                submit_gif.id = "submit-gif";
                submit_gif.type = "image";
                submit_gif.src = "utility/search.png";
                submit_gif.addEventListener('click', onGifSubmit);

                const gif_container = document.createElement('div');
                gif_container.id = "gif-container";


                gif_search.appendChild(search_bar);
                gif_search.appendChild(submit_gif);
                gif_search.appendChild(gif_container);
                gif_search.classList.add('hidden');
                comment_form.appendChild(gif_search);

                box.append(comment_form);
                document.querySelector("body").insertBefore(box, footer);

                loadComments(comment_container, json[post].id);
                break;
            }
        }
    }
}

function onLike(event){
    const input = event.currentTarget;
    let source;
    let type;
    if(input.dataset.like === 'none'){
        source = 'utility/redlike.png';
        input.dataset.like = 'like';
        type = 'insert';
    }
    else {
        source = 'utility/like.png';
        input.dataset.like = 'none';
        type = 'delete';
    }
    changeImage(input, source);
    const post_id = input.parentNode.dataset.post_id;
    const formData = new FormData();
    formData.append('type', type);
    formData.append('post_id', post_id);
    const options = {
        method: 'post', 
        body: formData
    };
    fetch("manage_post.php", options);
}

function changeImage(input,source) {
    input.classList.add('enlarge');
    setTimeout(function() {
      input.src = source;
      input.classList.remove('enlarge');
    }, 500);
}

function loadComments(container, id){
    fetch("load_content.php?q=comment&id=" + id).then(onResponse).then(json => onCommentJson(json, container));
}

function onCommentJson(json, container) {
    if (json.length == 0) {
        return;
    }
    for(const elem in json) {
        const comment = document.createElement('div');
        comment.classList.add("comment");

        const username = document.createElement('span');
        username.classList.add("username");
        username.textContent = json[elem].author;

        const content = document.createElement('div');

        //se è un commento testuale
        if(json[elem].media == '0'){
            content.classList.add("content");
            content.textContent = json[elem].content;

            
        } else { //altimenti nel caso di una gif
            content.classList.add("content-wrap");
            gif = document.createElement('img');
            gif.classList.add("content-gif");
            gif.src = json[elem].content;
            content.appendChild(gif);
        }

        comment.appendChild(username);
        comment.appendChild(content);
        container.appendChild(comment);
    }
}

function onGifClick(event) {
    const gif_button = event.currentTarget;
    const gif_container = gif_button.parentNode.querySelector("#gif-search");
    gif_container.classList.toggle("hidden");
}

function onGifSubmit(event){
    const input = event.currentTarget;

    event.preventDefault();
    const searchbar = input.parentNode.querySelector('#searchbar');
    const content = searchbar.value;
    const gif_container = input.parentNode.querySelector('#gif-container');
    fetch("gif_search.php?q=" + encodeURIComponent(content)).then(onResponse).then(json => onGif(json, gif_container));
}

function onGif(json, container){
    if(!json.length){
        return;
    }
    container.innerHTML = ''; 
    for(const elem in json){
        const gif_wrapper = document.createElement('div');
        gif_wrapper.classList.add("gif-wrap");
        gif_wrapper.type = 'submit';

        const img = document.createElement('img');
        img.src = json[elem];
        img.classList.add("gif");
        
        gif_wrapper.appendChild(img);
        container.appendChild(gif_wrapper);
        gif_wrapper.addEventListener('click', function(event) {
            postGif(container, event);
          });
    }
}

function postGif(post, event){
    const gif_wrap = event.currentTarget;
    const gif = gif_wrap.querySelector("img");

    const form = post.parentNode.parentNode;

    const hiddenField = form.querySelector("input[name='gif-source']");
    //Impostiamo il valore del campo nascosto a quello della gif cliccata
    hiddenField.value = gif.src;

    //impostiamo il campo media ad 1
    const media = form.querySelector("input[name='media']");
    media.value = '1';
    
    //Inviamo il form
    form.submit(); 
}

let liked;
load_post();


