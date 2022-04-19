import sys
import subprocess
import streamlit as st
import numpy as np
import pandas as pd
import json

from PIL import Image
from surprise import Reader, Dataset
from surprise import SVD
import plotly.express as px

import plotly.graph_objects as go
from plotly.subplots import make_subplots
from streamlit_lottie import st_lottie

subprocess.run([f"{sys.executable}", "app.py"])

st.set_page_config(layout="wide")

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

ratings = get_data('merged2.csv')    
item_list = ratings.iloc[:, 1].unique().tolist() 
user_list = ratings.iloc[:, 2].unique().tolist()
rs_list = ratings.iloc[:, 3].tolist()
country_list = ratings.iloc[:, 4].unique().tolist()
date_list = ratings.iloc[:, 5].unique().tolist()



with header:
    #img1 = Image.open('system.png')
    #img1 = img1.resize((300,250),) #length, width
    #st.image(img1,use_column_width=False)
    
    lottie_img = load_lottiefile('lottie/system.json')
    
    
    col1, col2 = st.columns((1,4))
    with col1:
        st_lottie(lottie_img, height=300, width=300, key='logo')
        
    with col2:
        
        st.markdown('''<h1 style='position:relative;top:100px;text-align: left; color:white ;'>Rating Recommender System</h1>''',unsafe_allow_html=True)
        
    
    
    
    intro = st.markdown('''<h4 style='text-align: left; color: #ff2e00;'>The Data is based on Amazon webpage</h4>''',unsafe_allow_html=True)
    st.write('')
    st.write('')
    col1, col2, col3 = st.columns(3)
    col1.metric("Total No. of Customers",len(user_list))
    col2.metric("Total No. of Items", len(item_list))
    col3.metric("Total No. of Rated Scores", len(rs_list))
    
    
with option1:

    askcategory = st.sidebar.radio("Do you want to view the overall plots?" + '📊',('Yes', 'No'), index=0)
     
    if askcategory == 'Yes':
        #intro.empty()

        itemyes = st.sidebar.checkbox('Items')
        useryes = st.sidebar.checkbox('Customers')
        ratingyes = st.sidebar.checkbox('Rating Scores')
       
     
    

        if itemyes:
            
            #date_list = ratings.iloc[:, 5].unique().tolist()
            #top_n = st.sidebar.number_input("Please indicate the number of Items",min_value=1, max_value=len(item_list))
            top_n = st.sidebar.multiselect("Select Items",item_list,default=item_list[0])
            #dateopts = st.sidebar.date_input("Select Date")
            
            filteritem = ratings[ratings['Item_Name'].isin(top_n)]
            itemcolumns2 = filteritem['Item_Name'].str.split('|').str[0]
            itemcolumns3 = itemcolumns2.str.split('5G').str[0]
           
            
           
                #st.write(datetimeformat)
            #filtercol4 = filtercol3.dt.strftime('%Y-%m-%d')
            
          
            itemcrosstab = pd.crosstab(itemcolumns3, ratings['Rating_Score'])
            #itemcrosstab_text = itemcrosstab.iloc[:,0].tolist()
           
           
            fig2 = px.bar(itemcrosstab, text_auto=True,title='Number of each Ratings per Item', barmode='group')
            fig2.update_layout(xaxis=dict(tickmode='linear'),plot_bgcolor='rgba(0,0,0,0)', yaxis=(dict(showgrid=False)),)
            fig2.update_xaxes(title_text='Rating Score')
            fig2.update_yaxes(title_text='No. of Ratings')
         
            st.plotly_chart(fig2, use_container_width=True)
            
            
            
          
            
        if useryes:

            #top_n = st.sidebar.slider("How many users do u want to see?", 0,999,10)
            #top_n = st.sidebar.number_input("Please indicate the number of Customers",value=10,min_value=1, max_value=len(user_list))
            top_n = st.sidebar.multiselect("Select Customers",user_list,default='Amazon Customer')
            filteruser = ratings[ratings['Username'].isin(top_n)]
            usercrosstab = pd.crosstab(filteruser['Username'], ratings['Rating_Score'])
            
            fig = px.bar(usercrosstab,text_auto=True,title='Number of each Ratings per Customer', barmode='group')
            fig.update_layout(xaxis=dict(tickmode='linear'), width=800,plot_bgcolor='rgba(0,0,0,0)', yaxis=(dict(showgrid=False)),)
            fig.update_xaxes(title_text='Rating Score')
            fig.update_yaxes(title_text='No. of Ratings')
            st.plotly_chart(fig, use_container_width=True)
        
    
        if ratingyes:
            #st.subheader('Number of each Rating Scores')
            ratingcounts = ratings['Rating_Score'].value_counts()
            
            fig3 = px.bar(ratingcounts, text=ratingcounts,color_discrete_sequence = ['#F63366'],title='Number of each Rating Scores')
            fig3.update_layout(xaxis=dict(tickmode='linear'), plot_bgcolor='rgba(0,0,0,0)', yaxis=(dict(showgrid=False)),)
            fig3.update_xaxes(title_text='Rating Score')
            fig3.update_yaxes(title_text='No. of Ratings')
            
            fig4 = px.pie(ratingcounts, values=ratingcounts, names=ratingcounts.index, hole=.3, title='Number of each Rating Scores')
           
            
            rate1, rate2 = st.columns(2)
        
            rate1.plotly_chart(fig3,use_container_width=True)
            rate2.plotly_chart(fig4, use_container_width=True)
          


            
            
            
