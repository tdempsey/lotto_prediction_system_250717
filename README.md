# Georgia Fantasy 5 Lottery Prediction System

This system analyzes historical Georgia Fantasy 5 lottery draws and generates predictions based on statistical patterns and filters.

## Key Features

- Pattern-based prediction algorithm for Georgia Fantasy 5
- Statistical analysis of historical draws
- Filtering system with multiple criteria
- Web interface for easy use and visualization
- Database storage for historical data and configuration

## Setup Instructions

### Database Setup

1. Create a MySQL database named `ga_f5_lotto`
2. Set up all required tables using the provided database tool:
   ```bash
   python setup_database.py init
   ```
   
   This will create and initialize:
   - `ga_f5_draws` - Stores historical lottery draws
   - `ga_f5_rank_counts` - Stores rank count configuration
   - `ga_f5_rank_limits` - Stores rank limit configuration

3. To check the status of your database tables:
   ```bash
   python setup_database.py check
   ```

4. Database Maintenance:
   - Update rank counts: `python setup_database.py update-counts 5,5,2,1,3,5,...`
   - Update rank limits: `python setup_database.py update-limits 1,1,2,3,2,3,1,1`
   - For help: `python setup_database.py --help`

### Application Setup

1. Install required dependencies:
```bash
pip install flask pandas numpy mysql-connector-python
```

2. Run the web application:
```bash
python app.py
```

3. Access the web interface at http://localhost:5000

## System Components

- **app.py**: Main Flask web application
- **lot_display.py**: Standalone prediction script
- **ps_cli.py**: Command-line interface
- **Templates**: HTML files for web interface
- **Data Files**: CSV files for configuration
- **SQL Scripts**: For database setup and management

## Database Tables

- **ga_f5_draws**: Stores historical draws with date and ball numbers
- **ga_f5_rank_counts**: Stores rank counts used in prediction algorithms
- **ga_f5_rank_limits**: Stores rank limits used in prediction algorithms

Using database tables for configuration offers several advantages over CSV files:
1. Centralized data management
2. Better data integrity
3. Easier to update through admin interfaces
4. More resilient to file system issues

## License

Copyright (c) 2025. All rights reserved.
