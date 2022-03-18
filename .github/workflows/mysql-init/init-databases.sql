CREATE DATABASE IF NOT EXISTS `rodexal_test_db`;
CREATE DATABASE IF NOT EXISTS `rodexal_alexdor_test_db`;

GRANT ALL ON `rodexal_test_db`.* TO 'rodexal_test_db_user'@'%';
GRANT ALL ON `rodexal_alexdor_test_db`.* TO 'rodexal_test_db_user'@'%';
