import streamlit as st
from streamlit_option_menu import option_menu
import numpy as np
import pandas as pd
import json


from PIL import Image
from surprise import Reader, Dataset
from surprise import SVD
import plotly.express as px


from sqlalchemy import create_engine
import pymysql

import plotly.graph_objects as go
from plotly.subplots import make_subplots
from streamlit_lottie import st_lottie

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

s = f"""
        <style>
        .button {{border: none;
          color: white;
          padding: 5px 5px;
          text-align: center;
          text-decoration: none;
          display: inline-block;
          font-size: 18px;
          margin: 4px 2px;
          cursor: pointer;
          border-radius: 1px;
          background-color: #2C3845;
            }}

        .button1:hover {{background-color: #f63366;}} 

        
        <style>
        """
st.markdown(s, unsafe_allow_html=True)

header = st.container()
option0 = st.container()
option1 = st.container()
option2 = st.container()
option3 = st.container()
calculate = st.container()

db_connection_str = 'mysql+pymysql://y0vryqAKXK:moMOpaacUP@remotemysql.com/y0vryqAKXK'

query = 'select * from combinedreview'

@st.cache(allow_output_mutation=True)
def get_connection():
    return create_engine(db_connection_str)

@st.cache
def load_data(query):
    #with st.spinner('Loading Data...'):
        #time.sleep(0.5)
    df = pd.read_sql(query, get_connection())
    return df

df = load_data(query)

        
def load_lottiefile(filepath: str):
    with open(filepath, 'r') as f:
        return json.load(f)

    

st.sidebar.write('''<h2 style='position:relative;left:15px;'>Go back to User Page <a target="_self" href="https://fyp-project-recommender-system.herokuapp.com/userhome.php"><button class="button button1">Back</button></a></h2>''', 
                        unsafe_allow_html=True)
        
with st.sidebar:
    selected = option_menu(
                menu_title="Menu", 
                options=["Home", "Ratings", "Compare Selections", 'Items', 'Customers', 'Predict Rating Score'], 
                icons=['house-fill', 'star-half', "bar-chart-fill", 'basket-fill', 'people-fill','patch-question-fill'], 
                menu_icon="list", 
                default_index=0,
                styles={
        "container": {"padding": "1!important"},
        "icon": {"color": "orange", "font-size": "20px"}, 
        "nav-link": {"font-size": "18px", "text-align": "left", "margin":"0px", "--hover-color": "#f63366"},
        "nav-link-selected": {"background-color": "#2C3845"},
    } 
                )



image_list = df.iloc[:, 1].unique().tolist()
item_list = df.iloc[:, 2].unique().tolist() 
user_list = df.iloc[:, 3].unique().tolist()
rs_list = df.iloc[:, 4].tolist()
country_list = df.iloc[:, 5].unique().tolist()
date_list = df.iloc[:, 6].unique().tolist()

storeImage = image_list



def introduction():
    with header:

        if selected == "Home":

            lottie_img = load_lottiefile('lottie/system.json')
            
            
            col1, col2 = st.columns((1,4))
            with col1:
                st_lottie(lottie_img, height=300, width=300, key='logo')
                
            with col2:
                
                st.markdown('''<h1 style='position:relative;top:100px;text-align: left; color:white ;'>Rating Recommender System</h1>''',unsafe_allow_html=True)
                
            

            
            
            intro = st.markdown('''<h4 style='text-align: left; color: #f63366;'>The Data is based on Amazon webpage</h4>''',unsafe_allow_html=True)
            st.write('')
            st.write('')
            col1, col2, col3 = st.columns(3)
            col1.metric("Total No. of Customers",len(user_list))
            col2.metric("Total No. of Items", len(item_list))
            col3.metric("Total No. of Rated Scores", len(rs_list))



def overallPlots():
    
    with option0:
    
       


        if selected == "Ratings":

            #askcategory = st.sidebar.radio("Do you want to view Overall Rating Scores?" + '📊',('Yes', 'No'), index=1)
             
            #if askcategory == 'Yes':

            ratingcounts = df['rating_score'].value_counts()

            st.markdown('''<h2 style='text-align: left; color: #28e515 ;'>Displaying Overall Rating Scores</h2>''',
                        unsafe_allow_html=True)
        
            st.markdown(f'*Total Rated Scores:* **{len(rs_list)}**')
                    
            fig3 = px.bar(ratingcounts, text=ratingcounts,color_discrete_sequence = ['#F63366'],title='Number of each Rating Scores')
            fig3.update_layout(xaxis=dict(tickmode='linear'), plot_bgcolor='rgba(0,0,0,0)', yaxis=(dict(showgrid=False)),)
            fig3.update_xaxes(title_text='Rating Score')
            fig3.update_yaxes(title_text='No. of Ratings')
                    
            fig4 = px.pie(ratingcounts, values=ratingcounts, names=ratingcounts.index, hole=.3, title='Number of each Rating Scores')
                   
                    
            rate1, rate2 = st.columns(2)
                
            rate1.plotly_chart(fig3,use_container_width=True)
            rate2.plotly_chart(fig4, use_container_width=True)


