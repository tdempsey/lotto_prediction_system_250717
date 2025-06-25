import mysql.connector
import pandas as pd
import numpy as np
from datetime import datetime, timedelta
import argparse
import json
import sys
from itertools import combinations

class GeorgiaFantasy5Predictor:
    def __init__(self, config):
        """
        Initialize the Georgia Fantasy 5 Predictor
        
        Parameters:
        config (dict): MySQL database connection parameters
        """
        print("Connecting to database...")
        try:
            self.conn = mysql.connector.connect(**config)
            self.cursor = self.conn.cursor(dictionary=True)
            print("Connected successfully!")
        except mysql.connector.Error as e:
            print(f"Error connecting to MySQL database: {e}")
            sys.exit(1)
        
        # Georgia Fantasy 5 specifics
        self.num_range = range(1, 43)  # Numbers 1-42
        self.nums_per_draw = 5
        
        # Filter settings (can be adjusted via command line)
        self.max_seq2 = 1
        self.max_seq3 = 0
        self.max_mod_tot = 2
        self.sum_range = (70, 139)
        
        # Load historical data
        self.load_historical_data()
    
    def load_historical_data(self):
        """Load historical draws from MySQL database"""
        print("Loading historical data...")
        query = "SELECT * FROM ga_f5_draws ORDER BY date DESC"
        self.cursor.execute(query)
        results = self.cursor.fetchall()
        
        # Convert to DataFrame for easier manipulation
        self.historical_draws = pd.DataFrame(results)
        print(f"Loaded {len(self.historical_draws)} historical draws")
    
    def get_last_n_draws(self, n=10):
        """Get the last n draws"""
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
        
        return d0, d1, d2, d3
    
    def calculate_duplicates_from_previous(self, numbers, max_draws=10):
        """
        Calculate duplicates from previous draws
        
        Parameters:
        numbers (list): The combination to check
        max_draws (int): Number of previous draws to check
        
        Returns:
        list: Count of duplicates for each of the previous draws
        """
        dup_counts = []
        
        last_draws = self.get_last_n_draws(max_draws)
        
        # Individual draw duplicate counts
        for i, row in last_draws.iterrows():
            previous_draw = [row['b1'], row['b2'], row['b3'], row['b4'], row['b5']]
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
                    all_previous_nums.extend([row['b1'], row['b2'], row['b3'], row['b4'], row['b5']])
            
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
            
        # Get statistics
        stats = {}
        
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
        d0, d1, d2, d3 = self.calculate_decade_distribution(combination)
        if d0 > 2 or d1 > 2 or d2 > 2 or d3 > 2:
            return False
            
        # Duplicate check from previous draws
        dup_counts, cumulative_dups = self.calculate_duplicates_from_previous(combination)
        
        # Your filtering rules:
        # - No more than 1 number from most recent draw
        if dup_counts[0] > 1:
            return False
            
        # - No more than 2 numbers from cumulative 2 most recent draws
        if cumulative_dups[1] > 2:
            return False
            
        # - No more than 3 numbers from cumulative 3 most recent draws
        if cumulative_dups[2] > 3:
            return False
            
        # Sum range check
        total_sum = sum(combination)
        min_sum, max_sum = self.sum_range
        if not (min_sum <= total_sum <= max_sum):
            return False
            
        # All filters passed
        return True
    
    def generate_predictions(self, count=10):
        """
        Generate top predictions
        
        Parameters:
        count (int): Number of predictions to generate
        
        Returns:
        list: List of prediction dictionaries with combinations and scores
        """
        print(f"Generating {count} predictions for Georgia Fantasy 5...")
        
        # Get the latest draw date for tracking
        latest_date = self.historical_draws.iloc[0]['date'] if not self.historical_draws.empty else None
        
        # Get position 1, 3, 5 candidates based on recent frequency (scaffolding approach)
        # This mimics the user's scaffolding approach where they first establish positions 1, 3, and 5
        print("Finding position 1, 3, 5 candidates from recent draws...")
        
        query = """
        SELECT b1, COUNT(*) as freq FROM ga_f5_draws 
        WHERE date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        GROUP BY b1 ORDER BY freq DESC LIMIT 15
        """
        self.cursor.execute(query)
        pos1_candidates = [row['b1'] for row in self.cursor.fetchall()]
        
        query = """
        SELECT b3, COUNT(*) as freq FROM ga_f5_draws 
        WHERE date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        GROUP BY b3 ORDER BY freq DESC LIMIT 15
        """
        self.cursor.execute(query)
        pos3_candidates = [row['b3'] for row in self.cursor.fetchall()]
        
        query = """
        SELECT b5, COUNT(*) as freq FROM ga_f5_draws 
        WHERE date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        GROUP BY b5 ORDER BY freq DESC LIMIT 15
        """
        self.cursor.execute(query)
        pos5_candidates = [row['b5'] for row in self.cursor.fetchall()]
        
        # Fill in any missing candidates if we don't have enough
        all_nums = list(range(1, 43))
        if len(pos1_candidates) < 10:
            pos1_candidates.extend([n for n in all_nums if n not in pos1_candidates][:10-len(pos1_candidates)])
        if len(pos3_candidates) < 10:
            pos3_candidates.extend([n for n in all_nums if n not in pos3_candidates][:10-len(pos3_candidates)])
        if len(pos5_candidates) < 10:
            pos5_candidates.extend([n for n in all_nums if n not in pos5_candidates][:10-len(pos5_candidates)])
        
        # Track all filtered combinations
        filtered_combinations = []
        
        # Generate scaffolding combinations (positions 1, 3, 5)
        # Only need a limited number to start with
        scaffold_count = 0
        max_scaffolds = min(count * 10, 100)  # Cap at 100 scaffolds
        
        print("Building combinations using scaffolding approach...")
        for pos1 in pos1_candidates[:5]:  # Limit to top 5 for position 1
            for pos3 in pos3_candidates[:5]:  # Limit to top 5 for position 3
                for pos5 in pos5_candidates[:5]:  # Limit to top 5 for position 5
                    # Skip if numbers are not ascending
                    if not (pos1 < pos3 < pos5):
                        continue
                    
                    # Now find valid positions 2 and 4
                    # For each scaffold (1, 3, 5), try all possible positions 2 and 4
                    for pos2 in range(pos1 + 1, pos3):
                        for pos4 in range(pos3 + 1, pos5):
                            # Create the full combination
                            combo = sorted([pos1, pos2, pos3, pos4, pos5])
                            
                            # Check if it passes all filters
                            if self.filter_combination(combo):
                                # Calculate a score for this combination
                                stats = self._calculate_stats(combo)
                                score = self._calculate_score(combo, stats)
                                
                                filtered_combinations.append({
                                    'combination': combo,
                                    'score': score,
                                    'sum': sum(combo),
                                    'stats': stats
                                })
                                
                                scaffold_count += 1
                                if scaffold_count >= max_scaffolds:
                                    break
                        if scaffold_count >= max_scaffolds:
                            break
                    if scaffold_count >= max_scaffolds:
                        break
                if scaffold_count >= max_scaffolds:
                    break
            if scaffold_count >= max_scaffolds:
                break
        
        # If we don't have enough combinations yet, generate more using a different approach
        if len(filtered_combinations) < count:
            print(f"Only found {len(filtered_combinations)} combinations using scaffolding, generating more...")
            
            # Get frequency rankings
            query = """
            SELECT ball, COUNT(*) as freq FROM (
                SELECT b1 as ball FROM ga_f5_draws WHERE date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                UNION ALL SELECT b2 as ball FROM ga_f5_draws WHERE date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                UNION ALL SELECT b3 as ball FROM ga_f5_draws WHERE date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                UNION ALL SELECT b4 as ball FROM ga_f5_draws WHERE date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                UNION ALL SELECT b5 as ball FROM ga_f5_draws WHERE date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            ) as all_balls
            GROUP BY ball
            ORDER BY freq DESC
            """
            self.cursor.execute(query)
            freq_results = self.cursor.fetchall()
            
            # Convert to a dictionary for easier lookup
            frequency = {row['ball']: row['freq'] for row in freq_results}
            
            # Fill in any missing numbers with a default frequency of 0
            for num in range(1, 43):
                if num not in frequency:
                    frequency[num] = 0
            
            # Create weighted combinations based on frequency
            weighted_nums = []
            for num, freq in frequency.items():
                # Add higher-frequency numbers more often
                weighted_nums.extend([num] * (freq + 1))
            
            # Keep track of combinations we've already checked
            checked_combos = set(tuple(combo['combination']) for combo in filtered_combinations)
            attempts = 0
            
            # Try to find more combinations
            while len(filtered_combinations) < count * 2 and attempts < 5000:
                attempts += 1
                
                # Generate a random 5-number combination
                if attempts % 2 == 0:
                    # Every other attempt, use weighted selection
                    combo = sorted(np.random.choice(weighted_nums, self.nums_per_draw, replace=False))
                else:
                    # Otherwise, use pure random selection from all numbers
                    combo = sorted(np.random.choice(range(1, 43), self.nums_per_draw, replace=False))
                
                combo_tuple = tuple(combo)
                if combo_tuple in checked_combos:
                    continue
                
                checked_combos.add(combo_tuple)
                
                # Check if it passes filters
                if self.filter_combination(combo):
                    # Calculate stats and score
                    stats = self._calculate_stats(combo)
                    score = self._calculate_score(combo, stats)
                    
                    filtered_combinations.append({
                        'combination': combo,
                        'score': score,
                        'sum': sum(combo),
                        'stats': stats
                    })
        
        # Sort by score and return top combinations
        sorted_combinations = sorted(filtered_combinations, key=lambda x: x['score'], reverse=True)
        return sorted_combinations[:count]
    
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
        d0, d1, d2, d3 = self.calculate_decade_distribution(numbers)
        
        # Sequential checks
        seq2, seq3 = self.count_sequential_numbers(numbers)
        
        # Modular analysis
        mod_total, mod_x = self.calculate_modular_total(numbers)
        
        return {
            'mean': mean,
            'median': median,
            'even': even_count,
            'odd': odd_count,
            'd0': d0,
            'd1': d1,
            'd2': d2,
            'd3': d3,
            'seq2': seq2,
            'seq3': seq3,
            'mod_total': mod_total,
            'mod_x': mod_x,
            'sum': sum(numbers)
        }
    
    def _calculate_score(self, combination, stats):
        """
        Calculate a composite score for a combination
        
        Parameters:
        combination (list): The 5-number combination
        stats (dict): Statistical measures for the combination
        
        Returns:
        float: A score between 0-100
        """
        # Get frequency of each number in last 30 days
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
        frequency = {row['ball']: row['freq'] for row in freq_results}
        
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
        for count in [stats['d0'], stats['d1'], stats['d2'], stats['d3']]:
            if count > 2:
                decade_balance -= 25
            elif count == 0:
                decade_balance -= 15
        
        # Sequential number penalty
        seq_penalty = stats['seq2'] * 5 + stats['seq3'] * 15
        
        # Sum score - prefer combinations with sums close to average winning sum
        query = "SELECT AVG(sum) as avg_sum FROM ga_f5_draws WHERE date >= DATE_SUB(NOW(), INTERVAL 365 DAY)"
        self.cursor.execute(query)
        result = self.cursor.fetchone()
        avg_sum = result['avg_sum'] if result and result['avg_sum'] else 100
        
        sum_score = 100 - min(abs(stats['sum'] - avg_sum), 30) * 3.33
        
        # Composite score with weights
        weights = {
            'frequency': 0.30,
            'even_odd': 0.15,
            'decade': 0.20,
            'seq_penalty': 0.10,
            'sum': 0.25
        }
        
        composite_score = (
            freq_score * weights['frequency'] + 
            even_odd_balance * weights['even_odd'] + 
            decade_balance * weights['decade'] -
            seq_penalty * weights['seq_penalty'] +
            sum_score * weights['sum']
        )
        
        # Normalize to 0-100 range
        final_score = max(0, min(100, composite_score))
        
        return final_score
    
    def display_predictions(self, predictions):
        """Display predictions in a formatted way"""
        print("\n============== GEORGIA FANTASY 5 PREDICTIONS ==============")
        print(f"Generated on: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")
        print("==========================================================\n")
        
        for i, pred in enumerate(predictions, 1):
            combo = pred['combination']
            score = pred['score']
            total = pred['sum']
            
            print(f"Prediction #{i}: {combo[0]}-{combo[1]}-{combo[2]}-{combo[3]}-{combo[4]}")
            print(f"Sum: {total} | Score: {score:.2f}%")
            print("----------------------------------------------------------")
    
    def save_predictions_to_csv(self, predictions, filename=None):
        """Save predictions to a CSV file"""
        if filename is None:
            filename = f"ga_f5_predictions_{datetime.now().strftime('%Y%m%d')}.csv"
        
        # Create rows for DataFrame
        rows = []
        for i, pred in enumerate(predictions, 1):
            combo = pred['combination']
            row = {
                'Prediction': i,
                'Ball1': combo[0],
                'Ball2': combo[1],
                'Ball3': combo[2],
                'Ball4': combo[3],
                'Ball5': combo[4],
                'Sum': pred['sum'],
                'Score': pred['score'],
                'Generated': datetime.now().strftime('%Y-%m-%d %H:%M:%S')
            }
            rows.append(row)
        
        # Create and save DataFrame
        df = pd.DataFrame(rows)
        df.to_csv(filename, index=False)
        
        print(f"\nPredictions saved to {filename}")
        return filename
    
    def close(self):
        """Close database connection"""
        if self.conn:
            self.conn.close()

