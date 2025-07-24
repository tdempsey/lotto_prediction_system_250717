#!/usr/bin/env python3
"""
Command-line interface for GA Fantasy 5 lottery generation
Alternative to the web dashboard for direct terminal use
"""

import json
import os
from datetime import datetime
from lot_cover_1000 import GAFantasy5Generator
from lot_cover_1000_summary import LottoDrawSummary

def get_user_input(prompt, default_value, input_type=str):
    """Get user input with default value"""
    user_input = input(f"{prompt} [{default_value}]: ").strip()
    if not user_input:
        return default_value
    
    try:
        return input_type(user_input)
    except ValueError:
        print(f"Invalid input, using default: {default_value}")
        return default_value

def main():
    """Main CLI interface"""
    print("=" * 60)
    print("GA FANTASY 5 LOTTERY GENERATOR - CLI VERSION")
    print("=" * 60)
    
    # Get user settings
    print("\nEnter generation settings (press Enter for defaults):")
    
    count = get_user_input("Number of draws", 1000, int)
    max_seq2 = get_user_input("Max sequential pairs", 1, int)
    max_seq3 = get_user_input("Max sequential triplets", 0, int)
    max_mod_tot = get_user_input("Max modular total", 1, int)
    sum_min = get_user_input("Sum minimum", 70, int)
    sum_max = get_user_input("Sum maximum", 139, int)
    
    include_scores = input("Include scoring & ranking? [y/N]: ").lower().startswith('y')
    load_historical = input("Load historical data for duplicate filtering? [y/N]: ").lower().startswith('y')
    
    print(f"\n{'='*60}")
    print("GENERATING DRAWS...")
    print(f"{'='*60}")
    
    # Create generator with settings
    generator = GAFantasy5Generator()
    generator.max_seq2 = max_seq2
    generator.max_seq3 = max_seq3
    generator.max_mod_tot = max_mod_tot
    generator.sum_range = (sum_min, sum_max)
    
    try:
        # Generate draws
        start_time = datetime.now()
        draws = generator.generate_draws(count=count, include_scores=include_scores)
        end_time = datetime.now()
        
        # Create timestamped filenames
        timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
        csv_filename = f"ga_fantasy5_{count}_draws_{timestamp}.csv"
        json_filename = f"ga_fantasy5_{count}_draws_{timestamp}.json"
        
        # Save files
        generator.save_draws_csv(draws, csv_filename)
        generator.save_draws_json(draws, json_filename)
        
        # Display results
        generation_time = (end_time - start_time).total_seconds()
        
        print(f"\nGENERATION COMPLETE!")
        print(f"Time taken: {generation_time:.2f} seconds")
        print(f"Total draws: {len(draws)}")
        print(f"CSV file: {csv_filename}")
        print(f"JSON file: {json_filename}")
        
        if draws and isinstance(draws[0], dict):
            scores = [draw['score'] for draw in draws]
            sums = [draw['sum'] for draw in draws]
            print(f"Score range: {min(scores):.4f} - {max(scores):.4f}")
            print(f"Sum range: {min(sums)} - {max(sums)}")
        
        # Ask if user wants analysis
        if input(f"\nGenerate analysis report for {csv_filename}? [y/N]: ").lower().startswith('y'):
            print(f"\n{'='*60}")
            print("GENERATING ANALYSIS...")
            print(f"{'='*60}")
            
            analyzer = LottoDrawSummary(csv_filename)
            analyzer.create_summary_report()
            analysis_filename = analyzer.save_output_file()
            
            print(f"\nAnalysis complete! Report saved to: {analysis_filename}")
        
        print(f"\n{'='*60}")
        print("ALL OPERATIONS COMPLETE")
        print(f"{'='*60}")
        
    except Exception as e:
        print(f"Error during generation: {e}")
        return 1
    
    return 0

if __name__ == "__main__":
    exit(main())