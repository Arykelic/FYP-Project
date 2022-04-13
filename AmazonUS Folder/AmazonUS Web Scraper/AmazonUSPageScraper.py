from xml.dom.minidom import Attr
from requests_html import HTMLSession
from bs4 import BeautifulSoup
import re
import os
s = HTMLSession()

#my_url = "https://www.amazon.sg/Samsung-Factory-Unlocked-Smartphone-Pro-Grade/dp/B08FYTSXGQ/ref=sr_1_48?crid=21O3WZX42E419&keywords=samsung+smartphones&qid=1647967669&sprefix=samsung+smartphones%2Caps%2C270&sr=8-48"
print('Enter the url link to be scraped')
my_url = input('>')
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
print(soup)
# pulling all data sets on current page and verifying length
containers = soup.findAll("div", {"class": "a-section celwidget"})
# use below line to check the length of the dataset
print(len(containers))

search_term_value = soup.find("span", {"class": "a-size-large product-title-word-break"}).text
search_term_stripped = search_term_value.strip()
search_term = search_term_stripped.replace('"', ",").replace("|",",")

#Change Directory
os.chdir('AmazonUS Page Files')

filename = "{}_Page.csv".format(search_term)
f = open(filename, "w")

headers = "Image_Url, Item_Name, Item_Price, Username, Rating_Score (Max Score is 5), Review_Description, Review_Date \n"

f.write(headers)


# loop
for container in containers:
    Image_Url = soup.find("div", {"class": "imgTagWrapper"}).img["src"]

    Item_Name_Value = soup.find("span", {"class": "a-size-large product-title-word-break"}).text
    Item_Name = Item_Name_Value.strip()

    Item_Price = soup.find("span", {"class": "a-offscreen"}).text

    Username_Container = container.findAll("span", {"class": "a-profile-name"})
    Username = Username_Container[0].text

    Rating_Score_Container = container.findAll("span", {"class": "a-icon-alt"})
    Rating_Score = Rating_Score_Container[0].text[0:4]

    Review_Description_Container = container.findAll("div", {"class": "a-expander-content reviewText review-text-content a-expander-partial-collapse-content"})
    Review_Description_Value = Review_Description_Container[0].text
    Review_Description = Review_Description_Value.strip()

    Review_Date_Container = container.findAll("span", {"class": "a-size-base a-color-secondary review-date"})
    Review_Date = Review_Date_Container[0].text


    print("Image Url: " + Image_Url)
    print("Item_Name: " + Item_Name)
    print("Item_Price: " + Item_Price)
    print("Username: " + Username)
    print("Rating_Score: " + Rating_Score)
    print("Review_Description: " + Review_Description)
    print("Review_Date: " + Review_Date)

    f.write(Image_Url.replace(",", "|") + "," + Item_Name.replace(",", "|") + "," + Item_Price.replace(",", "'") + "," + Username.replace(",", ".")
    + "," + Rating_Score.replace(",", ".") + "," + Review_Description.replace(",", "'").replace("\U0001f60a",":)") + "," + Review_Date.replace(",", "'") + "\n")

f.close()
print("End of CSV Writing")
