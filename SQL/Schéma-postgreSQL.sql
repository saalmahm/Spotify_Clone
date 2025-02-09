-- Database: musify_clone

-- DROP DATABASE IF EXISTS musify_clone;

CREATE DATABASE musify_clone
    WITH
    OWNER = postgres
    ENCODING = 'UTF8'
    LC_COLLATE = 'French_France.1252'
    LC_CTYPE = 'French_France.1252'
    LOCALE_PROVIDER = 'libc'
    TABLESPACE = pg_default
    CONNECTION LIMIT = -1
    IS_TEMPLATE = False;

-- Table des utilisateurs
CREATE TABLE Users (
    idUser SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(10) CHECK (role IN ('user', 'artiste', 'admin')) DEFAULT 'user',
    status VARCHAR(10) CHECK (status IN ('active', 'banned')) DEFAULT 'active',
    image VARCHAR(255),
    phone VARCHAR(15)
);

ALTER TABLE Users
ALTER COLUMN role TYPE VARCHAR(50);
ALTER TABLE Users
ALTER COLUMN status TYPE VARCHAR(50);
ALTER TABLE Users
ALTER COLUMN role TYPE VARCHAR(60);
-- Table des catégories
CREATE TABLE Category (
    idCategory SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

-- insertion
-- Insérer 16 catégories dans la table Category
INSERT INTO Category (name) VALUES
('Pop'),
('Rock'),
('Hip Hop'),
('Jazz'),
('Classique'),
('Electro'),
('Reggae'),
('Blues'),
('Country'),
('Folk'),
('R&B'),
('Soul'),
('Funk'),
('Disco'),
('Latino'),
('Indie');
-- Table des chansons
CREATE TABLE Chanson (
    idChanson SERIAL PRIMARY KEY,
    titre VARCHAR(100) NOT NULL,
    image VARCHAR(255),
    type VARCHAR(10) CHECK (type IN ('audio', 'video')) NOT NULL,
    artisteId INT NOT NULL,
    categorieId INT,
    duree INTERVAL,
    songFile VARCHAR(255),
    FOREIGN KEY (artisteId) REFERENCES Users (idUser) ON DELETE CASCADE,
    FOREIGN KEY (categorieId) REFERENCES Category (idCategory) ON DELETE SET NULL
);
-- Table des playlists
CREATE TABLE PlayListe (
    idPlayListe SERIAL PRIMARY KEY,
    titre VARCHAR(100) NOT NULL,
    type VARCHAR(10) CHECK (type IN ('album', 'playlist', 'favoris')) NOT NULL,
    userId INT NOT NULL,
    anneeSortie INT CHECK (anneeSortie >= 1900 AND anneeSortie <= EXTRACT(YEAR FROM NOW())),
    visibilite VARCHAR(10) CHECK (visibilite IN ('visible', 'hidden')) DEFAULT 'visible',
    FOREIGN KEY (userId) REFERENCES Users (idUser) ON DELETE CASCADE
);

-- Table de liaison entre playlists et chansons
CREATE TABLE PlayListeChanson (
    playListeId INT NOT NULL,
    chansonId INT NOT NULL,
    PRIMARY KEY (playListeId, chansonId),
    FOREIGN KEY (playListeId) REFERENCES PlayListe (idPlayListe) ON DELETE CASCADE,
    FOREIGN KEY (chansonId) REFERENCES Chanson (idChanson) ON DELETE CASCADE
);

-- Table des chansons aimées par les utilisateurs
CREATE TABLE ChansonAimee (
    userId INT NOT NULL,
    chansonId INT NOT NULL,
    PRIMARY KEY (userId, chansonId),
    FOREIGN KEY (userId) REFERENCES Users (idUser) ON DELETE CASCADE,
    FOREIGN KEY (chansonId) REFERENCES Chanson (idChanson) ON DELETE CASCADE
);

-- Table des albums gérés par les artistes
CREATE TABLE Album (
    idAlbum SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    artisteId INT NOT NULL,
    FOREIGN KEY (artisteId) REFERENCES Users (idUser) ON DELETE CASCADE
);

-- Table de liaison entre albums et chansons
CREATE TABLE AlbumChanson (
    albumId INT NOT NULL,
    chansonId INT NOT NULL,
    PRIMARY KEY (albumId, chansonId),
    FOREIGN KEY (albumId) REFERENCES Album (idAlbum) ON DELETE CASCADE,
    FOREIGN KEY (chansonId) REFERENCES Chanson (idChanson) ON DELETE CASCADE
);
