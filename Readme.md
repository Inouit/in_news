# What's this about
_in_news_ is plugin for Typo3 to manage News as Pages.

# Why using page
Ok. that question is a good one. In fact, there's a [french debate](https://www.youtube.com/watch?v=D5AuZfrW_lY&index=16&list=PLnszbTENO-kXsPiTquOPpUwzNjOSv3OFI) which try to find a response. This plugin choose his side cause a lot of page's options should be native for news too : author, publication date, url rewriting, templating, advanced content layout, ...
  
# The features  
## Categories  
 - display a list of categories in a tree view


## News  

 - display a list of news with some option:
   - filter only news related to categories (complex union are available)
   - highilighted news in first
   - only highilighted news
   - filter only current and future news (pretty useful to handle events)
   - filter by date range (start and duration)
 - in all fluid template file, you can access to a news object which contains news properties (only if you are on a news page)

# Reference
Name                       | Data Type                           | Default     | Description
---------------------------|-------------------------------------|-------------|------------
orderBy                    | displayDate&#124;crdate&#124;tstamp | displayDate | manage order by a field
orderDirection             | ASC&#124;DESC                       | DESC        | order direction
limit                      | int                                 |             | limit the number of results
topFirst                   | boolean                             | 0           | display hightlighted news before others
onlyTop                    | boolean                             | 0           | display only hightlighted news
targetedCategories         | list of ids                         |             | display only news related to categories (you can add more complex filter like 1,2&8)
targetedCategoriesUnion    | OR&#124;AND&#124;ANY                          | OR          | define the relation between targeted categories
excludePastEvents          | boolean                             | 0           | hide past news
notDisplayedCategories     | list of ids                         |             | hide some categories in listing (the category filter still work)
dateRange.start            | DateTime                            |             | start date of the range filter
dateRange.duration         | DateInterval                        |             | duration of the range in addition to dateRange.start
listPage                   | id                                  |             | pid of the page which contains a listing of news
pagebrowser.itemsPerPage   | int                                 |             | activate pagebrowser (template must contains pagebrowser viewhelper) and limit number of items per page
images.width               | int/stdwrap                         | 200         | width of image
images.height              | int/stdwrap                         | 150m        | height of image