def compareSelection():

    with option1:

        if selected == "Compare Selections":

            #askselection = st.write("Compare Selections here")

            #itemyes = st.checkbox('Items')
            #useryes = st.checkbox('Customers')

            col1, col2 = st.columns((1,8))

            lottie_img = load_lottiefile('lottie/compare.json')

            with col1:
                st_lottie(lottie_img, height=150, width=150, key='compare')
                
            with col2:
                st.markdown('''<h2 style='position:relative;top:25px;text-align: left; color: #28e515 ;'>Compare Selections</h2>''',
                        unsafe_allow_html=True)

            
        

            left,right,r2,r3,r4,r5,r6,r7 = st.columns(8)

            with left: 
                    itemyes = st.checkbox("💼"+'Items', value=True)

            with right:
                    useryes = st.checkbox('👨‍👨‍👧‍👧'+'Customers')


            if itemyes:
                  
                top_n = st.multiselect("Select Items",item_list,default=item_list[0])

                    
                filteritem = df[df['item_name'].isin(top_n)]
                    
                itemcolumns2 = filteritem['item_name'].str.split(',').str[0]

                itemcrosstab = pd.crosstab(itemcolumns2, df['rating_score'])


                itemcrosstab2 = pd.crosstab(df['rating_score'], itemcolumns2)

                        

                fig3 = px.line(itemcrosstab2,title='Number of each Ratings per Item')
                fig3.update_layout(xaxis=dict(tickmode="linear",showgrid=False),plot_bgcolor='rgba(0,0,0,0)', yaxis=(dict(showgrid=False)),)
                fig3.update_xaxes(title_text='Rating Score')
                fig3.update_yaxes(title_text='No. of Ratings')
                     
                st.plotly_chart(fig3, use_container_width=True)
                       
                       
                fig2 = px.bar(itemcrosstab,text_auto=True,title='Number of each Ratings per Item', barmode='group')
                fig2.update_layout(xaxis=dict(tickmode='linear'),plot_bgcolor='rgba(0,0,0,0)', yaxis=(dict(showgrid=False)),)
                fig2.update_xaxes(title_text='Rating Score')
                fig2.update_yaxes(title_text='No. of Ratings')
                     
                st.plotly_chart(fig2, use_container_width=True)
                    
                    
                    
                  
                    
            if useryes:

                   
                top_n = st.multiselect("Select Customers",user_list,default='Amazon Customer')

                filteruser = df[df['customername'].isin(top_n)]

                usercrosstab = pd.crosstab(filteruser['customername'], df['rating_score'])
                        
                fig = px.bar(usercrosstab,text_auto=True,title='Number of each Ratings per Customer', barmode='group')
                fig.update_layout(xaxis=dict(tickmode='linear'), width=800,plot_bgcolor='rgba(0,0,0,0)', yaxis=(dict(showgrid=False)),)
                fig.update_xaxes(title_text='Rating Score')
                fig.update_yaxes(title_text='No. of Ratings')
                st.plotly_chart(fig, use_container_width=True)
                

