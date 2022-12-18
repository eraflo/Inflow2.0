CREATE TABLE USERS(
   user_id INT AUTO_INCREMENT,
   email VARCHAR(50) ,
   password VARCHAR(100) ,
   username VARCHAR(20) ,
   theme VARCHAR(20) ,
   font VARCHAR(40) ,
   font_size INT,
   PRIMARY KEY(user_id)
);

CREATE TABLE RANKS(
   rank_id INT AUTO_INCREMENT,
   name VARCHAR(20) ,
   PRIMARY KEY(rank_id)
);

CREATE TABLE ARTICLES(
   article_id INT AUTO_INCREMENT,
   title VARCHAR(30) ,
   content VARCHAR(2000) ,
   date_release DATE,
   description VARCHAR(100) ,
   user_id INT NOT NULL,
   PRIMARY KEY(article_id),
   FOREIGN KEY(user_id) REFERENCES USERS(user_id)
);

CREATE TABLE OPINIONS(
   opinion_id INT AUTO_INCREMENT,
   value_opinion DOUBLE,
   user_id INT NOT NULL,
   article_id INT NOT NULL,
   PRIMARY KEY(opinion_id),
   FOREIGN KEY(user_id) REFERENCES USERS(user_id),
   FOREIGN KEY(article_id) REFERENCES ARTICLES(article_id)
);

CREATE TABLE CATEGORIES(
   category_id INT AUTO_INCREMENT,
   name VARCHAR(30) ,
   PRIMARY KEY(category_id)
);

CREATE TABLE LINKS(
   link_id INT AUTO_INCREMENT,
   url VARCHAR(200) ,
   user_id INT NOT NULL,
   PRIMARY KEY(link_id),
   FOREIGN KEY(user_id) REFERENCES USERS(user_id)
);

CREATE TABLE NOTIFICATIONS(
   notif_id INT AUTO_INCREMENT,
   title VARCHAR(30) ,
   content VARCHAR(200) ,
   expireDate DATETIME,
   PRIMARY KEY(notif_id)
);

CREATE TABLE has(
   user_id INT,
   rank_id INT,
   PRIMARY KEY(user_id, rank_id),
   FOREIGN KEY(user_id) REFERENCES USERS(user_id),
   FOREIGN KEY(rank_id) REFERENCES RANKS(rank_id)
);

CREATE TABLE consult(
   user_id INT,
   article_id INT,
   PRIMARY KEY(user_id, article_id),
   FOREIGN KEY(user_id) REFERENCES USERS(user_id),
   FOREIGN KEY(article_id) REFERENCES ARTICLES(article_id)
);

CREATE TABLE include(
   article_id INT,
   category_id INT,
   PRIMARY KEY(article_id, category_id),
   FOREIGN KEY(article_id) REFERENCES ARTICLES(article_id),
   FOREIGN KEY(category_id) REFERENCES CATEGORIES(category_id)
);

CREATE TABLE receive(
   user_id INT,
   notif_id INT,
   PRIMARY KEY(user_id, notif_id),
   FOREIGN KEY(user_id) REFERENCES USERS(user_id),
   FOREIGN KEY(notif_id) REFERENCES NOTIFICATIONS(notif_id)
);

CREATE TABLE follow(
   user_id INT,
   user_id_1 INT,
   PRIMARY KEY(user_id, user_id_1),
   FOREIGN KEY(user_id) REFERENCES USERS(user_id),
   FOREIGN KEY(user_id_1) REFERENCES USERS(user_id)
);

CREATE TABLE suscribe(
   user_id INT,
   category_id INT,
   PRIMARY KEY(user_id, category_id),
   FOREIGN KEY(user_id) REFERENCES USERS(user_id),
   FOREIGN KEY(category_id) REFERENCES CATEGORIES(category_id)
);

CREATE TABLE mentions(
   user_id INT,
   article_id INT,
   PRIMARY KEY(user_id, article_id),
   FOREIGN KEY(user_id) REFERENCES USERS(user_id),
   FOREIGN KEY(article_id) REFERENCES ARTICLES(article_id)
);
