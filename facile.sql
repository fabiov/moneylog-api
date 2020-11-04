########################
# DROP EXISTING TABLES #
########################
DROP TABLE IF EXISTS item;
DROP TABLE IF EXISTS invoice;
DROP TABLE IF EXISTS book;
DROP TABLE IF EXISTS customer;
DROP TABLE IF EXISTS author;

#####################
# CREATE NEW TABLES #
#####################
CREATE TABLE `customer` (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `email` varchar(255) NOT NULL,
    `name` varchar(255) NOT NULL,
    `surname` varchar(255) NOT NULL
);
CREATE TABLE `author` (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `name` varchar(255) NOT NULL
);
CREATE TABLE `book` (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `author_id` int NOT NULL,
    `title` varchar(255) NOT NULL,
    `isbn` varchar(255) UNIQUE NOT NULL,
    `description` text NOT NULL,
    `price` double NOT NULL,
    `availability` int NOT NULL
);
CREATE TABLE `invoice` (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `customer_id` int NOT NULL,
    `date` datetime NOT NULL
);
CREATE TABLE `item` (
    `id` int PRIMARY KEY AUTO_INCREMENT,
    `invoice_id` int NOT NULL,
    `book_id` int NOT NULL,
    `price` decimal NOT NULL
);

###################
# ADD CONSTRAINTS #
###################
ALTER TABLE `book` ADD FOREIGN KEY (`author_id`) REFERENCES `author` (`id`);
ALTER TABLE `invoice` ADD FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`);
ALTER TABLE `item` ADD FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`id`);
ALTER TABLE `item` ADD FOREIGN KEY (`book_id`) REFERENCES `book` (`id`);

###############
# INSERT DATA #
###############
-- CUSTOMER
INSERT INTO customer VALUES (1, 'foravatenubi@gmail.com', 'Fabio', 'Ventura');
-- AUTHOR
INSERT INTO author VALUES (1, 'Camilleri');
INSERT INTO author VALUES (2, 'Pirandello');
-- BOOK CAMILLERI ID 1
INSERT INTO book VALUES (1, 1, 'Riccardino', '9788838940750', 'L''ultima avventura del commissario Montalbano.', 14.25, 100);
INSERT INTO book VALUES (2, 1, 'Gli arancini di Montalbano', '9788838938511', 'Venti racconti si dispiegano l''un dietro l''altro.', 11.25, 251);
-- BOOK PIRANDELLO ID 2
INSERT INTO book VALUES (3, 2, 'Il fu Mattia Pascal', '9788804725978', 'Siamo stati messi al mondo senza libretto delle istruzioni.', 9.5, 534);
INSERT INTO book VALUES (4, 2, 'Uno, nessuno e centomila', '9788817014670', 'Una realtà non ci fu data e non c''è.', 8.55, 654);
