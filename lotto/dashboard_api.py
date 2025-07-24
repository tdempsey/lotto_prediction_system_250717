#!/usr/bin/env python3

from flask import Flask, jsonify, request
from flask_cors import CORS
import mysql.connector
from mysql.connector import Error
import datetime
import json
from collections import Counter
import statistics

app = Flask(__name__)
CORS(app)

# Database configuration
DB_CONFIG = {
    'host': 'localhost',
    'user': 'root',
    'password': 'wef5esuv',
    'database': 'ga_f5_lotto'
}

class LotteryAPI:
    def __init__(self):
        self.connection = None
        self.connect_to_database()
    
    def connect_to_database(self):
        """Establish database connection"""
        try:
            self.connection = mysql.connector.connect(**DB_CONFIG)
            if self.connection.is_connected():
                print("Connected to MySQL database")
        except Error as e:
            print(f"Error connecting to MySQL: {e}")
            self.connection = None
    
    def get_connection(self):
        """Get database connection, reconnect if needed"""
        if not self.connection or not self.connection.is_connected():
            self.connect_to_database()
        return self.connection
    
    def get_game_info(self, game_id):
        """Get game information based on game ID"""
        games = {
            1: {
                'name': 'Georgia Fantasy 5',
                'table': 'ga_f5_draws',
                'max_number': 42,
                'balls_drawn': 5
            },
            2: {
                'name': 'Mega Millions',
                'table': 'mm_draws',
                'max_number': 70,
                'balls_drawn': 5
            },
            3: {
                'name': 'Georgia 5',
                'table': 'ga_5_draws',
                'max_number': 39,
                'balls_drawn': 5
            },
            7: {
                'name': 'Powerball',
                'table': 'pb_draws',
                'max_number': 69,
                'balls_drawn': 5
            }
        }
        return games.get(game_id, games[1])
    
    def get_recent_draws(self, game_id, limit=10):
        """Get recent lottery draws"""
        connection = self.get_connection()
        if not connection:
            return []
        
        game_info = self.get_game_info(game_id)
        table_name = game_info['table']
        
        try:
            cursor = connection.cursor()
            query = f"""
            SELECT date, b1, b2, b3, b4, b5 
            FROM {table_name} 
            ORDER BY date DESC 
            LIMIT %s
            """
            cursor.execute(query, (limit,))
            results = cursor.fetchall()
            
            draws = []
            for row in results:
                draws.append({
                    'date': row[0].strftime('%Y-%m-%d') if row[0] else None,
                    'numbers': [row[1], row[2], row[3], row[4], row[5]]
                })
            
            cursor.close()
            return draws
            
        except Error as e:
            print(f"Error getting recent draws: {e}")
            return []
    
    def get_number_frequency(self, game_id, limit=1000):
        """Get frequency of each number"""
        connection = self.get_connection()
        if not connection:
            return {'numbers': [], 'counts': []}
        
        game_info = self.get_game_info(game_id)
        table_name = game_info['table']
        max_number = game_info['max_number']
        
        try:
            cursor = connection.cursor()
            
            # Get all numbers from recent draws
            query = f"""
            SELECT b1, b2, b3, b4, b5 
            FROM {table_name} 
            ORDER BY date DESC 
            LIMIT %s
            """
            cursor.execute(query, (limit,))
            results = cursor.fetchall()
            
            # Count frequency of each number
            all_numbers = []
            for row in results:
                all_numbers.extend([row[0], row[1], row[2], row[3], row[4]])
            
            frequency = Counter(all_numbers)
            
            # Create arrays for all possible numbers
            numbers = list(range(1, max_number + 1))
            counts = [frequency.get(num, 0) for num in numbers]
            
            cursor.close()
            return {'numbers': numbers, 'counts': counts}
            
        except Error as e:
            print(f"Error getting number frequency: {e}")
            return {'numbers': [], 'counts': []}
    
    def get_hot_cold_numbers(self, game_id, limit=500):
        """Get hot and cold numbers"""
        frequency_data = self.get_number_frequency(game_id, limit)
        
        if not frequency_data['numbers']:
            return {'hot': [], 'cold': []}
        
        # Combine numbers with their frequencies
        number_freq = list(zip(frequency_data['numbers'], frequency_data['counts']))
        
        # Sort by frequency
        sorted_numbers = sorted(number_freq, key=lambda x: x[1], reverse=True)
        
        # Get top 10 hot and cold numbers
        hot_numbers = [num for num, freq in sorted_numbers[:10]]
        cold_numbers = [num for num, freq in sorted_numbers[-10:]]
        
        return {'hot': hot_numbers, 'cold': cold_numbers}
    
    def get_sum_analysis(self, game_id, limit=500):
        """Analyze sum statistics"""
        connection = self.get_connection()
        if not connection:
            return {'average': 0, 'mostCommon': 0, 'range': [0, 0]}
        
        game_info = self.get_game_info(game_id)
        table_name = game_info['table']
        
        try:
            cursor = connection.cursor()
            query = f"""
            SELECT (b1 + b2 + b3 + b4 + b5) as sum_total
            FROM {table_name} 
            ORDER BY date DESC 
            LIMIT %s
            """
            cursor.execute(query, (limit,))
            results = cursor.fetchall()
            
            sums = [row[0] for row in results if row[0]]
            
            if not sums:
                return {'average': 0, 'mostCommon': 0, 'range': [0, 0]}
            
            average = statistics.mean(sums)
            most_common = Counter(sums).most_common(1)[0][0]
            sum_range = [min(sums), max(sums)]
            
            cursor.close()
            return {
                'average': round(average, 1),
                'mostCommon': most_common,
                'range': sum_range
            }
            
        except Error as e:
            print(f"Error getting sum analysis: {e}")
            return {'average': 0, 'mostCommon': 0, 'range': [0, 0]}
    
    def get_even_odd_analysis(self, game_id, limit=500):
        """Analyze even/odd distribution"""
        connection = self.get_connection()
        if not connection:
            return {'evenCount': 50, 'oddCount': 50, 'ratio': 1.0}
        
        game_info = self.get_game_info(game_id)
        table_name = game_info['table']
        
        try:
            cursor = connection.cursor()
            query = f"""
            SELECT b1, b2, b3, b4, b5 
            FROM {table_name} 
            ORDER BY date DESC 
            LIMIT %s
            """
            cursor.execute(query, (limit,))
            results = cursor.fetchall()
            
            even_count = 0
            odd_count = 0
            total_numbers = 0
            
            for row in results:
                for num in [row[0], row[1], row[2], row[3], row[4]]:
                    if num:
                        total_numbers += 1
                        if num % 2 == 0:
                            even_count += 1
                        else:
                            odd_count += 1
            
            if total_numbers == 0:
                return {'evenCount': 50, 'oddCount': 50, 'ratio': 1.0}
            
            even_percent = round((even_count / total_numbers) * 100)
            odd_percent = round((odd_count / total_numbers) * 100)
            ratio = round(even_count / odd_count if odd_count > 0 else 1.0, 2)
            
            cursor.close()
            return {
                'evenCount': even_percent,
                'oddCount': odd_percent,
                'ratio': ratio
            }
            
        except Error as e:
            print(f"Error getting even/odd analysis: {e}")
            return {'evenCount': 50, 'oddCount': 50, 'ratio': 1.0}
    
    def get_sum_distribution(self, game_id, limit=500):
        """Get sum distribution for charting"""
        connection = self.get_connection()
        if not connection:
            return {'ranges': [], 'counts': []}
        
        game_info = self.get_game_info(game_id)
        table_name = game_info['table']
        
        try:
            cursor = connection.cursor()
            query = f"""
            SELECT (b1 + b2 + b3 + b4 + b5) as sum_total
            FROM {table_name} 
            ORDER BY date DESC 
            LIMIT %s
            """
            cursor.execute(query, (limit,))
            results = cursor.fetchall()
            
            sums = [row[0] for row in results if row[0]]
            
            if not sums:
                return {'ranges': [], 'counts': []}
            
            # Create ranges based on min and max sums
            min_sum = min(sums)
            max_sum = max(sums)
            range_size = (max_sum - min_sum) // 6
            
            ranges = []
            counts = []
            
            for i in range(6):
                start = min_sum + (i * range_size)
                end = start + range_size - 1 if i < 5 else max_sum
                
                ranges.append(f"{start}-{end}")
                count = sum(1 for s in sums if start <= s <= end)
                counts.append(count)
            
            cursor.close()
            return {'ranges': ranges, 'counts': counts}
            
        except Error as e:
            print(f"Error getting sum distribution: {e}")
            return {'ranges': [], 'counts': []}
    
    def get_dashboard_data(self, game_id):
        """Get all dashboard data"""
        recent_draws = self.get_recent_draws(game_id, 10)
        frequency = self.get_number_frequency(game_id)
        hot_cold = self.get_hot_cold_numbers(game_id)
        sum_analysis = self.get_sum_analysis(game_id)
        even_odd = self.get_even_odd_analysis(game_id)
        sum_distribution = self.get_sum_distribution(game_id)
        
        # Calculate additional stats
        total_draws = self.get_total_draws(game_id)
        last_draw_date = recent_draws[0]['date'] if recent_draws else None
        next_draw_date = self.calculate_next_draw_date(last_draw_date)
        
        return {
            'lastDrawDate': last_draw_date,
            'nextDrawDate': next_draw_date,
            'totalDraws': total_draws,
            'recentDraws': recent_draws,
            'frequency': frequency,
            'hotNumbers': hot_cold['hot'][:5],
            'coldNumbers': hot_cold['cold'][:5],
            'sumAnalysis': sum_analysis,
            'evenOddAnalysis': even_odd,
            'sumDistribution': sum_distribution
        }
    
    def get_total_draws(self, game_id):
        """Get total number of draws"""
        connection = self.get_connection()
        if not connection:
            return 0
        
        game_info = self.get_game_info(game_id)
        table_name = game_info['table']
        
        try:
            cursor = connection.cursor()
            query = f"SELECT COUNT(*) FROM {table_name}"
            cursor.execute(query)
            result = cursor.fetchone()
            cursor.close()
            return result[0] if result else 0
            
        except Error as e:
            print(f"Error getting total draws: {e}")
            return 0
    
    def calculate_next_draw_date(self, last_draw_date):
        """Calculate next draw date (assuming draws every 2 days)"""
        if not last_draw_date:
            return datetime.date.today().strftime('%Y-%m-%d')
        
        try:
            last_date = datetime.datetime.strptime(last_draw_date, '%Y-%m-%d').date()
            next_date = last_date + datetime.timedelta(days=2)
            return next_date.strftime('%Y-%m-%d')
        except:
            return datetime.date.today().strftime('%Y-%m-%d')

