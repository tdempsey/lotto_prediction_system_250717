import requests
from bs4 import BeautifulSoup

url = 'https://www.galottery.com/en-us/games/draw-games/fantasy-five.html#tab-winningNumbers'
response = requests.get(url)
soup = BeautifulSoup(response.text, 'html.parser')

winning_numbers = []

table = soup.find('table', class_='table table-striped table-winning-numbers')
rows = table.find_all('tr')

for row in rows:
    data = row.find_all('td')
    if len(data) > 0:
        numbers = [td.text.strip() for td in data]
        winning_numbers.append(numbers)

print(winning_numbers)
