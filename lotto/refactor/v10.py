# Flask Lottery Analysis System
# app.py

from flask import Flask, render_template, request, jsonify, redirect, url_for, flash, send_file, session
from flask_sqlalchemy import SQLAlchemy
from flask_login import LoginManager, UserMixin, login_user, logout_user, login_required, current_user
from werkzeug.security import generate_password_hash, check_password_hash
from datetime import datetime, timedelta
import numpy as np
from collections import defaultdict
import os
import pandas as pd
from io import BytesIO, StringIO, TextIOWrapper
import csv
from reportlab.lib import colors
from reportlab.lib.pagesizes import letter, A4
from reportlab.platypus import SimpleDocTemplate, Table, TableStyle, Paragraph, Spacer
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.lib.units import inch

app = Flask(__name__)
app.config['SECRET_KEY'] = 'your-secret-key-here'
app.config['SQLALCHEMY_DATABASE_URI'] = 'mysql+pymysql://root:password@localhost/lottery_db'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False

db = SQLAlchemy(app)
login_manager = LoginManager(app)
login_manager.login_view = 'login'

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
class User(UserMixin, db.Model):
    __tablename__ = 'users'
    
    id = db.Column(db.Integer, primary_key=True)
    username = db.Column(db.String(80), unique=True, nullable=False)
    email = db.Column(db.String(120), unique=True, nullable=False)
    password_hash = db.Column(db.String(200))
    is_admin = db.Column(db.Boolean, default=False)
    created_at = db.Column(db.DateTime, default=datetime.utcnow)
    
    def set_password(self, password):
        self.password_hash = generate_password_hash(password)
    
    def check_password(self, password):
        return check_password_hash(self.password_hash, password)

@login_manager.user_loader
def load_user(user_id):
    return User.query.get(int(user_id))

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

# Authentication Routes
@app.route('/login', methods=['GET', 'POST'])
def login():
    """Login page"""
    if request.method == 'POST':
        username = request.form.get('username')
        password = request.form.get('password')
        user = User.query.filter_by(username=username).first()
        
        if user and user.check_password(password):
            login_user(user)
            next_page = request.args.get('next')
            return redirect(next_page) if next_page else redirect(url_for('admin_dashboard'))
        else:
            flash('Invalid username or password', 'error')
    
    return render_template('login.html')

@app.route('/logout')
@login_required
def logout():
    """Logout user"""
    logout_user()
    return redirect(url_for('index'))

# Admin Routes
@app.route('/admin')
@login_required
def admin_dashboard():
    """Admin dashboard"""
    if not current_user.is_admin:
        flash('You do not have permission to access this page.', 'error')
        return redirect(url_for('index'))
    
    # Get statistics for dashboard
    stats = {}
    for game_id, game in GAMES.items():
        count = LotteryDraw.query.filter_by(game_id=game_id).count()
        latest = LotteryDraw.query.filter_by(game_id=game_id).order_by(LotteryDraw.draw_date.desc()).first()
        stats[game_id] = {
            'name': game['name'],
            'total_draws': count,
            'latest_draw': latest.draw_date if latest else None
        }
    
    return render_template('admin/dashboard.html', stats=stats, games=GAMES)

@app.route('/admin/draws')
@login_required
def admin_draws():
    """List all draws with filtering"""
    if not current_user.is_admin:
        flash('You do not have permission to access this page.', 'error')
        return redirect(url_for('index'))
    
    game_id = request.args.get('game_id', type=int)
    page = request.args.get('page', 1, type=int)
    per_page = 50
    
    query = LotteryDraw.query
    if game_id:
        query = query.filter_by(game_id=game_id)
    
    draws = query.order_by(LotteryDraw.draw_date.desc()).paginate(
        page=page, per_page=per_page, error_out=False
    )
    
    return render_template('admin/draws.html', draws=draws, games=GAMES, current_game=game_id)

@app.route('/admin/draws/add', methods=['GET', 'POST'])
@login_required
def admin_add_draw():
    """Add a new draw"""
    if not current_user.is_admin:
        flash('You do not have permission to access this page.', 'error')
        return redirect(url_for('index'))
    
    if request.method == 'POST':
        try:
            draw = LotteryDraw(
                game_id=request.form.get('game_id', type=int),
                draw_date=datetime.strptime(request.form.get('draw_date'), '%Y-%m-%d').date(),
                ball1=request.form.get('ball1', type=int),
                ball2=request.form.get('ball2', type=int),
                ball3=request.form.get('ball3', type=int),
                ball4=request.form.get('ball4', type=int),
                ball5=request.form.get('ball5', type=int),
                ball6=request.form.get('ball6', type=int) if request.form.get('ball6') else None,
                mega_ball=request.form.get('mega_ball', type=int) if request.form.get('mega_ball') else None
            )
            
            draw.calculate_stats()
            db.session.add(draw)
            db.session.commit()
            
            flash('Draw added successfully!', 'success')
            return redirect(url_for('admin_draws'))
            
        except Exception as e:
            flash(f'Error adding draw: {str(e)}', 'error')
    
    return render_template('admin/add_draw.html', games=GAMES)

