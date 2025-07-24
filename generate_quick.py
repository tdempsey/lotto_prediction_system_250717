#!/usr/bin/env python3
"""
Quick lottery generation with default settings
"""

from datetime import datetime
from lot_cover_1000 import GAFantasy5Generator
from lot_cover_1000_summary import LottoDrawSummary

def main():
    print("=" * 60)
    print("GA FANTASY 5 LOTTERY GENERATOR - QUICK VERSION")
    print("=" * 60)
    
    # Use default settings
    count = 1000
    print(f"Generating {count} draws with default settings...")
    
    # Create generator
    generator = GAFantasy5Generator()
    
    # Generate draws
    start_time = datetime.now()
    draws = generator.generate_draws(count=count, include_scores=True)
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
    
    # Generate analysis
    print(f"\nGenerating analysis report...")
    analyzer = LottoDrawSummary(csv_filename)
    analyzer.create_summary_report()
    analysis_filename = analyzer.save_output_file()
    
    print(f"Analysis report saved to: {analysis_filename}")
    
    print(f"\n{'='*60}")
    print("ALL OPERATIONS COMPLETE")
    print(f"{'='*60}")

if __name__ == "__main__":
    main()