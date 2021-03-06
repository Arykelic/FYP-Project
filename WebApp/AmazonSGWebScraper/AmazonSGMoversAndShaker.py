from requests_html import HTMLSession
from bs4 import BeautifulSoup
import sys
import re
import os
from debugpy import connect
import pymysql

s = HTMLSession()

""" print('Enter the search term to be scraped (delimit search terms with a "+")') """
""" search_term = sys.argv[1] """
""" print(f'Filtering out {search_term}') """
url = "https://www.amazon.sg/gp/movers-and-shakers"
createdby = sys.argv[1]

# Download the webpage

def getdata(url):
    r = s.get(url)
    soup = BeautifulSoup(r.text, "html.parser")
    return soup

# Parse next pagination

def beforeQuestionMark(inputStr):
    return inputStr.split("?")[0]

i = 1

while True:
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
                Product_Url_Cleaned = beforeQuestionMark(Product_Url)
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
                    connection = pymysql.connect(host="remotemysql.com", user="y0vryqAKXK", passwd="ovYvXY4sFJ", database="y0vryqAKXK")
                    cursor = connection.cursor()
                    sql = "INSERT INTO cataloguedata (product_url, image_url, item_name, item_price, average_rating, number_of_ratings, createdby, search_term) VALUES (%s,%s,%s,%s,%s,%s,%s,%s)"
                    data = (Product_Url_Cleaned, Image_Url, Item_Name, Item_Price, Average_Rating, Number_Of_Ratings, createdby)
                    cursor.execute(sql, data)
                    print("Record inserted #", i)
                    connection.commit()
                    """ To parse through the next few records other than the duplicates (However heroku will crash due to too many requests)
                    i += 1 """

                except pymysql.Error as err:
                    print("Something went wrong: {}".format(err))
                
                i += 1

                if i == 21:
                    break
        
        # parse the next url
        
        
        if i == 21:
            print("Script has ended")
            """ print("20 Records have been added successfully to the database, closing the script") """
            break
        
        i += 1

exit()
