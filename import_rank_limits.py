#!/usr/bin/env python3

"""
Script to import rank limit data from CSV to MySQL database.
This script reads the rank_limit_100.csv file and imports the data
into the ga_f5_rank_limits table in the MySQL database.
"""

import mysql.connector
import csv
import sys

# Database connection parameters
db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'ga_f5_lotto'
}

def import_rank_limits(csv_file='rank_limit_100.csv'):
    """
    Import rank limits from CSV to MySQL database
    
    Parameters:
    csv_file (str): Path to the CSV file with rank limit data
    """
    try:
        # Connect to the database
        conn = mysql.connector.connect(**db_config)
        cursor = conn.cursor()
        
        # Create the table if it doesn't exist
        cursor.execute("""
        CREATE TABLE IF NOT EXISTS ga_f5_rank_limits (
            rank_id INT NOT NULL AUTO_INCREMENT,
            rank_limit INT NOT NULL,
            rank_description VARCHAR(255),
            PRIMARY KEY (rank_id)
        )
        """)
        
        # Clear existing data
        cursor.execute("TRUNCATE TABLE ga_f5_rank_limits")
        
        # Read the CSV file
        with open(csv_file, 'r') as f:
            csv_reader = csv.reader(f)
            # First row is headers
            headers = next(csv_reader)
            # Second row is data
            limit_data = next(csv_reader)
            
            # Insert each value with a description
            for i, limit in enumerate(limit_data):
                cursor.execute(
                    "INSERT INTO ga_f5_rank_limits (rank_limit, rank_description) VALUES (%s, %s)",
                    (int(limit), f"Rank {i+1} limit")
                )
        
        # Commit the changes
        conn.commit()
        
        # Verify the data was inserted correctly
        cursor.execute("SELECT COUNT(*) FROM ga_f5_rank_limits")
        count = cursor.fetchone()[0]
        print(f"Successfully imported {count} rank limit values to database")
        
        # Close the connection
        cursor.close()
        conn.close()
        
        return True
    except Exception as e:
        print(f"Error importing rank limits: {e}")
        return False

if __name__ == "__main__":
    # Use command line argument for CSV file if provided
    csv_file = sys.argv[1] if len(sys.argv) > 1 else 'rank_limit_100.csv'
    import_rank_limits(csv_file)