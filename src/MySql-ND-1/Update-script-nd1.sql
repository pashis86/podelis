INSERT INTO Authors (name) VALUES ('Jonas'), ('Petras');
INSERT INTO Books (authorId, title, year) VALUES (8, 'Gera knyga', 2011), (9, 'Knyga', 2011);
UPDATE Books SET Books.authorId = 8 WHERE Books.authorId = 9;
DELETE FROM Authors WHERE authorId IN(8, 9);
DELETE FROM Books WHERE Books.authorId IS NULL;

ALTER TABLE Books ADD genre char(255);
ALTER TABLE Authors ENGINE = INNODB;
CREATE TABLE bookAuthors(
    id INT AUTO_INCREMENT,
    bookId INT(11),
    authorId INT(11),
    PRIMARY KEY (id),
   	FOREIGN KEY (bookId) REFERENCES Books(bookId),
	FOREIGN KEY (authorId) REFERENCES Authors(authorId));

ALTER TABLE Books DROP COLUMN authorId;
INSERT INTO bookAuthors (bookId, authorId) VALUES (1, 1), (1, 3), (2, 4), (3, 5);
ALTER TABLE Books CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

