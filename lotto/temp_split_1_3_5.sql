DROP TABLE IF EXISTS `temp_split_count_1_3_5_123_2_3`;
CREATE TABLE `temp_split_count_1_3_5_123_2_3` (
  `id` smallint(6) NOT NULL,
  `b1` tinyint(4) NOT NULL,
  `b3` tinyint(4) NOT NULL,
  `b5` tinyint(4) NOT NULL,
  `count` mediumint(9) NOT NULL,
  `y1_sum` float(4,2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;