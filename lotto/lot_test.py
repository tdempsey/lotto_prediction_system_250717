#!/usr/bin/env python3

import sys
import os
import time
import datetime
import mysql.connector
from mysql.connector import Error

# Game Configuration
game = 1  # Georgia Fantasy 5
# game = 2  # Mega Millions
# game = 3  # Georgia 5
# game = 4  # Jumbo
# game = 5  # Florida Fantasy 5
# game = 6  # Florida Lotto
# game = 7  # Powerball

# No time limit in Python equivalent
# set_time_limit(0) - Python doesn't have this concept

debug = False

if debug:
    import logging
    logging.basicConfig(level=logging.DEBUG)
    print("Debug mode enabled")

hml = 0
# hml = 1    # high
# hml = 2    # medium
# hml = 3    # low
# hml = 4    # min
# hml = 5    # max
# hml = 110

col1_select = 0

# Import equivalent modules
try:
    from includes.games_switch import *
    from includes.mysqli_python import get_connection
    from includes.table_exist import *
    from includes.last_draws import *
    from includes.first_draw_unix import *
    from includes.last_draw_unix import *
    from includes.test_draw_table import *
    from includes.test_filter_a_table import *
    from includes.test_filter_b_table import *
    from includes.test_filter_c_table import *
    from includes.test_filter_d_table import *
    from includes.test_wheel_table import *
    from includes.calculate_draw import *
    from includes.table_draw_count import *
    from includes.x10 import *
    from includes.count_2_seq import *
    from includes.count_3_seq import *
    from includes.count_4_seq import *
    from includes.count_5_seq import *
    from includes.count_6_seq import *
    from includes.count_7_seq import *
    from includes.count_8_seq import *
    from includes.count_mod import *
    from includes.draw_count_total import *
    from includes.dateDiffInDays import *
    from includes.dup_table_update import *
    from includes.unix import *
    
    # Game-specific wheel functions
    if game == 1:
        from includes_ga_f5.ga_f5_print_wheel_sum_table_eo2 import *
        from includes_ga_f5.ga_f5_print_wheel_sum_table_eo3 import *
        from includes_ga_f5.ga_f5_print_wheel_sum_table_eo4 import *
        from includes_ga_f5.ga_f5_print_wheel_sum_table_eo5 import *
        from includes_ga_f5.ga_f5_print_wheel_sum_table_eo6 import *
        from includes_ga_f5.ga_f5_print_wheel_sum_table_eo7 import *
    elif game == 2:
        from includes_mm.mm_print_wheel_sum_table_eo4 import *
    elif game == 7:
        from includes_pb.pb_print_wheel_sum_table_eo4 import *
        from includes_pb.pb_print_wheel_sum_table_eo import *
        
except ImportError as e:
    print(f"Warning: Some modules not found: {e}")

# Database connection
try:
    connection = get_connection()
except NameError:
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

try:
    from includes.hml_switch import *
except ImportError:
    print("Warning: hml_switch module not found")

debug = False

