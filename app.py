from flask import Flask, render_template, request, redirect, url_for, send_file, jsonify, g
import mysql.connector
import pandas as pd
import numpy as np
from datetime import datetime, timedelta
import json
import os
import tempfile
from itertools import combinations, cycle
import decimal
import random
import csv

app = Flask(__name__)

# Configure MySQL connection
db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'ga_f5_lotto'
}

# Function to load rank limit data
def load_rank_limits(conn=None, cursor=None):
    """
    Load rank limits from MySQL database. These limits specify 
    the maximum number of ranks to use for each frequency distribution.
    
    Parameters:
    conn (mysql.connector.connection): Database connection
    cursor (mysql.connector.cursor): Database cursor
    
    Returns:
    list: List of rank limits
    """
    try:
        # If connection and cursor are provided, use them
        if conn and cursor:
            # Query to get rank limits from ga_f5_rank_limits table
            query = "SELECT rank_limit FROM ga_f5_rank_limits ORDER BY rank_id"
            cursor.execute(query)
            results = cursor.fetchall()
            
            # Extract values from results
            if results:
                # If we're getting dictionary results
                if isinstance(results[0], dict):
                    return [int(row['rank_limit']) for row in results]
                # If we're getting tuple results
                else:
                    return [int(row[0]) for row in results]
            else:
                # No results found, use default values
                print("No rank limit data found in database, using defaults")
                return [1, 1, 2, 3, 2, 3, 1, 1]
        else:
            # No connection provided, try to open a new one
            try:
                temp_conn = mysql.connector.connect(**db_config)
                temp_cursor = temp_conn.cursor(dictionary=True)
                
                query = "SELECT rank_limit FROM ga_f5_rank_limits ORDER BY rank_id"
                temp_cursor.execute(query)
                results = temp_cursor.fetchall()
                
                # Extract values from results
                if results:
                    rank_limits = [int(row['rank_limit']) for row in results]
                    temp_cursor.close()
                    temp_conn.close()
                    return rank_limits
                else:
                    # No results found, use default values
                    temp_cursor.close()
                    temp_conn.close()
                    print("No rank limit data found in database, using defaults")
                    return [1, 1, 2, 3, 2, 3, 1, 1]
            except mysql.connector.Error as e:
                print(f"Database connection error when loading rank limits: {e}")
                # Use default values if database connection fails
                return [1, 1, 2, 3, 2, 3, 1, 1]
    except Exception as e:
        print(f"Error loading rank limits: {e}")
        # Default fallback values
        return [1, 1, 2, 3, 2, 3, 1, 1]

# Function to load rank count data
def load_rank_counts(conn=None, cursor=None):
    """
    Load rank counts from MySQL database. These values represent 
    the frequency distribution counts for each rank.
    
    Parameters:
    conn (mysql.connector.connection): Database connection
    cursor (mysql.connector.cursor): Database cursor
    
    Returns:
    list: List of rank counts
    """
    try:
        # If connection and cursor are provided, use them
        if conn and cursor:
            # Query to get rank counts from ga_f5_rank_counts table
            query = "SELECT rank_count FROM ga_f5_rank_counts ORDER BY rank_id"
            cursor.execute(query)
            results = cursor.fetchall()
            
            # Extract values from results
            if results:
                # If we're getting dictionary results
                if isinstance(results[0], dict):
                    return [int(row['rank_count']) for row in results]
                # If we're getting tuple results
                else:
                    return [int(row[0]) for row in results]
            else:
                # No results found, use default values
                print("No rank count data found in database, using defaults")
                return [5, 5, 2, 1, 3, 5, 3, 5, 5, 5, 5, 4, 2, 5, 5, 3, 5, 4, 0, 4, 5, 2, 4, 5, 3, 5, 5, 0, 4, 3, 2, 1, 4, 5, 3, 5, 1, 4, 3, 3, 2, 5]
        else:
            # No connection provided, try to open a new one
            try:
                temp_conn = mysql.connector.connect(**db_config)
                temp_cursor = temp_conn.cursor(dictionary=True)
                
                query = "SELECT rank_count FROM ga_f5_rank_counts ORDER BY rank_id"
                temp_cursor.execute(query)
                results = temp_cursor.fetchall()
                
                # Extract values from results
                if results:
                    rank_counts = [int(row['rank_count']) for row in results]
                    temp_cursor.close()
                    temp_conn.close()
                    return rank_counts
                else:
                    # No results found, use default values
                    temp_cursor.close()
                    temp_conn.close()
                    print("No rank count data found in database, using defaults")
                    return [5, 5, 2, 1, 3, 5, 3, 5, 5, 5, 5, 4, 2, 5, 5, 3, 5, 4, 0, 4, 5, 2, 4, 5, 3, 5, 5, 0, 4, 3, 2, 1, 4, 5, 3, 5, 1, 4, 3, 3, 2, 5]
            except mysql.connector.Error as e:
                print(f"Database connection error when loading rank counts: {e}")
                # Use default values if database connection fails
                return [5, 5, 2, 1, 3, 5, 3, 5, 5, 5, 5, 4, 2, 5, 5, 3, 5, 4, 0, 4, 5, 2, 4, 5, 3, 5, 5, 0, 4, 3, 2, 1, 4, 5, 3, 5, 1, 4, 3, 3, 2, 5]
    except Exception as e:
        print(f"Error loading rank counts: {e}")
        # Default fallback values
        return [5, 5, 2, 1, 3, 5, 3, 5, 5, 5, 5, 4, 2, 5, 5, 3, 5, 4, 0, 4, 5, 2, 4, 5, 3, 5, 5, 0, 4, 3, 2, 1, 4, 5, 3, 5, 1, 4, 3, 3, 2, 5]

