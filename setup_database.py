#!/usr/bin/env python3

"""
Database maintenance script for Georgia Fantasy 5 Lottery Prediction System.
This script provides tools to manage and update the database tables.
"""

import mysql.connector
import argparse
import sys

# Database connection parameters
db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'ga_f5_lotto'
}

def check_tables():
    """Check if all required tables exist and have data"""
    try:
        # Connect to the database
        conn = mysql.connector.connect(**db_config)
        cursor = conn.cursor()
        
        # Check which tables exist
        cursor.execute("SHOW TABLES")
        tables = [table[0] for table in cursor.fetchall()]
        
        # Required tables
        required_tables = ['ga_f5_draws', 'ga_f5_rank_counts', 'ga_f5_rank_limits']
        
        print("\nDatabase Table Status:")
        print("======================")
        
        for table in required_tables:
            if table in tables:
                # Check row count
                cursor.execute(f"SELECT COUNT(*) FROM {table}")
                count = cursor.fetchone()[0]
                print(f"✓ {table}: Found with {count} rows")
            else:
                print(f"✗ {table}: Not found")
        
        # Close the connection
        cursor.close()
        conn.close()
        
    except mysql.connector.Error as e:
        print(f"Database error: {e}")
        return False
        
def update_rank_counts(counts):
    """Update rank counts in the database"""
    try:
        # Connect to the database
        conn = mysql.connector.connect(**db_config)
        cursor = conn.cursor()
        
        # Check if table exists
        cursor.execute("SHOW TABLES LIKE 'ga_f5_rank_counts'")
        if not cursor.fetchone():
            print("Creating ga_f5_rank_counts table...")
            cursor.execute("""
            CREATE TABLE ga_f5_rank_counts (
                rank_id INT NOT NULL AUTO_INCREMENT,
                rank_count INT NOT NULL,
                rank_description VARCHAR(255),
                PRIMARY KEY (rank_id)
            )
            """)
        
        # Parse the counts
        count_values = [int(c) for c in counts.split(',')]
        
        # Update the values
        cursor.execute("DELETE FROM ga_f5_rank_counts")
        
        for i, count in enumerate(count_values):
            cursor.execute(
                "INSERT INTO ga_f5_rank_counts (rank_count, rank_description) VALUES (%s, %s)",
                (count, f"Rank {i+1} count")
            )
        
        # Commit changes
        conn.commit()
        
        print(f"Updated ga_f5_rank_counts with {len(count_values)} values")
        
        # Close the connection
        cursor.close()
        conn.close()
        
        return True
    except Exception as e:
        print(f"Error updating rank counts: {e}")
        return False

def update_rank_limits(limits):
    """Update rank limits in the database"""
    try:
        # Connect to the database
        conn = mysql.connector.connect(**db_config)
        cursor = conn.cursor()
        
        # Check if table exists
        cursor.execute("SHOW TABLES LIKE 'ga_f5_rank_limits'")
        if not cursor.fetchone():
            print("Creating ga_f5_rank_limits table...")
            cursor.execute("""
            CREATE TABLE ga_f5_rank_limits (
                rank_id INT NOT NULL AUTO_INCREMENT,
                rank_limit INT NOT NULL,
                rank_description VARCHAR(255),
                PRIMARY KEY (rank_id)
            )
            """)
        
        # Parse the limits
        limit_values = [int(l) for l in limits.split(',')]
        
        # Update the values
        cursor.execute("DELETE FROM ga_f5_rank_limits")
        
        for i, limit in enumerate(limit_values):
            cursor.execute(
                "INSERT INTO ga_f5_rank_limits (rank_limit, rank_description) VALUES (%s, %s)",
                (limit, f"Rank {i+1} limit")
            )
        
        # Commit changes
        conn.commit()
        
        print(f"Updated ga_f5_rank_limits with {len(limit_values)} values")
        
        # Close the connection
        cursor.close()
        conn.close()
        
        return True
    except Exception as e:
        print(f"Error updating rank limits: {e}")
        return False

