# migrate_from_php.py
# Script to migrate lottery data from PHP MySQL database to Flask application

import pymysql
from datetime import datetime
import requests
import json
from time import sleep

# Configuration
PHP_DB_CONFIG = {
    'host': 'localhost',
    'user': 'root',
    'password': 'wef5esuv',  # From your PHP file
    'database': 'ga_f5_lotto',
    'charset': 'utf8mb4'
}

FLASK_API_URL = 'http://localhost:5000/api/import_draw'

# Game mappings from PHP to Flask
GAME_MAPPING = {
    'ga_f5': 1,      # Georgia Fantasy 5
    'mega': 2,       # Mega Millions
    'ga_5': 3,       # Georgia 5
    'jumbo': 4,      # Jumbo
    'fl_f5': 5,      # Florida Fantasy 5
    'fl_lotto': 6,   # Florida Lotto
    'powerball': 7   # Powerball
}

def get_php_connection():
    """Create connection to PHP database"""
    return pymysql.connect(**PHP_DB_CONFIG)

def migrate_game_draws(game_prefix, flask_game_id, has_mega=False):
    """Migrate draws for a specific game"""
    connection = get_php_connection()
    imported = 0
    errors = 0
    
    try:
        with connection.cursor(pymysql.cursors.DictCursor) as cursor:
            # Determine table name based on your PHP structure
            table_name = f"{game_prefix}_draws"
            
            # Get all draws from PHP database
            query = f"SELECT * FROM {table_name} ORDER BY date ASC"
            cursor.execute(query)
            draws = cursor.fetchall()
            
            print(f"\nMigrating {len(draws)} draws from {table_name}...")
            
            for draw in draws:
                try:
                    # Prepare data for Flask API
                    draw_data = {
                        'game_id': flask_game_id,
                        'date': draw['date'].strftime('%Y-%m-%d') if hasattr(draw['date'], 'strftime') else str(draw['date'])
                    }
                    
                    # Map ball columns (adjust based on your PHP schema)
                    for i in range(1, 7):
                        ball_col = f'b{i}' if f'b{i}' in draw else str(i)
                        if ball_col in draw and draw[ball_col]:
                            draw_data[f'ball{i}'] = int(draw[ball_col])
                    
                    # Add mega ball if applicable
                    if has_mega and 'pb' in draw and draw['pb']:
                        draw_data['mega_ball'] = int(draw['pb'])
                    
                    # Send to Flask API
                    response = requests.post(FLASK_API_URL, json=draw_data)
                    
                    if response.status_code == 200:
                        imported += 1
                        if imported % 100 == 0:
                            print(f"  Imported {imported} draws...")
                    else:
                        errors += 1
                        print(f"  Error importing draw {draw_data['date']}: {response.text}")
                    
                    # Rate limiting to avoid overwhelming the API
                    sleep(0.01)
                    
                except Exception as e:
                    errors += 1
                    print(f"  Error processing draw: {e}")
                    continue
            
            print(f"  Completed: {imported} imported, {errors} errors")
            
    finally:
        connection.close()
    
    return imported, errors

def migrate_combinations(game_prefix, flask_game_id):
    """Migrate combination analysis data"""
    connection = get_php_connection()
    
    try:
        with connection.cursor(pymysql.cursors.DictCursor) as cursor:
            # Check for combination tables
            tables = [f"{game_prefix}_draws_2", f"{game_prefix}_draws_3", f"{game_prefix}_draws_4"]
            
            for table in tables:
                try:
                    cursor.execute(f"SELECT COUNT(*) as count FROM {table}")
                    result = cursor.fetchone()
                    print(f"  Found {result['count']} records in {table}")
                    # You can extend this to migrate combination data if needed
                except:
                    print(f"  Table {table} not found, skipping...")
                    
    finally:
        connection.close()

def verify_migration():
    """Verify migration by comparing counts"""
    connection = get_php_connection()
    
    print("\nVerifying migration...")
    
    try:
        with connection.cursor() as cursor:
            for php_prefix, flask_id in GAME_MAPPING.items():
                try:
                    # Count draws in PHP database
                    cursor.execute(f"SELECT COUNT(*) FROM {php_prefix}_draws")
                    php_count = cursor.fetchone()[0]
                    
                    # Get count from Flask API (you'd need to add this endpoint)
                    # For now, just print PHP count
                    print(f"  {php_prefix}: {php_count} draws in PHP database")
                    
                except Exception as e:
                    print(f"  Could not verify {php_prefix}: {e}")
                    
    finally:
        connection.close()

def main():
    """Main migration function"""
    print("Starting PHP to Flask Lottery Data Migration")
    print("=" * 50)
    
    total_imported = 0
    total_errors = 0
    
    # Migrate Georgia Fantasy 5
    if 'ga_f5' in GAME_MAPPING:
        imported, errors = migrate_game_draws('ga_f5', GAME_MAPPING['ga_f5'], has_mega=False)
        total_imported += imported
        total_errors += errors
        migrate_combinations('ga_f5', GAME_MAPPING['ga_f5'])
    
    # Migrate Mega Millions
    if 'mega' in GAME_MAPPING:
        imported, errors = migrate_game_draws('mega', GAME_MAPPING['mega'], has_mega=True)
        total_imported += imported
        total_errors += errors
    
    # Migrate Powerball
    if 'powerball' in GAME_MAPPING:
        imported, errors = migrate_game_draws('powerball', GAME_MAPPING['powerball'], has_mega=True)
        total_imported += imported
        total_errors += errors
    
    # Add other games as needed...
    
    print("\n" + "=" * 50)
    print(f"Migration Complete!")
    print(f"Total Imported: {total_imported}")
    print(f"Total Errors: {total_errors}")
    
    # Verify migration
    verify_migration()
    
    # Update statistics via API
    print("\nUpdating statistics...")
    for flask_game_id in GAME_MAPPING.values():
        try:
            response = requests.get(f'http://localhost:5000/api/update_stats/{flask_game_id}')
            if response.status_code == 200:
                print(f"  Updated statistics for game {flask_game_id}")
            else:
                print(f"  Failed to update statistics for game {flask_game_id}")
        except Exception as e:
            print(f"  Error updating statistics for game {flask_game_id}: {e}")

if __name__ == "__main__":
    # Check if Flask app is running
    try:
        response = requests.get('http://localhost:5000/')
        if response.status_code != 200:
            print("ERROR: Flask application is not running!")
            print("Please start the Flask app first with: flask run")
            exit(1)
    except:
        print("ERROR: Cannot connect to Flask application!")
        print("Please start the Flask app first with: flask run")
        exit(1)
    
    # Confirm before proceeding
    print("This will migrate data from your PHP database to the Flask application.")
    confirm = input("Continue? (yes/no): ")
    
    if confirm.lower() == 'yes':
        main()
    else:
        print("Migration cancelled.")