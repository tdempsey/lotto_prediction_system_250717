import mysql.connector
import decimal
import random
from datetime import datetime

# Helper function to convert Decimal to float
def convert_decimal(value):
    if isinstance(value, decimal.Decimal):
        return float(value)
    return value

def main():
    # Connect to the database
    print("Connecting to database...")
    try:
        conn = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="ga_f5_lotto"
        )
        cursor = conn.cursor(dictionary=True)
        print("Connected successfully!")
    except mysql.connector.Error as e:
        print(f"Error connecting to MySQL: {e}")
        return
    
    # Get the most recent draws
    print("\nFetching most recent draws...")
    cursor.execute("SELECT * FROM ga_f5_draws ORDER BY date DESC LIMIT 5")
    recent_draws = cursor.fetchall()
    
    print("\nMost recent draws:")
    for draw in recent_draws:
        date = draw['date']
        numbers = [
            int(draw['b1']), 
            int(draw['b2']), 
            int(draw['b3']), 
            int(draw['b4']), 
            int(draw['b5'])
        ]
        print(f"{date}: {'-'.join(str(n) for n in numbers)} (Sum: {draw['sum']})")
    
    # Get filter settings
    print("\nFilter settings:")
    max_seq2 = 1
    max_seq3 = 0
    max_mod_tot = 2
    min_sum = 80
    max_sum = 120
    num_predictions = 10
    
    print(f"Maximum sequential pairs: {max_seq2}")
    print(f"Maximum sequential triplets: {max_seq3}")
    print(f"Maximum modular total: {max_mod_tot}")
    print(f"Sum range: {min_sum} to {max_sum}")
    print(f"Number of predictions to generate: {num_predictions}")
    
    # Get position candidates for scaffolding approach
    print("\nGetting position candidates for scaffolding approach...")
    
    # Position 1 candidates
    cursor.execute("""
    SELECT b1, COUNT(*) as freq FROM ga_f5_draws 
    WHERE date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    GROUP BY b1 ORDER BY freq DESC LIMIT 15
    """)
    pos1_candidates = []
    for row in cursor.fetchall():
        pos1_candidates.append(int(row['b1']))
    
    # Position 3 candidates
    cursor.execute("""
    SELECT b3, COUNT(*) as freq FROM ga_f5_draws 
    WHERE date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    GROUP BY b3 ORDER BY freq DESC LIMIT 15
    """)
    pos3_candidates = []
    for row in cursor.fetchall():
        pos3_candidates.append(int(row['b3']))
    
    # Position 5 candidates
    cursor.execute("""
    SELECT b5, COUNT(*) as freq FROM ga_f5_draws 
    WHERE date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
    GROUP BY b5 ORDER BY freq DESC LIMIT 15
    """)
    pos5_candidates = []
    for row in cursor.fetchall():
        pos5_candidates.append(int(row['b5']))
    
    # Fill in any missing candidates
    all_nums = list(range(1, 43))
    if len(pos1_candidates) < 10:
        pos1_candidates.extend([n for n in all_nums if n not in pos1_candidates][:10-len(pos1_candidates)])
    if len(pos3_candidates) < 10:
        pos3_candidates.extend([n for n in all_nums if n not in pos3_candidates][:10-len(pos3_candidates)])
    if len(pos5_candidates) < 10:
        pos5_candidates.extend([n for n in all_nums if n not in pos5_candidates][:10-len(pos5_candidates)])
    
    print(f"Position 1 candidates: {pos1_candidates[:5]}...")
    print(f"Position 3 candidates: {pos3_candidates[:5]}...")
    print(f"Position 5 candidates: {pos5_candidates[:5]}...")
    
    # Generate combinations
    print("\nGenerating combinations...")
    filtered_combinations = []
    
    # Helper functions for filtering
    def count_sequential_numbers(numbers):
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
    
    def calculate_modular_total(numbers):
        mod_counts = [0] * 10
        
        for num in numbers:
            mod_counts[num % 10] += 1
        
        # Sum the count of duplicates
        mod_total = sum(max(0, count-1) for count in mod_counts)
        
        # Count moduli with more than 2 numbers
        mod_x = sum(1 for count in mod_counts if count > 2)
        
        return mod_total, mod_x
    
    def calculate_decade_distribution(numbers):
        d0, d1, d2, d3, d4 = 0, 0, 0, 0, 0
        
        for num in numbers:
            if 1 <= num <= 9:
                d0 += 1
            elif 10 <= num <= 19:
                d1 += 1
            elif 20 <= num <= 29:
                d2 += 1
            elif 30 <= num <= 39:
                d3 += 1
            else:
                d4 += 1
        
        return d0, d1, d2, d3, d4
    
    def check_duplicates_from_previous(numbers, recent_draws, max_draws=3):
        dup_counts = []
        
        # Individual draw duplicate counts
        for i in range(min(max_draws, len(recent_draws))):
            draw = recent_draws[i]
            previous_draw = [
                int(draw['b1']), int(draw['b2']), int(draw['b3']), 
                int(draw['b4']), int(draw['b5'])
            ]
            dup_count = len(set(numbers).intersection(set(previous_draw)))
            dup_counts.append(dup_count)
        
        # Fill with zeros if not enough draws
        while len(dup_counts) < max_draws:
            dup_counts.append(0)
        
        # Calculate cumulative duplicates
        cumulative_dups = []
        for i in range(1, max_draws + 1):
            # All numbers from the last i draws
            all_previous_nums = []
            for j in range(i):
                if j < len(recent_draws):
                    draw = recent_draws[j]
                    all_previous_nums.extend([
                        int(draw['b1']), int(draw['b2']), int(draw['b3']), 
                        int(draw['b4']), int(draw['b5'])
                    ])
            
            # Count unique duplicates
            unique_prevs = set(all_previous_nums)
            dup_count = len(set(numbers).intersection(unique_prevs))
            cumulative_dups.append(dup_count)
        
        return dup_counts, cumulative_dups
    
    def filter_combination(combination):
        # Even/Odd distribution
        even_count = sum(1 for num in combination if num % 2 == 0)
        odd_count = 5 - even_count
        
        if not (2 <= even_count <= 3 and 2 <= odd_count <= 3):
            return False
            
        # Sequential numbers check
        seq2, seq3 = count_sequential_numbers(combination)
        if seq2 > max_seq2 or seq3 > max_seq3:
            return False
            
        # Modular totals check
        mod_total, mod_x = calculate_modular_total(combination)
        if mod_total > max_mod_tot or mod_x > 0:
            return False
            
        # Decade distribution check
        d0, d1, d2, d3 = calculate_decade_distribution(combination)
        if d0 > 2 or d1 > 2 or d2 > 2 or d3 > 2:
            return False
            
        # Duplicate check from previous draws
        dup_counts, cumulative_dups = check_duplicates_from_previous(combination, recent_draws)
        
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
        if not (min_sum <= total_sum <= max_sum):
            return False
            
        # All filters passed
        return True
    
    # Try to find combinations using scaffolding approach
    scaffold_count = 0
    for pos1 in pos1_candidates[:5]:
        for pos3 in pos3_candidates[:5]:
            if pos1 >= pos3:  # Must be ascending
                continue
                
            for pos5 in pos5_candidates[:5]:
                if pos3 >= pos5:  # Must be ascending
                    continue
                
                # Try all possible positions 2 and 4
                for pos2 in range(pos1 + 1, pos3):
                    for pos4 in range(pos3 + 1, pos5):
                        combo = sorted([pos1, pos2, pos3, pos4, pos5])
                        
                        if filter_combination(combo):
                            filtered_combinations.append({
                                'combination': combo,
                                'sum': sum(combo)
                            })
                            scaffold_count += 1
                            if scaffold_count >= num_predictions * 2:
                                break
                    if scaffold_count >= num_predictions * 2:
                        break
                if scaffold_count >= num_predictions * 2:
                    break
            if scaffold_count >= num_predictions * 2:
                break
        if scaffold_count >= num_predictions * 2:
            break
    
    # If we don't have enough, get frequency data for weighting random selections
    if len(filtered_combinations) < num_predictions:
        print(f"Only found {len(filtered_combinations)} combinations using scaffolding, generating more...")
        
        cursor.execute("""
        SELECT ball, COUNT(*) as freq FROM (
            SELECT b1 as ball FROM ga_f5_draws WHERE date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            UNION ALL SELECT b2 as ball FROM ga_f5_draws WHERE date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            UNION ALL SELECT b3 as ball FROM ga_f5_draws WHERE date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            UNION ALL SELECT b4 as ball FROM ga_f5_draws WHERE date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            UNION ALL SELECT b5 as ball FROM ga_f5_draws WHERE date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        ) as all_balls
        GROUP BY ball
        ORDER BY freq DESC
        """)
        
        frequency = {}
        for row in cursor.fetchall():
            ball = int(row['ball'])
            frequency[ball] = convert_decimal(row['freq'])
        
        # Fill in missing numbers
        for num in range(1, 43):
            if num not in frequency:
                frequency[num] = 0
        
        # Create weighted list for sampling
        weighted_nums = []
        for num, freq in frequency.items():
            # Add higher-frequency numbers more often
            weight = int(freq) + 1
            weighted_nums.extend([num] * weight)
        
        # Track combinations we've already checked
        checked_combos = set(tuple(combo['combination']) for combo in filtered_combinations)
        attempts = 0
        
        # Generate random combinations
        while len(filtered_combinations) < num_predictions * 2 and attempts < 5000:
            attempts += 1
            
            # Get 5 random numbers, with higher weight for frequent numbers
            combo = sorted(random.sample(weighted_nums, 5))
            
            # Ensure no duplicates
            if len(set(combo)) < 5:
                continue
                
            combo_tuple = tuple(combo)
            if combo_tuple in checked_combos:
                continue
                
            checked_combos.add(combo_tuple)
            
            # Check if it passes filters
            if filter_combination(combo):
                filtered_combinations.append({
                    'combination': combo,
                    'sum': sum(combo)
                })
    
    # Sort by sum (simpler than calculating a score)
    filtered_combinations.sort(key=lambda x: x['sum'])
    
    # Return top predictions
    predictions = filtered_combinations[:num_predictions]
    
    # Display predictions
    print("\n============== GEORGIA FANTASY 5 PREDICTIONS ==============")
    print(f"Generated on: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")
    print("==========================================================\n")
    
    for i, pred in enumerate(predictions, 1):
        combo = pred['combination']
        total = pred['sum']
        
        print(f"Prediction #{i}: {combo[0]}-{combo[1]}-{combo[2]}-{combo[3]}-{combo[4]}")
        print(f"Sum: {total}")
        print("----------------------------------------------------------")
    
    # Close database connection
    cursor.close()
    conn.close()

if __name__ == "__main__":
    main()
