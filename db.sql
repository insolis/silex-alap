CREATE TABLE IF NOT EXISTS `projektneve_session` (
  `sess_id` varchar(255) COLLATE utf8_hungarian_ci NOT NULL,
  `sess_data` text COLLATE utf8_hungarian_ci NOT NULL,
  `sess_time` int(11) NOT NULL,
  PRIMARY KEY (`sess_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_hungarian_ci;
