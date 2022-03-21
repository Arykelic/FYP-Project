from bs4 import BeautifulSoup as soup
from urllib.request import urlopen as uReq
import pandas as pd

my_url = "https://www.lazada.sg/shop-mobiles/?spm=a2o42.searchlistcategory.cate_1.1.34293f0327QcL2"

client = uReq(my_url)
client