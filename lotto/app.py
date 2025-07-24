from flask import Flask, render_template, request
import pymysql
import time
from datetime import datetime, timedelta
import math
import statistics
import os

# Create Flask application
app = Flask(__name__)

# Configuration
class Config:
    # Game definitions
    GAMES = {
        1: {"name": "Georgia Fantasy 5", "balls": 42, "balls_drawn": 5, "mega_balls": False, 
            "db_prefix": "ga_f5_", "draw_table": "ga_f5_draws"},
        2: {"name": "Mega Millions", "balls": 70, "balls_drawn": 5, "mega_balls": True,
            "db_prefix": "mm_", "draw_table": "mm_draws"},
        3: {"name": "Georgia 5", "balls": 39, "balls_drawn": 5, "mega_balls": False,
            "db_prefix": "ga_5_", "draw_table": "ga_5_draws"},
        4: {"name": "Jumbo Bucks", "balls": 47, "balls_drawn": 6, "mega_balls": False,
            "db_prefix": "ga_jb_", "draw_table": "ga_jb_draws"},
        5: {"name": "Florida Fantasy 5", "balls": 36, "balls_drawn": 5, "mega_balls": False,
            "db_prefix": "fl_f5_", "draw_table": "fl_f5_draws"},
        6: {"name": "Florida Lotto", "balls": 53, "balls_drawn": 6, "mega_balls": False,
            "db_prefix": "fl_lotto_", "draw_table": "fl_lotto_draws"},
        7: {"name": "Powerball", "balls": 69, "balls_drawn": 5, "mega_balls": True,
            "db_prefix": "pb_", "draw_table": "pb_draws"},
    }

    # Database configuration
    DB_CONFIG = {
        "host": "localhost",
        "user": "root",
        "password": "wef5esuv",  # In production, use environment variables instead
        "db": "ga_f5_lotto"
    }

    # HML settings
    HML_OPTIONS = {
        0: "all",
        1: "high",
        2: "medium",
        3: "low",
        4: "min",
        5: "max",
        110: "special"
    }
    
    # Default grid flags
    GRID_26_FLAG = 4
    GRID_ALL_FLAG = 35

# Load the configuration
config = Config()

def get_db_connection():
    """Establish a database connection"""
    return pymysql.connect(
        host=config.DB_CONFIG["host"],
        user=config.DB_CONFIG["user"],
        password=config.DB_CONFIG["password"],
        db=config.DB_CONFIG["db"],
        cursorclass=pymysql.cursors.DictCursor
    )

def calculate_date_ranges():
    """Calculate date ranges for statistics"""
    today = datetime.now()
    
    date_ranges = {
        "day1": today - timedelta(days=1),
        "week1": today - timedelta(days=7),
        "week2": today - timedelta(days=14),
        "month1": today - timedelta(days=30),
        "month3": today - timedelta(days=90),
        "month6": today - timedelta(days=180),
        "year1": today - timedelta(days=365),
        "year2": today - timedelta(days=365*2),
        "year3": today - timedelta(days=365*3),
        "year4": today - timedelta(days=365*4),
        "year5": today - timedelta(days=365*5),
        "year6": today - timedelta(days=365*6),
        "year7": today - timedelta(days=365*7),
        "year8": today - timedelta(days=365*8),
        "year9": today - timedelta(days=365*9),
        "year10": today - timedelta(days=365*10)
    }
    
    return date_ranges

def update_even_odd(draw, db, game_id):
    """Update even/odd counts for a draw"""
    draw_even = 0
    draw_odd = 0
    
    for num in draw:
        if num % 2 == 0:
            draw_even += 1
        else:
            draw_odd += 1
    
    # Update the database record
    game_config = config.GAMES[game_id]
    cursor = db.cursor()
    
    query = f"UPDATE {game_config['draw_table']} SET even = %s, odd = %s WHERE id = %s"
    cursor.execute(query, (draw_even, draw_odd, row['id']))
    db.commit()
    cursor.close()
    
    return draw_even, draw_odd

def update_sum(draw, db, game_id):
    """Update sum for a draw"""
    draw_sum = sum(draw)
    
    # Update the database record
    game_config = config.GAMES[game_id]
    cursor = db.cursor()
    
    query = f"UPDATE {game_config['draw_table']} SET sum = %s WHERE id = %s"
    cursor.execute(query, (draw_sum, row['id']))
    db.commit()
    cursor.close()
    
    return draw_sum

def calculate_draw_count(draw, draw_count_array, game_id):
    """Calculate draw count statistics"""
    game_config = config.GAMES[game_id]
    
    for num in draw:
        draw_count_array[num][0] += 1
    
    return draw_count_array

