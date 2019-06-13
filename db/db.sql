

-- ----------------------------------------------------------------------------------------------------------
-- 基础框架
-- ----------------------------------------------------------------------------------------------------------


DROP TABLE IF EXISTS `db_admin`;
CREATE TABLE `db_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',

  `username` varchar(64) DEFAULT '' COMMENT '用户名',
  `password` varchar(512) DEFAULT '' COMMENT '密码',

  `token` varchar(64) DEFAULT '' COMMENT 'Token',
  `token_over_at` int(11) DEFAULT '0' COMMENT 'Token结束时间',

  `power` tinyint(4) DEFAULT '0' COMMENT '权限 1：超级管理员',

  `status` tinyint(4) DEFAULT '0' COMMENT '状态 1：正常 2：禁用',

  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_username` (`username`(10))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员表';

INSERT INTO `db_admin` (`id`, `username`, `password`, `power`, `status`, `create_at`) VALUES (1, 'caizhiyun', '34a4af4496503372a0387f35bf0c2e1f', 1, 1, UNIX_TIMESTAMP()); -- password:caihiyun@123


DROP TABLE IF EXISTS `db_user`;
CREATE TABLE `db_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',

  `czy_model` varchar(64) DEFAULT '' COMMENT '彩之云-业主用户模型名',
  `czy_user_id` int(11) DEFAULT '0' COMMENT '彩之云-业主用户ID',
  `czy_username` varchar(128) DEFAULT '' COMMENT '彩之云-业主用户账号',
  `czy_realname` varchar(64) DEFAULT '' COMMENT '彩之云-业主真实姓名',
  `czy_mobile` varchar(64) DEFAULT '' COMMENT '彩之云-手机号码',
  `czy_password` varchar(64) DEFAULT '' COMMENT '彩之云-真实姓名',
  `czy_cid` int(11) DEFAULT '0' COMMENT '彩之云-小区id',
  `czy_cname` varchar(128) DEFAULT '' COMMENT '彩之云-小区名称',
  `czy_caddress` varchar(256) DEFAULT '' COMMENT '彩之云-地址',
  `czy_gender` tinyint(4) DEFAULT '0' COMMENT '彩之云-性别',
  `czy_portrait_url` varchar(256) DEFAULT '' COMMENT '彩之云-头像',
  `czy_create_time` int(11) DEFAULT '0' COMMENT '彩之云-注册时间',
  `czy_bind_at` int(11) DEFAULT '0' COMMENT '彩之云绑定时间',

  `token` varchar(64) DEFAULT '' COMMENT 'Token',
  `token_over_at` int(11) DEFAULT '0' COMMENT 'Token结束时间',

  `status` tinyint(4) DEFAULT '0' COMMENT '状态 1：正常 2：禁用',

  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_czy_user_id` (`czy_user_id`),
  KEY `idx_czy_mobile` (`czy_mobile`(10))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';

INSERT INTO `db_user` (`id`, `czy_username`, `status`, `create_at`) VALUES (1, 'caizhiyun', 1, UNIX_TIMESTAMP());


DROP TABLE IF EXISTS `db_variable`;
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


-- ----------------------------------------------------------------------------------------------------------
-- 统计
-- ----------------------------------------------------------------------------------------------------------


DROP TABLE IF EXISTS `db_statistic_record`;
CREATE TABLE `db_statistic_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',

  `user_id` int(11) DEFAULT '0' COMMENT '用户ID',
  `name` varchar(256) DEFAULT '' COMMENT '名称',

  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_name` (`name`(10))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='统计记录表';