class GeorgiaFantasy5Predictor:
    def __init__(self, config):
        """
        Initialize the Georgia Fantasy 5 Predictor
        
        Parameters:
        config (dict): MySQL database connection parameters
        """
        try:
            self.conn = mysql.connector.connect(**config)
            self.cursor = self.conn.cursor(dictionary=True)
            
            # Georgia Fantasy 5 specifics
            self.num_range = range(1, 43)  # Numbers 1-42
            self.nums_per_draw = 5
            
            # Filter settings (can be adjusted via web interface)
            self.max_seq2 = 1
            self.max_seq3 = 0
            self.max_mod_tot = 1
            self.sum_range = (70, 139)
            
            # Load rank data
            self.rank_limits = load_rank_limits(self.conn, self.cursor)
            self.rank_counts = load_rank_counts(self.conn, self.cursor)
            
            # Load column 1 distribution data
            self.col1_data = self.load_col1_data()
            
            # Create cyclers for round-robin col1 selection
            self.col1_cyclers = {}
            for key, values in self.col1_data.items():
                if values:  # Only create cyclers for non-empty lists
                    self.col1_cyclers[key] = cycle(values)
            
            # Load historical data
            self.load_historical_data()
        except mysql.connector.Error as e:
            print(f"Database connection error: {e}")
            # Create empty dataframe for historical draws if DB connection fails
            self.historical_draws = pd.DataFrame(columns=['date', 'b1', 'b2', 'b3', 'b4', 'b5', 'sum'])
            self.conn = None
            self.cursor = None
    
    def load_col1_data(self, file_path='draws_col1.csv'):
        """
        Load column 1 data from CSV file. This data maps sum, even, and odd counts
        to possible values for the first column (first ball).
        
        Parameters:
        file_path (str): Path to the CSV file
        
        Returns:
        dict: Mapping of (sum_range, even, odd) to list of possible col1 values
        """
        try:
            col1_data = {}
            with open(file_path, 'r') as f:
                csv_reader = csv.reader(f)
                # First row is headers
                headers = next(csv_reader)
                # Read data rows
                for row in csv_reader:
                    if len(row) >= 4:  # Ensure row has enough columns
                        sum_val = row[0].strip()
                        even_val = row[1].strip()
                        odd_val = row[2].strip()
                        col1_val = row[3].strip()
                        
                        # Skip rows with empty values
                        if not sum_val or not even_val or not odd_val or not col1_val:
                            continue
                        
                        # Convert values
                        try:
                            sum_int = int(sum_val)
                            col1_val = int(col1_val)
                            
                            # Create exact sum key for lookup (e.g., "110-110")
                            exact_key = (f"{sum_int}-{sum_int}", even_val, odd_val)
                            
                            # Add to dictionary with exact sum
                            if exact_key not in col1_data:
                                col1_data[exact_key] = []
                            if col1_val not in col1_data[exact_key]:
                                col1_data[exact_key].append(col1_val)
                                
                            # Also add to range-based key for backward compatibility
                            # Determine which range this sum falls into
                            if sum_int < 85:
                                range_key = ("<85", even_val, odd_val)
                            elif 85 <= sum_int <= 95:
                                range_key = ("85-95", even_val, odd_val)
                            elif 96 <= sum_int <= 105:
                                range_key = ("96-105", even_val, odd_val)
                            elif 106 <= sum_int <= 115:
                                range_key = ("106-115", even_val, odd_val)
                            else:
                                range_key = (">115", even_val, odd_val)
                            
                            # Add to range-based dictionary
                            if range_key not in col1_data:
                                col1_data[range_key] = []
                            if col1_val not in col1_data[range_key]:
                                col1_data[range_key].append(col1_val)
                                
                        except ValueError:
                            # Skip rows with non-integer sum or col1 values
                            continue
            
            # Create wildcard entries for range-based keys (for backward compatibility)
            sum_ranges = ["<85", "85-95", "96-105", "106-115", ">115"]
            
            for sum_range in sum_ranges:
                # Create wildcard entries for this sum range
                key = (sum_range, "*", "*")
                col1_data[key] = []
                
                # Collect all col1 values for this sum range
                for (s, e, o), values in col1_data.items():
                    if s == sum_range:
                        for v in values:
                            if v not in col1_data[key]:
                                col1_data[key].append(v)
            
            # Add even/odd wildcard entries
            for even in ["2", "3"]:
                for odd in ["2", "3"]:
                    key = ("*", even, odd)
                    col1_data[key] = []
                    
                    # Collect all col1 values for this even/odd distribution
                    for (s, e, o), values in col1_data.items():
                        if e == even and o == odd:
                            for v in values:
                                if v not in col1_data[key]:
                                    col1_data[key].append(v)
            
            # Add global wildcard
            key = ("*", "*", "*")
            col1_data[key] = list(range(1, 15))  # Default range for wildcard
            
            print(f"Loaded {len(col1_data)} unique combinations for col1 selection")
            return col1_data
        except Exception as e:
            print(f"Error loading col1 data: {e}")
            return {}
    
    def get_col1_candidates(self, total_sum, even_count, odd_count):
        """
        Get candidate values for column 1 based on sum, even, and odd counts
        
        Parameters:
        total_sum (int): Sum of the 5 numbers
        even_count (int): Count of even numbers
        odd_count (int): Count of odd numbers
        
        Returns:
        list: Candidate values for column 1
        """
        # Try exact sum match first
        exact_key = (f"{total_sum}-{total_sum}", str(even_count), str(odd_count))
        print(f"Looking up col1 for sum={total_sum}, even={even_count}, odd={odd_count}")
        print(f"Trying exact key: {exact_key}")
        if exact_key in self.col1_data and self.col1_data[exact_key]:
            print(f"Found values for exact key: {self.col1_data[exact_key]}")
            return self.col1_data[exact_key]
        
        # Fall back to range-based matching if exact match not found
        # Convert sum to a range string
        if total_sum < 85:
            sum_range = "<85"
        elif 85 <= total_sum <= 95:
            sum_range = "85-95"
        elif 96 <= total_sum <= 105:
            sum_range = "96-105"
        elif 106 <= total_sum <= 115:
            sum_range = "106-115"
        else:
            sum_range = ">115"
        
        # Convert even/odd to strings
        even_str = str(even_count)
        odd_str = str(odd_count)
        
        print(f"Falling back to range: {sum_range}")
        
        # Try to find matching candidates
        key = (sum_range, even_str, odd_str)
        print(f"Trying range key: {key}")
        if key in self.col1_data and self.col1_data[key]:
            print(f"Found values for range key: {self.col1_data[key]}")
            return self.col1_data[key]
        
        # If no exact match, try more generic keys
        for generic_key in [(sum_range, "*", "*"), ("*", even_str, odd_str), ("*", "*", "*")]:
            print(f"Trying generic key: {generic_key}")
            if generic_key in self.col1_data and self.col1_data[generic_key]:
                print(f"Found values for generic key: {self.col1_data[generic_key]}")
                return self.col1_data[generic_key]
        
        # Fallback to default range
        print("No matching keys found, using fallback range")
        return list(range(1, 20))
    
    def get_next_col1(self, total_sum, even_count, odd_count):
        """
        Get the next column 1 value using round-robin selection with improved rotation
        
        Parameters:
        total_sum (int): Sum of the 5 numbers
        even_count (int): Count of even numbers
        odd_count (int): Count of odd numbers
        
        Returns:
        int: Next column 1 value
        """
        # Try exact sum match first
        exact_sum_key = (f"{total_sum}-{total_sum}", str(even_count), str(odd_count))
        
        # Convert sum to a range string for fallback
        if total_sum < 85:
            sum_range = "<85"
        elif 85 <= total_sum <= 95:
            sum_range = "85-95"
        elif 96 <= total_sum <= 105:
            sum_range = "96-105"
        elif 106 <= total_sum <= 115:
            sum_range = "106-115"
        else:
            sum_range = ">115"
        
        # Convert even/odd to strings
        even_str = str(even_count)
        odd_str = str(odd_count)
        
        # Try different keys in priority order
        possible_keys = [
            exact_sum_key,                    # Exact sum match
            (sum_range, even_str, odd_str),   # Range-based match
            (sum_range, "*", "*"),            # Same sum range, any even/odd
            ("*", even_str, odd_str),         # Any sum, same even/odd
            ("*", "*", "*")                   # Complete wildcard
        ]
        
        # Add more variation with a combined approach
        # In get_next_col1 function
        use_random = False
        
        # First get all possible candidates across all relevant keys
        all_candidates = set()
        exact_match_candidates = []
        
        for key in possible_keys:
            if key in self.col1_data and self.col1_data[key]:
                if key == possible_keys[0]:  # Save exact match candidates
                    exact_match_candidates = self.col1_data[key].copy()
                all_candidates.update(self.col1_data[key])
        
        # Convert to sorted list for consistency
        all_candidates = sorted(list(all_candidates))
        
        # If we have no candidates at all, fall back to default range
        if not all_candidates:
            all_candidates = list(range(1, 15))
        
        # If we're using random selection (for variety)
        if use_random:
            # Prefer exact matches if available, otherwise use all candidates
            if exact_match_candidates:
                return random.choice(exact_match_candidates)
            else:
                return random.choice(all_candidates)
        
        # Otherwise use cycler-based selection (round-robin)
        # Try to get the next col1 value from the cycler for the exact match first
        exact_key = possible_keys[0]
        
        # If we have a cycler for this key, use it
        if exact_key in self.col1_cyclers and exact_match_candidates:
            return next(self.col1_cyclers[exact_key])
        
        # If not, create a new cycler with the available candidates from all relevant sources
        if all_candidates:
            # Use a generic key that combines all options
            generic_key = "combined_cycler"
            
            # Create a new cycler if it doesn't exist or if we've used all values before
            if generic_key not in self.col1_cyclers:
                # Shuffle for more randomness in the cycle order
                random.shuffle(all_candidates)
                self.col1_cyclers[generic_key] = cycle(all_candidates)
            
            # Get the next value from the cycler
            return next(self.col1_cyclers[generic_key])
        
        # Ultimate fallback
        return random.randint(1, 15)
    
    def load_historical_data(self):
        """Load historical draws from MySQL database"""
        if not self.conn or not self.cursor:
            print("Database connection not available.")
            self.historical_draws = pd.DataFrame(columns=['date', 'b1', 'b2', 'b3', 'b4', 'b5', 'sum'])
            return
            
        try:
            query = "SELECT * FROM ga_f5_draws ORDER BY date DESC"
            self.cursor.execute(query)
            results = self.cursor.fetchall()
            
            # Process any decimal values
            processed_results = []
            for row in results:
                processed_row = {}
                for key, value in row.items():
                    if isinstance(value, decimal.Decimal):
                        processed_row[key] = float(value)
                    else:
                        processed_row[key] = value
                processed_results.append(processed_row)
            
            # Convert to DataFrame for easier manipulation
            self.historical_draws = pd.DataFrame(processed_results)
            print(f"Loaded {len(self.historical_draws)} historical draws")
        except Exception as e:
            print(f"Error loading historical data: {e}")
            self.historical_draws = pd.DataFrame(columns=['date', 'b1', 'b2', 'b3', 'b4', 'b5', 'sum'])
    
    def get_last_n_draws(self, n=10):
        """Get the last n draws"""
        if self.historical_draws.empty:
            return pd.DataFrame(columns=['date', 'b1', 'b2', 'b3', 'b4', 'b5', 'sum'])
        return self.historical_draws.head(n)
    
    def count_sequential_numbers(self, numbers):
        """Count sequences of 2 and 3 consecutive numbers"""
        seq2 = 0
        seq3 = 0
        
        # Sort the numbers
        sorted_nums = sorted(numbers)
        
        # Check for sequences of 2
        for i in range(len(sorted_nums)-1):
            if sorted_nums[i+1] - sorted_nums[i] == 1:
                seq2 += 1
                
        # Check for sequences of 3
        for i in range(len(sorted_nums)-2):
            if sorted_nums[i+1] - sorted_nums[i] == 1 and sorted_nums[i+2] - sorted_nums[i+1] == 1:
                seq3 += 1
                
        return seq2, seq3
    
    def calculate_modular_total(self, numbers):
        """Calculate modular total - counts how many numbers share the same remainder when divided by 10"""
        mod_counts = [0] * 10
        
        for num in numbers:
            mod_counts[num % 10] += 1
        
        # Sum the count of duplicates
        mod_total = sum(max(0, count-1) for count in mod_counts)
        
        # Count moduli with more than 2 numbers (used in filtering)
        mod_x = sum(1 for count in mod_counts if count > 2)
        
        return mod_total, mod_x
    
    def calculate_decade_distribution(self, numbers):
        """Calculate how many numbers fall in each decade (1-9, 10-19, 20-29, 30-39)"""
        d0, d1, d2, d3 = 0, 0, 0, 0
        
        for num in numbers:
            if 1 <= num <= 9:
                d0 += 1
            elif 10 <= num <= 19:
                d1 += 1
            elif 20 <= num <= 29:
                d2 += 1
            elif 30 <= num <= 39:
                d3 += 1
            else:                   ###250626 ###
                d4 += 1             ###250626 ###
        
        return d0, d1, d2, d3, d4   ###250626 ###
    
    def calculate_duplicates_from_previous(self, numbers, max_draws=10):
        """
        Calculate duplicates from previous draws
        
        Parameters:
        numbers (list): The combination to check
        max_draws (int): Number of previous draws to check
        
        Returns:
        tuple: (individual_dup_counts, cumulative_dup_counts)
        """
        dup_counts = []
        
        last_draws = self.get_last_n_draws(max_draws)
        if last_draws.empty:
            return [0] * max_draws, [0] * max_draws
        
        # Individual draw duplicate counts
        for i, row in last_draws.iterrows():
            previous_draw = [int(row['b1']), int(row['b2']), int(row['b3']), int(row['b4']), int(row['b5'])]
            dup_count = len(set(numbers).intersection(set(previous_draw)))
            dup_counts.append(dup_count)
        
        # Calculate cumulative duplicates
        cumulative_dups = []
        for i in range(1, max_draws + 1):
            # All numbers from the last i draws
            all_previous_nums = []
            for j in range(i):
                if j < len(last_draws):
                    row = last_draws.iloc[j]
                    all_previous_nums.extend([
                        int(row['b1']), int(row['b2']), int(row['b3']), 
                        int(row['b4']), int(row['b5'])
                    ])
            
            # Count unique duplicates
            unique_prevs = set(all_previous_nums)
            dup_count = len(set(numbers).intersection(unique_prevs))
            cumulative_dups.append(dup_count)
        
        return dup_counts, cumulative_dups
    
    def filter_combination(self, combination):
        """
        Apply all filters to a combination
        
        Parameters:
        combination (list): The 5-number combination to filter
        
        Returns:
        bool: True if combination passes all filters, False otherwise
        """
        # Basic checks
        if len(combination) != self.nums_per_draw:
            return False
            
        # Even/Odd distribution
        even_count = sum(1 for num in combination if num % 2 == 0)
        odd_count = self.nums_per_draw - even_count
        
        if not (2 <= even_count <= 3 and 2 <= odd_count <= 3):
            return False
            
        # Sequential numbers check
        seq2, seq3 = self.count_sequential_numbers(combination)
        if seq2 > self.max_seq2 or seq3 > self.max_seq3:
            return False
            
        # Modular totals check
        mod_total, mod_x = self.calculate_modular_total(combination)
        if mod_total > self.max_mod_tot or mod_x > 0:
            return False
            
        # Decade distribution check
        d0, d1, d2, d3, d4 = self.calculate_decade_distribution(combination)
        if d0 > 2 or d1 > 2 or d2 > 2 or d3 > 2 or d4 > 2:  ### 250626 ###
            return False
            
        # Duplicate check from previous draws
        dup_counts, cumulative_dups = self.calculate_duplicates_from_previous(combination)
        
        # Your filtering rules:
        # - No more than 1 number from most recent draw
        if len(dup_counts) > 0 and dup_counts[0] > 1:
            return False
            
        # - No more than 2 numbers from cumulative 2 most recent draws
        if len(cumulative_dups) > 1 and cumulative_dups[1] > 2:
            return False
            
        # - No more than 3 numbers from cumulative 3 most recent draws
        if len(cumulative_dups) > 2 and cumulative_dups[2] > 3:
            return False
            
        # Sum range check
        total_sum = sum(combination)
        min_sum, max_sum = self.sum_range
        if not (min_sum <= total_sum <= max_sum):
            return False
        
        # All filters passed
        return True
    
    def generate_predictions(self, count=10, specific_even_odd=None):
        """
        Generate top predictions with improved selection for position 1 based on historical data
        
        Parameters:
        count (int): Number of predictions to generate
        specific_even_odd (tuple): Optional tuple of (even_count, odd_count) to use
        
        Returns:
        list: List of prediction dictionaries with combinations and scores
        """
        print(f"Generating {count} predictions for Georgia Fantasy 5...")
        
        # Check if database is available
        if self.historical_draws.empty:
            # Generate some random combinations if no historical data
            filtered_combinations = []
            for _ in range(count * 5):  # Generate more than needed to ensure we have enough
                combo = sorted(random.sample(range(1, 43), 5))
                stats = self._calculate_stats(combo)
                filtered_combinations.append({
                    'combination': combo,
                    'score': random.uniform(40, 90),  # Random score
                    'sum': sum(combo),
                    'stats': stats
                })
            # Sort and return
            sorted_combinations = sorted(filtered_combinations, key=lambda x: x['score'], reverse=True)
            return sorted_combinations[:count]
        
        # Get the latest draw date for tracking
        latest_date = self.historical_draws.iloc[0]['date'] if not self.historical_draws.empty else None
        
        # Calculate typical distribution for initial filtering
        if specific_even_odd:
            even_odd_distributions = [specific_even_odd]  # Use specified distribution
        else:
            # Use default distributions if none specified
            even_odd_distributions = [(2, 3), (3, 2)]  # (even, odd) combinations
        
        # Get position 3 candidates from frequency
        pos3_candidates = []
        if not self.historical_draws.empty and self.conn and self.cursor:
            try:
                query = """
                SELECT b3, COUNT(*) as freq FROM ga_f5_draws 
                WHERE date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY b3 ORDER BY freq DESC LIMIT 15
                """
                self.cursor.execute(query)
                pos3_results = self.cursor.fetchall()
                
                for row in pos3_results:
                    pos3_candidates.append(int(row['b3']))
            except Exception as e:
                print(f"Error getting position 3 candidates: {e}")
        
        # Fill in any missing candidates for position 3 if needed
        all_nums = list(range(1, 43))
        if len(pos3_candidates) < 10:
            pos3_candidates.extend([n for n in all_nums if n not in pos3_candidates][:10-len(pos3_candidates)])
        
        # Track all filtered combinations
        filtered_combinations = []
        
        # Track combinations by their col1 value
        col1_combinations = {}
        
        # For each sum range, try to generate combinations
        min_sum, max_sum = self.sum_range
        
        # Special case for exact sum
        if min_sum == max_sum:
            sum_ranges = [(min_sum, max_sum)]
        else:
            sum_ranges = [
                (min_sum, min_sum + 10),
                (min_sum + 10, min_sum + 20),
                (min_sum + 20, min_sum + 30),
                (min_sum + 30, max_sum)
            ]
        
        # Track used col1 values for each key to ensure round-robin
        used_col1_values = {}
        
        # Maximum combinations to generate per col1 value
        max_per_col1 = 20
        max_attempts = 10000
        attempts = 0
        
        for sum_min, sum_max in sum_ranges:
            # Use the midpoint of this range for initial selection
            target_sum = (sum_min + sum_max) // 2
            
            # Try different even/odd distributions
            for even_count, odd_count in even_odd_distributions:
                # Get column 1 candidates based on sum and even/odd distribution
                col1_candidates = self.get_col1_candidates(target_sum, even_count, odd_count)
                
                # If no candidates from data, use wider range
                if not col1_candidates:
                    col1_candidates = list(range(1, 15))
                
                # Ensure we try each col1 value explicitly
                for forced_col1 in col1_candidates:
                    # Initialize if this col1 isn't tracked yet
                    if forced_col1 not in col1_combinations:
                        col1_combinations[forced_col1] = []
                    
                    # Don't generate too many combinations for a single col1
                    if len(col1_combinations[forced_col1]) >= max_per_col1:
                        continue
                    
                    # Get a wide range of pos5 candidates
                    pos5_candidates = list(range(20, 40))  # Position 5 can be 20-39
                    random.shuffle(pos5_candidates)  # Thoroughly randomize
                    
                    # Try different pos5 values to ensure diversity
                    for pos5 in pos5_candidates[:8]:  # Try several different pos5 values
                        # Skip invalid ranges
                        if pos5 <= forced_col1 + 2:
                            continue
                        
                        # Try each position 3 candidate
                        random.shuffle(pos3_candidates)  # Randomize order
                        for pos3 in pos3_candidates[:5]:  # Try top 5 position 3 candidates
                            # Skip if not ascending
                            if not (forced_col1 < pos3 < pos5):
                                continue
                            
                            # Try all possible positions 2 and 4 to complete the combination
                            for pos2 in range(forced_col1 + 1, pos3):
                                for pos4 in range(pos3 + 1, pos5):
                                    # Create the full combination
                                    combo = sorted([forced_col1, pos2, pos3, pos4, pos5])
                                    
                                    attempts += 1
                                    if attempts > max_attempts:
                                        break
                                    
                                    # Check if it passes all filters
                                    if self.filter_combination(combo):
                                        # Calculate stats and score
                                        stats = self._calculate_stats(combo)
                                        score = self._calculate_score(combo, stats)
                                        
                                        new_combo = {
                                            'combination': combo,
                                            'score': score,
                                            'sum': sum(combo),
                                            'stats': stats
                                        }
                                        
                                        filtered_combinations.append(new_combo)
                                        col1_combinations[forced_col1].append(new_combo)
                                        
                                        # If we have enough combinations for this col1, move on
                                        if len(col1_combinations[forced_col1]) >= max_per_col1:
                                            break
                                if len(col1_combinations[forced_col1]) >= max_per_col1 or attempts > max_attempts:
                                    break
                            if len(col1_combinations[forced_col1]) >= max_per_col1 or attempts > max_attempts:
                                break
                        if len(col1_combinations[forced_col1]) >= max_per_col1 or attempts > max_attempts:
                            break
        
        # If we couldn't generate any combinations, return random ones
        if not filtered_combinations:
            print("Could not generate any filtered combinations. Using random combinations.")
            random_combinations = []
            for _ in range(count * 2):  # Generate more than needed
                combo = sorted(random.sample(range(1, 43), 5))
                if self.filter_combination(combo):
                    stats = self._calculate_stats(combo)
                    score = self._calculate_score(combo, stats)
                    random_combinations.append({
                        'combination': combo,
                        'score': score,
                        'sum': sum(combo),
                        'stats': stats
                    })
            
            sorted_random = sorted(random_combinations, key=lambda x: x['score'], reverse=True)
            return sorted_random[:count]
        
        # Track if we have enough diversity
        unique_col1_values = set(combo['combination'][0] for combo in filtered_combinations)
        print(f"Generated combinations with {len(unique_col1_values)} different col1 values")
        
        # If we don't have enough col1 diversity, try harder
        if len(unique_col1_values) < min(5, count) and col1_candidates and len(col1_candidates) >= min(5, count):
            print("Insufficient col1 diversity. Trying fallback method...")
            
            # For each col1 value in candidates that isn't represented yet
            for col1_value in col1_candidates:
                if col1_value not in unique_col1_values and len(filtered_combinations) < count * 3:
                    
                    # Try to generate a valid combination with this col1
                    for _ in range(50):  # Try up to 50 times for each missing col1
                        # Generate a random combination with this col1
                        pos2 = random.randint(col1_value + 1, 25)
                        pos3 = random.randint(pos2 + 1, 30)
                        pos4 = random.randint(pos3 + 1, 35)
                        pos5 = random.randint(pos4 + 1, 39)
                        
                        combo = [col1_value, pos2, pos3, pos4, pos5]
                        
                        # Check if this combination meets our criteria
                        if self.filter_combination(combo):
                            stats = self._calculate_stats(combo)
                            score = self._calculate_score(combo, stats)
                            
                            new_combo = {
                                'combination': combo,
                                'score': score,
                                'sum': sum(combo),
                                'stats': stats
                            }
                            
                            filtered_combinations.append(new_combo)
                            if col1_value not in col1_combinations:
                                col1_combinations[col1_value] = []
                            col1_combinations[col1_value].append(new_combo)
                            unique_col1_values.add(col1_value)
                            break
        
        # Final diversity selection
        # First select the best combination for each col1 value
        diverse_combinations = []
        
        # Sort col1 values by their best score
        col1_best_scores = {}
        for col1, combos in col1_combinations.items():
            if combos:  # Skip empty groups
                best_combo = max(combos, key=lambda x: x['score'])
                col1_best_scores[col1] = best_combo['score']
        
        # Check if we have any scores to sort
        if not col1_best_scores:
            print("No valid combinations found. Using random combinations.")
            random_combinations = []
            for _ in range(count * 2):
                combo = sorted(random.sample(range(1, 43), 5))
                if self.filter_combination(combo):
                    stats = self._calculate_stats(combo)
                    score = self._calculate_score(combo, stats)
                    random_combinations.append({
                        'combination': combo,
                        'score': score,
                        'sum': sum(combo),
                        'stats': stats
                    })
            
            sorted_random = sorted(random_combinations, key=lambda x: x['score'], reverse=True)
            return sorted_random[:count]
        
        # Take combinations in order of best score, one from each col1 group
        for col1 in sorted(col1_best_scores.keys(), key=lambda x: col1_best_scores[x], reverse=True):
            if len(diverse_combinations) >= count:
                break
                
            # Get best combination from this col1 group
            best_combo = max(col1_combinations[col1], key=lambda x: x['score'])
            diverse_combinations.append(best_combo)
        
        # If we don't have enough combinations yet, add more
        if diverse_combinations and len(diverse_combinations) < count:
            # Track which col5 values we've used
            used_col5 = set(combo['combination'][-1] for combo in diverse_combinations)
            
            # Sort remaining combinations by score
            remaining = sorted(
                [c for c in filtered_combinations if c not in diverse_combinations],
                key=lambda x: x['score'],
                reverse=True
            )
            
            # First prioritize col5 diversity
            for combo in remaining:
                if len(diverse_combinations) >= count:
                    break
                    
                col5 = combo['combination'][-1]
                if col5 not in used_col5:
                    diverse_combinations.append(combo)
                    used_col5.add(col5)
            
            # Then add any remaining high scorers
            for combo in remaining:
                    if len(diverse_combinations) >= count:
                        break
                        
                    if combo not in diverse_combinations:
                        diverse_combinations.append(combo)
        
        # Final sorting by score
        if diverse_combinations:
            final_combinations = sorted(diverse_combinations, key=lambda x: x['score'], reverse=True)
            return final_combinations[:count]
        else:
            # Ultimate fallback if we somehow still have no combinations
            print("No valid combinations found after all attempts. Using completely random combinations.")
            random_combinations = []
            for _ in range(count):
                combo = sorted(random.sample(range(1, 43), 5))
                stats = self._calculate_stats(combo)
                random_combinations.append({
                    'combination': combo,
                    'score': random.uniform(40, 90),
                    'sum': sum(combo),
                    'stats': stats
                })
            return random_combinations
    
    def _calculate_stats(self, numbers):
        """Calculate statistical measures for a combination"""
        numbers_array = np.array(numbers)
        
        # Basic stats
        mean = np.mean(numbers_array)
        median = np.median(numbers_array)
        
        # Even/Odd distribution
        even_count = sum(1 for num in numbers if num % 2 == 0)
        odd_count = len(numbers) - even_count
        
        # Decade distribution
        d0, d1, d2, d3, d4 = self.calculate_decade_distribution(numbers)    ### 250626 ###
        
        # Sequential checks
        seq2, seq3 = self.count_sequential_numbers(numbers)
        
        # Modular analysis
        mod_total, mod_x = self.calculate_modular_total(numbers)
        
        # Apply rank analysis to the numbers
        rank_analysis = {}
        for i, num in enumerate(numbers):
            if i < len(self.rank_limits) and i < len(self.rank_counts):
                rank_analysis[f'rank_{i}_limit'] = self.rank_limits[i]
                rank_analysis[f'rank_{i}_count'] = self.rank_counts[i]
        
        stats = {
            'mean': mean,
            'median': median,
            'even': even_count,
            'odd': odd_count,
            'd0': d0,
            'd1': d1,
            'd2': d2,
            'd3': d3,
            'd4': d4,
            'seq2': seq2,
            'seq3': seq3,
            'mod_total': mod_total,
            'mod_x': mod_x,
            'sum': sum(numbers),
            'rank_analysis': rank_analysis
        }
        
        return stats
    
    def _calculate_score(self, combination, stats):
        """
        Calculate a composite score for a combination
        
        Parameters:
        combination (list): The 5-number combination
        stats (dict): Statistical measures for the combination
        
        Returns:
        float: A score between a0-100
        """
        # Get frequency of each number in last 30 days
        frequency = {}
        
        if self.conn and self.cursor and not self.historical_draws.empty:
            try:
                query = """
                SELECT ball, COUNT(*) as freq FROM (
                    SELECT b1 as ball FROM ga_f5_draws WHERE date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                    UNION ALL SELECT b2 as ball FROM ga_f5_draws WHERE date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                    UNION ALL SELECT b3 as ball FROM ga_f5_draws WHERE date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                    UNION ALL SELECT b4 as ball FROM ga_f5_draws WHERE date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                    UNION ALL SELECT b5 as ball FROM ga_f5_draws WHERE date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                ) as all_balls
                WHERE ball IN (%s)
                GROUP BY ball
                """ % ','.join(str(num) for num in combination)
                
                self.cursor.execute(query)
                freq_results = self.cursor.fetchall()
                
                # Convert to a dictionary for easier lookup
                for row in freq_results:
                    # Convert any Decimal values to float
                    ball = int(row['ball'])
                    if isinstance(row['freq'], decimal.Decimal):
                        frequency[ball] = float(row['freq'])
                    else:
                        frequency[ball] = row['freq']
            except Exception as e:
                print(f"Error getting number frequencies: {e}")
        
        # Fill in any missing numbers with a default frequency of 0
        for num in combination:
            if num not in frequency:
                frequency[num] = 0
        
        # Calculate frequency score (0-100)
        max_possible_freq = 10  # Assuming a number could appear in all 5 positions for 30 days = 150 times
        freq_score = sum(min(frequency.get(num, 0), max_possible_freq) for num in combination) / (len(combination) * max_possible_freq) * 100
        
        # Balance score for even/odd distribution (0-100)
        even_count = stats['even']
        even_odd_balance = 100 - abs(even_count - (len(combination)/2)) * 40
        
        # Decade distribution score (0-100)
        decade_balance = 100
        for count in [stats['d0'], stats['d1'], stats['d2'], stats['d3'], stats['d4']]:
            if count > 2:
                decade_balance -= 25
            elif count == 0:
                decade_balance -= 15
        
        # Sequential number penalty
        seq_penalty = stats['seq2'] * 5 + stats['seq3'] * 15
        
        # Sum score - prefer combinations with sums close to average winning sum
        avg_sum = 100  # Default if DB query fails
        
        if self.conn and self.cursor and not self.historical_draws.empty:
            try:
                query = "SELECT AVG(sum) as avg_sum FROM ga_f5_draws WHERE date >= DATE_SUB(NOW(), INTERVAL 365 DAY)"
                self.cursor.execute(query)
                result = self.cursor.fetchone()
                
                # Handle Decimal type for avg_sum
                if result and result['avg_sum']:
                    if isinstance(result['avg_sum'], decimal.Decimal):
                        avg_sum = float(result['avg_sum'])
                    else:
                        avg_sum = result['avg_sum']
            except Exception as e:
                print(f"Error getting average sum: {e}")
        
        sum_score = 100 - min(abs(stats['sum'] - avg_sum), 30) * 3.33
        
        # Rank score - evaluate the combination based on rank data
        rank_score = 0
        total_rank_points = 0
        
        # Check each number against its rank limit and count
        for i, num in enumerate(combination):
            if i < len(self.rank_limits) and i < len(self.rank_counts):
                rank_limit = self.rank_limits[i]
                rank_count = self.rank_counts[i]
                
                # Skip if rank count is 0 (no data for this rank)
                if rank_count == 0:
                    continue
                    
                # Simple scoring: award points if the number satisfies the rank constraints
                if rank_limit > 0 and rank_count <= rank_limit:
                    rank_score += 20  # Award 20 points per satisfied rank constraint
                
                total_rank_points += 20
        
        # Normalize rank score to 0-100 range
        if total_rank_points > 0:
            rank_score = (rank_score / total_rank_points) * 100
        else:
            rank_score = 50  # Default middle score if no rank constraints were evaluated
        
        # Check if first number (position 1) is from col1_data
        col1_bonus = 0
        if len(combination) > 0:
            # Get sum range
            total_sum = sum(combination)
            
            # Get sum range string
            if total_sum < 85:
                sum_range = "<85"
            elif 85 <= total_sum <= 95:
                sum_range = "85-95"
            elif 96 <= total_sum <= 105:
                sum_range = "96-105"
            elif 106 <= total_sum <= 115:
                sum_range = "106-115"
            else:
                sum_range = ">115"
            
            # Get even/odd counts
            even_count = stats['even']
            odd_count = stats['odd']
            
            # Convert to strings
            even_str = str(even_count)
            odd_str = str(odd_count)
            
            # Check if the first number is in our col1 data for this distribution
            key = (sum_range, even_str, odd_str)
            pos1 = combination[0]
            
            if key in self.col1_data and pos1 in self.col1_data[key]:
                col1_bonus = 15  # Bonus points for having first position from historical data
            elif (sum_range, "*", "*") in self.col1_data and pos1 in self.col1_data[(sum_range, "*", "*")]:
                col1_bonus = 10  # Smaller bonus for generic sum range match
            elif ("*", even_str, odd_str) in self.col1_data and pos1 in self.col1_data[("*", even_str, odd_str)]:
                col1_bonus = 10  # Smaller bonus for generic even/odd match
        
        # Calculate final score with weighted components
        weights = {
            'frequency': 0.20,    # 20% weight for recent frequency
            'even_odd': 0.15,     # 15% weight for even/odd balance
            'decade': 0.15,       # 15% weight for decade distribution
            'sequential': 0.10,   # 10% weight for sequential penalty
            'sum': 0.15,          # 15% weight for sum score
            'rank': 0.15,         # 15% weight for rank analysis
            'col1': 0.10          # 10% weight for col1 bonus
        }
        
        # Convert sequential penalty to a score (100 = no penalty)
        seq_score = max(0, 100 - seq_penalty)
        
        # Normalize col1 bonus to 0-100 scale
        col1_score = col1_bonus * (100 / 15) if col1_bonus > 0 else 0
        
        # Calculate weighted score
        final_score = (
            weights['frequency'] * freq_score +
            weights['even_odd'] * even_odd_balance +
            weights['decade'] * decade_balance +
            weights['sequential'] * seq_score +
            weights['sum'] * sum_score +
            weights['rank'] * rank_score +
            weights['col1'] * col1_score
        )
        
        return final_score
        
    def export_predictions_to_csv(self, predictions, filename='fantasy5_predictions.csv'):
        """
        Export predictions to a CSV file
        
        Parameters:
        predictions (list): List of prediction dictionaries
        filename (str): Name of the CSV file to create
        
        Returns:
        str: Path to the created CSV file
        """
        # Create a temporary file
        temp_dir = tempfile.gettempdir()
        file_path = os.path.join(temp_dir, filename)
        
        with open(file_path, 'w', newline='') as csvfile:
            # Define CSV fields
            fieldnames = ['Combination', 'Score', 'Sum', 'Even', 'Odd', 'Mean', 'Median', 
                          'D0', 'D1', 'D2', 'D3', 'D4', 'Seq2', 'Seq3', 'ModTotal']
            
            writer = csv.DictWriter(csvfile, fieldnames=fieldnames)
            writer.writeheader()
            
            for pred in predictions:
                combo_str = '-'.join(str(num) for num in pred['combination'])
                stats = pred['stats']
                
                row = {
                    'Combination': combo_str,
                    'Score': round(pred['score'], 2),
                    'Sum': stats['sum'],
                    'Even': stats['even'],
                    'Odd': stats['odd'],
                    'Mean': round(stats['mean'], 2),
                    'Median': round(stats['median'], 2),
                    'D0': stats['d0'],
                    'D1': stats['d1'],
                    'D2': stats['d2'],
                    'D3': stats['d3'],
                    'D4': stats['d4'],
                    'Seq2': stats['seq2'],
                    'Seq3': stats['seq3'],
                    'ModTotal': stats['mod_total']
                }
                
                writer.writerow(row)
        
        return file_path
        
        # Setup Flask routes
