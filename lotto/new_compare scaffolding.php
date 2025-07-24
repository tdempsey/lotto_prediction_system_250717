SELECT 
        table1.b1, table1.b2, table1.b3, table1.b4, table1.b5
    FROM 
        table1
    INNER JOIN 
        table2 
    ON 
        table1.b1 = table2.b1
        AND table1.b2 = table2.b2
        AND table1.b3 = table2.b3
        AND table1.b4 = table2.b4
        AND table1.b5 = table2.b5
		


SELECT * FROM `temp_cover_1k_scaffolding_135_241009` WHERE `b1` = 18 OR `b2` = 18 OR `b3` = 18 OR `b4` = 18 OR `b5` = 18 OR 