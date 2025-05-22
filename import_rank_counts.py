#!/usr/bin/env python3

"""
Script to import rank count data from CSV to MySQL database.
This script reads the rank_count_100.csv file and imports the data
into the ga_f5_rank_counts table in the MySQL database.
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

def import_rank_counts(csv_file='rank_count_100.csv'):
    """
    Import rank counts from CSV to MySQL database
    
    Parameters:
    csv_file (str): Path to the CSV file with rank count data
    """
    try:
        # Connect to the database
        conn = mysql.connector.connect(**db_config)
        cursor = conn.cursor()
        
        # Create the table if it doesn't exist
        cursor.execute("""
        CREATE TABLE IF NOT EXISTS ga_f5_rank_counts (
            rank_id INT NOT NULL AUTO_INCREMENT,
            rank_count INT NOT NULL,
            rank_description VARCHAR(255),
            PRIMARY KEY (rank_id)
        )
        """)
        
        # Clear existing data
        cursor.execute("TRUNCATE TABLE ga_f5_rank_counts")
        
        # Read the CSV file
        with open(csv_file, 'r') as f:
            csv_reader = csv.reader(f)
            # First row is headers
            headers = next(csv_reader)
            # Second row is data
            count_data = next(csv_reader)
            
            # Insert each value with a description
            for i, count in enumerate(count_data):
                cursor.execute(
                    "INSERT INTO ga_f5_rank_counts (rank_count, rank_description) VALUES (%s, %s)",
                    (int(count), f"Rank {i+1} count")
                )
        
        # Commit the changes
        conn.commit()
        
        # Verify the data was inserted correctly
        cursor.execute("SELECT COUNT(*) FROM ga_f5_rank_counts")
        count = cursor.fetchone()[0]
        print(f"Successfully imported {count} rank count values to database")
        
        # Close the connection
        cursor.close()
        conn.close()
        
        return True
    except Exception as e:
        print(f"Error importing rank counts: {e}")
        return False

if __name__ == "__main__":
    # Use command line argument for CSV file if provided
    csv_file = sys.argv[1] if len(sys.argv) > 1 else 'rank_count_100.csv'
    import_rank_counts(csv_file)