@app.route('/')
def index():
    """Render the main page with previous draws"""
    try:
        # Use get_predictor() instead of creating a new instance
        predictor = get_predictor()
        
        # Get the last 10 draws for display
        last_draws = predictor.get_last_n_draws(10)
        
        # Convert to list for JSON serialization, handle datetime objects
        last_draws_list = []
        for _, row in last_draws.iterrows():
            draw_dict = {}
            for key, value in row.items():
                if key in ['b1', 'b2', 'b3', 'b4', 'b5', 'sum']:
                    draw_dict[key] = int(value) if value is not None else None
                elif key == 'date':
                    draw_dict[key] = value.strftime('%Y-%m-%d') if value is not None else None
                else:
                    draw_dict[key] = value
            last_draws_list.append(draw_dict)
        
        return render_template(
            'index.html',
            last_draws=last_draws_list,
            filters={
                'max_seq2': predictor.max_seq2,
                'max_seq3': predictor.max_seq3,
                'max_mod_tot': predictor.max_mod_tot,
                'min_sum': predictor.sum_range[0],
                'max_sum': predictor.sum_range[1]
            }
        )
    except Exception as e:
        print(f"Error rendering index: {e}")
        # Return a simple response if template is missing
        return f"""
        <html>
        <head><title>Georgia Fantasy 5 Predictor</title></head>
        <body>
            <h1>Georgia Fantasy 5 Predictor</h1>
            <p>Error rendering index: {e}</p>
            <p>Please make sure templates are correctly installed.</p>
            <a href="/generate">Generate Predictions</a>
        </body>
        </html>
        """

