from urllib.request import urlopen as uReq
from bs4 import BeautifulSoup as soup

my_url = "https://www.amazon.sg/Samsung-Factory-Unlocked-Smartphone-Pro-Grade/product-reviews/B08FYTSXGQ/ref=cm_cr_getr_d_paging_btm_prev_1?ie=UTF8&reviewerType=all_reviews&pageNumber=1"
my_url

# opening connection to url and grabbing page

uClient = uReq(my_url)
page_html = uClient.read()
uClient.close()

# html parsing
page_soup = soup(page_html, "html.parser")

# use below line to check the html set
# page_soup.h1

# pulling all data sets on current page and verifying length
containers = page_soup.findAll("div", {"class": "a-section review aok-relative"})
# use below line to check the length of the dataset
# len(containers)

search_term_value = page_soup.find("h1", {"class": "a-size-large a-text-ellipsis"}).text
search_term_stripped = search_term_value.strip()
search_term = search_term_stripped.replace('"', ",").replace("|",",")
search_term

filename = "{}_Page.csv".format(search_term)
f = open(filename, "w")

headers = "Image_Url, Item_Name, Username, Rating_Score, Review_Description, Review_Date \n"

f.write(headers)


# loop
for container in containers:
    Image_Url = page_soup.find("div", {"class": "a-text-center a-spacing-top-micro a-fixed-left-grid-col product-image a-col-left"}).img["src"]

    Item_Name_Value = page_soup.find("h1", {"class": "a-size-large a-text-ellipsis"}).text
    Item_Name = Item_Name_Value.strip()

    Username_Container = container.findAll("span", {"class": "a-profile-name"})
    Username = Username_Container[0].text

    Rating_Score_Container = container.findAll("span", {"class": "a-icon-alt"})
    Rating_Score = Rating_Score_Container[0].text[0:4]

    Review_Description_Container = container.findAll("div", {"class": "a-row a-spacing-small review-data"})
    Review_Description_Value = Review_Description_Container[0].text
    Review_Description = Review_Description_Value.strip()

    Review_Date_Container = container.findAll("span", {"class": "a-size-base a-color-secondary review-date"})
    Review_Date = Review_Date_Container[0].text


    print("Image Url: " + Image_Url)
    print("Item_Name: " + Item_Name)
    print("Username: " + Username)
    print("Rating_Score: " + Rating_Score)
    print("Review_Description: " + Review_Description)
    print("Review_Date: " + Review_Date)

    f.write(Image_Url.replace(",", "|") + "," + Item_Name.replace(",", "|") + "," + Username.replace(",", ".") 
    + "," + Rating_Score.replace(",", ".") + "," + Review_Description.replace(",", "'").replace("\U0001f60a",":)") + "," + Review_Date.replace(",", "'") + "\n")

f.close()