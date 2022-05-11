from requests_html import HTMLSession
from bs4 import BeautifulSoup
import sys
import re
import os

import pymysql

s = HTMLSession()

""" print('Enter the search term to be scraped (delimit search terms with a "+")') """
search_term = sys.argv[1]
print(f'Filtering out {search_term}')
url = "https://www.amazon.sg/s?k={}".format(search_term)
createdby = sys.argv[2]

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

def splitString(inputStr):
    return inputStr.split("ref")[0]

i = 1
recordsinserted = 0
duplicatecount = 0

""" while True: """
while i <= 21:
        soup = getdata(url)
        # pulling all data sets on current page and verifying length
        # create containers group
        containers = soup.findAll("div", {"class": "sg-col-4-of-12 s-result-item s-asin sg-col-4-of-16 sg-col s-widget-spacing-small sg-col-4-of-20"})
        # use below line to check the length of the dataset
        """ print(len(containers)) """
        # containers
        #filename = "{}_Catalogue.csv".format(url).replace("/",",").replace("?",",")
        #f = open(filename, "w", encoding="utf-8")

        #headers = "Image_Url, Item_Name, Item_Price, Average_Rating, Number_Review\n"

        # f.write(headers)

        # loop inside each container
        for container in containers:
                Product_Url_Container = container.findAll("a", {"class": "a-link-normal s-no-outline"})
                Product_Url = "https://www.amazon.sg" + str(Product_Url_Container[0]["href"])
                Product_Url_Cleaned = splitString(Product_Url)
                """ print(Product_Url_Cleaned) """

                """ Image_Url_Container = container.findAll("div", {"class": "a-section aok-relative s-image-square-aspect"})
                Image_Url = Image_Url_Container[0].img["src"] """

                Image_Url_Container = container.findAll("div", {"class": "s-product-image-container aok-relative s-image-overlay-grey s-text-center s-padding-left-small s-padding-right-small s-spacing-small s-height-equalized"})
                Image_Url = Image_Url_Container[0].span.a.div.img["src"]
                

                Item_Name_Container = container.findAll("a", {"class": "a-link-normal s-underline-text s-underline-link-text s-link-style a-text-normal"})
                Item_Name = Item_Name_Container[0].span.text

                try:
                    Item_Price_Container = container.findAll("span", {"class": "a-offscreen"})
                    Item_Price = Item_Price_Container[0].text[2:]

                    Average_Rating_Container = container.findAll("span", {"class": "a-icon-alt"})
                    Average_Rating = Average_Rating_Container[0].text[0:4]

                    Number_Of_Ratings_Container = container.findAll("span", {"class": "a-size-base s-underline-text"})
                    Number_Of_Ratings = Number_Of_Ratings_Container[0].text

                except:
                    Item_Price = "NA"
                    Average_Rating = "NA"
                    Number_Of_Ratings = "NA"


                """ print("product_url: " + Product_Url_Cleaned)
                print("image_url: " + Image_Url)
                print("item_name: " + Item_Name)
                print("item_price: " + Item_Price)
                print("average_rating: " + Average_Rating)
                print("number_of_ratings: " + Number_Of_Ratings) """
                
                try:
                    connection = pymysql.connect(host="remotemysql.com", user="y0vryqAKXK", passwd="moMOpaacUP", database="y0vryqAKXK")
                    cursor = connection.cursor()
                    sql = "INSERT INTO cataloguedata (product_url, image_url, item_name, item_price, average_rating, number_of_ratings, createdby, search_term) VALUES (%s,%s,%s,%s,%s,%s,%s,%s)"
                    data = (Product_Url_Cleaned, Image_Url, Item_Name, Item_Price, Average_Rating, Number_Of_Ratings, createdby, search_term)
                    cursor.execute(sql, data)
                    """ print("Record inserted #", i) """
                    recordsinserted += 1
                    connection.commit()
                    """ To parse through the next few records other than the duplicates (However heroku will crash due to too many requests)
                    i += 1 """

                except pymysql.Error as err:
                    duplicatecount += 1
                    
                    """ print("This item has already been scraped, please choose a different search term") """
                    """ print("Something went wrong: {}".format(err)) """

                    """ connection = pymysql.connect(host="remotemysql.com", user="y0vryqAKXK", passwd="moMOpaacUP", database="y0vryqAKXK")
                    cursor = connection.cursor()
                    sql = "UPDATE cataloguedata SET item_price = ?, average_rating = ?, number_of_ratings = ? WHERE product_url = ?"
                    data = (Item_Price, Average_Rating, Number_Of_Ratings, Product_Url_Cleaned)
                    cursor.execute(sql, data)
                    print("Record updated #", i)
                    connection.commit() """
                
                i += 1

                if i == 21:
                    break
        
        # parse the next url
        url = getnextpage(soup)
        if not url:
            connection.close()
            """ print("MySQL connection is closed") """
            break
        
        if i == 21:
            print("(" , recordsinserted , ") Records Inserted")
            print("(" , duplicatecount , ") Duplicate Records Found")
            print("Scraping completed")
            """ print("20 Records have been added successfully to the database, closing the script") """
            break
        
        i += 1

exit()
