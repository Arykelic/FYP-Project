from requests_html import HTMLSession
from bs4 import BeautifulSoup
import re
import os
s = HTMLSession()

print('Enter the search term to be scraped (delimit search terms with a "+")')
search_term = input('>')
print(f'Filtering out {search_term}')
#url = "https://www.amazon.com/s?k={}".format(search_term)
url = "https://www.amazon.com/s?k=samsung+smartphones&ref=nb_sb_noss"

#Download the webpage
def getdata(url):
    r = s.get(url)
    soup = BeautifulSoup(r.text, "html.parser")
    return soup

#Parse next pagination
def getnextpage(soup):
    try:
        page = soup.find("span", {"class":"s-pagination-strip"})
        #if not last page
        if not page.find("span", {"class":"s-pagination-item s-pagination-next s-pagination-disabled"}):
            url = "https://www.amazon.com" + str(page.find("a",{"class":"s-pagination-item s-pagination-next s-pagination-button s-pagination-separator"})["href"])
            return url
        else:
            return
    except TypeError:
        return
    except AttributeError:
        return

#conversion of data into CSV

#Change Directory
os.chdir('AmazonUS Catalogue Files')

filename = "{}_Catalogue.csv".format(search_term)
f = open(filename, "w", encoding="utf-8")

headers = "Image_Url, Item_Name, Item_Price, Average_Rating (Max Score is 5), Number_Of_Ratings \n"

f.write(headers)
    
while True:
    soup = getdata(url)
    print(soup)
    #pulling all data sets on current page and verifying length
    #create containers group
    containers = soup.findAll("div",{"class":"s-result-item s-asin sg-col-0-of-12 sg-col-16-of-20 sg-col s-widget-spacing-small sg-col-12-of-16"})
    #use below line to check the length of the dataset
    print(len(containers))
    #containers
    #filename = "{}_Catalogue.csv".format(url).replace("/",",").replace("?",",")
    #f = open(filename, "w", encoding="utf-8")

    #headers = "Image_Url, Item_Name, Item_Price, Average_Rating, Number_Review\n"

    #f.write(headers)

    #loop inside each container
    for container in containers:

            Image_Url_Container = container.findAll("div", {"class":"a-section aok-relative s-image-fixed-height"})
            Image_Url = Image_Url_Container[0].img["src"]

            Item_Name_Container = container.findAll("span", {"class":"a-size-medium a-color-base a-text-normal"})
            Item_Name = Item_Name_Container[0].text

            try:
                Item_Price_Container = container.findAll("span", {"class":"a-offscreen"})
                Item_Price = Item_Price_Container[0].text
                
                Average_Rating_Container = container.findAll("span", {"class":"a-icon-alt"})
                Average_Rating = Average_Rating_Container[0].text[0:4]

                Number_Of_Ratings_Container = container.findAll("span", {"class":"a-size-base s-underline-text"})
                Number_Of_Ratings = Number_Of_Ratings_Container[0].text

            except:
                Item_Price = ""
                Average_Rating = ""
                Number_Of_Ratings = ""

            print("Image Url: " + Image_Url)
            print("Item_Name: " + Item_Name)
            print("Item_Price: " + Item_Price)
            print("Average_Rating: " + Average_Rating)
            print("Number_Review: " + Number_Of_Ratings)
            
            f.write(Image_Url.replace(",", "|") + "," + Item_Name.replace(",", "|") + "," + Item_Price.replace(",", "'") + "," + Average_Rating.replace(",", "'") + "," + Number_Of_Ratings.replace(",", "'") + "\n")

    #parse the next url    
    url = getnextpage(soup)
    if not url:
        f.close()
        print("End of CSV Writing")
        break
