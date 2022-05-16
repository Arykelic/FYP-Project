from __future__ import print_function, division

import os

#os.chdir('')

import re
from time import time, sleep

from bs4 import BeautifulSoup  # Web scrapping
import urllib

#from urllib2 import urlopen  # Download link
from urllib.request import urlopen

#from urlparse import urlparse  # Parse URL
import urllib.parse

import json

from datetime import datetime  # Parse date time
from selenium import webdriver
from webdriver_manager.chrome import ChromeDriverManager

import firebase_admin
from firebase_admin import credentials, firestore, initialize_app


# initialize first these variables,
PATH_FIREBASE_AUTHENTICATION_FILE = os.path.dirname(os.path.realpath("databasedesign-344009-firebase-adminsdk-p4elo-598fc3afc3.json"))
PATH_CHROME_WEB_DRIVER_EXECUTABLE = "chromedriver.exe"



# scrapes the product page
def scrape_product(product_url):

    driver = webdriver.Chrome(PATH_CHROME_WEB_DRIVER_EXECUTABLE)
    driver.get(product_url)
    soup = BeautifulSoup(driver.page_source, "html.parser")

    product = {}
    '''
#property keys to scrape
item_id
category
name
brand_name
url
price
average_rating
total_reviews
datePublished
num_rating_5
num_rating_4
num_rating_3
num_rating_2
num_rating_1
    '''

    product_temp_block = soup.find('input', attrs={'name': 'buyParams'})
    json_temp_object = json.loads(product_temp_block.get('value'))
    product['item_id'] = json_temp_object['items'][0]['itemId']

    product_temp_block = soup.find('h1', attrs={'class': 'pdp-mod-product-badge-title'})
    product['name'] = product_temp_block.text.strip()

    product_temp_block = soup.find('script', attrs={'type': 'application/ld+json'})
    json_temp_object = json.loads(product_temp_block.text.strip())
    product['category'] = json_temp_object["category"]

    product['brand_name'] = json_temp_object["brand"]['name']
    product['url'] = json_temp_object["url"]
    product['price'] = json_temp_object["offers"]['lowPrice']
    product['average_rating'] = json_temp_object["aggregateRating"]['ratingValue']
    product['total_reviews'] = json_temp_object["aggregateRating"]['ratingCount']
    product['retrieved_date'] = json.dumps(datetime.now().isoformat())

    driver.quit()

    return product


# initialiaze our firebase access,
credentials = credentials.Certificate(PATH_FIREBASE_AUTHENTICATION_FILE)
default_app = initialize_app(credentials)

db = firestore.client()

# make sure we have a product document in our firestore database.
products_db = db.collection('products')

product_urls = [
    'https://www.lazada.sg/products/poco-f3-5g-6gb128gb8gb256gb-global-version1-year-warranty-i1698075609-s8237438055.html',
    'https://www.lazada.sg/products/oneplus-nord-2-5g-smartphone-global-version-8gb-128gb12gb-256gb-50mp-ai-camera-ois-mtk-dimensity-1200-ai-warp-charge-65-i1953910226-s10483471247.html',
    'https://www.lazada.sg/products/samsuny-galaxy-s21ultra-5g-original-smartphone-16gb-ram-512gb-rom-6800mah-71-inch-hd-full-screen-equipped-with-10core-processor-2448mp-high-pixel-camera-gaming-smartphone-i1981399298-s10709508661.html',
    'https://www.lazada.sg/products/2022-new-black-shark-4proxiaomi-black-shark-4pro-original-global-version-black-shark-4-pro-gaming-mobile-phone-5g-120w-charge-snapdragon-888-pop-up-magnetic-shutters-144hz-nfc-i2228652322-s12803584030.html?spm=a2o42.searchlistcategory.list.8.23da3f03243bZ0&search=1&freeshipping=1',
    'https://www.lazada.sg/products/vivo-y21-2-years-warranty-4gb64gb-4gb1gb-extended-ram-5000mah-18w-fast-charge-80mm-trendy-slim-design-i1961300510-s10538474531.html?spm=a2o42.searchlistcategory.list.1.23da3f03243bZ0&search=1&freeshipping=1',
    'https://www.lazada.sg/products/poco-x3-gt-5g-128gb-256gb-8gb-ram-1-year-official-warranty-xiaomi-i2236121944-s12857858958.html?spm=a2o42.searchlistcategory.list.27.23da3f03243bZ0&search=1&freeshipping=1'
]

for index in range(len(product_urls)):
    product_temp = scrape_product(product_urls[index])

    # save to firebase db,
    # save this in a schema like , root reference is,
    # { products { product_id : { 'name' : 'test', ... }, ...{} } }
    # change reference to inside 'products' then we write the product_id as the key, then the value is the actual product
    products_db.document(product_temp['item_id']).set(product_temp)

