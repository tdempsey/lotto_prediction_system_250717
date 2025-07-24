#!/usr/bin/env python3
"""
Georgia Fantasy 5 Lottery - 1000 Draw Generator
Generates 1000 unique lottery draws for GA Fantasy 5 using the same filtering logic
as the main prediction system.
"""

import random
import itertools
from datetime import datetime
import csv
import json
import decimal
try:
    import numpy as np
    NUMPY_AVAILABLE = True
except ImportError:
    NUMPY_AVAILABLE = False
    print("NumPy not available - some statistical features will be limited")

try:
    import mysql.connector
    MYSQL_AVAILABLE = True
except ImportError:
    MYSQL_AVAILABLE = False
    print("MySQL connector not available - using default rank data")


class GAFantasy5Generator:
    """Generate GA Fantasy 5 lottery draws with comprehensive filtering"""
    
    def __init__(self):
        self.num_range = (1, 42)  # GA Fantasy 5 range
        self.nums_per_draw = 5
        
        # Filter configuration (matching main app defaults)
        self.max_seq2 = 1  # Max sequential pairs
        self.max_seq3 = 0  # Max sequential triplets
        self.max_mod_tot = 1  # Max modular total
        self.sum_range = (70, 139)  # Sum range limits
        
        # Database configuration
        self.db_config = {
            'host': 'localhost',
            'user': 'root',
            'password': '',
            'database': 'ga_f5_lotto'
        }
        self.conn = None
        self.cursor = None
        
        # Load historical data and rank analysis data
        self.recent_draws = self.load_recent_draws()
        self.rank_limits = self.load_rank_limits()
        self.rank_counts = self.load_rank_counts()
        print(f"Loaded {len(self.recent_draws)} recent draws for duplicate filtering")
        print(f"Loaded rank limits: {self.rank_limits}")
        print(f"Loaded rank counts: {len(self.rank_counts)} values")
        
    def count_sequential_numbers(self, combination):
        """Count sequential number pairs and triplets"""
        sorted_combo = sorted(combination)
        seq2_count = 0
        seq3_count = 0
        
        # Count sequential pairs
        for i in range(len(sorted_combo) - 1):
            if sorted_combo[i+1] == sorted_combo[i] + 1:
                seq2_count += 1
                
        # Count sequential triplets
        for i in range(len(sorted_combo) - 2):
            if (sorted_combo[i+1] == sorted_combo[i] + 1 and 
                sorted_combo[i+2] == sorted_combo[i] + 2):
                seq3_count += 1
                
        return seq2_count, seq3_count
    
    def calculate_modular_total(self, combination):
        """Calculate modular total (numbers sharing same remainder when divided by 10)"""
        mod_counts = {}
        for num in combination:
            mod = num % 10
            mod_counts[mod] = mod_counts.get(mod, 0) + 1
        
        # Count total duplicates and groups with more than 2 numbers
        mod_total = sum(count - 1 for count in mod_counts.values() if count > 1)
        mod_x = sum(1 for count in mod_counts.values() if count > 2)
        
        # Count number of mod groups with 3 or more occurrences
        modx = sum(1 for count in mod_counts.values() if count >= 3)
        
        return mod_total, mod_x, modx
    
    def calculate_decade_distribution(self, combination):
        """Calculate decade distribution (1-9, 10-19, 20-29, 30-39, 40-42)"""
        decades = [0, 0, 0, 0, 0]  # d0, d1, d2, d3, d4
        
        for num in combination:
            if 1 <= num <= 9:
                decades[0] += 1
            elif 10 <= num <= 19:
                decades[1] += 1
            elif 20 <= num <= 29:
                decades[2] += 1
            elif 30 <= num <= 39:
                decades[3] += 1
            elif 40 <= num <= 42:
                decades[4] += 1
                
        return decades
    
    def load_recent_draws(self, count=10):
        """Load recent draws from multiple sources"""
        draws = []
        
        # Try MySQL database first
        if MYSQL_AVAILABLE:
            try:
                conn = mysql.connector.connect(**self.db_config)
                cursor = conn.cursor(dictionary=True)
                
                query = "SELECT b1, b2, b3, b4, b5 FROM ga_f5_draws ORDER BY date DESC LIMIT %s"
                cursor.execute(query, (count,))
                results = cursor.fetchall()
                
                if results:
                    draws = [[row['b1'], row['b2'], row['b3'], row['b4'], row['b5']] for row in results]
                    cursor.close()
                    conn.close()
                    print(f"Loaded {len(draws)} recent draws from MySQL database")
                    return draws
                    
                cursor.close()
                conn.close()
            except Exception as e:
                print(f"Error loading from database: {e}")
        
        # Try CSV files as fallback
        csv_files = [
            "data/georgia_fantasy5_data.csv",
            "ga_fantasy5_1000_draws.csv", 
            "ga_fantasy5_1000_draws_scored.csv"
        ]
        
        for filename in csv_files:
            try:
                with open(filename, 'r') as f:
                    reader = csv.DictReader(f)
                    rows = list(reader)[:count]  # Get first 'count' rows
                    
                    # Try different column formats
                    if 'b1' in rows[0]:  # Georgia fantasy format
                        draws = [[int(row['b1']), int(row['b2']), int(row['b3']), 
                                 int(row['b4']), int(row['b5'])] for row in rows]
                    elif 'Num1' in rows[0]:  # Generated draws format
                        draws = [[int(row['Num1']), int(row['Num2']), int(row['Num3']),
                                 int(row['Num4']), int(row['Num5'])] for row in rows]
                    
                    if draws:
                        print(f"Loaded {len(draws)} recent draws from {filename}")
                        return draws
                        
            except Exception as e:
                continue  # Try next file
        
        # If no data found, use some default recent draws
        print("No historical data found, using default recent draws")
        return [
            [5, 12, 23, 34, 42],  # Most recent draw
            [8, 15, 21, 28, 35],  # 2nd most recent
            [3, 17, 25, 31, 39]   # 3rd most recent
        ]
    
    def calculate_duplicates_from_previous(self, combination):
        """Calculate duplicates from previous draws with filtering rules"""
        if not self.recent_draws:
            return [], []
            
        dup_counts = []
        cumulative_dups = []
        cumulative_numbers = set()
        
        # Check each recent draw
        for i, recent_draw in enumerate(self.recent_draws):
            # Count duplicates with this specific draw
            duplicates = len(set(combination) & set(recent_draw))
            dup_counts.append(duplicates)
            
            # Add numbers to cumulative set
            cumulative_numbers.update(recent_draw)
            
            # Count cumulative duplicates up to this point
            cumulative_duplicates = len(set(combination) & cumulative_numbers)
            cumulative_dups.append(cumulative_duplicates)
            
            # Only check first 3 draws for filtering
            if i >= 2:
                break
        
        return dup_counts, cumulative_dups
    
    def filter_combination(self, combination):
        """Apply all filters to a combination"""
        # Basic checks
        if len(combination) != self.nums_per_draw:
            return False
            
        # Even/Odd distribution (2-3 even, 2-3 odd)
        even_count = sum(1 for num in combination if num % 2 == 0)
        odd_count = self.nums_per_draw - even_count
        
        if not (2 <= even_count <= 3 and 2 <= odd_count <= 3):
            return False
            
        # Sequential numbers check
        seq2, seq3 = self.count_sequential_numbers(combination)
        if seq2 > self.max_seq2 or seq3 > self.max_seq3:
            return False
            
        # Modular totals check
        mod_total, mod_x, modx = self.calculate_modular_total(combination)
        if mod_total > self.max_mod_tot or mod_x > 0:
            return False
            
        # Decade distribution check (max 2 per decade)
        decades = self.calculate_decade_distribution(combination)
        if any(d > 2 for d in decades):
            return False
            
        # Historical duplicate check from previous draws
        dup_counts, cumulative_dups = self.calculate_duplicates_from_previous(combination)
        
        # Apply filtering rules:
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
        
        return True
    
    def generate_random_combination(self):
        """Generate a random 5-number combination"""
        return sorted(random.sample(range(self.num_range[0], self.num_range[1] + 1), 
                                  self.nums_per_draw))
    
    def generate_draws(self, count=1000, max_attempts=100000, include_scores=True):
        """Generate specified number of unique filtered draws with scores"""
        draws = set()
        scored_draws = []
        attempts = 0
        
        print(f"Generating {count} unique GA Fantasy 5 draws...")
        print(f"Filter settings: seq2≤{self.max_seq2}, seq3≤{self.max_seq3}, "
              f"mod_tot≤{self.max_mod_tot}, sum_range={self.sum_range}")
        print(f"Rank analysis: {len(self.rank_limits)} limits, {len(self.rank_counts)} counts")
        
        while len(draws) < count and attempts < max_attempts:
            combination = self.generate_random_combination()
            
            if self.filter_combination(combination):
                combo_tuple = tuple(combination)
                if combo_tuple not in draws:
                    draws.add(combo_tuple)
                    
                    if include_scores:
                        score, stats = self.calculate_score(combination)
                        scored_draws.append({
                            'combination': list(combination),
                            'score': round(score, 4),
                            'sum': sum(combination),
                            'stats': stats
                        })
                    else:
                        scored_draws.append(list(combination))
                    
                    if len(draws) % 100 == 0:
                        print(f"Generated {len(draws)} draws...")
            
            attempts += 1
            
            if attempts % 10000 == 0:
                print(f"Attempts: {attempts}, Generated: {len(draws)}")
        
        if len(draws) < count:
            print(f"Warning: Only generated {len(draws)} draws after {attempts} attempts")
        
        if include_scores:
            # Sort by score (highest first)
            scored_draws.sort(key=lambda x: x['score'], reverse=True)
        
        return scored_draws
    
    def load_rank_limits(self):
        """Load rank limits from MySQL database or use defaults"""
        if not MYSQL_AVAILABLE:
            return [1, 1, 2, 3, 2, 3, 1, 1]  # Default fallback
            
        try:
            conn = mysql.connector.connect(**self.db_config)
            cursor = conn.cursor(dictionary=True)
            
            query = "SELECT rank_limit FROM ga_f5_rank_limits ORDER BY rank_id"
            cursor.execute(query)
            results = cursor.fetchall()
            
            if results:
                rank_limits = [int(row['rank_limit']) for row in results]
                cursor.close()
                conn.close()
                return rank_limits
            else:
                cursor.close()
                conn.close()
                return [1, 1, 2, 3, 2, 3, 1, 1]  # Default fallback
                
        except Exception as e:
            print(f"Error loading rank limits: {e}")
            return [1, 1, 2, 3, 2, 3, 1, 1]  # Default fallback
    
    def load_rank_counts(self):
        """Load rank counts from MySQL database or use defaults"""
        if not MYSQL_AVAILABLE:
            return [5, 5, 2, 1, 3, 5, 3, 5, 5, 5, 5, 4, 2, 5, 5, 3, 5, 4, 0, 4, 5, 2, 4, 5, 3, 5, 5, 0, 4, 3, 2, 1, 4, 5, 3, 5, 1, 4, 3, 3, 2, 5]
            
        try:
            conn = mysql.connector.connect(**self.db_config)
            cursor = conn.cursor(dictionary=True)
            
            query = "SELECT rank_count FROM ga_f5_rank_counts ORDER BY rank_id"
            cursor.execute(query)
            results = cursor.fetchall()
            
            if results:
                rank_counts = [int(row['rank_count']) for row in results]
                cursor.close()
                conn.close()
                return rank_counts
            else:
                cursor.close()
                conn.close()
                return [5, 5, 2, 1, 3, 5, 3, 5, 5, 5, 5, 4, 2, 5, 5, 3, 5, 4, 0, 4, 5, 2, 4, 5, 3, 5, 5, 0, 4, 3, 2, 1, 4, 5, 3, 5, 1, 4, 3, 3, 2, 5]
                
        except Exception as e:
            print(f"Error loading rank counts: {e}")
            return [5, 5, 2, 1, 3, 5, 3, 5, 5, 5, 5, 4, 2, 5, 5, 3, 5, 4, 0, 4, 5, 2, 4, 5, 3, 5, 5, 0, 4, 3, 2, 1, 4, 5, 3, 5, 1, 4, 3, 3, 2, 5]
    
    def calculate_stats(self, numbers):
        """Calculate statistical measures for a combination"""
        # Basic stats
        if NUMPY_AVAILABLE:
            numbers_array = np.array(numbers)
            mean = float(np.mean(numbers_array))
            median = float(np.median(numbers_array))
        else:
            mean = sum(numbers) / len(numbers)
            sorted_nums = sorted(numbers)
            n = len(sorted_nums)
            median = sorted_nums[n//2] if n % 2 == 1 else (sorted_nums[n//2-1] + sorted_nums[n//2]) / 2
        
        # Even/Odd distribution
        even_count = sum(1 for num in numbers if num % 2 == 0)
        odd_count = len(numbers) - even_count
        
        # Decade distribution
        d0, d1, d2, d3, d4 = self.calculate_decade_distribution(numbers)
        # Sequential checks
        seq2, seq3 = self.count_sequential_numbers(numbers)
        
        # Modular analysis
        mod_total, mod_x, modx = self.calculate_modular_total(numbers)
        
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
            'modx': modx,
            'sum': sum(numbers),
            'rank_analysis': rank_analysis
        }
        
        return stats
    
    def calculate_score(self, combination):
        """Calculate a composite score for a combination"""
        stats = self.calculate_stats(combination)
        
        # Default frequency (since we don't have historical data loaded)
        frequency = {num: 5 for num in combination}  # Default frequency
        
        # Calculate frequency score (0-100)
        max_possible_freq = 10
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
        avg_sum = 100  # Default average
        sum_score = 100 - min(abs(stats['sum'] - avg_sum), 30) * 3.33
        
        # Rank score - evaluate the combination based on rank data
        rank_score = 0
        total_rank_points = 0
        
        # Check each number against frequency-based rank limits
        for num in combination:
            # Get the frequency count for this number (num 1-42 maps to index 0-41)
            if 1 <= num <= 42 and (num - 1) < len(self.rank_counts):
                num_frequency = self.rank_counts[num - 1]  # Convert number to 0-based index
                
                # Find the appropriate rank limit based on frequency level
                # Lower frequencies get more lenient limits (higher rank limit index)
                if num_frequency == 0:
                    limit_index = 7  # Best rank - use most lenient limit
                elif num_frequency == 1:
                    limit_index = 6
                elif num_frequency == 2:
                    limit_index = 5  
                elif num_frequency == 3:
                    limit_index = 4
                elif num_frequency == 4:
                    limit_index = 3
                elif num_frequency == 5:
                    limit_index = 2
                elif num_frequency == 6:
                    limit_index = 1
                else:  # 7+
                    limit_index = 0  # Worst rank - use most restrictive limit
                
                # Get the rank limit for this frequency level
                if limit_index < len(self.rank_limits):
                    rank_limit = self.rank_limits[limit_index]
                    
                    # Award points if this number's frequency is within acceptable range
                    # (Lower frequency = better, so we want freq <= some threshold)
                    frequency_threshold = limit_index + 1  # Convert limit index to threshold
                    if num_frequency <= frequency_threshold:
                        rank_score += 20
                        
            total_rank_points += 20
        
        # Normalize rank score to 0-100 range
        if total_rank_points > 0:
            rank_score = (rank_score / total_rank_points) * 100
        else:
            rank_score = 50  # Default middle score
        
        # Combine all scores with weights
        final_score = (
            freq_score * 0.25 +          # 25% frequency weight
            even_odd_balance * 0.20 +    # 20% even/odd balance
            decade_balance * 0.20 +      # 20% decade balance
            sum_score * 0.15 +           # 15% sum score
            rank_score * 0.20            # 20% rank score
        ) - seq_penalty                  # Subtract sequential penalty
        
        # Ensure score is within 0-100 range
        final_score = max(0, min(100, final_score))
        
        return final_score, stats
    
    def save_draws_csv(self, draws, filename="ga_fantasy5_1000_draws_scored.csv"):
        """Save draws to CSV file with scores"""
        with open(filename, 'w', newline='') as csvfile:
            writer = csv.writer(csvfile)
            
            # Check if draws have score information
            if draws and isinstance(draws[0], dict) and 'score' in draws[0]:
                writer.writerow(['Rank', 'Score', 'Num1', 'Num2', 'Num3', 'Num4', 'Num5', 'Sum', 'Even', 'Odd', 'Seq2', 'Seq3', 'Mod', 'Modx', 'Dup1', 'Dup2', 'Dup3', 'r0', 'r1', 'r2', 'r3', 'r4', 'r5', 'r6', 'r7', 'Mean', 'Median'])
                
                for i, draw_data in enumerate(draws, 1):
                    combination = draw_data['combination']
                    stats = draw_data['stats']
                    
                    # Calculate duplicate counts for this combination
                    dup_counts, cumulative_dups = self.calculate_duplicates_from_previous(combination)
                    dup1 = dup_counts[0] if len(dup_counts) > 0 else 0
                    dup2 = cumulative_dups[1] if len(cumulative_dups) > 1 else 0  
                    dup3 = cumulative_dups[2] if len(cumulative_dups) > 2 else 0
                    
                    # Calculate individual rank scores for each rank level (r0-r7)
                    rank_scores = [0] * 8  # Initialize r0-r7 counters
                    
                    # Check each number against the frequency-based rank limits
                    for num in combination:
                        # Get the frequency count for this number (num 1-42 maps to index 0-41)
                        if 1 <= num <= 42 and (num - 1) < len(self.rank_counts):
                            num_frequency = self.rank_counts[num - 1]  # Convert number to 0-based index
                            
                            # Find the appropriate rank limit based on frequency level
                            # Lower frequencies get more lenient limits (higher rank limit index)
                            if num_frequency == 0:
                                limit_index = 7  # Best rank - use most lenient limit
                            elif num_frequency == 1:
                                limit_index = 6
                            elif num_frequency == 2:
                                limit_index = 5  
                            elif num_frequency == 3:
                                limit_index = 4
                            elif num_frequency == 4:
                                limit_index = 3
                            elif num_frequency == 5:
                                limit_index = 2
                            elif num_frequency == 6:
                                limit_index = 1
                            else:  # 7+
                                limit_index = 0  # Worst rank - use most restrictive limit
                            
                            # Increment the counter for this rank level
                            if 0 <= limit_index < 8:
                                rank_scores[limit_index] += 1
                    
                    row = [i, draw_data['score']] + combination + [
                        draw_data['sum'], 
                        stats['even'], 
                        stats['odd'],
                        stats['seq2'],
                        stats['seq3'], 
                        stats['mod_total'],
                        stats['modx'],
                        dup1,
                        dup2,
                        dup3
                    ] + rank_scores + [  # Add r0-r7 counts
                        round(stats['mean'], 2),
                        round(stats['median'], 2)
                    ]
                    writer.writerow(row)
            else:
                # Legacy format for backward compatibility
                writer.writerow(['Draw', 'Num1', 'Num2', 'Num3', 'Num4', 'Num5', 'Sum'])
                
                for i, draw in enumerate(draws, 1):
                    if isinstance(draw, dict):
                        combination = draw['combination']
                    else:
                        combination = draw
                    row = [i] + combination + [sum(combination)]
                    writer.writerow(row)
        
        print(f"Saved {len(draws)} draws to {filename}")
    
    def save_draws_json(self, draws, filename="ga_fantasy5_1000_draws_scored.json"):
        """Save draws to JSON file with scores and rank analysis"""
        data = {
            'generated_at': datetime.now().isoformat(),
            'total_draws': len(draws),
            'filter_settings': {
                'max_seq2': self.max_seq2,
                'max_seq3': self.max_seq3,
                'max_mod_tot': self.max_mod_tot,
                'sum_range': self.sum_range
            },
            'rank_analysis_config': {
                'rank_limits': self.rank_limits,
                'rank_counts_length': len(self.rank_counts)
            },
            'draws': draws
        }
        
        with open(filename, 'w') as jsonfile:
            json.dump(data, jsonfile, indent=2)
        
        print(f"Saved {len(draws)} draws to {filename}")
    
    def print_statistics(self, draws):
        """Print statistics about generated draws"""
        print(f"\n=== GA Fantasy 5 Draw Statistics ===")
        print(f"Total draws generated: {len(draws)}")
        
        # Handle both scored and unscored draws
        if draws and isinstance(draws[0], dict) and 'combination' in draws[0]:
            combinations = [draw['combination'] for draw in draws]
            scores = [draw['score'] for draw in draws] if 'score' in draws[0] else None
        else:
            combinations = draws
            scores = None
        
        # Sum distribution
        sums = [sum(combo) for combo in combinations]
        print(f"Sum range: {min(sums)} - {max(sums)}")
        print(f"Average sum: {sum(sums) / len(sums):.1f}")
        
        # Score distribution if available
        if scores:
            print(f"Score range: {min(scores):.4f} - {max(scores):.4f}")
            print(f"Average score: {sum(scores) / len(scores):.4f}")
        
        # Even/Odd distribution
        even_counts = [sum(1 for num in combo if num % 2 == 0) for combo in combinations]
        if NUMPY_AVAILABLE:
            print(f"Even count distribution: {dict(zip(*np.unique(even_counts, return_counts=True)))}")
        else:
            from collections import Counter
            print(f"Even count distribution: {dict(Counter(even_counts))}")
        
        # Sequential analysis
        seq2_counts = [self.count_sequential_numbers(combo)[0] for combo in combinations]
        seq3_counts = [self.count_sequential_numbers(combo)[1] for combo in combinations]
        
        if NUMPY_AVAILABLE:
            print(f"Sequential pairs: {dict(zip(*np.unique(seq2_counts, return_counts=True)))}")
            print(f"Sequential triplets: {dict(zip(*np.unique(seq3_counts, return_counts=True)))}")
        else:
            from collections import Counter
            print(f"Sequential pairs: {dict(Counter(seq2_counts))}")
            print(f"Sequential triplets: {dict(Counter(seq3_counts))}")
        
        # Top scoring draws (if scores available)
        if scores:
            print(f"\nTop 10 scoring draws:")
            for i, draw in enumerate(draws[:10], 1):
                combo = draw['combination']
                score = draw['score']
                print(f"{i:2d}: {combo} (sum: {sum(combo)}, score: {score:.4f})")
        else:
            print(f"\nFirst 10 draws:")
            for i, combo in enumerate(combinations[:10], 1):
                print(f"{i:2d}: {combo} (sum: {sum(combo)})")


def main():
    """Main function to generate 1000 GA Fantasy 5 draws with scoring"""
    generator = GAFantasy5Generator()
    
    # Generate 1000 draws with scores
    draws = generator.generate_draws(count=1000, include_scores=True)
    
    # Save to files with timestamp to avoid permission issues
    from datetime import datetime
    timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
    csv_file = f"ga_fantasy5_1000_draws_{timestamp}.csv"
    json_file = f"ga_fantasy5_1000_draws_{timestamp}.json"
    
    generator.save_draws_csv(draws, csv_file)
    generator.save_draws_json(draws, json_file)
    
    # Print statistics
    generator.print_statistics(draws)
    
    print(f"\nGeneration complete! Files saved:")
    print(f"- {csv_file}")
    print(f"- {json_file}")
    print(f"\nDraws are sorted by score (highest to lowest)")
    print(f"Scoring factors: frequency (25%), even/odd balance (20%), decade balance (20%), sum (15%), rank analysis (20%)")


if __name__ == "__main__":
    main()