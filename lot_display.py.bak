import pandas as pd
import numpy as np
import random
import os
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
        balls = sorted(random.sample(range(1, 43), 5))
        
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

def main():
    """Main function to run the application"""
    # Define file paths
    data_dir = 'data'
    os.makedirs(data_dir, exist_ok=True)
    
    scaffolding_file = os.path.join(data_dir, 'fantasy5_scaffolding.csv')
    
    # Create sample data if files don't exist
    create_sample_scaffolding(scaffolding_file, num_records=100)
    
    # Initialize predictor
    predictor = Fantasy5Predictor(scaffolding_file)
    
    # Generate predictions
    print("\nGenerating predictions...")
    predictions = predictor.generate_predictions(count=10, sum_range=(110, 110), even_range=(2, 3))
    
    # Display predictions
    print("\nFantasy 5 Predictions:")
    print("-" * 50)
    for i, pred in enumerate(predictions, 1):
        combo_str = '-'.join(str(n) for n in pred['combination'])
        print(f"Prediction #{i}: {combo_str} (Sum: {pred['sum']}, Even/Odd: {pred['even']}/{pred['odd']})")
    
    # Export to CSV
    predictions_file = os.path.join(data_dir, 'fantasy5_predictions.csv')
    predictor.export_to_csv(predictions, predictions_file)
    
    print("\nApplication completed successfully!")

if __name__ == "__main__":
    main()