# Create a global predictor instance at the module level
global_predictor = None 
predictor_init_count = 0  # Add a counter to track initializations

def get_predictor():
    """Get or create the predictor instance"""
    global global_predictor
    global predictor_init_count
    
    if global_predictor is None:
        global_predictor = GeorgiaFantasy5Predictor(db_config)
        predictor_init_count += 1
        print(f"Initialized predictor instance #{predictor_init_count}")
    else:
        print(f"Reusing existing predictor instance #{predictor_init_count}")
    
    return global_predictor

@app.route('/generate', methods=['GET', 'POST'])
def generate():
    try:
        # Get the predictor from application context
        predictor = get_predictor()
        
        # Process form inputs if POST request
        if request.method == 'POST':
            # Get parameters from form
            count = int(request.form.get('count', 10))
            
            # Debug the form data
            print("Form data received:")
            for key, value in request.form.items():
                print(f"  {key}: {value}")
            
            # Set filter parameters if provided
            if 'max_seq2' in request.form:
                predictor.max_seq2 = int(request.form.get('max_seq2'))
            
            if 'max_seq3' in request.form:
                predictor.max_seq3 = int(request.form.get('max_seq3'))
            
            if 'max_mod_tot' in request.form:
                predictor.max_mod_tot = int(request.form.get('max_mod_tot'))
            
            # Get min_sum and max_sum from the form
            if 'min_sum' in request.form and 'max_sum' in request.form:
                min_sum = int(request.form.get('min_sum'))
                max_sum = int(request.form.get('max_sum'))
                predictor.sum_range = (min_sum, max_sum)
                
                # Generate predictions with specific parameters if needed
                if min_sum == max_sum:
                    # For exact sum, use specific even/odd distribution
                    # Default to (3, 2) if not specified
                    even_count = int(request.form.get('even_count', 3))
                    odd_count = int(request.form.get('odd_count', 2))
                    predictions = predictor.generate_predictions(count, specific_even_odd=(even_count, odd_count))
                else:
                    # Use default behavior for ranges
                    predictions = predictor.generate_predictions(count)
            else:
                # Use default behavior if no sum range specified
                predictions = predictor.generate_predictions(count)
        else:
            # Use default count for GET requests
            count = 10
            predictions = predictor.generate_predictions(count)
        
        # Get the last 10 draws for display
        last_draws = predictor.get_last_n_draws(10)
        
        # Convert to list for JSON serialization, handle datetime objects
        last_draws_list = []
        for _, row in last_draws.iterrows():
            draw_dict = {}
            for key, value in row.items():
                if key in ['b1', 'b2', 'b3', 'b4', 'b5', 'sum']:
                    draw_dict[key] = int(value) if value is not None else None
                elif key == 'date':
                    draw_dict[key] = value.strftime('%Y-%m-%d') if value is not None else None
                else:
                    draw_dict[key] = value
            last_draws_list.append(draw_dict)
        
        # Try rendering the template
        try:
            return render_template(
                'predictions.html',
                predictions=predictions,
                last_draws=last_draws_list,
                filters={
                    'max_seq2': predictor.max_seq2,
                    'max_seq3': predictor.max_seq3,
                    'max_mod_tot': predictor.max_mod_tot,
                    'min_sum': predictor.sum_range[0],
                    'max_sum': predictor.sum_range[1]
                }
            )
        except Exception as e:
            # Fallback to a simple HTML response if template is missing
            result_html = """
            <html>
            <head><title>Georgia Fantasy 5 Predictions</title></head>
            <body>
                <h1>Georgia Fantasy 5 Predictions</h1>
                <p>Template error: {}</p>
                <h2>Predictions:</h2>
                <table border="1">
                    <tr>
                        <th>Combination</th>
                        <th>Score</th>
                        <th>Sum</th>
                    </tr>
            """.format(str(e))
            
            for pred in predictions:
                combo_str = '-'.join(str(num) for num in pred['combination'])
                result_html += f"""
                <tr>
                    <td>{combo_str}</td>
                    <td>{round(pred['score'], 2)}</td>
                    <td>{pred['stats']['sum']}</td>
                </tr>
                """
            
            result_html += """
                </table>
                <h2>Previous Draws:</h2>
                <table border="1">
                    <tr>
                        <th>Date</th>
                        <th>Numbers</th>
                        <th>Sum</th>
                    </tr>
            """
            
            for draw in last_draws_list:
                numbers = f"{draw.get('b1', '-')}-{draw.get('b2', '-')}-{draw.get('b3', '-')}-{draw.get('b4', '-')}-{draw.get('b5', '-')}"
                result_html += f"""
                <tr>
                    <td>{draw.get('date', 'Unknown')}</td>
                    <td>{numbers}</td>
                    <td>{draw.get('sum', '-')}</td>
                </tr>
                """
            
            result_html += """
                </table>
                <br>
                <a href="/">Back to Home</a>
            </body>
            </html>
            """
            
            return result_html
    except Exception as e:
        print(f"Error generating predictions: {e}")
        return f"""
        <html>
        <head><title>Error</title></head>
        <body>
            <h1>Error Generating Predictions</h1>
            <p>{str(e)}</p>
            <a href="/">Back to Home</a>
        </body>
        </html>
        """


