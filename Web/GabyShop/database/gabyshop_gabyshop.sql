-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 22, 2011 at 12:26 PM
-- Server version: 5.0.92
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `gabyshop_gabyshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE IF NOT EXISTS `brands` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(256) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`) VALUES
(1, 'Varun.los'),
(2, 'Chanel'),
(3, 'Paris Hilton'),
(4, 'Louis Vuitton'),
(5, 'Burbery'),
(6, 'Bandicoot');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE IF NOT EXISTS `carts` (
  `id` int(11) NOT NULL auto_increment,
  `checkout_datetime` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=182 ;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE IF NOT EXISTS `cart_items` (
  `id` int(11) NOT NULL auto_increment,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=488 ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(256) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(3, 'Túi Xách'),
(4, 'Sắc - Ví');

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE IF NOT EXISTS `cities` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(256) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=56 ;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`) VALUES
(52, 'Lào Cai'),
(51, 'Bắc Ninh'),
(50, 'Bắc Giang'),
(49, 'Thái Bình'),
(48, 'Hà Tĩnh'),
(42, 'Hà Nội'),
(44, 'Đà Nẵng'),
(45, 'Hải Phòng'),
(54, 'Hồ Chí Minh'),
(47, 'Nghệ An');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(11) NOT NULL auto_increment,
  `full_name` varchar(256) collate utf8_unicode_ci NOT NULL,
  `address` varchar(256) collate utf8_unicode_ci default NULL,
  `email` varchar(256) collate utf8_unicode_ci default NULL,
  `birthday` date default NULL,
  `city_id` int(11) default NULL,
  `note` varchar(512) collate utf8_unicode_ci default NULL,
  `phone` varchar(64) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `export_bills`
--

CREATE TABLE IF NOT EXISTS `export_bills` (
  `id` int(11) NOT NULL auto_increment,
  `stock_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `export_datetime` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `export_bill_items`
--

CREATE TABLE IF NOT EXISTS `export_bill_items` (
  `id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `export_bill_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `global`
--

CREATE TABLE IF NOT EXISTS `global` (
  `id` int(11) NOT NULL auto_increment,
  `about` text collate utf8_unicode_ci NOT NULL,
  `help` text collate utf8_unicode_ci NOT NULL,
  `contact` text collate utf8_unicode_ci NOT NULL,
  `count_stats` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `global`
--

INSERT INTO `global` (`id`, `about`, `help`, `contact`, `count_stats`) VALUES
(1, '<p style="text-align: justify;"><span style="font-size: small;"><strong>Welcome to Gaby shop!</strong></span></p>\r\n<p style="text-align: justify;"><span style="font-size: small;">Gaby shop chuy&ecirc;n cung cấp sỉ v&agrave; lẻ c&aacute;c loại t&uacute;i x&aacute;ch, sắc đầm v&agrave; v&iacute; nữ với kiểu d&aacute;ng đa dạng, hợp thời trang. Kh&aacute;ch h&agrave;ng c&oacute; thể y&ecirc;n t&acirc;m về chất lượng v&agrave; gi&aacute; cả khi mua h&agrave;ng của Gaby shop. Hiện nay, Gaby shop đ&atilde; c&oacute; chi nh&aacute;nh tại H&agrave; Nội v&agrave; TP Hồ Ch&iacute; Minh.</span></p>\r\n<p style="text-align: justify;"><span style="font-size: small;">H&atilde;y nhắn tin hoặc gửi email m&atilde; số sản phẩm hoặc m&atilde; số giỏ h&agrave;ng cho ch&uacute;ng t&ocirc;i, ch&uacute;ng t&ocirc;i sẽ phản hồi trong thời gian sớm nhất.</span></p>\r\n<p style="text-align: justify;"><span style="font-size: small;">Sau khi x&aacute;c nhận tiền đ&atilde; được chuyển v&agrave;o t&agrave;i khoản, ch&uacute;ng t&ocirc;i sẽ th&ocirc;ng b&aacute;o cho kh&aacute;ch h&agrave;ng v&agrave; tiến h&agrave;nh giao h&agrave;ng.</span></p>\r\n<p style="text-align: justify;"><span style="font-size: small;"><strong>Dịch vụ giao h&agrave;ng tận nơi của Gaby shop</strong></span></p>\r\n<p style="text-align: justify;"><span style="font-size: small;">Đối với c&aacute;c kh&aacute;ch h&agrave;ng ở nội th&agrave;nh H&agrave; Nội hoặc TP Hồ Ch&iacute; Minh, sản phẩm sẽ được giao trong ng&agrave;y. Ph&iacute; ship h&agrave;ng dao động từ 15,000 đến 30,000.&nbsp;</span></p>\r\n<p style="text-align: justify;"><span style="font-size: small;">Đối với kh&aacute;ch h&agrave;ng ở ngoại th&agrave;nh H&agrave; Nội, TP Hồ Ch&iacute; Minh v&agrave; c&aacute;c tỉnh tr&ecirc;n to&agrave;n quốc,&nbsp;<span class="Apple-style-span">ch&uacute;ng t&ocirc;i sẽ gửi h&agrave;ng đến địa chỉ m&agrave; kh&aacute;ch h&agrave;ng cung cấp trong v&ograve;ng 2 ng&agrave;y kể từ ng&agrave;y đặt h&agrave;ng.&nbsp;</span><span class="Apple-style-span">Ph&iacute; ship h&agrave;ng sẽ được t&iacute;nh theo gi&aacute; bưu điện.</span></span></p>\r\n<p style="text-align: justify;"><span style="font-size: small;"><strong>Dịch vụ bảo h&agrave;nh sản phẩm của Gaby shop</strong></span></p>\r\n<p style="text-align: justify;"><span style="font-size: small;">Kh&aacute;ch h&agrave;ng được đổi sản phẩm đ&atilde; mua sang sản phẩm kh&aacute;c trong v&ograve;ng 3 ng&agrave;y kể từ ng&agrave;y mua h&agrave;ng với điệu kiện sản phẩm chưa sử dụng v&agrave; c&ograve;n nguy&ecirc;n tem m&aacute;c. Việc đổi sản phẩm chỉ &aacute;p dụng với đổi sản phẩm ngang gi&aacute; hoặc đổi sản phẩm hơn gi&aacute;.</span></p>\r\n<p style="text-align: justify;"><span style="font-size: small;">Sản phẩm mua tại Gaby shop được bảo h&agrave;nh trong v&ograve;ng 1 th&aacute;ng kể từ ng&agrave;y mua h&agrave;ng. C&aacute;c sản phẩm được bảo h&agrave;nh l&agrave; c&aacute;c sản phẩm bị hư hỏng do lỗi kỹ thuật. Gaby shop kh&ocirc;ng nhận bảo h&agrave;nh c&aacute;c sản phẩm bị hỏng do d&ugrave;ng sai quy c&aacute;ch hoặc c&aacute;c sản phẩm kh&ocirc;ng phải do Gaby shop ph&acirc;n phối.</span></p>\r\n<p style="text-align: justify;"><span style="font-size: small;">Kh&aacute;ch h&agrave;ng vui l&ograve;ng để lại th&ocirc;ng tin c&aacute; nh&acirc;n để được cung cấp dịch vụ bảo h&agrave;nh n&agrave;y.</span></p>\r\n<p style="text-align: justify;"><span style="font-size: small;"><strong>Dịch vụ kh&aacute;ch h&agrave;ng th&acirc;n thiết</strong></span></p>\r\n<p style="text-align: justify;"><span style="font-size: small;">Gaby shop lu&ocirc;n c&oacute; ch&iacute;nh s&aacute;ch ưu đ&atilde;i tốt nhất đối với c&aacute;c kh&aacute;ch h&agrave;ng th&agrave;nh vi&ecirc;n v&agrave; kh&aacute;ch h&agrave;ng th&acirc;n thiết.</span></p>\r\n<p style="text-align: justify;"><span style="font-size: small;">Khi mua h&agrave;ng của Gaby shop, kh&aacute;ch h&agrave;ng h&atilde;y để lại c&aacute;c th&ocirc;ng tin về họ t&ecirc;n, số điện thoại, địa chỉ, email, ... Gaby shop sẽ lưu lại th&ocirc;ng tin của kh&aacute;ch h&agrave;ng để t&iacute;ch lũy điểm. Tương đương với c&aacute;c mức t&iacute;ch lũy bạn sẽ nhận được c&aacute;c ưu đ&atilde;i sau:</span></p>\r\n<p style="padding-left: 30px;"><span style="font-size: small;">* T&iacute;ch lũy được 1 triệu đồng, bạn sẽ được giảm 5% cho c&aacute;c lần mua h&agrave;ng tiếp theo.</span><br /><span style="font-size: small;">* T&iacute;ch lũy được 2 triệu đồng, bạn sẽ được giảm 10% cho c&aacute;c lần mua h&agrave;ng tiếp theo.</span><br /><span style="font-size: small;">* T&iacute;ch lũy được 3 triệu đồng, bạn sẽ được giảm 15% cho c&aacute;c lần mua h&agrave;ng tiếp theo.</span><br /><span style="font-size: small;">* T&iacute;ch lũy được 5 triệu đồng, bạn sẽ được giảm 20% cho c&aacute;c lần mua h&agrave;ng tiếp theo.</span></p>\r\n<p style="text-align: justify; padding-left: 30px;"><span style="font-size: x-small;"><em>(Trong v&ograve;ng 12 th&aacute;ng nếu kh&aacute;ch h&agrave;ng kh&ocirc;ng mua lặp lại th&igrave; số tiền đ&atilde; t&iacute;ch lũy sẽ trở về 0.)</em></span></p>\r\n<p style="text-align: justify;"><span style="font-size: small;">N<span class="Apple-style-span">go&agrave;i ra, kh&aacute;ch h&agrave;ng sẽ nhận được th&ocirc;ng tin về c&aacute;c chương tr&igrave;nh khuyến m&atilde;i qua email v&agrave; qu&agrave; tặng v&agrave;o ng&agrave;y sinh nhật.</span></span></p>', '<p><span class="Apple-style-span" style="font-family: verdana, geneva; color: #888888;"><strong><span style="font-size: medium;">Under construction <img title="Smile" src="/views/tiny_mce/plugins/emotions/img/smiley-smile.gif" alt="Smile" border="0" /><br /></span></strong></span></p>', '<p><span style="font-size: small;"><strong>Tại TP Hồ Ch&iacute; Minh</strong></span></p>\r\n<p style="padding-left: 30px;"><span style="font-size: small;">Ms. Phương Thanh<br />Điện thoại: <em>0987918796</em><br />Email: <em><a href="mailto:pttran87@gmail.com">pttran87@gmail.com</a> hoặc <a href="mailto:phuongthanh@gaby-shop.com">phuongthanh@gaby-shop.com</a></em><br />T&agrave;i khoản ng&acirc;n h&agrave;ng Vietcombank: <em>Trần Phương Thanh - 001 100 3316 973</em></span></p>\r\n<p><span style="font-size: small;"><strong>Tại H&agrave; Nội</strong></span></p>\r\n<p style="padding-left: 30px;"><span style="font-size: small;">Ms. Th&ugrave;y Linh<br />Điện thoại: <em>0983871412</em><br />Email: <em><a href="mailto:ptlinh1412@yahoo.com.vn">ptlinh1412@yahoo.com.vn</a></em><br />T&agrave;i khoản ng&acirc;n h&agrave;ng Vietinbank: <em>Đặng Thị Thanh - 711A 1173 7082</em></span></p>', 8865);

-- --------------------------------------------------------

--
-- Table structure for table `import_bills`
--

CREATE TABLE IF NOT EXISTS `import_bills` (
  `id` int(11) NOT NULL auto_increment,
  `stock_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `import_datetime` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `import_bill_items`
--

CREATE TABLE IF NOT EXISTS `import_bill_items` (
  `id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `import_bill_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE IF NOT EXISTS `members` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(32) collate utf8_unicode_ci NOT NULL,
  `full_name` varchar(256) collate utf8_unicode_ci default NULL,
  `password` varchar(32) collate utf8_unicode_ci NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `username`, `full_name`, `password`, `group_id`) VALUES
(1, 'katatunix', 'Kata Tunix', 'g1gabyt3?ac', 1),
(2, 'soicon', 'Sói Con', 'a1b2c3d4', 1);

-- --------------------------------------------------------

--
-- Table structure for table `member_groups`
--

CREATE TABLE IF NOT EXISTS `member_groups` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(256) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `member_groups`
--

INSERT INTO `member_groups` (`id`, `name`) VALUES
(1, 'Admin'),
(2, 'Mod');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL auto_increment,
  `code` varchar(512) collate utf8_unicode_ci NOT NULL,
  `price_fonds` int(11) NOT NULL,
  `price_sell` int(11) NOT NULL,
  `category_id` int(11) default NULL,
  `brand_id` int(11) default NULL,
  `description` varchar(1024) collate utf8_unicode_ci default NULL,
  `pics` varchar(1024) collate utf8_unicode_ci default NULL,
  `seo_url` varchar(256) collate utf8_unicode_ci default NULL,
  `order_in_cat` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=22 ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `code`, `price_fonds`, `price_sell`, `category_id`, `brand_id`, `description`, `pics`, `seo_url`, `order_in_cat`) VALUES
(1, 'TX12-0-GR', 275000, 465000, 3, 1, 'Chất liệu: da mềm\r\nKích thước: 24cm x 30cm x 7cm\r\nMàu: xanh rêu\r\nPhụ kiện đi kèm: quai đeo chéo', 'IMG_3267_small.jpg/IMG_3267_medium.jpg/IMG_3267_large.jpg/IMG_3267.jpg', 'tui-xach-varunlos-TX12-0-GR', 7),
(2, 'TX10-2-RE', 394000, 745000, 3, 1, 'Chất liệu: da mềm\r\nKích thước: 30cm x 40cm x 7cm\r\nMàu: nâu đỏ\r\nPhụ kiện đi kèm: quai đeo chéo\r\n3 ngăn rộng, thích hợp đi làm hoặc đi du lịch', 'IMG_3280_small.jpg/IMG_3280_medium.jpg/IMG_3280_large.jpg/IMG_3280.jpg', 'tui-xach-varunlos-TX10-2-RE', 11),
(3, 'TX09-2-BR', 394000, 745000, 3, 1, 'Da mềm', 'IMG_3283_small.jpg/IMG_3283_medium.jpg/IMG_3283_large.jpg/IMG_3283.jpg', 'tui-xach-varunlos-TX09-2-BR', 12),
(4, 'TX01-0-BU', 285000, 575000, 3, 1, 'Chất liệu: da bóng\r\nKích thước: 16cm x 30cm x 5cm\r\nMàu: xanh ngọc\r\nPhụ kiện đi kèm: dây đeo\r\n2 ngăn, khóa kéo, dạng xếp ly', 'IMG_3316_small.jpg/IMG_3316_medium.jpg/IMG_3316_large.jpg/IMG_3316.jpg', 'tui-xach-varunlos-TX01-0-BU', 3),
(5, 'TX02-1-BL', 290000, 585000, 3, 1, 'Chất liệu: da mềm, họa tiết da hươu\r\nKích thước: 30cm x 35cm x 11cm\r\n3 ngăn rộng, dáng tròn, khóa kéo và đóng nút', 'IMG_3269_small.jpg/IMG_3269_medium.jpg/IMG_3269_large.jpg/IMG_3269.jpg', 'tui-xach-varunlos-TX02-1-BL', 9),
(6, 'TX11-0-PI', 275000, 545000, 3, 1, 'Chất liệu: da mềm\r\nKích thước: 12cm x 32cm x 6cm\r\nMàu: hồng đậm\r\n3 ngăn tách rời, khóa kéo, dạng thuyền', 'IMG_3313_small.jpg/IMG_3313_medium.jpg/IMG_3313_large.jpg/IMG_3313.jpg', 'tui-xach-varunlos-TX11-0-PI', 4),
(7, 'TX05-1-PI', 345000, 585000, 3, 3, 'Chất liệu: da mềm\r\nKích thước: 30cm x 30cm x 5cm\r\nMàu: hồng đậm\r\nPhụ kiện đi kèm: quai đeo\r\n3 ngăn, khóa kéo, nắp đậy cách điệu', 'IMG_3288_small.jpg/IMG_3288_medium.jpg/IMG_3288_large.jpg/IMG_3288.jpg', 'tui-xach-paris-hilton-TX05-1-PI', 10),
(8, 'TX08-2-GY', 315000, 485000, 3, 1, 'Chất liệu: da mềm\r\nKích thước: 30cm x 40cm x 9cm\r\nMàu: ghi xám\r\nĐính đá, 2 ngăn rộng, khóa kéo', 'IMG_3286_small.jpg/IMG_3286_medium.jpg/IMG_3286_large.jpg/IMG_3286.jpg', 'tui-xach-varunlos-TX08-2-GY', 13),
(9, 'TX07-1-BR', 292000, 445000, 3, 5, 'Chất liệu: da mềm và vải thô\r\nKích thước: 30cm x 35cm x 10cm\r\nMàu: nâu\r\n2 ngăn, khóa kéo\r\nThay đổi kiểu dáng bằng cách nới lỏng hoặc thắt chặt dây đai', 'IMG_32_small.jpg/IMG_32_medium.jpg/IMG_32_large.jpg/IMG_32.jpg', 'tui-xach-burbery-TX07-1-BR', 14),
(10, 'TX13-0-VI', 340000, 645000, 3, 1, 'Chất liệu: da bóng\r\nKích thước: 17cm x 30cm x 6cm\r\nMàu: hồng, tím, cam, đen\r\nPhụ kiện đi kèm: quai đeo chéo', 'IMG_3303_small.jpg/IMG_3303_medium.jpg/IMG_3303_large.jpg/IMG_3303.jpg', 'tui-xach-varunlos-TX13-0-VI', 1),
(11, 'SA03-GR', 310000, 575000, 4, 1, 'Chất liệu: da thật | Màu: tím, xám, vàng\r\nKích thước: 14cm x 25cm x 3cm\r\nPhụ kiện đi kèm: quai đeo\r\n1 ngăn, kéo khóa và đóng nút\r\nCầm tay, đeo vai, đeo chéo\r\nThích hợp với các buổi dã ngoại và tiệc ngoài trời', 'IMG_3299_small.jpg/IMG_3299_medium.jpg/IMG_3299_large.jpg/IMG_3299.jpg', 'sac-vi-varunlos-SA03-GR', 1),
(12, 'TX04-1-BR', 258000, 485000, 3, 1, 'Chất liệu: da mềm\r\nKích thước: 20cm x 30cm x 5cm\r\nMàu: nâu\r\nPhụ kiện đi kèm: quai đeo\r\n1 ngăn, khóa kéo\r\nCó thể xách tay, đeo vai, đeo chéo', 'IMG_3314_small.jpg/IMG_3314_medium.jpg/IMG_3314_large.jpg/IMG_3314.jpg', 'tui-xach-varunlos-TX04-1-BR', 6),
(13, 'TX03-0-PI', 285000, 485000, 3, 1, 'Chất liệu: da thật, họa tiết da rắn\r\nKích thước: 14cm x 28cm x 7cm\r\nMàu: hồng, đen\r\nPhụ kiện đi kèm: quai đeo\r\n2 ngăn, khóa kéo và đóng nút', 'IMG_3292_small.jpg/IMG_3292_medium.jpg/IMG_3292_large.jpg/IMG_3292.jpg', 'tui-xach-varunlos-TX03-0-PI', 5),
(14, 'TX06-1-RE', 285000, 565000, 3, 1, 'Chất liệu: da mềm\r\nKích thước: 27cm x 35cm x 11cm\r\nMàu: đỏ\r\nPhụ kiện đi kèm: quai đeo\r\n2 ngăn, khóa kéo, gắn hoa', 'IMG_3284_small.jpg/IMG_3284_medium.jpg/IMG_3284_large.jpg/IMG_3284.jpg', 'tui-xach-varunlos-TX06-1-RE', 8),
(15, 'SA02-RE', 310000, 595000, 4, 1, 'Chất liệu: da thật\r\nKích thước: 12cm x 22cm x 5cm\r\nMàu: đỏ\r\nPhụ kiện đi kèm: dây đeo\r\n2 ngăn, khóa kéo và khóa cài\r\nCầm tay, đeo vai, đeo chéo', 'IMG_3296_small.jpg/IMG_3296_medium.jpg/IMG_3296_large.jpg/IMG_3296.jpg', 'sac-vi-varunlos-SA02-RE', 0),
(16, 'TX15-0-BE', 275000, 565000, 3, 2, 'Chất liệu: da mềm\r\nKích thước: 18cm x 32cm x 4cm\r\nMàu: nâu nhạt\r\n2 ngăn, xích viền xung quanh', 'IMG_3289_small.jpg/IMG_3289_medium.jpg/IMG_3289_large.jpg/IMG_3289.jpg', 'tui-xach-chanel-TX15-0-BE', 2),
(18, 'TX14-0-WH', 115000, 245000, 3, 2, 'Chất liệu: da mềm\r\nKích thước: 16cm x 28cm x 3cm\r\nMàu: trắng\r\nPhụ kiện đi kèm: quai đeo\r\n1 ngăn, khóa kéo và nút đóng\r\nCó thể cầm tay, đeo chéo', 'IMG_3304_small.jpg/IMG_3304_medium.jpg/IMG_3304_large.jpg/IMG_3304.jpg', 'tui-xach-chanel-TX14-0-WH', 0),
(19, 'VI01-BR', 278000, 485000, 4, 6, 'Chất liệu: da thật\r\nKích thước: 11cm x 20cm x 1cm\r\nMàu: nâu\r\nPhụ kiện đi kèm: quai đeo cổ tay\r\nKẻ ca-rô, nhiều ngăn, đựng tiền, card-visit', 'IMG_3310_small.jpg/IMG_3310_medium.jpg/IMG_3310_large.jpg/IMG_3310.jpg', 'sac-vi-bandicoot-VI01-BR', 4),
(20, 'VI02-BR', 195000, 375000, 4, 4, 'Kẻ ca-rô', 'IMG_3311_small.jpg/IMG_3311_medium.jpg/IMG_3311_large.jpg/IMG_3311.jpg', 'sac-vi-louis-vuitton-VI02-BR', 2),
(21, 'VI03-BL', 105000, 185000, 4, 4, 'Chất liệu: da bóng | Màu: đen\r\nKích thước: 9cm x 18cm x 2cm\r\nPhụ kiện đi kèm: dây xích nhỏ\r\nHọa tiết hoa in chìm\r\nNhiều ngăn, đựng tiền, card-visit, giấy tờ\r\nCầm tay hoặc đeo vai', 'IMG_3312_small.jpg/IMG_3312_medium.jpg/IMG_3312_large.jpg/IMG_3312.jpg', 'sac-vi-louis-vuitton-VI03-BL', 3);

-- --------------------------------------------------------

--
-- Table structure for table `promos`
--

CREATE TABLE IF NOT EXISTS `promos` (
  `id` int(11) NOT NULL auto_increment,
  `subject` varchar(512) collate utf8_unicode_ci NOT NULL,
  `content` text collate utf8_unicode_ci NOT NULL,
  `promo_date` date NOT NULL,
  `seo_url` varchar(256) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE IF NOT EXISTS `stocks` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(256) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `name`) VALUES
(1, 'Kho HCM');

-- --------------------------------------------------------

--
-- Table structure for table `transfer_bills`
--

CREATE TABLE IF NOT EXISTS `transfer_bills` (
  `id` int(11) NOT NULL auto_increment,
  `source_stock_id` int(11) NOT NULL,
  `destination_stock_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `transfer_datetime` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `transfer_bill_items`
--

CREATE TABLE IF NOT EXISTS `transfer_bill_items` (
  `id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `transfer_bill_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