with option2:
   
    checkitem = st.sidebar.selectbox('Choose Item' +"💼",['--Please type an item--']+item_list)
    
    
    if checkitem == '--Please type an item--':
        st.sidebar.info('You can view the Item information here.')
    
    else:
        #intro.empty()
        st.markdown('''<h4 style='text-align: left; color: #28e515 ;'>Displaying the Item's Statistics</h4>''',
                    unsafe_allow_html=True)
    
        
        
        x = ratings[ratings['Item_Name'] == checkitem]

        itemdetails = x['Rating_Score'].value_counts()
        
        st.write(checkitem)
        
        #filtercol = x['Review_Date '].str.split('Reviewed in').str[1]
        #filtercol2 = filtercol.str.split('on').str[0]

        countrydetails = x['Review_Location'].value_counts()
        
        
        
        c1, c2 = st.columns((2,1))
        
            
        fig2 = px.bar(itemdetails, text=itemdetails,title='Number of each Ratings based on Item')
        
        fig2.update_layout(xaxis=dict(tickmode='linear'), plot_bgcolor='rgba(0,0,0,0)', yaxis=(dict(showgrid=False)),)
        fig2.update_xaxes(title_text='Rating Score')
        fig2.update_yaxes(title_text='No. of Ratings')
        
      
        fig3 = px.pie(x, values=itemdetails, names=itemdetails.index, hole=.3, title='Number of each Ratings based on Item')
        
        c1.plotly_chart(fig2,use_container_width=True)
        c2.plotly_chart(fig3, use_container_width=True)
        
        
        sort_order = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        
        
        filtercol = x['Review_Date'].str.split('-').str[1]
        filtercol2 = x['Review_Date'].str.split('-').str[2]
        filtercol3 = filtercol +' '+ filtercol2
        
        
        
       
    
       
        
        
        
        
        #days_sorted = sorted(filtercol3, key=lambda day: datetime.strptime(day, "%b %y"))
        
        
       
        choice = st.multiselect('Please select the month(s)',sort_order, sort_order[0])
       
        mask = filtercol.isin(choice)
        
        result = x[mask].shape[0]
        
        st.markdown(f'*Available Results:* **{result}**')
        
        dfgrouped = x[mask].groupby(by=['Rating_Score']).count()[['Review_Date']]
        
        
        c3, c4 = st.columns(2)
        
        
        
        bar_chart = px.bar(dfgrouped,color_discrete_sequence = ['#F63366'],title='Total Ratings based on ' + str(choice),text_auto=True,barmode='group')
        bar_chart.update_layout(xaxis=dict(tickmode='linear'), showlegend=False,plot_bgcolor='rgba(0,0,0,0)', yaxis=(dict(showgrid=False)),)
        bar_chart.update_xaxes(title_text='Rating Score')
        bar_chart.update_yaxes(title_text='No. of Ratings')
        
        
        
        #checkmonth = mask.value_counts()
        
      
        
        fig = px.line(dfgrouped,title='Total Ratings based on ' + str(choice))
        fig.update_layout(xaxis=(dict(showgrid=False)),showlegend=False,plot_bgcolor='rgba(0,0,0,0)', yaxis=(dict(showgrid=False)),)
        fig.update_xaxes(title_text='Rating Score')
        fig.update_yaxes(title_text='No. of Ratings')
        
        c3.plotly_chart(bar_chart,use_container_width=True)
        c4.plotly_chart(fig,use_container_width=True)
        
        
        
        #fig0 = px.bar(checkmonth,text=checkmonth, color_discrete_sequence = ['#F63366'],title='Number of Ratings based on Month')
        #fig0.update_layout(xaxis=(dict(showgrid=False)), plot_bgcolor='rgba(0,0,0,0)', yaxis=(dict(showgrid=False)),)
        #fig0.update_xaxes(title_text='Month')
        #fig0.update_yaxes(title_text='No. of Ratings')
        #st.write(fig0)
        
        
        
        fig4 = px.bar(countrydetails,text=countrydetails,title='Number of Ratings based on Country',color=countrydetails)
        fig4.update_layout(xaxis=dict(tickmode='linear'),showlegend=False, plot_bgcolor='rgba(0,0,0,0)', yaxis=(dict(showgrid=False)),)
        fig4.update_xaxes(title_text='Country')
        fig4.update_yaxes(title_text='No. of Ratings')
        
        fig5 = px.pie(countrydetails, values=countrydetails,names=countrydetails.index, hole=.3, title='Number of Ratings based on Country')
        
        #set plots side by side
        leftcol, rightcol = st.columns(2)
        
        leftcol.plotly_chart(fig4,use_container_width=True)
        rightcol.plotly_chart(fig5, use_container_width=True)
            
