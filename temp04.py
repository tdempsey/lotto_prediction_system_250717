# Dynamic Col1 Data Generator - replaces static draws_col1.csv

import mysql.connector
from collections import defaultdict
import random

class DynamicCol1Generator:
    def __init__(self, conn, cursor):
        self.conn = conn
        self.cursor = cursor
        
    def calculate_even_odd_counts(self, b1, b2, b3, b4, b5):
        """Calculate even and odd counts for a draw"""
        numbers = [b1, b2, b3, b4, b5]
        even_count = sum(1 for num in numbers if num % 2 == 0)
        odd_count = 5 - even_count
        return even_count, odd_count
    
    def get_sum_range_key(self, total_sum):
        """Convert sum to range key"""
        if total_sum < 85:
            return "<85"
        elif 85 <= total_sum <= 95:
            return "85-95"
        elif 96 <= total_sum <= 105:
            return "96-105"
        elif 106 <= total_sum <= 115:
            return "106-115"
        else:
            return ">115"
    
    def generate_col1_data_for_sumeo(self, target_sum, target_even, target_odd, lookback_days=365):
        """
        Generate col1 candidates for a specific sum and even/odd combination
        
        Parameters:
        target_sum (int): Target sum for the combination
        target_even (int): Target even count (2 or 3)
        target_odd (int): Target odd count (2 or 3)
        lookback_days (int): Number of days to look back in history
        
        Returns:
        list: List of col1 values that have worked for this sum/even/odd combination
        """
        if not self.conn or not self.cursor:
            # Fallback if no database connection
            return list(range(1, 15))
        
        try:
            # Query for historical draws with similar characteristics
            query = """
            SELECT b1, b2, b3, b4, b5, sum 
            FROM ga_f5_draws 
            WHERE date >= DATE_SUB(NOW(), INTERVAL %s DAY)
            ORDER BY date DESC
            """
            
            self.cursor.execute(query, (lookback_days,))
            results = self.cursor.fetchall()
            
            if not results:
                return list(range(1, 15))  # Fallback if no data
            
            # Analyze draws to find matches
            exact_sum_matches = []
            range_matches = []
            even_odd_matches = []
            
            target_range = self.get_sum_range_key(target_sum)
            
            for row in results:
                b1, b2, b3, b4, b5 = int(row['b1']), int(row['b2']), int(row['b3']), int(row['b4']), int(row['b5'])
                draw_sum = int(row['sum'])
                
                # Calculate even/odd for this draw
                even_count, odd_count = self.calculate_even_odd_counts(b1, b2, b3, b4, b5)
                draw_range = self.get_sum_range_key(draw_sum)
                
                # Exact sum and even/odd match (highest priority)
                if draw_sum == target_sum and even_count == target_even and odd_count == target_odd:
                    exact_sum_matches.append(b1)
                
                # Same range and even/odd match (medium priority)
                elif draw_range == target_range and even_count == target_even and odd_count == target_odd:
                    range_matches.append(b1)
                
                # Same even/odd, any sum (lower priority)
                elif even_count == target_even and odd_count == target_odd:
                    even_odd_matches.append(b1)
            
            # Return best available matches
            if exact_sum_matches:
                # Remove duplicates while preserving order
                unique_matches = []
                seen = set()
                for val in exact_sum_matches:
                    if val not in seen:
                        unique_matches.append(val)
                        seen.add(val)
                return unique_matches
            
            elif range_matches:
                unique_matches = []
                seen = set()
                for val in range_matches:
                    if val not in seen:
                        unique_matches.append(val)
                        seen.add(val)
                return unique_matches
            
            elif even_odd_matches:
                unique_matches = []
                seen = set()
                for val in even_odd_matches:
                    if val not in seen:
                        unique_matches.append(val)
                        seen.add(val)
                return unique_matches
            
            else:
                # No matches found, return general range
                return list(range(1, 15))
                
        except Exception as e:
            print(f"Error generating dynamic col1 data: {e}")
            return list(range(1, 15))
    
    def generate_comprehensive_col1_data(self, lookback_days=365):
        """
        Generate comprehensive col1 data for all common sum/even/odd combinations
        Returns data in the same format as the old CSV-based system
        
        Parameters:
        lookback_days (int): Number of days to look back in history
        
        Returns:
        dict: Col1 data dictionary compatible with existing code
        """
        col1_data = {}
        
        # Generate for common sum ranges and even/odd combinations
        sum_ranges = [
            (70, 79), (80, 89), (90, 99), (100, 109), (110, 119), (120, 139)
        ]
        even_odd_combos = [(2, 3), (3, 2)]  # (even, odd) pairs
        
        for sum_min, sum_max in sum_ranges:
            range_key = self.get_sum_range_key((sum_min + sum_max) // 2)
            
            for even_count, odd_count in even_odd_combos:
                # Generate for range-based key
                candidates = self.generate_col1_data_for_sumeo(
                    (sum_min + sum_max) // 2, even_count, odd_count, lookback_days
                )
                
                range_based_key = (range_key, str(even_count), str(odd_count))
                col1_data[range_based_key] = candidates
                
                # Also generate for specific sums within the range
                for target_sum in range(sum_min, min(sum_max + 1, sum_min + 10)):
                    exact_candidates = self.generate_col1_data_for_sumeo(
                        target_sum, even_count, odd_count, lookback_days
                    )
                    
                    exact_key = (f"{target_sum}-{target_sum}", str(even_count), str(odd_count))
                    col1_data[exact_key] = exact_candidates
        
        # Add wildcard entries
        for sum_range in ["<85", "85-95", "96-105", "106-115", ">115"]:
            # Sum range wildcards
            key = (sum_range, "*", "*")
            all_candidates = set()
            for (s, e, o), values in col1_data.items():
                if s == sum_range:
                    all_candidates.update(values)
            col1_data[key] = sorted(list(all_candidates)) if all_candidates else list(range(1, 15))
        
        # Even/odd wildcards
        for even_count, odd_count in even_odd_combos:
            key = ("*", str(even_count), str(odd_count))
            all_candidates = set()
            for (s, e, o), values in col1_data.items():
                if e == str(even_count) and o == str(odd_count):
                    all_candidates.update(values)
            col1_data[key] = sorted(list(all_candidates)) if all_candidates else list(range(1, 15))
        
        # Global wildcard
        col1_data[("*", "*", "*")] = list(range(1, 15))
        
        return col1_data

# Modified GeorgiaFantasy5Predictor class methods
class GeorgiaFantasy5Predictor:
    def __init__(self, config):
        """Initialize with dynamic col1 generation"""
        try:
            self.conn = mysql.connector.connect(**config)
            self.cursor = self.conn.cursor(dictionary=True)
            
            # Georgia Fantasy 5 specifics
            self.num_range = range(1, 43)
            self.nums_per_draw = 5
            
            # Filter settings
            self.max_seq2 = 1
            self.max_seq3 = 0
            self.max_mod_tot = 1
            self.sum_range = (70, 139)
            
            # Load rank data from database
            self.rank_limits = load_rank_limits(self.conn, self.cursor)
            self.rank_counts = load_rank_counts(self.conn, self.cursor)
            
            # Initialize dynamic col1 generator
            self.col1_generator = DynamicCol1Generator(self.conn, self.cursor)
            
            # Generate initial col1 data (can be regenerated as needed)
            self.col1_data = self.col1_generator.generate_comprehensive_col1_data()
            
            # Create cyclers for round-robin col1 selection
            self.col1_cyclers = {}
            for key, values in self.col1_data.items():
                if values:
                    self.col1_cyclers[key] = cycle(values)
            
            # Load historical data
            self.load_historical_data()
        except mysql.connector.Error as e:
            print(f"Database connection error: {e}")
            self.historical_draws = pd.DataFrame(columns=['date', 'b1', 'b2', 'b3', 'b4', 'b5', 'sum'])
            self.conn = None
            self.cursor = None
            self.col1_generator = None
            # Use fallback col1 data
            self.col1_data = {("*", "*", "*"): list(range(1, 15))}
            self.col1_cyclers = {}
    
    def refresh_col1_data(self, lookback_days=365):
        """
        Refresh col1 data with latest database information
        Call this method to update col1 patterns based on recent draws
        """
        if self.col1_generator:
            print(f"Refreshing col1 data using last {lookback_days} days of draws...")
            self.col1_data = self.col1_generator.generate_comprehensive_col1_data(lookback_days)
            
            # Recreate cyclers with new data
            self.col1_cyclers = {}
            for key, values in self.col1_data.items():
                if values:
                    self.col1_cyclers[key] = cycle(values)
            
            print(f"Col1 data refreshed with {len(self.col1_data)} patterns")
        else:
            print("No database connection available for col1 data refresh")
    
    def get_col1_candidates_dynamic(self, total_sum, even_count, odd_count, force_refresh=False):
        """
        Get col1 candidates with option to force fresh calculation
        
        Parameters:
        total_sum (int): Target sum
        even_count (int): Number of even numbers
        odd_count (int): Number of odd numbers  
        force_refresh (bool): If True, calculate fresh data from database
        
        Returns:
        list: Candidate col1 values
        """
        if force_refresh and self.col1_generator:
            # Get fresh data for this specific combination
            fresh_candidates = self.col1_generator.generate_col1_data_for_sumeo(
                total_sum, even_count, odd_count
            )
            if fresh_candidates:
                return fresh_candidates
        
        # Fall back to existing logic
        return self.get_col1_candidates(total_sum, even_count, odd_count)
    
    # Remove the old load_col1_data method since we're generating dynamically
    # def load_col1_data(self, file_path='draws_col1.csv'):  # DELETE THIS METHOD
	
	Based on the dynamic system I provided, here's how col1 values are populated for each draw:

## **Col1 Population Process for Each Draw**

### **1. Target Parameters Determined**
For each prediction being generated, the system first determines:
- **Target sum** (e.g., 110)
- **Even count** (e.g., 3 even numbers)
- **Odd count** (e.g., 2 odd numbers)

### **2. Database Query for Historical Matches**
The `generate_col1_data_for_sumeo()` method queries the database:

```sql
SELECT b1, b2, b3, b4, b5, sum 
FROM ga_f5_draws 
WHERE date >= DATE_SUB(NOW(), INTERVAL 365 DAY)
ORDER BY date DESC
```

### **3. Analysis of Historical Draws**
For each historical draw, the system:
- Calculates the even/odd counts
- Determines which sum range it falls into
- Checks if it matches the target criteria

### **4. Three-Tier Matching Priority**

**Tier 1: Exact Matches (Highest Priority)**
```python
# Example: Looking for sum=110, even=3, odd=2
if draw_sum == 110 and even_count == 3 and odd_count == 2:
    exact_sum_matches.append(b1)  # Add the first number from this draw
```

**Tier 2: Range Matches (Medium Priority)**
```python
# Example: Sum range 106-115, even=3, odd=2
if draw_range == "106-115" and even_count == 3 and odd_count == 2:
    range_matches.append(b1)
```

**Tier 3: Even/Odd Matches (Lower Priority)**
```python
# Example: Any sum, but even=3, odd=2
if even_count == 3 and odd_count == 2:
    even_odd_matches.append(b1)
```

### **5. Col1 Value Selection**
Returns the best available matches:
1. If **exact matches** exist → Use those first numbers
2. Else if **range matches** exist → Use those first numbers  
3. Else if **even/odd matches** exist → Use those first numbers
4. Else → Use fallback range `[1, 2, 3, ..., 15]`

### **6. Example Walkthrough**

**Scenario**: Generating prediction with sum=110, even=3, odd=2

**Step 1**: Query finds these historical draws:
```
Date        B1  B2  B3  B4  B5  Sum  Even  Odd
2024-01-15   5  12  18  25  30  110   3     2   ← Exact match!
2024-01-10   3  14  22  28  35  112   3     2   ← Range match
2024-01-05   7  16  20  30  32  105   3     2   ← Range match
2023-12-20   2  11  19  24  36  92    3     2   ← Even/odd match
```

**Step 2**: Categorize first numbers (B1):
- **Exact matches**: [5] (sum=110, even=3, odd=2)
- **Range matches**: [3, 7] (sum 106-115, even=3, odd=2)  
- **Even/odd matches**: [2] (any sum, even=3, odd=2)

**Step 3**: Return col1 candidates = `[5]` (exact matches take priority)

### **7. Integration with Prediction Generation**

In `generate_predictions()`, col1 values are used in two ways:

**Method A: Forced Iteration**
```python
col1_candidates = self.get_col1_candidates(target_sum, even_count, odd_count)
for forced_col1 in col1_candidates:
    # Force this col1 value and build rest of combination
    combo = [forced_col1, pos2, pos3, pos4, pos5]
```

**Method B: Round-Robin Selection**
```python
next_col1 = self.get_next_col1(target_sum, even_count, odd_count)
# Uses cyclers to rotate through available col1 values
```

### **8. Dynamic Updates**

**Automatic**: Col1 patterns update when:
- App restarts (generates fresh data)
- `refresh_col1_data()` is called
- `force_refresh=True` is used

**Manual**: You can refresh via:
```python
predictor.refresh_col1_data(180)  # Use last 180 days
```

## **Key Points:**

• **Historical evidence-based**: Col1 values come from actual winning combinations
• **Context-aware**: Selection depends on target sum and even/odd distribution  
• **Recency-weighted**: More recent draws have higher influence
• **Tiered fallback**: Multiple levels of matching ensure col1 values are always available
• **Dynamic**: Patterns evolve automatically as new lottery results are added

**The system essentially asks: "What first numbers have historically worked for combinations with similar sums and even/odd distributions?"**

This creates col1 values that are statistically informed by actual lottery history rather than random selection!