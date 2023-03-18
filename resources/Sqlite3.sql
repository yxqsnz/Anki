CREATE TABLE players (
  name TEXT PRIMARY KEY,
  lastIP TEXT NOT NULL,
  lastLogin INTEGER,
  loginExpire INTEGER, 
  createdOn INTEGER,
  password TEXT
);