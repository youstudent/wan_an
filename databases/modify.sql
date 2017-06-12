CREATE TABLE `wa_goods_img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '模型名',
  `img_path` varchar(255) NOT NULL COMMENT '存放路径',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='商品图片表';

# 区域座位表
CREATE TABLE `wa_district` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL COMMENT '会员id',
  `district` int(11) NOT NULL COMMENT '区域id',
  `seat` int(11) DEFAULT NULL COMMENT '座位id',
  `created_at` int(11) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='座位区域表';

# 订单表添加goods_id
ALTER TABLE `wa_order` ADD `goods_id` int(11) NOT NULl COMMENT '商品id';


CREATE TABLE `wa_fruiter_img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fruiter_id` int(11) NOT NULL DEFAULT '0' COMMENT '果树ID',
  `img_path` varchar(255) NOT NULL COMMENT '存放路径',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COMMENT='果树图片表';

CREATE TABLE `wa_fruiter` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '果树自增id',
  `member_id` int(11) NOT NULL COMMENT '会员id',
  `order_sn` varchar(255) NOT NULL COMMENT '订单号',
  `fruiter_name` varchar(20) DEFAULT NULL COMMENT '果树名称',
  `updated_at` int(10) DEFAULT NULL,
  `fruiter_img` varchar(255) DEFAULT NULL COMMENT '果树图片',
  `created_at` int(10) NOT NULL COMMENT '补充时间',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '状态 1:已补充 0:未补充',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='果树管理表';

# 会员表增加会员VIPID
ALTER TABLE `wa_member` ADD `vip_number` int(11) NOT NULL COMMENT '会员卡号';

# 区域表加一个换位时间记录
ALTER TABLE wa_district ADD updated_at INT(11) DEFAULT NULL COMMENT '更新时间-即换位时间';