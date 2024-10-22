import requests as r
from bs4 import BeautifulSoup as bs

URL = 'https://www.univr.it/it/corsi-di-studio/2024-2025/medicina-e-chirurgia'


def fetch_page():
    response = r.get(URL)
    soup = bs(response.text, 'html.parser')
    return soup

def get_bachelors(soup):
    return soup.find_all('div', class_='item-corso')

def get_bachelor_name(bachelor):
    tag = bachelor.find('h4', class_='item-corso-title')
    # remove the child tag
    
    return tag.find(string=True, recursive=False).strip().replace("'", " ")

def get_bachelor_places(bachelor):
    tags = bachelor.find_all('a')
    return [(tag['href'].strip(), tag.text.strip()) for tag in tags]

# create sql insert
def create_insert_sql(name, place, url):
    sql = f"INSERT INTO corsi (laurea, sede, url) VALUES ('{name}', '{place}', '{url}');"
    return sql

def create_orari_sql(id):
    out = ''
    for i in range(1, 4):
        out += f"INSERT INTO orari (corso, `year`, lasturl) VALUES({id}, {i}, '');\n"
    return out

def main():
    soup = fetch_page()
    bachelors = get_bachelors(soup)
    id = 1
    for bachelor in bachelors:
        name = get_bachelor_name(bachelor)
        places = get_bachelor_places(bachelor)
        for url, place in places:
            print(create_insert_sql(name, place, url))
            print(create_orari_sql(id))
            id += 1

if __name__ == '__main__':
    main()