def main():
    # Set up command line arguments
    parser = argparse.ArgumentParser(description='Georgia Fantasy 5 Prediction Generator')
    
    # Database connection settings
    parser.add_argument('--host', default='localhost', help='MySQL host')
    parser.add_argument('--user', default='root', help='MySQL username')
    parser.add_argument('--password', default='', help='MySQL password')
    parser.add_argument('--database', default='ga_f5_lotto', help='MySQL database name')
    
    # Filter settings
    parser.add_argument('--seq2', type=int, default=1, help='Maximum sequential pairs allowed')
    parser.add_argument('--seq3', type=int, default=0, help='Maximum sequential triplets allowed')
    parser.add_argument('--mod_tot', type=int, default=2, help='Maximum modular total allowed')
    parser.add_argument('--min_sum', type=int, default=80, help='Minimum sum allowed')
    parser.add_argument('--max_sum', type=int, default=120, help='Maximum sum allowed')
    
    # Output settings
    parser.add_argument('--count', type=int, default=10, help='Number of predictions to generate')
    parser.add_argument('--output', help='Output CSV filename (default: ga_f5_predictions_YYYYMMDD.csv)')
    
    # Parse arguments
    args = parser.parse_args()
    
    # Database configuration
    db_config = {
        'host': args.host,
        'user': args.user,
        'password': args.password,
        'database': args.database
    }
    
    try:
        # Initialize predictor
        predictor = GeorgiaFantasy5Predictor(db_config)
        
        # Set filter settings
        predictor.max_seq2 = args.seq2
        predictor.max_seq3 = args.seq3
        predictor.max_mod_tot = args.mod_tot
        predictor.sum_range = (args.min_sum, args.max_sum)
        
        # Generate predictions
        predictions = predictor.generate_predictions(count=args.count)
        
        # Display predictions
        predictor.display_predictions(predictions)
        
        # Save predictions to CSV
        predictor.save_predictions_to_csv(predictions, filename=args.output)
        
    except Exception as e:
        print(f"Error: {e}")
        return 1
    finally:
        # Clean up
        if 'predictor' in locals():
            predictor.close()
    
    return 0

if __name__ == "__main__":
    sys.exit(main())