def get_draw_statistics(draw):
    """Calculate various statistics for a draw"""
    if not draw:
        return {
            "average": 0, "median": 0, "harmean": 0, "geomean": 0,
            "quart1": 0, "quart2": 0, "quart3": 0, "stdev": 0,
            "variance": 0, "avedev": 0, "kurt": 0, "skew": 0
        }
    
    # Basic statistics
    average = sum(draw) / len(draw)
    median = statistics.median(draw)
    
    # Harmonic mean
    harmean = len(draw) / sum(1/x if x != 0 else 0.0001 for x in draw)
    
    # Geometric mean
    geomean = math.exp(sum(math.log(x) if x > 0 else 0 for x in draw) / len(draw))
    
    # Quartiles
    sorted_draw = sorted(draw)
    n = len(sorted_draw)
    
    if n >= 4:
        quart1 = sorted_draw[n // 4]
        quart2 = sorted_draw[n // 2]
        quart3 = sorted_draw[3 * n // 4]
    else:
        quart1 = quart2 = quart3 = 0
    
    # Standard deviation and variance
    if n > 1:
        stdev = statistics.stdev(draw)
        variance = statistics.variance(draw)
    else:
        stdev = variance = 0
    
    # Average deviation
    avedev = sum(abs(x - average) for x in draw) / len(draw)
    
    # Skewness and Kurtosis (simplified versions)
    if n > 2 and stdev > 0:
        skew = sum((x - average) ** 3 for x in draw) / ((n - 1) * stdev ** 3)
        kurt = sum((x - average) ** 4 for x in draw) / ((n - 1) * stdev ** 4) - 3
    else:
        skew = kurt = 0
    
    return {
        "average": round(average, 2),
        "median": median,
        "harmean": round(harmean, 2),
        "geomean": round(geomean, 2),
        "quart1": quart1,
        "quart2": quart2,
        "quart3": quart3,
        "stdev": round(stdev, 2),
        "variance": round(variance, 2),
        "avedev": round(avedev, 2),
        "kurt": round(kurt, 2),
        "skew": round(skew, 2)
    }

@app.route('/')
def index():
    """Home page with game selection"""
    return render_template('index.html', games=config.GAMES)

@app.route('/display/<int:game_id>')
def display(game_id):
    """Main display route to show lottery analysis"""
    if game_id not in config.GAMES:
        return "Invalid game selection", 404
    
    # Get limit parameter or use default
    limit = request.args.get('limit', default=30, type=int)
    
    # Get HML parameter (high/medium/low setting)
    hml = request.args.get('hml', default=0, type=int)
    
    game_config = config.GAMES[game_id]
    
    # Get database connection
    db = get_db_connection()
    cursor = db.cursor()
    
    # Get date ranges for statistics
    date_ranges = calculate_date_ranges()
    
    # Get the draws data
    query = f"""
        SELECT * FROM {game_config['draw_table']} 
        WHERE date >= '2015-10-01' 
        ORDER BY date DESC 
        LIMIT {limit}
    """
    cursor.execute(query)
    draws = cursor.fetchall()
    
    # Process each draw to ensure all data is updated
    draw_count_array = [[0] * 17 for _ in range(game_config['balls'] + 1)]
    processed_draws = []
    
    for row in draws:
        # Extract the draw numbers
        draw_numbers = [row[f'b{i}'] for i in range(1, game_config['balls_drawn'] + 1)]
        
        # Sort the draw for display
        sorted_draw = sorted(draw_numbers)
        
        # Calculate sum if not already set
        if row['sum'] == 0:
            draw_sum = update_sum(draw_numbers, db, game_id)
        else:
            draw_sum = row['sum']
        
        # Calculate even/odd if not already set
        if row['even'] == 0:
            draw_even, draw_odd = update_even_odd(draw_numbers, db, game_id)
        else:
            draw_even, draw_odd = row['even'], row['odd']
        
        # Calculate statistics
        stats = get_draw_statistics(draw_numbers)
        
        # Update draw count array
        draw_count_array = calculate_draw_count(draw_numbers, draw_count_array, game_id)
        
        # Add processed draw to the list
        processed_draws.append({
            'id': row['id'],
            'date': row['date'],
            'numbers': draw_numbers,
            'sorted_numbers': sorted_draw,
            'sum': draw_sum,
            'even': draw_even,
            'odd': draw_odd,
            'stats': stats,
            'wa': row.get('wa', 0)  # Weighted average if available
        })
    
    # Calculate number statistics
    number_stats = []
    for num in range(1, game_config['balls'] + 1):
        # Calculate how many times each number appeared
        count = draw_count_array[num][0]
        
        # Calculate frequency percentage
        frequency = (count / limit) * 100 if limit > 0 else 0
        
        number_stats.append({
            'number': num,
            'count': count,
            'frequency': round(frequency, 2)
        })
    
    # Sort number stats by count (descending)
    number_stats.sort(key=lambda x: x['count'], reverse=True)
    
    # Close the database connection
    cursor.close()
    db.close()
    
    return render_template(
        'display.html',
        game=game_config,
        hml=hml,
        hml_name=config.HML_OPTIONS.get(hml, "all"),
        limit=limit,
        draws=processed_draws,
        number_stats=number_stats
    )

@app.route('/pairs/<int:game_id>')
def pairs(game_id):
    """Show pair analysis for a game"""
    if game_id not in config.GAMES:
        return "Invalid game selection", 404
    
    limit = request.args.get('limit', default=30, type=int)
    game_config = config.GAMES[game_id]
    
    # Get database connection
    db = get_db_connection()
    cursor = db.cursor()
    
    # We'll query and calculate pair data
    pairs_data = {}
    
    # Create a grid of all possible pairs
    grid_data = [[0 for _ in range(game_config['balls'] + 1)] for _ in range(game_config['balls'] + 1)]
    
    # Get the last 'limit' draws
    query = f"""
        SELECT * FROM {game_config['draw_table']}
        ORDER BY date DESC
        LIMIT {limit}
    """
    cursor.execute(query)
    draws = cursor.fetchall()
    
    # Process each draw to count pairs
    for row in draws:
        # Extract the draw numbers
        draw_numbers = [row[f'b{i}'] for i in range(1, game_config['balls_drawn'] + 1)]
        
        # Count each pair in the draw
        for i, num1 in enumerate(draw_numbers):
            for num2 in draw_numbers[i+1:]:
                if num1 < num2:
                    key = f"{num1}-{num2}"
                else:
                    key = f"{num2}-{num1}"
                    
                # Update the pair count
                if key in pairs_data:
                    pairs_data[key]['count'] += 1
                else:
                    pairs_data[key] = {
                        'num1': min(num1, num2),
                        'num2': max(num1, num2),
                        'count': 1,
                        'last_date': row['date']
                    }
                
                # Update the grid data
                grid_data[min(num1, num2)][max(num1, num2)] += 1
    
    # Sort pairs by count (descending)
    sorted_pairs = sorted(pairs_data.values(), key=lambda x: x['count'], reverse=True)
    
    # Close the database connection
    cursor.close()
    db.close()
    
    return render_template(
        'pairs.html',
        game=game_config,
        limit=limit,
        pairs=sorted_pairs,
        grid=grid_data,
        grid_flag_26=config.GRID_26_FLAG,
        grid_flag_all=config.GRID_ALL_FLAG
    )

@app.route('/rank/<int:game_id>')
def rank(game_id):
    """Show ranking analysis for numbers"""
    if game_id not in config.GAMES:
        return "Invalid game selection", 404
    
    limit = request.args.get('limit', default=30, type=int)
    game_config = config.GAMES[game_id]
    
    # Get database connection
    db = get_db_connection()
    cursor = db.cursor()
    
    # Get the draws
    query = f"""
        SELECT * FROM {game_config['draw_table']}
        ORDER BY date DESC
        LIMIT {limit}
    """
    cursor.execute(query)
    draws = cursor.fetchall()
    
    # Count occurrences of each number
    number_counts = [0] * (game_config['balls'] + 1)
    
    for row in draws:
        for i in range(1, game_config['balls_drawn'] + 1):
            number = row[f'b{i}']
            number_counts[number] += 1
    
    # Create ranking data
    rank_data = []
    for num in range(1, game_config['balls'] + 1):
        # Calculate how many times the number appeared
        count = number_counts[num]
        
        # Calculate frequency percentage
        frequency = (count / limit) * 100 if limit > 0 else 0
        
        rank_data.append({
            'number': num,
            'count': count,
            'frequency': round(frequency, 2)
        })
    
    # Sort by count (descending)
    rank_data.sort(key=lambda x: x['count'], reverse=True)
    
    # Close the database connection
    cursor.close()
    db.close()
    
    return render_template(
        'rank.html',
        game=game_config,
        limit=limit,
        rank_data=rank_data
    )

@app.route('/grid/<int:game_id>')
def grid(game_id):
    """Show grid analysis for a game"""
    if game_id not in config.GAMES:
        return "Invalid game selection", 404
    
    limit = request.args.get('limit', default=30, type=int)
    game_config = config.GAMES[game_id]
    
    # Get database connection
    db = get_db_connection()
    cursor = db.cursor()
    
    # Create a grid of all possible pairs
    grid_data = [[0 for _ in range(game_config['balls'] + 1)] for _ in range(game_config['balls'] + 1)]
    
    # Get the last 'limit' draws
    query = f"""
        SELECT * FROM {game_config['draw_table']}
        ORDER BY date DESC
        LIMIT {limit}
    """
    cursor.execute(query)
    draws = cursor.fetchall()
    
    # Process each draw to count pairs
    for row in draws:
        # Extract the draw numbers
        draw_numbers = [row[f'b{i}'] for i in range(1, game_config['balls_drawn'] + 1)]
        
        # Count each pair in the draw
        for i, num1 in enumerate(draw_numbers):
            for num2 in draw_numbers[i+1:]:
                # Update the grid data (use min/max to ensure correct placement)
                grid_data[min(num1, num2)][max(num1, num2)] += 1
    
    # Determine grid flag based on limit
    if limit == 26:
        grid_flag = config.GRID_26_FLAG
    else:
        grid_flag = config.GRID_ALL_FLAG
    
    # Close the database connection
    cursor.close()
    db.close()
    
    return render_template(
        'grid.html',
        game=game_config,
        limit=limit,
        grid=grid_data,
        balls=game_config['balls'],
        grid_flag=grid_flag
    )

if __name__ == '__main__':
    app.run(debug=True)