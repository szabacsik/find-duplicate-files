CREATE DATABASE duplicates CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

CREATE TABLE `hash`
(
    `id`         INT UNSIGNED                                                  NOT NULL AUTO_INCREMENT,
    `hash`       VARCHAR(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP                            NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE `hash_index` (`hash`),
    INDEX `created_at_index` (`created_at`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  DEFAULT CHARSET = utf8mb4
  COLLATE utf8mb4_general_ci;

CREATE TABLE `file`
(
    `id`         INT UNSIGNED                                                  NOT NULL AUTO_INCREMENT,
    `path`       VARCHAR(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
    `hash_id`    INT UNSIGNED                                                  NOT NULL,
    `atime`      DATETIME                                                      NOT NULL,
    `ctime`      DATETIME                                                      NOT NULL,
    `mtime`      DATETIME                                                      NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP                            NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE `path_index` (`path`),
    INDEX `hash_id_index` (`hash_id`),
    INDEX `created_at_index` (`created_at`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  DEFAULT CHARSET = utf8mb4
  COLLATE utf8mb4_general_ci;

/*
 https://www.eversql.com/mysql-datetime-vs-timestamp-column-types-which-one-i-should-use/
 MySQL converts TIMESTAMP values from the current time zone to UTC for storage,
 and back from UTC to the current time zone for retrieval. (This does not occur for other types such as DATETIME.)”.
 */