#!/usr/bin/env python3
"""
GA Fantasy 5 Lottery Dashboard
Flask web interface for adjusting filter settings and generating draws
"""

from flask import Flask, render_template, request, redirect, url_for, jsonify, send_file, flash
import json
import os
import csv
from datetime import datetime
from collections import Counter, defaultdict
import tempfile
import io
import zipfile

# Import our lottery generation classes
import sys
sys.path.append('.')

# Import the generator class from lot_cover_1000.py
from lot_cover_1000 import GAFantasy5Generator

app = Flask(__name__)
app.secret_key = 'ga_fantasy5_dashboard_secret_key'

# Default settings
DEFAULT_SETTINGS = {
    'count': 1000,
    'max_seq2': 1,
    'max_seq3': 0,
    'max_mod_tot': 1,
    'sum_min': 70,
    'sum_max': 139,
    'include_scores': True,
    'load_historical': True
}

def load_settings():
    """Load settings from file or return defaults"""
    try:
        with open('dashboard_settings.json', 'r') as f:
            settings = json.load(f)
            # Ensure all required keys exist
            for key, default_value in DEFAULT_SETTINGS.items():
                if key not in settings:
                    settings[key] = default_value
            return settings
    except FileNotFoundError:
        return DEFAULT_SETTINGS.copy()

def save_settings(settings):
    """Save settings to file"""
    try:
        with open('dashboard_settings.json', 'w') as f:
            json.dump(settings, f, indent=2)
    except Exception as e:
        print(f"Error saving settings: {e}")

@app.route('/')
def index():
    """Main dashboard page"""
    settings = load_settings()
    return render_template('dashboard.html', settings=settings)

@app.route('/api/settings', methods=['GET', 'POST'])
def api_settings():
    """API endpoint for settings management"""
    if request.method == 'GET':
        return jsonify(load_settings())
    
    elif request.method == 'POST':
        try:
            new_settings = request.json
            
            # Validate settings
            if new_settings.get('count', 0) < 1 or new_settings.get('count', 0) > 10000:
                return jsonify({'error': 'Count must be between 1 and 10000'}), 400
                
            if new_settings.get('sum_min', 0) >= new_settings.get('sum_max', 0):
                return jsonify({'error': 'Sum minimum must be less than maximum'}), 400
            
            save_settings(new_settings)
            return jsonify({'success': True, 'settings': new_settings})
            
        except Exception as e:
            return jsonify({'error': str(e)}), 500

@app.route('/api/generate', methods=['POST'])
def api_generate():
    """API endpoint to generate draws"""
    try:
        settings = request.json if request.json else load_settings()
        
        # Create generator with custom settings
        generator = GAFantasy5Generator()
        generator.max_seq2 = settings.get('max_seq2', 1)
        generator.max_seq3 = settings.get('max_seq3', 0)
        generator.max_mod_tot = settings.get('max_mod_tot', 1)
        generator.sum_range = (settings.get('sum_min', 70), settings.get('sum_max', 139))
        
        # Generate draws
        count = settings.get('count', 1000)
        include_scores = settings.get('include_scores', True)
        
        draws = generator.generate_draws(count=count, include_scores=include_scores)
        
        # Create timestamped filenames
        timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
        csv_filename = f"ga_fantasy5_{count}_draws_{timestamp}.csv"
        json_filename = f"ga_fantasy5_{count}_draws_{timestamp}.json"
        
        # Save files
        generator.save_draws_csv(draws, csv_filename)
        generator.save_draws_json(draws, json_filename)
        
        # Generate quick summary statistics
        if draws and isinstance(draws[0], dict):
            combinations = [draw['combination'] for draw in draws]
            scores = [draw['score'] for draw in draws]
            sums = [draw['sum'] for draw in draws]
            
            summary = {
                'total_draws': len(draws),
                'score_range': f"{min(scores):.4f} - {max(scores):.4f}",
                'avg_score': f"{sum(scores)/len(scores):.4f}",
                'sum_range': f"{min(sums)} - {max(sums)}",
                'avg_sum': f"{sum(sums)/len(sums):.1f}",
                'files': {
                    'csv': csv_filename,
                    'json': json_filename
                }
            }
        else:
            summary = {
                'total_draws': len(draws),
                'files': {
                    'csv': csv_filename,
                    'json': json_filename
                }
            }
        
        return jsonify({
            'success': True,
            'summary': summary,
            'settings_used': settings
        })
        
    except Exception as e:
        return jsonify({'error': str(e)}), 500

@app.route('/api/analyze/<filename>')
def api_analyze(filename):
    """API endpoint to analyze a generated file"""
    try:
        if not os.path.exists(filename):
            return jsonify({'error': 'File not found'}), 404
        
        # Load and analyze the file
        draws = []
        with open(filename, 'r') as f:
            reader = csv.DictReader(f)
            draws = list(reader)
        
        if not draws:
            return jsonify({'error': 'No data in file'}), 400
        
        # Generate analysis summary
        analysis = analyze_draws_summary(draws)
        
        return jsonify({
            'success': True,
            'filename': filename,
            'total_draws': len(draws),
            'analysis': analysis
        })
        
    except Exception as e:
        return jsonify({'error': str(e)}), 500

def analyze_draws_summary(draws):
    """Generate quick analysis summary"""
    try:
        # Sum analysis
        sums = [int(draw['Sum']) for draw in draws]
        sum_counter = Counter(sums)
        
        # Score analysis (if available)
        scores = []
        if 'Score' in draws[0]:
            scores = [float(draw['Score']) for draw in draws]
        
        # Even/odd analysis
        even_counts = [int(draw['Even']) for draw in draws if 'Even' in draw]
        even_counter = Counter(even_counts)
        
        # Position analysis
        positions = defaultdict(lambda: defaultdict(int))
        for draw in draws:
            positions[1][int(draw['Num1'])] += 1
            positions[2][int(draw['Num2'])] += 1
            positions[3][int(draw['Num3'])] += 1
            positions[4][int(draw['Num4'])] += 1
            positions[5][int(draw['Num5'])] += 1
        
        # Overall frequency
        all_numbers = []
        for draw in draws:
            all_numbers.extend([int(draw['Num1']), int(draw['Num2']), 
                              int(draw['Num3']), int(draw['Num4']), int(draw['Num5'])])
        number_counter = Counter(all_numbers)
        
        analysis = {
            'sums': {
                'range': f"{min(sums)} - {max(sums)}",
                'average': f"{sum(sums)/len(sums):.1f}",
                'most_common': [{'sum': s, 'count': c} for s, c in sum_counter.most_common(5)]
            },
            'even_odd': {
                'distribution': [{'even': k, 'odd': 5-k, 'count': v, 'percent': f"{v/len(draws)*100:.1f}%"} 
                               for k, v in sorted(even_counter.items())]
            },
            'hottest_numbers': [{'number': n, 'count': c, 'percent': f"{c/len(all_numbers)*100:.2f}%"} 
                               for n, c in number_counter.most_common(10)],
            'coldest_numbers': [{'number': n, 'count': c, 'percent': f"{c/len(all_numbers)*100:.2f}%"} 
                               for n, c in number_counter.most_common()[-10:]],
        }
        
        # Add score analysis if available
        if scores:
            analysis['scores'] = {
                'range': f"{min(scores):.4f} - {max(scores):.4f}",
                'average': f"{sum(scores)/len(scores):.4f}"
            }
        
        return analysis
        
    except Exception as e:
        return {'error': str(e)}

@app.route('/api/files')
def api_files():
    """API endpoint to list generated files"""
    try:
        files = []
        for filename in os.listdir('.'):
            if filename.startswith('ga_fantasy5_') and filename.endswith('.csv'):
                stat = os.stat(filename)
                files.append({
                    'name': filename,
                    'size': stat.st_size,
                    'modified': datetime.fromtimestamp(stat.st_mtime).strftime('%Y-%m-%d %H:%M:%S')
                })
        
        # Sort by modification time, newest first
        files.sort(key=lambda x: x['modified'], reverse=True)
        
        return jsonify({'files': files})
        
    except Exception as e:
        return jsonify({'error': str(e)}), 500

@app.route('/download/<filename>')
def download_file(filename):
    """Download a generated file"""
    try:
        if not os.path.exists(filename):
            flash('File not found', 'error')
            return redirect(url_for('index'))
        
        return send_file(filename, as_attachment=True)
        
    except Exception as e:
        flash(f'Error downloading file: {e}', 'error')
        return redirect(url_for('index'))

@app.route('/api/download-batch', methods=['POST'])
def api_download_batch():
    """Download multiple files as a ZIP"""
    try:
        filenames = request.json.get('files', [])
        
        if not filenames:
            return jsonify({'error': 'No files specified'}), 400
        
        # Create a ZIP file in memory
        memory_file = io.BytesIO()
        
        with zipfile.ZipFile(memory_file, 'w', zipfile.ZIP_DEFLATED) as zf:
            for filename in filenames:
                if os.path.exists(filename):
                    zf.write(filename)
        
        memory_file.seek(0)
        
        timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')
        zip_filename = f"ga_fantasy5_batch_{timestamp}.zip"
        
        return send_file(
            memory_file,
            mimetype='application/zip',
            as_attachment=True,
            download_name=zip_filename
        )
        
    except Exception as e:
        return jsonify({'error': str(e)}), 500

@app.route('/api/delete/<filename>', methods=['DELETE'])
def api_delete_file(filename):
    """Delete a generated file"""
    try:
        if not os.path.exists(filename):
            return jsonify({'error': 'File not found'}), 404
        
        os.remove(filename)
        
        # Also try to remove corresponding JSON file
        json_filename = filename.replace('.csv', '.json')
        if os.path.exists(json_filename):
            os.remove(json_filename)
        
        return jsonify({'success': True, 'deleted': filename})
        
    except Exception as e:
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    # Create templates directory if it doesn't exist
    os.makedirs('templates', exist_ok=True)
    
    # Run the Flask app
    app.run(debug=True, host='127.0.0.1', port=5000)