from re import T
from textwrap import indent
from bs4 import BeautifulSoup
import random
from numpy import short
import requests
import sys
from lxml import etree
import json
from pathlib import Path

appId = sys.argv[1]
mode  = sys.argv[2]

search_query = appId

short_apk_json_path = 'user_content/short_apk_json/' + appId + '.json'
short_apk_search_path = 'user_content/short_apk_search/' + search_query + '.txt'

apk_url = 'https://play.google.com/store/apps/details?id=' + appId
search_url = 'https://play.google.com/store/search?q=' +  search_query + '&c=apps'

afile = open("useragents.txt")
last_app_index = 6

def random_line(afile):
    line = str(next(afile))
    for num, aline in enumerate(afile, 2):
        if random.randrange(num):
            continue
        line = aline
    return line

def short_apk_json_scrap(apk_url,appId):

    this_path = Path(short_apk_json_path)

    if this_path.is_file():
        print(f'The file {this_path} exists')
    
    else:
        headers = random_line(afile).rstrip()
        print(headers)
        print(url)

        r  = requests.get(url, headers={'User-Agent': headers}, timeout = 100)
        print(r)

        data = r.text

        data = data.encode(encoding = 'UTF-8', errors = 'strict')
        soup = BeautifulSoup(data,features="html.parser")
        dom = etree.HTML(str(soup))

        # app icon
        try:
            icon = dom.xpath("(//img[@alt='Icon image'])[1]/@src")
            icon = icon [0]
            print(f"icon_url: {icon}")

        except Exception as ex:
            print(ex)

        try:
            title = dom.xpath("//h1/span/text()")
            title = title [0]
            print(f"App_title: {title}")

        except Exception as ex:
            print(ex)

        try:
            meta_description = dom.xpath("//meta[@name='description']//@content")
            meta_description = meta_description[0]
            # print(f"Meta Description is: {meta_description}")

        except Exception as ex:
            print(ex)

        try:
            str1 = ""
            full_description = dom.xpath('//meta[@itemprop="description"]/following::div[1]/text()')
            full_description = str1.join(full_description)
            # print(f"Full Description is: {full_description}")

        except Exception as ex:
            print(ex)


        try:
            rating= dom.xpath('//div[@itemprop="starRating"]/div/text()')
            rating = rating[0]
            print(f"Rating: {rating}")

        except Exception as ex: 
            print(ex)


        try: 
            num_of_downloads = dom.xpath('//div[@itemprop="starRating"]/following::div[3]/text()')
            num_of_downloads = num_of_downloads[0]
            print(f"Number of downloads: {num_of_downloads}")

        except Exception as ex:
            print(ex)


        short_apk_json = {
            'appId':appId,
            'url':url,
            'icon': icon,
            'title': title,
            'rating': rating,
            'num_of_downloads': num_of_downloads,
            'meta_description': meta_description,
            'full_description': full_description
        }

        # print(short_apk_json)

        with open("user_content/short_apk_json/" + appId + ".json", 'w') as fd:
            json.dump(short_apk_json, fd, indent=4)

def similar_apps_search(search_url,search_query,last_app_index):

    this_path = Path(short_apk_search_path)

    if this_path.is_file():

        print(f'The file {this_path} exists')
           
        f = open("user_content/short_apk_search/" + search_query + ".txt", "r")
        links = f.readlines()
        similar_apps = []

        for link in links:
            similar_apps.append(link.rstrip()) 

        print("successfully read similar app links")

    else:

        print(search_url)

        # getting a random user agent from a file of many useragents
        headers = random_line(afile).rstrip()
        print(headers)

        r  = requests.get(search_url, headers={'User-Agent': headers}, timeout = 100)
        print(r)

        data = r.text

        # print(data)

        data = data.encode(encoding = 'UTF-8', errors = 'strict')
        soup = BeautifulSoup(data,features="html.parser")
        dom = etree.HTML(str(soup))

        # Similar apps
        
        try:  
            similar_apps = dom.xpath("//a/@href")
            print(f"Similar_apps: {similar_apps}")

            with open( 'user_content/short_apk_search/' + search_query +".txt", 'w', encoding="utf-8") as fd:
                
                for app in similar_apps[3:5]:
                    print("\n*********** Filtering and writing Similar App links ***********")
                    filter = app.split("?")
                    print("-> now filtering search urls")
                    # print(filter)

                    if "/store/search" in filter:
                        print("####### Irrelevant Search String #######")
                        print("-> search string exists!")
                        last_app_index = 8
                        print("-------------> At line # 86\n")

                for link in similar_apps[3:last_app_index]:
                    filter = link.split("?")
                    if "/store/search" in filter:
                        print("-> continue ...")
                        continue

                    appId = link.split("=")
                    appId = appId [1]
                    print(appId)

                    fd.write(appId)
                    fd.write("\n")

                print("successfully wrote similar apps into a file!")
                print("\n-------------> At line # 102")

        except Exception as ex:
            print(ex) 

########### main driver ###########

if (mode == 'search'):
    similar_apps_search(search_url,search_query,last_app_index)
