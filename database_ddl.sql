CREATE TABLE users (
	user_id INTEGER AUTO_INCREMENT,
	username VARCHAR(25) NOT NULL,
	email VARCHAR(250) NOT NULL,
	password VARCHAR(25) NOT NULL,
	PRIMARY KEY (user_id)
);

CREATE TABLE threads (
	thread_id INTEGER AUTO_INCREMENT,
	poster_id INTEGER NOT NULL,
	title VARCHAR(100) NOT NULL,
	content VARCHAR(3000),
	posted_time VARCHAR(20),
	points INTEGER,
	PRIMARY KEY (thread_id),
	FOREIGN KEY (poster_id) REFERENCES users(user_id)
);

CREATE TABLE thread_replies (
	reply_id INTEGER AUTO_INCREMENT,
	poster_id INTEGER NOT NULL,
	posted_time VARCHAR(20),
	content VARCHAR(3000),
	thread_id INTEGER NOT NULL,
	PRIMARY KEY (reply_id),
	FOREIGN KEY (thread_id) REFERENCES threads(thread_id)
);

CREATE TABLE thread_comments (
	comment_id INTEGER AUTO_INCREMENT,
	poster_id INTEGER NOT NULL,
	posted_time VARCHAR(20),
	content VARCHAR(1000),
	parent_id INTEGER NOT NULL,
	PRIMARY KEY (comment_id),
	FOREIGN KEY (parent_id) REFERENCES thread_replies(reply_id)
);