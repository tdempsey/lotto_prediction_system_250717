# Simplified rank loading functions - no database, no CSV files

def load_rank_limits():
    """
    Load rank limits from hardcoded values
    
    Returns:
    list: List of rank limits
    """
    return [1, 1, 2, 3, 2, 3, 1, 1]

def load_rank_counts():
    """
    Load rank counts from hardcoded values
    
    Returns:
    list: List of rank counts
    """
    return [5, 5, 2, 1, 3, 5, 3, 5, 5, 5, 5, 4, 2, 5, 5, 3, 5, 4, 0, 4, 
            5, 2, 4, 5, 3, 5, 5, 0, 4, 3, 2, 1, 4, 5, 3, 5, 1, 4, 3, 3, 2, 5]

# Simplified GeorgiaFantasy5Predictor class initialization
class GeorgiaFantasy5Predictor:
    def __init__(self, config):
        """
        Initialize the Georgia Fantasy 5 Predictor
        
        Parameters:
        config (dict): MySQL database connection parameters
        """
        try:
            self.conn = mysql.connector.connect(**config)
            self.cursor = self.conn.cursor(dictionary=True)
            
            # Georgia Fantasy 5 specifics
            self.num_range = range(1, 43)  # Numbers 1-39
            self.nums_per_draw = 5
            
            # Filter settings (can be adjusted via web interface)
            self.max_seq2 = 1
            self.max_seq3 = 0
            self.max_mod_tot = 1
            self.sum_range = (70, 139)
            
            # Load rank data from hardcoded values
            self.rank_limits = load_rank_limits()
            self.rank_counts = load_rank_counts()
            
            # Load column 1 distribution data
            self.col1_data = self.load_col1_data()
            
            # Create cyclers for round-robin col1 selection
            self.col1_cyclers = {}
            for key, values in self.col1_data.items():
                if values:  # Only create cyclers for non-empty lists
                    self.col1_cyclers[key] = cycle(values)
            
            # Load historical data
            self.load_historical_data()
        except mysql.connector.Error as e:
            print(f"Database connection error: {e}")
            # Create empty dataframe for historical draws if DB connection fails
            self.historical_draws = pd.DataFrame(columns=['date', 'b1', 'b2', 'b3', 'b4', 'b5', 'sum'])
            self.conn = None
            self.cursor = None
            
            # Still load rank data even if DB fails
            self.rank_limits = load_rank_limits()
            self.rank_counts = load_rank_counts()