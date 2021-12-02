CREATE TABLE `movies` (
                          `id` int(32) unsigned NOT NULL,
                          `title` varchar(100) NOT NULL,
                          `overview` text NOT NULL,
                          `releaseDate` date NOT NULL,
                          `imagePath` varchar(255) NOT NULL,
                          `rentedTo` varchar(100) DEFAULT '',
                          `owned` tinyint(1) DEFAULT 0,
                          PRIMARY KEY (`id`),
                          KEY `movies_rented_to_IDX` (`rentedTo`) USING BTREE,
                          KEY `movies_release_date_IDX` (`releaseDate`) USING BTREE,
                          KEY `movies_owned_IDX` (`owned`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4