DROP TABLE IF EXISTS `db_statistic_get_log`;
CREATE TABLE `db_statistic_get_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',

  `user_id` int(11) DEFAULT '0' COMMENT '用户ID',
  `czy_user_id` int(11) DEFAULT '0' COMMENT '彩之云ID',
  `czy_cid` int(11) DEFAULT '0' COMMENT '彩之云-小区id',

  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_czy_user_id` (`czy_user_id`),
  KEY `idx_czy_cid` (`czy_cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='软硬入口记录表';

DROP TABLE IF EXISTS `db_statistic_czy_send`;
CREATE TABLE `db_statistic_czy_send` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) DEFAULT '0' COMMENT '用户ID',
  `czy_user_id` int(11) DEFAULT '0' COMMENT '彩之云ID',
  `type_title` varchar(256) DEFAULT '' COMMENT '类型名称(模块)',
  `money` float(10, 2) DEFAULT '0' COMMENT '饭票金额',
  `phone` bigint(11) DEFAULT '0' COMMENT '手机号码',
  `status` int(11) DEFAULT '0' COMMENT '状态 1：成功 2：失败',
  `message` varchar(256) DEFAULT '' COMMENT '错误日志',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='发放彩之云饭票日志';

-- ----------------------------------------------------------------------------------------------------------
-- 收集卡片瓜分饭票
-- ----------------------------------------------------------------------------------------------------------


DROP TABLE IF EXISTS `db_carve_price`;
CREATE TABLE `db_carve_price` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',

  `price` float(10, 2) DEFAULT '0' COMMENT '金额',
  `probability` float(10, 2) DEFAULT '0' COMMENT '概率（百分比）',
  `total` int(11) DEFAULT '0' COMMENT '剩余数量',

  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  `version` int(11) DEFAULT '0' COMMENT '版本号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='收集卡片瓜分饭票-金额配置表';


DROP TABLE IF EXISTS `db_carve_card`;
CREATE TABLE `db_carve_card` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',

  `type` tinyint(4) DEFAULT '0' COMMENT '类型 1：无 2：卡片',
  `code` tinyint(4) DEFAULT '0' COMMENT '编码（唯一的数字）',
  `title` varchar(256) DEFAULT '' COMMENT '标题',
  `image` varchar(256) DEFAULT '' COMMENT '图片',
  `caption` varchar(256) DEFAULT '' COMMENT '描述',
  `probability` float(10, 2) DEFAULT '0' COMMENT '概率（百分比）',
  `total` int(11) DEFAULT '0' COMMENT '剩余数量',

  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  `version` int(11) DEFAULT '0' COMMENT '版本号',
  PRIMARY KEY (`id`),
  KEY `idx_code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='收集卡片瓜分饭票-卡片配置表';


DROP TABLE IF EXISTS `db_carve_card_record`;
CREATE TABLE `db_carve_card_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',

  `user_id` int(11) DEFAULT '0' COMMENT '用户ID',
  `card_id` int(11) DEFAULT '0' COMMENT '卡片ID',
  `card_type` tinyint(4) DEFAULT '0' COMMENT '卡片类型 1：无 2：卡片',
  `card_title` varchar(256) DEFAULT '' COMMENT '卡片标题',

  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_card_id` (`card_id`),
  KEY `idx_card_type` (`card_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='收集卡片瓜分饭票-领取卡片记录表';


DROP TABLE IF EXISTS `db_carve_price_record`;
CREATE TABLE `db_carve_price_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',

  `user_id` int(11) DEFAULT '0' COMMENT '用户ID',
  `price` float(10, 2) DEFAULT '0' COMMENT '金额',

  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='收集卡片瓜分饭票-领取金额记录表';


DROP TABLE IF EXISTS `db_carve_user`;
CREATE TABLE `db_carve_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',

  `user_id` int(11) DEFAULT '0' COMMENT '用户ID',
  `receive_status` tinyint(4) DEFAULT '0' COMMENT '收集状态 1：未完成 2：已完成',

  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='收集卡片瓜分饭票-用户表';


-- ----------------------------------------------------------------------------------------------------------
-- 彩住宅
-- ----------------------------------------------------------------------------------------------------------


DROP TABLE IF EXISTS `db_house`;
CREATE TABLE `db_house` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',

  `title` varchar(256) DEFAULT '' COMMENT '标题',
  `image` varchar(256) DEFAULT '' COMMENT '图片',
  `price` varchar(256) DEFAULT '' COMMENT '价格',
  `address` varchar(256) DEFAULT '' COMMENT '地址',
  `info1` varchar(256) DEFAULT '' COMMENT '开发商',
  `info2` varchar(256) DEFAULT '' COMMENT '楼盘信息',
  `info3` varchar(256) DEFAULT '' COMMENT '占地面积',
  `info4` varchar(256) DEFAULT '' COMMENT '建筑面积',
  `info5` varchar(256) DEFAULT '' COMMENT '容积率',
  `info6` varchar(256) DEFAULT '' COMMENT '绿化率',
  `info7` text COMMENT '周边配套',

  `status` tinyint(4) DEFAULT '0' COMMENT '状态 1：正常 2：禁用',

  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_title` (`title`(10)),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='彩住宅表';


DROP TABLE IF EXISTS `db_house_record`;
CREATE TABLE `db_house_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',

  `user_id` int(11) DEFAULT '0' COMMENT '用户ID',
  `chouse_id` int(11) DEFAULT '0' COMMENT '彩住宅ID',
  `name` varchar(256) DEFAULT '' COMMENT '姓名',
  `phone` varchar(256) DEFAULT '' COMMENT '联系方式',

  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='彩住宅预约记录表';


-- ----------------------------------------------------------------------------------------------------------
-- 广告
-- ----------------------------------------------------------------------------------------------------------


DROP TABLE IF EXISTS `db_ad`;
CREATE TABLE `db_ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',

  `type` int(11) DEFAULT '0' COMMENT '类型 1：彩住宅 2：秒杀',
  `title` varchar(256) DEFAULT '' COMMENT '标题',
  `image` varchar(256) DEFAULT '' COMMENT '图片',
  `url` varchar(512) DEFAULT '' COMMENT '链接地址',
  `sort` int(11) DEFAULT '0' COMMENT '排序（从大到小）',

  `status` tinyint(4) DEFAULT '0' COMMENT '状态 1：正常 2：禁用',

  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_type` (`type`),
  KEY `idx_sort` (`sort`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='广告表';


-- ----------------------------------------------------------------------------------------------------------
-- 商城
-- ----------------------------------------------------------------------------------------------------------

DROP TABLE IF EXISTS `db_shop_category`;
CREATE TABLE `db_shop_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `superior_id` int(11) DEFAULT '0' COMMENT '上级ID',
  `level` int(11) DEFAULT '0' COMMENT '级别',
  `title` varchar(256) DEFAULT '' COMMENT '标题',
  `image` varchar(256) DEFAULT '' COMMENT '分类图片',
  `sort` int(11) DEFAULT '0' COMMENT '排序（从大到小）',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_superior_id` (`superior_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品分类表';


DROP TABLE IF EXISTS `db_shop_goods`;
CREATE TABLE `db_shop_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `category_id` int(11) DEFAULT '0' COMMENT '分类ID',
  `title` varchar(256) DEFAULT '' COMMENT '标题',
  `image` varchar(256) DEFAULT '' COMMENT '商品图片',
  `describe` varchar(256) DEFAULT '' COMMENT '商品描述',
  `price` float(10, 2) DEFAULT '0' COMMENT '价格',
  `price_original` float(10, 2) DEFAULT '0' COMMENT '原价',
  `url` varchar(512) DEFAULT '' COMMENT 'URL（商品链接地址）',
  `sort` int(11) DEFAULT '0' COMMENT '排序（从大到小）',
  `value` tinyint(2) DEFAULT '0' COMMENT '是否在首页显示 0：不显示 1：显示',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品表';


-- ----------------------------------------------------------------------------------------------------------
-- 秒杀
-- ----------------------------------------------------------------------------------------------------------


DROP TABLE IF EXISTS `db_seckill_goods`;
CREATE TABLE `db_seckill_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(256) DEFAULT '' COMMENT '标题',
  `image` varchar(256) DEFAULT '' COMMENT '图片',
  `price` float(10, 2) DEFAULT '0' COMMENT '价格',
  `price_original` float(10, 2) DEFAULT '0' COMMENT '原价',
  `url` varchar(512) DEFAULT '' COMMENT 'URL（商品链接地址）',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='秒杀商品表';


DROP TABLE IF EXISTS `db_seckill_goods_publish_record`;
CREATE TABLE `db_seckill_goods_publish_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `goods_id` int(11) DEFAULT '0' COMMENT '商品ID',
  `number` int(11) DEFAULT '0' COMMENT '期号（20170806）',
  `sort` int(11) DEFAULT '0' COMMENT '排序（从大到小）',
  `grounding_at` int(11) DEFAULT '0' COMMENT '上架时间',
  `undercarriage_at` int(11) DEFAULT '0' COMMENT '下架时间',
  `status` tinyint(4) DEFAULT '0' COMMENT '状态 1：未发布 2：即将开始 3：立即秒杀 4：秒杀结束',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='秒杀商品发布记录表';


-- ----------------------------------------------------------------------------------------------------------
-- 免费领
-- ----------------------------------------------------------------------------------------------------------


DROP TABLE IF EXISTS `db_receive_goods`;
CREATE TABLE `db_receive_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(256) DEFAULT '' COMMENT '标题',
  `desc` text COMMENT '产品详情',
  `image` varchar(256) DEFAULT '' COMMENT '列表图片',
  `images` varchar(2014) DEFAULT '' COMMENT '详情图片',
  `price` float(10, 2) DEFAULT '0' COMMENT '价格',
  `price_original` float(10, 2) DEFAULT '0' COMMENT '原价',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='免费领商品表';


DROP TABLE IF EXISTS `db_receive_goods_publish_record`;
CREATE TABLE `db_receive_goods_publish_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `goods_id` int(11) DEFAULT '0' COMMENT '商品ID',
  `number` int(11) DEFAULT '0' COMMENT '期号（20170806）',
  `sort` int(11) DEFAULT '0' COMMENT '排序（从大到小）',
  `total` int(11) DEFAULT '0' COMMENT '商品剩余数量',
  `grounding_at` int(11) DEFAULT '0' COMMENT '上架时间',
  `undercarriage_at` int(11) DEFAULT '0' COMMENT '下架时间',
  `status` tinyint(4) DEFAULT '0' COMMENT '状态 1：未发布 2：即将开始 3：立即申请 4：申请结束',
  `version` int(11) DEFAULT '0' COMMENT '版本号',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='免费领商品发布记录表';


DROP TABLE IF EXISTS `db_receive_goods_apply_record`;
CREATE TABLE `db_receive_goods_apply_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) DEFAULT '0' COMMENT '用户ID',
  `goods_id` int(11) DEFAULT '0' COMMENT '商品ID',
  `publish_id` int(11) DEFAULT '0' COMMENT '发布记录ID',
  `user_name` varchar(32) DEFAULT '' COMMENT '用户的收件人',
  `user_address` varchar(256) DEFAULT '' COMMENT '用户的收货地址',
  `user_code` int(11) DEFAULT '0' COMMENT '用户的邮编号码',
  `user_phone` bigint(11) DEFAULT '0' COMMENT '用户的手机号码',
  `logistics_name` varchar(256) DEFAULT '' COMMENT '物流名称',
  `logistics_code` varchar(256) DEFAULT '' COMMENT '物流单号',
  `status` tinyint(4) DEFAULT '0' COMMENT '状态 1：未处理 2：商家备货 3：商家已发货 4：用户确认收货',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_goods_id` (`goods_id`),
  KEY `idx_publish_id` (`publish_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='免费领商品领取记录表';


-- ----------------------------------------------------------------------------------------------------------
-- 地址
-- ----------------------------------------------------------------------------------------------------------


DROP TABLE IF EXISTS `db_address`;
CREATE TABLE `db_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',

  `user_id` int(11) DEFAULT '0' COMMENT '用户ID',

  `name` varchar(32) DEFAULT '' COMMENT '收件人',
  `address` varchar(256) DEFAULT '' COMMENT '收货地址',
  `phone` varchar(32) DEFAULT '0' COMMENT '手机号码',
  `code` varchar(32) DEFAULT '0' COMMENT '邮编号码',
  `province` varchar(32) DEFAULT '' COMMENT '省',
  `city` varchar(32) DEFAULT '' COMMENT '市',
  `counties` varchar(32) DEFAULT '' COMMENT '区（县）',
  `is_default` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否默认 1：是 2：否',

  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='地址表';
 

-- ----------------------------------------------------------------------------------------------------------
-- 免单
-- ----------------------------------------------------------------------------------------------------------


DROP TABLE IF EXISTS `db_order_participant_record`;
CREATE TABLE `db_order_participant_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) DEFAULT '0' COMMENT '用户ID',
  `order_id` varchar(128) DEFAULT '' COMMENT '订单编号',
  `status_winning` tinyint(3) NOT NULL DEFAULT '2' COMMENT '记录状态 1：未中奖 2：中奖 3：已返回价格',
  `order_discount` varchar(128) NOT NULL DEFAULT '' COMMENT '订单价格',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `create_date` int(11) DEFAULT '0' COMMENT '创建日期（格式：20170518）',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
   KEY `idx_order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='免单参与记录表';


-- ----------------------------------------------------------------------------------------------------------
-- 抽奖
-- ----------------------------------------------------------------------------------------------------------


DROP TABLE IF EXISTS `db_lottery_award`;
CREATE TABLE `db_lottery_award` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `type` tinyint(6) DEFAULT '0' COMMENT '类型 1：谢谢参与 2：饭票 3：优惠券 4：商品 5：卡片 6：保险',
  `title` varchar(256) DEFAULT '' COMMENT '标题',
  `resource_id` int(11) DEFAULT '0' COMMENT '资源ID',
  `caption` varchar(256) DEFAULT '' COMMENT '描述',
  `image` varchar(256) DEFAULT '' COMMENT '图片',
  `total` int(11) DEFAULT '0' COMMENT '奖品剩余数量',
  `sort` int(11) DEFAULT '0' COMMENT '排序（从大到小）',
  `value1` varchar(256) DEFAULT '' COMMENT '属性1（根据商品类型不同意义不同）',
  `value2` varchar(256) DEFAULT '' COMMENT '属性2（根据商品类型不同意义不同）',
  `probability` float(10, 2) DEFAULT '0' COMMENT '概率（百分比）',
  `version` int(11) DEFAULT '0' COMMENT '版本号',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_resource_id` (`resource_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='抽奖奖品表';


DROP TABLE IF EXISTS `db_lottery_record`;
CREATE TABLE `db_lottery_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) DEFAULT '0' COMMENT '用户ID',
  `lottery_award_id` int(11) DEFAULT '0' COMMENT '抽奖奖品ID',
  `lottery_award_type` tinyint(4) DEFAULT '0' COMMENT '抽奖奖品类型（对应db_lottery_award的type）',
  `receive_at` int(11) DEFAULT '0' COMMENT '领取时间',
  `receive_no` varchar(32) DEFAULT '' COMMENT '领取编号',
  `receive1` varchar(256) DEFAULT '' COMMENT '领取值1（根据商品类型不同意义不同）',
  `receive2` varchar(256) DEFAULT '' COMMENT '领取值2（根据商品类型不同意义不同）',
  `receive3` varchar(256) DEFAULT '' COMMENT '领取值3（根据商品类型不同意义不同）',
  `receive4` varchar(256) DEFAULT '' COMMENT '领取值4（根据商品类型不同意义不同）',
  `receive5` varchar(256) DEFAULT '' COMMENT '领取值5（根据商品类型不同意义不同）',
  `false_amount` float(10, 2) DEFAULT '0' COMMENT '饭票金额(用于假数据)',
  `logistics_name` varchar(256) DEFAULT '' COMMENT '物流名称',
  `logistics_code` varchar(256) DEFAULT '' COMMENT '物流单号',
  `status` tinyint(6) DEFAULT '0' COMMENT '状态 1：未领取 2：领取中 3：已领取 4：商家备货 5：商家已发货 6：用户确认收货',
  `sign` tinyint(2) DEFAULT '1' COMMENT '标识 1：不是分享 2：分享',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `create_date` int(11) DEFAULT '0' COMMENT '创建日期（格式：20170518）',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `lottery_award_id` (`lottery_award_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='抽奖记录表';

DROP TABLE IF EXISTS `db_lottery_share_record`;
CREATE TABLE `db_lottery_share_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `orderno` varchar(128) DEFAULT '' COMMENT '订单编号',
  `user_id` int(11) DEFAULT '0' COMMENT '用户ID',
  `number` int(11) DEFAULT '0' COMMENT '抽奖剩余次数',
  `initialize_number` int(11) DEFAULT '0' COMMENT '抽奖初始次数',
  `version` int(11) DEFAULT '0' COMMENT '版本号',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `create_date` int(11) DEFAULT '0' COMMENT '创建日期（格式：20170518）',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_orderno` (`orderno`(10)),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='抽奖分享记录表';

-- ----------------------------------------------------------------------------------------------------------
-- 领饭票红包
-- ----------------------------------------------------------------------------------------------------------


DROP TABLE IF EXISTS `db_packet_award`;
CREATE TABLE `db_packet_award` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(256) DEFAULT '' COMMENT '标题',
  `image` varchar(256) DEFAULT '' COMMENT '图片',
  `total` int(11) DEFAULT '0' COMMENT '奖品剩余数量',
  `money` float(10, 2) DEFAULT '0' COMMENT '饭票金额',
  `probability` float(10, 2) DEFAULT '0' COMMENT '概率（百分比）',
  `version` int(11) DEFAULT '0' COMMENT '版本号',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='饭票红包奖品表';


DROP TABLE IF EXISTS `db_packet_record`;
CREATE TABLE `db_packet_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) DEFAULT '0' COMMENT '用户ID',
  `packet_award_id` int(11) DEFAULT '0' COMMENT '饭票奖品ID',
  `value1` varchar(255) DEFAULT '' COMMENT '属性1(根据业务不同，值不同)',
  `status` tinyint(2) DEFAULT '0' COMMENT '状态 1：未领取 2：领取中 3：已领取',
  `sign` tinyint(2) DEFAULT '1' COMMENT '标识 1：不是分享 2：分享',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `create_date` int(11) DEFAULT '0' COMMENT '创建日期（格式：20170518）',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_packet_award_id` (`packet_award_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='饭票红包领取记录表';

