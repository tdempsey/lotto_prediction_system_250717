from flask import Flask, render_template, request, redirect, url_for, jsonify, send_file
import pandas as pd
import numpy as np
import random
import os
import io
import csv
from datetime import datetime

class Fantasy5Predictor:
    def __init__(self, scaffolding_file):
        """
        Initialize the Fantasy 5 Predictor with scaffolding data
        
        Parameters:
        scaffolding_file (str): Path to the CSV file with scaffolding data
        """
        # Load the scaffolding data
        try:
            self.scaffolding = pd.read_csv(scaffolding_file)
            
            # Filter out rows with zeros in important positions
            self.filtered_data = self.scaffolding[
                (self.scaffolding['b1'] > 0) & 
                (self.scaffolding['b5'] > 0)
            ].copy()
            
            print(f"Loaded {len(self.filtered_data)} valid scaffolding records")
        except Exception as e:
            print(f"Error loading scaffolding data: {e}")
            # Create empty dataframe with necessary columns
            self.scaffolding = pd.DataFrame(columns=['b1', 'b2', 'b3', 'b4', 'b5', 'sum', 'even', 'odd'])
            self.filtered_data = self.scaffolding.copy()
            print("Created empty scaffolding data")
        
        # Create lookup tables
        self.create_lookup_tables()
        
    def create_lookup_tables(self):
        """Create lookup tables for pattern matching"""
        # Group by sum, even, odd counts
        self.sum_even_odd_groups = {}
        self.sum_groups = {}
        self.even_odd_groups = {}
        
        if len(self.filtered_data) == 0:
            print("No data available for lookup tables")
            return
        
        for _, row in self.filtered_data.iterrows():
            # Handle sum, even, odd groups
            if 'sum' in row and 'even' in row and 'odd' in row:
                key = (row['sum'], row['even'], row['odd'])
                if key not in self.sum_even_odd_groups:
                    self.sum_even_odd_groups[key] = []
                    
                # Store b1, b3 (if available), and b5 values
                pattern = {
                    'b1': row['b1'],
                    'b5': row['b5']
                }
                
                # Include b3 if it's available and valid
                if 'b3' in row and row['b3'] > 0:
                    pattern['b3'] = row['b3']
                
                self.sum_even_odd_groups[key].append(pattern)
            
            # Handle sum groups
            if 'sum' in row:
                sum_val = row['sum']
                if sum_val not in self.sum_groups:
                    self.sum_groups[sum_val] = []
                
                self.sum_groups[sum_val].append({'b1': row['b1'], 'b5': row['b5']})
            
            # Handle even/odd groups
            if 'even' in row and 'odd' in row:
                even_odd = (row['even'], row['odd'])
                if even_odd not in self.even_odd_groups:
                    self.even_odd_groups[even_odd] = []
                
                self.even_odd_groups[even_odd].append({'b1': row['b1'], 'b5': row['b5']})
    
    def get_pattern_by_sum_even_odd(self, target_sum, even_count, odd_count):
        """
        Get a pattern based on sum and even/odd distribution
        
        Parameters:
        target_sum (int): Sum of the 5 numbers
        even_count (int): Count of even numbers
        odd_count (int): Count of odd numbers
        
        Returns:
        dict: Pattern with b1, b3 (optional), b5 values
        """
        key = (target_sum, even_count, odd_count)
        
        # Exact match
        if key in self.sum_even_odd_groups and self.sum_even_odd_groups[key]:
            return random.choice(self.sum_even_odd_groups[key])
        
        # If no exact match, try matching by sum only
        if target_sum in self.sum_groups and self.sum_groups[target_sum]:
            return random.choice(self.sum_groups[target_sum])
        
        # If still no match, try by even/odd distribution
        even_odd_key = (even_count, odd_count)
        if even_odd_key in self.even_odd_groups and self.even_odd_groups[even_odd_key]:
            return random.choice(self.even_odd_groups[even_odd_key])
        
        # Last resort - find the closest sum in the scaffolding data
        if self.sum_groups:
            closest_sum = min(self.sum_groups.keys(), key=lambda x: abs(x - target_sum))
            if closest_sum in self.sum_groups and self.sum_groups[closest_sum]:
                return random.choice(self.sum_groups[closest_sum])
        
        # Ultimate fallback if nothing else works
        return {'b1': random.randint(1, 15), 'b5': random.randint(25, 39)}
    
    def find_middle_values(self, b1, b5, target_sum, even_target):
        """
        Find middle values (b2, b3, b4) to complete a combination
        
        Parameters:
        b1, b5 (int): First and last ball values
        target_sum (int): Target sum for the combination
        even_target (int): Target count of even numbers
        
        Returns:
        list: Complete 5-number combination [b1, b2, b3, b4, b5]
        """
        current_sum = b1 + b5
        remaining_sum = target_sum - current_sum
        
        # Count even numbers in b1 and b5
        current_even = (b1 % 2 == 0) + (b5 % 2 == 0)
        even_needed = even_target - current_even
        
        # We need to find b2, b3, b4 that:
        # 1. Are in ascending order between b1 and b5
        # 2. Sum to the remaining sum
        # 3. Have the required number of even digits
        
        valid_combinations = []
        
        # Define the range for middle values
        min_b2 = b1 + 1
        max_b4 = b5 - 1
        
        # Brute force approach for small range
        for b2 in range(min_b2, max_b4 - 1):
            for b3 in range(b2 + 1, max_b4):
                for b4 in range(b3 + 1, max_b4 + 1):
                    # Check if they sum to the remaining sum
                    if b2 + b3 + b4 == remaining_sum:
                        # Check if they have the right number of even digits
                        b2_even = b2 % 2 == 0
                        b3_even = b3 % 2 == 0
                        b4_even = b4 % 2 == 0
                        middle_even_count = b2_even + b3_even + b4_even
                        
                        if middle_even_count == even_needed:
                            valid_combinations.append([b1, b2, b3, b4, b5])
        
        if valid_combinations:
            return random.choice(valid_combinations)
        
        # If we couldn't find an exact match, find the closest match
        closest_combinations = []
        min_diff = float('inf')
        
        for b2 in range(min_b2, max_b4 - 1):
            for b3 in range(b2 + 1, max_b4):
                for b4 in range(b3 + 1, max_b4 + 1):
                    # Calculate how close this combination is to our targets
                    sum_diff = abs((b2 + b3 + b4) - remaining_sum)
                    
                    b2_even = b2 % 2 == 0
                    b3_even = b3 % 2 == 0
                    b4_even = b4 % 2 == 0
                    middle_even_count = b2_even + b3_even + b4_even
                    even_diff = abs(middle_even_count - even_needed)
                    
                    # Combined difference (prioritize sum)
                    total_diff = (sum_diff * 10) + even_diff
                    
                    # Update best match if this is better
                    if total_diff < min_diff:
                        min_diff = total_diff
                        closest_combinations = [[b1, b2, b3, b4, b5]]
                    elif total_diff == min_diff:
                        closest_combinations.append([b1, b2, b3, b4, b5])
        
        if closest_combinations:
            return random.choice(closest_combinations)
        
        # Ultimate fallback if no valid combinations found
        # Just pick random middle values
        b2 = random.randint(min_b2, min(min_b2 + 5, max_b4 - 2))
        b3 = random.randint(b2 + 1, min(b2 + 10, max_b4 - 1))
        b4 = random.randint(b3 + 1, max_b4)
        
        return [b1, b2, b3, b4, b5]
    
    def generate_predictions(self, count=10, sum_range=(85, 120), even_range=(2, 3)):
        """
        Generate lottery number predictions
        
        Parameters:
        count (int): Number of predictions to generate
        sum_range (tuple): Range of sums to consider (min, max)
        even_range (tuple): Range of even number counts to consider (min, max)
        
        Returns:
        list: List of prediction combinations
        """
        predictions = []
        min_sum, max_sum = sum_range
        
        # Print debug info
        print(f"Generating {count} predictions with sum range {min_sum}-{max_sum} and even range {even_range}")
        
        for _ in range(count):
            # Set target values
            # If min_sum equals max_sum, use that exact value
            if min_sum == max_sum:
                target_sum = min_sum
            else:
                target_sum = random.randint(min_sum, max_sum)
                
            # Set target even count
            target_even = random.randint(even_range[0], even_range[1])
            target_odd = 5 - target_even
            
            # Get pattern from scaffolding data
            pattern = self.get_pattern_by_sum_even_odd(target_sum, target_even, target_odd)
            
            # Extract b1 and b5 from pattern
            b1 = pattern['b1']
            b5 = pattern['b5']
            
            # Complete the combination
            combination = self.find_middle_values(b1, b5, target_sum, target_even)
            
            # Check if the sum matches exactly when min_sum equals max_sum
            actual_sum = sum(combination)
            if min_sum == max_sum and actual_sum != min_sum:
                # If not, try to adjust one of the middle values
                diff = min_sum - actual_sum
                # Adjust b3 if possible
                if 1 <= combination[2] + diff <= 39:
                    combination[2] += diff
                # Or adjust b2 or b4 if needed
                elif 1 <= combination[1] + diff <= 39:
                    combination[1] += diff
                elif 1 <= combination[3] + diff <= 39:
                    combination[3] += diff
            
            # Double-check the combination is valid (in ascending order)
            if not all(combination[i] < combination[i+1] for i in range(4)):
                # If not, sort it
                combination.sort()
            
            # Count even and odd numbers
            even_count = sum(1 for num in combination if num % 2 == 0)
            odd_count = 5 - even_count
            
            predictions.append({
                'combination': combination,
                'sum': sum(combination),
                'even': even_count,
                'odd': odd_count
            })
        
        return predictions
    
    def export_to_csv(self, predictions, filename='fantasy5_predictions.csv'):
        """
        Export predictions to CSV
        
        Parameters:
        predictions (list): List of prediction dictionaries
        filename (str): Output filename
        """
        rows = []
        for i, pred in enumerate(predictions, 1):
            rows.append({
                'Prediction': i,
                'Combination': '-'.join(str(n) for n in pred['combination']),
                'Sum': pred['sum'],
                'Even': pred['even'],
                'Odd': pred['odd']
            })
        
        df = pd.DataFrame(rows)
        df.to_csv(filename, index=False)
        print(f"Predictions exported to {filename}")
        return df