# Initialize API
lottery_api = LotteryAPI()

@app.route('/api/lottery-data')
def get_lottery_data():
    """Main API endpoint for lottery data"""
    try:
        game_id = request.args.get('game', 1, type=int)
        data = lottery_api.get_dashboard_data(game_id)
        return jsonify(data)
    except Exception as e:
        print(f"Error in API: {e}")
        return jsonify({'error': 'Internal server error'}), 500

@app.route('/api/recent-draws')
def get_recent_draws():
    """Get recent draws"""
    try:
        game_id = request.args.get('game', 1, type=int)
        limit = request.args.get('limit', 10, type=int)
        draws = lottery_api.get_recent_draws(game_id, limit)
        return jsonify(draws)
    except Exception as e:
        print(f"Error getting recent draws: {e}")
        return jsonify({'error': 'Internal server error'}), 500

@app.route('/api/frequency')
def get_frequency():
    """Get number frequency"""
    try:
        game_id = request.args.get('game', 1, type=int)
        limit = request.args.get('limit', 1000, type=int)
        frequency = lottery_api.get_number_frequency(game_id, limit)
        return jsonify(frequency)
    except Exception as e:
        print(f"Error getting frequency: {e}")
        return jsonify({'error': 'Internal server error'}), 500

@app.route('/api/hot-cold')
def get_hot_cold():
    """Get hot and cold numbers"""
    try:
        game_id = request.args.get('game', 1, type=int)
        limit = request.args.get('limit', 500, type=int)
        hot_cold = lottery_api.get_hot_cold_numbers(game_id, limit)
        return jsonify(hot_cold)
    except Exception as e:
        print(f"Error getting hot/cold numbers: {e}")
        return jsonify({'error': 'Internal server error'}), 500

