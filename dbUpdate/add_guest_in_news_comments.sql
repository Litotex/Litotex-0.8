ALTER TABLE `lttx1_news_comments`  ADD COLUMN `author_name` VARCHAR(200) NULL AFTER `IP`,  ADD COLUMN `author_mail` VARCHAR(200) NULL AFTER `author_name`;