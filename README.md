# Take Home Challenge Backend

1) in root cmd run : "docker-compose up --build"
3) checkout docker app terminal and run : "php artisan migrate" for setup database
4) checkout docker app terminal and run : "php artisan news:scrape" for fetching articles from data source like newsApi, The NewYork Times and The Guardians.

# Take Home Challenge Frontend

setup : 1) docker build -t take-home-challenge-frontend .
        2) docker run -p 3000:3000 take-home-challenge-frontend