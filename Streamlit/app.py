import streamlit as st
import numpy as np
import pandas as pd
import json
from PIL import Image


from surprise import Reader, Dataset
from surprise import SVD
import plotly.express as px
import matplotlib.pyplot as plt
import plotly.graph_objects as go
from plotly.subplots import make_subplots
from streamlit_lottie import st_lottie

#Hide streamlit style
hide_st_style = """
                <style>
                #MainMenu {visibility: hidden;}
                footer {visibility: hidden;}
                header {visibility: hidden;}
                </style>
                """
st.markdown(hide_st_style, unsafe_allow_html=True)

header = st.container()
option1 = st.container()
option2 = st.container()
option3 = st.container()
svdAlgo = st.container()
calculate = st.container()

#st.markdown("""<style>.main{background-color: #F5F5F5;}</style>""",unsafe_allow_html=True)




@st.cache
def get_data(filename):
    ratings  = pd.read_csv(filename)
    return ratings

def load_lottiefile(filepath: str):
    with open(filepath, 'r') as f:
        return json.load(f)

ratings = get_data('merged.csv')    
item_list = ratings.iloc[:, 1].unique().tolist() 
user_list = ratings.iloc[:, 2].unique().tolist()
rs_list = ratings.iloc[:, 3].tolist()




with header:
    #img1 = Image.open('system.png')
    #img1 = img1.resize((300,250),) #length, width
    #st.image(img1,use_column_width=False)

    lottie_img = load_lottiefile('lottie/system.json')
    st_lottie(lottie_img, height=300, width=300, key='logo')
    st.title("Rating Recommender System")
    intro = st.markdown('''<h5 style='text-align: left; color:red;'>The Data is based on Amazon webpage</h5>''',unsafe_allow_html=True)
    st.write('')
    st.write('')
    col1, col2, col3 = st.columns(3)
    col1.metric("Total No. of Users",len(user_list))
    col2.metric("Total No. of Items", len(item_list))
    col3.metric("Total No. of Rated Scores", len(rs_list))

    
        

    
with option1:

    askcategory = st.sidebar.radio("Do you want to see the overall graph plots?" + '📊',('Yes', 'No'), index=1)
     
    if askcategory == 'Yes':
        intro.empty()

        useryes = st.sidebar.checkbox('Users')
        itemyes = st.sidebar.checkbox('Items')
        ratingyes = st.sidebar.checkbox('Rating Scores')
       
     
        if useryes:

            #top_n = st.sidebar.slider("How many users do u want to see?", 0,999,10)
            top_n = st.sidebar.number_input("How many users do u want to see?",10)
           
            usercrosstab = pd.crosstab(ratings['Username'], ratings['Rating_Score']).head(top_n)
            fig = px.bar(usercrosstab, title='Number of each Ratings per User')
            fig.update_layout(xaxis=dict(tickmode='linear'), plot_bgcolor='rgba(0,0,0,0)', yaxis=(dict(showgrid=False)),)
            st.write(fig)

        if itemyes:
            
            top_n = st.sidebar.number_input("How many items do u want to see?",1)
            itemcolumns2 = ratings['Item_Name'].str.split('|').str[0]
            itemcolumns3 = itemcolumns2.str.split('Mobile').str[0]

            itemcrosstab = pd.crosstab(itemcolumns3, ratings['Rating_Score']).head(top_n)
            fig2 = px.bar(itemcrosstab, title='Number of each Ratings per Item', barmode='group')
            fig2.update_layout(xaxis=dict(tickmode='linear'), plot_bgcolor='rgba(0,0,0,0)', yaxis=(dict(showgrid=False)),)
         
            st.write(fig2)
        
    
        if ratingyes:
            #st.subheader('Number of each Rating Scores')
            ratingcounts = ratings['Rating_Score'].value_counts()
            fig3 = px.bar(ratingcounts, title='Number of each Rating Scores')
            fig3.update_layout(xaxis=dict(tickmode='linear'), plot_bgcolor='rgba(0,0,0,0)', yaxis=(dict(showgrid=False)),)
            st.write(fig3)
            
            
            