DROP TABLE IF EXISTS `db_packet_share_record`;
CREATE TABLE `db_packet_share_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) DEFAULT '0' COMMENT '用户ID',
  `number` int(11) DEFAULT '0' COMMENT '抽奖剩余次数',
  `initialize_number` int(11) DEFAULT '0' COMMENT '抽奖初始次数',
  `version` int(11) DEFAULT '0' COMMENT '版本号',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `create_date` int(11) DEFAULT '0' COMMENT '创建日期（格式：20170518）',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='领红包分享记录表';

-- ----------------------------------------------------------------------------------------------------------
-- 软硬转换记录
-- ----------------------------------------------------------------------------------------------------------

DROP TABLE IF EXISTS `db_statistic_count_log`;
CREATE TABLE `db_statistic_count_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL COMMENT '用户id',
  `category_id` int(11) NOT NULL COMMENT '入口类型id',
  `community_id` int(11) NOT NULL COMMENT '小区id',
  `operation_time` int(11) DEFAULT NULL COMMENT '操作时间',
  `status` tinyint(2) DEFAULT '0' COMMENT '状态 0：未处理 1：已提交',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_customer_id` (`customer_id`),
  KEY `idx_category_community` (`community_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='软硬入口中间表';

DROP TABLE IF EXISTS `db_statistic_status`;
CREATE TABLE `db_statistic_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `record_id` int(11) DEFAULT '0' COMMENT '记录ID',
  `start_id` int(11) DEFAULT '0' COMMENT '开始记录ID',
  `end_id` int(11) DEFAULT '0' COMMENT '结束记录ID',
  `record_count` int(11) DEFAULT '0' COMMENT '转换数量',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_record_id` (`record_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='转换中间状态表';

-- ----------------------------------------------------------------------------------------------------------
-- 拉新激励活动(新用户)
-- ----------------------------------------------------------------------------------------------------------
DROP TABLE IF EXISTS `db_bestir_user`;
CREATE TABLE `db_bestir_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) DEFAULT '0' COMMENT '用户ID',
  `type` int(11) DEFAULT '0' COMMENT '类型1:新用户 2:老用户',
  `version` int(11) DEFAULT '0' COMMENT '版本号',
  `start_at` int(11) DEFAULT '0' COMMENT '领取红包开始时间',
  `end_at` int(11) DEFAULT '0' COMMENT '领取红包结束时间',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='拉新激励-用户信息表';