@app.route('/predict', methods=['POST'])
def predict():
    """Legacy endpoint - redirects to generate for compatibility"""
    return generate()


@app.route('/export', methods=['POST'])
def export():
    """Export predictions to CSV"""
    try:
        # Get prediction data from form
        predictions_json = request.form.get('predictions', '[]')
        
        try:
            # Parse the JSON data
            prediction_data = json.loads(predictions_json)
        except json.JSONDecodeError as e:
            print(f"JSON decode error: {e}")
            print(f"Received data: {predictions_json[:100]}...")  # Print first 100 chars for debugging
            return f"""
            <html>
            <head><title>Export Error</title></head>
            <body>
                <h1>Error Exporting Predictions</h1>
                <p>Invalid JSON data: {str(e)}</p>
                <p>Please try generating new predictions.</p>
                <a href="/">Back to Home</a>
            </body>
            </html>
            """
            
        # Use get_predictor() instead of creating a new instance
        predictor = get_predictor()
        
        # Export to CSV
        file_path = predictor.export_predictions_to_csv(prediction_data)
        
        # Return the file as a download
        return send_file(file_path, as_attachment=True, download_name='fantasy5_predictions.csv')
    except Exception as e:
        print(f"Error exporting predictions: {str(e)}")
        return f"""
        <html>
        <head><title>Export Error</title></head>
        <body>
            <h1>Error Exporting Predictions</h1>
            <p>{str(e)}</p>
            <a href="/">Back to Home</a>
        </body>
        </html>
        """

