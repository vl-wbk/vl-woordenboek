-- init.sql

CREATE DATABASE vl_woordenboek;
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%';
GRANT ALL PRIVILEGES ON vl_woordenboek.* TO 'laravel'@'%';

FLUSH PRIVILEGES;
