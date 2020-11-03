DROP TABLE customer;
CREATE TABLE `customer` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `email` varchar(255),
  `name` varchar(255),
  `surname` varchar(255)
);
DROP TABLE author;
CREATE TABLE `author` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255)
);

DROP TABLE book;
CREATE TABLE `book` (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `author_id` int NOT NULL,
    `title` varchar(255),
    `isbn` varchar(255),
    `description` text,
    `price` double,
    `availability` int
);

DROP TABLE invoice;
CREATE TABLE `invoice` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `customer_id` int NOT NULL,
  `date` datetime
);

DROP TABLE item;
CREATE TABLE `item` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `invoice_id` int NOT NULL,
  `book_id` int NOT NULL,
  `price` decimal
);

ALTER TABLE `book` ADD FOREIGN KEY (`author_id`) REFERENCES `author` (`id`);
ALTER TABLE `invoice` ADD FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`);
ALTER TABLE `item` ADD FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`);
ALTER TABLE `item` ADD FOREIGN KEY (`book_id`) REFERENCES `book` (`id`);


INSERT INTO customer VALUES (1, 'foravatenubi@gmail.com', 'Fabio', 'Ventura');

INSERT INTO author VALUES (1, 'Camilleri');
INSERT INTO author VALUES (2, 'Pirandello');

-- CAMILLERI ID 1
INSERT INTO book VALUES (1, 1, 'Riccardino', '9788838940750', 'L''ultima avventura del commissario Montalbano.', 14.25, 100);
INSERT INTO book VALUES (2, 1, 'Gli arancini di Montalbano', '9788838938511', 'Venti racconti si dispiegano l''un dietro l''altro.', 11.25, 251);

-- PIRANDELLO ID 2
INSERT INTO book VALUES (3, 2, 'Il fu Mattia Pascal', '9788804725978', 'Siamo stati messi al mondo senza libretto delle istruzioni.', 9.5, 534);
INSERT INTO book VALUES (4, 2, 'Uno, nessuno e centomila', '9788817014670', 'Una realtà non ci fu data e non c''è.', 8.55, 654);
