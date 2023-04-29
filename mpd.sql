CREATE TABLE USERS(
   user_id INT AUTO_INCREMENT,
   email VARCHAR(50) ,
   password VARCHAR(100) ,
   username VARCHAR(20) ,
   theme VARCHAR(20) ,
   pfp_path VARCHAR(200) ,
   PRIMARY KEY(user_id)
);

CREATE TABLE RANKS(
   rank_id INT AUTO_INCREMENT,
   name VARCHAR(20) ,
   PRIMARY KEY(rank_id)
);

CREATE TABLE CATEGORIES(
   category_id INT AUTO_INCREMENT,
   name VARCHAR(30) ,
   img_path VARCHAR(255) ,
   category_id_1 INT NOT NULL,
   PRIMARY KEY(category_id),
   FOREIGN KEY(category_id_1) REFERENCES CATEGORIES(category_id)
);

CREATE TABLE PREFERENCES(
   notif_id INT AUTO_INCREMENT,
   name VARCHAR(30) ,
   setting_value VARCHAR(200) ,
   PRIMARY KEY(notif_id)
);

CREATE TABLE TAGS(
   id INT AUTO_INCREMENT,
   name VARCHAR(30) ,
   PRIMARY KEY(id)
);

CREATE TABLE SOCIALS(
   id INT AUTO_INCREMENT,
   network VARCHAR(20) ,
   url VARCHAR(255) ,
   user_id INT NOT NULL,
   PRIMARY KEY(id),
   FOREIGN KEY(user_id) REFERENCES USERS(user_id)
);

CREATE TABLE COMMENTS(
   article_id INT AUTO_INCREMENT,
   content VARCHAR(2000) ,
   posted_on DATE,
   PRIMARY KEY(article_id)
);

CREATE TABLE ARTICLES(
   article_id INT AUTO_INCREMENT,
   title VARCHAR(30) ,
   content VARCHAR(2000) ,
   release_date DATE,
   description VARCHAR(100) ,
   user_id INT NOT NULL,
   article_id_1 INT NOT NULL,
   PRIMARY KEY(article_id),
   FOREIGN KEY(user_id) REFERENCES USERS(user_id),
   FOREIGN KEY(article_id_1) REFERENCES COMMENTS(article_id)
);

CREATE TABLE OPINIONS(
   opinion_id INT AUTO_INCREMENT,
   opinion_value DOUBLE,
   user_id INT NOT NULL,
   article_id INT NOT NULL,
   article_id_1 INT NOT NULL,
   PRIMARY KEY(opinion_id),
   FOREIGN KEY(user_id) REFERENCES USERS(user_id),
   FOREIGN KEY(article_id) REFERENCES ARTICLES(article_id),
   FOREIGN KEY(article_id_1) REFERENCES COMMENTS(article_id)
);

CREATE TABLE has(
   user_id INT,
   rank_id INT,
   PRIMARY KEY(user_id, rank_id),
   FOREIGN KEY(user_id) REFERENCES USERS(user_id),
   FOREIGN KEY(rank_id) REFERENCES RANKS(rank_id)
);

CREATE TABLE consults(
   user_id INT,
   article_id INT,
   PRIMARY KEY(user_id, article_id),
   FOREIGN KEY(user_id) REFERENCES USERS(user_id),
   FOREIGN KEY(article_id) REFERENCES ARTICLES(article_id)
);

CREATE TABLE includes(
   article_id INT,
   category_id INT,
   PRIMARY KEY(article_id, category_id),
   FOREIGN KEY(article_id) REFERENCES ARTICLES(article_id),
   FOREIGN KEY(category_id) REFERENCES CATEGORIES(category_id)
);

CREATE TABLE set_(
   user_id INT,
   notif_id INT,
   PRIMARY KEY(user_id, notif_id),
   FOREIGN KEY(user_id) REFERENCES USERS(user_id),
   FOREIGN KEY(notif_id) REFERENCES PREFERENCES(notif_id)
);

CREATE TABLE follows(
   user_id INT,
   user_id_1 INT,
   PRIMARY KEY(user_id, user_id_1),
   FOREIGN KEY(user_id) REFERENCES USERS(user_id),
   FOREIGN KEY(user_id_1) REFERENCES USERS(user_id)
);

CREATE TABLE suscribed(
   user_id INT,
   category_id INT,
   PRIMARY KEY(user_id, category_id),
   FOREIGN KEY(user_id) REFERENCES USERS(user_id),
   FOREIGN KEY(category_id) REFERENCES CATEGORIES(category_id)
);

CREATE TABLE mentioned_in(
   user_id INT,
   article_id INT,
   PRIMARY KEY(user_id, article_id),
   FOREIGN KEY(user_id) REFERENCES USERS(user_id),
   FOREIGN KEY(article_id) REFERENCES ARTICLES(article_id)
);

CREATE TABLE concerns(
   article_id INT,
   id INT,
   PRIMARY KEY(article_id, id),
   FOREIGN KEY(article_id) REFERENCES ARTICLES(article_id),
   FOREIGN KEY(id) REFERENCES TAGS(id)
);
