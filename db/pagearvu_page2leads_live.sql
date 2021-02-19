-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 18, 2021 at 06:44 AM
-- Server version: 10.3.27-MariaDB-cll-lve
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pagearvu_page2leads_live`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@trigvent.com', NULL, '$2y$10$5P/VDkN/Ni71rHCiQrc9keQvs6RZw5HvBRN8FNak0Mx0mCqdyNsDq', NULL, '2020-10-17 05:08:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `all_leads`
--

CREATE TABLE `all_leads` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `url` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `all_leads`
--

INSERT INTO `all_leads` (`id`, `user_id`, `name`, `email`, `url`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'info@trigvent.com', 'https://trigvent.com/about-us/', '2020-12-08 10:34:49', '2020-12-08 10:34:49'),
(2, 1, NULL, 'info@trigvent.com', 'https://trigvent.com/about-us/', '2020-12-08 10:35:11', '2020-12-08 10:35:11'),
(3, 1, NULL, 'info@trigvent.com', 'https://trigvent.com/about-us/', '2020-12-08 10:39:43', '2020-12-08 10:39:43'),
(4, 1, NULL, 'info@trigvent.com', 'https://trigvent.com/about-us/', '2020-12-08 10:40:31', '2020-12-08 10:40:31'),
(5, 1, NULL, 'info@trigvent.com', 'https://trigvent.com/about-us/', '2020-12-08 10:48:41', '2020-12-08 10:48:41'),
(6, 1, NULL, 'info@trigvent.com', 'https://trigvent.com/about-us/', '2020-12-08 10:53:58', '2020-12-08 10:53:58'),
(7, 1, NULL, 'support@kartrocket.com', 'https://www.dealcliq.com/', '2020-12-08 17:04:42', '2020-12-08 17:04:42'),
(8, 1, NULL, 'sales@netsolutions.com', 'https://www.netsolutions.com/contact-us', '2020-12-09 16:50:21', '2020-12-09 16:50:21'),
(9, 1, NULL, 'careers@netsolutions.com', 'https://www.netsolutions.com/contact-us', '2020-12-09 16:50:21', '2020-12-09 16:50:21'),
(10, 1, NULL, 'info@netsolutions.com', 'https://www.netsolutions.com/contact-us', '2020-12-09 16:50:21', '2020-12-09 16:50:21'),
(11, 1, '123', 'email1', 'test', '2021-01-08 12:38:11', '2021-01-08 12:38:11'),
(12, 1, NULL, 'email2', 'test', '2021-01-08 12:38:29', '2021-01-08 12:38:29'),
(13, 1, '123', 'email3', 'test', '2021-01-08 12:44:17', '2021-01-08 12:44:17'),
(14, 1, NULL, 'email4', 'test', '2021-01-08 12:54:18', '2021-01-08 12:54:18'),
(15, 1, 'Shivam', 'shivambhandari00@gmail.com', 'https://www.google.com/', '2021-01-08 14:52:29', '2021-01-08 14:52:29'),
(16, 1, NULL, 'robert@broofa.com', 'https://www.google.com/', '2021-01-08 14:52:29', '2021-01-08 14:52:29'),
(18, 1, 'fdgdfgfdg', 'shiv@yahoo.in', 'http://localhost/lazyload/', '2021-01-14 10:15:15', '2021-01-14 10:15:15'),
(19, 1, NULL, 'richagadh1997@gmail.com', 'https://www.google.com/', '2021-01-14 11:30:26', '2021-01-14 11:30:26'),
(20, 1, NULL, 'shiv@yopmail.com', 'http://localhost/lazyload/', '2021-01-14 12:48:58', '2021-01-14 12:48:58'),
(21, 1, NULL, 'shivam@gmail.com', 'http://localhost/lazyload/', '2021-01-14 12:48:58', '2021-01-14 12:48:58'),
(22, 1, NULL, 'trigvent@gmail.com', 'https://www.google.com/', '2021-01-14 13:54:54', '2021-01-14 13:54:54'),
(23, 1, NULL, 'nitinsaluja98@gmail.com', 'https://www.google.com/', '2021-01-14 13:54:54', '2021-01-14 13:54:54');

-- --------------------------------------------------------

--
-- Table structure for table `autoresponders`
--

CREATE TABLE `autoresponders` (
  `id` int(11) NOT NULL,
  `autoresponder_list_id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `field_one_value` varchar(255) DEFAULT NULL COMMENT 'api_url',
  `field_two_value` text DEFAULT NULL COMMENT 'api_key',
  `field_three_value` varchar(255) DEFAULT NULL COMMENT 'app_path',
  `tagid` varchar(500) DEFAULT '0' COMMENT 'for activecampaign only',
  `tag_name` varchar(255) DEFAULT NULL,
  `field_four` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `autoresponders`
--

