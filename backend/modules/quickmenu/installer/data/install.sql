CREATE TABLE quickmenu (
  id int(11) NOT NULL AUTO_INCREMENT,
  category_id int(11) NOT NULL,
  page_id int(10) unsigned NOT NULL,
  created_on datetime NOT NULL,
  edited_on datetime NOT NULL,
  sequence int(11) NOT NULL,
  language varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS quickmenu_categories (
  id int(11) NOT NULL AUTO_INCREMENT,
  title varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  sequence int(11) NOT NULL,
  extra_id int(10) unsigned NOT NULL,
  created_on datetime NOT NULL,
  edited_on datetime NOT NULL,
  PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
