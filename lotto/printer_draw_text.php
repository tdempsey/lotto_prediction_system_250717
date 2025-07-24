<?php
$handle = printer_open();
printer_start_doc($handle, "Powerball");
printer_start_page($handle);

$font = printer_create_font("Wingdings", 72, 72, 700, false, false, false, 0); #400 normal
printer_select_font($handle, $font);
printer_draw_text($handle, "0x6E", 10, 10);
printer_delete_font($font);

printer_end_page($handle);
printer_end_doc($handle);
printer_close($handle);
?>