@app.route('/admin/draws/edit/<int:draw_id>', methods=['GET', 'POST'])
@login_required
def admin_edit_draw(draw_id):
    """Edit an existing draw"""
    if not current_user.is_admin:
        flash('You do not have permission to access this page.', 'error')
        return redirect(url_for('index'))
    
    draw = LotteryDraw.query.get_or_404(draw_id)
    
    if request.method == 'POST':
        try:
            draw.draw_date = datetime.strptime(request.form.get('draw_date'), '%Y-%m-%d').date()
            draw.ball1 = request.form.get('ball1', type=int)
            draw.ball2 = request.form.get('ball2', type=int)
            draw.ball3 = request.form.get('ball3', type=int)
            draw.ball4 = request.form.get('ball4', type=int)
            draw.ball5 = request.form.get('ball5', type=int)
            draw.ball6 = request.form.get('ball6', type=int) if request.form.get('ball6') else None
            draw.mega_ball = request.form.get('mega_ball', type=int) if request.form.get('mega_ball') else None
            
            draw.calculate_stats()
            db.session.commit()
            
            flash('Draw updated successfully!', 'success')
            return redirect(url_for('admin_draws'))
            
        except Exception as e:
            flash(f'Error updating draw: {str(e)}', 'error')
    
    return render_template('admin/edit_draw.html', draw=draw, games=GAMES)

@app.route('/admin/draws/delete/<int:draw_id>', methods=['POST'])
@login_required
def admin_delete_draw(draw_id):
    """Delete a draw"""
    if not current_user.is_admin:
        return jsonify({'error': 'Permission denied'}), 403
    
    draw = LotteryDraw.query.get_or_404(draw_id)
    db.session.delete(draw)
    db.session.commit()
    
    return jsonify({'status': 'success'})

@app.route('/admin/import', methods=['GET', 'POST'])
@login_required
def admin_bulk_import():
    """Bulk import draws from CSV file"""
    if not current_user.is_admin:
        flash('You do not have permission to access this page.', 'error')
        return redirect(url_for('index'))
    
    if request.method == 'POST':
        if 'file' not in request.files:
            flash('No file uploaded', 'error')
            return redirect(request.url)
        
        file = request.files['file']
        
        if file.filename == '':
            flash('No file selected', 'error')
            return redirect(request.url)
        
        if not file.filename.endswith('.csv'):
            flash('Please upload a CSV file', 'error')
            return redirect(request.url)
        
        try:
            # Process CSV file
            stream = TextIOWrapper(file.stream, encoding='utf-8')
            csv_reader = csv.DictReader(stream)
            
            imported = 0
            errors = []
            
            for row_num, row in enumerate(csv_reader, start=2):  # Start at 2 to account for header
                try:
                    # Validate required fields
                    if not row.get('game_id') or not row.get('date'):
                        errors.append(f"Row {row_num}: Missing game_id or date")
                        continue
                    
                    game_id = int(row['game_id'])
                    if game_id not in GAMES:
                        errors.append(f"Row {row_num}: Invalid game_id {game_id}")
                        continue
                    
                    # Create draw object
                    draw = LotteryDraw(
                        game_id=game_id,
                        draw_date=datetime.strptime(row['date'], '%Y-%m-%d').date()
                    )
                    
                    # Add ball numbers
                    balls = []
                    for i in range(1, 7):
                        ball_key = f'ball{i}'
                        if ball_key in row and row[ball_key]:
                            ball_value = int(row[ball_key])
                            setattr(draw, ball_key, ball_value)
                            balls.append(ball_value)
                    
                    # Validate number of balls
                    expected_balls = GAMES[game_id]['balls_drawn']
                    if len(balls) != expected_balls:
                        errors.append(f"Row {row_num}: Expected {expected_balls} balls, got {len(balls)}")
                        continue
                    
                    # Check for duplicates
                    if len(set(balls)) != len(balls):
                        errors.append(f"Row {row_num}: Duplicate ball numbers")
                        continue
                    
                    # Sort balls
                    balls.sort()
                    for i, ball in enumerate(balls, start=1):
                        setattr(draw, f'ball{i}', ball)
                    
                    # Add mega ball if applicable
                    if GAMES[game_id].get('mega') and row.get('mega_ball'):
                        draw.mega_ball = int(row['mega_ball'])
                    
                    # Calculate statistics
                    draw.calculate_stats()
                    
                    # Check if draw already exists
                    existing = LotteryDraw.query.filter_by(
                        game_id=draw.game_id,
                        draw_date=draw.draw_date
                    ).first()
                    
                    if existing:
                        errors.append(f"Row {row_num}: Draw already exists for {row['date']}")
                        continue
                    
                    db.session.add(draw)
                    imported += 1
                    
                except ValueError as e:
                    errors.append(f"Row {row_num}: Invalid number format - {str(e)}")
                except Exception as e:
                    errors.append(f"Row {row_num}: {str(e)}")
            
            if imported > 0:
                db.session.commit()
                flash(f'Successfully imported {imported} draws', 'success')
            
            if errors:
                error_msg = "Errors encountered:\n" + "\n".join(errors[:10])  # Show first 10 errors
                if len(errors) > 10:
                    error_msg += f"\n... and {len(errors) - 10} more errors"
                flash(error_msg, 'warning')
            
            return redirect(url_for('admin_draws'))
            
        except Exception as e:
            flash(f'Error processing file: {str(e)}', 'error')
            return redirect(request.url)
    
    return render_template('admin/bulk_import.html', games=GAMES)

