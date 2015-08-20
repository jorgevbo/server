ALTER TABLE `media_server`
ADD `heartbeat_time` DATETIME
AFTER `updated_at`;

ALTER TABLE `media_server`
ADD `partner_id` INTEGER
AFTER `id`;

ALTER TABLE `media_server`
ADD `status` INTEGER
AFTER `dc`;
