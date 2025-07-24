#!/usr/bin/env python3
"""
GA Fantasy 5 Draw Summary Analyzer - Updated with File Output
Analyzes and summarizes 1000 generated lottery draws from CSV files.
Creates both console output and formatted text report files.
"""

import csv
import json
from collections import Counter, defaultdict
from datetime import datetime
import os
import glob

try:
    import matplotlib.pyplot as plt
    import numpy as np
    PLOTTING_AVAILABLE = True
except ImportError:
    PLOTTING_AVAILABLE = False
    print("Matplotlib/NumPy not available - charts will be skipped")


class LottoDrawSummary:
    """Analyze and summarize lottery draw data"""
    
    def __init__(self, csv_file=None):
        self.csv_file = csv_file or self.find_latest_csv()
        self.draws = []
        self.output_lines = []  # Store formatted output
        self.load_data()
        
    def find_latest_csv(self):
        """Find the most recent ga_fantasy5_*.csv file"""
        pattern = "ga_fantasy5_*_*.csv"
        files = glob.glob(pattern)
        if not files:
            raise FileNotFoundError("No GA Fantasy 5 CSV files found")
        
        # Sort by modification time, newest first
        files.sort(key=os.path.getmtime, reverse=True)
        return files[0]
    
    def load_data(self):
        """Load draw data from CSV file"""
        try:
            with open(self.csv_file, 'r') as f:
                reader = csv.DictReader(f)
                self.draws = list(reader)
            print(f"Loaded {len(self.draws)} draws from {self.csv_file}")
        except FileNotFoundError:
            print(f"File {self.csv_file} not found")
            self.draws = []
    
    def output(self, text="", console=True, file_only=False):
        """Output text to both console and stored lines"""
        if not file_only and console:
            print(text)
        self.output_lines.append(text)
    
    def analyze_sums(self):
        """Analyze sum distribution"""
        self.output("\n=== SUM DISTRIBUTION ===")
        sums = [int(draw['Sum']) for draw in self.draws]
        sum_counter = Counter(sums)
        
        self.output(f"Sum range: {min(sums)} - {max(sums)}")
        self.output(f"Average sum: {sum(sums) / len(sums):.2f}")
        self.output(f"Most common sums:")
        for sum_val, count in sum_counter.most_common(10):
            self.output(f"  Sum {sum_val}: {count} times ({count/len(self.draws)*100:.1f}%)")
        
        return sum_counter
    
    def analyze_positions(self):
        """Analyze number frequency by position (col1-col5)"""
        self.output("\n=== POSITION ANALYSIS (Col1-Col5) ===")
        
        positions = defaultdict(lambda: defaultdict(int))
        
        # Collect data for each position
        for draw in self.draws:
            positions[1][int(draw['Num1'])] += 1
            positions[2][int(draw['Num2'])] += 1
            positions[3][int(draw['Num3'])] += 1
            positions[4][int(draw['Num4'])] += 1
            positions[5][int(draw['Num5'])] += 1
        
        # Report top numbers for each position
        for pos in range(1, 6):
            self.output(f"\nColumn {pos} - Top 10 most frequent:")
            pos_counter = Counter(positions[pos])
            for num, count in pos_counter.most_common(10):
                self.output(f"  {num}: {count} times ({count/len(self.draws)*100:.1f}%)")
        
        return positions
    
    def analyze_overall_frequency(self):
        """Analyze overall number frequency across all positions"""
        self.output("\n=== OVERALL NUMBER FREQUENCY ===")
        
        all_numbers = []
        for draw in self.draws:
            all_numbers.extend([int(draw['Num1']), int(draw['Num2']), 
                              int(draw['Num3']), int(draw['Num4']), int(draw['Num5'])])
        
        number_counter = Counter(all_numbers)
        total_picks = len(all_numbers)
        
        self.output(f"Total number picks: {total_picks}")
        self.output(f"Expected frequency per number: {total_picks / 42:.1f}")
        
        self.output("\nHOTTEST numbers (most frequent):")
        for num, count in number_counter.most_common(10):
            self.output(f"  {num}: {count} times ({count/total_picks*100:.2f}%)")
        
        self.output("\nCOLDEST numbers (least frequent):")
        for num, count in number_counter.most_common()[-10:]:
            self.output(f"  {num}: {count} times ({count/total_picks*100:.2f}%)")
        
        # Find missing numbers
        all_possible = set(range(1, 43))
        drawn_numbers = set(all_numbers)
        missing = all_possible - drawn_numbers
        
        if missing:
            self.output(f"\nNumbers NEVER drawn: {sorted(missing)}")
        else:
            self.output("\nAll numbers 1-42 were drawn at least once")
        
        return number_counter
    
    def analyze_even_odd(self):
        """Analyze even/odd distribution"""
        self.output("\n=== EVEN/ODD DISTRIBUTION ===")
        
        even_counts = [int(draw['Even']) for draw in self.draws]
        even_counter = Counter(even_counts)
        
        self.output("Even number counts per draw:")
        for even_count, frequency in sorted(even_counter.items()):
            odd_count = 5 - even_count
            self.output(f"  {even_count} even, {odd_count} odd: {frequency} draws ({frequency/len(self.draws)*100:.1f}%)")
        
        return even_counter
    
    def analyze_decades(self):
        """Analyze decade distribution"""
        self.output("\n=== DECADE DISTRIBUTION ===")
        
        decade_totals = defaultdict(int)
        
        for draw in self.draws:
            numbers = [int(draw['Num1']), int(draw['Num2']), int(draw['Num3']), 
                      int(draw['Num4']), int(draw['Num5'])]
            
            for num in numbers:
                if 1 <= num <= 9:
                    decade_totals['1-9'] += 1
                elif 10 <= num <= 19:
                    decade_totals['10-19'] += 1
                elif 20 <= num <= 29:
                    decade_totals['20-29'] += 1
                elif 30 <= num <= 39:
                    decade_totals['30-39'] += 1
                elif 40 <= num <= 42:
                    decade_totals['40-42'] += 1
        
        total_numbers = sum(decade_totals.values())
        
        self.output("Numbers by decade:")
        for decade, count in sorted(decade_totals.items()):
            self.output(f"  {decade}: {count} numbers ({count/total_numbers*100:.1f}%)")
        
        return decade_totals
    
    def analyze_sequential(self):
        """Analyze sequential number patterns"""
        self.output("\n=== SEQUENTIAL ANALYSIS ===")
        
        seq2_counts = [int(draw['Seq2']) for draw in self.draws if 'Seq2' in draw]
        seq3_counts = [int(draw['Seq3']) for draw in self.draws if 'Seq3' in draw]
        
        if seq2_counts:
            seq2_counter = Counter(seq2_counts)
            self.output("Sequential pairs per draw:")
            for count, frequency in sorted(seq2_counter.items()):
                self.output(f"  {count} pairs: {frequency} draws ({frequency/len(self.draws)*100:.1f}%)")
        
        if seq3_counts:
            seq3_counter = Counter(seq3_counts)
            self.output("Sequential triplets per draw:")
            for count, frequency in sorted(seq3_counter.items()):
                self.output(f"  {count} triplets: {frequency} draws ({frequency/len(self.draws)*100:.1f}%)")
    
    def analyze_rank_distribution(self):
        """Analyze rank (r0-r7) distribution"""
        self.output("\n=== RANK DISTRIBUTION ANALYSIS ===")
        
        # Check if rank columns exist
        rank_cols = [f'r{i}' for i in range(8)]
        if not all(col in self.draws[0] for col in rank_cols):
            self.output("Rank columns (r0-r7) not found in data")
            return
        
        rank_totals = defaultdict(int)
        
        for draw in self.draws:
            for i in range(8):
                rank_totals[f'r{i}'] += int(draw[f'r{i}'])
        
        total_numbers = sum(rank_totals.values())
        
        self.output("Numbers by rank level (frequency groups):")
        self.output("  r0 = freq 7+ (worst), r7 = freq 0 (best)")
        for i in range(8):
            rank = f'r{i}'
            count = rank_totals[rank]
            self.output(f"  {rank}: {count} numbers ({count/total_numbers*100:.1f}%)")
    
    def analyze_scores(self):
        """Analyze score distribution"""
        self.output("\n=== SCORE DISTRIBUTION ===")
        
        scores = [float(draw['Score']) for draw in self.draws]
        
        self.output(f"Score range: {min(scores):.4f} - {max(scores):.4f}")
        self.output(f"Average score: {sum(scores)/len(scores):.4f}")
        
        # Create score bins
        score_bins = {}
        for score in scores:
            bin_key = f"{int(score//5)*5}-{int(score//5)*5+4}"
            score_bins[bin_key] = score_bins.get(bin_key, 0) + 1
        
        self.output("Score distribution by 5-point ranges:")
        for bin_range, count in sorted(score_bins.items()):
            self.output(f"  {bin_range}: {count} draws ({count/len(self.draws)*100:.1f}%)")
        
        return scores
    
    def analyze_duplicates(self):
        """Analyze historical duplicate patterns"""
        self.output("\n=== HISTORICAL DUPLICATE ANALYSIS ===")
        
        if 'Dup1' not in self.draws[0]:
            self.output("Duplicate columns not found in data")
            return
        
        dup1_counts = Counter(int(draw['Dup1']) for draw in self.draws)
        dup2_counts = Counter(int(draw['Dup2']) for draw in self.draws)
        dup3_counts = Counter(int(draw['Dup3']) for draw in self.draws)
        
        self.output("Duplicates from most recent draw (Dup1):")
        for count, frequency in sorted(dup1_counts.items()):
            self.output(f"  {count} numbers: {frequency} draws ({frequency/len(self.draws)*100:.1f}%)")
        
        self.output("Duplicates from last 2 draws (Dup2):")
        for count, frequency in sorted(dup2_counts.items()):
            self.output(f"  {count} numbers: {frequency} draws ({frequency/len(self.draws)*100:.1f}%)")
        
        self.output("Duplicates from last 3 draws (Dup3):")
        for count, frequency in sorted(dup3_counts.items()):
            self.output(f"  {count} numbers: {frequency} draws ({frequency/len(self.draws)*100:.1f}%)")
    
    def analyze_gaps_and_ranges(self):
        """Analyze number gaps and ranges"""
        self.output("\n=== GAP AND RANGE ANALYSIS ===")
        
        ranges = []
        gaps = []
        
        for draw in self.draws:
            numbers = sorted([int(draw['Num1']), int(draw['Num2']), int(draw['Num3']), 
                            int(draw['Num4']), int(draw['Num5'])])
            
            # Calculate range (max - min)
            draw_range = numbers[-1] - numbers[0]
            ranges.append(draw_range)
            
            # Calculate gaps between consecutive numbers
            draw_gaps = []
            for i in range(len(numbers)-1):
                gap = numbers[i+1] - numbers[i] - 1
                draw_gaps.append(gap)
            gaps.extend(draw_gaps)
        
        self.output(f"Number range per draw (max - min):")
        self.output(f"  Average range: {sum(ranges)/len(ranges):.1f}")
        self.output(f"  Range distribution: {min(ranges)} to {max(ranges)}")
        
        range_counter = Counter(ranges)
        self.output("Most common ranges:")
        for range_val, count in range_counter.most_common(5):
            self.output(f"  Range {range_val}: {count} draws ({count/len(self.draws)*100:.1f}%)")
        
        self.output(f"\nGaps between consecutive numbers:")
        self.output(f"  Average gap: {sum(gaps)/len(gaps):.1f}")
        
        gap_counter = Counter(gaps)
        self.output("Gap distribution:")
        for gap, count in sorted(gap_counter.items())[:10]:
            self.output(f"  Gap {gap}: {count} times ({count/len(gaps)*100:.1f}%)")
    
    def create_summary_report(self):
        """Create comprehensive summary report"""
        self.output("=" * 80)
        self.output(f"GA FANTASY 5 DRAW ANALYSIS REPORT")
        self.output(f"Generated: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")
        self.output(f"Data source: {self.csv_file}")
        self.output(f"Total draws analyzed: {len(self.draws)}")
        self.output("=" * 80)
        
        # Run all analyses
        self.analyze_sums()
        self.analyze_positions()
        self.analyze_overall_frequency()
        self.analyze_even_odd()
        self.analyze_decades()
        self.analyze_sequential()
        self.analyze_rank_distribution()
        self.analyze_scores()
        self.analyze_duplicates()
        self.analyze_gaps_and_ranges()
        
        self.output("\n" + "=" * 80)
        self.output("ANALYSIS COMPLETE")
        self.output("=" * 80)
    
    def save_output_file(self, filename=None):
        """Save the analysis output to a formatted file"""
        if filename is None:
            timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
            base_name = os.path.splitext(os.path.basename(self.csv_file))[0]
            filename = f"{base_name}_analysis_{timestamp}.txt"
        
        try:
            with open(filename, 'w') as f:
                for line in self.output_lines:
                    # Convert \n to actual newlines for file output
                    f.write(line.replace('\\n', '\n') + '\n')
            
            print(f"\nAnalysis report saved to: {filename}")
            return filename
        except Exception as e:
            print(f"Error saving output file: {e}")
            return None
    
    def create_charts(self):
        """Create visualization charts if matplotlib is available"""
        if not PLOTTING_AVAILABLE:
            return
        
        print("\nCreating visualization charts...")
        
        # Sum distribution chart
        sums = [int(draw['Sum']) for draw in self.draws]
        plt.figure(figsize=(12, 8))
        
        plt.subplot(2, 3, 1)
        plt.hist(sums, bins=20, edgecolor='black', alpha=0.7)
        plt.title('Sum Distribution')
        plt.xlabel('Sum')
        plt.ylabel('Frequency')
        
        # Score distribution
        scores = [float(draw['Score']) for draw in self.draws]
        plt.subplot(2, 3, 2)
        plt.hist(scores, bins=20, edgecolor='black', alpha=0.7)
        plt.title('Score Distribution')
        plt.xlabel('Score')
        plt.ylabel('Frequency')
        
        # Even/odd distribution
        even_counts = [int(draw['Even']) for draw in self.draws]
        plt.subplot(2, 3, 3)
        even_counter = Counter(even_counts)
        plt.bar(even_counter.keys(), even_counter.values())
        plt.title('Even Count Distribution')
        plt.xlabel('Number of Even Numbers')
        plt.ylabel('Frequency')
        
        # Overall number frequency
        all_numbers = []
        for draw in self.draws:
            all_numbers.extend([int(draw['Num1']), int(draw['Num2']), 
                              int(draw['Num3']), int(draw['Num4']), int(draw['Num5'])])
        
        plt.subplot(2, 3, 4)
        number_counter = Counter(all_numbers)
        numbers = sorted(number_counter.keys())
        frequencies = [number_counter[num] for num in numbers]
        plt.bar(numbers, frequencies)
        plt.title('Overall Number Frequency')
        plt.xlabel('Number')
        plt.ylabel('Frequency')
        
        # Decade distribution
        plt.subplot(2, 3, 5)
        decade_totals = defaultdict(int)
        for draw in self.draws:
            numbers = [int(draw['Num1']), int(draw['Num2']), int(draw['Num3']), 
                      int(draw['Num4']), int(draw['Num5'])]
            for num in numbers:
                if 1 <= num <= 9:
                    decade_totals['1-9'] += 1
                elif 10 <= num <= 19:
                    decade_totals['10-19'] += 1
                elif 20 <= num <= 29:
                    decade_totals['20-29'] += 1
                elif 30 <= num <= 39:
                    decade_totals['30-39'] += 1
                elif 40 <= num <= 42:
                    decade_totals['40-42'] += 1
        
        plt.bar(decade_totals.keys(), decade_totals.values())
        plt.title('Decade Distribution')
        plt.xlabel('Decade Range')
        plt.ylabel('Frequency')
        
        # Rank distribution
        if all(f'r{i}' in self.draws[0] for i in range(8)):
            plt.subplot(2, 3, 6)
            rank_totals = defaultdict(int)
            for draw in self.draws:
                for i in range(8):
                    rank_totals[f'r{i}'] += int(draw[f'r{i}'])
            
            plt.bar(rank_totals.keys(), rank_totals.values())
            plt.title('Rank Distribution (r0=worst, r7=best)')
            plt.xlabel('Rank Level')
            plt.ylabel('Count')
        
        plt.tight_layout()
        chart_filename = f"ga_fantasy5_analysis_{datetime.now().strftime('%Y%m%d_%H%M%S')}.png"
        plt.savefig(chart_filename, dpi=300, bbox_inches='tight')
        plt.show()
        print(f"Charts saved to {chart_filename}")


def main():
    """Main function"""
    import sys
    
    # Allow specifying CSV file as command line argument
    csv_file = sys.argv[1] if len(sys.argv) > 1 else None
    
    try:
        analyzer = LottoDrawSummary(csv_file)
        analyzer.create_summary_report()
        
        # Save output to file
        output_filename = analyzer.save_output_file()
        
        # Ask if user wants charts
        if PLOTTING_AVAILABLE:
            response = input("\nCreate visualization charts? (y/n): ").lower().strip()
            if response in ['y', 'yes']:
                analyzer.create_charts()
        
    except FileNotFoundError as e:
        print(f"Error: {e}")
        print("Make sure you have generated draws using lot_cover_1000.py first")
    except Exception as e:
        print(f"Unexpected error: {e}")


if __name__ == "__main__":
    main()