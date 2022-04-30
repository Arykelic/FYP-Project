from debugpy import connect
import pymysql
import pandas as pd

empdata = pd.read_csv('C://xampp//htdocs//FYP-Project//AmazonSGCatalogueFiles//*.csv', index_col=False, delimiter = ',')
empdata[" item_price"].fillna("NA", inplace = True) 
empdata[" average_rating (Max Score is 5)"].fillna("NA", inplace = True) 
empdata[" number_of_ratings "].fillna("NA", inplace = True) 
empdata.head()

connection = pymysql.connect(host="remotemysql.com", user="y0vryqAKXK", passwd="moMOpaacUP", database="y0vryqAKXK")
cursor = connection.cursor()


for i,row in empdata.iterrows():
            #here %S means string values 
            sql = "INSERT INTO cataloguedata (product_url, image_url, item_name, item_price, average_rating, number_of_ratings) VALUES (%s,%s,%s,%s,%s,%s)"
            cursor.execute(sql, tuple(row))
            print("Record inserted")
            # the connection is not auto committed by default, so we must commit to save our changes
            connection.commit()

connection.close()
print("MySQL connection is closed")
