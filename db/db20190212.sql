

-- ----------------------------
-- 活动表
-- ----------------------------
CREATE TABLE `db_activity` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '活动名传',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '活动类型',
  `number` varchar(32) NOT NULL DEFAULT '' COMMENT '活动id(申请用唯一标识)',
  `start_at` int(11) NOT NULL DEFAULT '0' COMMENT '活动开始时间',
  `end_at` int(11) NOT NULL DEFAULT '0' COMMENT '活动结束时间',
  `group_id` varchar(32) DEFAULT '' COMMENT '活动范围',
  `gorup_title` varchar(128) DEFAULT '' COMMENT '活动范围(分组标题)',
  `number_daily` tinyint(1) NOT NULL DEFAULT '0' COMMENT '用户每日可抽奖次数',
  `number_total` tinyint(3) NOT NULL DEFAULT '0' COMMENT '用户可抽奖总次数',
  `new` tinyint(1) NOT NULL COMMENT '是否新人专享1是2否',
  `new_daylimit` tinyint(1) DEFAULT '0' COMMENT '新人天数设置(多少天内注册有效)',
  `rule` text COMMENT '活动规则',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '活动状态1审批中2待配置3即将开始4进行中5已下架6已失效',
  `level` varchar(32) DEFAULT NULL COMMENT '自动失效等级',
  `time` varchar(255) DEFAULT '' COMMENT '活动时间段',
  `desc` varchar(255) DEFAULT '' COMMENT '备注',
  `participants` int(11) DEFAULT '0' COMMENT '参与人次',
  `free_resource` tinyint(1) NOT NULL DEFAULT '2' COMMENT '是否已释放资源1已释放2未释放',
  `is_award` tinyint(1) NOT NULL DEFAULT '2' COMMENT '是否已添加奖品1是2否',
  `deleted` tinyint(1) NOT NULL DEFAULT '2' COMMENT '是否被删除1是2否',
  `create_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) NOT NULL DEFAULT '0' COMMENT '更细时间',
  PRIMARY KEY (`id`),
  KEY `idx_number` (`number`),
  KEY `idx_new` (`new`),
  KEY `idx_status` (`status`),
  KEY `idx_name` (`name`(10))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='活动表';

-- ----------------------------
-- 活动奖品表
-- ----------------------------
CREATE TABLE `db_activity_award` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动id',
  `activity_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '活动类型',
  `award_id` int(11) NOT NULL DEFAULT '0' COMMENT '奖品id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '奖品名传',
  `level` tinyint(1) DEFAULT '0' COMMENT '奖品级别',
  `category_id` int(11) DEFAULT '0' COMMENT '奖品类型id',
  `category` varchar(128) DEFAULT '' COMMENT '奖品类型',
  `number` int(11) NOT NULL DEFAULT '0' COMMENT '奖品数量/金额',
  `resource_number` float(11,2) DEFAULT '0.00' COMMENT '奖品资源比例',
  `used_number` int(11) DEFAULT '0' COMMENT '已使用',
  `personal_daily_number` int(11) DEFAULT '0' COMMENT '单个用户每日中奖数量上限',
  `all_daily_number` int(11) DEFAULT '0' COMMENT '奖品每日中奖数量上限',
  `rate` varchar(64) NOT NULL DEFAULT '' COMMENT '中奖概率',
  `create_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `version` int(11) DEFAULT '0' COMMENT '更新版本号',
  PRIMARY KEY (`id`),
  KEY `idx_activity_id` (`activity_id`),
  KEY `idx-activity_type` (`activity_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='活动奖品表';

