DROP TABLE IF EXISTS `test_board`;
CREATE TABLE `test_board` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '일련번호',
  `subject` varchar(255) NOT NULL COMMENT '제목',
  `content` varchar(255) NOT NULL COMMENT '내용',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='테스트게시판';
