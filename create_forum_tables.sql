USE forum_project;

CREATE TABLE IF NOT EXISTS forum_category (
    id_forum_category INT AUTO_INCREMENT PRIMARY KEY,
    forum_category_name VARCHAR(255) NOT NULL,
    forum_category_description TEXT NULL,
    forum_category_color VARCHAR(7) NULL,
    forum_category_created_at DATETIME NOT NULL,
    forum_category_updated_at DATETIME NULL
);

CREATE TABLE IF NOT EXISTS forum_discussion (
    id_forum_discussion INT AUTO_INCREMENT PRIMARY KEY,
    forum_discussion_title VARCHAR(255) NOT NULL,
    forum_discussion_content TEXT NOT NULL,
    forum_discussion_author_name VARCHAR(255) NOT NULL,
    forum_discussion_author_email VARCHAR(255) NULL,
    forum_discussion_is_pinned BOOLEAN DEFAULT FALSE NOT NULL,
    forum_discussion_is_locked BOOLEAN DEFAULT FALSE NOT NULL,
    forum_discussion_views INT DEFAULT 0 NOT NULL,
    forum_discussion_created_at DATETIME NOT NULL,
    forum_discussion_updated_at DATETIME NULL,
    id_forum_category INT NULL,
    FOREIGN KEY (id_forum_category) REFERENCES forum_category(id_forum_category) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS forum_message (
    id_forum_message INT AUTO_INCREMENT PRIMARY KEY,
    forum_message_content TEXT NOT NULL,
    forum_message_author_name VARCHAR(255) NOT NULL,
    forum_message_author_email VARCHAR(255) NULL,
    forum_message_is_author BOOLEAN DEFAULT FALSE NOT NULL,
    forum_message_created_at DATETIME NOT NULL,
    forum_message_updated_at DATETIME NULL,
    id_forum_discussion INT NOT NULL,
    FOREIGN KEY (id_forum_discussion) REFERENCES forum_discussion(id_forum_discussion) ON DELETE CASCADE
);
