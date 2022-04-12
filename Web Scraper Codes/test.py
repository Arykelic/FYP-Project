from requests_html import HTMLSession
from bs4 import BeautifulSoup
import re
import os
s = HTMLSession()


print('Enter the url link to be scraped')
url = input('>')
print(f'Filtering out {url}')

def getdata(url):
    r = s.get(url)
    soup = BeautifulSoup(r.text, "html.parser")
    return soup

soup = getdata(url)
containers = soup.findAll("div", {"class": "a-section review aok-relative"})
        #use below line to check the length of the dataset

for container in containers:
    Review_Location_Container = container.findAll("span", {"class": "a-size-base a-color-secondary review-date"})[0].text[12:]
    Review_Location = re.search("^(.*)on",Review_Location_Container)
    Review_Location_Formatted = Review_Location.group()[:-2]
    print(Review_Location_Formatted)
    #Review_Location_Formatted = Review_Location.string
    #Review_Location_Formatted = Review_Location(text=re.compile("^(.*)on"))

    Review_Date_Container = container.findAll("span", {"class": "a-size-base a-color-secondary review-date"})[0].text[12:]
    Review_Date = re.search("on(.*)",Review_Date_Container)
    Review_Date_Formatted = Review_Date.group()[3:]
    print(Review_Date_Formatted)

    #print("Review_Location: " , Review_Location)
    #print("Review_Date: " , Review_Date)

print("End of CSV Writing")
        