from urllib.request import urlopen as uReq
from bs4 import BeautifulSoup as soup

search_term = "samsung+smartphones"
my_url = "https://www.amazon.sg/s?k={}".format(search_term)
my_url

#opening connection to url and grabbing page
uClient = uReq(my_url)
page_html = uClient.read()
uClient.close()

#html parsing
page_soup = soup(page_html, "html.parser")

#use below line to check the html set
#page_soup.h1

#pulling all data sets on current page and verifying length
containers = page_soup.findAll("div",{"class":"sg-col-4-of-12 s-result-item s-asin sg-col-4-of-16 sg-col s-widget-spacing-small sg-col-4-of-20"})
#use below line to check the length of the dataset
#len(containers)
#containers

filename = "{}_Catalogue.csv".format(search_term)
f = open(filename, "w")

headers = "Image_Url, Item_Name, Item_Price, Average_Rating, Number_Review\n"

f.write(headers)

#loop
for container in containers:
        Image_Url_Container = container.findAll("div", {"class":"a-section aok-relative s-image-square-aspect"})
        Image_Url = Image_Url_Container[0].img["src"]

        Item_Name_Container = container.findAll("span", {"class":"a-size-base-plus a-color-base a-text-normal"})
        Item_Name = Item_Name_Container[0].text

        try:
            Item_Price_Container = container.findAll("span", {"class":"a-offscreen"})
            Item_Price = Item_Price_Container[0].text
        
            Average_Rating_Container = container.findAll("span", {"class":"a-icon-alt"})
            Average_Rating = Average_Rating_Container[0].text[0:4]

            Number_Review_Container = container.findAll("span", {"class":"a-size-base s-underline-text"})
            Number_Review = Number_Review_Container[0].text

        except:
            Average_Rating = ""
            Number_Review = ""

        print("Image Url: " + Image_Url)
        print("Item_Name: " + Item_Name)
        print("Item_Price: " + Item_Price)
        print("Average_Rating: " + Average_Rating)
        print("Number_Review: " + Number_Review)
    
        f.write(Image_Url.replace(",", "|") + "," + Item_Name.replace(",", "|") + "," + Item_Price.replace(",", "'") + "," + Average_Rating.replace(",", "'") + "," + Number_Review.replace(",", "'") + "\n")

f.close()