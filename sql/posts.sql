begin;

-- create table Posts (
--   id uuid not null,

--   title varchar(255) not null,
--   body text not null,
--   created_at timestamp not null,
--   modified_at timestamp not null,

--   author uuid not null references authors(id) on delete cascade,

--   primary key (id)
-- );


CREATE TABLE Posts (
  id CHAR(36) NOT NULL,
  title VARCHAR(255) NOT NULL,
  body TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  modified_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  author CHAR(36) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_posts_author FOREIGN KEY (author) REFERENCES Authors(id) ON DELETE CASCADE
);


commit;