with option3:

    checkuser = st.sidebar.selectbox('Choose Customer Name' + '👨‍👨‍👧‍👧',['--Please type a customer name--']+user_list)
    
    if checkuser == '--Please type a customer name--':
        st.sidebar.info('You can view the Customer information here.')
    
    else:
        #intro.empty()
        st.markdown('''<h4 style='text-align: left; color:#28e515;'>Displaying the User's Statistics</h4>''',
                    unsafe_allow_html=True)
                    
        dropimage = ratings.drop(columns=['Image_Url'])
        
        x = dropimage[dropimage['Username'] == checkuser]   
        userdetails = x['Rating_Score'].value_counts()    
                    
        st.write('**Customer name**: '+checkuser)  
        st.write('**Total no. of items rated:** ',(sum(userdetails)))            
    
       
        with st.expander('Click here for more information'):
        
            st.table(x)
        
        
        
        # Create subplots: use 'domain' type for Pie/bar subplot
        #fig = make_subplots(rows=1, cols=2, specs=[[{'type':'domain'}, {'type':'domain'}]])
        
        #fig.add_trace(go.Pie(labels=itemdetails.index, values=itemdetails, name="pie chart"),
        #      1, 1)
        #fig.add_trace(go.Pie(labels=userdetails.index, values=userdetails, name="bar chart"),
        #      1, 2)
        #st.write(fig)
        
        
        fig2 = px.bar(userdetails, text=userdetails,title='Number of each Ratings based on Customer')
        fig2.update_layout(xaxis=dict(tickmode='linear'), plot_bgcolor='rgba(0,0,0,0)', yaxis=(dict(showgrid=False)),)
        fig2.update_xaxes(title_text='Rating Score')
        fig2.update_yaxes(title_text='No. of Ratings')
        

      
        fig3 = px.pie(x, values=userdetails, names=userdetails.index, hole=.3, title='Number of each Ratings based on Customer')
        
        user1, user2 = st.columns(2)
        
        user1.plotly_chart(fig2,use_container_width=True)
        user2.plotly_chart(fig3, use_container_width=True)
    
    
