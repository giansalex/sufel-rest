CREATE TABLE client
(
  documento   VARCHAR(15)  NOT NULL,
  nombres     VARCHAR(100) NOT NULL,
  password    VARCHAR(100) NOT NULL,
  created     DATETIME DEFAULT CURRENT_TIMESTAMP,
  last_access DATETIME     NULL,
  PRIMARY KEY (documento)
)ENGINE = INNODB;