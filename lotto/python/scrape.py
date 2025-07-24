python
import requests
from bs4 import BeautifulSoup

# Send a GET request to the URL
url = "http://www.example.com/"
response = requests.get(url)

# Parse the HTML content using Beautiful Soup
soup = BeautifulSoup(response.content, 'html.parser')

# Find all the links in the page
links = soup.find_all("a")

# Print the URLs of the links
print([link["href"] for link in links])