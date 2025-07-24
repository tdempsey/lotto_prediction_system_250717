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

hml = 0
# hml = 1    # high
# hml = 2    # medium
# hml = 3    # low
# hml = 4    # min
# hml = 5    # max
# hml = 100

# No time limit in Python equivalent
# set_time_limit(0) - Python doesn't have this concept

# Error reporting equivalent
debug = True

if debug:
    import logging
    logging.basicConfig(level=logging.DEBUG)
    print("Debug mode enabled")

col1_select = 0

# Import equivalent modules
try:
    from includes.games_switch import *
    from includes.even_odd import *
    from includes.last_draws import *
    from includes.calculate_rank import *
    from includes.look_up_rank import *
    from includes.build_rank_table import *
    from includes.test_column_lookup import *
    from includes.next_draw import *
    from includes.number_due import *
    from includes.first_draw_unix import *
    from includes.last_draw_unix import *
    from includes.count_2_seq import *
    from includes.count_3_seq import *
    from includes.dateDiffInDays import *
    from includes.print_sumeo_drange import *
    from includes.limit_functions import *
    from includes.print_table_analyze import *
    from includes.unix import *
    
    # Game-specific includes
    game_includes = f"includes_{game_name.lower().replace(' ', '_')}"
    from includes.combin import *
    
except ImportError as e:
    print(f"Warning: Some modules not found: {e}")

# Database connection
try:
    from includes.mysqli_python import get_connection
    connection = get_connection()
except ImportError:
    print("Warning: mysqli_python module not found, using direct connection")
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

def print_draw_summary():
    """Print draw summary statistics"""
    global draw_table_name, balls, balls_drawn, draw_prefix, col1_select, hml, range_low, range_high
    
    cursor = connection.cursor()
    
    try:
        from includes.calculate_draw_summary import *
        from includes.print_draw_summary import *
    except ImportError:
        print("Warning: draw summary modules not found")
    
    print(f'<h3>Table <font color="#ff0000">{draw_prefix}sum</font> Updated!</h3>')

def print_sum_grid():
    """Print sum grid statistics"""
    global draw_table_name, balls, balls_drawn, draw_prefix, col1_select, hml, range_low, range_high
    
    cursor = connection.cursor()
    
    try:
        from includes.calculate_sum_count import *
        from includes.print_sum_table import *
    except ImportError:
        print("Warning: sum grid modules not found")
    
    print(f'<h3>Table <font color="#ff0000">{draw_prefix}sum</font> Updated!</h3>')

def print_sum_grid_sum():
    """Print sum grid with sum statistics"""
    global draw_table_name, balls, balls_drawn, draw_prefix, col1_select, hml, range_low, range_high
    
    cursor = connection.cursor()
    
    print("calculate_sum_count_sum.incl")
    
    try:
        from includes.calculate_sum_count_sum import *
    except ImportError:
        print("Warning: calculate_sum_count_sum module not found")
    
    print("print_sum_table_sum.incl")
    
    try:
        from includes.print_sum_table_sum import *
    except ImportError:
        print("Warning: print_sum_table_sum module not found")
    
    print("print_sum_table_sum_top25.incl")
    
    try:
        from includes.print_sum_table_sum_top25 import *
    except ImportError:
        print("Warning: print_sum_table_sum_top25 module not found")
    
    print(f'<h3>Table <font color="#ff0000">{draw_prefix}sum</font> Updated!</h3>')
    
    print("print_sum_table_sum_23_32.incl")
    
    try:
        from includes.print_sum_table_sum_23_32 import *
    except ImportError:
        print("Warning: print_sum_table_sum_23_32 module not found")
    
    print("print_sum_table_sum_top25_23_32.incl")
    
    try:
        from includes.print_sum_table_sum_top25_23_32 import *
    except ImportError:
        print("Warning: print_sum_table_sum_top25_23_32 module not found")

