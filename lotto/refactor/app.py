# Flask Lottery Analysis System
# app.py

from flask import Flask, render_template, request, jsonify
from flask_sqlalchemy import SQLAlchemy
from datetime import datetime, timedelta
import numpy as np
from collections import defaultdict
import os

app = Flask(__name__)
app.config['SECRET_KEY'] = 'your-secret-key-here'
app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+pymysql://root:password@localhost/lottery_db'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

db = SQLAlchemy(app)

# Game configurations
GAMES = {
    1: {'name': 'Georgia Fantasy 5', 'balls': 42, 'balls_drawn': 5, 'mega': False},
    2: {'name': 'Mega Millions', 'balls': 70, 'balls_drawn': 5, 'mega': True, 'mega_balls': 25},
    3: {'name': 'Georgia 5', 'balls': 47, 'balls_drawn': 5, 'mega': False},
    4: {'name': 'Jumbo', 'balls': 47, 'balls_drawn': 6, 'mega': False},
    5: {'name': 'Florida Fantasy 5', 'balls': 36, 'balls_drawn': 5, 'mega': False},
    6: {'name': 'Florida Lotto', 'balls': 53, 'balls_drawn': 6, 'mega': False},
    7: {'name': 'Powerball', 'balls': 69, 'balls_drawn': 5, 'mega': True, 'mega_balls': 26}
}

# Database Models
class LotteryDraw(db.Model):
    __tablename__ = 'lottery_draws'
    
    id = db.Column(db.Integer, primary_key=True)
    game_id = db.Column(db.Integer, nullable=False)
    draw_date = db.Column(db.Date, nullable=False)
    ball1 = db.Column(db.Integer)
    ball2 = db.Column(db.Integer)
    ball3 = db.Column(db.Integer)
    ball4 = db.Column(db.Integer)
    ball5 = db.Column(db.Integer)
    ball6 = db.Column(db.Integer)
    mega_ball = db.Column(db.Integer)
    draw_sum = db.Column(db.Integer)
    even_count = db.Column(db.Integer)
    odd_count = db.Column(db.Integer)
    average = db.Column(db.Float)
    
    def get_balls(self):
        """Return list of drawn balls"""
        balls = [self.ball1, self.ball2, self.ball3, self.ball4, self.ball5]
        if self.ball6:
            balls.append(self.ball6)
        return [b for b in balls if b is not None]
    
    def calculate_stats(self):
        """Calculate statistics for this draw"""
        balls = self.get_balls()
        self.draw_sum = sum(balls)
        self.even_count = sum(1 for b in balls if b % 2 == 0)
        self.odd_count = len(balls) - self.even_count
        self.average = self.draw_sum / len(balls)

class DrawStatistics(db.Model):
    __tablename__ = 'draw_statistics'
    
    id = db.Column(db.Integer, primary_key=True)
    game_id = db.Column(db.Integer, nullable=False)
    time_range = db.Column(db.Integer)  # days
    ball_number = db.Column(db.Integer)
    frequency = db.Column(db.Integer)
    last_drawn = db.Column(db.Date)
    updated_at = db.Column(db.DateTime, default=datetime.utcnow)

# Lottery Analysis Functions
class LotteryAnalyzer:
    def __init__(self, game_id):
        self.game_id = game_id
        self.game_config = GAMES[game_id]
    
    def get_draws(self, limit=None, days=None):
        """Get lottery draws with optional limit or date range"""
        query = LotteryDraw.query.filter_by(game_id=self.game_id)
        
        if days:
            start_date = datetime.now() - timedelta(days=days)
            query = query.filter(LotteryDraw.draw_date >= start_date)
        
        query = query.order_by(LotteryDraw.draw_date.desc())
        
        if limit:
            query = query.limit(limit)
        
        return query.all()
    
    def calculate_frequency(self, draws):
        """Calculate frequency of each ball number"""
        frequency = defaultdict(int)
        last_seen = {}
        
        for draw in draws:
            balls = draw.get_balls()
            for ball in balls:
                frequency[ball] += 1
                if ball not in last_seen or draw.draw_date > last_seen[ball]:
                    last_seen[ball] = draw.draw_date
        
        return frequency, last_seen
    
    def calculate_pairs(self, draws):
        """Calculate frequency of number pairs"""
        pairs = defaultdict(int)
        
        for draw in draws:
            balls = sorted(draw.get_balls())
            for i in range(len(balls)):
                for j in range(i + 1, len(balls)):
                    pair = (balls[i], balls[j])
                    pairs[pair] += 1
        
        return pairs
    
    def calculate_sum_stats(self, draws):
        """Calculate sum statistics"""
        sums = [draw.draw_sum for draw in draws if draw.draw_sum]
        
        if not sums:
            return {}
        
        return {
            'min': min(sums),
            'max': max(sums),
            'average': np.mean(sums),
            'median': np.median(sums),
            'std_dev': np.std(sums)
        }
    
    def get_hot_cold_numbers(self, draws, top_n=10):
        """Get hot (frequently drawn) and cold (rarely drawn) numbers"""
        frequency, last_seen = self.calculate_frequency(draws)
        
        # Sort by frequency
        sorted_freq = sorted(frequency.items(), key=lambda x: x[1], reverse=True)
        
        hot_numbers = sorted_freq[:top_n]
        cold_numbers = sorted_freq[-top_n:]
        
        return {
            'hot': [(num, freq, last_seen.get(num)) for num, freq in hot_numbers],
            'cold': [(num, freq, last_seen.get(num)) for num, freq in cold_numbers]
        }
    
    def analyze_combinations(self, draws, combination_size=4):
        """Analyze specific combination patterns"""
        combinations = defaultdict(list)
        
        for draw in draws:
            balls = sorted(draw.get_balls())
            
            # Skip if we don't have enough balls
            if len(balls) < combination_size:
                continue
            
            # Generate all possible combinations of the specified size
            from itertools import combinations as comb
            for combo in comb(balls, combination_size):
                combo_key = '-'.join(map(str, combo))
                combinations[combo_key].append(draw.draw_date)
        
        # Sort by frequency
        sorted_combos = sorted(
            [(k, len(v), max(v)) for k, v in combinations.items()],
            key=lambda x: x[1],
            reverse=True
        )
        
        return sorted_combos[:20]  # Top 20 combinations