class LotteryDataManager:
    def __init__(self, csv_path):
        """
        Initialize the lottery data manager
        
        Parameters:
        csv_path (str): Path to the CSV file with lottery data
        """
        self.csv_path = csv_path
        self.data = None
        self.load_data()
        
    def load_data(self):
        """Load data from CSV file"""
        try:
            if os.path.exists(self.csv_path) and os.path.getsize(self.csv_path) > 0:
                self.data = pd.read_csv(self.csv_path)
                
                # Check if required columns exist
                required_columns = ['Date', 'b1', 'b2', 'b3', 'b4', 'b5']
                if not all(col in self.data.columns for col in required_columns):
                    raise ValueError(f"CSV file missing required columns: {required_columns}")
                
                # Add a date column if it doesn't exist
                if 'Date' not in self.data.columns:
                    self.data['Date'] = pd.to_datetime('today').strftime('%Y-%m-%d')
                
                # Convert Date to string format if it's not already
                if len(self.data) > 0 and not isinstance(self.data['Date'].iloc[0], str):
                    self.data['Date'] = self.data['Date'].astype(str)
                
                # Calculate sum, even, odd if not present
                if 'Sum' not in self.data.columns:
                    self.data['Sum'] = self.data['b1'] + self.data['b2'] + self.data['b3'] + self.data['b4'] + self.data['b5']
                
                if 'Even' not in self.data.columns:
                    self.data['Even'] = self.data.apply(lambda row: sum(1 for b in ['b1', 'b2', 'b3', 'b4', 'b5'] if row[b] % 2 == 0), axis=1)
                
                if 'Odd' not in self.data.columns:
                    self.data['Odd'] = 5 - self.data['Even']
                
                # Initialize statistical columns if they don't exist
                stat_columns = ['Average', 'Median', 'Harmean', 'Geomean', 'Quart1', 'Quart2', 'Quart3', 'Stdev', 'Var', 'AveDev', 'Kurt', 'Skew', 'Y1', 'WA']
                for col in stat_columns:
                    if col not in self.data.columns:
                        self.data[col] = 0.0
                        
                # Calculate stats for each row
                self.calculate_stats()
                    
                print(f"Loaded {len(self.data)} lottery data records")
            else:
                # File doesn't exist or is empty, create a new DataFrame
                self.data = pd.DataFrame(columns=['Date', 'b1', 'b2', 'b3', 'b4', 'b5', 'Sum', 'Even', 'Odd', 
                                                 'Average', 'Median', 'Harmean', 'Geomean', 'Quart1', 'Quart2', 
                                                 'Quart3', 'Stdev', 'Var', 'AveDev', 'Kurt', 'Skew', 'Y1', 'WA'])
                print("Created new empty data file")
        except Exception as e:
            print(f"Error loading data: {e}")
            # Create empty dataframe with required columns
            self.data = pd.DataFrame(columns=['Date', 'b1', 'b2', 'b3', 'b4', 'b5', 'Sum', 'Even', 'Odd', 
                                             'Average', 'Median', 'Harmean', 'Geomean', 'Quart1', 'Quart2', 
                                             'Quart3', 'Stdev', 'Var', 'AveDev', 'Kurt', 'Skew', 'Y1', 'WA'])
            print("Created new empty data file after error")
    
    def calculate_stats(self):
        """Calculate statistical measures for each draw"""
        for idx, row in self.data.iterrows():
            try:
                balls = [row['b1'], row['b2'], row['b3'], row['b4'], row['b5']]
                
                # Average
                self.data.at[idx, 'Average'] = sum(balls) / 5
                
                # Median
                self.data.at[idx, 'Median'] = sorted(balls)[2]
                
                # Other statistical measures could be calculated here
                # For now we'll set them to placeholder values
                self.data.at[idx, 'Harmean'] = 0.0
                self.data.at[idx, 'Geomean'] = 0.0
                self.data.at[idx, 'Quart1'] = 0.0
                self.data.at[idx, 'Quart2'] = 0.0
                self.data.at[idx, 'Quart3'] = 0.0
                self.data.at[idx, 'Stdev'] = 0.0
                self.data.at[idx, 'Var'] = 0.0
                self.data.at[idx, 'AveDev'] = 0.0
                self.data.at[idx, 'Kurt'] = 0.0
                self.data.at[idx, 'Skew'] = 0.0
                self.data.at[idx, 'Y1'] = 0.0
                self.data.at[idx, 'WA'] = 0.0
            except Exception as e:
                print(f"Error calculating stats for row {idx}: {e}")
    
    def get_data(self, limit=None, sort_by=None, ascending=True):
        """
        Get lottery data, optionally limited and sorted
        
        Parameters:
        limit (int): Maximum number of records to return
        sort_by (str): Column to sort by
        ascending (bool): Sort order
        
        Returns:
        pd.DataFrame: Filtered and sorted data
        """
        if self.data is None or len(self.data) == 0:
            return pd.DataFrame()
        
        result = self.data.copy()
        
        # Sort if requested
        if sort_by and sort_by in result.columns:
            result = result.sort_values(by=sort_by, ascending=ascending)
        
        # Limit if requested
        if limit and limit > 0:
            result = result.head(limit)
        
        return result
    
    def get_numbers_frequency(self, limit=None):
        """
        Get frequency of each number in the dataset
        
        Parameters:
        limit (int): Only count in the last 'limit' draws
        
        Returns:
        dict: Number frequencies
        """
        if self.data is None or len(self.data) == 0:
            return {i: 0 for i in range(1, 40)}
        
        data = self.data
        if limit and limit > 0 and len(data) > limit:
            data = data.tail(limit)
        
        # Flatten all ball numbers into a single series
        all_numbers = pd.concat([
            data['b1'], data['b2'], data['b3'], data['b4'], data['b5']
        ]).reset_index(drop=True)
        
        # Count frequencies
        frequencies = all_numbers.value_counts().to_dict()
        
        # Make sure we have all numbers from 1 to 39
        for i in range(1, 40):
            if i not in frequencies:
                frequencies[i] = 0
        
        return {k: frequencies[k] for k in sorted(frequencies.keys())}
    
    def add_draw(self, date, b1, b2, b3, b4, b5):
        """
        Add a new draw to the dataset
        
        Parameters:
        date (str): Date of the draw
        b1, b2, b3, b4, b5 (int): Ball numbers
        
        Returns:
        bool: Success status
        """
        try:
            # Convert inputs to appropriate types
            try:
                b1, b2, b3, b4, b5 = int(b1), int(b2), int(b3), int(b4), int(b5)
            except (TypeError, ValueError):
                print("Error converting ball numbers to integers")
                return False
            
            # Check if all numbers are in the valid range
            if not all(1 <= b <= 39 for b in [b1, b2, b3, b4, b5]):
                print("Ball numbers must be between 1 and 39")
                return False
            
            # Check if numbers are unique
            if len(set([b1, b2, b3, b4, b5])) != 5:
                print("Ball numbers must be unique")
                return False
            
            # Sort the numbers
            balls = sorted([b1, b2, b3, b4, b5])
            
            # Calculate sum, even, odd
            ball_sum = sum(balls)
            even_count = sum(1 for b in balls if b % 2 == 0)
            odd_count = 5 - even_count
            
            # Create a new row
            new_row = {
                'Date': date,
                'b1': balls[0],
                'b2': balls[1],
                'b3': balls[2],
                'b4': balls[3],
                'b5': balls[4],
                'Sum': ball_sum,
                'Even': even_count,
                'Odd': odd_count,
                'Average': ball_sum / 5,
                'Median': balls[2],
            }
            
            # Add placeholder values for other stats
            for col in ['Harmean', 'Geomean', 'Quart1', 'Quart2', 'Quart3', 'Stdev', 'Var', 'AveDev', 'Kurt', 'Skew', 'Y1', 'WA']:
                new_row[col] = 0.0
            
            # Add the new row to the data
            if self.data is None:
                self.data = pd.DataFrame([new_row])
            else:
                self.data = pd.concat([self.data, pd.DataFrame([new_row])], ignore_index=True)
            
            # Save the updated data
            self.save_data()
            
            return True
        except Exception as e:
            print(f"Error adding draw: {e}")
            return False
    
    def save_data(self):
        """Save data to CSV file"""
        try:
            # Create directory if it doesn't exist
            os.makedirs(os.path.dirname(self.csv_path), exist_ok=True)
            
            self.data.to_csv(self.csv_path, index=False)
            return True
        except Exception as e:
            print(f"Error saving data: {e}")
            return False

