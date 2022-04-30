from xml.dom.minidom import Attr
from requests_html import HTMLSession
from bs4 import BeautifulSoup
import re
import os
import sys

from WebApp.AmazonSGWebScraper.AmazonSGCatalogueScraper import Average_Rating, Number_Of_Ratings
s = HTMLSession()

#my_url = "https://www.amazon.sg/Samsung-Factory-Unlocked-Smartphone-Pro-Grade/dp/B08FYTSXGQ/ref=sr_1_48?crid=21O3WZX42E419&keywords=samsung+smartphones&qid=1647967669&sprefix=samsung+smartphones%2Caps%2C270&sr=8-48"
print('Enter the url link to be scraped')
my_url = sys.argv[1]
print(f'Filtering out {my_url}')

# opening connection to url and grabbing page


# html parsing
def getdata(my_url):
    r = s.get(my_url)
    soup = BeautifulSoup(r.text, "html.parser")
    return soup


# use below line to check the html set
# page_soup.h1
soup = getdata(my_url)
# pulling all data sets on current page and verifying length
# use below line to check the length of the dataset
# len(containers)

search_term_value = soup.find(
    "span", {"class": "a-size-large product-title-word-break"}).text
search_term_stripped = search_term_value.strip()
search_term = search_term_stripped.replace(
    '"', ",").replace("|", ",").replace("-", ",")

# Change Directory
os.chdir('WebApp/AmazonSGPageFiles')

filename = "{}+Page.csv".format(search_term)
f = open(filename, "w")

headers = "review_url, image_url, item_name, item_price, average_rating (Max Score is 5), number_of_ratings \n"

f.write(headers)

Review_Url = "https://www.amazon.sg" + str(soup.find("a", {"class": "a-link-emphasis a-text-bold"}).href)
Image_Url = soup.find("div", {"class": "imgTagWrapper"}).img["src"]

try:
    Item_Price = soup.find("span", {"class": "a-offscreen"}).text
    Average_Rating = soup.find("span", {"class": "a-size-medium a-color-base a-text-beside-button a-text-bold"}).text[0:4]
    Number_Of_Ratings = soup.find("span", {"id": "acrCustomerReviewText"}).text

except:
    Item_Price = "NA"
    Average_Rating = "NA"
    Number_Of_Ratings = "NA"

print("review_url: " + Review_Url)
print("image_url: " + Image_Url)
print("item_name: " + search_term)
print("item_price: " + Item_Price)
print("average_rating: " + Average_Rating)
print("number_of_ratings: " + Number_Of_Ratings)

f.write(Review_Url.replace(",", "|") + "," + Image_Url.replace(",", "|") + "," + search_term.replace(",", "|") + "," +
 Item_Price.replace(",", "'") + "," + Average_Rating.replace(",", ".") + "," + Number_Of_Ratings.replace(",", ".")  + "\n")

f.close()
print("End of CSV Writing")

""" Start of tryign to pump values into db """

exit()

