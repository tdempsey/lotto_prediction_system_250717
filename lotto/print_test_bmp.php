<?php
$handle = printer_open();

printer_set_option($handle, PRINTER_SCALE, 100);
printer_set_option($handle, PRINTER_TEXT_ALIGN, PRINTER_TA_LEFT);

printer_start_doc($handle, "My Document");
printer_start_page($handle);

$pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
printer_select_pen($handle, $pen);

$brush = printer_create_brush(PRINTER_BRUSH_SOLID, "000000");
printer_select_brush($handle, $brush);

/*
printer_draw_bmp($handle, "black3.bmp", 1, 1, 50, 50);
printer_draw_bmp($handle, "black3.bmp", 50, 50, $x_e2 100);
printer_draw_bmp($handle, "black3.bmp", $x_e2 $x_e2 150, 150);
printer_draw_bmp($handle, "black3.bmp", 150, 150, 200, 200);
printer_draw_bmp($handle, "black3.bmp", 200, 200, 250, 250);
printer_draw_bmp($handle, "black3.bmp", $x_e5 250, 300, 300);
printer_draw_bmp($handle, "black3.bmp", 300, 300, 350, 350);
printer_draw_bmp($handle, "black3.bmp", 350, 350, 400, 400);
*/

#printer_draw_bmp($handle, "black3.bmp", $x_e1 303, 76, 346);

$x_e1 = 35;
$x_e2 = $x_e1 + 65;
$x_e3 = $x_e2 + 65;
$x_e4 = $x_e3 + 65;
$x_e5 = $x_e4 + 65;
$x_e6 = $x_e5 + 65;
$x_e7 = $x_e6 + 65;
$x_e8 = $x_e7 + 65;
$x_e9 = $x_e8 + 65; # void & quickpick

$y_e1 = 304;
$y_e2 = $y_e1 + 65;
$y_e3 = $y_e2 + 65;
$y_e4 = $y_e3 + 65;
$y_e5 = $y_e4 + 65;
$y_e6 = $y_e5 + 65;
$y_e7 = $y_e6 + 65;
$y_e8 = $y_e7 + 65;

############################################################## 
#						Panel E
############################################################## 
# row 1
printer_draw_bmp($handle, "black3.bmp", $x_e1, $y_e1); # E4 
printer_draw_bmp($handle, "black3.bmp", $x_e2, $y_e1); # E11
printer_draw_bmp($handle, "black3.bmp", $x_e3, $y_e1); # E18
printer_draw_bmp($handle, "black3.bmp", $x_e4, $y_e1); # E25
printer_draw_bmp($handle, "black3.bmp", $x_e5, $y_e1); # E32
printer_draw_bmp($handle, "black3.bmp", $x_e6, $y_e1); # E39
printer_draw_bmp($handle, "black3.bmp", $x_e7, $y_e1); # E46
printer_draw_bmp($handle, "black3.bmp", $x_e8, $y_e1); # E53
printer_draw_bmp($handle, "black3.bmp", $x_e9, $y_e1); # VOID
##############################################################
# row 2
printer_draw_bmp($handle, "black3.bmp", $x_e1, $y_e2); # E3
printer_draw_bmp($handle, "black3.bmp", $x_e2, $y_e2); # E10
printer_draw_bmp($handle, "black3.bmp", $x_e3, $y_e2); # E17
printer_draw_bmp($handle, "black3.bmp", $x_e4, $y_e2); # E24
printer_draw_bmp($handle, "black3.bmp", $x_e5, $y_e2); # E31
printer_draw_bmp($handle, "black3.bmp", $x_e6, $y_e2); # E38
printer_draw_bmp($handle, "black3.bmp", $x_e7, $y_e2); # E45
printer_draw_bmp($handle, "black3.bmp", $x_e8, $y_e2); # E52

############################################################## 
# row 3
printer_draw_bmp($handle, "black3.bmp", $x_e1, $y_e3); # E2 
printer_draw_bmp($handle, "black3.bmp", $x_e2, $y_e3); # E9
printer_draw_bmp($handle, "black3.bmp", $x_e3, $y_e3); # E16
printer_draw_bmp($handle, "black3.bmp", $x_e4, $y_e3); # E23
printer_draw_bmp($handle, "black3.bmp", $x_e5, $y_e3); # E30
printer_draw_bmp($handle, "black3.bmp", $x_e6, $y_e3); # E37
printer_draw_bmp($handle, "black3.bmp", $x_e7, $y_e3); # E44
printer_draw_bmp($handle, "black3.bmp", $x_e8, $y_e3); # E51

