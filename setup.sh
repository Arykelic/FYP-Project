mkdir -p ~/.streamlit/

echo "[theme]
primaryColor = '#F63366'
backgroundColor = '#black'
secondaryBackgroundColor = '#31333F'
textColor= '#F9F5F5'
font = 'sans serif'
[server]
headless = true
port = $PORT
enableCORS = false
" > ~/.streamlit/config.toml