def print_sum_drange2():
    """Print sum drange2 statistics"""
    global draw_table_name, balls, balls_drawn, draw_prefix, col1_select, hml, range_low, range_high
    
    cursor = connection.cursor()
    
    try:
        from includes.calculate_sum_drange2 import *
        from includes.print_sum_table_drange2 import *
        from includes.print_sum_table_drange2_top25 import *
    except ImportError:
        print("Warning: sum drange2 modules not found")
    
    print(f'<h3>Table <font color="#ff0000">{draw_prefix}sum</font> Updated!</h3>')

def print_sum_drange3():
    """Print sum drange3 statistics"""
    global draw_table_name, balls, balls_drawn, draw_prefix, col1_select, hml, range_low, range_high
    
    cursor = connection.cursor()
    
    try:
        from includes.calculate_sum_drange3 import *
        from includes.print_sum_table_drange3 import *
        from includes.print_sum_table_drange3_top25 import *
    except ImportError:
        print("Warning: sum drange3 modules not found")
    
    print(f'<h3>Table <font color="#ff0000">{draw_prefix}sum</font> Updated!</h3>')

def print_sum_grid_colx():
    """Print sum grid colx statistics"""
    global draw_table_name, balls, balls_drawn, draw_prefix, col1_select, hml, range_low, range_high
    
    cursor = connection.cursor()
    
    try:
        from includes.calculate_sum_count_colx import *
        from includes.print_sum_table_colx import *
    except ImportError:
        print("Warning: sum grid colx modules not found")
    
    print(f'<h3>Table <font color="#ff0000">{draw_prefix}sum</font> Updated!</h3>')

def print_combo_by_sum():
    """Print combo by sum statistics"""
    global draw_table_name, balls, balls_drawn, draw_prefix, col1_select, hml, range_low, range_high
    
    cursor = connection.cursor()
    
    # Drop table if exists
    query4 = f"DROP TABLE IF EXISTS {draw_prefix}combo_by_sum"
    cursor.execute(query4)
    
    # Create table
    query4 = f"""CREATE TABLE {draw_prefix}combo_by_sum (
        id int(10) unsigned NOT NULL auto_increment,
        sum tinyint(3) unsigned NOT NULL default '0',
        combo tinyint(3) unsigned NOT NULL default '0',
        d1 tinyint(3) unsigned NOT NULL default '0',
        d2 tinyint(3) unsigned NOT NULL default '0',
        day1 tinyint(3) unsigned NOT NULL default '0',
        week1 tinyint(3) unsigned NOT NULL default '0',
        week2 tinyint(3) unsigned NOT NULL default '0',
        month1 tinyint(3) unsigned NOT NULL default '0',
        month3 tinyint(3) unsigned NOT NULL default '0',
        month6 tinyint(3) unsigned NOT NULL default '0',
        year1 int(5) unsigned NOT NULL default '0',
        year2 int(5) unsigned NOT NULL default '0',
        year3 int(5) unsigned NOT NULL default '0',
        year4 int(5) unsigned NOT NULL default '0',
        year5 int(5) unsigned NOT NULL default '0',
        year6 int(5) unsigned NOT NULL default '0',
        year7 int(5) unsigned NOT NULL default '0',
        year8 int(5) unsigned NOT NULL default '0',
        year9 int(5) unsigned NOT NULL default '0',
        year10 int(5) unsigned NOT NULL default '0',
        count int(5) unsigned NOT NULL,
        percent_y1 float(5,3) unsigned NOT NULL,
        percent_y8 float(5,3) unsigned NOT NULL,
        percent_wa float(5,3) unsigned NOT NULL,
        PRIMARY KEY (id),
        UNIQUE KEY id_2 (id),
        KEY id (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1"""
    
    cursor.execute(query4)
    
    for c in range(1, 11):
        try:
            from includes.calculate_combo2_by_sum import *
        except ImportError:
            print("Warning: calculate_combo2_by_sum module not found")
    
    connection.commit()
    print(f'<h3>Table <font color="#ff0000">{draw_prefix}_combo_by_sum</font> Updated!</h3>')

