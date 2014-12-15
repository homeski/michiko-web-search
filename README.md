michiko-web-search
==================

Project to crawl, index, and search creative writing information for michikokatsu.com

####How does it work?

1. List the URLs of any RSS feed we want to monitor into seeds.txt

2. Setup a cron job to run bin/retrieve on a interval of choice

3. After the cron job is finished we need to index and import the data into Solr. Visit the URL http://localhost:8983/solr/dataimport?command=full-import&clean=false

4. Start the Apache server and visit the provided index.html

5. Using the search bar on index.html, the indexed documents can be search through. The search uses the Extended DisMax query parser to search throught the documents.
