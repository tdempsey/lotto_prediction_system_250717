# Lottery Analysis Dashboard

A comprehensive web-based dashboard for analyzing lottery data, trends, and generating predictions.

## Features

- **Real-time Data Visualization**: Interactive charts showing number frequency, sum analysis, and even/odd distributions
- **Multi-Game Support**: Supports Georgia Fantasy 5, Mega Millions, Georgia 5, and Powerball
- **Recent Draws Display**: Shows the most recent lottery draws with visual number balls
- **Hot & Cold Numbers**: Identifies frequently drawn and rarely drawn numbers
- **Sum Analysis**: Statistical analysis of draw sums with average, most common, and range
- **Prediction Engine**: Multiple prediction methods (frequency-based, pattern-based, statistical)
- **Responsive Design**: Works on desktop, tablet, and mobile devices

## Files Structure

```
lottery_dashboard/
├── lottery_dashboard.html     # Main dashboard HTML
├── dashboard_styles.css       # CSS styling
├── dashboard_script.js        # Frontend JavaScript
├── dashboard_api.py          # Python Flask API backend
└── README_DASHBOARD.md       # This file
```

## Installation & Setup

### Prerequisites

1. Python 3.7+
2. MySQL database with lottery data
3. Web server (Apache/Nginx) or run directly in browser

### Python Dependencies

Install required Python packages:

```bash
pip install flask flask-cors mysql-connector-python
```

### Database Setup

Ensure your MySQL database has the following tables:
- `ga_f5_draws` (Georgia Fantasy 5)
- `mm_draws` (Mega Millions)
- `ga_5_draws` (Georgia 5)
- `pb_draws` (Powerball)

Each table should have columns: `date`, `b1`, `b2`, `b3`, `b4`, `b5`

### Configuration

Edit the database configuration in `dashboard_api.py`:

```python
DB_CONFIG = {
    'host': 'localhost',
    'user': 'your_username',
    'password': 'your_password',
    'database': 'your_database_name'
}
```

## Running the Dashboard

### Method 1: With Python API Backend

1. Start the Python API server:
```bash
python dashboard_api.py
```

2. Open `lottery_dashboard.html` in a web browser

3. The dashboard will automatically connect to the API for real data

### Method 2: Standalone (Mock Data)

1. Simply open `lottery_dashboard.html` in a web browser
2. The dashboard will use mock data if the API is not available

### Method 3: Web Server Deployment

1. Copy all files to your web server directory
2. Start the Python API: `python dashboard_api.py`
3. Access via web browser: `http://your-server/lottery_dashboard.html`

## API Endpoints

The Python backend provides these API endpoints:

- `GET /api/lottery-data?game=1` - Get all dashboard data
- `GET /api/recent-draws?game=1&limit=10` - Get recent draws
- `GET /api/frequency?game=1&limit=1000` - Get number frequency
- `GET /api/hot-cold?game=1&limit=500` - Get hot/cold numbers
- `GET /api/sum-analysis?game=1&limit=500` - Get sum analysis
- `GET /api/prediction?game=1&method=frequency` - Generate prediction
- `GET /api/health` - Health check

## Game IDs

- 1: Georgia Fantasy 5
- 2: Mega Millions
- 3: Georgia 5
- 7: Powerball

## Dashboard Features

### Statistics Cards
- **Last Draw**: Date of most recent draw
- **Next Draw**: Estimated next draw date
- **Total Draws**: Total number of draws in database
- **Hot Numbers**: Top 3 most frequent numbers

### Charts & Visualizations
- **Number Frequency**: Bar chart showing how often each number appears
- **Sum Distribution**: Line chart showing distribution of draw sums
- **Even/Odd Analysis**: Doughnut chart showing even vs odd number distribution

### Analysis Tools
- **Hot & Cold Numbers**: Visual display of frequently and rarely drawn numbers
- **Sum Statistics**: Average sum, most common sum, and sum range
- **Prediction Engine**: Generate predictions using different methods

### Interactive Features
- **Game Selection**: Switch between different lottery games
- **Refresh Data**: Update dashboard with latest data
- **Prediction Methods**: Choose from frequency, pattern, or statistical methods
- **Responsive Design**: Optimized for all screen sizes

## Customization

### Adding New Games
1. Add game configuration to `get_game_info()` in `dashboard_api.py`
2. Update the game selector in `lottery_dashboard.html`
3. Ensure database table exists with proper structure

### Styling
- Modify `dashboard_styles.css` for visual customization
- Colors, fonts, and layout can be easily adjusted
- CSS variables are used for consistent theming

### Prediction Algorithms
- Add new prediction methods in `dashboard_script.js`
- Extend the `calculatePrediction()` function
- Add corresponding API endpoints in `dashboard_api.py`

## Security Notes

- This dashboard is for educational/entertainment purposes only
- Ensure proper database security and access controls
- Consider implementing authentication for production use
- Validate all user inputs and sanitize database queries

## Browser Compatibility

- Modern browsers (Chrome, Firefox, Safari, Edge)
- Requires JavaScript enabled
- Responsive design supports mobile devices

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check MySQL server is running
   - Verify database credentials in `dashboard_api.py`
   - Ensure database tables exist

2. **API Not Loading**
   - Check if Python API server is running on port 5000
   - Verify CORS settings in `dashboard_api.py`
   - Check browser console for errors

3. **Charts Not Displaying**
   - Ensure Chart.js library is loading
   - Check browser console for JavaScript errors
   - Verify data format from API

4. **Styling Issues**
   - Check CSS file is loading properly
   - Verify Font Awesome icons are loading
   - Clear browser cache

## Performance Optimization

- API responses are cached for better performance
- Charts are updated efficiently without full reloads
- Database queries are optimized with proper indexes
- Responsive images and optimized assets

## Future Enhancements

- Real-time data updates via WebSockets
- Historical trend analysis
- Advanced prediction algorithms
- User customizable dashboards
- Export functionality for charts and data
- Mobile app version

## License

This dashboard is provided for educational and entertainment purposes only. Lottery predictions are for fun and should not be used for actual gambling decisions.