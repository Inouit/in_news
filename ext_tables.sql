#
# Table structure for table 'sys_category'
#
CREATE TABLE sys_category (
	tx_innews_category_list_page int(11) unsigned DEFAULT '0' NOT NULL,
);

#
# Table structure for table 'pages'
#
CREATE TABLE pages (
	tx_innews_news_category tinyint,
	tx_innews_news_teaser text,
	tx_innews_news_event_from int(11) DEFAULT '0' NOT NULL,
	tx_innews_news_event_to int(11) DEFAULT '0' NOT NULL,
	tx_innews_news_event_where tinytext NOT NULL,
);