#!/usr/bin/env python3

import sys
import os
import time
import datetime
import mysql.connector
from mysql.connector import Error
import math

# Starting over - Georgia Fantasy 5 1K 1-3-5 Cover Analysis
print("Starting over - Georgia Fantasy 5 1K 1-3-5 Cover Analysis")

# Game Configuration
game = 1  # Georgia F5
draw_table_name = "ga_f5_draws"

print("c:\\wamp\\www\\lotto\\<br>")

# No time limit in Python equivalent
# set_time_limit(0) - Python doesn't have this concept

k = 1
debug = True

if debug:
    import logging
    logging.basicConfig(level=logging.DEBUG)
    print("Debug mode enabled")
else:
    logging.basicConfig(level=logging.ERROR)

# Import equivalent modules
try:
    from includes.games_switch import *
    from includes.mysqli_python import get_connection
    from includes.build_rank_table import *
    from includes.count_2_seq import *
    from includes.count_3_seq import *
    from includes.print_column_test_sumeo_no_tables import *
    from includes.dateDiffInDays import *
    from includes.first_draw_unix import *
    from includes.last_draw_unix import *
    from includes.last_draws import *
    from includes_ga_f5.last_draws_ga_f5 import *
    from includes_ga_f5.combin import *
    from includes_ga_f5.split_sumeo_2_no_tables import *
    from includes_ga_f5.split_sumeo_3_no_tables import *
    from includes_ga_f5.split_sumeo_4_no_tables import *
    from includes_ga_f5.split_sumeo_5_no_tables import *
    from includes.unix import *
    
except ImportError as e:
    print(f"Warning: Some modules not found: {e}")

# Database connection
try:
    connection = get_connection()
except (NameError, ImportError):
    print("Warning: get_connection function not found, using direct connection")
    try:
        connection = mysql.connector.connect(
            host='localhost',
            user='root',
            password='wef5esuv',
            database='ga_f5_lotto'
        )
        if connection.is_connected():
            print(f'Success... Connected to MySQL Server')
    except Error as e:
        print(f"Error connecting to MySQL: {e}")
        sys.exit(1)

# Set timezone
os.environ['TZ'] = 'America/New_York'
time.tzset()

def print_draw_summary(sum_val, even, odd):
    """Print draw summary for specific sum, even, odd combination"""
    global draw_table_name, balls, balls_drawn, draw_prefix, col1_select, hml, range_low, range_high, game
    
    print(f"print_draw_summary - {sum_val},{even},{odd}")
    
    cursor = connection.cursor()
    
    try:
        from includes.calculate_draw_summary_sumeo import *
        from includes.print_draw_summary_sumeo import *
    except ImportError:
        print("Warning: draw summary sumeo modules not found")
    
    print("add range & summary<br>")
    
    cursor.close()

def print_sum_grid_sum4_sumeo(combin, sumeo_sum, sumeo_even, sumeo_odd):
    """Print sum grid sum4 for specific sumeo combination"""
    global draw_table_name, balls, balls_drawn, draw_prefix, col1_select, hml, range_low, range_high
    
    cursor = connection.cursor()
    
    try:
        from includes.calculate_sum_count_sum4_sumeo import *
        from includes.print_sum_table_sum4_sumeo import *
    except ImportError:
        print("Warning: sum grid sum4 sumeo modules not found")
    
    cursor.close()

def calc_devsq(draw, average):
    """Calculate deviation squared"""
    average = sum(draw) / 5
    devsq = 0.0
    for x in range(5):
        temp = draw[x] - average
        devsq += temp * temp
    
    return devsq

