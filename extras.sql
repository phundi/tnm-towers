
CREATE TABLE `payment_requests` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `phone_number` varchar(50) NOT NULL,
  `amount` int(11) NOT NULL,
  `status` tinyint(1) UNSIGNED DEFAULT '0',
  `type` varchar(50) NOT NULL,
  `pro_plan` tinyint(1) DEFAULT '0',
  `via` varchar(50) NOT NULL,
  `verified_at` datetime DEFAULT '0000-00-00 00:00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

