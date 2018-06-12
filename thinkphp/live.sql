
CREATE TABLE `live_team`(
    `id` tinyint(1) unsigned NOT NULL auto_increment,
    `name` VARCHAR(20) NOT NULL DEFAULT '',
    `image` VARCHAR(20) NOT NULL DEFAULT '',
    `type` tinyint(1) unsigned NOT NULL DEFAULT 0,
    `create_time` INT(10) unsigned NOT NULL DEFAULT 0,
    `update_time` INT(10) unsigned NOT NULL DEFAULT 0,
    PRIMARY KEY(`id`)
)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT charset=utf8 COMMENT '球队表';


CREATE TABLE `live_game`(
    `id` int(10) unsigned NOT NULL auto_increment,
    `a_id` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT 'a队id',
    `b_id` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT 'a队id',
    `a_score` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT 'a队得分',
    `b_score` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT 'b队得分',
    `narrator` VARCHAR(20) NOT NULL DEFAULT '' COMMENT '直播员',
    `image` VARCHAR(20) NOT NULL DEFAULT '',
    `start_time` INT(10) unsigned NOT NULL DEFAULT 0 COMMENT '开始时间',
    `status` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT '状态',
    `create_time` INT(10) unsigned NOT NULL DEFAULT 0,
    `update_time` INT(10) unsigned NOT NULL DEFAULT 0,
    PRIMARY KEY(`id`)
)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT charset=utf8 COMMENT '直播赛况表';


CREATE TABLE `live_player`(
    `id` int(10) unsigned NOT NULL auto_increment,
    `name` VARCHAR(20) NOT NULL DEFAULT '' COMMENT '球员名称',
    `image` VARCHAR(20) NOT NULL DEFAULT '',
    `age` tinyint(1) unsigned NOT NULL DEFAULT 0,
    `position` tinyint(1) unsigned NOT NULL DEFAULT 0,
    `status` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT '状态',
    `create_time` INT(10) unsigned NOT NULL DEFAULT 0,
    `update_time` INT(10) unsigned NOT NULL DEFAULT 0,
    PRIMARY KEY(`id`)
)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT charset=utf8 COMMENT '球员表';


CREATE TABLE `live_outs` (
    `id` int(10) unsigned NOT NULL auto_increment,
    `game_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '直播id',
    `team_id` tinyint(10) unsigned NOT NULL DEFAULT 0 COMMENT '球队id',
    `content` VARCHAR(200) NOT NULL DEFAULT '' COMMENT '赛事赛况的内容',
    `image` VARCHAR(20) NOT NULL DEFAULT '',
    `type` tinyint(1) unsigned NOT NULL DEFAULT 0,
    `status` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT '状态',
    `create_time` INT(10) unsigned NOT NULL DEFAULT 0,
    PRIMARY KEY(`id`)
)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT charset=utf8 COMMENT '赛况表';


CREATE TABLE `live_chart`(
    `id` int(10) unsigned NOT NULL auto_increment,
    `game_id` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '直播id',
    `user_id` tinyint(10) unsigned NOT NULL DEFAULT 0 COMMENT '用户id',
    `content` VARCHAR(200) NOT NULL DEFAULT '' COMMENT '赛事赛况的内容',
    `image` VARCHAR(20) NOT NULL DEFAULT '',
    `status` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT '状态',
    `create_time` INT(10) unsigned NOT NULL DEFAULT 0,
    PRIMARY KEY(`id`)
)ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT charset=utf8 COMMENT '聊天室表';