with svdAlgo:

    #Define a Reader object
    #The Reader object helps in parsing the file or dataframe containing ratings
    ratings = ratings.drop(columns=['Image_Url','Review_Date','Review_Location'])

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

    
    usersopt = st.sidebar.selectbox('Predict a Customer Rating Score' + '⭐', ['--Please type a customer name--']+user_list)

    if usersopt == '--Please type a customer name--':
        st.sidebar.warning('You can view the rating score here')
        
   
    elif usersopt != '--Please type a customer name--':
        #intro.empty()
        itemsopt = st.sidebar.selectbox('Choose Item' + "💼", ['--Please type an item--']+item_list, key=1)
    
        if itemsopt == '--Please type an item--':
            st.sidebar.warning('Please choose an item')
        
        #do a for loop here for the item list
        
        elif itemsopt == item_list[0]:
            st.sidebar.image("https://m.media-amazon.com/images/I/31XThr46I6L._AC_US60_SCLZZZZZZZ__.jpg", width = 150)
            calculate_button = st.sidebar.button('Calculate', on_click=None)
            if calculate_button == True:
                st.sidebar.subheader('Estimated Rating Score out of 5 is:')  
                result = st.sidebar.success(svd.predict(itemsopt, usersopt).est.round(2))
                
               
                
        elif itemsopt == item_list[1]:
            st.sidebar.image("https://m.media-amazon.com/images/I/41UuyU7HsPL._AC_US60_SCLZZZZZZZ__.jpg", width = 150)
            calculate_button = st.sidebar.button('Calculate', on_click=None)
            if calculate_button == True:
                st.sidebar.subheader('Estimated Rating Score out of 5 is:')  
                
                result = st.sidebar.success(svd.predict(itemsopt, usersopt).est.round(2))
                
                
        elif itemsopt == item_list[2]:
            st.sidebar.image("https://m.media-amazon.com/images/I/41t1HM8UN8L._AC_US60_SCLZZZZZZZ__.jpg", width = 150)
            calculate_button = st.sidebar.button('Calculate', on_click=None)
            if calculate_button == True:
                st.sidebar.subheader('Estimated Rating Score out of 5 is:')  
                
                result = st.sidebar.success(svd.predict(itemsopt, usersopt).est.round(2))
                
                
                
        elif itemsopt == item_list[3]:
            st.sidebar.image("https://m.media-amazon.com/images/I/41YNaNTqJoL._AC_US60_SCLZZZZZZZ__.jpg", width = 150)
            calculate_button = st.sidebar.button('Calculate', on_click=None)
            if calculate_button == True:
                st.sidebar.subheader('Estimated Rating Score out of 5 is:')  
                
                result = st.sidebar.success(svd.predict(itemsopt, usersopt).est.round(2))
                
                
        elif itemsopt == item_list[4]:
            st.sidebar.image("https://m.media-amazon.com/images/I/41sHexVm4NL._AC_US60_SCLZZZZZZZ__.jpg", width = 150)
            calculate_button = st.sidebar.button('Calculate', on_click=None)
            if calculate_button == True:
                st.sidebar.subheader('Estimated Rating Score out of 5 is:')  
                
                result = st.sidebar.success(svd.predict(itemsopt, usersopt).est.round(2))
                
                

#codes for formating date
#filtercol = x['Review_Date '].str.split('Reviewed in').str[1]
#filtercol2 = filtercol.str.split('on').str[0]
#filtercol3 = filtercol.str.split('on').str[1]
#countrydetails = filtercol2.value_counts()
 
#date_list = filtercol3.unique().tolist()
 
#for d in date_list:
#	datetimeformat = parse(d)   



        