def create_sample_data(csv_path, num_records=30):
    """
    Create sample lottery data for testing
    
    Parameters:
    csv_path (str): Path to save the CSV file
    num_records (int): Number of sample records to create
    """
    if os.path.exists(csv_path):
        print(f"File {csv_path} already exists, skipping sample data creation")
        return
    
    # Create directory if it doesn't exist
    os.makedirs(os.path.dirname(csv_path), exist_ok=True)
    
    # Create sample data
    data = []
    for i in range(num_records):
        # Generate 5 unique random numbers between 1 and 39
        balls = sorted(random.sample(range(1, 40), 5))
        
        # Calculate sum and even/odd counts
        ball_sum = sum(balls)
        even_count = sum(1 for b in balls if b % 2 == 0)
        odd_count = 5 - even_count
        
        # Create row
        row = {
            'Date': (datetime.now() - pd.Timedelta(days=num_records-i)).strftime('%Y-%m-%d'),
            'b1': balls[0],
            'b2': balls[1],
            'b3': balls[2],
            'b4': balls[3],
            'b5': balls[4],
            'Sum': ball_sum,
            'Even': even_count,
            'Odd': odd_count,
            'Average': ball_sum / 5,
            'Median': balls[2],
            'Harmean': 0.0,
            'Geomean': 0.0,
            'Quart1': 0.0,
            'Quart2': 0.0,
            'Quart3': 0.0,
            'Stdev': 0.0,
            'Var': 0.0,
            'AveDev': 0.0,
            'Kurt': 0.0,
            'Skew': 0.0,
            'Y1': 0.0,
            'WA': 0.0
        }
        
        data.append(row)
    
    # Create DataFrame and save to CSV
    df = pd.DataFrame(data)
    df.to_csv(csv_path, index=False)
    print(f"Created sample data with {num_records} records at {csv_path}")