-- ----------------------------
-- 管理员表
-- ----------------------------
CREATE TABLE `db_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `role_uuid` varchar(128) DEFAULT '0' COMMENT '彩之云-角色uuid',
  `role_name` varchar(128) DEFAULT NULL COMMENT '角色名称',
  `username` varchar(128) DEFAULT '' COMMENT '用户名',
  `nickname` varchar(128) DEFAULT '' COMMENT '昵称',
  `token` varchar(64) DEFAULT '' COMMENT 'Token',
  `token_over_at` int(11) DEFAULT '0' COMMENT 'Token结束时间',
  `status` tinyint(4) DEFAULT '0' COMMENT '状态 1：正常 2：禁用',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_role_uuid` (`role_uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='账户表';

-- ----------------------------
-- 奖品管理
-- ----------------------------
CREATE TABLE `db_award` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动id',
  `activity_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '活动类型',
  `activity_name` varchar(255) DEFAULT '' COMMENT '活动名称',
  `resource_id` int(11) NOT NULL DEFAULT '0' COMMENT '奖品资源id',
  `resource_name` varchar(255) DEFAULT '' COMMENT '资源名称',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '奖品名传',
  `image` varchar(255) DEFAULT NULL COMMENT '图片',
  `category_id` int(11) DEFAULT '0' COMMENT '奖品类型id',
  `category_name` varchar(255) DEFAULT '' COMMENT '资源类型名称',
  `resource_number` float(11,2) NOT NULL DEFAULT '0.00' COMMENT '单个奖品需要的资源数量',
  `award_number` int(11) NOT NULL DEFAULT '0' COMMENT '奖品数量/金额',
  `used_number` int(11) DEFAULT '0' COMMENT '已发放',
  `status` tinyint(1) DEFAULT '0' COMMENT '奖品状态1正常2禁用',
  `create_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_activity_id` (`activity_id`),
  KEY `idx-activity_type` (`activity_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='奖品表';

-- ----------------------------
-- 活动奖品变更记录
-- ----------------------------
CREATE TABLE `db_award_change_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动id',
  `activity_award_id` int(11) NOT NULL DEFAULT '0' COMMENT '奖品id',
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '奖品名传',
  `field` varchar(128) DEFAULT '0' COMMENT '变更字段',
  `before` varchar(128) DEFAULT '0' COMMENT '变更前',
  `after` varchar(128) DEFAULT '' COMMENT '变更后',
  `operator` varchar(255) DEFAULT NULL COMMENT '操作人',
  `create_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_activity_id` (`activity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='活动奖品变更记录表';

-- ----------------------------
-- 抽奖资格表
-- ----------------------------
CREATE TABLE `db_lottery_qualifications` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `activity_number` varchar(32) NOT NULL DEFAULT '' COMMENT '活动ID',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `status` varchar(255) NOT NULL DEFAULT '0' COMMENT '是否已使用1是2否',
  `create_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_activity_number` (`activity_number`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='抽奖资格';

-- ----------------------------
-- 抽奖记录表
-- ----------------------------
CREATE TABLE `db_lottery_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `activity_id` varchar(255) NOT NULL DEFAULT '' COMMENT '活动id(活动主键/序号)',
  `activity_name` varchar(255) DEFAULT '0' COMMENT '活动名称',
  `activity_number` varchar(255) DEFAULT '' COMMENT '活动ID(申请标识)',
  `user_id` int(11) DEFAULT NULL COMMENT '抽奖人id',
  `user_name` varchar(64) DEFAULT '0' COMMENT '抽奖人姓名',
  `user_mobile` varchar(32) DEFAULT '0' COMMENT '抽奖人手机号',
  `user_address` varchar(255) DEFAULT NULL COMMENT '抽奖人地址',
  `award` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否中奖1中奖2未中奖',
  `award_id` int(11) DEFAULT '0' COMMENT '中奖奖品id',
  `award_name` varchar(128) DEFAULT '0' COMMENT '奖品名称',
  `award_level` tinyint(1) DEFAULT '0' COMMENT '奖品等级',
  `award_number` int(11) DEFAULT '0' COMMENT '中奖数量',
  `status` tinyint(1) DEFAULT NULL COMMENT '是否用效1有效2无效3已领取',
  `create_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_activity_id` (`activity_id`),
  KEY `idx_activity_number` (`activity_number`),
  KEY `idx_award` (`award`),
  KEY `idx_award_name` (`award_name`(10)),
  KEY `idx_activity_name` (`activity_name`(10))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='抽奖记录';

-- ----------------------------
-- 用户表
-- ----------------------------
CREATE TABLE `db_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `czy_uuid` varchar(64) DEFAULT '' COMMENT '彩之云-UUID',
  `czy_openid` varchar(64) DEFAULT '' COMMENT '彩之云-openId',
  `czy_nickname` varchar(128) DEFAULT '' COMMENT '彩之云用户昵称',
  `czy_portrait_url` varchar(256) DEFAULT '' COMMENT '彩之云-头像',
  `czy_gender` tinyint(4) DEFAULT '0' COMMENT '彩之云-性别',
  `czy_mobile` varchar(64) DEFAULT '' COMMENT '彩之云-手机号码',
  `czy_community_uuid` varchar(128) DEFAULT '' COMMENT '彩之云-小区uuid',
  `czy_community_name` varchar(128) DEFAULT '' COMMENT '彩之云-小区名称',
  `czy_country` varchar(128) DEFAULT '' COMMENT '国家',
  `czy_province` varchar(128) DEFAULT '' COMMENT '省份',
  `czy_city` varchar(128) DEFAULT '' COMMENT '城市',
  `czy_region` varchar(128) DEFAULT '' COMMENT '地区',
  `czy_create_at` int(11) DEFAULT '0' COMMENT '彩之云创建时间',
  `address` varchar(255) DEFAULT '' COMMENT '详细地址',
  `token` varchar(64) DEFAULT '' COMMENT 'Token',
  `token_over_at` int(11) DEFAULT '0' COMMENT 'Token结束时间',
  `status` tinyint(4) DEFAULT '0' COMMENT '状态 1：正常 2：禁用',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_czy_openid` (`czy_openid`(10))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- 变量表
-- ----------------------------
CREATE TABLE `db_variable` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(128) DEFAULT '' COMMENT '名称',
  `desc` varchar(256) DEFAULT '' COMMENT '描述 ',
  `value` TEXT COMMENT '值',

  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`(10))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='变量表';
