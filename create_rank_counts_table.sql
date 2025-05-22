-- SQL script to create and populate the ga_f5_rank_counts table

-- Create the table if it doesn't exist
CREATE TABLE IF NOT EXISTS ga_f5_rank_counts (
    rank_id INT NOT NULL AUTO_INCREMENT,
    rank_count INT NOT NULL,
    rank_description VARCHAR(255),
    PRIMARY KEY (rank_id)
);

-- Clear existing data if any
TRUNCATE TABLE ga_f5_rank_counts;

-- Insert default rank count data
-- These values are the same as the default fallback values in the app.py file
INSERT INTO ga_f5_rank_counts (rank_count, rank_description) VALUES
(5, 'Rank 1 count'),
(5, 'Rank 2 count'),
(2, 'Rank 3 count'),
(1, 'Rank 4 count'),
(3, 'Rank 5 count'),
(5, 'Rank 6 count'),
(3, 'Rank 7 count'),
(5, 'Rank 8 count'),
(5, 'Rank 9 count'),
(5, 'Rank 10 count'),
(5, 'Rank 11 count'),
(4, 'Rank 12 count'),
(2, 'Rank 13 count'),
(5, 'Rank 14 count'),
(5, 'Rank 15 count'),
(3, 'Rank 16 count'),
(5, 'Rank 17 count'),
(4, 'Rank 18 count'),
(0, 'Rank 19 count'),
(4, 'Rank 20 count'),
(5, 'Rank 21 count'),
(2, 'Rank 22 count'),
(4, 'Rank 23 count'),
(5, 'Rank 24 count'),
(3, 'Rank 25 count'),
(5, 'Rank 26 count'),
(5, 'Rank 27 count'),
(0, 'Rank 28 count'),
(4, 'Rank 29 count'),
(3, 'Rank 30 count'),
(2, 'Rank 31 count'),
(1, 'Rank 32 count'),
(4, 'Rank 33 count'),
(5, 'Rank 34 count'),
(3, 'Rank 35 count'),
(5, 'Rank 36 count'),
(1, 'Rank 37 count'),
(4, 'Rank 38 count'),
(3, 'Rank 39 count'),
(3, 'Rank 40 count'),
(2, 'Rank 41 count'),
(5, 'Rank 42 count');

-- Verify the data was inserted correctly
SELECT * FROM ga_f5_rank_counts ORDER BY rank_id;