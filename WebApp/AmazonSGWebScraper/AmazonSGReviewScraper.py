from requests_html import HTMLSession
from bs4 import BeautifulSoup
import re
import os
import sys
import pymysql

s = HTMLSession()

#my_url = "https://www.amazon.sg/Samsung-Factory-Unlocked-Smartphone-Pro-Grade/dp/B08FYTSXGQ/ref=sr_1_48?crid=21O3WZX42E419&keywords=samsung+smartphones&qid=1647967669&sprefix=samsung+smartphones%2Caps%2C270&sr=8-48"
""" print('Enter the url link to be scraped') """
url = sys.argv[1]
print(f'Filtering out {url}')
createdby = sys.argv[2]

def getdata(url):
    r = s.get(url)
    soup = BeautifulSoup(r.text, "html.parser")
    return soup

def getnextpage(soup):
    try:
        page = soup.find("ul", {"class":"a-pagination"})
        if not page.find("li", {"class":"a-disabled a-last"}):
            url = "https://www.amazon.sg" + str(page.find("li",{"class":"a-last"}).find("a")["href"])
            return url
        else:
            return
    except TypeError:
        return
    except AttributeError:
        return

soup = getdata(url)
search_term_value = soup.find("h1", {"class":"a-size-large a-text-ellipsis"}).text
search_term = search_term_value.strip().replace('"', ",").replace("|",",").replace("/",",").replace("-",",")

#Change Directory
""" os.chdir('AmazonSGReviewFiles')

filename = "{}+Reviews.csv".format(search_term)
f = open(filename, "w", encoding="utf-8")
#headers = "Image_Url, Item_Name, Username, Rating_Score (Max Score is 5), Review_Description, Review_Date \n"
headers = "image_url, item_name, customername, rating_score (Max Score is 5), review_location, review_date \n"

f.write(headers) """

i = 1
recordsinserted = 0
duplicatecount = 0

""" while True: """
while i <= 22:
        soup = getdata(url)
        #pulling all data sets on current page and verifying length
        containers = soup.findAll("div", {"class": "a-section review aok-relative"})
        #use below line to check the length of the dataset
        #len(containers)
        #containers
        #filename = "{}_Catalogue.csv".format(url).replace("/",",").replace("?",",")
        #f = open(filename, "w", encoding="utf-8")

        #headers = "Image_Url, Item_Name, Item_Price, Average_Rating, Number_Review\n"

        #f.write(headers)

        #loop
        for container in containers:
                Image_Url = soup.find("div", {"class": "a-text-center a-spacing-top-micro a-fixed-left-grid-col product-image a-col-left"}).img["src"]

                Item_Name_Value = soup.find("h1", {"class": "a-size-large a-text-ellipsis"}).text
                Item_Name = Item_Name_Value.strip()

                Username_Container = container.findAll("span", {"class": "a-profile-name"})
                Username = Username_Container[0].text

                Rating_Score_Container = container.findAll("span", {"class": "a-icon-alt"})
                Rating_Score = Rating_Score_Container[0].text[0:4]

                #Review_Description_Container = container.findAll("div", {"class": "a-row a-spacing-small review-data"})
                #Review_Description_Value = Review_Description_Container[0].text
                #Review_Description = Review_Description_Value.strip()

                Review_Location_Container = container.findAll("span", {"class": "a-size-base a-color-secondary review-date"})[0].text[12:]
                Review_Location = re.search("^(.*)on",Review_Location_Container)
                Review_Location_Formatted = Review_Location.group()[:-2]

                Review_Date_Container = container.findAll("span", {"class": "a-size-base a-color-secondary review-date"})[0].text[12:]
                Review_Date = re.search("on(.*)",Review_Date_Container)
                Review_Date_Formatted = Review_Date.group()[3:]

                """ print("Image Url: " + Image_Url)
                print("Item_Name: " + Item_Name)
                print("Username: " + Username)
                print("Rating_Score: " + Rating_Score)
                #print("Review_Description: " + Review_Description)
                print("Review_Location: " + Review_Location_Formatted)
                print("Review_Date: " + Review_Date_Formatted) """

                #f.write(Image_Url.replace(",", "|") + "," + Item_Name.replace(",", "|") + "," + Username.replace(",", ".") 
                #+ "," + Rating_Score.replace(",", ".") + "," + Review_Description.replace(",", "'").replace("\U0001f60a",":)") + "," + Review_Date.replace(",", "'") + "\n")

                """ f.write(Image_Url.replace(",", "|") + "," + Item_Name.replace(",", "|") + "," + Username.replace(",", ".") 
                + "," + Rating_Score.replace(",", ".") + "," + Review_Location_Formatted.replace(",", "|") + "," + Review_Date_Formatted.replace(",", "'") + "\n") """

                try:
                    connection = pymysql.connect(host="remotemysql.com", user="y0vryqAKXK", passwd="ovYvXY4sFJ", database="y0vryqAKXK")
                    cursor = connection.cursor()
                    sql = "INSERT INTO combinedreview (image_url, item_name, customername, rating_score, review_location, review_date, createdby) VALUES (%s,%s,%s,%s,%s,%s,%s)"
                    data = (Image_Url, Item_Name, Username, Rating_Score, Review_Location_Formatted, Review_Date_Formatted, createdby)
                    cursor.execute(sql, data)
                    """ print("Record inserted #", i) """
                    recordsinserted += 1
                    connection.commit()
                    """ To parse through the next few records other than the duplicates (However heroku will crash due to too many requests)
                    i += 1 """
                    
                except pymysql.Error as err:
                    """ print("Something went wrong: {}".format(err)) """
                    duplicatecount += 1
                    """ print("(" , duplicatecount , ") duplicate records found") """
                    """ print("This item has already been scraped, please choose a different product page review") """

                i += 1

                if i == 22:
                    break

        # parse the next url
        url = getnextpage(soup)
        if not url:
            """ f.close() """
            connection.close()
            """ print("MySQL connection is closed") """
            """ print("End of CSV Writing") """
            break

        if i == 22:
            print("(",recordsinserted,") Records Inserted")
            print("(",duplicatecount,") Duplicate Records Found")
            print("Scraping completed")
            """ print("20 Reviews have been added successfully to the database, closing the script") """
            break

        i += 1

exit()