-- SQL script to create and populate the ga_f5_rank_limits table

-- Create the table if it doesn't exist
CREATE TABLE IF NOT EXISTS ga_f5_rank_limits (
    rank_id INT NOT NULL AUTO_INCREMENT,
    rank_limit INT NOT NULL,
    rank_description VARCHAR(255),
    PRIMARY KEY (rank_id)
);

-- Clear existing data if any
TRUNCATE TABLE ga_f5_rank_limits;

-- Insert default rank limit data
-- These values are the same as the default fallback values in the app.py file
INSERT INTO ga_f5_rank_limits (rank_limit, rank_description) VALUES
(1, 'Rank 1 limit'),
(1, 'Rank 2 limit'),
(2, 'Rank 3 limit'),
(3, 'Rank 4 limit'),
(2, 'Rank 5 limit'),
(3, 'Rank 6 limit'),
(1, 'Rank 7 limit'),
(1, 'Rank 8 limit');

-- Verify the data was inserted correctly
SELECT * FROM ga_f5_rank_limits ORDER BY rank_id;