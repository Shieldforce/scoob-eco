-- User root

CREATE USER IF NOT EXISTS 'root'@'%' IDENTIFIED BY 'scoob_pass';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;

CREATE USER IF NOT EXISTS 'root'@'%.%.%.%' IDENTIFIED BY 'scoob_pass';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%.%.%.%' WITH GRANT OPTION;

CREATE USER IF NOT EXISTS 'root'@'0.0.0.0' IDENTIFIED BY 'scoob_pass';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'0.0.0.0' WITH GRANT OPTION;

CREATE USER IF NOT EXISTS 'root'@'localhost' IDENTIFIED BY 'scoob_pass';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION;

-- User criado

CREATE USER IF NOT EXISTS 'scoob_user'@'%' IDENTIFIED BY 'scoob_pass';
GRANT ALL PRIVILEGES ON *.* TO 'scoob_user'@'%' WITH GRANT OPTION;

CREATE USER IF NOT EXISTS 'scoob_user'@'%.%.%.%' IDENTIFIED BY 'scoob_pass';
GRANT ALL PRIVILEGES ON *.* TO 'scoob_user'@'%.%.%.%' WITH GRANT OPTION;

CREATE USER IF NOT EXISTS 'scoob_user'@'0.0.0.0' IDENTIFIED BY 'scoob_pass';
GRANT ALL PRIVILEGES ON *.* TO 'scoob_user'@'0.0.0.0' WITH GRANT OPTION;

CREATE USER IF NOT EXISTS 'scoob_user'@'localhost' IDENTIFIED BY 'scoob_pass';
GRANT ALL PRIVILEGES ON *.* TO 'scoob_user'@'localhost' WITH GRANT OPTION;

FLUSH PRIVILEGES;
