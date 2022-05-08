from requests_html import HTMLSession
from bs4 import BeautifulSoup
import re
import os
import sys
from debugpy import connect
import pymysql




s = HTMLSession()

#my_url = "https://www.amazon.sg/Samsung-Factory-Unlocked-Smartphone-Pro-Grade/dp/B08FYTSXGQ/ref=sr_1_48?crid=21O3WZX42E419&keywords=samsung+smartphones&qid=1647967669&sprefix=samsung+smartphones%2Caps%2C270&sr=8-48"
""" print('Enter the url link to be scraped') """
my_url = sys.argv[1]
print(f'Filtering out {my_url}')
createdby = sys.argv[2]

# opening connection to url and grabbing page


# html parsing
def getdata(my_url):
    r = s.get(my_url)
    soup = BeautifulSoup(r.text, "html.parser")
    return soup

def beforeQuestionMark(inputStr):
    return inputStr.split("?")[0]


# use below line to check the html set
# page_soup.h1
soup = getdata(my_url)
# pulling all data sets on current page and verifying length
# use below line to check the length of the dataset
# len(containers)

""" search_term_value = soup.find("h1", {"class": "a-size-large a-spacing-none"}).text """
search_term_value = soup.find("span", {"id": "productTitle"}).text
search_term_stripped = search_term_value.strip()


# Change Directory
""" os.chdir('AmazonSGPageFiles')

filename = "{}+Page.csv".format(search_term)
f = open(filename, "w")

headers = "review_url, image_url, item_name, item_price, average_rating (Max Score is 5), number_of_ratings \n"

f.write(headers) """

Review_Url = "https://www.amazon.sg" + str(soup.find("a", {"class": "a-link-emphasis a-text-bold"})["href"])
Review_Url_Cleaned = beforeQuestionMark(Review_Url)
Image_Url = soup.find("div", {"class": "imgTagWrapper"}).img["src"]

try:
    """ Item_Price_Container = soup.find("span", {"class":"a-price a-text-price"})
    print(Item_Price_Container) """
    """ Item_Price = soup.find("span", {"class": "a-offscreen"}).text[2:] """
    Average_Rating = soup.find("span", {"class": "a-icon-alt"}).text[0:4]
    Number_Of_Ratings = soup.find("span", {"id": "acrCustomerReviewText"}).text[0:-7]
    #similar items
    allSimilarItemsArray = []
    Similar_Items = soup.findAll("span", {"class": "_p13n-desktop-sims-fbt_fbt-desktop_title-truncate__1pPAM"})
    for items in Similar_Items[1:]:
        name = items.find(class_="_p13n-desktop-sims-fbt_fbt-desktop_title-truncate__1pPAM")
        text = ''.join(items.text.strip())
        allSimilarItemsArray.append(text)
    s = ' ||AND|| '
    allSimilarItemsString =s.join(allSimilarItemsArray)

    Item_Brand_Container = soup.find("tr", {"class": "a-spacing-small po-brand"})
    Item_Brand = Item_Brand_Container.find("td", {"class":"a-span9"}).text

except:
    """ Item_Price = "NA" """
    Average_Rating = "NA"
    Number_Of_Ratings = "NA"
    allSimilarItemsString = "NA"
    Item_Brand = "NA"

""" print("review_url: " + Review_Url_Cleaned)
print("image_url: " + Image_Url)
print("item_name: " + search_term_value)
print("item_price: " + Item_Price)
print("average_rating: " + Average_Rating)
print("number_of_ratings: " + Number_Of_Ratings)
print("similar_items: " + allSimilarItemsString)
print("item_brand: " + Item_Brand) """


""" f.write(Review_Url.replace(",", "|") + "," + Image_Url.replace(",", "|") + "," + search_term.replace(",", "|") + "," +
 Item_Price.replace(",", "'") + "," + Average_Rating.replace(",", ".") + "," + Number_Of_Ratings.replace(",", ".")  + "\n") """

try:
    connection = pymysql.connect(host="remotemysql.com", user="y0vryqAKXK", passwd="moMOpaacUP", database="y0vryqAKXK")
    cursor = connection.cursor()
    sql = "INSERT INTO pagedata (review_url, image_url, item_name, average_rating, number_of_ratings, similar_items, item_brand, createdby) VALUES (%s,%s,%s,%s,%s,%s,%s,%s)"
    data = (Review_Url_Cleaned, Image_Url, search_term_value, Average_Rating, Number_Of_Ratings, allSimilarItemsString, Item_Brand, createdby)
    cursor.execute(sql, data)
    print("Record inserted")
    connection.commit()
    print("Script has ran successfully")
except pymysql.Error as err:
    print("Something went wrong: {}".format(err))
""" f.close()
print("End of CSV Writing") """

connection.close()
""" print("MySQL connection is closed") """
exit()

