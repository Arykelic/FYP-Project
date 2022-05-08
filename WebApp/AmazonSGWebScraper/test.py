
from bs4 import BeautifulSoup
import sys
import re
import os
from debugpy import connect
import pymysql
import requests


""" print('Enter the search term to be scraped (delimit search terms with a "+")') """
search_term = sys.argv[1]
print(f'Filtering out {search_term}')
url = f"https://www.amazon.sg/s?k={search_term}"
createdby = sys.argv[2]

# Download the webpage

page = requests.get(url).text
doc = BeautifulSoup(page, "html.parser")

page_text = doc.find(class_="s-pagination-strip")
page_loop = page_text.find(class_="s-pagination-item s-pagination-disabled").text
page_count = int(page_loop)

def beforeQuestionMark(inputStr):
    return inputStr.split("?")[0]

i = 1

for page in range(1, page_count + 1):
    url = f"https://www.amazon.sg/s?k={search_term}&page={page}"
    page = requests.get(url).text
    doc = BeautifulSoup(page, "html.parser")
    
    container = doc.find(class_="s-main-slot s-result-list s-search-results sg-row")
    items = container.find_all(class_="sg-col-4-of-12 s-result-item s-asin sg-col-4-of-16 sg-col s-widget-spacing-small sg-col-4-of-20").text
    print(items)

    """ for item in items:
        parent = item.parent
        if parent.name != "a":
                continue
        link = parent['href']
        print(link) """

        




