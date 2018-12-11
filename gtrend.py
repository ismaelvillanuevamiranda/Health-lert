# -- coding: utf-8 --
"""
Created on Wed Nov 28 13:27:02 2018

@author: Rohan
"""

import requests
from bs4 import BeautifulSoup
from urllib.request import urlopen
import re
#from nltk.corpus import stopwords
#from nltk.tokenize import word_tokenize
#import string
#from collections import counter


url= "http://www.who.int/csr/don/archive/year/2018/en/"
# html=urlopen(url).read()
# raw=BeautifulSoup(html,features="html.parser").get_text()
# print(raw)
sourcecode=requests.get(url)
plain_text=sourcecode.text
soup= BeautifulSoup(plain_text,features="html.parser")
for ul in soup.find_all('ul', class_='auto_archive'):
    #href="http://www.who.int/csr/don"+ link.get('href')
    #href=link.get('href')
    outbreak = ul.text
    outbreakSplit = outbreak.split(' \t\n\r')
    print(outbreakSplit)