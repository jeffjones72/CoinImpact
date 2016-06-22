-- 0001 create initial schema
CREATE TABLE `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(256) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `date_joined` datetime NOT NULL,
  `last_login` datetime NOT NULL,
  `facebook_id` varchar(64) DEFAULT NULL,
  `timezone` int(11) DEFAULT NULL,
  `is_staff` tinyint(1) NOT NULL,
  `is_dev` tinyint(1) NOT NULL DEFAULT '0',
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `invitation_account_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY ` invitation_account_id` (`invitation_account_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `achievements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `description` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `player_id` int(11) NOT NULL,
  `place_id` int(11) DEFAULT NULL,
  `new_place` tinyint(1) DEFAULT NULL,
  `progress` int(11) DEFAULT NULL,
  `combatant_id` int(11) DEFAULT NULL,
  `new_combatant` tinyint(1) DEFAULT NULL,
  `trader_id` int(11) DEFAULT NULL,
  `new_trader` tinyint(1) DEFAULT NULL,
  `boss_id` int(11) DEFAULT NULL,
  `new_boss` tinyint(1) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `damage` int(11) DEFAULT NULL,
  `critical_hit` tinyint(1) DEFAULT NULL,
  `fatal_hit` tinyint(1) DEFAULT NULL,
  `health` int(11) DEFAULT NULL,
  `credit` int(11) DEFAULT NULL,
  `experience` int(11) DEFAULT NULL,
  `energy` int(11) DEFAULT NULL,
  `stamina` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `actions_ea2d1965` (`player_id`),
  KEY `actions_c4391d6c` (`place_id`),
  KEY `actions_9e2c5802` (`combatant_id`),
  KEY `actions_aaae1032` (`trader_id`),
  KEY `actions_72f065a1` (`boss_id`),
  KEY `actions_e9b82f95` (`event_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `title` varchar(256) NOT NULL,
  `text` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `auth_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `auth_group_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `group_id` (`group_id`,`permission_id`),
  KEY `auth_group_permissions_bda51c3c` (`group_id`),
  KEY `auth_group_permissions_1e014c8f` (`permission_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `auth_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `content_type_id` int(11) NOT NULL,
  `codename` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `content_type_id` (`content_type_id`,`codename`),
  KEY `auth_permission_e4470c6e` (`content_type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `auth_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `email` varchar(75) NOT NULL,
  `password` varchar(128) NOT NULL,
  `is_staff` tinyint(1) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_superuser` tinyint(1) NOT NULL,
  `last_login` datetime NOT NULL,
  `date_joined` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `auth_user_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`group_id`),
  KEY `auth_user_groups_fbfc09f1` (`user_id`),
  KEY `auth_user_groups_bda51c3c` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `auth_user_user_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`permission_id`),
  KEY `auth_user_user_permissions_fbfc09f1` (`user_id`),
  KEY `auth_user_user_permissions_1e014c8f` (`permission_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `boosts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `release_id` int(11) NOT NULL,
  `rarity_id` int(11) NOT NULL,
  `limit` int(11) DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  `description` longtext,
  `price` int(11) DEFAULT NULL,
  `premium_price` int(11) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `from_combatants` tinyint(1) NOT NULL,
  `from_bosses` tinyint(1) NOT NULL,
  `from_store` tinyint(1) NOT NULL,
  `from_cache` tinyint(1) NOT NULL,
  `automatic` tinyint(1) NOT NULL,
  `duration` int(11) DEFAULT NULL,
  `skill` int(11) DEFAULT NULL,
  `death` int(11) DEFAULT NULL,
  `stamina_refill` tinyint(1) NOT NULL,
  `energy_refill` tinyint(1) NOT NULL,
  `health_refill` tinyint(1) NOT NULL,
  `durability_refill` tinyint(1) NOT NULL,
  `attack_multiplier` double DEFAULT NULL,
  `defense_multiplier` double DEFAULT NULL,
  `regeneration_multiplier` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `boosts_b827d594` (`release_id`),
  KEY `boosts_97f21689` (`rarity_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `bosses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `description` longtext,
  `health` int(11) DEFAULT NULL,
  `attack` int(11) NOT NULL,
  `defense` int(11) NOT NULL,
  `timeout` int(11) DEFAULT NULL,
  `experience_reward` int(11) NOT NULL,
  `credit_reward` int(11) NOT NULL,
  `max_players` int(11) NOT NULL,
  `type` enum('human','creature','vehicle') NOT NULL,
  `cost` int(11) NOT NULL,
  `next_summon` int(11) NOT NULL,
  `skulls` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `max_awards_at_dmg` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `bosses_required_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `boss_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `boss_id` (`boss_id`,`item_id`),
  KEY `bosses_required_items_72f065a1` (`boss_id`),
  KEY `bosses_required_items_67b70d25` (`item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `bosses_required_missions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `boss_id` int(11) NOT NULL,
  `mission_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `boss_id` (`boss_id`,`mission_id`),
  KEY `bosses_required_missions_72f065a1` (`boss_id`),
  KEY `bosses_required_missions_f0bc9b8d` (`mission_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `bosses_required_places` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `boss_id` int(11) NOT NULL,
  `place_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `boss_id` (`boss_id`,`place_id`),
  KEY `bosses_required_places_72f065a1` (`boss_id`),
  KEY `bosses_required_places_c4391d6c` (`place_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `boss_combat_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `damage_to_boss` int(11) NOT NULL,
  `damage_to_player` int(11) NOT NULL,
  `time` datetime NOT NULL,
  `player_boss_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `boss_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `boss_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `boss_kill_counts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `boss_id` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `boss_things` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `boss_id` int(11) NOT NULL,
  `thing_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `caches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `release_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` longtext,
  `premium_price` int(11) NOT NULL,
  `featured` bit(1) DEFAULT b'0',
  PRIMARY KEY (`id`),
  KEY `caches_b827d594` (`release_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `caches_allowed_boosts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cache_id` int(11) NOT NULL,
  `boost_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cache_id` (`cache_id`,`boost_id`),
  KEY `caches_allowed_boosts_f9d3b7c3` (`cache_id`),
  KEY `caches_allowed_boosts_578f24dc` (`boost_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `caches_allowed_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cache_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cache_id` (`cache_id`,`item_id`),
  KEY `caches_allowed_items_f9d3b7c3` (`cache_id`),
  KEY `caches_allowed_items_67b70d25` (`item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `caches_allowed_item_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cache_id` int(11) NOT NULL,
  `itemsection_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cache_id` (`cache_id`,`itemsection_id`),
  KEY `caches_allowed_item_sections_f9d3b7c3` (`cache_id`),
  KEY `caches_allowed_item_sections_f3a7a405` (`itemsection_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `caches_allowed_modifiers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cache_id` int(11) NOT NULL,
  `modifier_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cache_id` (`cache_id`,`modifier_id`),
  KEY `caches_allowed_modifiers_f9d3b7c3` (`cache_id`),
  KEY `caches_allowed_modifiers_b6a11c53` (`modifier_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `caches_allowed_things` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cache_id` int(11) NOT NULL,
  `thing_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cache_id` (`cache_id`,`thing_id`),
  KEY `caches_allowed_things_f9d3b7c3` (`cache_id`),
  KEY `caches_allowed_things_b95e4ba9` (`thing_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `caches_forbidden_boosts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cache_id` int(11) NOT NULL,
  `boost_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cache_id` (`cache_id`,`boost_id`),
  KEY `caches_forbidden_boosts_f9d3b7c3` (`cache_id`),
  KEY `caches_forbidden_boosts_578f24dc` (`boost_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `caches_forbidden_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cache_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cache_id` (`cache_id`,`item_id`),
  KEY `caches_forbidden_items_f9d3b7c3` (`cache_id`),
  KEY `caches_forbidden_items_67b70d25` (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `caches_forbidden_item_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cache_id` int(11) NOT NULL,
  `itemsection_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cache_id` (`cache_id`,`itemsection_id`),
  KEY `caches_forbidden_item_sections_f9d3b7c3` (`cache_id`),
  KEY `caches_forbidden_item_sections_f3a7a405` (`itemsection_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `caches_forbidden_modifiers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cache_id` int(11) NOT NULL,
  `modifier_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cache_id` (`cache_id`,`modifier_id`),
  KEY `caches_forbidden_modifiers_f9d3b7c3` (`cache_id`),
  KEY `caches_forbidden_modifiers_b6a11c53` (`modifier_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `caches_forbidden_things` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cache_id` int(11) NOT NULL,
  `thing_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cache_id` (`cache_id`,`thing_id`),
  KEY `caches_forbidden_things_f9d3b7c3` (`cache_id`),
  KEY `caches_forbidden_things_b95e4ba9` (`thing_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `combatants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `release_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` longtext,
  `rarity_id` int(11) NOT NULL,
  `limit` int(11) DEFAULT NULL,
  `section_id` int(11) NOT NULL,
  `health` int(11) NOT NULL,
  `attack` int(11) NOT NULL,
  `defense` int(11) NOT NULL,
  `dodge` int(11) NOT NULL,
  `strike` int(11) NOT NULL,
  `minimum_objects` int(11) DEFAULT NULL,
  `maximum_objects` int(11) DEFAULT NULL,
  `items_ratio` double DEFAULT NULL,
  `things_ratio` double DEFAULT NULL,
  `experience_reward` int(11) DEFAULT NULL,
  `credit_reward` int(11) DEFAULT NULL,
  `place_progress` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `combatants_b827d594` (`release_id`),
  KEY `combatants_97f21689` (`rarity_id`),
  KEY `combatants_c007bd5a` (`section_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `combatants_allowed_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `combatant_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `combatant_id` (`combatant_id`,`item_id`),
  KEY `combatants_allowed_items_9e2c5802` (`combatant_id`),
  KEY `combatants_allowed_items_67b70d25` (`item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `combatants_allowed_item_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `combatant_id` int(11) NOT NULL,
  `itemsection_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `combatant_id` (`combatant_id`,`itemsection_id`),
  KEY `combatants_allowed_item_sections_9e2c5802` (`combatant_id`),
  KEY `combatants_allowed_item_sections_f3a7a405` (`itemsection_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `combatants_allowed_things` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `combatant_id` int(11) NOT NULL,
  `thing_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `combatant_id` (`combatant_id`,`thing_id`),
  KEY `combatants_allowed_things_9e2c5802` (`combatant_id`),
  KEY `combatants_allowed_things_b95e4ba9` (`thing_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `combatants_forbidden_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `combatant_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `combatant_id` (`combatant_id`,`item_id`),
  KEY `combatants_forbidden_items_9e2c5802` (`combatant_id`),
  KEY `combatants_forbidden_items_67b70d25` (`item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `combatants_forbidden_item_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `combatant_id` int(11) NOT NULL,
  `itemsection_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `combatant_id` (`combatant_id`,`itemsection_id`),
  KEY `combatants_forbidden_item_sections_9e2c5802` (`combatant_id`),
  KEY `combatants_forbidden_item_sections_f3a7a405` (`itemsection_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `combatants_forbidden_things` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `combatant_id` int(11) NOT NULL,
  `thing_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `combatant_id` (`combatant_id`,`thing_id`),
  KEY `combatants_forbidden_things_9e2c5802` (`combatant_id`),
  KEY `combatants_forbidden_things_b95e4ba9` (`thing_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `combatant_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `description` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `django_admin_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action_time` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `content_type_id` int(11) DEFAULT NULL,
  `object_id` longtext,
  `object_repr` varchar(200) NOT NULL,
  `action_flag` smallint(5) unsigned NOT NULL,
  `change_message` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `django_admin_log_fbfc09f1` (`user_id`),
  KEY `django_admin_log_e4470c6e` (`content_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `django_content_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `app_label` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_label` (`app_label`,`model`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `django_session` (
  `session_key` varchar(40) NOT NULL,
  `session_data` longtext NOT NULL,
  `expire_date` datetime NOT NULL,
  PRIMARY KEY (`session_key`),
  KEY `django_session_c25c2c28` (`expire_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_id` int(11) NOT NULL,
  `release_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` longtext,
  `rarity_id` int(11) NOT NULL,
  `limit` int(11) DEFAULT NULL,
  `damage` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `events_c007bd5a` (`section_id`),
  KEY `events_b827d594` (`release_id`),
  KEY `events_97f21689` (`rarity_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `events_places_blacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `place_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_id` (`event_id`,`place_id`),
  KEY `events_places_blacklist_e9b82f95` (`event_id`),
  KEY `events_places_blacklist_c4391d6c` (`place_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `events_places_whitelist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `place_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_id` (`event_id`,`place_id`),
  KEY `events_places_whitelist_e9b82f95` (`event_id`),
  KEY `events_places_whitelist_c4391d6c` (`place_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `events_zones_blacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `zone_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_id` (`event_id`,`zone_id`),
  KEY `events_zones_blacklist_e9b82f95` (`event_id`),
  KEY `events_zones_blacklist_2957a812` (`zone_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `events_zones_whitelist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `zone_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_id` (`event_id`,`zone_id`),
  KEY `events_zones_whitelist_e9b82f95` (`event_id`),
  KEY `events_zones_whitelist_2957a812` (`zone_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `event_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `description` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `intro_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `page` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `release_id` int(11) NOT NULL,
  `rarity_id` int(11) NOT NULL,
  `limit` int(11) DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  `description` longtext,
  `price` int(11) DEFAULT NULL,
  `premium_price` int(11) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `from_combatants` tinyint(1) NOT NULL,
  `from_bosses` tinyint(1) NOT NULL,
  `from_store` tinyint(1) NOT NULL,
  `from_cache` tinyint(1) NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `has_quality` tinyint(1) NOT NULL,
  `weight` int(11) NOT NULL,
  `attack` int(11) DEFAULT NULL,
  `defense` int(11) DEFAULT NULL,
  `energy` int(11) DEFAULT NULL,
  `stamina` int(11) DEFAULT NULL,
  `health` int(11) DEFAULT NULL,
  `strike` int(11) DEFAULT NULL,
  `strike_boost` int(11) DEFAULT NULL,
  `damage_boost` int(11) DEFAULT NULL,
  `luck` int(11) DEFAULT NULL,
  `dodge` int(11) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `modifiers_limit` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `items_b827d594` (`release_id`),
  KEY `items_97f21689` (`rarity_id`),
  KEY `items_c007bd5a` (`section_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `item_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `description` longtext,
  `durability_decrease` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `levels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `experience` int(11) NOT NULL,
  `skill` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `missions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `description` longtext,
  `tabindex` int(11) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `experience_reward` int(11) DEFAULT NULL,
  `credit_reward` int(11) DEFAULT NULL,
  `skill_reward` int(11) DEFAULT NULL,
  `minimum_items` int(11) DEFAULT NULL,
  `unique_items` tinyint(1) NOT NULL,
  `minimum_things` int(11) DEFAULT NULL,
  `unique_things` tinyint(1) NOT NULL,
  `minimum_combatants` int(11) DEFAULT NULL,
  `unique_combatants` tinyint(1) NOT NULL,
  `minimum_events` int(11) DEFAULT NULL,
  `unique_events` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `missions_next_places` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mission_id` int(11) NOT NULL,
  `place_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mission_id` (`mission_id`,`place_id`),
  KEY `missions_next_places_f0bc9b8d` (`mission_id`),
  KEY `missions_next_places_c4391d6c` (`place_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `missions_required_combatants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mission_id` int(11) NOT NULL,
  `combatant_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mission_id` (`mission_id`,`combatant_id`),
  KEY `missions_required_combatants_f0bc9b8d` (`mission_id`),
  KEY `missions_required_combatants_9e2c5802` (`combatant_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `missions_required_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mission_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mission_id` (`mission_id`,`event_id`),
  KEY `missions_required_events_f0bc9b8d` (`mission_id`),
  KEY `missions_required_events_e9b82f95` (`event_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `missions_required_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mission_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mission_id` (`mission_id`,`item_id`),
  KEY `missions_required_items_f0bc9b8d` (`mission_id`),
  KEY `missions_required_items_67b70d25` (`item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `missions_required_things` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mission_id` int(11) NOT NULL,
  `thing_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mission_id` (`mission_id`,`thing_id`),
  KEY `missions_required_things_f0bc9b8d` (`mission_id`),
  KEY `missions_required_things_b95e4ba9` (`thing_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `modifiers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `release_id` int(11) NOT NULL,
  `rarity_id` int(11) NOT NULL,
  `limit` int(11) DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  `description` longtext,
  `price` int(11) DEFAULT NULL,
  `premium_price` int(11) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `from_combatants` tinyint(1) NOT NULL,
  `from_bosses` tinyint(1) NOT NULL,
  `from_store` tinyint(1) NOT NULL,
  `from_cache` tinyint(1) NOT NULL,
  `has_quality` tinyint(1) NOT NULL,
  `attack` int(11) DEFAULT NULL,
  `defense` int(11) DEFAULT NULL,
  `energy` int(11) DEFAULT NULL,
  `stamina` int(11) DEFAULT NULL,
  `health` int(11) DEFAULT NULL,
  `strike` int(11) DEFAULT NULL,
  `strike_boost` int(11) DEFAULT NULL,
  `damage_boost` int(11) DEFAULT NULL,
  `luck` int(11) DEFAULT NULL,
  `dodge` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `modifiers_b827d594` (`release_id`),
  KEY `modifiers_97f21689` (`rarity_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `modifiers_items_blacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modifier_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `modifier_id` (`modifier_id`,`item_id`),
  KEY `modifiers_items_blacklist_b6a11c53` (`modifier_id`),
  KEY `modifiers_items_blacklist_67b70d25` (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `modifiers_items_whitelist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modifier_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `modifier_id` (`modifier_id`,`item_id`),
  KEY `modifiers_items_whitelist_b6a11c53` (`modifier_id`),
  KEY `modifiers_items_whitelist_67b70d25` (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `modifiers_sections_blacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modifier_id` int(11) NOT NULL,
  `itemsection_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `modifier_id` (`modifier_id`,`itemsection_id`),
  KEY `modifiers_sections_blacklist_b6a11c53` (`modifier_id`),
  KEY `modifiers_sections_blacklist_f3a7a405` (`itemsection_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `modifiers_sections_whitelist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modifier_id` int(11) NOT NULL,
  `itemsection_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `modifier_id` (`modifier_id`,`itemsection_id`),
  KEY `modifiers_sections_whitelist_b6a11c53` (`modifier_id`),
  KEY `modifiers_sections_whitelist_f3a7a405` (`itemsection_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `places` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `description` longtext,
  `zone_id` int(11) NOT NULL,
  `x` int(11) DEFAULT NULL,
  `y` int(11) DEFAULT NULL,
  `z` int(11) DEFAULT NULL,
  `release_id` int(11) NOT NULL,
  `energy` int(11) NOT NULL,
  `max_active_combatants` int(11) NOT NULL,
  `max_active_traders` int(11) NOT NULL,
  `tabindex` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `places_2957a812` (`zone_id`),
  KEY `places_b827d594` (`release_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `places_allowed_combatants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `place_id` int(11) NOT NULL,
  `combatant_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `place_id` (`place_id`,`combatant_id`),
  KEY `places_allowed_combatants_c4391d6c` (`place_id`),
  KEY `places_allowed_combatants_9e2c5802` (`combatant_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `places_allowed_combatant_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `place_id` int(11) NOT NULL,
  `combatantsection_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `place_id` (`place_id`,`combatantsection_id`),
  KEY `places_allowed_combatant_sections_c4391d6c` (`place_id`),
  KEY `places_allowed_combatant_sections_7e0877ac` (`combatantsection_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `places_allowed_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `place_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `place_id` (`place_id`,`event_id`),
  KEY `places_allowed_events_c4391d6c` (`place_id`),
  KEY `places_allowed_events_e9b82f95` (`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `places_allowed_event_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `place_id` int(11) NOT NULL,
  `eventsection_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `place_id` (`place_id`,`eventsection_id`),
  KEY `places_allowed_event_sections_c4391d6c` (`place_id`),
  KEY `places_allowed_event_sections_6a3fcba3` (`eventsection_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `places_allowed_traders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `place_id` int(11) NOT NULL,
  `trader_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `place_id` (`place_id`,`trader_id`),
  KEY `places_allowed_traders_c4391d6c` (`place_id`),
  KEY `places_allowed_traders_aaae1032` (`trader_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `places_allowed_trader_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `place_id` int(11) NOT NULL,
  `tradersection_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `place_id` (`place_id`,`tradersection_id`),
  KEY `places_allowed_trader_sections_c4391d6c` (`place_id`),
  KEY `places_allowed_trader_sections_37c02d24` (`tradersection_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `places_forbidden_combatants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `place_id` int(11) NOT NULL,
  `combatant_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `place_id` (`place_id`,`combatant_id`),
  KEY `places_forbidden_combatants_c4391d6c` (`place_id`),
  KEY `places_forbidden_combatants_9e2c5802` (`combatant_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `places_forbidden_combatant_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `place_id` int(11) NOT NULL,
  `combatantsection_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `place_id` (`place_id`,`combatantsection_id`),
  KEY `places_forbidden_combatant_sections_c4391d6c` (`place_id`),
  KEY `places_forbidden_combatant_sections_7e0877ac` (`combatantsection_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `places_forbidden_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `place_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `place_id` (`place_id`,`event_id`),
  KEY `places_forbidden_events_c4391d6c` (`place_id`),
  KEY `places_forbidden_events_e9b82f95` (`event_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `places_forbidden_event_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `place_id` int(11) NOT NULL,
  `eventsection_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `place_id` (`place_id`,`eventsection_id`),
  KEY `places_forbidden_event_sections_c4391d6c` (`place_id`),
  KEY `places_forbidden_event_sections_6a3fcba3` (`eventsection_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `places_forbidden_traders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `place_id` int(11) NOT NULL,
  `trader_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `place_id` (`place_id`,`trader_id`),
  KEY `places_forbidden_traders_c4391d6c` (`place_id`),
  KEY `places_forbidden_traders_aaae1032` (`trader_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `places_forbidden_trader_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `place_id` int(11) NOT NULL,
  `tradersection_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `place_id` (`place_id`,`tradersection_id`),
  KEY `places_forbidden_trader_sections_c4391d6c` (`place_id`),
  KEY `places_forbidden_trader_sections_37c02d24` (`tradersection_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `players` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `account_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `attack` int(11) NOT NULL,
  `defense` int(11) NOT NULL,
  `energy` int(11) NOT NULL,
  `energy_limit` int(11) NOT NULL,
  `stamina` int(11) NOT NULL,
  `stamina_limit` int(11) NOT NULL,
  `health` int(11) NOT NULL,
  `health_limit` int(11) NOT NULL,
  `skill` int(11) NOT NULL,
  `damage_boost` int(11) NOT NULL,
  `strike` int(11) NOT NULL,
  `strike_boost` int(11) NOT NULL,
  `luck` int(11) NOT NULL,
  `dodge` int(11) NOT NULL,
  `experience` int(11) NOT NULL,
  `level_id` int(11) NOT NULL,
  `balance` int(11) NOT NULL,
  `premium_balance` int(11) NOT NULL,
  `speciality_id` int(11) DEFAULT NULL,
  `rank_id` int(11) NOT NULL,
  `path` int(11) NOT NULL,
  `user_agent` varchar(256) NOT NULL,
  `remote_address` varchar(64) NOT NULL,
  `location_id` int(11) DEFAULT NULL,
  `energy_rate` int(11) NOT NULL,
  `health_rate` int(11) NOT NULL,
  `stamina_rate` int(11) NOT NULL,
  `energy_refill` datetime NOT NULL,
  `health_refill` datetime NOT NULL,
  `stamina_refill` datetime NOT NULL,
  `passed_intro` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `players_6f2fe10e` (`account_id`),
  KEY `players_cbf42d71` (`level_id`),
  KEY `players_5f1df27f` (`speciality_id`),
  KEY `players_845cd956` (`rank_id`),
  KEY `players_319d859` (`location_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `player_available_bosses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `boss_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `player_boosts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `boost_id` int(11) NOT NULL,
  `collected` datetime NOT NULL,
  `applied` datetime DEFAULT NULL,
  `expires` datetime DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `player_boosts_ea2d1965` (`player_id`),
  KEY `player_boosts_578f24dc` (`boost_id`),
  KEY `player_boosts_67b70d25` (`item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `player_bosses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `boss_id` int(11) NOT NULL,
  `generated` datetime NOT NULL,
  `health` int(11) DEFAULT NULL,
  `completed` datetime DEFAULT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT '0',
  `share_id` varchar(256) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `player_bosses_ea2d1965` (`player_id`),
  KEY `player_bosses_72f065a1` (`boss_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `player_boss_combatants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `player_boss_id` int(11) NOT NULL,
  `is_current` tinyint(1) NOT NULL DEFAULT '0',
  `damage` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `player_boss_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_boss_combatant_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `mod_atk` int(11) NOT NULL,
  `mod_def` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

CREATE TABLE `player_boss_things` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_boss_combatant_id` int(11) NOT NULL,
  `thing_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `player_combatants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `combatant_id` int(11) NOT NULL,
  `place_id` int(11) NOT NULL,
  `generated` datetime NOT NULL,
  `health` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `completed` datetime DEFAULT NULL,
  `fighting` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `player_combatants_9e2c5802` (`combatant_id`),
  KEY `player_combatants_c4391d6c` (`place_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `player_combatant_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `combatant_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quality` int(11) DEFAULT NULL,
  `durability` double NOT NULL,
  `mod_atk` int(11) DEFAULT NULL,
  `mod_def` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `player_combatant_items_9e2c5802` (`combatant_id`),
  KEY `player_combatant_items_67b70d25` (`item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `player_combatant_things` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `combatant_id` int(11) NOT NULL,
  `thing_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `player_combatant_things_9e2c5802` (`combatant_id`),
  KEY `player_combatant_things_b95e4ba9` (`thing_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `player_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `place_id` int(11) NOT NULL,
  `generated` datetime NOT NULL,
  `completed` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `player_events_e9b82f95` (`event_id`),
  KEY `player_events_c4391d6c` (`place_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `player_friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `friend_id` int(11) NOT NULL,
  `added` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `player_friends_ea2d1965` (`player_id`),
  KEY `player_friends_a233848e` (`friend_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `player_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `slot_id` int(11) DEFAULT NULL,
  `collected` datetime NOT NULL,
  `quality` int(11) DEFAULT '1',
  `durability` double NOT NULL DEFAULT '100',
  `mod_atk` int(11) DEFAULT NULL,
  `mod_def` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `player_items_ea2d1965` (`player_id`),
  KEY `player_items_67b70d25` (`item_id`),
  KEY `player_items_153c935c` (`slot_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `player_missions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `player_id` int(11) NOT NULL,
  `mission_id` int(11) NOT NULL,
  `started` datetime DEFAULT NULL,
  `completed` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `player_missions_ea2d1965` (`player_id`),
  KEY `player_missions_f0bc9b8d` (`mission_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `player_modifiers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `modifier_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `collected` datetime NOT NULL,
  `quality` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `player_modifiers_ea2d1965` (`player_id`),
  KEY `player_modifiers_b6a11c53` (`modifier_id`),
  KEY `player_modifiers_67b70d25` (`item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `player_places` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `player_id` int(11) NOT NULL,
  `place_id` int(11) NOT NULL,
  `progress` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Allow specification of location.  No need to parse actions table to get current location.\n\nJJ',
  PRIMARY KEY (`id`),
  KEY `player_places_ea2d1965` (`player_id`),
  KEY `player_places_c4391d6c` (`place_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `player_things` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `thing_id` int(11) NOT NULL,
  `collected` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `player_things_ea2d1965` (`player_id`),
  KEY `player_things_b95e4ba9` (`thing_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `player_traders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trader_id` int(11) NOT NULL,
  `place_id` int(11) NOT NULL,
  `generated` datetime NOT NULL,
  `active` tinyint(1) NOT NULL,
  `completed` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `player_traders_aaae1032` (`trader_id`),
  KEY `player_traders_c4391d6c` (`place_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `player_trader_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trader_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quality` int(11) DEFAULT NULL,
  `durability` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `player_trader_items_aaae1032` (`trader_id`),
  KEY `player_trader_items_67b70d25` (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `player_trader_things` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trader_id` int(11) NOT NULL,
  `thing_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `player_trader_things_aaae1032` (`trader_id`),
  KEY `player_trader_things_b95e4ba9` (`thing_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `ranks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `label` varchar(64) NOT NULL,
  `description` longtext,
  `service_id` int(11) NOT NULL,
  `grade` int(11) NOT NULL,
  `rank` int(11) NOT NULL,
  `path0` int(11) DEFAULT NULL,
  `path1` int(11) DEFAULT NULL,
  `path2` int(11) DEFAULT NULL,
  `stamina` int(11) NOT NULL,
  `attack` int(11) NOT NULL,
  `defense` int(11) NOT NULL,
  `strike` int(11) NOT NULL,
  `dodge` int(11) NOT NULL,
  `luck` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ranks_90e28c3e` (`service_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `rarities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `description` longtext,
  `threshold` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `releases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `description` longtext,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level_id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `description` longtext,
  `label` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `services_cbf42d71` (`level_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `slots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `description` longtext,
  `weight_limit` int(11) DEFAULT NULL,
  `x` int(11) DEFAULT NULL,
  `y` int(11) DEFAULT NULL,
  `z` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `slots_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slot_id` int(11) NOT NULL,
  `itemsection_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slot_id` (`slot_id`,`itemsection_id`),
  KEY `slots_sections_153c935c` (`slot_id`),
  KEY `slots_sections_f3a7a405` (`itemsection_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `specialities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` longtext,
  `attack` int(11) NOT NULL,
  `defense` int(11) NOT NULL,
  `strike` int(11) NOT NULL,
  `dodge` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `specialities_cbf42d71` (`level_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `things` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `release_id` int(11) NOT NULL,
  `rarity_id` int(11) NOT NULL,
  `limit` int(11) DEFAULT NULL,
  `name` varchar(64) NOT NULL,
  `description` longtext,
  `price` int(11) DEFAULT NULL,
  `premium_price` int(11) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `from_combatants` tinyint(1) NOT NULL,
  `from_bosses` tinyint(1) NOT NULL,
  `from_store` tinyint(1) NOT NULL,
  `from_cache` tinyint(1) NOT NULL,
  `partial_item_id` int(11) DEFAULT NULL,
  `gift` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `things_b827d594` (`release_id`),
  KEY `things_97f21689` (`rarity_id`),
  KEY `things_6812fcf` (`partial_item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `traders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `release_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` longtext,
  `rarity_id` int(11) NOT NULL,
  `limit` int(11) DEFAULT NULL,
  `section_id` int(11) NOT NULL,
  `cost` int(11) DEFAULT NULL,
  `minimum_objects` int(11) DEFAULT NULL,
  `maximum_objects` int(11) DEFAULT NULL,
  `items_ratio` double DEFAULT NULL,
  `things_ratio` double DEFAULT NULL,
  `experience_reward` int(11) DEFAULT NULL,
  `credit_reward` int(11) DEFAULT NULL,
  `place_progress` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `traders_b827d594` (`release_id`),
  KEY `traders_97f21689` (`rarity_id`),
  KEY `traders_c007bd5a` (`section_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `traders_allowed_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trader_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trader_id` (`trader_id`,`item_id`),
  KEY `traders_allowed_items_aaae1032` (`trader_id`),
  KEY `traders_allowed_items_67b70d25` (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `traders_allowed_item_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trader_id` int(11) NOT NULL,
  `itemsection_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trader_id` (`trader_id`,`itemsection_id`),
  KEY `traders_allowed_item_sections_aaae1032` (`trader_id`),
  KEY `traders_allowed_item_sections_f3a7a405` (`itemsection_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `traders_allowed_things` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trader_id` int(11) NOT NULL,
  `thing_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trader_id` (`trader_id`,`thing_id`),
  KEY `traders_allowed_things_aaae1032` (`trader_id`),
  KEY `traders_allowed_things_b95e4ba9` (`thing_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `traders_forbidden_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trader_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trader_id` (`trader_id`,`item_id`),
  KEY `traders_forbidden_items_aaae1032` (`trader_id`),
  KEY `traders_forbidden_items_67b70d25` (`item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `traders_forbidden_item_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trader_id` int(11) NOT NULL,
  `itemsection_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trader_id` (`trader_id`,`itemsection_id`),
  KEY `traders_forbidden_item_sections_aaae1032` (`trader_id`),
  KEY `traders_forbidden_item_sections_f3a7a405` (`itemsection_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `traders_forbidden_things` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trader_id` int(11) NOT NULL,
  `thing_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trader_id` (`trader_id`,`thing_id`),
  KEY `traders_forbidden_things_aaae1032` (`trader_id`),
  KEY `traders_forbidden_things_b95e4ba9` (`thing_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `trader_sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `description` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `zones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `description` longtext,
  `x` int(11) DEFAULT NULL,
  `y` int(11) DEFAULT NULL,
  `z` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