# Export Routes
@app.route('/export/csv/<int:game_id>')
def export_csv(game_id):
    """Export game data to CSV"""
    if game_id not in GAMES:
        return "Game not found", 404
    
    # Get parameters
    days = request.args.get('days', type=int)
    
    analyzer = LotteryAnalyzer(game_id)
    draws = analyzer.get_draws(days=days)
    
    # Create DataFrame
    data = []
    for draw in draws:
        row = {
            'Date': draw.draw_date,
            'Ball 1': draw.ball1,
            'Ball 2': draw.ball2,
            'Ball 3': draw.ball3,
            'Ball 4': draw.ball4,
            'Ball 5': draw.ball5,
        }
        if draw.ball6:
            row['Ball 6'] = draw.ball6
        if draw.mega_ball:
            row['Mega Ball'] = draw.mega_ball
        row.update({
            'Sum': draw.draw_sum,
            'Average': draw.average,
            'Even Count': draw.even_count,
            'Odd Count': draw.odd_count
        })
        data.append(row)
    
    df = pd.DataFrame(data)
    
    # Create CSV
    output = StringIO()
    df.to_csv(output, index=False)
    output.seek(0)
    
    filename = f"{GAMES[game_id]['name'].replace(' ', '_')}_{datetime.now().strftime('%Y%m%d')}.csv"
    
    return send_file(
        BytesIO(output.getvalue().encode()),
        mimetype='text/csv',
        as_attachment=True,
        download_name=filename
    )

