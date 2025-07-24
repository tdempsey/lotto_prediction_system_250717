# Lottery Analysis System

A Flask-based web application for analyzing lottery draw patterns and statistics.

## Features

- Display and analyze lottery draw statistics
- Statistical analysis of number frequency
- Pair analysis to identify commonly drawn number pairs
- Grid visualization of pair frequency
- Ranking of numbers by appearance frequency
- Support for multiple lottery games

## Requirements

- Python 3.7+
- Flask
- PyMySQL
- Bootstrap 4 (included in static/css)

## Installation

1. Clone the repository
2. Install the required dependencies:

```bash
pip install flask pymysql
```

3. Copy Bootstrap files:
   - Download Bootstrap 4 if not already in the static directory
   - Place bootstrap.min.css in static/css/
   - Place jquery-3.5.1.slim.min.js and bootstrap.bundle.min.js in static/js/

## Database Setup

The application requires a MySQL database with tables for lottery draws. Each lottery game should have its own table following this structure:

```sql
CREATE TABLE ga_f5_draws (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    b1 TINYINT UNSIGNED NOT NULL,
    b2 TINYINT UNSIGNED NOT NULL,
    b3 TINYINT UNSIGNED NOT NULL,
    b4 TINYINT UNSIGNED NOT NULL,
    b5 TINYINT UNSIGNED NOT NULL,
    sum SMALLINT UNSIGNED DEFAULT 0,
    even TINYINT UNSIGNED DEFAULT 0,
    odd TINYINT UNSIGNED DEFAULT 0,
    pb TINYINT UNSIGNED DEFAULT 0,
    harmean FLOAT DEFAULT 0,
    geomean FLOAT DEFAULT 0,
    quart1 TINYINT UNSIGNED DEFAULT 0,
    quart2 TINYINT UNSIGNED DEFAULT 0,
    quart3 TINYINT UNSIGNED DEFAULT 0,
    stdev FLOAT DEFAULT 0,
    variance FLOAT DEFAULT 0,
    avedev FLOAT DEFAULT 0,
    kurt FLOAT DEFAULT 0,
    skew FLOAT DEFAULT 0,
    wa FLOAT DEFAULT 0
);
```

Update the database configuration in app.py to match your environment.

## Running the Application

```bash
python app.py
```

The application will be available at http://127.0.0.1:5000/

## Configuration

Game and database configuration can be modified in the Config class in app.py.

## Features Ported from Original PHP Version

- Main display with draw history
- Statistical calculations (mean, median, quartiles, etc.)
- Number frequency analysis
- Pair analysis with grid visualization
- Number ranking

## License

This project is for educational purposes only.