printer_draw_bmp($handle, "black3.bmp", $x_e9, $y_e3); # QUICK PICK

##############################################################
# row 4
printer_draw_bmp($handle, "black3.bmp", $x_e1, $y_e4); # E1
printer_draw_bmp($handle, "black3.bmp", $x_e2, $y_e4); # E8
printer_draw_bmp($handle, "black3.bmp", $x_e3, $y_e4); # E15
printer_draw_bmp($handle, "black3.bmp", $x_e4, $y_e4); # E22
printer_draw_bmp($handle, "black3.bmp", $x_e5, $y_e4); # E29
printer_draw_bmp($handle, "black3.bmp", $x_e6, $y_e4); # E36
printer_draw_bmp($handle, "black3.bmp", $x_e7, $y_e4); # E43
printer_draw_bmp($handle, "black3.bmp", $x_e8, $y_e4); # E50

##############################################################
#row 5
#printer_draw_bmp($handle, "black3.bmp", $x_e1 495, 78, 531)); # 
printer_draw_bmp($handle, "black3.bmp", $x_e2, $y_e5); # E7
printer_draw_bmp($handle, "black3.bmp", $x_e3, $y_e5); # E14
printer_draw_bmp($handle, "black3.bmp", $x_e4, $y_e5); # E21
printer_draw_bmp($handle, "black3.bmp", $x_e5, $y_e5); # E28
printer_draw_bmp($handle, "black3.bmp", $x_e6, $y_e5); # E35
printer_draw_bmp($handle, "black3.bmp", $x_e7, $y_e5); # E42
printer_draw_bmp($handle, "black3.bmp", $x_e8, $y_e5); # E49

##############################################################
# row 6
#printer_draw_bmp($handle, "black3.bmp", $x_e1 495, 78, 531)); # 
printer_draw_bmp($handle, "black3.bmp", $x_e2, $y_e6); # E6
printer_draw_bmp($handle, "black3.bmp", $x_e3, $y_e6); # E13
printer_draw_bmp($handle, "black3.bmp", $x_e4, $y_e6); # E20
printer_draw_bmp($handle, "black3.bmp", $x_e5, $y_e6); # E27
printer_draw_bmp($handle, "black3.bmp", $x_e6, $y_e6); # E34
printer_draw_bmp($handle, "black3.bmp", $x_e7, $y_e6); # E41
printer_draw_bmp($handle, "black3.bmp", $x_e8, $y_e6); # E48

##############################################################
#row 7
#printer_draw_bmp($handle, "black3.bmp", $x_e1 495, 78, 531)); # 
printer_draw_bmp($handle, "black3.bmp", $x_e2, $y_e7); # E5
printer_draw_bmp($handle, "black3.bmp", $x_e3, $y_e7); # E12
printer_draw_bmp($handle, "black3.bmp", $x_e4, $y_e7); # E19
printer_draw_bmp($handle, "black3.bmp", $x_e5, $y_e7); # E26
printer_draw_bmp($handle, "black3.bmp", $x_e6, $y_e7); # E33
printer_draw_bmp($handle, "black3.bmp", $x_e7, $y_e7); # E40
printer_draw_bmp($handle, "black3.bmp", $x_e8, $y_e7); # E47

############################################################## 
#						END Panel E
##############################################################
/*
$x_j10 = 620;
$x_j11 = $x_j10 + 65;
$x_j12 = $x_j11 + 65;
$x_j13 = $x_j12 + 65;
$x_j14 = $x_j13 + 65;
$x_j15 = $x_j14 + 65;
$x_j16 = $x_j15 + 65;
$x_j17 = $x_j16 + 65;
$x_j18 = $x_j17 + 65; # void & quickpick

$y_j1 = 304;
$y_j2 = $y_j1 + 65;
$y_j3 = $y_j2 + 65;
$y_j4 = $y_j3 + 65;
$y_j5 = $y_j4 + 65;
$y_j6 = $y_j5 + 65;
$y_j7 = $y_j6 + 65;
$y_j8 = $y_j7 + 65;

############################################################## 
#						Panel J
############################################################## 
# row 1
printer_draw_bmp($handle, "black3.bmp", $x_j10, $y_j1); # E4 
printer_draw_bmp($handle, "black3.bmp", $x_j11, $y_j1); # E11
printer_draw_bmp($handle, "black3.bmp", $x_j12, $y_j1); # E18
printer_draw_bmp($handle, "black3.bmp", $x_j13, $y_j1); # E25
printer_draw_bmp($handle, "black3.bmp", $x_j14, $y_j1); # E32
printer_draw_bmp($handle, "black3.bmp", $x_j15, $y_j1); # E39
printer_draw_bmp($handle, "black3.bmp", $x_j16, $y_j1); # E46
printer_draw_bmp($handle, "black3.bmp", $x_j17, $y_j1); # E53
printer_draw_bmp($handle, "black3.bmp", $x_j18, $y_j1); # VOID
##############################################################
# row 2
printer_draw_bmp($handle, "black3.bmp", $x_j10, $y_j2); # E3
printer_draw_bmp($handle, "black3.bmp", $x_j11, $y_j2); # E10
printer_draw_bmp($handle, "black3.bmp", $x_j12, $y_j2); # E17
printer_draw_bmp($handle, "black3.bmp", $x_j13, $y_j2); # E24
printer_draw_bmp($handle, "black3.bmp", $x_j14, $y_j2); # E31
printer_draw_bmp($handle, "black3.bmp", $x_j15, $y_j2); # E38
printer_draw_bmp($handle, "black3.bmp", $x_j16, $y_j2); # E45
printer_draw_bmp($handle, "black3.bmp", $x_j17, $y_j2); # E52

############################################################## 
# row 3
printer_draw_bmp($handle, "black3.bmp", $x_j10, $y_j3); # E2 
printer_draw_bmp($handle, "black3.bmp", $x_j11, $y_j3); # E9
printer_draw_bmp($handle, "black3.bmp", $x_j12, $y_j3); # E16
printer_draw_bmp($handle, "black3.bmp", $x_j13, $y_j3); # E23
printer_draw_bmp($handle, "black3.bmp", $x_j14, $y_j3); # E30
printer_draw_bmp($handle, "black3.bmp", $x_j15, $y_j3); # E37
printer_draw_bmp($handle, "black3.bmp", $x_j16, $y_j3); # E44
printer_draw_bmp($handle, "black3.bmp", $x_j17, $y_j3); # E51
printer_draw_bmp($handle, "black3.bmp", $x_j18, $y_j3); # QUICK PICK

##############################################################
# row 4
printer_draw_bmp($handle, "black3.bmp", $x_j10, $y_j4); # E1
printer_draw_bmp($handle, "black3.bmp", $x_j11, $y_j4); # E8
printer_draw_bmp($handle, "black3.bmp", $x_j12, $y_j4); # E15
printer_draw_bmp($handle, "black3.bmp", $x_j13, $y_j4); # E22
printer_draw_bmp($handle, "black3.bmp", $x_j14, $y_j4); # E29
printer_draw_bmp($handle, "black3.bmp", $x_j15, $y_j4); # E36
printer_draw_bmp($handle, "black3.bmp", $x_j16, $y_j4); # E43
printer_draw_bmp($handle, "black3.bmp", $x_j17, $y_j4); # E50

##############################################################
#row 5
#printer_draw_bmp($handle, "black3.bmp", $x_j10 495, 78, 531)); # 
printer_draw_bmp($handle, "black3.bmp", $x_j11, $y_j5); # E7
printer_draw_bmp($handle, "black3.bmp", $x_j12, $y_j5); # E14
printer_draw_bmp($handle, "black3.bmp", $x_j13, $y_j5); # E21
printer_draw_bmp($handle, "black3.bmp", $x_j14, $y_j5); # E28
printer_draw_bmp($handle, "black3.bmp", $x_j15, $y_j5); # E35
printer_draw_bmp($handle, "black3.bmp", $x_j16, $y_j5); # E42
printer_draw_bmp($handle, "black3.bmp", $x_j17, $y_j5); # E49

##############################################################
# row 6
#printer_draw_bmp($handle, "black3.bmp", $x_j10 495, 78, 531)); # 
printer_draw_bmp($handle, "black3.bmp", $x_j11, $y_j6); # E6
printer_draw_bmp($handle, "black3.bmp", $x_j12, $y_j6); # E13
printer_draw_bmp($handle, "black3.bmp", $x_j13, $y_j6); # E20
printer_draw_bmp($handle, "black3.bmp", $x_j14, $y_j6); # E27
printer_draw_bmp($handle, "black3.bmp", $x_j15, $y_j6); # E34
printer_draw_bmp($handle, "black3.bmp", $x_j16, $y_j6); # E41
printer_draw_bmp($handle, "black3.bmp", $x_j17, $y_j6); # E48

##############################################################
#row 7
#printer_draw_bmp($handle, "black3.bmp", $x_j10 495, 78, 531)); # 
printer_draw_bmp($handle, "black3.bmp", $x_j11, $y_j7); # E5
printer_draw_bmp($handle, "black3.bmp", $x_j12, $y_j7); # E12
printer_draw_bmp($handle, "black3.bmp", $x_j13, $y_j7); # E19
printer_draw_bmp($handle, "black3.bmp", $x_j14, $y_j7); # E26
printer_draw_bmp($handle, "black3.bmp", $x_j15, $y_j7); # E33
printer_draw_bmp($handle, "black3.bmp", $x_j16, $y_j7); # E40
printer_draw_bmp($handle, "black3.bmp", $x_j17, $y_j7); # E47

############################################################## 
#						END Panel J
##############################################################
*/
$x_e1 = 35;
$x_e2 = $x_e1 + 65;
$x_e3 = $x_e2 + 65;
$x_e4 = $x_e3 + 65;
$x_e5 = $x_e4 + 65;
$x_e6 = $x_e5 + 65;
$x_e7 = $x_e6 + 65;
$x_e8 = $x_e7 + 65;
$x_e9 = $x_e8 + 65; # void & quickpick

