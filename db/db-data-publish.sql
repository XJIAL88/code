-- INSERT INTO `db_activity_group`
-- (`id`, `title`, `create_at`) VALUES
-- (1, '全部用户', UNIX_TIMESTAMP());

--生成超级管理员
INSERT INTO `db_admin` (`id`, `role_uuid`, `role_name`, `username`, `nickname`, `token`, `token_over_at`, `status`, `create_at`, `update_at`) VALUES ('1', '5ed8a87b9d564a66b99a537d254004d7', '超级管理员', 'wanghao1', 'wanghao1', '', 0, '1', 0, 0);