@app.route('/export/pdf/<int:game_id>')
def export_pdf(game_id):
    """Export game analysis report to PDF"""
    if game_id not in GAMES:
        return "Game not found", 404
    
    # Get parameters
    days = request.args.get('days', type=int)
    
    analyzer = LotteryAnalyzer(game_id)
    draws = analyzer.get_draws(limit=100, days=days)
    
    # Calculate statistics
    frequency, last_seen = analyzer.calculate_frequency(draws)
    hot_cold = analyzer.get_hot_cold_numbers(draws)
    sum_stats = analyzer.calculate_sum_stats(draws)
    
    # Create PDF
    buffer = BytesIO()
    doc = SimpleDocTemplate(buffer, pagesize=letter)
    elements = []
    styles = getSampleStyleSheet()
    
    # Title
    title_style = ParagraphStyle(
        'CustomTitle',
        parent=styles['Heading1'],
        fontSize=24,
        textColor=colors.HexColor('#333333'),
        alignment=1  # Center
    )
    elements.append(Paragraph(f"{GAMES[game_id]['name']} Analysis Report", title_style))
    elements.append(Spacer(1, 0.25*inch))
    
    # Date range
    date_text = f"Report generated on: {datetime.now().strftime('%Y-%m-%d')}"
    if days:
        date_text += f" (Last {days} days)"
    elements.append(Paragraph(date_text, styles['Normal']))
    elements.append(Spacer(1, 0.25*inch))
    
    # Summary Statistics
    elements.append(Paragraph("Summary Statistics", styles['Heading2']))
    stats_data = [
        ['Metric', 'Value'],
        ['Total Draws', len(draws)],
        ['Sum Range', f"{sum_stats.get('min', 'N/A')} - {sum_stats.get('max', 'N/A')}"],
        ['Average Sum', f"{sum_stats.get('average', 0):.2f}"],
        ['Median Sum', f"{sum_stats.get('median', 0):.2f}"],
        ['Standard Deviation', f"{sum_stats.get('std_dev', 0):.2f}"]
    ]
    
    stats_table = Table(stats_data, colWidths=[3*inch, 2*inch])
    stats_table.setStyle(TableStyle([
        ('BACKGROUND', (0, 0), (-1, 0), colors.grey),
        ('TEXTCOLOR', (0, 0), (-1, 0), colors.whitesmoke),
        ('ALIGN', (0, 0), (-1, -1), 'CENTER'),
        ('FONTNAME', (0, 0), (-1, 0), 'Helvetica-Bold'),
        ('FONTSIZE', (0, 0), (-1, 0), 12),
        ('BOTTOMPADDING', (0, 0), (-1, 0), 12),
        ('BACKGROUND', (0, 1), (-1, -1), colors.beige),
        ('GRID', (0, 0), (-1, -1), 1, colors.black)
    ]))
    elements.append(stats_table)
    elements.append(Spacer(1, 0.5*inch))
    
    # Hot Numbers
    elements.append(Paragraph("Hot Numbers (Most Frequent)", styles['Heading2']))
    hot_data = [['Number', 'Frequency', 'Last Drawn']]
    for num, freq, last_date in hot_cold['hot'][:10]:
        hot_data.append([str(num), str(freq), str(last_date)])
    
    hot_table = Table(hot_data, colWidths=[1.5*inch, 1.5*inch, 2*inch])
    hot_table.setStyle(TableStyle([
        ('BACKGROUND', (0, 0), (-1, 0), colors.red),
        ('TEXTCOLOR', (0, 0), (-1, 0), colors.whitesmoke),
        ('ALIGN', (0, 0), (-1, -1), 'CENTER'),
        ('FONTNAME', (0, 0), (-1, 0), 'Helvetica-Bold'),
        ('FONTSIZE', (0, 0), (-1, 0), 12),
        ('BOTTOMPADDING', (0, 0), (-1, 0), 12),
        ('BACKGROUND', (0, 1), (-1, -1), colors.lightyellow),
        ('GRID', (0, 0), (-1, -1), 1, colors.black)
    ]))
    elements.append(hot_table)
    
    # Build PDF
    doc.build(elements)
    buffer.seek(0)
    
    filename = f"{GAMES[game_id]['name'].replace(' ', '_')}_Report_{datetime.now().strftime('%Y%m%d')}.pdf"
    
    return send_file(
        buffer,
        mimetype='application/pdf',
        as_attachment=True,
        download_name=filename
    )

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

@app.cli.command()
def create_admin():
    """Create an admin user"""
    import getpass
    
    username = input("Enter admin username: ")
    email = input("Enter admin email: ")
    password = getpass.getpass("Enter admin password: ")
    
    # Check if user already exists
    if User.query.filter_by(username=username).first():
        print(f"User '{username}' already exists!")
        return
    
    user = User(
        username=username,
        email=email,
        is_admin=True
    )
    user.set_password(password)
    
    db.session.add(user)
    db.session.commit()
    
    print(f"Admin user '{username}' created successfully!")

@app.cli.command()
def create_sample_csv():
    """Create sample import CSV file"""
    import os
    
    sample_content = '''game_id,date,ball1,ball2,ball3,ball4,ball5,ball6,mega_ball
1,2024-01-15,5,12,23,34,41,,
1,2024-01-14,3,8,19,27,38,,
2,2024-01-15,7,14,28,45,67,,10
3,2024-01-15,8,15,22,31,44,,
4,2024-01-15,4,11,19,26,33,40,
5,2024-01-15,2,9,16,24,32,,
6,2024-01-15,5,13,21,29,37,45,
7,2024-01-15,5,15,25,35,55,,20'''
    
    os.makedirs('static', exist_ok=True)
    with open('static/sample_import.csv', 'w') as f:
        f.write(sample_content.strip())
    
    print("Sample CSV created at static/sample_import.csv")

if __name__ == '__main__':
    app.run(debug=True)