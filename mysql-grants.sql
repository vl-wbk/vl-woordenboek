-- init.sql

CREATE DATABASE vl_woordenboek;
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%';
GRANT ALL PRIVILEGES ON vl_woordenboek.* TO 'laravel'@'%';

CREATE DATABASE vlaams_woordenboek_testing;
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%';
GRANT ALL PRIVILEGES ON vlaams_woordenboek_testing.* TO 'laravel'@'%';

FLUSH PRIVILEGES;