def print_wheel_report_col(sum_val, col1):
    """Print wheel report for specific column"""
    global debug, draw_table_name, balls, balls_drawn, draw_prefix, game
    
    cursor = connection.cursor()
    
    today = int(time.mktime(datetime.date.today().timetuple()))
    prev_draw = '1962-08-17'
    last_draw = '1962-08-17'
    
    # Query all records for the column
    query_all = f"SELECT * FROM {draw_table_name} WHERE b1 = {col1}"
    print(f"{query_all}<p>")
    
    cursor.execute(query_all)
    results_all = cursor.fetchall()
    num_rows_all = len(results_all)
    
    curr_date = datetime.date.today().strftime("%Y-%m-%d")
    
    # Start table
    print(f"<h3>Wheel Report - {sum_val} - col1 {col1}</h3>")
    print("<TABLE BORDER=\"1\">")
    
    # Create header row
    print("<TR>")
    headers = ["Sum", "Even", "Odd", "d2_1", "d2_2", "Last", "Week1", "Week2", 
               "Month1", "Month3", "Month6", "Year1", "Year2", "Year3", "Year4", 
               "Year5", "Year6", "Year7", "Year8", "Year9", "Year10", 
               str(num_rows_all), "30", "365", str(num_rows_all), "wa"]
    
    for header in headers:
        print(f'<TD BGCOLOR="#CCCCCC" align="center">{header}</TD>')
    print("</TR>")
    
    # Query eo50 table
    query1 = f"SELECT * FROM {draw_prefix}eo50"
    print(f"{query1}<p>")
    
    cursor.execute(query1)
    results_1 = cursor.fetchall()
    
    for row6 in results_1:
        if game == 4 or game == 5:
            query2 = f"""SELECT * FROM combo_{balls_drawn}_{balls} 
                        WHERE sum = {sum_val} 
                        AND even = {row6[1]}  
                        AND odd = {row6[2]}
                        AND d2_1 = {row6[3]}
                        AND d2_2 = {row6[4]}"""
            
            cursor.execute(query2)
            results7 = cursor.fetchall()
            num_rows = len(results7)
        else:
            num_rows = 0
            
            for x in range(1, 10):
                query2 = f"""SELECT * FROM combo_{balls_drawn}_{balls}_0{x} 
                            WHERE sum = {sum_val} 
                            AND even = {row6[1]}  
                            AND odd = {row6[2]}
                            AND d2_1 = {row6[3]}
                            AND d2_2 = {row6[4]}"""
                
                cursor.execute(query2)
                results7 = cursor.fetchall()
                num_rows += len(results7)
        
        print(f"<b>num_rows = {num_rows}</b><p>")
        
        if num_rows:
            print("<TR>")
            print(f'<TD align="center">{sum_val}</TD>')
            print(f'<TD align="center">{row6[1]}</TD>')  # even
            print(f'<TD align="center">{row6[2]}</TD>')  # odd
            print(f'<TD align="center">{row6[3]}</TD>')  # d2_1
            print(f'<TD align="center">{row6[4]}</TD>')  # d2_2
            
            # Calculate wheel totals
            try:
                wheel_tot_array = calculate_wheel_count(col1, sum_val, row6)
            except NameError:
                wheel_tot_array = [0] * 19  # Default array if function not available
            
            for y in range(17):
                print(f'<TD align="center">{wheel_tot_array[y]}</TD>')
            
            # Calculate percentages
            num_rows_temp_30 = round((wheel_tot_array[3]/30)*100, 1) if wheel_tot_array[3] else 0
            num_rows_temp_365 = round((wheel_tot_array[6]/365)*100, 1) if wheel_tot_array[6] else 0
            num_rows_temp_5000 = round((wheel_tot_array[15]/num_rows_all)*100, 1) if wheel_tot_array[15] and num_rows_all else 0
            
            # Calculate weighted average
            weighted_average = (
                (wheel_tot_array[0]/10*100*0.05) +
                (wheel_tot_array[1]/30*100*0.05) +
                (wheel_tot_array[2]/100*100*0.05) +
                (wheel_tot_array[3]/365*100*0.05) +
                (wheel_tot_array[4]/500*100*0.05) +
                (wheel_tot_array[5]/1000*100*0.05) +
                (wheel_tot_array[6]/1000*100*0.05) +
                (wheel_tot_array[7]/1000*100*0.05) +
                (wheel_tot_array[8]/1000*100*0.05) +
                (wheel_tot_array[9]/1000*100*0.05) +
                (wheel_tot_array[10]/1000*100*0.05) +
                (wheel_tot_array[11]/1000*100*0.05) +
                (wheel_tot_array[12]/1000*100*0.05) +
                (wheel_tot_array[13]/1000*100*0.05) +
                (wheel_tot_array[14]/1000*100*0.05) +
                (wheel_tot_array[15]/num_rows_all*100*0.05 if num_rows_all else 0)
            )
            
            num_rows_temp_wa = round(weighted_average, 1)
            
            print(f'<TD align="center">{num_rows_temp_30}</TD>')
            print(f'<TD align="center">{num_rows_temp_365}</TD>')
            print(f'<TD align="center">{num_rows_temp_5000}</TD>')
            print(f'<TD align="center">{num_rows_temp_wa}</TD>')
            print("</TR>")
    
    print("</TABLE>")
    cursor.close()