def pair_sum_count_5(draw_num):
    """Calculate pair sum count for 5 numbers"""
    global debug
    
    cursor = connection.cursor()
    pair_sum = 0
    
    # Pair count combinations
    pairs = [
        (1, 2), (1, 3), (1, 4), (1, 5),
        (2, 3), (2, 4), (2, 5),
        (3, 4), (3, 5),
        (4, 5)
    ]
    
    for i, (pos1, pos2) in enumerate(pairs, 1):
        d1 = draw_num[pos1]
        d2 = draw_num[pos2]
        
        query8 = f"""SELECT num1, num2, count FROM ga_f5_temp_2_5000 
                    WHERE num1 = {d1} AND num2 = {d2}"""
        
        cursor.execute(query8)
        results = cursor.fetchall()
        num_rows = len(results)
        
        pair_sum += num_rows
    
    cursor.close()
    return pair_sum

def generate_html_header():
    """Generate HTML header"""
    html = """<HTML>
<HEAD>
<TITLE>Lotto Cover 1K - 5/42</TITLE>
</HEAD>
<BODY bgcolor="#FFFFFF" text="#000000">
"""
    return html

def main():
    """Main execution function"""
    
    try:
        # Generate HTML output
        print(generate_html_header())
        
        # Get current date
        curr_date = datetime.date.today().strftime('%Y-%m-%d')
        currdate = datetime.date.today().strftime('%y%m%d')
        
        # Configuration
        drop_tables = True  # Set to True as in PHP
        update_level = 2  # dup, rank
        max_num = 42
        
        count = 1
        count_all = 0
        print_flag = 0
        
        # Scaffolding tables
        temp_table1 = f'temp_cover_1k_count_{currdate}'
        temp_table2 = f'temp_cover_1k_scaffolding_135_{currdate}'
        temp_table4 = f'temp_cover_1k_candidates_scaffolding_{currdate}'
        
        cursor = connection.cursor()
        
        print("<p>############################################ scaffolding_drop_tables ####################################################</p>")
        try:
            from includes_ga_f5.scaffolding_drop_tables import *
        except ImportError:
            print("Warning: scaffolding_drop_tables module not found")
        
        print("<p>############################################ build 1000 counts ####################################################</p>")
        try:
            from includes_ga_f5.scaffolding_count import *
        except ImportError:
            print("Warning: scaffolding_count module not found")
        
        print("<p>############################################ Col 1 ####################################################</p>")
        try:
            from includes_ga_f5.scaffolding_col1 import *
        except ImportError:
            print("Warning: scaffolding_col1 module not found")
        
        print("<p>############################################ Col 5 ####################################################</p>")
        try:
            from includes_ga_f5.scaffolding_col5 import *
        except ImportError:
            print("Warning: scaffolding_col5 module not found")
        
        print("<p>############################################ Col 2/3/4 ####################################################</p>")
        try:
            from includes_ga_f5.scaffolding_col234 import *
        except ImportError:
            print("Warning: scaffolding_col234 module not found")
        
        print("<p>############################################ Col 2/Col4 ####################################################</p>")
        try:
            from includes_ga_f5.scaffolding_col2_col4 import *
        except ImportError:
            print("Warning: scaffolding_col2_col4 module not found")
        
        print("<h3>##### todo - add col1 filter #####</h3>")
        
        # Main sumeo processing loop
        query3 = f"SELECT DISTINCT sum,even,odd,k_count FROM {temp_table1} ORDER BY k_count DESC"
        print(f"<p>{query3}</p>")
        
        cursor.execute(query3)
        results3 = cursor.fetchall()
        
        for row3 in results3:
            print(f"<b>{row3[0]}, {row3[1]}, {row3[2]} - {row3[3]}</b><br>")
            
            # Build temp draw table for sumeo
            temp_table3 = f'temp_sumeo_draw_{row3[0]}_{row3[1]}_{row3[2]}'
            temp_table_sumeo_col1 = f'temp2_column_sumeo_{row3[0]}_{row3[1]}_{row3[2]}_1'
            
            if drop_tables:
                # Drop and create table
                query4 = f"DROP TABLE IF EXISTS {temp_table3}"
                cursor.execute(query4)
                print(f"<p>{query4}</p>")
                
                query4 = f"CREATE TABLE {temp_table3} LIKE combo_5_42"
                cursor.execute(query4)
                print(f"<p>{query4}</p>")
                
                # Query draw summary
                if row3[0] < 80:
                    query_dc = f"""SELECT * FROM ga_f5_draw_summary_by_sumeo2 
                                  WHERE sum = {row3[0]} AND even = {row3[1]} AND odd = {row3[2]}
                                  ORDER BY percent_wa DESC"""
                else:
                    query_dc = f"""SELECT * FROM ga_f5_draw_summary_by_sumeo2 
                                  WHERE sum = {row3[0]} AND even = {row3[1]} AND odd = {row3[2]}
                                  AND year1 > 0 AND percent_wa >= 0.1 ORDER BY percent_wa DESC"""
                
                cursor.execute(query_dc)
                results_dc = cursor.fetchall()
                
                if not results_dc:
                    print("no num_rows_dc<br>")
                    for col in range(1, 6):
                        try:
                            print_column_test_sumeo_no_tables(col, row3[0], row3[1], row3[2])
                        except NameError:
                            print(f"Warning: print_column_test_sumeo_no_tables function not found")
                else:
                    for row_dc in results_dc:
                        query5 = f"""SELECT * FROM {temp_table_sumeo_col1} 
                                    WHERE percent_wa > 0.100 
                                    ORDER BY percent_wa DESC"""
                        
                        print(f"<p>{query5}</p>")
                        cursor.execute(query5)
                        results5 = cursor.fetchall()
                        
                        for row5 in results5:
                            query4 = f"""INSERT INTO {temp_table3} 
                                        SELECT * FROM combo_5_42 WHERE sum = {row3[0]} AND even = {row3[1]} AND odd = {row3[2]}
                                        AND seq2 <= 1 AND seq3 = 0 AND mod_tot <= 1 AND mod_x = 0
                                        AND d0 = {row_dc[5]} AND d1 = {row_dc[6]} AND d2 = {row_dc[7]} AND d3 = {row_dc[8]} AND d4 = {row_dc[9]}
                                        AND b1 = {row5[0]}"""
                            
                            cursor.execute(query4)
        
        # Exit early as in PHP
        print("Main sumeo processing complete - exiting early")
        connection.commit()
        return
        
        # The following code would run if we didn't exit early
        # (keeping it for reference but it won't execute)
        
        # Read draw count table for each sumeo
        query3 = f"SELECT DISTINCT * FROM {temp_table1} ORDER BY k_count DESC"
        print(f"<p>{query3}</p>")
        
        cursor.execute(query3)
        results3 = cursor.fetchall()
        
        for row3 in results3:
            print(f"{row3[0]}, {row3[1]}, {row3[2]} - {row3[3]}<br>")
            
            temp_table3 = f'temp_sumeo_draw_{row3[0]}_{row3[1]}_{row3[2]}'
            
            # Filter temp_table3
            query_dc = f"""SELECT * FROM ga_f5_draw_summary_by_sumeo2 
                          WHERE sum = {row3[0]} AND even = {row3[1]} AND odd = {row3[2]} 
                          ORDER BY percent_wa DESC"""
            
            cursor.execute(query_dc)
            results_dc = cursor.fetchall()
            
            for row_dc in results_dc:
                query5 = f"""SELECT * FROM {temp_table3} 
                            WHERE d0 = {row_dc[5]} AND d1 = {row_dc[6]} AND d2 = {row_dc[7]} AND d3 = {row_dc[8]} AND d4 = {row_dc[9]}
                            AND dup1 <= 1 AND dup2 <= 2 AND dup3 <= 3 AND dup4 <= 4
                            AND rank0 <= 0 AND rank1 <= 1 AND rank2 <= 3 AND rank3 <= 3 AND rank4 <= 2 AND rank5 <= 3 AND rank6 <= 2 AND rank7 <= 0
                            ORDER BY y1_sum DESC 
                            LIMIT 10"""
                
                cursor.execute(query5)
                results5 = cursor.fetchall()
                
                for row5e in results5:
                    query7 = f"INSERT INTO {temp_table2} VALUES (0, "
                    for t in range(1, 6):
                        query7 += f"'{row5e[t]}', "
                    for t in range(6, 60):
                        query7 += f"'{row5e[t]}', "
                    query7 += "'1962-08-17')"
                    
                    cursor.execute(query7)
        
        # Build combination 4/42
        col1_num = [0] * 11
        col2_num = [0] * 11
        col3_num = [0] * 11
        col4_num = [0] * 11
        col5_num = [0] * 11
        
        draw_num = [0] * 6  # 0-indexed, but we'll use 1-5
        draw_num[1] = k
        draw_num[2] = k + 1
        draw_num[3] = k + 2
        draw_num[4] = k + 3
        draw_num[5] = k + 4
        
        # Set specific starting values as in PHP
        draw_num[1] = 8
        draw_num[2] = 11
        draw_num[3] = 21
        draw_num[4] = 22
        
        print(f"<p align=\"center\"><b><font face=\"Arial, Helvetica, sans-serif\">Build Combo 4/{max_num}</font></b></p>")
        
        # Main combination building loop
        while draw_num[1] <= 37:
            num_rows = 0
            temp_table = "combo_4_42"
            
            # Initialize counters
            even = 0
            odd = 0
            d501 = 0
            d502 = 0
            d3_array = [0] * 3
            d4_array = [0] * 4
            seq2 = 0
            seq3 = 0
            mod_total = 0
            dup_pass = 1
            mod_x = 0
            
            total_combin = [0] * 7
            num_count = [0] * 7
            rank_count = [0] * 7
            mod = [0] * 7
            dup_count = [0] * 11
            
            draw_array = [draw_num[1], draw_num[2], draw_num[3], draw_num[4]]
            draw_array_0 = [0, draw_num[1], draw_num[2], draw_num[3], draw_num[4]]
            
            sum_val = draw_num[1] + draw_num[2] + draw_num[3] + draw_num[4]
            
            print(f"<h3>{draw_num[1]} - {draw_num[2]} - {draw_num[3]} - {draw_num[4]}</h3>")
            
            # Calculate sequences
            try:
                seq2 = Count2Seq(draw_array)
                seq3 = Count3Seq(draw_array)
            except NameError:
                seq2 = 0
                seq3 = 0
            
            # Calculate even/odd
            for val in draw_array:
                if val % 2 == 0:
                    even += 1
                else:
                    odd += 1
            
            # Calculate modulus
            for x in range(1, 5):
                if 0 < draw_num[x] < 10:
                    y = draw_num[x]
                    mod[y] += 1
                    num_count[0] += 1
                elif 9 < draw_num[x] < 20:
                    y = draw_num[x] - 10
                    mod[y] += 1
                    num_count[1] += 1
                elif 19 < draw_num[x] < 30:
                    y = draw_num[x] - 20
                    mod[y] += 1
                    num_count[2] += 1
                elif 29 < draw_num[x] < 40:
                    y = draw_num[x] - 30
                    mod[y] += 1
                    num_count[3] += 1
                else:
                    y = draw_num[x] - 40
                    mod[y] += 1
                    num_count[4] += 1
            
            mod_x = 0
            for x in range(10):
                if mod[x] > 1:
                    mod_total += mod[x] - 1
                if mod[x] > 2:
                    mod_x += 1
            
            # Check if combination passes filters
            if True:  # All filters passed in PHP
                # Calculate statistics
                avg = sum_val / 4
                median = (draw_num[2] + draw_num[3]) / 2
                quart1 = (draw_num[1] + draw_num[2]) / 2
                quart2 = (draw_num[1] + draw_num[2] + draw_num[3]) / 2
                quart3 = (draw_num[1] + draw_num[2] + draw_num[3] + draw_num[4]) / 2
                avedev = 0
                
                wheel_generated_rows = 0
                wheel_generated_wa = 0.0
                pair_sum = 0
                draw_count = 0
                
                # Check for existing combination
                query7 = f"""SELECT * FROM combo_4_42 
                           WHERE b1 = {draw_num[1]} AND b2 = {draw_num[2]} 
                           AND b3 = {draw_num[3]} AND b4 = {draw_num[4]}
                           ORDER BY id ASC"""
                
                cursor.execute(query7)
                results7 = cursor.fetchall()
                draw_count = len(results7)
                
                if draw_count > 1:
                    # Delete duplicates
                    for i, row7 in enumerate(results7[1:], 1):
                        query_delete = f"DELETE FROM combo_4_42 WHERE id = {row7[0]}"
                        cursor.execute(query_delete)
                else:
                    draw_last = '1962-08-17'
                    hml = int(sum_val / 10) * 10
                    
                    # Insert new combination
                    query = f"""INSERT INTO combo_4_42 
                              (id, b1, b2, b3, b4, sum, hml, even, odd, 
                               d0, d1, d2, d3, d4, rank0, rank1, rank2, rank3, rank4, rank5, rank6, rank7,
                               mod_tot, mod_x, seq2, seq3, comb2, comb3, comb4, comb5,
                               dup1, dup2, dup3, dup4, dup5, dup6, dup7, dup8, dup9, dup10,
                               pair_sum, avg, median, harmean, geomean, quart1, quart2, quart3,
                               stdev, variance, avedev, kurt, skew, devsq, wheel_cnt5000, wheel_percent_wa,
                               draw_last, draw_count, y1_sum, last_updated) 
                              VALUES (0, {draw_num[1]}, {draw_num[2]}, {draw_num[3]}, {draw_num[4]}, 
                                     {sum_val}, {hml}, {even}, {odd}, 
                                     {num_count[0]}, {num_count[1]}, {num_count[2]}, {num_count[3]}, {num_count[4]},
                                     0, 0, 0, 0, 0, 0, 0, 0,
                                     {mod_total}, {mod_x}, {seq2}, {seq3}, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
                                     0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00,
                                     0, 0.00, '1962-08-17', 0, 0.00, '1962-08-17')"""
                    
                    cursor.execute(query)
                    count += 1
            
            # Increment draw numbers
            if draw_num[4] < max_num:
                draw_num[4] += 1
            elif draw_num[3] < max_num - 1:
                draw_num[3] += 1
                draw_num[4] = draw_num[3] + 1
            elif draw_num[2] < max_num - 2:
                draw_num[2] += 1
                draw_num[3] = draw_num[2] + 1
                draw_num[4] = draw_num[3] + 1
            else:
                draw_num[1] += 1
                draw_num[2] = draw_num[1] + 1
                draw_num[3] = draw_num[2] + 1
                draw_num[4] = draw_num[3] + 1
            
            # Print progress
            if print_flag == 50000:
                print(f"{draw_num[1]},{draw_num[2]},{draw_num[3]},{draw_num[4]}<br>")
                print_flag = 0
            
            print_flag += 1
        
        # Display final count
        print("</table>")
        print(f"<h2>Count = {count}</h2>")
        
        # Display example array data
        array = [0, 0, 0, 0, 1, 19, 52, 55, 111, 166, 165, 115, 120, 67, 58, 39, 27, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        
        print("<table border='1'>")
        print("<tr><th>Index</th><th>Value</th></tr>")
        
        for index, value in enumerate(array):
            print(f"<tr><td>{index}</td><td>{value}</td></tr>")
        
        print("</table>")
        
        # Close HTML
        print("</body>")
        print("</html>")
        
    except Exception as e:
        print(f"Error in main execution: {e}")
        import traceback
        traceback.print_exc()
        
        # Ensure HTML is closed on error
        print("</body>")
        print("</html>")
    
    finally:
        # Clean up database connection
        if connection.is_connected():
            connection.close()

if __name__ == "__main__":
    main()