# Routes
@app.route('/')
def index():
    """Home page showing available games"""
    return render_template('index.html', games=GAMES)

@app.route('/game/<int:game_id>')
def game_display(game_id):
    """Display lottery analysis for a specific game"""
    if game_id not in GAMES:
        return "Game not found", 404
    
    # Get parameters
    limit = request.args.get('limit', 100, type=int)
    days = request.args.get('days', None, type=int)
    
    analyzer = LotteryAnalyzer(game_id)
    draws = analyzer.get_draws(limit=limit, days=days)
    
    # Calculate statistics
    frequency, last_seen = analyzer.calculate_frequency(draws)
    pairs = analyzer.calculate_pairs(draws)
    sum_stats = analyzer.calculate_sum_stats(draws)
    hot_cold = analyzer.get_hot_cold_numbers(draws)
    
    # Convert to sorted lists for display
    frequency_list = sorted(frequency.items(), key=lambda x: x[1], reverse=True)
    pairs_list = sorted(pairs.items(), key=lambda x: x[1], reverse=True)[:20]
    
    return render_template('game_display.html',
        game=GAMES[game_id],
        game_id=game_id,
        draws=draws,
        frequency=frequency_list,
        pairs=pairs_list,
        sum_stats=sum_stats,
        hot_cold=hot_cold,
        limit=limit,
        days=days
    )

@app.route('/game/<int:game_id>/combinations')
def combinations_analysis(game_id):
    """Analyze combinations for a game"""
    if game_id not in GAMES:
        return "Game not found", 404
    
    combination_size = request.args.get('size', 4, type=int)
    limit = request.args.get('limit', 1000, type=int)
    
    analyzer = LotteryAnalyzer(game_id)
    draws = analyzer.get_draws(limit=limit)
    combinations = analyzer.analyze_combinations(draws, combination_size)
    
    return render_template('combinations.html',
        game=GAMES[game_id],
        game_id=game_id,
        combinations=combinations,
        combination_size=combination_size
    )

@app.route('/api/update_stats/<int:game_id>')
def update_stats(game_id):
    """API endpoint to update statistics for a game"""
    if game_id not in GAMES:
        return jsonify({'error': 'Game not found'}), 404
    
    analyzer = LotteryAnalyzer(game_id)
    
    # Update statistics for different time ranges
    time_ranges = [7, 14, 21, 30, 60, 90, 180, 365]
    
    for days in time_ranges:
        draws = analyzer.get_draws(days=days)
        frequency, last_seen = analyzer.calculate_frequency(draws)
        
        # Update database
        for ball_num, freq in frequency.items():
            stat = DrawStatistics.query.filter_by(
                game_id=game_id,
                time_range=days,
                ball_number=ball_num
            ).first()
            
            if not stat:
                stat = DrawStatistics(
                    game_id=game_id,
                    time_range=days,
                    ball_number=ball_num
                )
                db.session.add(stat)
            
            stat.frequency = freq
            stat.last_drawn = last_seen[ball_num]
            stat.updated_at = datetime.utcnow()
    
    db.session.commit()
    
    return jsonify({'status': 'success', 'message': f'Updated statistics for {GAMES[game_id]["name"]}'})

@app.route('/api/import_draw', methods=['POST'])
def import_draw():
    """Import a new lottery draw"""
    data = request.json
    
    draw = LotteryDraw(
        game_id=data['game_id'],
        draw_date=datetime.strptime(data['date'], '%Y-%m-%d').date(),
        ball1=data.get('ball1'),
        ball2=data.get('ball2'),
        ball3=data.get('ball3'),
        ball4=data.get('ball4'),
        ball5=data.get('ball5'),
        ball6=data.get('ball6'),
        mega_ball=data.get('mega_ball')
    )
    
    draw.calculate_stats()
    
    db.session.add(draw)
    db.session.commit()
    
    return jsonify({'status': 'success', 'id': draw.id})

# CLI Commands for database setup
@app.cli.command()
def init_db():
    """Initialize the database"""
    db.create_all()
    print("Database initialized!")

@app.cli.command()
def seed_test_data():
    """Seed database with test data"""
    import random
    from datetime import date, timedelta
    
    # Generate test data for Georgia Fantasy 5
    start_date = date.today() - timedelta(days=365)
    
    for i in range(365):
        draw_date = start_date + timedelta(days=i)
        balls = sorted(random.sample(range(1, 43), 5))
        
        draw = LotteryDraw(
            game_id=1,
            draw_date=draw_date,
            ball1=balls[0],
            ball2=balls[1],
            ball3=balls[2],
            ball4=balls[3],
            ball5=balls[4]
        )
        draw.calculate_stats()
        db.session.add(draw)
    
    db.session.commit()
    print("Test data seeded!")

if __name__ == '__main__':
    app.run(debug=True)