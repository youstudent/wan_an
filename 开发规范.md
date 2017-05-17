昂恪YII2项目开发规范
======

#### 一. 数据库管理

````
在自己的应用根目录建立 databases 文件夹

databases
- demo.sql  带演示数据sql
- init.sql  初始化整个项目的sql
- modify.sql  项目进入线上测试，每个字段的更改，记录
````

1.1 demo.sql 范本

```
#v201705131041 标志demo数据的版本号。格式为 YmdHi
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `t_action_log`
-- ----------------------------
DROP TABLE IF EXISTS `t_action_log`;
CREATE TABLE `t_action_log` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
................
................
  `delete_time` int(11) DEFAULT NULL COMMENT '逻辑删除时间',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=678 DEFAULT CHARSET=utf8 COMMENT='平台后台人员行为日志表';

BEGIN;
INSERT INTO `t_action_log` VALUES ('1', '2', '小张', '登录后台成功', '1490144816', '0', null), 
................
................
('3', '2', '小张', '添加权限', '1490147053', '0', null), ('4', '2', '小张', '查看权限列表', '1490147054', '0', null);
COMMIT;

```


1.2 init.sql 范本

```
#初始化SQL
#清空存取记录表
TRUNCATE TABLE jbp_access;
#清空哟西输赢记录表
TRUNCATE TABLE jbp_outcome;
#清空支付记录表
TRUNCATE TABLE jbp_pay;
#清空支付日志表;
TRUNCATE TABLE jbp_pay_log;
#清空交易详情表
TRUNCATE TABLE jbp_transaction_details;
#删除管理员表多余记录,并重置自增id
DELETE FROM jbp_user WHERE user_id>1;
ALTER TABLE jbp_user AUTO_INCREMENT=2;
#清空玩家表
TRUNCATE TABLE jbp_users;
#清空提现记录表
TRUNCATE TABLE jbp_withdraw;
TRUNCATE TABLE jbp_withdraw_rule;
TRUNCATE TABLE jbp_withdrawals;
```

1.3 数据更改范本

```
#添加一个门店添加时间的字段 #v201705131041 关联demo数据的版本号，用来标记数据更改的字段，在demo多少的版本已经包含
ALTER TABLE t_sp_store ADD add_time int(11) COMMENT '添加时间';
#测试注释。描述为什么要更改这个字段
ALTER TABLE t_sp_store modify add_time varchar(10) COMMENT '添加时间';

#创建一个视图，用来统计数据
CREATE VIEW t_view_shoper AS SELECT * FROM t_sp_store;
```



二. 配置文件管理

上传git的时候，请添加一个 范例的配置文件。如将 main-local.php 复制为 main-example.php 并上传版本库，版本库配置目录必须添加如下本地范例配置文件

```
main-local.php.example
params-local.php.example
```


三. 编码规范

- 新模块 创建新应用
- MVC 架构组织代码
- 缩进必须使用4个空格
- 换行必须使用LR

四. MVC的三要素

MVC是模型(Model)、视图(View)、控制器(Controller)3个单词的缩写。 下面我们从这3个方面来讲解MVC中的三个要素。

- Model是指数据模型，是对客观事物的抽象。包含但不仅限于 数据库模型，业务逻辑模型，表单模型，过滤器模型，query生成模型.
- View是指视图，也就是呈现给用户的一个界面，是model的具体表现形式，也是收集用户输入的地方。
- Contorller指的是控制器，主要负责与model和view打交道。

对于Model而言，最主要就是保存事物的信息，表征事物的行为和对他可以进行的操作。 比如，Post类必然有一个用于保存博客文章标题的title属性，必然有一个删除的操作，这都是Model的内容。 以下是关于Model的几个原则：

- 数据、行为、方法是Model的主要内容；
- 实际工作中，Model是MVC中代码量最大，逻辑最复杂的地方，因为关于应用的大量的业务逻辑也要在这里面表示。
- 注意与Controller区分开。Model是处理业务方面的逻辑，Controller只是简单的协调Model和View之间的关系， 只要是与业务有关的，就该放在Model里面。好的设计，应当是胖Model，瘦Controller。


对于Controller，主要是响应用户请求，决定使用什么视图，需要准备什么数据用来显示。 以下是有关Controller的设计原则：

- 用于处理用户请求。 因此，对于reqeust的访问代码应该放在Controller里面，比如 $_GET $_POST 等。 但仅限于获取用户请求数据，不应该对数据有任何操作或预处理，这些工作应该交由Models来完成。
- 调用Models的读方法，获取数据，直接传递给视图，供显示。 当涉及到多个Model时，有关的逻辑应当交给Model来完成。

- 调用Models的类方法，对Models进行写操作。

- 调用视图渲染函数等，形成对用户Reqeust的Response。


五. API 返回包结构

1.对内部项目 

```
code 1 代表成功，其他则是错误码


{
	"code":1,
	"msg":"提示信息",
	"data": [
	.....
	]
}
```

2.对外项目