@app.route('/stats')
def stats():
    """Show statistical analysis page"""
    try:
        # Use get_predictor() instead of creating a new instance
        predictor = get_predictor()
        
        # Get the last 10 draws for display
        last_draws = predictor.get_last_n_draws(10)
        
        # Convert to list for JSON serialization, handle datetime objects
        last_draws_list = []
        for _, row in last_draws.iterrows():
            draw_dict = {}
            for key, value in row.items():
                if key in ['b1', 'b2', 'b3', 'b4', 'b5', 'sum']:
                    draw_dict[key] = int(value) if value is not None else None
                elif key == 'date':
                    draw_dict[key] = value.strftime('%Y-%m-%d') if value is not None else None
                else:
                    draw_dict[key] = value
            last_draws_list.append(draw_dict)
        
        try:
            return render_template('stats.html', last_draws=last_draws_list)
        except Exception as template_error:
            # Fallback if template is missing
            return f"""
            <html>
            <head><title>Georgia Fantasy 5 Statistics</title></head>
            <body>
                <h1>Georgia Fantasy 5 Statistics</h1>
                <p>Template error: {template_error}</p>
                <p>Please make sure templates are correctly installed.</p>
                <h2>API Endpoints:</h2>
                <ul>
                    <li><a href="/api/stats/frequency">Number Frequency</a></li>
                    <li><a href="/api/stats/sum_distribution">Sum Distribution</a></li>
                    <li><a href="/api/stats/even_odd">Even/Odd Distribution</a></li>
                    <li><a href="/api/stats/decade">Decade Distribution</a></li>
                </ul>
                <a href="/">Back to Home</a>
            </body>
            </html>
            """
    except Exception as e:
        print(f"Error rendering stats page: {e}")
        return f"""
        <html>
        <head><title>Error</title></head>
        <body>
            <h1>Error Loading Statistics</h1>
            <p>{str(e)}</p>
            <a href="/">Back to Home</a>
        </body>
        </html>
        """


