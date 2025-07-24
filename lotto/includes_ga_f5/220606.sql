SELECT * FROM `temp_column_sumeo_col_3_summary_220606` WHERE `sum` = 116 AND `even` = 3 AND `odd` = 2 AND `col1` = 1 AND `col2` = 3 AND `col3` = 5 AND `num1` = 2 ORDER BY `y1_sum` DESC, `count` DESC

SELECT DISTINCT `sum`,`even`,`odd`,`num1` FROM `temp_column_sumeo_col_3_summary_220606` WHERE `col1`= 1 AND `col2`= 3 AND `col3`= 5 AND `num1` <= 10 ORDER BY `y1_sum` DESC, `count` DESC