def itemSelect():            
    with option2:

        if selected == "Items":

            lottie_img = load_lottiefile('lottie/bars2.json')

            col1, col2 = st.columns((1,8))

            with col1:
                st_lottie(lottie_img, height=150, width=150, key='bars2')
                
            with col2:
                st.markdown('''<h2 style='position:relative;top:50px;text-align: left; color: #28e515 ;'>Display Item's Statistics</h2>''', unsafe_allow_html=True)

           

       
            checkitem = st.selectbox('Choose Item' +"💼",['--Please type an item--']+item_list)
            
                
            
            
            if checkitem == '--Please type an item--':
                st.info('You can view the Item information here.')
            
            else:
                #intro.empty()
                
            
                st.markdown(f'*Item Name:* **{checkitem}**')
                
                x = df[df['item_name'] == checkitem]

                itemdetails = x['rating_score'].value_counts()
                
                
                
                #filtercol = x['Review_Date '].str.split('Reviewed in').str[1]
                #filtercol2 = filtercol.str.split('on').str[0]

                countrydetails = x['review_location'].value_counts()
                
                
                
                c1, c2 = st.columns((2,1))
                
                    
                fig2 = px.bar(itemdetails, orientation='h',text=itemdetails,title='Number of each Ratings based on Item')
                
                fig2.update_layout(yaxis=dict(tickmode='linear'), plot_bgcolor='rgba(0,0,0,0)', xaxis=(dict(showgrid=False)),)
                fig2.update_xaxes(title_text='No. of Ratings')
                fig2.update_yaxes(title_text='Rating Score')
                
              
                fig3 = px.pie(x, values=itemdetails, names=itemdetails.index, hole=.3, title='Number of each Ratings based on Item')
                
                c1.plotly_chart(fig2,use_container_width=True)
                c2.plotly_chart(fig3, use_container_width=True)
                
                
                sort_order = ['January', 'Febuary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
                
                
                filtercol = x['review_date'].str.split(' ').str[1] #month

                filtercol2 = x['review_date'].str.split(' ').str[2] #year
                filtercol3 = filtercol +' '+ filtercol2
                
             

                
                #days_sorted = sorted(filtercol3, key=lambda day: datetime.strptime(day, "%b %y"))
                
                
               
                choice = st.multiselect('Please select the month(s)',sort_order, sort_order)
               
                mask = filtercol.isin(choice)
                
                result = x[mask].shape[0]
                
                st.markdown(f'*Available Results:* **{result}**')
                
                dfgrouped = x[mask].groupby(by=['rating_score']).count()[['review_date']]
                
                
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
                
                leftcol.plotly_chart(fig5,use_container_width=True)
                rightcol.plotly_chart(fig4, use_container_width=True)

def custSelect():            
    with option3:

         if selected == "Customers":

            

          
            st.markdown('''<h2 style='text-align: left; color:#28e515;'>Display Customer's Statistics</h2>''',
                            unsafe_allow_html=True)
                
            
            

            checkuser = st.selectbox('Choose Customer Name' + '👨‍👨‍👧‍👧',['--Please type a customer name--']+user_list)
            
            if checkuser == '--Please type a customer name--':
                st.info('You can view the Customer information here.')
            
            else:
                #intro.empty()
               
                            
                dropimage = df.drop(columns=['image_url', 'combinedreviewid', 'createddatetime', 'createdby'])
                
                x = dropimage[dropimage['customername'] == checkuser]   
                userdetails = x['rating_score'].value_counts()    
                         
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
                
                user1, user2, user3 = st.columns(3)

                lottie_img = load_lottiefile('lottie/custstats.json')

                
                
                with user1:
                    user1.plotly_chart(fig2,use_container_width=True)

                with user2:
                    user2.plotly_chart(fig3, use_container_width=True)

                with user3:
                    st_lottie(lottie_img, height=450, width=450, key='cstats')
    
              
   
    

def predictScore():    
    with calculate:

         #Define a Reader object
        #The Reader object helps in parsing the file or dataframe containing ratings
        df2 = df.drop(columns=['combinedreviewid','image_url','review_date','review_location', 'createddatetime', 'createdby'])

        reader = Reader()
        #dataset creation
        data = Dataset.load_from_df(df2, reader)
        #model
        svd = SVD()

        trainset = data.build_full_trainset()
        svd.fit(trainset)

        user_list = df2.iloc[:, 1].unique().tolist()
        item_list = df2.iloc[:, 0].unique().tolist()
        
        if selected == "Predict Rating Score":

            lottie_img = load_lottiefile('lottie/customer3.json')

            col1, col2 = st.columns((1,8))

            with col1:
                st_lottie(lottie_img, height=200, width=200, key='cust3')

                
            with col2:
                st.markdown('''<h2 style='position:relative;top:75px;text-align: left; color:#28e515;'>Calculate Customer's Rating Score</h2>''',
                            unsafe_allow_html=True)

        

            usersopt = st.selectbox('Predict a Customer Rating Score' + '⭐', ['--Please type a customer name--']+user_list)

            if usersopt == '--Please type a customer name--':
                st.warning('You can view the rating score here')
                
           
            elif usersopt != '--Please type a customer name--':
                #intro.empty()
                itemsopt = st.selectbox('Choose Item' + "💼", ['--Please type an item--']+item_list, key=1)
            
                if itemsopt == '--Please type an item--':
                    st.warning('Please choose an item')
                
             
                else:

                    #zip to pair the objects together, dict to make it iterable
                    combined = dict(zip(item_list, storeImage))
                    st.image(combined[itemsopt], width=200)
                    calculate_button = st.button('Calculate', on_click=None)
                    if calculate_button == True:
                        st.subheader('Estimated Rating Score out of 5 is:')  
                        result = st.success(svd.predict(itemsopt, usersopt).est.round(2))
            





if __name__ == '__main__':
    introduction()
    overallPlots()
    compareSelection()
    itemSelect()
    custSelect()
    predictScore()

