import re
import pymysql

game = 1  # Georgia Fantasy 5

filename = 'C:\\Bitnami\\wampstack-8.1.3-0\\apache2\\htdocs\\lotto\\gaf5_170615.txt'
print(filename)

with open(filename, "r") as fp:
    contents = fp.read()

print("contents =", contents)

matches_date = re.findall(r"(\d{2}/\d{2}/\d{4})", contents)
print(matches_date)

matches_nums = re.findall(r"(\d{2} \d{2} \d{2} \d{2} \d{2})", contents)
print(matches_nums)

size_date = len(matches_date)
print("size_date =", size_date)

# Connect to MySQL
mysqli_link = pymysql.connect(
    host='localhost',
    user='root',
    password='wef5esuv',
    database='ga_f5_lotto'
)

print('Success... ' + mysqli_link.get_host_info())

for x in range(size_date - 1):
    date = matches_date[x].split("/")
    print(f"{date[2]}/{date[0]}/{date[1]} ", end="")

    draws = matches_nums[x].split(" ")
    print(f"{draws[0]}-{draws[1]}-{draws[2]}-{draws[3]}-{draws[4]} ")

    # Get from draw table
    query5 = f"SELECT * FROM {draw_table_name} WHERE date = '{date[2]}-{date[0]}-{date[1]}'"
    print(query5)

    cursor = mysqli_link.cursor()
    cursor.execute(query5)
    num_rows5 = cursor.rowcount
    print("num_rows5 =", num_rows5)

    if not num_rows5:
        query_insert = f"INSERT INTO `ga_f5_lotto`.`ga_f5_draws` (`date`, `b1`, `b2`, `b3`, `b4`, `b5`, `sum`, `even`, `odd`, `eo50_wa`, `draw0`, `draw1`, `draw2`, `draw3`, `draw4`, `rank0`, `rank1`, `rank2`, `rank3`, `rank4`, `rank5`, `rank6`, `pair_sum`, `draw_average`, `median`, `harmean`, `geomean`, `quart1`, `quart2`, `quart3`, `stdev`, `variance`, `avedev`, `kurt`, `skew`, `devsq`, `nums_total_2`, `combo_total_2`, `nums_total_3`, `combo_total_3`, `nums_total_4`, `combo_total_4`, `s1510`, `s61510`, `last_updated`) VALUES ('{date[2]}-{date[0]}-{date[1]}', {draws[0]}, {draws[1]}, {draws[2]}, {draws[3]}, {draws[4]}, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0.00, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '1962-08-17')"

        print(query_insert)

        cursor.execute(query_insert)
        mysqli_link.commit()

mysqli_link.close()
