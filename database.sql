CREATE DATABASE kalendarz DEFAULT CHARACTER SET utf8;

CREATE TABLE kalendarz_terminy(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nazwa_terminu VARCHAR(25),
    opis_terminu VARCHAR(255),
    data_rozpoczecia DATETIME
);
