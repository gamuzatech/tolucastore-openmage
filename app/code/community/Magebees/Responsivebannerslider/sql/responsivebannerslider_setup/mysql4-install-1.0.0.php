<?php
$installer = $this;
$installer->startSetup();
$installer->run(
    "
CREATE TABLE IF NOT EXISTS {$this->getTable('responsivebannerslider_group')} (
  `slidergroup_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `position` varchar(255) NOT NULL default '',
  `sort_order` smallint(6) NOT NULL default '1',
  `status` tinyint(1) NOT NULL default '1',
  `start_animation` varchar(32) NOT NULL default '',
  `loop_slider` tinyint(1) NOT NULL default '0',
  `pause_snavigation` tinyint(1) NOT NULL default '0',
  `pause_shover` tinyint(1) NOT NULL default '0',
  `animation_type` varchar(32) NOT NULL default '',
  `animation_duration` varchar(32) NOT NULL default '600',
  `animation_direction` varchar(32) NOT NULL default '',
  `slide_duration` varchar(32) NOT NULL default '7000',
  `random_order` varchar(32) NOT NULL default '',
  `smooth_height` tinyint(1) NOT NULL default '1',
  `max_width` smallint(6) NOT NULL default '0',
  `slider_theme` varchar(32) NOT NULL default '',
  `slider_type` varchar(32) NOT NULL default '',
  `content_background` varchar(32) NOT NULL default '',
  `content_opacity` varchar(32) NOT NULL default '',
  `thumbnail_size` smallint(6) NOT NULL DEFAULT '200',
  `navigation_arrow` varchar(32) NOT NULL default '',
  `navigation_style` varchar(32) NOT NULL default '',
  `navigation_aposition` varchar(32) NOT NULL default '',
  `navigation_acolor` varchar(32) NOT NULL default '',
  `show_pagination` varchar(32) NOT NULL default '',
  `pagination_style` varchar(32) NOT NULL default '',
  `pagination_position` varchar(32) NOT NULL default '',
  `pagination_color` varchar(32) NOT NULL default '',
  `pagination_active` varchar(32) NOT NULL default '',
  `pagination_bar` varchar(32) NOT NULL default '',
  PRIMARY KEY (`slidergroup_id`)
) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci AUTO_INCREMENT=0;

CREATE TABLE IF NOT EXISTS {$this->getTable('responsivebannerslider_page')} (
		`page_id` int(11) unsigned NOT NULL auto_increment,
		`slidergroup_id` smallint(6) NOT NULL,
		`pages` smallint(6) NOT NULL,
		PRIMARY KEY (`page_id`)
	) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci AUTO_INCREMENT=0;

CREATE TABLE IF NOT EXISTS {$this->getTable('responsivebannerslider_category')} (
		`category_id` int(11) unsigned NOT NULL auto_increment,
		`slidergroup_id` smallint(6) NOT NULL,
		`category_ids` smallint(6) NOT NULL,
		PRIMARY KEY (`category_id`)
	) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci AUTO_INCREMENT=0;

CREATE TABLE IF NOT EXISTS {$this->getTable('responsivebannerslider_product')} (
		`product_id` int(11) unsigned NOT NULL auto_increment,
		`slidergroup_id` smallint(6) NOT NULL,
		`product_sku` varchar(255) NOT NULL default '',
		PRIMARY KEY (`product_id`)
	) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci AUTO_INCREMENT=0;

CREATE TABLE IF NOT EXISTS {$this->getTable('responsivebannerslider_store')} (
		`store_ids` int(11) unsigned NOT NULL auto_increment,
		`slidergroup_id` smallint(6) NOT NULL,
		`store_id` smallint(6) NOT NULL,
		PRIMARY KEY (`store_ids`)
	) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci AUTO_INCREMENT=0;

CREATE TABLE IF NOT EXISTS  {$this->getTable('responsivebannerslider_slide')} (
  `slide_id` int(11) unsigned NOT NULL auto_increment,
  `group_names` varchar(255) NOT NULL default '',
  `titles` varchar(255) NOT NULL default '',
  `img_video` varchar(32) NOT NULL default '',
  `img_hosting` tinyint(1) NOT NULL default '0',
  `video_id` varchar(255) NOT NULL default '',
  `hosted_url` varchar(512) NOT NULL default '',
  `hosted_thumb` varchar(512) NOT NULL default '',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `alt_text` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `url_target` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `date_enabled` tinyint(1) NOT NULL,
  `from_date` datetime DEFAULT NULL,
  `to_date` datetime DEFAULT NULL,
  `sort_order` smallint(6) NOT NULL default '1',
  `statuss` tinyint(1) NOT NULL default '1',
  PRIMARY KEY (`slide_id`)
) ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci AUTO_INCREMENT=0;
	
  "
);

$installer->endSetup(); 