$y_d1 = 755;
$y_d2 = $y_d1 + 65;
$y_d3 = $y_d2 + 65;
$y_d4 = $y_d3 + 65;
$y_d5 = $y_d4 + 65;
$y_d6 = $y_d5 + 65;
$y_d7 = $y_d6 + 65;
$y_d8 = $y_d7 + 65;

############################################################## 
#						Panel D
############################################################## 
# row 1
printer_draw_bmp($handle, "black3.bmp", $x_e1, $y_d1); # E4 
printer_draw_bmp($handle, "black3.bmp", $x_e2, $y_d1); # E11
printer_draw_bmp($handle, "black3.bmp", $x_e3, $y_d1); # E18
printer_draw_bmp($handle, "black3.bmp", $x_e4, $y_d1); # E25
printer_draw_bmp($handle, "black3.bmp", $x_e5, $y_d1); # E32
printer_draw_bmp($handle, "black3.bmp", $x_e6, $y_d1); # E39
printer_draw_bmp($handle, "black3.bmp", $x_e7, $y_d1); # E46
printer_draw_bmp($handle, "black3.bmp", $x_e8, $y_d1); # E53
printer_draw_bmp($handle, "black3.bmp", $x_e9, $y_d1); # VOID
##############################################################
# row 2
printer_draw_bmp($handle, "black3.bmp", $x_e1, $y_d2); # E3
printer_draw_bmp($handle, "black3.bmp", $x_e2, $y_d2); # E10
printer_draw_bmp($handle, "black3.bmp", $x_e3, $y_d2); # E17
printer_draw_bmp($handle, "black3.bmp", $x_e4, $y_d2); # E24
printer_draw_bmp($handle, "black3.bmp", $x_e5, $y_d2); # E31
printer_draw_bmp($handle, "black3.bmp", $x_e6, $y_d2); # E38
printer_draw_bmp($handle, "black3.bmp", $x_e7, $y_d2); # E45
printer_draw_bmp($handle, "black3.bmp", $x_e8, $y_d2); # E52

############################################################## 
# row 3
printer_draw_bmp($handle, "black3.bmp", $x_e1, $y_d3); # E2 
printer_draw_bmp($handle, "black3.bmp", $x_e2, $y_d3); # E9
printer_draw_bmp($handle, "black3.bmp", $x_e3, $y_d3); # E16
printer_draw_bmp($handle, "black3.bmp", $x_e4, $y_d3); # E23
printer_draw_bmp($handle, "black3.bmp", $x_e5, $y_d3); # E30
printer_draw_bmp($handle, "black3.bmp", $x_e6, $y_d3); # E37
printer_draw_bmp($handle, "black3.bmp", $x_e7, $y_d3); # E44
printer_draw_bmp($handle, "black3.bmp", $x_e8, $y_d3); # E51

printer_draw_bmp($handle, "black3.bmp", $x_e9, $y_d3); # QUICK PICK