@app.route('/api/sum-analysis')
def get_sum_analysis():
    """Get sum analysis"""
    try:
        game_id = request.args.get('game', 1, type=int)
        limit = request.args.get('limit', 500, type=int)
        analysis = lottery_api.get_sum_analysis(game_id, limit)
        return jsonify(analysis)
    except Exception as e:
        print(f"Error getting sum analysis: {e}")
        return jsonify({'error': 'Internal server error'}), 500

@app.route('/api/prediction')
def get_prediction():
    """Generate lottery prediction"""
    try:
        game_id = request.args.get('game', 1, type=int)
        method = request.args.get('method', 'frequency')
        
        # Get data for prediction
        frequency = lottery_api.get_number_frequency(game_id)
        hot_cold = lottery_api.get_hot_cold_numbers(game_id)
        
        # Simple prediction based on method
        if method == 'frequency':
            # Use most frequent numbers
            prediction = hot_cold['hot'][:5]
        elif method == 'pattern':
            # Use pattern analysis (simplified)
            recent_draws = lottery_api.get_recent_draws(game_id, 20)
            all_numbers = []
            for draw in recent_draws:
                all_numbers.extend(draw['numbers'])
            
            number_count = Counter(all_numbers)
            prediction = [num for num, count in number_count.most_common(5)]
        else:
            # Statistical method
            import random
            game_info = lottery_api.get_game_info(game_id)
            prediction = sorted(random.sample(range(1, game_info['max_number'] + 1), 5))
        
        return jsonify({'prediction': prediction, 'method': method})
        
    except Exception as e:
        print(f"Error generating prediction: {e}")
        return jsonify({'error': 'Internal server error'}), 500

@app.route('/api/health')
def health_check():
    """Health check endpoint"""
    return jsonify({'status': 'healthy', 'timestamp': datetime.datetime.now().isoformat()})

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000)