def create_sample_scaffolding(csv_path, num_records=100):
    """
    Create sample scaffolding data for testing
    
    Parameters:
    csv_path (str): Path to save the CSV file
    num_records (int): Number of sample records to create
    """
    if os.path.exists(csv_path):
        print(f"File {csv_path} already exists, skipping sample scaffolding creation")
        return
    
    # Create directory if it doesn't exist
    os.makedirs(os.path.dirname(csv_path), exist_ok=True)
    
    # Create sample data
    data = []
    for i in range(num_records):
        # Generate 5 unique random numbers between 1 and 39
        balls = sorted(random.sample(range(1, 40), 5))
        
        # Calculate sum and even/odd counts
        ball_sum = sum(balls)
        even_count = sum(1 for b in balls if b % 2 == 0)
        odd_count = 5 - even_count
        
        # Create row
        row = {
            'Date': (datetime.now() - pd.Timedelta(days=num_records-i)).strftime('%Y-%m-%d'),
            'b1': balls[0],
            'b2': balls[1],
            'b3': balls[2],
            'b4': balls[3],
            'b5': balls[4],
            'sum': ball_sum,
            'even': even_count,
            'odd': odd_count
        }
        
        data.append(row)
    
    # Create DataFrame and save to CSV
    df = pd.DataFrame(data)
    df.to_csv(csv_path, index=False)
    print(f"Created sample scaffolding data with {num_records} records at {csv_path}")

