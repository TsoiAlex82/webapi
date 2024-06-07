	CREATE TABLE `weather_data` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `location` char(100) NOT NULL,
 `date_time` datetime NOT NULL,
 `weather_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`weather_json`)),
 PRIMARY KEY (`id`),
 UNIQUE KEY `loc_date` (`location`,`date_time`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci