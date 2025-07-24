
from flask import Flask, render_template, request, jsonify
import pymysql
import os
from datetime import datetime, timedelta
import logging
from config import Config
from models.database import Database
from models.lottery_analyzer import LotteryAnalyzer
from models.combination_generator import CombinationGenerator
from utils.validators import ValidationError

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('lottery_analysis.log'),
        logging.StreamHandler()
    ]
)

app = Flask(__name__)
app.config.from_object(Config)

# Initialize components
db = Database(app.config)
analyzer = LotteryAnalyzer(db)
generator = CombinationGenerator(db)

@app.route('/')
def index():
    """Main dashboard for lottery analysis"""
    try:
        # Get basic statistics
        stats = analyzer.get_basic_stats()
        recent_draws = analyzer.get_recent_draws(10)
        
        return render_template('dashboard.html', 
                             stats=stats, 
                             recent_draws=recent_draws)
    except Exception as e:
        app.logger.error(f"Error in index route: {str(e)}")
        return render_template('error.html', error="Failed to load dashboard")

@app.route('/api/generate-combinations', methods=['POST'])
def generate_combinations():
    """API endpoint to generate lottery combinations"""
    try:
        data = request.get_json()
        
        # Validate input
        if not data or 'filters' not in data:
            raise ValidationError("Missing filters in request")
        
        filters = data['filters']
        limit = data.get('limit', 1000)
        
        # Generate combinations
        combinations = generator.generate_filtered_combinations(filters, limit)
        
        return jsonify({
            'success': True,
            'combinations': combinations,
            'count': len(combinations)
        })
        
    except ValidationError as e:
        app.logger.warning(f"Validation error: {str(e)}")
        return jsonify({'success': False, 'error': str(e)}), 400
    except Exception as e:
        app.logger.error(f"Error generating combinations: {str(e)}")
        return jsonify({'success': False, 'error': 'Internal server error'}), 500

@app.route('/api/analyze/<int:draw_id>')
def analyze_draw(draw_id):
    """Analyze a specific draw"""
    try:
        analysis = analyzer.analyze_draw(draw_id)
        return jsonify({'success': True, 'analysis': analysis})
    except Exception as e:
        app.logger.error(f"Error analyzing draw {draw_id}: {str(e)}")
        return jsonify({'success': False, 'error': 'Analysis failed'}), 500

@app.route('/coverage')
def coverage_analysis():
    """Coverage analysis page"""
    try:
        coverage_stats = analyzer.get_coverage_stats()
        return render_template('coverage.html', stats=coverage_stats)
    except Exception as e:
        app.logger.error(f"Error in coverage analysis: {str(e)}")
        return render_template('error.html', error="Coverage analysis failed")

@app.errorhandler(404)
def not_found_error(error):
    return render_template('error.html', error="Page not found"), 404

@app.errorhandler(500)
def internal_error(error):
    app.logger.error(f"Internal server error: {str(error)}")
    return render_template('error.html', error="Internal server error"), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)