@app.route('/api/stats/frequency', methods=['GET'])
def get_frequency_stats():
    """Get frequency statistics for all numbers"""
    try:
        # Initialize predictor
        predictor = GeorgiaFantasy5Predictor(db_config)
        
        # Get time period from query params
        days = int(request.args.get('days', 30))
        
        # Check if database is connected
        if not predictor.conn or not predictor.cursor:
            return jsonify({'error': 'Database not available'}), 500
        
        # Query to get frequency for each number
        query = f"""
        SELECT ball, COUNT(*) as freq FROM (
            SELECT b1 as ball FROM ga_f5_draws WHERE date >= DATE_SUB(NOW(), INTERVAL {days} DAY)
            UNION ALL SELECT b2 as ball FROM ga_f5_draws WHERE date >= DATE_SUB(NOW(), INTERVAL {days} DAY)
            UNION ALL SELECT b3 as ball FROM ga_f5_draws WHERE date >= DATE_SUB(NOW(), INTERVAL {days} DAY)
            UNION ALL SELECT b4 as ball FROM ga_f5_draws WHERE date >= DATE_SUB(NOW(), INTERVAL {days} DAY)
            UNION ALL SELECT b5 as ball FROM ga_f5_draws WHERE date >= DATE_SUB(NOW(), INTERVAL {days} DAY)
        ) as all_balls
        GROUP BY ball
        ORDER BY ball ASC
        """
        
        predictor.cursor.execute(query)
        results = predictor.cursor.fetchall()
        
        # Process results to handle Decimal values
        frequency_data = []
        for row in results:
            ball = int(row['ball'])
            freq = float(row['freq']) if isinstance(row['freq'], decimal.Decimal) else row['freq']
            frequency_data.append({'ball': ball, 'frequency': freq})
        
        # Fill in missing balls with zero frequency
        all_balls = set(range(1, 43))
        existing_balls = set(item['ball'] for item in frequency_data)
        missing_balls = all_balls - existing_balls
        
        for ball in missing_balls:
            frequency_data.append({'ball': ball, 'frequency': 0})
        
        # Sort by ball number
        frequency_data.sort(key=lambda x: x['ball'])
        
        return jsonify({'data': frequency_data})
    except Exception as e:
        print(f"Error getting frequency stats: {e}")
        return jsonify({'error': str(e)}), 500


@app.route('/api/stats/sum_distribution', methods=['GET'])
def get_sum_distribution():
    """Get sum distribution statistics"""
    try:
        # Initialize predictor
        predictor = GeorgiaFantasy5Predictor(db_config)
        
        # Get time period from query params
        days = int(request.args.get('days', 365))
        
        # Check if database is connected
        if not predictor.conn or not predictor.cursor:
            return jsonify({'error': 'Database not available'}), 500
        
        # Query to get sum distribution
        query = f"""
        SELECT 
            CASE 
                WHEN sum < 80 THEN '<80'
                WHEN sum BETWEEN 80 AND 90 THEN '80-90'
                WHEN sum BETWEEN 91 AND 100 THEN '91-100'
                WHEN sum BETWEEN 101 AND 110 THEN '101-110'
                WHEN sum BETWEEN 111 AND 120 THEN '111-120'
                ELSE '>120'
            END as sum_range,
            COUNT(*) as count
        FROM ga_f5_draws 
        WHERE date >= DATE_SUB(NOW(), INTERVAL {days} DAY)
        GROUP BY sum_range
        ORDER BY 
            CASE sum_range
                WHEN '<80' THEN 1
                WHEN '80-90' THEN 2
                WHEN '91-100' THEN 3
                WHEN '101-110' THEN 4
                WHEN '111-120' THEN 5
                ELSE 6
            END
        """
        
        predictor.cursor.execute(query)
        results = predictor.cursor.fetchall()
        
        # Process results
        sum_data = []
        for row in results:
            count = float(row['count']) if isinstance(row['count'], decimal.Decimal) else row['count']
            sum_data.append({'range': row['sum_range'], 'count': count})
        
        return jsonify({'data': sum_data})
    except Exception as e:
        print(f"Error getting sum distribution: {e}")
        return jsonify({'error': str(e)}), 500


