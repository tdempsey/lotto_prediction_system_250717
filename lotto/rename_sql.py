import mysql.connector

# Database connection details
config = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'ga_f5_lotto'
}

# Connect to the database
conn = mysql.connector.connect(**config)
cursor = conn.cursor()

# Fetch tables with the old prefix
old_prefix = 'temp2_filer_5_5'
new_prefix = 'temp2_filer_5_'
cursor.execute(f"SELECT table_name FROM information_schema.tables WHERE table_schema = '{config['database']}' AND table_name LIKE '{old_prefix}%'")
tables = cursor.fetchall()

# Generate and execute RENAME TABLE statements
for (table_name,) in tables:
    new_table_name = table_name.replace(old_prefix, new_prefix, 1)
    rename_query = f"RENAME TABLE `{table_name}` TO `{new_table_name}`;"
    cursor.execute(rename_query)
    print(f"Renamed table {table_name} to {new_table_name}")

# Commit changes
conn.commit()

# Close the connection
cursor.close()
conn.close()
