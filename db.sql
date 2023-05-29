CREATE DATABASE hw1;
USE hw1;

CREATE TABLE users (
	username varchar(16) primary key,
	name varchar(255) NOT NULL,
	lastname varchar(255) NOT NULL,
	email varchar(255) NOT NULL UNIQUE,
	password varchar(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE posts (
	id integer primary key auto_increment,
    author varchar(16) NOT NULL,
    date varchar(10),
	content text NOT NULL,
    index(author),
    foreign key(author) references users(username)
) ENGINE=InnoDB;
    
CREATE TABLE comments (
	id integer primary key auto_increment,
	author varchar(16) NOT NULL,
	content text,
	media boolean,
	post_id integer,
	index(author), 
	index(post_id),
	foreign key(author) references users(username),
	foreign key(post_id) references posts(id)
) ENGINE = InnoDB;

CREATE TABLE liked (
	id integer primary key auto_increment,
	username varchar(16) NOT NULL,
	post_id integer,
    index(username), 
    index(post_id),
    foreign key(username) references users(username),
	foreign key(post_id) references posts(id)
) ENGINE=InnoDB;

-- Inserimento dei dati per gli utenti a titolo di esempio, le password ovviamente non sono hashate, quindi non si potrà accedere a tali utenti 
INSERT INTO users (username, name, lastname, email, password) VALUES
('utente1', 'Nome1', 'Cognome1', 'email1@example.com', 'password1'),
('utente2', 'Nome2', 'Cognome2', 'email2@example.com', 'password2'),
('utente3', 'Nome3', 'Cognome3', 'email3@example.com', 'password3'),
('utente4', 'Nome4', 'Cognome4', 'email4@example.com', 'password4'),
('utente5', 'Nome5', 'Cognome5', 'email5@example.com', 'password5');

-- Inserimento dei post e dei commenti a titolo di esempio 
INSERT INTO posts (id, author, date, content) VALUES
(1, 'utente1', '2022-05-28', 'Domanda: Qual è il miglior mixer audio per concerti dal vivo?'),
(2, 'utente3', '2023-04-23', "Ho bisogno di consigli su come gestire i cavi per l'illuminazione."),
(3, 'utente4', '2023-05-28', "Quali sono i migliori altoparlanti portatili per eventi all'aperto?"),
(4, 'utente1', '2023-05-28', 'Che noia oggi sto lavorando in teaatro, sarei voluto essere in piazza a fare un bel concerto!');

INSERT INTO comments (id, author, content, media, post_id) VALUES
(1, 'utente5', 'Ti consiglio di utilizzare fascette per cavi e canaline per mantenere tutto ordinato.', 0, 2),
(2, 'utente2', 'Io ho sempre usato il Soundcraft Si Performer e mi sono trovato bene', 0, 1),
(3, 'utente2', 'Ho provato i Bose S1 Pro e mi hanno sorpreso per la qualità del suono.', 0, 3),
(4, 'utente5', 'https://media1.giphy.com/media/RIAn3suKf7EXc62sGl/200w.gif?cid=08084a86wq7is2juhwpf79p3b95tt78dal0b8wg7jlpkv4bi&ep=v1_gifs_search&rid=200w.gif&ct=g', 1, 4),
(5, 'utente5', 'https://media0.giphy.com/media/iGowJfpQRSqhfXhHUY/200w.gif?cid=08084a86jkzzulozj75x4ycjto939ubpdn9525b1b2o2b9cj&ep=v1_gifs_search&rid=200w.gif&ct=g', 1, 1);