with option2:
   
    checkitem = st.sidebar.selectbox('Select item' +"💼",['--Select--']+item_list)
    
    if checkitem == '--Select--':
        st.sidebar.info('You can view the item information here.')
    
    else:
        intro.empty()
        st.markdown('''<h4 style='text-align: left; color:green;'>Displaying the Item's Statistics</h4>''',
                    unsafe_allow_html=True)
    
        
        
        x = ratings[ratings['Item_Name'] == checkitem]

        itemdetails = x['Rating_Score'].value_counts()
        
        st.write(checkitem)

        fig2 = px.bar(itemdetails, title='Number of each Ratings based on Item')
        
        fig2.update_layout(xaxis=dict(tickmode='linear'), plot_bgcolor='rgba(0,0,0,0)', yaxis=(dict(showgrid=False)),)
        st.write(fig2)
      
        fig3 = px.pie(x, values=itemdetails, names=itemdetails.index, hole=.3, title='Number of each Ratings based on Item')
        st.write(fig3)
        
        #set plots side by side
        leftcol, rightcol = st.columns(2)
        
        leftcol.plotly_chart(fig2, use_container_width=True)
        rightcol.plotly_chart(fig3, use_container_width=True)
            
with option3:

    checkuser = st.sidebar.selectbox('Select username' + '👨‍👨‍👧‍👧',['--Select--']+user_list)
    
    if checkuser == '--Select--':
        st.sidebar.info('You can view the user information here.')
    
    else:
        intro.empty()
        st.markdown('''<h4 style='text-align: left; color:green;'>Displaying the User's Statistics</h4>''',
                    unsafe_allow_html=True)
    
        dropimage = ratings.drop(columns=['Image_Url','Review_Description'])
        
        x = dropimage[dropimage['Username'] == checkuser]
        with st.expander('Click here for more information'):
        
            st.write(x)
        
        userdetails = x['Rating_Score'].value_counts()
        
        # Create subplots: use 'domain' type for Pie/bar subplot
        #fig = make_subplots(rows=1, cols=2, specs=[[{'type':'domain'}, {'type':'domain'}]])
        
        #fig.add_trace(go.Pie(labels=itemdetails.index, values=itemdetails, name="pie chart"),
        #      1, 1)
        #fig.add_trace(go.Pie(labels=userdetails.index, values=userdetails, name="bar chart"),
        #      1, 2)
        #st.write(fig)
        
        st.write('**Customer name**: '+checkuser)
        
        st.write('**Total no. of items rated:** ',(sum(userdetails)))
        fig2 = px.bar(userdetails, title='Number of each Ratings based on User')
        fig2.update_layout(xaxis=dict(tickmode='linear'), plot_bgcolor='rgba(0,0,0,0)', yaxis=(dict(showgrid=False)),)
        st.write(fig2)

      
        fig3 = px.pie(x, values=userdetails, names=userdetails.index, hole=.3, title='Number of each Ratings based on User')
        st.write(fig3)
 
 
with svdAlgo:

    #Define a Reader object
    #The Reader object helps in parsing the file or dataframe containing ratings
    ratings = ratings.drop(columns=['Image_Url','Review_Description','Review_Date '])

    reader = Reader()
    #dataset creation
    data = Dataset.load_from_df(ratings, reader)
    #model
    svd = SVD()

    trainset = data.build_full_trainset()
    svd.fit(trainset)
    

with calculate:

    user_list = ratings.iloc[:, 1].unique().tolist()
    item_list = ratings.iloc[:, 0].unique().tolist()

    
    usersopt = st.sidebar.selectbox('Predict User Rating Score' + '⭐', ['--Select--']+user_list)

    if usersopt == '--Select--':
        st.sidebar.warning('Please type a username')
        
   
    elif usersopt != '--Select--':
        intro.empty()
        itemsopt = st.sidebar.selectbox('Select item name' + "💼", ['--Select--']+item_list)
    
        if itemsopt == '--Select--':
            st.sidebar.warning('Please select an item')
        
        elif itemsopt == item_list[0]:
            st.sidebar.image("https://m.media-amazon.com/images/I/41UuyU7HsPL._AC_US60_SCLZZZZZZZ__.jpg", width = 150)
            calculate_button = st.sidebar.button('Calculate', on_click=None)
            if calculate_button == True:
                st.subheader('Estimated rating is :')  
                result = st.success(svd.predict(itemsopt, usersopt).est)
               
                
        elif itemsopt == item_list[1]:
            st.sidebar.image("https://m.media-amazon.com/images/I/419sppTfYEL._AC_US60_SCLZZZZZZZ__.jpg", width = 150)
            calculate_button = st.sidebar.button('Calculate', on_click=None)
            if calculate_button == True:
                st.subheader('Estimated Rating Score is:')  
                
                result = st.success(svd.predict(itemsopt, usersopt).est)
                
               