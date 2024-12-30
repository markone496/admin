/*
 Navicat Premium Data Transfer

 Source Server         : 本地8.0
 Source Server Type    : MySQL
 Source Server Version : 80012
 Source Host           : localhost:3308
 Source Schema         : lzadmin

 Target Server Type    : MySQL
 Target Server Version : 80012
 File Encoding         : 65001

 Date: 03/12/2024 16:55:45
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for sys_config
-- ----------------------------
DROP TABLE IF EXISTS `sys_config`;
CREATE TABLE `sys_config`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '配置ID',
  `index_key` char(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '标识',
  `title` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '标题',
  `data` json NULL COMMENT '配置',
  `model_id` int(11) NOT NULL COMMENT '模型ID',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `index_key`(`index_key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-配置' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sys_config
-- ----------------------------

-- ----------------------------
-- Table structure for sys_function
-- ----------------------------
DROP TABLE IF EXISTS `sys_function`;
CREATE TABLE `sys_function`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '权限ID',
  `title` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '权限名称',
  `route` json NULL COMMENT '路由组',
  `menu_id` int(11) NOT NULL COMMENT '菜单ID',
  `menu_ids` json NULL COMMENT '菜单组ID',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-权限' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sys_function
-- ----------------------------
INSERT INTO `sys_function` VALUES (1, '查看', '[\"main\"]', 1, '[\"1\"]', '2023-12-06 08:48:27', '2024-04-18 09:34:04');
INSERT INTO `sys_function` VALUES (2, '查看', '[\"sys/role\", \"sys/role/list\"]', 5, '[\"5\", \"3\", \"2\"]', '2023-12-06 06:44:46', '2023-12-06 16:49:08');
INSERT INTO `sys_function` VALUES (3, '新增', '[\"sys/role/edit\", \"sys/role/create\"]', 5, '[\"5\", \"3\", \"2\"]', '2023-12-06 06:45:16', '2023-12-06 16:49:06');
INSERT INTO `sys_function` VALUES (4, '编辑', '[\"sys/role/edit\", \"sys/role/update\"]', 5, '[\"5\", \"3\", \"2\"]', '2023-12-06 06:45:38', '2023-12-06 16:49:05');
INSERT INTO `sys_function` VALUES (5, '删除', '[\"sys/role/delete\"]', 5, '[\"5\", \"3\", \"2\"]', '2023-12-06 06:45:47', '2023-12-06 16:49:02');
INSERT INTO `sys_function` VALUES (6, '复制', '[\"sys/role/copy\"]', 5, '[\"5\", \"3\", \"2\"]', '2023-12-06 06:46:00', '2023-12-06 16:49:01');
INSERT INTO `sys_function` VALUES (7, '查看', '[\"sys/user\", \"sys/user/list\"]', 4, '[\"4\", \"3\", \"2\"]', '2023-12-06 08:24:22', '2024-04-18 09:33:37');
INSERT INTO `sys_function` VALUES (8, '新增', '[\"sys/user/edit\", \"sys/user/create\"]', 4, '[\"4\", \"3\", \"2\"]', '2023-12-06 08:24:40', '2024-04-18 09:33:43');
INSERT INTO `sys_function` VALUES (9, '编辑', '[\"sys/user/edit\", \"sys/user/update\"]', 4, '[\"4\", \"3\", \"2\"]', '2023-12-06 08:24:52', '2024-04-18 09:33:48');
INSERT INTO `sys_function` VALUES (10, '删除', '[\"sys/user/delete\"]', 4, '[\"4\", \"3\", \"2\"]', '2023-12-06 08:25:01', '2024-04-18 09:33:51');
INSERT INTO `sys_function` VALUES (11, '重置密码', '[\"sys/user/password\"]', 4, '[\"4\", \"3\", \"2\"]', '2023-12-06 08:25:11', '2024-04-18 09:33:54');
INSERT INTO `sys_function` VALUES (12, '查看', '[\"sys/configData\", \"sys/configData/list\", \"sys/configData/info\"]', 7, '[\"7\", \"6\", \"2\"]', '2024-12-16 08:36:34', '2024-12-16 08:48:17');
INSERT INTO `sys_function` VALUES (13, '编辑', '[\"sys/configData/edit\", \"sys/configData/update\"]', 7, '[\"7\", \"6\", \"2\"]', '2024-12-16 08:48:32', '2024-12-16 09:51:30');


-- ----------------------------
-- Table structure for sys_log
-- ----------------------------
DROP TABLE IF EXISTS `sys_log`;
CREATE TABLE `sys_log`  (
  `ip` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '访问IP',
  `user_id` bigint(20) NOT NULL COMMENT '用户ID',
  `route` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '路由',
  `params` json NULL COMMENT '参数',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-日志' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sys_log
-- ----------------------------
INSERT INTO `sys_log` VALUES ('127.0.0.1', 0, '/', '[]', '2024-12-03 08:49:05');
INSERT INTO `sys_log` VALUES ('127.0.0.1', 0, 'sys/refreshCache', '[]', '2024-12-03 08:49:08');
INSERT INTO `sys_log` VALUES ('127.0.0.1', 0, '/', '[]', '2024-12-03 08:49:10');
INSERT INTO `sys_log` VALUES ('127.0.0.1', 0, 'sys/user', '[]', '2024-12-03 08:49:14');
INSERT INTO `sys_log` VALUES ('127.0.0.1', 0, 'sys/user/list', '{\"page\": \"1\", \"limit\": \"20\", \"_token\": \"LE9ZZFv0pHYczQacDW3765RIXqSRJDioaF0QnXMm\"}', '2024-12-03 08:49:14');
INSERT INTO `sys_log` VALUES ('127.0.0.1', 0, 'sys/role', '[]', '2024-12-03 08:49:15');
INSERT INTO `sys_log` VALUES ('127.0.0.1', 0, 'sys/role/list', '{\"page\": \"1\", \"limit\": \"20\", \"_token\": \"LE9ZZFv0pHYczQacDW3765RIXqSRJDioaF0QnXMm\"}', '2024-12-03 08:49:15');

-- ----------------------------
-- Table structure for sys_menu
-- ----------------------------
DROP TABLE IF EXISTS `sys_menu`;
CREATE TABLE `sys_menu`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '父ID',
  `parent_ids` json NOT NULL COMMENT '父ID集',
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '标题',
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '图标',
  `route` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '路由',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `is_hide` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否隐藏',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '系统-菜单' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sys_menu
-- ----------------------------
INSERT INTO `sys_menu` VALUES (1, 0, '[]', '主页', 'layui-icon layui-icon-home', '/main', 0, 1, '2023-12-06 06:40:45', '2023-12-06 06:40:45');
INSERT INTO `sys_menu` VALUES (2, 0, '[]', '系统管理', 'layui-icon layui-icon-auz', NULL, 0, 0, '2023-12-06 06:42:02', '2023-12-06 06:42:02');
INSERT INTO `sys_menu` VALUES (3, 2, '[\"2\"]', '管理员', 'layui-icon layui-icon-group', NULL, 0, 0, '2024-04-19 06:19:06', '2023-12-06 06:42:31');
INSERT INTO `sys_menu` VALUES (4, 3, '[\"3\", \"2\"]', '用户', NULL, '/sys/user', 0, 0, '2024-04-07 08:32:38', '2023-12-06 06:42:52');
INSERT INTO `sys_menu` VALUES (5, 3, '[\"3\", \"2\"]', '角色', NULL, '/sys/role', 0, 0, '2023-12-06 06:43:03', '2023-12-06 06:43:03');
INSERT INTO `sys_menu` VALUES (6, 2, '[\"2\"]', '配置项', 'layui-icon layui-icon-set-sm', NULL, 0, 0, '2024-12-16 09:24:33', '2024-12-16 08:35:06');
INSERT INTO `sys_menu` VALUES (7, 6, '[\"6\", \"2\"]', '配置列表', NULL, '/sys/configData', 0, 0, '2024-12-16 09:24:42', '2024-12-16 08:36:23');


-- ----------------------------
-- Table structure for sys_model
-- ----------------------------
DROP TABLE IF EXISTS `sys_model`;
CREATE TABLE `sys_model`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '模型ID',
  `title` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '标题',
  `choose_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '选项',
  `table_config` json NULL COMMENT '表配置',
  `cols_config` json NULL COMMENT '列表配置',
  `search_config` json NULL COMMENT '搜索配置',
  `form_config` json NULL COMMENT '表单配置',
  `toolbar_config` json NULL COMMENT '头部按钮配置',
  `tool_config` json NULL COMMENT '行按钮配置',
  `info_config` json NULL COMMENT '详情配置',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-模型' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sys_model
-- ----------------------------

-- ----------------------------
-- Table structure for sys_option
-- ----------------------------
DROP TABLE IF EXISTS `sys_option`;
CREATE TABLE `sys_option`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '选项ID',
  `title` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '标题',
  `option_config` json NULL COMMENT '选项配置',
  `action` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '控制器方法名',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-选项' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sys_option
-- ----------------------------
INSERT INTO `sys_option` VALUES (1, '通用是否', '[{\"color\": \"layui-bg-gray\", \"title\": \"否\", \"value\": \"0\"}, {\"color\": \"layui-bg-green\", \"title\": \"是\", \"value\": \"1\"}]', NULL, '2023-12-06 11:54:38', '2023-12-06 03:55:05');
INSERT INTO `sys_option` VALUES (2, '系统角色', '[]', 'lz\\admin\\Services\\RoleService@getRoleOption', '2023-12-06 15:19:45', '2024-12-03 07:31:18');

-- ----------------------------
-- Table structure for sys_role
-- ----------------------------
DROP TABLE IF EXISTS `sys_role`;
CREATE TABLE `sys_role`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `role_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '角色名称',
  `function_ids` json NOT NULL COMMENT '权限ID集合',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-角色' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sys_role
-- ----------------------------

-- ----------------------------
-- Table structure for sys_user
-- ----------------------------
DROP TABLE IF EXISTS `sys_user`;
CREATE TABLE `sys_user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `account` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '登录账号',
  `password_md5` char(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'MD5密码',
  `nickname` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '昵称',
  `role_id` int(11) UNSIGNED NOT NULL COMMENT '角色ID',
  `is_disable` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否禁用',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `account`(`account`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统-用户' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of sys_user
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