##############################################################
# row 4
printer_draw_bmp($handle, "black3.bmp", $x_e1, $y_d4); # E1
printer_draw_bmp($handle, "black3.bmp", $x_e2, $y_d4); # E8
printer_draw_bmp($handle, "black3.bmp", $x_e3, $y_d4); # E15
printer_draw_bmp($handle, "black3.bmp", $x_e4, $y_d4); # E22
printer_draw_bmp($handle, "black3.bmp", $x_e5, $y_d4); # E29
printer_draw_bmp($handle, "black3.bmp", $x_e6, $y_d4); # E36
printer_draw_bmp($handle, "black3.bmp", $x_e7, $y_d4); # E43
printer_draw_bmp($handle, "black3.bmp", $x_e8, $y_d4); # E50

##############################################################
#row 5
#printer_draw_bmp($handle, "black3.bmp", $x_e1 495, 78, 531)); # 
printer_draw_bmp($handle, "black3.bmp", $x_e2, $y_d5); # E7
printer_draw_bmp($handle, "black3.bmp", $x_e3, $y_d5); # E14
printer_draw_bmp($handle, "black3.bmp", $x_e4, $y_d5); # E21
printer_draw_bmp($handle, "black3.bmp", $x_e5, $y_d5); # E28
printer_draw_bmp($handle, "black3.bmp", $x_e6, $y_d5); # E35
printer_draw_bmp($handle, "black3.bmp", $x_e7, $y_d5); # E42
printer_draw_bmp($handle, "black3.bmp", $x_e8, $y_d5); # E49

##############################################################
# row 6
#printer_draw_bmp($handle, "black3.bmp", $x_e1 495, 78, 531)); # 
printer_draw_bmp($handle, "black3.bmp", $x_e2, $y_d6); # E6
printer_draw_bmp($handle, "black3.bmp", $x_e3, $y_d6); # E13
printer_draw_bmp($handle, "black3.bmp", $x_e4, $y_d6); # E20
printer_draw_bmp($handle, "black3.bmp", $x_e5, $y_d6); # E27
printer_draw_bmp($handle, "black3.bmp", $x_e6, $y_d6); # E34
printer_draw_bmp($handle, "black3.bmp", $x_e7, $y_d6); # E41
printer_draw_bmp($handle, "black3.bmp", $x_e8, $y_d6); # E48

##############################################################
#row 7
#printer_draw_bmp($handle, "black3.bmp", $x_e1 495, 78, 531)); # 
printer_draw_bmp($handle, "black3.bmp", $x_e2, $y_d7); # E5
printer_draw_bmp($handle, "black3.bmp", $x_e3, $y_d7); # E12
printer_draw_bmp($handle, "black3.bmp", $x_e4, $y_d7); # E19
printer_draw_bmp($handle, "black3.bmp", $x_e5, $y_d7); # E26
printer_draw_bmp($handle, "black3.bmp", $x_e6, $y_d7); # E33
printer_draw_bmp($handle, "black3.bmp", $x_e7, $y_d7); # E40
printer_draw_bmp($handle, "black3.bmp", $x_e8, $y_d7); # E47

############################################################## 
#						END Panel D
##############################################################

$x_e1 = 35;
$x_e2 = $x_e1 + 65;
$x_e3 = $x_e2 + 65;
$x_e4 = $x_e3 + 65;
$x_e5 = $x_e4 + 65;
$x_e6 = $x_e5 + 65;
$x_e7 = $x_e6 + 65;
$x_e8 = $x_e7 + 65;
$x_e9 = $x_e8 + 65; # void & quickpick

$y_c1 = 1210;
$y_d2 = $y_d1 + 65;
$y_d3 = $y_d2 + 65;
$y_d4 = $y_d3 + 65;
$y_d5 = $y_d4 + 65;
$y_d6 = $y_d5 + 65;
$y_d7 = $y_d6 + 65;
$y_d8 = $y_d7 + 65;

$y_b1 = $y_c1 + 450;
$y_a1 = $y_b1 + 450;

############################################################## 
#						Panel C
############################################################## 
# row 1
printer_draw_bmp($handle, "black3.bmp", $x_e1, $y_c1); # E4 

############################################################## 
#						Panel B
############################################################## 
# row 1
printer_draw_bmp($handle, "black3.bmp", $x_e1, $y_b1); # E4 

############################################################## 
#						Panel A
############################################################## 
# row 1
printer_draw_bmp($handle, "black3.bmp", $x_e1, $y_a1); # E4 


printer_delete_brush($brush);
printer_delete_pen($pen);

printer_end_page($handle);
printer_end_doc($handle);
printer_close($handle);
?> 