# Initialize Flask app
app = Flask(__name__)

# Create data directory if it doesn't exist
if not os.path.exists('data'):
    os.makedirs('data')

# Create static directory for templates
if not os.path.exists('static'):
    os.makedirs('static')

# Create templates directory
if not os.path.exists('templates'):
    os.makedirs('templates')

# Initialize with default files
DATA_FILE = 'data/georgia_fantasy5_data.csv'
SCAFFOLDING_FILE = 'data/fantasy5_scaffolding.csv'

# Create empty files if they don't exist
if not os.path.exists(DATA_FILE):
    create_sample_data(DATA_FILE, num_records=30)

if not os.path.exists(SCAFFOLDING_FILE):
    create_sample_scaffolding(SCAFFOLDING_FILE, num_records=100)

# Initialize managers
data_manager = LotteryDataManager(DATA_FILE)

# Create template directories if they don't exist
os.makedirs('templates', exist_ok=True)

# Create templates for the application
def create_templates():
    # Base template
    with open('templates/base.html', 'w') as f:
        f.write('''<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Georgia Fantasy 5 - {% block title %}Home{% endblock %}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        h1, h2, h3 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .navigation {
            margin-bottom: 20px;
        }
        .navigation a {
            display: inline-block;
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            margin-right: 10px;
        }
        .navigation a:hover {
            background-color: #0056b3;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ddd;
        }
        button, input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            border: none;
            padding: 10px 15px;
            font-size: 16px;
        }
        button:hover, input[type="submit"]:hover {
            background-color: #45a049;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .prediction {
            background-color: #e3f2fd;
            border: 1px solid #90caf9;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .prediction-number {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .prediction-stats {
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Georgia Fantasy 5</h1>
        
        <div class="navigation">
            <a href="/">Home</a>
            <a href="/display">View Draws</a>
            <a href="/add_draw">Add Draw</a>
            <a href="/predict">Generate Predictions</a>
        </div>
        
        {% if message %}
        <div class="message {% if success %}success{% else %}error{% endif %}">
            {{ message }}
        </div>
        {% endif %}
        
        <div class="content">
            {% block content %}{% endblock %}
        </div>
    </div>
</body>
</html>''')
    
    # Home template
    with open('templates/index.html', 'w') as f:
        f.write('''{% extends "base.html" %}
{% block title %}Home{% endblock %}
{% block content %}
    <h2>Welcome to Georgia Fantasy 5 Predictor</h2>
    <p>This application helps you track Georgia Fantasy 5 lottery draws and generate predictions based on historical patterns.</p>
    
    <div class="menu">
        <h3>What would you like to do?</h3>
        <ul>
            <li><a href="/display">View past lottery draws</a></li>
            <li><a href="/add_draw">Add a new lottery draw</a></li>
            <li><a href="/predict">Generate predictions</a></li>
        </ul>
    </div>
{% endblock %}''')
    
    # Display template
    with open('templates/display.html', 'w') as f:
        f.write('''{% extends "base.html" %}
{% block title %}View Draws{% endblock %}
{% block content %}
    <h2>Lottery Draws</h2>
    
    <form method="get" action="/display">
        <div class="form-group">
            <label for="limit">Number of draws to display:</label>
            <input type="number" id="limit" name="limit" min="1" max="100" value="{{ limit }}">
        </div>
        <div class="form-group">
            <label for="sort_by">Sort by:</label>
            <select id="sort_by" name="sort_by">
                <option value="Date" {% if sort_by == 'Date' %}selected{% endif %}>Date</option>
                <option value="Sum" {% if sort_by == 'Sum' %}selected{% endif %}>Sum</option>
                <option value="Even" {% if sort_by == 'Even' %}selected{% endif %}>Even Count</option>
                <option value="Odd" {% if sort_by == 'Odd' %}selected{% endif %}>Odd Count</option>
            </select>
        </div>
        <div class="form-group">
            <label for="ascending">Order:</label>
            <select id="ascending" name="ascending">
                <option value="true" {% if ascending %}selected{% endif %}>Ascending</option>
                <option value="false" {% if not ascending %}selected{% endif %}>Descending</option>
            </select>
        </div>
        <button type="submit">Apply Filters</button>
    </form>
    
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Numbers</th>
                <th>Sum</th>
                <th>Even</th>
                <th>Odd</th>
            </tr>
        </thead>
        <tbody>
            {% for draw in draws %}
            <tr>
                <td>{{ draw.Date }}</td>
                <td>{{ draw.b1 }}-{{ draw.b2 }}-{{ draw.b3 }}-{{ draw.b4 }}-{{ draw.b5 }}</td>
                <td>{{ draw.Sum }}</td>
                <td>{{ draw.Even }}</td>
                <td>{{ draw.Odd }}</td>
            </tr>
            {% endfor %}
        </tbody>
    </table>
    
    {% if not draws %}
    <p>No draw data available. <a href="/add_draw">Add some draws</a> to get started.</p>
    {% endif %}
{% endblock %}''')
    
    # Add Draw template
    with open('templates/add_draw.html', 'w') as f:
        f.write('''{% extends "base.html" %}
{% block title %}Add Draw{% endblock %}
{% block content %}
    <h2>Add New Draw</h2>
    
    <form method="post" action="/add_draw">
        <div class="form-group">
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required value="{{ today }}">
        </div>
        
        <div class="form-group">
            <label for="b1">Ball 1:</label>
            <input type="number" id="b1" name="b1" min="1" max="39" required>
        </div>
        
        <div class="form-group">
            <label for="b2">Ball 2:</label>
            <input type="number" id="b2" name="b2" min="1" max="39" required>
        </div>
        
        <div class="form-group">
            <label for="b3">Ball 3:</label>
            <input type="number" id="b3" name="b3" min="1" max="39" required>
        </div>
        
        <div class="form-group">
            <label for="b4">Ball 4:</label>
            <input type="number" id="b4" name="b4" min="1" max="39" required>
        </div>
        
        <div class="form-group">
            <label for="b5">Ball 5:</label>
            <input type="number" id="b5" name="b5" min="1" max="39" required>
        </div>
        
        <button type="submit">Add Draw</button>
    </form>
    
    <p>Note: Numbers will be automatically sorted in ascending order.</p>
{% endblock %}''')
    
    # Predict template
    with open('templates/predict.html', 'w') as f:
        f.write('''{% extends "base.html" %}
{% block title %}Generate Predictions{% endblock %}
{% block content %}
    <h2>Generate Fantasy 5 Predictions</h2>
    
    <form method="post" action="/predict">
        <div class="form-group">
            <label for="count">Number of predictions:</label>
            <input type="number" id="count" name="count" min="1" max="50" value="10" required>
        </div>
        
        <div class="form-group">
            <label for="min_sum">Minimum Sum:</label>
            <input type="number" id="min_sum" name="min_sum" min="5" max="195" value="85" required>
        </div>
        
        <div class="form-group">
            <label for="max_sum">Maximum Sum:</label>
            <input type="number" id="max_sum" name="max_sum" min="5" max="195" value="120" required>
        </div>
        
        <div class="form-group">
            <label for="min_even">Minimum Even Numbers:</label>
            <input type="number" id="min_even" name="min_even" min="0" max="5" value="2" required>
        </div>
        
        <div class="form-group">
            <label for="max_even">Maximum Even Numbers:</label>
            <input type="number" id="max_even" name="max_even" min="0" max="5" value="3" required>
        </div>
        
        <button type="submit">Generate Predictions</button>
    </form>
    
    {% if predictions %}
    <h3>Predictions</h3>
    <div class="predictions-container">
        {% for pred in predictions %}
        <div class="prediction">
            <div class="prediction-number">{{ pred.combination }}</div>
            <div class="prediction-stats">
                Sum: {{ pred.sum }} | Even: {{ pred.even }} | Odd: {{ pred.odd }}
            </div>
        </div>
        {% endfor %}
    </div>
    
    <p>
        <a href="/download_predictions" class="btn">Download as CSV</a>
    </p>
    {% endif %}
{% endblock %}''')

# Create the templates
create_templates()