INSERT INTO `autoresponders` (`id`, `autoresponder_list_id`, `userId`, `field_one_value`, `field_two_value`, `field_three_value`, `tagid`, `tag_name`, `field_four`, `status`, `created_at`, `updated_at`) VALUES
(35, 1, 1, '0882d361e6ed229aac6d036eae6e8c70-us4', 'eb71722de6', '', '0', NULL, NULL, 0, NULL, '2021-01-18 09:39:18'),
(37, 5, 1, 'ZDFwWxNU75vaQ-jV5PBA7aqSjkWRNwoAlbqEV-PwJck', 'WlqA2dgW7cAu4QYi5xZAyg', 'FB Busy Mums Group', '0', NULL, NULL, 0, NULL, '2021-01-08 15:12:28'),
(38, 8, 1, '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', '3', '', '0', NULL, NULL, 0, NULL, '2021-01-08 11:15:35'),
(39, 4, 1, 'f410563f678c0864d18645f7bfe0c936', '57618952', '', '0', NULL, NULL, 1, NULL, '2021-01-18 12:06:31'),
(40, 2, 1, 'gu2xvsm9hwtuylxgr8gesxhml5slpn95', 'a_healthy_living_community', '', '0', NULL, NULL, 1, NULL, '2021-01-18 12:06:29'),
(41, 18, 1, 'bowpFnaE', 'dSmwXaEqMnuk', 'sachintest', '0', NULL, NULL, 0, NULL, '2021-01-08 11:15:28'),
(42, 23, 1, 'b6e306c3-edae-4a7e-b46c-d3526561e3a5', '1', '', '0', NULL, NULL, 0, NULL, '2021-01-08 11:15:27'),
(43, 7, 1, 'pk_5c1bbc6b0e39164253a6465067016c9c00', 'THJjLc', '', '0', NULL, NULL, 0, NULL, '2021-01-08 11:15:35'),
(44, 15, 1, '7014667', '07f8b6bfc3f686bc972318118e28425d', '', '0', NULL, NULL, 0, NULL, '2021-01-08 11:15:28'),
(45, 21, 1, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImZjZGExNGU3ZjlhNmNhM2NmNGViOTdlZjg3MmU3ZTFkMDMzNzFhODc4ZTFlMTczYTMwNzBlODdiMWRiNjA0OGUyMzIzNDA1ZGQwYWU1OWQzIn0.eyJhdWQiOiI0IiwianRpIjoiZmNkYTE0ZTdmOWE2Y2EzY2Y0ZWI5N2VmODcyZTdlMWQwMzM3MWE4NzhlMWUxNzNhMzA3MGU4N2I', '175621', '', '0', NULL, NULL, 0, NULL, '2021-01-08 11:15:28'),
(46, 19, 1, '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', 'ffa3b6e2-9053-404a-8af9-a4499ea8b925', '', '0', NULL, NULL, 0, NULL, '2021-01-08 11:15:27'),
(47, 10, 1, '0e73951a-9320-485a-9626-4a8b4640d07b', 'u4p4n678je7rxa4vd6xm4572', '1839818667', '0', NULL, NULL, 0, NULL, '2021-01-08 11:15:29');

-- --------------------------------------------------------

--
-- Table structure for table `autoresponder_list`
--

CREATE TABLE `autoresponder_list` (
  `id` int(20) NOT NULL,
  `responder_type` varchar(255) CHARACTER SET utf8 NOT NULL,
  `responder_key` varchar(200) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `field_one_validation` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL,
  `field_two_validation` varchar(255) DEFAULT NULL,
  `field_three_validation` varchar(255) DEFAULT NULL,
  `notice` text DEFAULT NULL,
  `status` int(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `autoresponder_list`
--

INSERT INTO `autoresponder_list` (`id`, `responder_type`, `responder_key`, `logo`, `field_one_validation`, `field_two_validation`, `field_three_validation`, `notice`, `status`, `created_at`, `updated_at`) VALUES
(1, 'MailChimp', 'mailchimp', 'mailchimp2.png', '{\"label\":\"*API key \",\"en\":{\"label\":\"*API key\"},\"ch\":{\"label\":\"c*API key\"},\"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"1\"}', '{\"label\":\"*Audience ID \",\"en\":{\"label\":\"*Audience ID \"},\"ch\":{\"label\":\"c*Audience ID \"},\"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"2\"}', '{\"label\":\"Tag Name \",\"en\":{\"label\":\"Tag Name\"},\"ch\":{\"label\":\"\"},\"required\":false,\"type\":\"text\",\"show\":true,\"order\":\"3\"}', 'Here is a short tutorial on how to integrate <a href=\'https://docs.groupleads.net/article/19-mailchimp\' target=\'_blank\'>Group Leads with Mailchimp <span class=\'fa fa-arrow-right\'></span></a>.', 1, '2019-08-16 00:00:00', NULL),
(2, 'GetResponse', 'getresponse', 'getresponse.png', '{\"label\":\"*API key \",\"en\":{\"label\":\"*API key\"},\"ch\":{\"label\":\"c*API key\"},\"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"1\"}', '{\"label\":\"*List Name\",\"en\":{\"label\":\"*List Name\"},\"ch\":{\"label\":\"c*List Name\"},\"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"2\"}', NULL, 'Here is a short tutorial on how to integrate <a href=\'https://docs.groupleads.net/article/17-getresponse\' target=\'_blank\'>Group Leads with Getresponse <span class=\'fa fa-arrow-right\'></span></a>', 1, '2019-08-16 00:00:00', NULL),
(3, 'Sendy', 'sendy', 'mailchimp.png', '{\"label\":\"*API Path\",\"en\":{\"label\":\"*API Path\"},\"ch\":{\"label\":\"* API路径\"},\"required\":true,\"type\":\"url\",\"show\":true}', '{\"label\":\"*API key \",\"en\":{\"label\":\"*API key\"},\"ch\":{\"label\":\"c*API key\"},\"required\":true,\"type\":\"text\",\"show\":true}', '{\"label\":\"*List ID \",\"en\":{\"label\":\"*List ID \"},\"ch\":{\"label\":\"c*List ID \"},\"required\":true,\"type\":\"text\",\"show\":true}', 'Here is a short tutorail on how to integrate <a href=\'https://docs.groupleads.net/article/18-sendy\' target=\'_blank\'>Group Leads with Sendy <span class=\'fa fa-arrow-right\'></span></a>', 0, '2019-08-16 00:00:00', NULL),
(4, 'Mailer Lite', 'mailerlite', 'mailerlite.png', '{\"label\":\"*API key \",\"en\":{\"label\":\"*API key\"},\"ch\":{\"label\":\"c*API key\"},\"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"1\"}', '{\"label\":\"*List ID\",\"en\":{\"label\":\"*List ID\"},\"ch\":{\"label\":\"c*List ID\"},\"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"2\"}', NULL, 'Here is a short tutorial on how to integrate <a href=\'https://docs.groupleads.net/article/16-mailerlite\' target=\'_blank\'>Group Leads with Mailerlite <span class=\'fa fa-arrow-right\'></span></a>', 1, '2019-08-16 00:00:00', NULL),
(5, 'ConvertKit', 'convertkit', 'convertkit.png', '{\"label\":\"*API Secret\",\"en\":{\"label\":\"*API Secret\"},\"ch\":{\"label\":\"c*API Secret\"},\"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"1\"}', '{\"label\":\"*API key\",\"en\":{\"label\":\"*API key\"},\"ch\":{\"label\":\"c*API key\"},\"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"2\"}', '{\"label\":\"*Exact Tag Name \",\"en\":{\"label\":\"*Exact Tag Name\"},\"ch\":{\"label\":\"c*Exact Tag Name\"},\"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"3\"}', 'Here is a short tutorial on how to integrate <a href=\'https://docs.groupleads.net/article/82-how-to-integrate-group-leads-with-convertkit\' target=\'_blank\'>Group Leads with Convertkit <span class=\'fa fa-arrow-right\'></span></a>', 1, '2019-08-16 00:00:00', NULL),
(6, 'Active Campaign', 'activecampaign', 'activecampaign.png', '{\"label\":\"*App Url\",\"en\":{\"label\":\"*App Url\"},\"ch\":{\"label\":\"c*App Url\"},\"required\":true,\"type\":\"url\",\"show\":true,\"order\":\"1\"}', '{\"label\":\"*API key \",\"en\":{\"label\":\"*API key\"},\"ch\":{\"label\":\"c*API key\"},\"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"2\"}', '{\"label\":\"*List ID \",\"en\":{\"label\":\"*List ID\"},\"ch\":{\"label\":\"c*List ID\"}, \"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"3\"}', 'Here is a short tutorial on how to integrate <a href=\'https://docs.groupleads.net/article/15-active-campaign\' target=\'_blank\'>Group Leads with Active Campaign <span class=\'fa fa-arrow-right\'></span></a>', 1, '2019-08-16 00:00:00', NULL),
(7, 'Klaviyo', 'klaviyo', 'klaviyo.png', '{\"label\":\"*API key \",\"en\":{\"label\":\"*API key\"},\"ch\":{\"label\":\"c*API key\"}, \"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"1\"}', '{\"label\":\"*List ID\",\"en\":{\"label\":\"*List ID\"},\"ch\":{\"label\":\"c*List ID\"},\"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"2\"}', NULL, 'Here is a short tutorial on how to integrate <a href=\'https://docs.groupleads.net/article/78-how-to-integrate-group-leads-with-klaviyo\' target=\'_blank\'>Group Leads with Klaviyo <span class=\'fa fa-arrow-right\'></span></a>', 1, '2019-08-16 00:00:00', NULL),
(8, 'Sendinblue', 'sendinblue', 'sendinblue.png', '{\"label\":\"*API key\",\"en\":{\"label\":\"*API key\"},\"ch\":{\"label\":\"c*API key\"},\"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"1\"}', '{\"label\":\"*List ID\",\"en\":{\"label\":\"*List ID\"},\"ch\":{\"label\":\"c*List ID\"},\"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"2\"}', NULL, 'Here is a short tutorial on how to integrate <a href=\'https://docs.groupleads.net/article/52-how-to-integrate-group-leads-with-sendlane\' target=\'_blank\'>Group Leads with Sendinblue <span class=\'fa fa-arrow-right\'></span></a>', 1, '2019-08-16 00:00:00', NULL),
(9, 'AcellEmail', 'acellemail', 'mailchimp.png', NULL, '{\"label\":\"*API key\",\"en\":{\"label\":\"*API key\"},\"ch\":{\"label\":\"c*API key\"},\"required\":true,\"type\":\"text\",\"show\":true}', '{\"label\":\"*List ID\",\"en\":{\"label\":\"*List ID\"},\"ch\":{\"label\":\"c*List ID\"},\"required\":true,\"type\":\"text\",\"show\":true}', 'Here is a short tutorial on how to integrate <a href=\'https://docs.groupleads.net/article/75-how-to-integrate-group-leads-with-acellemail\' target=\'_blank\'>Group Leads with Accellemail <span class=\'fa fa-arrow-right\'></span></a>.', 0, '2019-08-16 00:00:00', NULL),
(10, 'Constant Contact', 'constantcontact', 'constantcontact.png', '{\"label\":\"*API Token\",\"en\":{\"label\":\"*API Token\"},\"ch\":{\"label\":\"c*API Token\"},\"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"1\"}', '{\"label\":\"*API key \",\"en\":{\"label\":\"*API key\"},\"ch\":{\"label\":\"c*API key\"},\"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"2\"}', '{\"label\":\"*LIST ID \",\"en\":{\"label\":\"*LIST ID\"},\"ch\":{\"label\":\"c*LIST ID\"},\"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"3\"}', 'Here is a short tutorial on how to integrate <a href=\'https://docs.groupleads.net/article/20-constant-contact\' target=\'_blank\'>Group Leads with Constant Contact <span class=\'fa fa-arrow-right\'></span></a>', 1, '2019-09-23 00:00:00', NULL),
(11, 'Market Hero', 'markethero', 'mailchimp.png', NULL, '{\"label\":\"*API key\",\"en\":{\"label\":\"*API key\"},\"ch\":{\"label\":\"c*API key\"},\"required\":true,\"type\":\"text\",\"show\":true}', '{\"label\":\"*Exact Tag Name\",\"en\":{\"label\":\"*Exact Tag Name\"},\"ch\":{\"label\":\"c*Exact Tag Name\"},\"required\":true,\"type\":\"text\",\"show\":true}', 'Here is a short tutorial on how to integrate <a href=\'https://docs.groupleads.net/article/21-market-hero\' target=\'_blank\'>Group Leads with Market Hero <span class=\'fa fa-arrow-right\'></span></a>', 0, '2019-09-25 03:07:56', NULL),
(12, 'Moosend App', 'moosend', 'mailchimp.png', NULL, '{\"label\":\"*API key\",\"en\":{\"label\":\"*API key\"},\"ch\":{\"label\":\"c*API key\"},\"required\":true,\"type\":\"text\",\"show\":true}', '{\"label\":\"*List ID\",\"en\":{\"label\":\"*List ID\"},\"ch\":{\"label\":\"c*List ID\"},\"required\":true,\"type\":\"text\",\"show\":true}', 'Here is a short tutorial on how to integrate <a href=\'https://docs.groupleads.net/article/77-how-to-integrate-group-leads-with-moosend\' target=\'_blank\'>Group Leads with Moosend <span class=\'fa fa-arrow-right\'></span></a>', 0, '2019-09-25 03:07:56', NULL),
(13, 'Gist', 'getgist', 'mailchimp.png', NULL, '{\"label\":\"*API key \",\"en\":{\"label\":\"*API key\"},\"ch\":{\"label\":\"c*API key\"},\"required\":true,\"type\":\"text\",\"show\":true}', '{\"label\":\"*Exact Tag Name \",\"en\":{\"label\":\"*Exact Tag Name\"},\"ch\":{\"label\":\"c*Exact Tag Name\"},\"required\":true,\"type\":\"text\",\"show\":true}', 'Here is a short tutorial on how to integrate <a href=\'https://docs.groupleads.net/article/80-how-to-integrate-group-leads-with-gist\' target=\'_blank\'>Group Leads with Gist <span class=\'fa fa-arrow-right\'></span></a>', 0, '2019-12-10 04:59:17', NULL),
(14, 'Hot Prospector', 'hotprospector', 'mailchimp.png', '{\"label\":\"*API ID\",\"en\":{\"label\":\"*API ID\"},\"ch\":{\"label\":\"c*API ID\"},\"required\":true,\"type\":\"text\",\"show\":true}', '{\"label\":\"*API key \",\"en\":{\"label\":\"*API key\"},\"ch\":{\"label\":\"c*API key\"},\"required\":true,\"type\":\"text\",\"show\":true}', '{\"label\":\"*GROUP Id \",\"en\":{\"label\":\"*GROUP Id\"},\"ch\":{\"label\":\"c*GROUP Id\"},\"required\":false,\"type\":\"hidden\",\"show\":true}', 'Here is a short tutorial on how to integrate <a href=\'https://docs.groupleads.net/article/79-how-to-integrate-group-leads-with-hot-prospector\' target=\'_blank\'>Group Leads with Hot Prospector <span class=\'fa fa-arrow-right\'></span></a>', 0, '2019-12-19 06:03:10', NULL),
(15, 'Drip', 'drip', 'drip.png', '{\"label\":\"*Account ID\",\"en\":{\"label\":\"*Account ID\"},\"ch\":{\"label\":\"c*Account ID\"},\"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"1\"}', '{\"label\":\"*API Token \",\"en\":{\"label\":\"*API Token\"},\"ch\":{\"label\":\"c*API Token\"},\"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"2\"}', '{\"label\":\"*Select Tag \",\"en\":{\"label\":\"*Select Tag\"},\"ch\":{\"label\":\"c*Select Tag\"},\"required\":false,\"type\":\"hidden\",\"show\":true,\"order\":\"3\"}', 'Here is a short tutorial on how to integrate <a href=\'https://docs.groupleads.net/article/81-how-to-integrate-group-leads-with-drip\' target=\'_blank\'>Group Leads with Drip <span class=\'fa fa-arrow-right\'></span></a>', 1, '2019-12-19 06:03:10', NULL),
(16, 'Send Lane', 'sendlane', 'mailchimp.png', '{\"label\":\"*Hash key\",\"en\":{\"label\":\"*Hash key\"},\"ch\":{\"label\":\"c*Hash key\"},\"required\":true,\"type\":\"text\",\"show\":true}', '{\"label\":\"*API Key \",\"en\":{\"label\":\"*API Key\"},\"ch\":{\"label\":\"c*API Key\"},\"required\":true,\"type\":\"text\",\"show\":true}', '{\"label\":\"*List ID\",\"en\":{\"label\":\"*List ID\"},\"ch\":{\"label\":\"c*List ID\"},\"required\":true,\"type\":\"text\",\"show\":true}', 'If double optin is disabled, leads would show up immediately after approval, else leads won\'t show up until subscription is confirmed. Here is a short tutorial on how to integrate <a href=\'https://docs.groupleads.net/article/52-how-to-integrate-group-leads-with-sendlane\' target=\'_blank\'>Group Leads with Sendlane <span class=\'fa fa-arrow-right\'></span></a>', 0, '2020-01-30 06:03:10', NULL),
(17, 'Mailing Boss', 'mailingboss', 'mailchimp.png', NULL, '{\"label\":\"*API Key \",\"en\":{\"label\":\"*API Key\"},\"ch\":{\"label\":\"c*API Key\"},\"required\":true,\"type\":\"text\",\"show\":true}', '{\"label\":\"*List ID\",\"en\":{\"label\":\"*List ID\"},\"ch\":{\"label\":\"c*List ID\"},\"required\":true,\"type\":\"text\",\"show\":true}', 'Here is a short tutorail on how to integrate <a href=\'https://docs.groupleads.net/article/54-how-to-integrate-group-leads-with-mailingboss\' target=\'_blank\'>Group Leads with Mailingboss<span class=\'fa fa-arrow-right\'></span></a>', 0, '2020-02-03 06:03:10', NULL),
(18, 'Kartra', 'kartra', 'kartra.png', '{\"label\":\"*API Key\",\"en\":{\"label\":\"*API Key\"},\"ch\":{\"label\":\"c*API Key\"},\"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"1\"}', '{\"label\":\"*API Password\",\"en\":{\"label\":\"*API Password\"},\"ch\":{\"label\":\"c*API Password\"},\"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"2\"}', '{\"label\":\"*Exact List Name \",\"en\":{\"label\":\"*Exact List Name\"},\"ch\":{\"label\":\"c*Exact List Name\"},\"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"3\"}', 'Here is a short tutorial on how to integrate <a href=\'https://docs.groupleads.net/article/70-how-to-integrate-group-leads-with-kartra\' target=\'_blank\'>Group Leads with Kartra <span class=\'fa fa-arrow-right\'></span></a>', 1, '2020-02-03 06:03:10', NULL),
(19, 'Send Grid', 'sendgrid', 'sendgrid.png', '{\"label\":\"*API key\",\"en\":{\"label\":\"*API key\"},\"ch\":{\"label\":\"c*API key\"},\"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"1\"}', '{\"label\":\"*List ID\",\"en\":{\"label\":\"*List ID\"},\"ch\":{\"label\":\"c*List ID\"},\"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"2\"}', NULL, 'Here is a short tutorial on how to integrate <a href=\'https://docs.groupleads.net/article/83-how-to-integrate-group-leads-with-sendgrid\' target=\'_blank\'>Group Leads with Sendgrid <span class=\'fa fa-arrow-right\'></span></a>', 1, '2020-02-03 06:03:10', NULL),
(20, 'Benchmark', 'benchmark', 'mailchimp.png', NULL, '{\"label\":\"*API key\",\"en\":{\"label\":\"*API key\"},\"ch\":{\"label\":\"c*API key\"},\"required\":true,\"type\":\"text\",\"show\":true}', '{\"label\":\"*List ID\",\"en\":{\"label\":\"*List ID\"},\"ch\":{\"label\":\"c*List ID\"},\"required\":true,\"type\":\"text\",\"show\":true}', 'Here is a short tutorial on how to integrate <a href=\'https://docs.groupleads.net/article/96-how-to-integrate-group-leads-with-benchmark\' target=\'_blank\'>Group Leads with Benchmark <span class=\'fa fa-arrow-right\'></span></a>', 0, '2020-02-03 06:03:10', NULL),
(21, 'Sendfox', 'sendfox', 'sendfox.png', '{\"label\":\"*API Token\",\"en\":{\"label\":\"*API Token\"},\"ch\":{\"label\":\"c*API Token\"},\"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"1\"}', '{\"label\":\"*List ID\",\"en\":{\"label\":\"*List ID\"},\"ch\":{\"label\":\"c*List ID\"},\"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"2\"}', NULL, 'Here is a short tutorial on how to integrate <a href=\'https://docs.groupleads.net/article/91-how-to-integrate-group-leads-with-sendfox\' target=\'_blank\'>Group Leads with Sendfox <span class=\'fa fa-arrow-right\'></span></a>', 1, '2020-02-03 06:03:10', NULL),
(22, 'Mautic', 'mautic', 'mailchimp.png', '{\"label\":\"*Email\",\"en\":{\"label\":\"*Email\"},\"ch\":{\"label\":\"c*Email\"},\"required\":true,\"type\":\"email\",\"show\":true}', '{\"label\":\"*Password\",\"en\":{\"label\":\"*Password\"},\"ch\":{\"label\":\"c*Password\"},\"required\":true,\"type\":\"text\",\"show\":true}', '{\"label\":\"*Url\",\"en\":{\"label\":\"*Url\"},\"ch\":{\"label\":\"c*Url\"},\"required\":true,\"type\":\"url\",\"show\":true}', '', 0, '2020-03-25 05:03:10', NULL),
(23, 'Hubspot', 'hubspot', 'hubspot.png', '{\"label\":\"*API key \",\"en\":{\"label\":\"*API key\"},\"ch\":{\"label\":\"c*API key\"},\"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"1\"}', '{\"label\":\"*List ID\",\"en\":{\"label\":\"*List ID\"},\"ch\":{\"label\":\"c*List ID\"},\"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"2\"}', NULL, 'Here is a short tutorial on how to integrate <a href=\'https://docs.groupleads.net/article/102-how-to-integrate-group-leads-with-hubspot\' target=\'_blank\'>Group Leads with Hubspot <span class=\'fa fa-arrow-right\'></span></a>', 1, '2019-08-16 00:00:00', NULL),
(24, 'Simplero', 'simplero', 'mailchimp.png', '{\"label\":\"* Api Key\",\"required\":true,\"type\":\"text\",\"show\":true}', '{\"label\":\"* User Agent\",\"required\":true,\"type\":\"text\",\"show\":true}', '{\"label\":\"* List ID\",\"required\":true,\"type\":\"text\",\"show\":true}', 'Here is a short tutorial on how to integrate <a href=\'https://docs.groupleads.net/article/103-how-to-integrate-group-leads-with-simplero\' target=\'_blank\'>Group Leads with Simplero <span class=\'fa fa-arrow-right\'></span></a>', 0, '2019-08-16 00:00:00', NULL),
(25, 'Ontraport', 'ontraport', 'mailchimp.png', '{\"label\":\"* Api Key\",\"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"1\"}', '{\"label\":\"* APP id\",\"required\":true,\"type\":\"text\",\"show\":true,\"order\":\"2\"}', '{\"label\":\"* Campaign ID\",\"required\":true,\"type\":\"text\",\"show\":true,,\"order\":\"3\"}', 'Here is a short tutorial on how to integrate <a href=\'https://docs.groupleads.net/article/139-how-to-integrate-group-leads-with-ontraport\' target=\'_blank\'>Group Leads with Ontraport <span class=\'fa fa-arrow-right\'></span></a>', 0, '2020-05-14 00:00:00', NULL),
(26, 'Influencersoft', 'influencersoft', 'mailchimp.png', '{\"label\":\"* Username\",\"required\":true,\"type\":\"text\",\"show\":true}', '{\"label\":\"* Api Key\",\"required\":true,\"type\":\"text\",\"show\":true}', '{\"label\":\"* List ID\",\"required\":true,\"type\":\"text\",\"show\":true}', 'Here is a short tutorial on how to integrate <a href=\'https://docs.groupleads.net/article/97-how-to-integrate-group-leads-with-influencersoft\' target=\'_blank\'>Group Leads with Influencersoft <span class=\'fa fa-arrow-right\'></span></a>', 0, '2020-05-14 00:00:00', NULL),
(27, 'Automizy', 'automizy', 'mailchimp.png', '{\"label\":\"*API key \",\"en\":{\"label\":\"*API key\"},\"ch\":{\"label\":\"c*API key\"},\"required\":true,\"type\":\"text\",\"show\":true}', '{\"label\":\"*List ID\",\"en\":{\"label\":\"*List ID\"},\"ch\":{\"label\":\"c*List ID\"},\"required\":true,\"type\":\"text\",\"show\":true}', '{\"label\":\"* Exact Tag Name\",\"en\":{\"label\":\"* Exact Tag Name\"},\"ch\":{\"label\":\"c* Exact Tag Name\"},\"required\":false,\"type\":\"text\",\"show\":true}', 'Here is a short tutorial on how to integrate <a href=\'https://docs.groupleads.net/article/138-how-to-integrate-group-leads-with-automizy\' target=\'_blank\'>Group Leads with Automizy <span class=\'fa fa-arrow-right\'></span></a>', 0, '2020-07-04 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `slug` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category`, `slug`) VALUES
(1, 'Onboarding', 'onboarding'),
(2, 'Lead Generation', 'lead-generation'),
(3, 'Integrations', 'integrations'),
(4, 'Message New Group Member Before Approval', 'message_new_group_me'),
(5, 'Apply Tag To New Members (via CHATSILO)', 'apply_tag_to_new_mem'),
(6, 'Troubleshooting', 'troubleshooting');

-- --------------------------------------------------------

--
-- Table structure for table `cronjob_logs`
--

CREATE TABLE `cronjob_logs` (
  `id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `history_logs`
--

CREATE TABLE `history_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reseller_id` int(11) NOT NULL,
  `last_status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `passwordreset`
--

CREATE TABLE `passwordreset` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(32) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `passwordreset`
--

INSERT INTO `passwordreset` (`id`, `email`, `token`, `created_at`, `updated_at`) VALUES
(1, 'shiv@yopmail.com', '92L3FUQX1QMLX2UQGITB', '2020-10-23 05:06:12', NULL),
(2, 'shiv@yopmail.com', 'ERDPMAKWFC8P28VUXHN3', '2020-10-23 05:06:41', NULL),
(3, 'shiv@yopmail.com', 'JFEPUZC8USHZEJWLA8GV', '2020-10-27 04:42:23', NULL),
(4, 'shiv@yopmail.com', 'OISVSDYDOLODB0RIRQKQ', '2020-10-27 04:43:09', NULL),
(5, 'shiv@yopmail.com', 'SOVPTU2DFM0P9NEGKPIS', '2020-10-27 04:44:32', NULL),
(6, 'shiv@yopmail.com', 'AWGACSU9TINI0LNJIX3Y', '2020-10-27 04:44:44', NULL),
(7, 'shiv@yopmail.com', 'GXMDWJY9ISE3KZ3FLECN', '2020-10-27 04:52:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment_gateway_settings`
--

CREATE TABLE `payment_gateway_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reseller_id` int(11) NOT NULL COMMENT '0 = admin',
  `credentials` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_gateway_settings`
--

INSERT INTO `payment_gateway_settings` (`id`, `payment_type`, `reseller_id`, `credentials`, `status`, `created_at`, `updated_at`) VALUES
(1, 'r', 0, '{\"vendor_id\":\"106303\",\"api_key\":\"2d9657df417f3e724ed0946ef3a578209d410ba9d6c36aeb39\"}', 1, '2021-01-12 15:54:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `level` int(11) NOT NULL DEFAULT 2 COMMENT '1 = admin\r\n2 = reseller',
  `payment_gateway_id` int(11) NOT NULL,
  `is_reseller_package` int(11) NOT NULL DEFAULT 0 COMMENT '0 = subscriber plan 1 = reseller package',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `live_plan_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'stripe or paddle checkout plan id',
  `type` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'monthly' COMMENT 'monthly|yearly|life_time',
  `trial` int(11) NOT NULL DEFAULT 0,
  `leads_limit` int(11) NOT NULL DEFAULT 0,
  `button_text` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`id`, `level`, `payment_gateway_id`, `is_reseller_package`, `name`, `price`, `live_plan_id`, `type`, `trial`, `leads_limit`, `button_text`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 0, 'Free', 0, '0', 'monthly', 0, 250, '', 1, '2021-01-08 15:44:20', NULL),
(2, 1, 1, 0, 'Bronze', 7, '640171', 'monthly', 1, 1000, '', 1, '2021-01-08 15:44:20', NULL),
(3, 1, 1, 0, 'Silver', 17, '640172', 'monthly', 1, 3000, '', 1, '2021-01-08 15:44:20', NULL),
(4, 1, 1, 0, 'Gold', 27, '640173', 'monthly', 1, 5000, '', 1, '2021-01-08 15:44:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `doc_url` varchar(255) DEFAULT NULL,
  `cat_id` int(5) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `doc_url`, `cat_id`, `created_date`) VALUES
(1, 'How to automatically send messages to new facebook members.', 'https://page2leads.net/', 1, '2021-02-05 12:49:08'),
(2, 'How to update page2leads chrome extension', 'https://page2leads.net/', 5, '2021-02-05 12:49:36'),
(3, 'How to use page2leads automatic approval feature', 'https://page2leads.net/', 2, '2021-02-05 12:49:49'),
(4, 'Using Group Leads to generate leads from your Facebook group', 'https://page2leads.net/', 4, '2021-02-05 14:45:20'),
(5, 'How To Automatically Tag New Members In Facebook Group Welcome Post', 'https://page2leads.net/', 3, '2021-02-05 14:46:30'),
(6, 'How Do I Disable Autoresponder For a Specific Facebook Group Within Group Leads', 'https://page2leads.net/', 3, '2021-02-05 14:46:56');

-- --------------------------------------------------------

--
-- Table structure for table `resellers`
--

CREATE TABLE `resellers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subscription_id` varchar(255) DEFAULT NULL,
  `package_limit` int(11) DEFAULT NULL,
  `reseller_hash` varchar(255) DEFAULT NULL,
  `is_email_send` int(11) NOT NULL DEFAULT 0,
  `first_payment_failed` datetime DEFAULT NULL,
  `is_failed_email_sent` int(11) NOT NULL DEFAULT 0 COMMENT 'paddle payment failed email',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 = pending\r\n1 = active\r\n2 = rejected',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `plan_id` int(11) DEFAULT NULL,
  `payment_subscription_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'it relates to paddle or stripe Subscription Id',
  `is_trial` int(11) NOT NULL DEFAULT 0,
  `started_on` date DEFAULT NULL,
  `expired_on` date DEFAULT NULL,
  `cancellation_effective_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `payment_type`, `plan_id`, `payment_subscription_id`, `is_trial`, `started_on`, `expired_on`, `cancellation_effective_date`, `created_at`, `updated_at`) VALUES
(1, '', 1, '', 0, '2020-10-17', NULL, NULL, NULL, NULL),
(3, '', 4, 'NEW123', 1, '2021-01-08', '2021-02-03', NULL, '2021-01-08 09:53:23', '2021-01-20 09:59:48'),
(4, '', 1, '', 0, '2021-01-16', NULL, NULL, '2021-01-16 15:48:58', '2021-01-16 15:48:58'),
(9, 'paddle', 4, '22222', 0, '2021-01-18', '2021-02-18', '2021-02-11', '2021-01-18 05:00:00', '2021-01-18 17:49:28'),
(10, '', 1, '', 0, '2021-01-19', NULL, NULL, '2021-01-19 10:23:28', '2021-01-19 10:23:28'),
(11, 'paddle', 3, '12321', 0, '2021-01-19', '2021-02-06', '2021-01-19', '2021-01-19 05:00:00', '2021-01-19 12:21:34'),
(12, 'paddle', 3, '12345', 0, '2021-01-19', '2021-01-23', NULL, '2021-01-19 05:00:00', '2021-01-19 12:32:31'),
(13, '', 1, '', 0, '2021-01-19', NULL, NULL, '2021-01-19 16:48:55', '2021-01-19 16:48:55'),
(14, '', 1, '', 0, '2021-01-19', NULL, NULL, '2021-01-19 16:50:11', '2021-01-19 16:50:11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subscription_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reseller_id` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL,
  `is_manual` int(11) NOT NULL DEFAULT 0,
  `first_login` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `license`, `subscription_id`, `reseller_id`, `status`, `is_manual`, `first_login`, `created_at`, `updated_at`) VALUES
(1, 'Ankit', 'ankit@gmail.com', '$2y$10$JdbPgcSZ18YHlRDyDLsfDOXRz7l/gR3DOH3N7taSPOmzBnDGi8.qO', NULL, '1234567890', '1', 0, 1, 0, 1, NULL, '2021-01-18 10:46:59'),
(3, 'shivam', 'shivam@gmail.com', '$2y$10$CXwexuplHDxmxSFdo.RXL.F/qPy93n4YBEBue1/SccKIrlkiviLXe', NULL, '', '3', 0, 1, 0, 1, '2021-01-08 09:53:23', '2021-01-20 09:59:48'),
(4, 'nitinsaluja98', 'nitinsaluja98@gmail.com', '$2y$10$bvwfaLqBUjuYb1sm3PFyE.Om.jkSuBbnMDnSz2JEcpCag.RY48K4K', NULL, '', '4', 0, 1, 0, 1, '2021-01-16 15:48:58', '2021-01-18 11:48:49'),
(9, NULL, 'shivambhandari00@gmail.com', '$2y$10$RLLtz1b1.cChkPkwfrIJA.Mp7ibTwoDVyca2oTx3MNQbXvqMBt4Uy', NULL, 'UBIXGFRLRQWV6P4L', '9', 0, 1, 0, 0, '2021-01-18 17:36:24', '2021-01-18 17:49:28'),
(10, 'ankit', 'ankit@yopmail.com', '$2y$10$cHLKv/FF/fA9E57sdAh9o.6QmTn6mUlaP8DLMhaymoFA8lNrOvpfa', NULL, '', '10', 0, 1, 0, 1, '2021-01-19 10:23:28', '2021-01-19 10:24:14'),
(11, NULL, 'richa@yopmail.com', '$2y$10$.h.5gVrtyIc.gl5ocnAt..xUAHUxJDfAan21X/7jI7sLxgdMF7tkG', NULL, '', '11', 0, 0, 0, 0, '2021-01-19 11:54:19', '2021-01-19 12:21:34'),
(12, 'Monika', 'monika@yopmail.com', '$2y$10$DIxooYt27YEoNCiuHRuVaujq1fx9V3wJVGX9xFjKA/E1Wp0Q4cuOm', NULL, '', '12', 0, 1, 0, 0, '2021-01-19 12:27:01', '2021-01-19 12:32:31'),
(13, 'shiv', 'shiv@yopmail.com', '$2y$10$fd9RUYxSNYQYRHUiAwggMOixTFhNz9xRFzoDW6tUf/gLS0AApFyIK', NULL, '', '13', 0, 1, 0, 0, '2021-01-19 16:48:55', '2021-01-19 16:48:55'),
(14, 'shiv', 'shiv@yopmail.in', '$2y$10$I7yClEH/Qx7z9BgsWOa03OjTCt48GKTn7KTvwERXP3V9fyn3WDpJ.', NULL, '', '14', 0, 1, 0, 1, '2021-01-19 16:50:11', '2021-01-19 16:50:28');

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

CREATE TABLE `user_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `unique_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `global_autoresponder_status` tinyint(4) NOT NULL DEFAULT 0,
  `enable_googlesheet` tinyint(4) NOT NULL DEFAULT 0,
  `is_email_send` tinyint(4) NOT NULL DEFAULT 0,
  `ext_version` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_settings`
--

INSERT INTO `user_settings` (`id`, `user_id`, `unique_hash`, `global_autoresponder_status`, `enable_googlesheet`, `is_email_send`, `ext_version`, `created_at`, `updated_at`) VALUES
(1, 1, 'J7V5Q1WRPQYSVEDF3', 0, 0, 0, '', NULL, NULL),
(2, 2, 'J7V5Q1WRPQYSVEHN', 0, 0, 0, '', NULL, NULL),
(3, 9, 'MRIBH0TDVG8BHH00ALD', 0, 0, 0, '', '2021-01-18 17:36:24', '2021-01-18 17:36:24'),
(4, 10, 'K0V436CSTZR6EMC2EO8', 0, 0, 0, '', '2021-01-19 10:23:28', '2021-01-19 10:23:28'),
(5, 3, 'Y2AASSHRW1LNJQLHLRY', 0, 0, 0, '', '2021-01-19 11:54:19', '2021-01-19 11:54:19'),
(6, 4, 'ZCMXODAOLV0LEBQWRTQ', 0, 0, 0, '', '2021-01-19 12:27:01', '2021-01-19 12:27:01'),
(7, 13, 'YGF8FIFIHZIRUWX4TP5', 0, 0, 0, '', '2021-01-19 16:48:55', '2021-01-19 16:48:55'),
(8, 14, 'T3DBPHYLHYVPEDORHYX', 0, 0, 0, '', '2021-01-19 16:50:11', '2021-01-19 16:50:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `all_leads`
--
ALTER TABLE `all_leads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `autoresponders`
--
ALTER TABLE `autoresponders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `autoresponder_list`
--
ALTER TABLE `autoresponder_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cronjob_logs`
--
ALTER TABLE `cronjob_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history_logs`
--
ALTER TABLE `history_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `passwordreset`
--
ALTER TABLE `passwordreset`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_gateway_settings`
--
ALTER TABLE `payment_gateway_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resellers`
--
ALTER TABLE `resellers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `all_leads`
--
ALTER TABLE `all_leads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `autoresponders`
--
ALTER TABLE `autoresponders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `autoresponder_list`
--
ALTER TABLE `autoresponder_list`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cronjob_logs`
--
ALTER TABLE `cronjob_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `history_logs`
--
ALTER TABLE `history_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `passwordreset`
--
ALTER TABLE `passwordreset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payment_gateway_settings`
--
ALTER TABLE `payment_gateway_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `resellers`
--
ALTER TABLE `resellers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user_settings`
--
ALTER TABLE `user_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
