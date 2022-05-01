import sys
from streamlit import cli as stcli
import streamlit
    
def main():
    import streamlit as st
    import numpy as np
    import pandas as pd
    import json

    from PIL import Image
    from surprise import Reader, Dataset
    from surprise import SVD
    import plotly.express as px
    import time

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

    header = st.container()
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
            


    #data = run_query("SELECT * from combinedreview;")
    #item = run_query("SELECT distinct item_name from combinedreview;")
    #user = run_query("SELECT distinct customername from combinedreview;")
    #rating = run_query("SELECT rating_score from combinedreview;")

    image_list = df.iloc[:, 1].unique().tolist()
    item_list = df.iloc[:, 2].unique().tolist() 
    user_list = df.iloc[:, 3].unique().tolist()
    rs_list = df.iloc[:, 4].tolist()
    country_list = df.iloc[:, 5].unique().tolist()
    date_list = df.iloc[:, 6].unique().tolist()

    storeImage = image_list


    def introduction():
        with header:

            
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

    navigation_button = st.sidebar.button('Back', on_click="https://fyp-project-recommender-system.herokuapp.com/userhome.php")
    def overallPlots():
        
        with option1:

            askcategory = st.sidebar.radio("Do you want to view the overall plots?" + '📊',('Yes', 'No'), index=0)
            
            if askcategory == 'Yes':
                #intro.empty()

                itemyes = st.sidebar.checkbox('Items')
                useryes = st.sidebar.checkbox('Customers')
                ratingyes = st.sidebar.checkbox('Rating Scores')
            
            
            

                if itemyes:
                    
                    #item_list = [row[0] for row in item]
                    #data_list = [row[0] for row in data]
                    #date_list = ratings.iloc[:, 5].unique().tolist()
                    #top_n = st.sidebar.number_input("Please indicate the number of Items",min_value=1, max_value=len(item_list))
                    top_n = st.sidebar.multiselect("Select Items",item_list,default=item_list[0])
                    #dateopts = st.sidebar.date_input("Select Date")
                    
                    filteritem = df[df['item_name'].isin(top_n)]
                    
                    itemcolumns2 = filteritem['item_name'].str.split('|').str[0]
                    itemcolumns3 = itemcolumns2.str.split('5G').str[0]
                
                    
                
                        #st.write(datetimeformat)
                    #filtercol4 = filtercol3.dt.strftime('%Y-%m-%d')
                    
                
                    itemcrosstab = pd.crosstab(itemcolumns3, df['rating_score'])
                    #itemcrosstab_text = itemcrosstab.iloc[:,0].tolist()

                    itemcrosstab2 = pd.crosstab(df['rating_score'], itemcolumns3)

                    

                    fig3 = px.line(itemcrosstab2,title='Number of each Ratings per Item')
                    fig3.update_layout(xaxis=dict(tickmode="linear",showgrid=False),plot_bgcolor='rgba(0,0,0,0)', yaxis=(dict(showgrid=False)),)
                    fig3.update_xaxes(title_text='Rating Score')
                    fig3.update_yaxes(title_text='No. of Ratings')
                
                    st.plotly_chart(fig3, use_container_width=True)
                
                
                    fig2 = px.bar(itemcrosstab, text_auto=True,title='Number of each Ratings per Item', barmode='group')
                    fig2.update_layout(xaxis=dict(tickmode='linear'),plot_bgcolor='rgba(0,0,0,0)', yaxis=(dict(showgrid=False)),)
                    fig2.update_xaxes(title_text='Rating Score')
                    fig2.update_yaxes(title_text='No. of Ratings')
                
                    st.plotly_chart(fig2, use_container_width=True)
                    
                    
                    
                
                    
                if useryes:

                    #top_n = st.sidebar.slider("How many users do u want to see?", 0,999,10)
                    #top_n = st.sidebar.number_input("Please indicate the number of Customers",value=10,min_value=1, max_value=len(user_list))
                    top_n = st.sidebar.multiselect("Select Customers",user_list,default='Amazon Customer')
                    filteruser = df[df['customername'].isin(top_n)]
                    usercrosstab = pd.crosstab(filteruser['customername'], df['rating_score'])
                    
                    fig = px.bar(usercrosstab,text_auto=True,title='Number of each Ratings per Customer', barmode='group')
                    fig.update_layout(xaxis=dict(tickmode='linear'), width=800,plot_bgcolor='rgba(0,0,0,0)', yaxis=(dict(showgrid=False)),)
                    fig.update_xaxes(title_text='Rating Score')
                    fig.update_yaxes(title_text='No. of Ratings')
                    st.plotly_chart(fig, use_container_width=True)
                
            
                if ratingyes:
                    #st.subheader('Number of each Rating Scores')
                    ratingcounts = df['rating_score'].value_counts()
                    
                    fig3 = px.bar(ratingcounts, text=ratingcounts,color_discrete_sequence = ['#F63366'],title='Number of each Rating Scores')
                    fig3.update_layout(xaxis=dict(tickmode='linear'), plot_bgcolor='rgba(0,0,0,0)', yaxis=(dict(showgrid=False)),)
                    fig3.update_xaxes(title_text='Rating Score')
                    fig3.update_yaxes(title_text='No. of Ratings')
                    
                    fig4 = px.pie(ratingcounts, values=ratingcounts, names=ratingcounts.index, hole=.3, title='Number of each Rating Scores')
                
                    
                    rate1, rate2 = st.columns(2)
                
                    rate1.plotly_chart(fig3,use_container_width=True)
                    rate2.plotly_chart(fig4, use_container_width=True)
                

    def itemSelect():            
        with option2:
        
            checkitem = st.sidebar.selectbox('Choose Item' +"💼",['--Please type an item--']+item_list)
            
            
            if checkitem == '--Please type an item--':
                st.sidebar.info('You can view the Item information here.')
            
            else:
                #intro.empty()
                st.markdown('''<h4 style='text-align: left; color: #28e515 ;'>Displaying the Item's Statistics</h4>''',
                            unsafe_allow_html=True)
            
                
                
                x = df[df['item_name'] == checkitem]

                itemdetails = x['rating_score'].value_counts()
                
                
                
                #filtercol = x['Review_Date '].str.split('Reviewed in').str[1]
                #filtercol2 = filtercol.str.split('on').str[0]

                countrydetails = x['review_location'].value_counts()
                
                
                
                c1, c2 = st.columns((2,1))
                
                    
                fig2 = px.bar(itemdetails, text=itemdetails,title='Number of each Ratings based on Item')
                
                fig2.update_layout(xaxis=dict(tickmode='linear'), plot_bgcolor='rgba(0,0,0,0)', yaxis=(dict(showgrid=False)),)
                fig2.update_xaxes(title_text='Rating Score')
                fig2.update_yaxes(title_text='No. of Ratings')
                
            
                fig3 = px.pie(x, values=itemdetails, names=itemdetails.index, hole=.3, title='Number of each Ratings based on Item')
                
                c1.plotly_chart(fig2,use_container_width=True)
                c2.plotly_chart(fig3, use_container_width=True)
                
                
                sort_order = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                
                
                filtercol = x['review_date'].str.split('-').str[1]

                filtercol2 = x['review_date'].str.split('-').str[2]
                filtercol3 = filtercol +' '+ filtercol2
                
                
                
            
            
            
        
                
                
                
                #days_sorted = sorted(filtercol3, key=lambda day: datetime.strptime(day, "%b %y"))
                
                
            
                choice = st.multiselect('Please select the month(s)',sort_order, sort_order[0])
            
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
                
                leftcol.plotly_chart(fig4,use_container_width=True)
                rightcol.plotly_chart(fig5, use_container_width=True)

    def custSelect():            
        with option3:

            checkuser = st.sidebar.selectbox('Choose Customer Name' + '👨‍👨‍👧‍👧',['--Please type a customer name--']+user_list)
            
            if checkuser == '--Please type a customer name--':
                st.sidebar.info('You can view the Customer information here.')
            
            else:
                #intro.empty()
                st.markdown('''<h4 style='text-align: left; color:#28e515;'>Displaying the User's Statistics</h4>''',
                            unsafe_allow_html=True)
                            
                dropimage = df.drop(columns=['image_url', 'combinedreviewid'])
                
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
                
                user1, user2 = st.columns(2)
                
                user1.plotly_chart(fig2,use_container_width=True)
                user2.plotly_chart(fig3, use_container_width=True)
        
    

    
        

    def predictScore():    
        with calculate:

            #Define a Reader object
            #The Reader object helps in parsing the file or dataframe containing ratings
            df2 = df.drop(columns=['combinedreviewid','image_url','review_date','review_location'])

            reader = Reader()
            #dataset creation
            data = Dataset.load_from_df(df2, reader)
            #model
            svd = SVD()

            trainset = data.build_full_trainset()
            svd.fit(trainset)

            user_list = df2.iloc[:, 1].unique().tolist()
            item_list = df2.iloc[:, 0].unique().tolist()
            

            usersopt = st.sidebar.selectbox('Predict a Customer Rating Score' + '⭐', ['--Please type a customer name--']+user_list)

            if usersopt == '--Please type a customer name--':
                st.sidebar.warning('You can view the rating score here')
                
        
            elif usersopt != '--Please type a customer name--':
                #intro.empty()
                itemsopt = st.sidebar.selectbox('Choose Item' + "💼", ['--Please type an item--']+item_list, key=1)
            
                if itemsopt == '--Please type an item--':
                    st.sidebar.warning('Please choose an item')
                
            
                else:

                    #zip to pair the objects together, dict to make it iterable
                    combined = dict(zip(item_list, storeImage))
                    st.sidebar.image(combined[itemsopt], width=150)
                    calculate_button = st.sidebar.button('Calculate', on_click=None)
                    if calculate_button == True:
                        st.sidebar.subheader('Estimated Rating Score out of 5 is:')  
                        result = st.sidebar.success(svd.predict(itemsopt, usersopt).est.round(2))



    """ if __name__ == '__main__':
        introduction()
        overallPlots()
        itemSelect()
        custSelect()
        predictScore() """

    if __name__ == '__main__':
        introduction()
        overallPlots()
        itemSelect()
        custSelect()
        predictScore()
        
        if streamlit._is_running_with_streamlit:
            main()
        else:
            sys.argv = ["streamlit", "run", sys.argv[0]]
            sys.exit(stcli.main())