def test_nums(limit):
    """Test numbers function"""
    try:
        # This function would be implemented in the test_nums module
        print(f"Testing numbers with limit: {limit}")
        # Implementation would go here
    except Exception as e:
        print(f"Error in test_nums: {e}")

def print_column_test(column):
    """Print column test"""
    try:
        # This function would be implemented in the column test module
        print(f"Testing column: {column}")
        # Implementation would go here
    except Exception as e:
        print(f"Error in print_column_test: {e}")

def print_dup_table(limit, mode):
    """Print duplicate table"""
    try:
        # This function would be implemented in the dup_table module
        print(f"Print dup table with limit: {limit}, mode: {mode}")
        # Implementation would go here
    except Exception as e:
        print(f"Error in print_dup_table: {e}")

def generate_html_header(game_name):
    """Generate HTML header"""
    html = f"""<HTML>
<HEAD>
<TITLE>{game_name} Tests</TITLE>
</HEAD>
<BODY>
<H1>{game_name} Tests</H1>
"""
    return html

def main():
    """Main execution function"""
    
    try:
        # Get game name from configuration
        game_name = "Georgia Fantasy 5"  # This should come from games_switch
        
        # Generate HTML output
        print(generate_html_header(game_name))
        
        # Get current date
        curr_date = datetime.datetime.now().strftime("%Y%m%d")
        curr_date_dash = datetime.datetime.now().strftime("%Y-%m-%d")
        
        # Flush output
        sys.stdout.flush()
        
        # Main test functions
        test_nums(30)
        
        # Test each column
        try:
            for x in range(1, balls_drawn + 1):
                print_column_test(x)
        except NameError:
            print("Warning: balls_drawn not defined")
            for x in range(1, 6):  # Default to 5 for Fantasy 5
                print_column_test(x)
        
        # Print duplicate table
        print_dup_table(100, 1)
        
        # Flush output
        sys.stdout.flush()
        
        # Game-specific wheel functions
        try:
            dateDiff = 30  # This should come from dateDiffInDays
            
            if game == 1:
                print("eo2")
                ga_f5_print_wheel_sum_table_eo2(limit=dateDiff)
                print("eo3")
                ga_f5_print_wheel_sum_table_eo3(limit=dateDiff)
                print("eo4")
                ga_f5_print_wheel_sum_table_eo4(limit=dateDiff)
                print("eo5")
                ga_f5_print_wheel_sum_table_eo5(limit=dateDiff)
                print("eo6")
                ga_f5_print_wheel_sum_table_eo6(limit=dateDiff)
                print("eo7")
                ga_f5_print_wheel_sum_table_eo7(limit=dateDiff)
            elif game == 2:
                mm_print_wheel_sum_table_eo4(10000)
            elif game == 7:
                pb_print_wheel_sum_table_eo(limit=10000)
                pb_print_wheel_sum_table_eo4(limit=10000)
        except NameError as e:
            print(f"Warning: Some wheel functions not available: {e}")
        
        # Exit early (equivalent to PHP die())
        print("Test execution complete - exiting early")
        return
        
        # The following code would run if we didn't exit early
        # (keeping it for reference but it won't execute)
        
        if not hml:
            try:
                print_wheel_sum_table_eo4(10000)
                if game == 1:
                    ga_f5_print_wheel_sum_table_eo7(limit=10000)
                elif game == 7:
                    pb_print_wheel_sum_table_eo4(10000)
            except NameError:
                print("Warning: Additional wheel functions not available")
        
        # Close HTML page
        print("</BODY>")
        print("</HTML>")
        
    except Exception as e:
        print(f"Error in main execution: {e}")
        import traceback
        traceback.print_exc()
    
    finally:
        # Clean up database connection
        if connection.is_connected():
            connection.close()

if __name__ == "__main__":
    main()