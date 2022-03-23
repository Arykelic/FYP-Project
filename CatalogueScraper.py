from requests_html import HTMLSession
from bs4 import BeautifulSoup

s = HTMLSession()
search_term = "samsung+smartphones"
url = "https://www.amazon.sg/s?k={}".format(search_term)

def getdata(url):
    r = s.get(url)
    soup = BeautifulSoup(r.text, "html.parser")
    return soup

def getnextpage(soup):
    page = soup.find("span", {"class":"s-pagination-strip"})
    if not page.find("span", {"class":"s-pagination-item s-pagination-next s-pagination-disabled "}):
        url = "https://www.amazon.sg" + str(page.find("a",{"class":"s-pagination-item s-pagination-next s-pagination-button s-pagination-separator"})["href"])
        return url
    else:
        return

filename = "{}_Catalogue.csv".format(search_term)
f = open(filename, "w", encoding="utf-8")

headers = "Image_Url, Item_Name, Item_Price, Average_Rating, Number_Review\n"

f.write(headers)
    
while True:
    try:
        soup = getdata(url)
        #pulling all data sets on current page and verifying length
        containers = soup.findAll("div",{"class":"sg-col-4-of-12 s-result-item s-asin sg-col-4-of-16 sg-col s-widget-spacing-small sg-col-4-of-20"})
        #use below line to check the length of the dataset
        #len(containers)
        #containers
        #filename = "{}_Catalogue.csv".format(url).replace("/",",").replace("?",",")
        #f = open(filename, "w", encoding="utf-8")

        #headers = "Image_Url, Item_Name, Item_Price, Average_Rating, Number_Review\n"

        #f.write(headers)

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
        
        url = getnextpage(soup)
    except TypeError:
        f.close()
        print("End of CSV Writing")