def print_limits_by_sumeo():
    """Print limits by sumeo statistics"""
    global draw_table_name, balls, balls_drawn, draw_prefix, col1_select, hml, range_low, range_high
    
    cursor = connection.cursor()
    
    try:
        from includes.calculate_limits_by_sumeo import *
        from includes.calculate_limits_by_sumeo_method2 import *
    except ImportError:
        print("Warning: limits by sumeo modules not found")
    
    print(f'<h3>Table <font color="#ff0000">{draw_prefix}_combo_by_sum</font> Updated!</h3>')

def calculate_weighted_average():
    """
    Calculate the weighted average of the Number Draws
    Example from PHP comment converted to Python
    """
    # Given values array based on 'x' as 1 and '-' as 0
    values = [1, 0, 0, 0, 0, 1, 0, 1, 0, 1, 0, 0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 1, 1, 1, 0, 1, 1]
    
    # Assign weights descending from 30 to 1
    weights = list(range(30, 0, -1))
    
    # Calculate weighted sum
    weighted_sum = 0
    total_weight = sum(weights)
    
    for position, value in enumerate(values):
        # The weight for each position is from the descending weights array
        weight = weights[position]
        weighted_sum += value * weight
    
    # Calculate weighted average
    weighted_average = weighted_sum / total_weight
    
    # Display the result
    print(f"The weighted average is: {round(weighted_average, 4)}")
    
    return weighted_average

def generate_html_header(game_name):
    """Generate HTML header"""
    html = f"""<HTML>
<HEAD>
<TITLE>{game_name} Analyze</TITLE>
</HEAD>
<BODY>
<H1>{game_name} Analyze</H1>
"""
    return html

def main():
    """Main execution function"""
    
    try:
        # Get game name from configuration
        game_name = "Georgia Fantasy 5"  # This should come from games_switch
        
        # Generate HTML output
        print(generate_html_header(game_name))
        
        # Get current date and next draw date
        curr_date = datetime.datetime.now().strftime("%Y%m%d")
        
        try:
            next_draw_Ymd = findNextDrawDate(game)
        except NameError:
            next_draw_Ymd = curr_date  # Fallback if function not available
        
        # Flush output
        sys.stdout.flush()
        
        # Main analysis functions
        print("entering print_table_analyze(31)")
        try:
            print_table_analyze(31)
        except NameError:
            print("Error: print_table_analyze function not found")
        
        print("entering print_draw_summary")
        print_draw_summary()
        
        print("entering print_sum_grid")
        print_sum_grid()
        
        print("entering print_sum_grid_sum")
        print_sum_grid_sum()
        
        # Additional analysis functions
        try:
            print_sumeo_drange_summary()
        except NameError:
            print("Error: print_sumeo_drange_summary function not found")
        
        # Exit early (equivalent to PHP die())
        print("Analysis complete - exiting early")
        return
        
        # The following code would run if we didn't exit early
        # (keeping it for reference but it won't execute)
        
        try:
            print_sumeo_drange6()
            print_sumeo_drange7()
            print_sumeo_drange_summary()
        except NameError:
            print("Error: Some sumeo drange functions not found")
        
        # Flush output
        sys.stdout.flush()
        
        # Additional table analysis
        try:
            print_table_analyze(31)
            print_table_analyze(14)
            print_table_analyze(7)
        except NameError:
            print("Error: print_table_analyze function not found")
        
        print_sum_grid()
        print_sum_grid_sum()
        
        print_limits_by_sumeo()
        
        print_sum_drange2()
        print_sum_drange3()
        
        print_limits_by_sumeo()
        
        print('<p><a href="lot_test.py" target="_blank">Open lot_test.py</a></p>')
        
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