def initialize_tables():
    """Initialize all required tables with default values"""
    try:
        # Connect to the database
        conn = mysql.connector.connect(**db_config)
        cursor = conn.cursor()
        
        # Create draws table if it doesn't exist
        print("Ensuring ga_f5_draws table exists...")
        cursor.execute("""
        CREATE TABLE IF NOT EXISTS ga_f5_draws (
            id INT NOT NULL AUTO_INCREMENT,
            date DATE NOT NULL,
            b1 INT NOT NULL,
            b2 INT NOT NULL,
            b3 INT NOT NULL,
            b4 INT NOT NULL,
            b5 INT NOT NULL,
            sum INT NOT NULL,
            PRIMARY KEY (id)
        )
        """)
        
        # Check if rank limits table exists
        cursor.execute("SHOW TABLES LIKE 'ga_f5_rank_limits'")
        if not cursor.fetchone():
            print("Creating ga_f5_rank_limits table...")
            cursor.execute("""
            CREATE TABLE ga_f5_rank_limits (
                rank_id INT NOT NULL AUTO_INCREMENT,
                rank_limit INT NOT NULL,
                rank_description VARCHAR(255),
                PRIMARY KEY (rank_id)
            )
            """)
            
            # Insert default values
            default_limits = [1, 1, 2, 3, 2, 3, 1, 1]
            for i, limit in enumerate(default_limits):
                cursor.execute(
                    "INSERT INTO ga_f5_rank_limits (rank_limit, rank_description) VALUES (%s, %s)",
                    (limit, f"Rank {i+1} limit")
                )
                
            print(f"Initialized ga_f5_rank_limits with {len(default_limits)} default values")
        
        # Check if rank counts table exists
        cursor.execute("SHOW TABLES LIKE 'ga_f5_rank_counts'")
        if not cursor.fetchone():
            print("Creating ga_f5_rank_counts table...")
            cursor.execute("""
            CREATE TABLE ga_f5_rank_counts (
                rank_id INT NOT NULL AUTO_INCREMENT,
                rank_count INT NOT NULL,
                rank_description VARCHAR(255),
                PRIMARY KEY (rank_id)
            )
            """)
            
            # Insert default values
            default_counts = [5, 5, 2, 1, 3, 5, 3, 5, 5, 5, 5, 4, 2, 5, 5, 3, 5, 4, 0, 4, 5, 2, 4, 5, 3, 5, 5, 0, 4, 3, 2, 1, 4, 5, 3, 5, 1, 4, 3, 3, 2, 5]
            for i, count in enumerate(default_counts):
                cursor.execute(
                    "INSERT INTO ga_f5_rank_counts (rank_count, rank_description) VALUES (%s, %s)",
                    (count, f"Rank {i+1} count")
                )
                
            print(f"Initialized ga_f5_rank_counts with {len(default_counts)} default values")
        
        # Commit changes
        conn.commit()
        
        # Close the connection
        cursor.close()
        conn.close()
        
        print("Database initialization complete!")
        return True
    except Exception as e:
        print(f"Error initializing tables: {e}")
        return False

if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="Georgia Fantasy 5 Database Maintenance Tool")
    
    # Create subparsers for different commands
    subparsers = parser.add_subparsers(dest="command", help="Command to execute")
    
    # Check tables command
    check_parser = subparsers.add_parser("check", help="Check database tables")
    
    # Initialize tables command
    init_parser = subparsers.add_parser("init", help="Initialize database tables")
    
    # Update rank counts command
    update_counts_parser = subparsers.add_parser("update-counts", help="Update rank counts")
    update_counts_parser.add_argument("counts", help="Comma-separated list of count values")
    
    # Update rank limits command
    update_limits_parser = subparsers.add_parser("update-limits", help="Update rank limits")
    update_limits_parser.add_argument("limits", help="Comma-separated list of limit values")
    
    # Parse arguments
    args = parser.parse_args()
    
    # Execute the appropriate command
    if args.command == "check":
        check_tables()
    elif args.command == "init":
        initialize_tables()
    elif args.command == "update-counts":
        update_rank_counts(args.counts)
    elif args.command == "update-limits":
        update_rank_limits(args.limits)
    else:
        # Default action is to show help
        parser.print_help()