@app.route('/api/stats/even_odd', methods=['GET'])
def get_even_odd_stats():
    """Get even/odd distribution statistics"""
    try:
        # Initialize predictor
        predictor = GeorgiaFantasy5Predictor(db_config)
        
        # Get time period from query params
        days = int(request.args.get('days', 365))
        
        # Check if database is connected
        if not predictor.conn or not predictor.cursor:
            return jsonify({'error': 'Database not available'}), 500
        
        # Query to get even/odd distribution
        query = f"""
        SELECT 
            even_count,
            COUNT(*) as count
        FROM (
            SELECT 
                (b1 % 2 = 0) + (b2 % 2 = 0) + (b3 % 2 = 0) + (b4 % 2 = 0) + (b5 % 2 = 0) as even_count
            FROM ga_f5_draws 
            WHERE date >= DATE_SUB(NOW(), INTERVAL {days} DAY)
        ) as counts
        GROUP BY even_count
        ORDER BY even_count
        """
        
        predictor.cursor.execute(query)
        results = predictor.cursor.fetchall()
        
        # Process results
        even_odd_data = []
        for row in results:
            even_count = int(row['even_count'])
            odd_count = 5 - even_count
            count = float(row['count']) if isinstance(row['count'], decimal.Decimal) else row['count']
            even_odd_data.append({'even': even_count, 'odd': odd_count, 'count': count})
        
        return jsonify({'data': even_odd_data})
    except Exception as e:
        print(f"Error getting even/odd stats: {e}")
        return jsonify({'error': str(e)}), 500


@app.route('/api/stats/decade', methods=['GET'])
def get_decade_stats():
    """Get decade distribution statistics"""
    try:
        # Initialize predictor
        predictor = GeorgiaFantasy5Predictor(db_config)
        
        # Get time period from query params
        days = int(request.args.get('days', 365))
        
        # Check if database is connected
        if not predictor.conn or not predictor.cursor:
            return jsonify({'error': 'Database not available'}), 500
        
        # Query to get decade distribution
        query = f"""
        SELECT 
            CASE 
                WHEN ball BETWEEN 1 AND 9 THEN '1-9'
                WHEN ball BETWEEN 10 AND 19 THEN '10-19'
                WHEN ball BETWEEN 20 AND 29 THEN '20-29'
                ELSE '30-39'
            END as decade,
            COUNT(*) as count
        FROM (
            SELECT b1 as ball FROM ga_f5_draws WHERE date >= DATE_SUB(NOW(), INTERVAL {days} DAY)
            UNION ALL SELECT b2 as ball FROM ga_f5_draws WHERE date >= DATE_SUB(NOW(), INTERVAL {days} DAY)
            UNION ALL SELECT b3 as ball FROM ga_f5_draws WHERE date >= DATE_SUB(NOW(), INTERVAL {days} DAY)
            UNION ALL SELECT b4 as ball FROM ga_f5_draws WHERE date >= DATE_SUB(NOW(), INTERVAL {days} DAY)
            UNION ALL SELECT b5 as ball FROM ga_f5_draws WHERE date >= DATE_SUB(NOW(), INTERVAL {days} DAY)
        ) as all_balls
        GROUP BY decade
        ORDER BY decade
        """
        
        predictor.cursor.execute(query)
        results = predictor.cursor.fetchall()
        
        # Process results
        decade_data = []
        for row in results:
            count = float(row['count']) if isinstance(row['count'], decimal.Decimal) else row['count']
            decade_data.append({'decade': row['decade'], 'count': count})
        
        return jsonify({'data': decade_data})
    except Exception as e:
        print(f"Error getting decade stats: {e}")
        return jsonify({'error': str(e)}), 500


@app.route('/show_col1_data')
def show_col1_data():
    """Display the loaded col1 data (for debugging)"""
    try:
        # Use get_predictor() instead of creating a new instance
        predictor = get_predictor()
        
        # Build HTML table of the col1 data
        html = """
        <html>
        <head>
            <title>Col1 Data</title>
            <style>
                table { border-collapse: collapse; width: 100%; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                tr:nth-child(even) { background-color: #f9f9f9; }
                h1, h2 { color: #333; }
            </style>
        </head>
        <body>
            <h1>Georgia Fantasy 5 - Column 1 Data</h1>
            <p>This page shows all the column 1 data loaded from draws_col1.csv</p>
            
            <h2>Specific Sum/Even/Odd Combinations</h2>
            <table>
                <tr>
                    <th>Sum</th>
                    <th>Even</th>
                    <th>Odd</th>
                    <th>Col1 Values</th>
                </tr>
        """
        
        # Display specific combinations
        for key, values in sorted(predictor.col1_data.items()):
            if "*" not in key:  # Skip wildcard entries
                sum_val, even_val, odd_val = key
                html += f"""
                <tr>
                    <td>{sum_val}</td>
                    <td>{even_val}</td>
                    <td>{odd_val}</td>
                    <td>{', '.join(str(v) for v in sorted(values))}</td>
                </tr>
                """
                
        html += """
            </table>
            
            <h2>Generic Patterns</h2>
            <table>
                <tr>
                    <th>Sum</th>
                    <th>Even</th>
                    <th>Odd</th>
                    <th>Col1 Values</th>
                </tr>
        """
        
        # Display wildcard combinations
        for key, values in sorted(predictor.col1_data.items()):
            if "*" in key:  # Show only wildcard entries
                sum_val, even_val, odd_val = key
                html += f"""
                <tr>
                    <td>{sum_val}</td>
                    <td>{even_val}</td>
                    <td>{odd_val}</td>
                    <td>{', '.join(str(v) for v in sorted(values))}</td>
                </tr>
                """
                
        html += """
            </table>
            
            <p><a href="/">Back to Home</a></p>
        </body>
        </html>
        """
        
        return html
    except Exception as e:
        return f"""
        <html>
        <head><title>Error</title></head>
        <body>
            <h1>Error Displaying Col1 Data</h1>
            <p>{str(e)}</p>
            <a href="/">Back to Home</a>
        </body>
        </html>
        """

@app.route('/test_cycle/<int:sum_val>/<int:even>/<int:odd>')
def test_cycle(sum_val, even, odd):
    """Test the col1 cycler for a specific combination"""
    predictor = get_predictor()
    
    # Get the next 10 values from the cycler
    results = []
    for _ in range(10):
        next_val = predictor.get_next_col1(sum_val, even, odd)
        results.append(next_val)
    
    return jsonify({
        'sum': sum_val,
        'even': even,
        'odd': odd,
        'values': results
    })

@app.errorhandler(404)
def page_not_found(e):
    """Handle 404 errors"""
    try:
        return render_template('404.html'), 404
    except:
        return """
        <html>
        <head><title>Page Not Found</title></head>
        <body>
            <h1>404 - Page Not Found</h1>
            <p>The page you requested does not exist.</p>
            <a href="/">Back to Home</a>
        </body>
        </html>
        """, 404


@app.errorhandler(500)
def server_error(e):
    """Handle 500 errors"""
    try:
        return render_template('500.html', error=str(e)), 500
    except:
        return f"""
        <html>
        <head><title>Server Error</title></head>
        <body>
            <h1>500 - Server Error</h1>
            <p>An error occurred: {str(e)}</p>
            <a href="/">Back to Home</a>
        </body>
        </html>
        """, 500


# Run the application
if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000, use_reloader=False)
