<?php
$handle = printer_open();
printer_start_doc($handle, "Powerball");
printer_start_page($handle);

$pen = printer_create_pen(PRINTER_PEN_SOLID, 20, "000000");
printer_select_pen($handle, $pen);

$brush = printer_create_brush(PRINTER_BRUSH_SOLID, "000000");
printer_select_brush($handle, $brush);

printer_draw_rectangle($handle, 1, 1, 500, 500);

printer_delete_brush($brush);
printer_delete_pen($pen);

printer_end_page($handle);
printer_end_doc($handle);
printer_close($handle);
?>