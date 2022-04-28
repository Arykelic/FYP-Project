from requests_html import HTMLSession
from bs4 import BeautifulSoup
import sys
import re
import os
s = HTMLSession()

""" print('Enter the search term to be scraped (delimit search terms with a "+")')
search_term = input('>')
print(f'Filtering out {search_term}')
url = "https://www.amazon.sg/s?k={}".format(search_term) """

url = "https://www.amazon.sg/s?k=food"

# Download the webpage


def getdata(url):
    r = s.get(url)
    soup = BeautifulSoup(r.text, "html.parser")
    return soup

# Parse next pagination


def getnextpage(soup):
    try:
        page = soup.find("span", {"class": "s-pagination-strip"})
        # if not last page
        if not page.find("span", {"class": "s-pagination-item s-pagination-next s-pagination-disabled "}):
            url = "https://www.amazon.sg" + str(page.find("a", {"class": "s-pagination-item s-pagination-next s-pagination-button s-pagination-separator"})["href"])
            return url
        else:
            return
    except TypeError:
        return
    except AttributeError:
        return

# conversion of data into CSV

# Change Directory
os.chdir('AmazonSGCatalogueFiles')

#filename = "{}+Catalogue.csv".format(search_term)
filename = "food+Catalogue.csv"
f = open(filename, "w", encoding="utf-8")

headers = "product_url, image_url, item_name, item_price, average_rating (Max Score is 5), number_of_ratings \n"

f.write(headers)

while True:
    soup = getdata(url)
    # pulling all data sets on current page and verifying length
    # create containers group
    containers = soup.findAll("div", {"class": "sg-col-4-of-12 s-result-item s-asin sg-col-4-of-16 sg-col s-widget-spacing-small sg-col-4-of-20"})
    # use below line to check the length of the dataset
    print(len(containers))
    #containers
    #filename = "{}_Catalogue.csv".format(url).replace("/",",").replace("?",",")
    #f = open(filename, "w", encoding="utf-8")

    #headers = "Image_Url, Item_Name, Item_Price, Average_Rating, Number_Review\n"

    # f.write(headers)

    # loop inside each container
    for container in containers:
        Product_Url_Container = container.findAll("a", {"class": "a-link-normal s-no-outline"})
        Product_Url = "https://www.amazon.sg" + str(Product_Url_Container[0]["href"])

        Image_Url_Container = container.findAll("div", {"class": "a-section aok-relative s-image-square-aspect"})
        Image_Url = Image_Url_Container[0].img["src"]

        Item_Name_Container = container.findAll("span", {"class": "a-size-base a-color-base a-text-normal"})
        Item_Name = Item_Name_Container[0].text

        try:
            Item_Price_Container = container.findAll("span", {"class": "a-offscreen"})
            Item_Price = Item_Price_Container[0].text[2:]

            Average_Rating_Container = container.findAll("span", {"class": "a-icon-alt"})
            Average_Rating = Average_Rating_Container[0].text[0:4]

            Number_Of_Ratings_Container = container.findAll("span", {"class": "a-size-base s-underline-text"})
            Number_Of_Ratings = Number_Of_Ratings_Container[0].text

        except:
            Item_Price = ""
            Average_Rating = ""
            Number_Of_Ratings = ""

        print("product_url: " + Product_Url)
        print("image_url: " + Image_Url)
        print("item_name: " + Item_Name)
        print("item_price: " + Item_Price)
        print("average_rating: " + Average_Rating)
        print("number_of_ratings: " + Number_Of_Ratings)

        f.write(Product_Url.replace(",", "|") + "," + Image_Url.replace(",", "|") + "," + Item_Name.replace(",", "|") + "," +
                Item_Price.replace(",", "'") + "," + Average_Rating.replace(",", "'") + "," + Number_Of_Ratings.replace(",", "'") + "\n")

    # parse the next url
    url = getnextpage(soup)
    if not url:
        f.close()
        print("End of CSV Writing")
        break
