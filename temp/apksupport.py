
import sys
from bs4 import BeautifulSoup

import requests
from pathlib import Path

import random

# command line arguments
appId = sys.argv[1]
# scrape_str = sys.argv[2]

css_selector = '#pageapp > div > div.downloading_tinfo > div.dinfo > div.fhelp > a'

url_apksupport = "https://apk.support/download-app/" +  appId
url_apkpure = "https://apkpure.com/art_and_design?page=21"


def random_line(afile):
    line = str(next(afile))
    for num, aline in enumerate(afile, 2):
        if random.randrange(num):
            continue
        line = aline
    return line


try:
    # getting a random user agent from a file of many useragents
    afile = open("useragents.txt")
    headers = random_line(afile).rstrip()
    print(headers)

    r  = requests.get(url_apkpure, headers={'User-Agent': headers}, timeout = 5)
    print(r)
    data = r.text
    # print(data)
    soup = BeautifulSoup(data, features="lxml")

    #title of apk:
    print("\nTitle of apk")
    print(soup.title)
    print("\nTitle of apk (string only)")
    print(soup.title.string)


    #Content of First paragraph:
    print("\nSoup First Paragraph")
    print(soup.p)
    print(soup.p.text)

    #Href
    print("\nHref:")
    print(soup.a)
    print(soup.a['title'])
    print()
    # print(soup.p.parent)
    print(soup.p.parent.prettify())

    #Find all links
    print("All links:")
    for link in soup.find_all('a'):
        print(link.get('href'))

    #download link of apk:
    # print("\nDownload link of apk")
    # get_dl_link = soup.select(css_selector)
    # link = get_dl_link[0].get('href')
    # print(link)

    try:
        print("")
        print("checking the exact download link response code")
        # resp = requests.get(link, headers={'User-Agent': headers}, timeout =5)
        # print(resp)
    except Exception as ex:
        print(ex)


except Exception as ex:
    success = False
    print("The download link is not present on the site, it means the apk does not exist, possible reason is that the apk is removed by DMCA. See below the actual Error! generated by my python code")
    print("Actual Error: ", ex)


