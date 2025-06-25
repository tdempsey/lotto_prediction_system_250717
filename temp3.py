# Simplified rank loading functions - pull directly from database only

def load_rank_limits(conn=None, cursor=None):
    """
    Load rank limits directly from MySQL database
    
    Parameters:
    conn (mysql.connector.connection): Database connection
    cursor (mysql.connector.cursor): Database cursor
    
    Returns:
    list: List of rank limits
    """
    try:
        # If connection and cursor are provided, use them
        if conn and cursor:
            query = "SELECT rank_limit FROM ga_f5_rank_limits ORDER BY rank_id"
            cursor.execute(query)
            results = cursor.fetchall()
            
            # Extract values from results
            if results:
                # If we're getting dictionary results
                if isinstance(results[0], dict):
                    return [int(row['rank_limit']) for row in results]
                # If we're getting tuple results
                else:
                    return [int(row[0]) for row in results]
            else:
                print("No rank limit data found in database, using defaults")
                return [1, 1, 2, 3, 2, 3, 1, 1]
        else:
            # No connection provided, try to open a new one
            try:
                temp_conn = mysql.connector.connect(**db_config)
                temp_cursor = temp_conn.cursor(dictionary=True)
                
                query = "SELECT rank_limit FROM ga_f5_rank_limits ORDER BY rank_id"
                temp_cursor.execute(query)
                results = temp_cursor.fetchall()
                
                if results:
                    rank_limits = [int(row['rank_limit']) for row in results]
                    temp_cursor.close()
                    temp_conn.close()
                    return rank_limits
                else:
                    temp_cursor.close()
                    temp_conn.close()
                    print("No rank limit data found in database, using defaults")
                    return [1, 1, 2, 3, 2, 3, 1, 1]
            except mysql.connector.Error as e:
                print(f"Database connection error when loading rank limits: {e}")
                return [1, 1, 2, 3, 2, 3, 1, 1]
    except Exception as e:
        print(f"Error loading rank limits: {e}")
        return [1, 1, 2, 3, 2, 3, 1, 1]

def load_rank_counts(conn=None, cursor=None):
    """
    Load rank counts directly from MySQL database
    
    Parameters:
    conn (mysql.connector.connection): Database connection
    cursor (mysql.connector.cursor): Database cursor
    
    Returns:
    list: List of rank counts
    """
    try:
        # If connection and cursor are provided, use them
        if conn and cursor:
            query = "SELECT rank_count FROM ga_f5_rank_counts ORDER BY rank_id"
            cursor.execute(query)
            results = cursor.fetchall()
            
            # Extract values from results
            if results:
                # If we're getting dictionary results
                if isinstance(results[0], dict):
                    return [int(row['rank_count']) for row in results]
                # If we're getting tuple results
                else:
                    return [int(row[0]) for row in results]
            else:
                print("No rank count data found in database, using defaults")
                return [5, 5, 2, 1, 3, 5, 3, 5, 5, 5, 5, 4, 2, 5, 5, 3, 5, 4, 0, 4, 
                        5, 2, 4, 5, 3, 5, 5, 0, 4, 3, 2, 1, 4, 5, 3, 5, 1, 4, 3, 3, 2, 5]
        else:
            # No connection provided, try to open a new one
            try:
                temp_conn = mysql.connector.connect(**db_config)
                temp_cursor = temp_conn.cursor(dictionary=True)
                
                query = "SELECT rank_count FROM ga_f5_rank_counts ORDER BY rank_id"
                temp_cursor.execute(query)
                results = temp_cursor.fetchall()
                
                if results:
                    rank_counts = [int(row['rank_count']) for row in results]
                    temp_cursor.close()
                    temp_conn.close()
                    return rank_counts
                else:
                    temp_cursor.close()
                    temp_conn.close()
                    print("No rank count data found in database, using defaults")
                    return [5, 5, 2, 1, 3, 5, 3, 5, 5, 5, 5, 4, 2, 5, 5, 3, 5, 4, 0, 4, 
                            5, 2, 4, 5, 3, 5, 5, 0, 4, 3, 2, 1, 4, 5, 3, 5, 1, 4, 3, 3, 2, 5]
            except mysql.connector.Error as e:
                print(f"Database connection error when loading rank counts: {e}")
                return [5, 5, 2, 1, 3, 5, 3, 5, 5, 5, 5, 4, 2, 5, 5, 3, 5, 4, 0, 4, 
                        5, 2, 4, 5, 3, 5, 5, 0, 4, 3, 2, 1, 4, 5, 3, 5, 1, 4, 3, 3, 2, 5]
    except Exception as e:
        print(f"Error loading rank counts: {e}")
        return [5, 5, 2, 1, 3, 5, 3, 5, 5, 5, 5, 4, 2, 5, 5, 3, 5, 4, 0, 4, 
                5, 2, 4, 5, 3, 5, 5, 0, 4, 3, 2, 1, 4, 5, 3, 5, 1, 4, 3, 3, 2, 5]

# GeorgiaFantasy5Predictor class remains the same
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
            self.num_range = range(1, 43)  # Numbers 1-42
            self.nums_per_draw = 5
            
            # Filter settings (can be adjusted via web interface)
            self.max_seq2 = 1
            self.max_seq3 = 0
            self.max_mod_tot = 1
            self.sum_range = (70, 139)
            
            # Load rank data from database
            self.rank_limits = load_rank_limits(self.conn, self.cursor)
            self.rank_counts = load_rank_counts(self.conn, self.cursor)
            
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
            
            # Load rank data with no connection (will use defaults)
            self.rank_limits = load_rank_limits()
            self.rank_counts = load_rank_counts()

# Add a utility function to check current database values
def check_database_rank_data():
    """
    Check what rank data is currently in the database
    """
    try:
        conn = mysql.connector.connect(**db_config)
        cursor = conn.cursor(dictionary=True)
        
        print("=== Current Rank Limits in Database ===")
        cursor.execute("SELECT * FROM ga_f5_rank_limits ORDER BY rank_id")
        limits = cursor.fetchall()
        for row in limits:
            print(f"Rank {row['rank_id']}: {row['rank_limit']} - {row.get('rank_description', 'No description')}")
        
        print("\n=== Current Rank Counts in Database ===")
        cursor.execute("SELECT * FROM ga_f5_rank_counts ORDER BY rank_id")
        counts = cursor.fetchall()
        for row in counts:
            print(f"Rank {row['rank_id']}: {row['rank_count']} - {row.get('rank_description', 'No description')}")
        
        cursor.close()
        conn.close()
        
    except Exception as e:
        print(f"Error checking database rank data: {e}")

# Usage example:
if __name__ == "__main__":
    # Check current database values
    check_database_rank_data()
    
    # Test loading from database
    limits = load_rank_limits()
    counts = load_rank_counts()
    
    print(f"\nLoaded {len(limits)} rank limits: {limits}")
    print(f"Loaded {len(counts)} rank counts: {counts}")