DROP TABLE IF EXISTS `db_bestir_new_award`;
CREATE TABLE `db_bestir_new_award` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(256) DEFAULT '' COMMENT '标题',
  `total` int(11) DEFAULT '0' COMMENT '奖品剩余数量',
  `money` float(10, 2) DEFAULT '0' COMMENT '饭票金额',
  `probability` float(10, 2) DEFAULT '0' COMMENT '概率（百分比）',
  `value1` int(11) DEFAULT '0' COMMENT '用于标识属于第几天',
  `version` int(11) DEFAULT '0' COMMENT '版本号',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='拉新激励-新用户红包奖品表';

DROP TABLE IF EXISTS `db_bestir_new_record`;
CREATE TABLE `db_bestir_new_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) DEFAULT '0' COMMENT '用户ID',
  `bestir_award_id` int(11) DEFAULT '0' COMMENT '饭票奖品ID',
  `bestir_award_money` float(10, 2) DEFAULT '0' COMMENT '饭票奖品金额',
  `value1` int(11) DEFAULT '0' COMMENT '用于标识属于第几天',
  `value` float(10, 2) DEFAULT '0' COMMENT '饭票金额(用于假数据)',
  `status` tinyint(2) DEFAULT '0' COMMENT '状态 1：未领取 2：领取中 3：已领取 ',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `create_date` int(11) DEFAULT '0' COMMENT '创建日期（格式：20170518）',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_bestir_award_id` (`bestir_award_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='拉新激励-新用户领取记录表';



-- ----------------------------------------------------------------------------------------------------------
-- 拉新激励活动 老用户
-- ----------------------------------------------------------------------------------------------------------

DROP TABLE IF EXISTS `db_bestir_old_award`;
CREATE TABLE `db_bestir_old_award` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(256) DEFAULT '' COMMENT '标题',
  `total` int(11) DEFAULT '0' COMMENT '奖品剩余数量',
  `money` float(10, 2) DEFAULT '0' COMMENT '饭票金额',
  `probability` float(10, 2) DEFAULT '0' COMMENT '概率（百分比）',
  `version` int(11) DEFAULT '0' COMMENT '版本号',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='拉新激励-老用户红包奖品表';


DROP TABLE IF EXISTS `db_bestir_inviter_record`;
CREATE TABLE `db_bestir_inviter_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `inviter_user_id` int(11) DEFAULT '0' COMMENT '邀请人ID',
  `bestir_award_id` int(11) DEFAULT '0' COMMENT '饭票奖品ID',
  `money` float(10, 2) DEFAULT '0' COMMENT '饭票金额',
  `value` float(10, 2) DEFAULT '0' COMMENT '饭票金额(用于假数据)',
  `invitee_user_id` int(11) DEFAULT '0' COMMENT '被邀请人ID',
  `invitee_user_phone` bigint(11) DEFAULT '0' COMMENT '被邀请人手机号码',
  `invitee_user_realname` varchar(256) DEFAULT '' COMMENT '被邀请人昵称',
  `status` int(11) DEFAULT '4' COMMENT '红包状态 1：未领取 2：领取中 3：已领取 4：未激活',
  `sort` int(11) DEFAULT '0' COMMENT '排序',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_inviter_user_id` (`inviter_user_id`),
  KEY `idx_invitee_user_id` (`invitee_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='拉新激励--邀请记录表';


-- ----------------------------------------------------------------------------------------------------------
-- 签到活动
-- ----------------------------------------------------------------------------------------------------------

DROP TABLE IF EXISTS `db_signed_sign`;
CREATE TABLE `db_signed_sign` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `value` int(11) DEFAULT '0' COMMENT '天数',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='签到活动-天数配置表';

DROP TABLE IF EXISTS `db_signed_award`;
CREATE TABLE `db_signed_award` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `sign_id` int(11) DEFAULT '0' COMMENT '对应天数配置ID',
  `title` varchar(256) DEFAULT '' COMMENT '标题',
  `describe` varchar(256) DEFAULT '' COMMENT '奖品描述',
  `total` int(11) DEFAULT '0' COMMENT '奖品剩余数量',
  `money` float(10, 2) DEFAULT '0' COMMENT '饭票金额',
  `probability` float(10, 2) DEFAULT '0' COMMENT '概率（百分比）',
  `version` int(11) DEFAULT '0' COMMENT '版本号',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_sign_id` (`sign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='签到活动-红包奖品表';

DROP TABLE IF EXISTS `db_signed_user_record`;
CREATE TABLE `db_signed_user_record` (
	`id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
	`user_id` int(11) DEFAULT '0' COMMENT '用户ID',
	`sign_id` int(11) DEFAULT '0' COMMENT '对应天数配置ID',
	`award_id` int(11) DEFAULT '0' COMMENT '饭票奖品ID',
	`money` float(10, 2) DEFAULT '0' COMMENT '饭票金额',
	`value` float(10, 2) DEFAULT '0' COMMENT '饭票金额(用于假数据)',
	`status` tinyint(3) DEFAULT '0' COMMENT '状态 1：未领取 2：领取中 3：已领取 ',
	`create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `create_date` int(11) DEFAULT '0' COMMENT '创建日期（格式：20170518）',
	`update_at` int(11) DEFAULT '0' COMMENT '更新时间',
	PRIMARY KEY (`id`),
	KEY `idx_user_id` (`user_id`),
	KEY `idx_sign_id` (`sign_id`),
	KEY `idx_award_id` (`award_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='签到活动-用户签到信息表';

-- ----------------------------------------------------------------------------------------------------------
-- 百万活动
-- ----------------------------------------------------------------------------------------------------------

DROP TABLE IF EXISTS `db_ogift_award`;
CREATE TABLE `db_ogift_award` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(256) DEFAULT '' COMMENT '标题',
  `type` tinyint(2) DEFAULT '0' COMMENT '类型 1：谢谢参与 2：饭票 3：商品',
  `image` varchar(256) DEFAULT '' COMMENT '图片',
  `describe` varchar(256) DEFAULT '' COMMENT '奖品描述',
  `total` int(11) DEFAULT '0' COMMENT '奖品剩余数量',
  `money` float(10, 2) DEFAULT '0' COMMENT '饭票金额(类型为3时必需)',
  `probability` float(10, 2) DEFAULT '0' COMMENT '概率（百分比）',
 	`lottery_type`  tinyint(2) DEFAULT '0' COMMENT '奖品抽奖资格来源 1：赠送 2：下单',
	`version` int(11) DEFAULT '0' COMMENT '版本号',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='百万领红包活动-奖品表';

DROP TABLE IF EXISTS `db_ogift_user_record`;
CREATE TABLE `db_ogift_user_record` (
	`id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
	`czy_user_id` int(11) DEFAULT '0' COMMENT '彩之云ID',
  `order_id` varchar(128) DEFAULT '' COMMENT '订单编号(新用户初始化抽奖：订单编号为空)',
  `order_money` float(10, 2) DEFAULT '0' COMMENT '订单金额',
	`award_id` int(11) DEFAULT '0' COMMENT '奖品ID',
	`award_type` tinyint(2) DEFAULT '0' COMMENT '类型 1：谢谢参与 2：饭票 3：商品',
	`money` float(10, 2) DEFAULT '0' COMMENT '饭票金额',
	`value` float(10, 2) DEFAULT '0' COMMENT '假数据',
	`status` tinyint(3) DEFAULT '0' COMMENT '状态 1：初始化 2：未领取 3：领取中 4：已领取',
 	`lottery_type`  tinyint(2) DEFAULT '0' COMMENT '奖品抽奖资格来源 1：赠送 2：下单',
 	`receive1` varchar(256) DEFAULT '' COMMENT '领取值1（根据商品类型不同意义不同）',
  `receive2` varchar(256) DEFAULT '' COMMENT '领取值2（根据商品类型不同意义不同）',
  `receive3` varchar(256) DEFAULT '' COMMENT '领取值3（根据商品类型不同意义不同）',
	`version` int(11) DEFAULT '0' COMMENT '版本号',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
	`update_at` int(11) DEFAULT '0' COMMENT '更新时间',
	PRIMARY KEY (`id`),
	KEY `idx_czy_user_id` (`czy_user_id`),
	KEY `idx_order_id` (`order_id`),
	KEY `idx_award_id` (`award_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='百万领红包活动-用户抽奖记录表';

-- ----------------------------------------------------------------------------------------------------------
-- 猜灯谜
-- ----------------------------------------------------------------------------------------------------------

DROP TABLE IF EXISTS `db_guessing_award`;
CREATE TABLE `db_guessing_award` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(256) DEFAULT '' COMMENT '奖品标题',
  `total` int(11) DEFAULT '0' COMMENT '奖品剩余数量',
  `caption` varchar(256) DEFAULT '' COMMENT '描述',
  `image` varchar(256) DEFAULT '' COMMENT '图片',
  `money` float(10, 2) DEFAULT '0' COMMENT '饭票金额',
  `probability` float(10, 2) DEFAULT '0' COMMENT '概率（百分比）',
  `version` int(11) DEFAULT '0' COMMENT '版本号',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='猜灯谜奖品表';

DROP TABLE IF EXISTS `db_guessing_questions`;
CREATE TABLE `db_guessing_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(256) DEFAULT '' COMMENT '题目',
  `image` varchar(256) DEFAULT '' COMMENT '图片',
  `answer` varchar(256) DEFAULT '' COMMENT '答案',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='猜灯谜题库表';

DROP TABLE IF EXISTS `db_guessing_join_record`;
CREATE TABLE `db_guessing_join_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) DEFAULT '0' COMMENT '用户ID',
  `award_id` int(11) DEFAULT '0' COMMENT '奖品ID',
  `questions_id` int(11) DEFAULT '0' COMMENT '谜题ID',
  `money` float(10, 2) DEFAULT '0' COMMENT '饭票金额',
  `status` tinyint(3) DEFAULT '0' COMMENT '状态 1：未领取 2：领取中 3：已领取',
  `type` tinyint(2) DEFAULT '1' COMMENT '状态 1：未猜中 2：猜中',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `create_date` int(11) DEFAULT '0' COMMENT '创建日期（格式：20170518）',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_award_id` (`award_id`),
  KEY `idx_questions_id` (`questions_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='猜灯谜用户参与表';


-- ----------------------------------------------------------------------------------------------------------
-- 脑力全开
-- ----------------------------------------------------------------------------------------------------------

DROP TABLE IF EXISTS `db_answer_date`;
CREATE TABLE `db_answer_date` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `date` int(11) DEFAULT '0' COMMENT '日期（格式：20170518）',
  `start_slogan` varchar(556) DEFAULT '' COMMENT '答题前提示语',
  `end_slogan` varchar(556) DEFAULT '' COMMENT '答题完毕提示语',
  `lottery_number` int(11) DEFAULT '0' COMMENT '回答正确多少题才可抽奖',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='脑力全开-日期表';

DROP TABLE IF EXISTS `db_answer_subject`;
CREATE TABLE `db_answer_subject` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `date_id` int(11) DEFAULT '0' COMMENT '日期ID',
  `title` varchar(556) DEFAULT '' COMMENT '标题',
  `type` tinyint(2) DEFAULT '0' COMMENT '类型 1：填空题 2：选择题',
  `answer` varchar(2000) DEFAULT '' COMMENT '答案',
  `option` varchar(2000) DEFAULT '' COMMENT '选项',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_date_id` (`date_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='脑力全开-题目表';

DROP TABLE IF EXISTS `db_answer_award`;
CREATE TABLE `db_answer_award` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `date_id` int(11) DEFAULT '0' COMMENT '日期ID（不填则代表所有日期）',
  `type` tinyint(6) DEFAULT '0' COMMENT '类型 1：谢谢参与 2：饭票 3：商品',
  `title` varchar(256) DEFAULT '' COMMENT '标题',
  `caption` varchar(256) DEFAULT '' COMMENT '描述',
  `image` varchar(256) DEFAULT '' COMMENT '图片',
  `money` float(10, 2) DEFAULT '0' COMMENT '价格',
  `total` int(11) DEFAULT '0' COMMENT '奖品数量',
  `probability` float(10, 2) DEFAULT '0' COMMENT '概率（百分比）',
  `version` int(11) DEFAULT '0' COMMENT '版本号',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_date_id` (`date_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='脑力全开-奖品表';

DROP TABLE IF EXISTS `db_answer_silk_bag_record`;
CREATE TABLE `db_answer_silk_bag_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) DEFAULT '0' COMMENT '用户ID',
  `user_phone` bigint(11) DEFAULT '0' COMMENT '用户手机号码',
  `type` tinyint(2) DEFAULT '0' COMMENT '获得渠道 1：分享获得 2：答题获得',
  `status` tinyint(2) DEFAULT '0' COMMENT '使用状态 1：未使用 2：已使用',
  `create_date` int(11) DEFAULT '0' COMMENT '创建日期（格式：20170518）',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='脑力全开-免死记录表';

DROP TABLE IF EXISTS `db_answer_receive_record`;
CREATE TABLE `db_answer_receive_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) DEFAULT '0' COMMENT '用户ID',
  `date_id` int(11) DEFAULT '0' COMMENT '日期ID',
  `award_id` int(11) DEFAULT '0' COMMENT '奖品ID',
  `award_type` tinyint(3) DEFAULT '0' COMMENT '类型 1：谢谢参与 2：饭票 3：商品',
  `award_title` varchar(256) DEFAULT '' COMMENT '奖品标题',
  `money` float(10, 2) DEFAULT '0' COMMENT '价格',
  `receive_time` int(11) DEFAULT '0' COMMENT '领取时间',
  `value1` varchar(256) DEFAULT '' COMMENT '属性1（根据商品类型不同意义不同）',
  `value2` varchar(256) DEFAULT '' COMMENT '属性2（根据商品类型不同意义不同）',
  `value3` varchar(256) DEFAULT '' COMMENT '属性3（根据商品类型不同意义不同）',
  `status` tinyint(5) DEFAULT '0' COMMENT '状态 1：未完成 2：已完成 3：已抽奖 4：领取中 5：已领取',
  `create_date` int(11) DEFAULT '0' COMMENT '创建日期（格式：20170518）',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_date_id` (`date_id`),
  KEY `idx_award_id` (`award_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='脑力全开-用户参与记录表';

DROP TABLE IF EXISTS `db_answer_answer`;
CREATE TABLE `db_answer_answer` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) DEFAULT '0' COMMENT '用户ID',
  `date_id` int(11) DEFAULT '0' COMMENT '日期ID',
  `subject_id` int(11) DEFAULT '0' COMMENT '题目ID',
  `answer` varchar(556) DEFAULT '' COMMENT '答案',
  `status` tinyint(4) DEFAULT '0' COMMENT '状态 1：未答 2：自己答对 3：自己答错 4：好友答对',
  `create_date` int(11) DEFAULT '0' COMMENT '创建日期（格式：20170518）',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_date_id` (`date_id`),
  KEY `idx_subject_id` (`subject_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='脑力全开-回答记录表';

DROP TABLE IF EXISTS `db_answer_help`;
CREATE TABLE `db_answer_help` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) DEFAULT '0' COMMENT '用户ID',
  `date_id` int(11) DEFAULT '0' COMMENT '日期ID',
  `subject_id` int(11) DEFAULT '0' COMMENT '题目ID',
  `answer_id` int(11) DEFAULT '0' COMMENT '答题ID',
  `friends_id` varchar(256) DEFAULT '' COMMENT '好友标识',
  `answer` varchar(556) DEFAULT '' COMMENT '答案',
  `status` tinyint(2) DEFAULT '0' COMMENT '状态 1：答对 2：答错',
  `friends_phone` bigint(11) DEFAULT '0' COMMENT '好友手机号码',
  `sign` tinyint(2) DEFAULT '0' COMMENT '状态 1：已领取 2：未领取',
  `create_date` int(11) DEFAULT '0' COMMENT '创建日期（格式：20170518）',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_date_id` (`date_id`),
  KEY `idx_subject_id` (`subject_id`),
  KEY `idx_answer_id` (`answer_id`),
  KEY `idx_friends_id` (`friends_id`(10))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='脑力全开-求助记录表';

-- ----------------------------------------------------------------------------------------------------------
-- 京东比价
-- ----------------------------------------------------------------------------------------------------------

DROP TABLE IF EXISTS `db_parity_goods`;
CREATE TABLE `db_parity_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(256) DEFAULT '' COMMENT '标题',
  `describe` varchar(256) DEFAULT '' COMMENT '商品描述',
  `label` varchar(256) DEFAULT '' COMMENT '标签',
  `image` varchar(256) DEFAULT '' COMMENT '商品图片',
  `price` float(10, 2) DEFAULT '0' COMMENT '价格',
  `price_original` float(10, 2) DEFAULT '0' COMMENT '原价',
  `url` varchar(512) DEFAULT '' COMMENT 'URL（商品链接地址）',
  `sort` int(11) DEFAULT '0' COMMENT '排序（从大到小）',
  `increment_type` int(11) DEFAULT '0' COMMENT '自增类型 1：每分钟 2：每小时',
  `increment_max_number` int(11) DEFAULT '0' COMMENT '自增最大数量',
  `increment_min_number` int(11) DEFAULT '0' COMMENT '自增最小数量',
  `increment_over_number` int(11) DEFAULT '0' COMMENT '自增结束数量',
  `sell_number` int(11) DEFAULT '0' COMMENT '卖数',
  `total_number` int(11) DEFAULT '0' COMMENT '总数',
  `remind_number` int(11) DEFAULT '0' COMMENT '提醒数量',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='京东比价-商品表';

DROP TABLE IF EXISTS `db_parity_remind_record`;
CREATE TABLE `db_parity_remind_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) DEFAULT '0' COMMENT '用户ID',
  `goods_id` int(11) DEFAULT '0' COMMENT '商品ID',
  `status` int(11) DEFAULT '0' COMMENT '状态 1：未发送 2：已发送',
  `create_at` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_at` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_goods_id` (`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='京东比价-提醒表';