-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 25, 2026 at 07:47 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pregpal`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_text` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `post_id`, `user_id`, `comment_text`, `created_at`, `image`, `link`) VALUES
(3, 2, 19, 'delete this', '2025-11-13 11:59:52', NULL, NULL),
(4, 2, 22, 'ndwbjbw', '2025-11-13 12:59:40', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `conditions`
--

CREATE TABLE `conditions` (
  `condition_id` int(11) NOT NULL,
  `condition_name` varchar(255) NOT NULL,
  `severity` varchar(50) DEFAULT NULL,
  `advice` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `conditions`
--

INSERT INTO `conditions` (`condition_id`, `condition_name`, `severity`, `advice`) VALUES
(1, 'Normal pregnancy', 'Normal', 'Routine pregnancy; attend checkups and maintain healthy lifestyle.'),
(2, 'Preeclampsia', 'Urgent', 'High blood pressure and organ stress; seek immediate medical care.'),
(3, 'Gestational diabetes', 'Monitor', 'High blood sugar during pregnancy; monitor diet and glucose.'),
(4, 'Hyperemesis gravidarum', 'Risky', 'Severe vomiting causing dehydration; see doctor urgently.'),
(5, 'Iron-deficiency anemia', 'Monitor', 'Low iron; follow diet or supplements.'),
(6, 'Urinary tract infection (UTI)', 'Monitor', 'Infection in urinary tract; see doctor for antibiotics.'),
(7, 'Preterm labor', 'Urgent', 'Labor before term; contact hospital immediately.'),
(8, 'Placenta previa', 'Urgent', 'Placenta covers cervix; painless bleeding; urgent evaluation.'),
(9, 'Threatened miscarriage', 'Risky', 'Bleeding or cramping early in pregnancy; seek medical advice.'),
(10, 'Gestational hypertension', 'Monitor', 'High blood pressure; monitor and follow doctor instructions.'),
(11, 'Deep vein thrombosis (DVT)', 'Urgent', 'Blood clot in legs; swelling/pain; seek urgent care.'),
(12, 'Pulmonary embolism (PE)', 'Urgent', 'Blood clot in lungs; sudden shortness of breath; emergency care.'),
(13, 'Cholestasis of pregnancy', 'Monitor', 'Liver disorder causing itching; follow doctor advice.'),
(14, 'Placental abruption', 'Urgent', 'Placenta separates early; severe pain/bleeding; emergency.'),
(15, 'Ectopic pregnancy', 'Urgent', 'Fertilized egg outside uterus; severe pain/bleeding; emergency.'),
(16, 'Gestational trophoblastic disease', 'Risky', 'Rare abnormal pregnancy tissue growth; requires medical treatment.'),
(17, 'Intrahepatic cholestasis of pregnancy', 'Monitor', 'Liver condition causing itching; monitor with doctor.'),
(18, 'Pregnancy-induced hypertension', 'Monitor', 'High blood pressure; monitor symptoms and follow doctor advice.'),
(19, 'Anemia (non-iron)', 'Monitor', 'Low blood count; follow doctor recommendations.'),
(20, 'Urinary incontinence', 'Monitor', 'Involuntary urine leakage; pelvic exercises recommended.'),
(21, 'Varicose veins', 'Normal', 'Swollen veins; elevate legs and stay active.'),
(22, 'Gestational hypothyroidism', 'Monitor', 'Low thyroid hormone; take prescribed medication.'),
(23, 'Gestational hyperthyroidism', 'Monitor', 'High thyroid hormone; monitor with doctor.'),
(24, 'Urinary retention', 'Monitor', 'Difficulty urinating; consult doctor.'),
(25, 'Round ligament pain', 'Normal', 'Stretching ligament pain; normal in pregnancy.'),
(26, 'Symphysis pubis dysfunction (SPD)', 'Monitor', 'Pelvic joint pain; use support and gentle exercise.'),
(27, 'Polyhydramnios', 'Monitor', 'Excess amniotic fluid; monitor with doctor.'),
(28, 'Oligohydramnios', 'Monitor', 'Low amniotic fluid; monitor fetal growth.'),
(29, 'Miscarriage', 'Urgent', 'Loss of pregnancy; seek medical care.'),
(30, 'Eclampsia', 'Urgent', 'Seizures in pregnancy due to high blood pressure; emergency.'),
(31, 'HELLP syndrome', 'Urgent', 'Severe liver and blood disorder; emergency care.'),
(32, 'Hypercoagulable state', 'Monitor', 'Increased risk of blood clots; follow doctor advice.'),
(33, 'Obstetric cholestasis', 'Monitor', 'Liver condition causing itching; monitor and follow doctor.'),
(34, 'Maternal infection', 'Monitor', 'Any infection during pregnancy; follow treatment plan.'),
(35, 'Peripartum cardiomyopathy', 'Urgent', 'Heart weakness near delivery; emergency care.'),
(36, 'Incompetent cervix', 'Risky', 'Cervix opens too early; medical intervention required.'),
(37, 'Polyhydramnios with gestational diabetes', 'Monitor', 'Excess fluid due to high blood sugar; monitor.'),
(38, 'Preterm premature rupture of membranes (PPROM)', 'Urgent', 'Water breaks early; hospital care required.'),
(39, 'Placental insufficiency', 'Monitor', 'Poor blood flow to placenta; monitor fetal growth.');

-- --------------------------------------------------------

--
-- Table structure for table `email_reminders`
--

CREATE TABLE `email_reminders` (
  `reminder_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pregnancy_week` int(11) NOT NULL,
  `email_subject` varchar(255) NOT NULL,
  `email_body` text NOT NULL,
  `scheduled_date` date NOT NULL,
  `status` enum('pending','sent') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_reminders`
--

INSERT INTO `email_reminders` (`reminder_id`, `user_id`, `pregnancy_week`, `email_subject`, `email_body`, `scheduled_date`, `status`, `created_at`) VALUES
(1, 19, 17, 'PregPal Weekly Reminder: Week 17', 'Hi Neha,<br>You are in <strong>Week 17</strong> of your pregnancy (5 days).<br>Don\'t forget to log your symptoms!<br>', '2025-11-02', 'sent', '2025-11-02 20:10:01'),
(3, 19, 5, 'PregPal Weekly Reminder: Week 5', 'Hi Neha,<br>You are in <strong>Week 5</strong> of your pregnancy ( days).<br>Don\'t forget to log your symptoms!<br>', '2025-11-02', 'sent', '2025-11-02 20:18:23'),
(4, 19, 19, 'PregPal Weekly Reminder: Week 19', 'Hi Neha,<br>You are in <strong>Week 19</strong> of your pregnancy (1 days).<br>Don\'t forget to log your symptoms!<br>', '2025-11-12', 'sent', '2025-11-12 12:37:02'),
(5, 19, 20, 'PregPal Weekly Reminder: Week 20', 'Hi Neha,<br>You are in <strong>Week 20</strong> of your pregnancy (0 days).<br>Don\'t forget to log your symptoms!<br>', '2025-11-18', 'sent', '2025-11-18 14:09:21'),
(6, 19, 26, 'PregPal Weekly Reminder: Week 26', 'Hi Neha,<br>You are in <strong>Week 26</strong> of your pregnancy (0 days).<br>Don\'t forget to log your symptoms!<br>', '2025-12-30', 'sent', '2025-12-30 17:07:55'),
(7, 19, 29, 'PregPal Weekly Reminder: Week 29', 'Hi Neha,<br>You are in <strong>Week 29</strong> of your pregnancy (5 days).<br>Don\'t forget to log your symptoms!<br>', '2026-01-25', 'sent', '2026-01-25 04:24:17');

-- --------------------------------------------------------

--
-- Table structure for table `insights`
--

CREATE TABLE `insights` (
  `insight_id` int(11) NOT NULL,
  `trimester` int(11) NOT NULL,
  `type` enum('article','video') NOT NULL,
  `title` varchar(255) NOT NULL,
  `summary` text DEFAULT NULL,
  `url` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `insights`
--

INSERT INTO `insights` (`insight_id`, `trimester`, `type`, `title`, `summary`, `url`, `created_at`) VALUES
(1, 2, 'article', 'Nutrition Tips for First Trimester', 'Learn how to maintain proper nutrition in your first trimester.', 'lllll', '2025-11-03 03:00:34'),
(3, 2, 'article', 'What to expect', 'Safe exercises to stay active in your second trimester.', 'https://my.clevelandclinic.org/health/articles/16092-pregnancy-second-trimester', '2025-11-03 03:00:34'),
(4, 2, 'video', 'Second Trimester Growth', 'Watch how your baby develops during the second trimester.', 'https://youtu.be/k_V8axPqI34', '2025-11-03 03:00:34'),
(5, 3, 'article', 'Preparing for Delivery', 'Essential tips for your last trimester to prepare for childbirth.', 'https://www.whattoexpect.com/pregnancy/third-trimester-preparation', '2025-11-03 03:00:34'),
(6, 3, 'video', 'Third Trimester Tips', 'Important third trimester health tips and precautions.', 'https://www.youtube.com/embed/ijkl9012', '2025-11-03 03:00:34');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `post_id`, `user_id`, `created_at`) VALUES
(3, 1, 19, '2025-11-13 11:20:27'),
(5, 2, 22, '2025-11-13 12:59:28'),
(6, 2, 19, '2025-11-13 14:50:04');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `title`, `content`, `image`, `link`, `tags`, `created_at`) VALUES
(1, 19, 'Morning Sickness', 'I have been experiencing alot of morning sickness throughout my first trimester ', 'belly.png', 'https://pmc.ncbi.nlm.nih.gov/articles/PMC3676933/', 'first trimester, morning sickness', '2025-11-13 09:39:46'),
(2, 19, 'second trimester', 'yuilb jufigbbhcgh', '', '', 'second trimester', '2025-11-13 10:12:39'),
(4, 19, 'Severe headache', 'goykhbbcwlipechpecbwbecp', '', '', 'second trimester', '2025-11-13 14:50:54');

-- --------------------------------------------------------

--
-- Table structure for table `symptoms`
--

CREATE TABLE `symptoms` (
  `symptom_id` int(11) NOT NULL,
  `symptom_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `symptoms`
--

INSERT INTO `symptoms` (`symptom_id`, `symptom_name`, `description`) VALUES
(1, 'Nausea', 'Feeling sick in the stomach'),
(2, 'Vomiting', 'Throwing up'),
(3, 'Fatigue', 'Feeling very tired'),
(4, 'Headache', 'Pain in the head'),
(5, 'Dizziness', 'Feeling lightheaded'),
(6, 'Back pain', 'Pain in the lower back'),
(7, 'Swollen feet', 'Feet feel puffy or swollen'),
(8, 'Shortness of breath', 'Hard to breathe normally'),
(9, 'Constipation', 'Trouble passing stool'),
(10, 'Heartburn', 'Burning feeling in the chest'),
(11, 'Mood swings', 'Feeling happy one moment, sad the next'),
(12, 'Insomnia', 'Trouble sleeping'),
(13, 'Loss of appetite', 'Not wanting to eat'),
(14, 'Rapid heartbeat', 'Heart beats fast'),
(15, 'Abdominal cramps', 'Pain or tightening in the stomach'),
(16, 'Frequent urination', 'Needing to pee often'),
(17, 'Vaginal bleeding', 'Spotting or bleeding from vagina'),
(18, 'Leg cramps', 'Sudden pain in the legs'),
(19, 'Swollen hands', 'Hands feel puffy'),
(20, 'Blurred vision', 'Trouble seeing clearly'),
(21, 'Fever', 'High body temperature'),
(22, 'Chills', 'Feeling very cold'),
(23, 'Runny nose', 'Nose keeps dripping'),
(24, 'Sore throat', 'Pain or irritation in throat'),
(25, 'Cough', 'Need to cough often'),
(26, 'Body aches', 'Pain all over the body'),
(27, 'Weakness', 'Feeling physically weak'),
(28, 'Sneezing', 'Sudden air from nose'),
(29, 'Stomach bloating', 'Stomach feels full or tight'),
(30, 'Heart palpitations', 'Feeling heart beating irregularly'),
(31, 'Numbness', 'Loss of feeling in part of the body'),
(32, 'Tingling', 'Pins-and-needles feeling'),
(33, 'Swollen belly', 'Belly feels puffy'),
(34, 'Hot flashes', 'Sudden warmth all over body'),
(35, 'Night sweats', 'Sweating while sleeping'),
(36, 'Itchy skin', 'Skin feels itchy'),
(37, 'Rash', 'Red or bumpy skin'),
(38, 'Hair loss', 'Losing hair more than usual'),
(39, 'Dry skin', 'Skin feels rough or flaky'),
(40, 'Toothache', 'Pain in teeth'),
(41, 'Gum bleeding', 'Bleeding from gums'),
(42, 'Nosebleeds', 'Blood coming from nose'),
(43, 'Swollen eyes', 'Eyes look puffy'),
(44, 'Watery eyes', 'Eyes produce too many tears'),
(45, 'Earache', 'Pain in the ear'),
(46, 'Ringing in ears', 'Buzzing sound in ear'),
(47, 'Nausea when standing', 'Feeling sick after standing up'),
(48, 'Cravings', 'Wanting specific foods'),
(49, 'Heart flutter', 'Heart feels like it skips a beat'),
(50, 'Tired eyes', 'Eyes feel heavy or strained'),
(51, 'Neck pain', 'Pain in neck muscles'),
(52, 'Shoulder pain', 'Pain in shoulders'),
(53, 'Hip pain', 'Pain in hips'),
(54, 'Knee pain', 'Pain in knees'),
(55, 'Foot pain', 'Pain in feet'),
(56, 'Swollen fingers', 'Fingers feel puffy'),
(57, 'Back stiffness', 'Hard to bend or move back'),
(58, 'Muscle cramps', 'Sudden pain in muscles'),
(59, 'Bruises', 'Marks from bumps or minor injury'),
(60, 'Gas', 'Feeling bloated from trapped air'),
(61, 'Burping', 'Releasing air from stomach'),
(62, 'Indigestion', 'Stomach discomfort after eating'),
(63, 'Acid reflux', 'Stomach acid comes up into throat'),
(64, 'Frequent headaches', 'Head hurts many times'),
(65, 'Swollen ankles', 'Ankles look puffy'),
(66, 'Varicose veins', 'Swollen veins in legs'),
(67, 'Leg heaviness', 'Legs feel heavy or tired'),
(68, 'Cold hands', 'Hands feel cold easily'),
(69, 'Cold feet', 'Feet feel cold easily'),
(70, 'Trouble concentrating', 'Hard to focus or remember things'),
(71, 'Normal', 'Everything is normal');

-- --------------------------------------------------------

--
-- Table structure for table `symptom_condition`
--

CREATE TABLE `symptom_condition` (
  `condition_id` int(11) NOT NULL,
  `symptom_id` int(11) NOT NULL,
  `weight` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `symptom_condition`
--

INSERT INTO `symptom_condition` (`condition_id`, `symptom_id`, `weight`) VALUES
(1, 3, 0.2),
(1, 6, 0.1),
(1, 11, 0.2),
(1, 16, 0.2),
(1, 18, 0.1),
(1, 25, 0.2),
(1, 26, 0.2),
(1, 57, 0.2),
(2, 1, 0.8),
(2, 2, 0.7),
(2, 4, 0.7),
(2, 5, 0.6),
(2, 14, 0.7),
(2, 17, 0.5),
(2, 20, 0.6),
(2, 21, 0.5),
(3, 3, 0.6),
(3, 13, 0.5),
(3, 14, 0.7),
(3, 16, 0.7),
(3, 27, 0.4),
(4, 1, 1),
(4, 2, 1),
(4, 3, 0.8),
(4, 13, 0.6),
(4, 15, 0.7),
(5, 3, 0.7),
(5, 9, 0.6),
(5, 13, 0.6),
(5, 21, 0.4),
(5, 27, 0.5),
(6, 1, 0.3),
(6, 16, 0.9),
(6, 17, 0.8),
(6, 21, 0.6),
(6, 25, 0.4),
(7, 3, 0.5),
(7, 6, 0.7),
(7, 15, 0.9),
(7, 18, 0.4),
(8, 1, 0.3),
(8, 15, 0.6),
(8, 17, 0.9),
(9, 2, 0.4),
(9, 15, 0.6),
(9, 17, 0.8),
(10, 5, 0.5),
(10, 8, 0.5),
(10, 14, 0.6),
(10, 21, 0.7),
(11, 7, 0.9),
(11, 18, 0.7),
(11, 19, 0.5),
(11, 65, 0.7),
(12, 3, 0.3),
(12, 5, 0.5),
(12, 8, 0.9),
(12, 14, 0.6),
(12, 21, 0.8),
(13, 1, 0.2),
(13, 34, 0.5),
(13, 36, 0.8),
(13, 37, 0.4),
(14, 4, 0.5),
(14, 6, 0.5),
(14, 15, 0.9),
(14, 17, 0.8),
(14, 21, 0.6),
(15, 1, 0.9),
(15, 2, 0.9),
(15, 13, 0.6),
(15, 15, 0.8),
(15, 17, 0.7),
(16, 1, 0.6),
(16, 2, 0.6),
(16, 3, 0.5),
(16, 13, 0.4),
(16, 14, 0.4),
(17, 1, 0.3),
(17, 20, 0.4),
(17, 34, 0.5),
(17, 36, 0.8),
(18, 5, 0.5),
(18, 8, 0.4),
(18, 14, 0.7),
(18, 21, 0.6),
(19, 3, 0.6),
(19, 9, 0.4),
(19, 13, 0.5),
(19, 27, 0.5),
(20, 1, 0.2),
(20, 3, 0.3),
(20, 16, 0.7),
(20, 26, 0.4),
(21, 6, 0.5),
(21, 65, 0.7),
(21, 66, 0.9),
(21, 67, 0.6),
(22, 3, 0.6),
(22, 13, 0.5),
(22, 14, 0.6),
(22, 27, 0.4),
(23, 3, 0.5),
(23, 14, 0.7),
(23, 27, 0.5),
(23, 30, 0.3),
(24, 1, 0.2),
(24, 3, 0.2),
(24, 6, 0.3),
(24, 16, 0.6),
(25, 6, 0.4),
(25, 25, 0.8),
(25, 51, 0.5),
(25, 52, 0.3),
(25, 53, 0.3),
(26, 6, 0.6),
(26, 51, 0.7),
(26, 52, 0.5),
(26, 54, 0.4),
(26, 55, 0.4),
(27, 1, 0.6),
(27, 2, 0.5),
(27, 16, 0.4),
(27, 29, 0.5),
(27, 48, 0.3),
(28, 1, 0.3),
(28, 2, 0.3),
(28, 16, 0.5),
(28, 29, 0.6),
(28, 30, 0.4),
(29, 13, 0.5),
(29, 14, 0.4),
(29, 15, 0.8),
(29, 17, 0.9),
(29, 21, 0.6),
(30, 1, 0.3),
(30, 4, 0.4),
(30, 8, 0.5),
(30, 14, 0.8),
(30, 21, 0.7),
(31, 3, 0.4),
(31, 14, 0.8),
(31, 21, 0.9),
(31, 27, 0.3),
(32, 7, 0.8),
(32, 18, 0.6),
(32, 65, 0.7),
(32, 67, 0.5),
(33, 1, 0.3),
(33, 3, 0.3),
(33, 13, 0.4),
(33, 36, 0.7),
(34, 21, 0.7),
(34, 22, 0.6),
(34, 23, 0.5),
(34, 24, 0.5),
(34, 25, 0.4),
(35, 3, 0.4),
(35, 4, 0.5),
(35, 8, 0.6),
(35, 14, 0.8),
(36, 6, 0.6),
(36, 15, 0.8),
(36, 51, 0.5),
(36, 52, 0.4),
(36, 53, 0.4),
(37, 1, 0.6),
(37, 2, 0.6),
(37, 3, 0.5),
(37, 14, 0.4),
(37, 16, 0.4),
(38, 1, 0.3),
(38, 15, 0.9),
(38, 16, 0.8),
(38, 17, 0.6),
(38, 18, 0.5),
(39, 1, 0.2),
(39, 3, 0.5),
(39, 6, 0.3),
(39, 13, 0.5),
(39, 27, 0.6);

-- --------------------------------------------------------

--
-- Table structure for table `symptom_logs`
--

CREATE TABLE `symptom_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `symptom_name` varchar(255) NOT NULL,
  `severity_score` float NOT NULL DEFAULT 1,
  `weighted_score` float NOT NULL DEFAULT 1,
  `date_logged` date NOT NULL,
  `week_number` int(11) NOT NULL,
  `trimester` tinyint(4) NOT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `symptom_logs`
--

INSERT INTO `symptom_logs` (`id`, `user_id`, `symptom_name`, `severity_score`, `weighted_score`, `date_logged`, `week_number`, `trimester`, `notes`) VALUES
(131, 19, 'fever', 1, 0.5, '2025-07-25', 3, 1, NULL),
(132, 19, 'headache', 1, 0.7, '2025-07-25', 3, 1, NULL),
(133, 19, 'fever', 1, 0.5, '2025-07-25', 3, 1, NULL),
(134, 19, 'headache', 1, 0.7, '2025-07-14', 1, 1, NULL),
(135, 19, 'constipation', 1, 0.6, '2025-07-14', 1, 1, NULL),
(136, 19, 'constipation', 1, 0.6, '2025-07-29', 4, 1, NULL),
(137, 19, 'dizziness', 1, 0.6, '2025-07-29', 4, 1, NULL),
(138, 19, 'vomiting', 1, 0.7, '2025-08-07', 5, 1, NULL),
(139, 19, 'headache', 1, 0.7, '2025-08-07', 5, 1, NULL),
(140, 19, 'insomnia', 1, 0.1, '2025-08-07', 5, 1, NULL),
(141, 19, 'loss of appetite', 1, 0.5, '2025-08-18', 6, 1, NULL),
(142, 19, 'mood swings', 1, 0.2, '2025-08-20', 7, 1, NULL),
(143, 19, 'weakness', 1, 0.4, '2025-08-31', 8, 1, NULL),
(144, 19, 'weakness', 1, 0.4, '2025-08-31', 8, 1, NULL),
(145, 19, 'fever', 1, 0.5, '2025-08-31', 8, 1, NULL),
(146, 19, 'weakness', 1, 0.4, '2025-08-31', 8, 1, NULL),
(147, 19, 'heartburn', 1, 0.1, '2025-09-10', 10, 1, NULL),
(148, 19, 'swollen eyes', 1, 0.1, '2025-09-14', 10, 1, NULL),
(149, 19, 'neck pain', 1, 0.5, '2025-09-14', 10, 1, NULL),
(150, 19, 'headache', 1, 0.7, '2025-09-14', 10, 1, NULL),
(151, 19, 'vomiting', 1, 0.7, '2025-09-24', 12, 1, NULL),
(152, 19, 'constipation', 1, 0.6, '2025-09-24', 12, 1, NULL),
(153, 19, 'vomiting', 1, 0.7, '2025-09-24', 12, 1, NULL),
(154, 19, 'cold hands', 1, 0.1, '2025-09-29', 12, 1, NULL),
(155, 19, 'dizziness', 1, 0.6, '2025-09-29', 12, 1, NULL),
(156, 19, 'swollen eyes', 1, 0.1, '2025-10-08', 14, 2, NULL),
(157, 19, 'swollen eyes', 1, 0.1, '2025-10-08', 14, 2, NULL),
(158, 19, 'tired eyes', 1, 0.1, '2025-10-08', 14, 2, NULL),
(159, 19, 'constipation', 1, 0.6, '2025-10-24', 16, 2, NULL),
(160, 19, 'hip pain', 1, 0.3, '2025-10-19', 15, 2, NULL),
(161, 19, 'hip pain', 1, 0.3, '2025-11-06', 18, 2, NULL),
(162, 19, 'vomiting', 1, 0.7, '2025-11-06', 18, 2, NULL),
(163, 19, 'headache', 1, 0.7, '2025-11-06', 18, 2, NULL),
(164, 19, 'nausea', 1, 0.8, '2025-11-06', 18, 2, NULL),
(165, 19, 'gas', 1, 0.1, '2025-11-11', 19, 2, NULL),
(166, 19, 'normal', 1, 0.1, '2025-11-19', 20, 2, NULL),
(167, 19, 'normal', 1, 0.1, '2025-11-20', 20, 2, NULL),
(168, 19, 'constipation', 1, 0.6, '2025-11-20', 20, 2, NULL),
(169, 19, 'dizziness', 1, 0.6, '2025-11-23', 20, 2, NULL),
(170, 19, 'weakness', 1, 0.4, '2025-11-27', 21, 2, NULL),
(171, 19, 'swollen feet', 1, 0.9, '2025-12-12', 23, 2, NULL),
(172, 19, 'swollen eyes', 1, 0.1, '2025-12-12', 23, 2, NULL),
(173, 19, 'heartburn', 1, 0.1, '2025-12-12', 23, 2, NULL),
(174, 19, 'vomiting', 1, 0.7, '2025-12-16', 24, 2, NULL),
(175, 19, 'rash', 1, 0.4, '2025-12-16', 24, 2, NULL),
(176, 19, 'watery eyes', 1, 0.1, '2025-12-31', 26, 2, NULL),
(177, 19, 'leg heaviness', 1, 0.6, '2025-12-20', 24, 2, NULL),
(178, 19, 'heartburn', 1, 0.1, '2026-01-08', 27, 3, NULL),
(179, 19, 'constipation', 1, 0.6, '2026-01-08', 27, 3, NULL),
(180, 19, 'constipation', 1, 0.6, '2026-01-04', 26, 2, NULL),
(181, 19, 'headache', 1, 0.7, '2026-01-16', 28, 3, NULL),
(182, 19, 'headache', 1, 0.7, '2026-01-20', 29, 3, NULL),
(183, 19, 'headache', 1, 0.7, '2026-01-20', 29, 3, NULL),
(184, 19, 'back pain', 1, 0.1, '2026-01-20', 29, 3, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `last_period` date DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_verified` tinyint(1) DEFAULT 0,
  `verification_token` varchar(100) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `otp` varchar(6) DEFAULT NULL,
  `otp_expires` datetime DEFAULT NULL,
  `last_period_set_on` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `last_period`, `password`, `registered_at`, `is_verified`, `verification_token`, `role`, `otp`, `otp_expires`, `last_period_set_on`) VALUES
(1, 'admin', 'admin@pregpal.com', NULL, 'admin123', '2025-08-06 08:16:47', 1, NULL, 'admin', NULL, NULL, NULL),
(19, 'Neha', 'nehamaharjan016@gmail.com', '2025-07-01', 'neha@123', '2025-08-13 21:18:19', 1, NULL, 'user', NULL, NULL, '2025-08-14'),
(22, 'riya', 'nehamaharjan2004@gmail.com', NULL, 'riya@123', '2025-11-13 06:30:16', 1, NULL, 'user', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_diagnosis`
--

CREATE TABLE `user_diagnosis` (
  `diagnosis_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `diagnosis_html` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_symptoms`
--

CREATE TABLE `user_symptoms` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `symptom_date` date NOT NULL,
  `symptoms_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `diagnosis_html` text DEFAULT NULL,
  `trimester` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_symptoms`
--

INSERT INTO `user_symptoms` (`id`, `user_id`, `symptom_date`, `symptoms_text`, `created_at`, `diagnosis_html`, `trimester`) VALUES
(240, 19, '2025-07-25', 'fever', '2026-01-25 06:35:05', '<h5>Possible conditions:</h5><ul><li><strong>HELLP syndrome</strong><br>Severity: Urgent<br>Advice: Severe liver and blood disorder; emergency care.</li><br><li><strong>Pulmonary embolism (PE)</strong><br>Severity: Urgent<br>Advice: Blood clot in lungs; sudden shortness of breath; emergency care.</li><br><li><strong>Gestational hypertension</strong><br>Severity: Monitor<br>Advice: High blood pressure; monitor and follow doctor instructions.</li><br></ul>', NULL),
(241, 19, '2025-07-25', 'fever,headache', '2026-01-25 06:35:14', '<h5>Possible conditions:</h5><ul><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br><li><strong>Placental abruption</strong><br>Severity: Urgent<br>Advice: Placenta separates early; severe pain/bleeding; emergency.</li><br><li><strong>Eclampsia</strong><br>Severity: Urgent<br>Advice: Seizures in pregnancy due to high blood pressure; emergency.</li><br></ul>', NULL),
(242, 19, '2025-07-14', 'headache', '2026-01-25 06:35:24', '<h5>Possible conditions:</h5><ul><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br><li><strong>Placental abruption</strong><br>Severity: Urgent<br>Advice: Placenta separates early; severe pain/bleeding; emergency.</li><br><li><strong>Peripartum cardiomyopathy</strong><br>Severity: Urgent<br>Advice: Heart weakness near delivery; emergency care.</li><br></ul>', NULL),
(243, 19, '2025-07-14', 'constipation', '2026-01-25 06:35:33', '<h5>Possible conditions:</h5><ul><li><strong>Iron-deficiency anemia</strong><br>Severity: Monitor<br>Advice: Low iron; follow diet or supplements.</li><br><li><strong>Anemia (non-iron)</strong><br>Severity: Monitor<br>Advice: Low blood count; follow doctor recommendations.</li><br><li><strong>Normal pregnancy</strong><br>Severity: Normal<br>Advice: Routine pregnancy; attend checkups and maintain healthy lifestyle.</li><br></ul>', NULL),
(244, 19, '2025-07-29', 'constipation', '2026-01-25 06:35:44', '<h5>Possible conditions:</h5><ul><li><strong>Iron-deficiency anemia</strong><br>Severity: Monitor<br>Advice: Low iron; follow diet or supplements.</li><br><li><strong>Anemia (non-iron)</strong><br>Severity: Monitor<br>Advice: Low blood count; follow doctor recommendations.</li><br><li><strong>Normal pregnancy</strong><br>Severity: Normal<br>Advice: Routine pregnancy; attend checkups and maintain healthy lifestyle.</li><br></ul>', NULL),
(245, 19, '2025-07-29', 'dizziness', '2026-01-25 06:35:52', '<h5>Possible conditions:</h5><ul><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br><li><strong>Gestational hypertension</strong><br>Severity: Monitor<br>Advice: High blood pressure; monitor and follow doctor instructions.</li><br><li><strong>Pulmonary embolism (PE)</strong><br>Severity: Urgent<br>Advice: Blood clot in lungs; sudden shortness of breath; emergency care.</li><br></ul>', NULL),
(246, 19, '2025-08-07', 'vomiting,headache', '2026-01-25 06:36:10', '<h5>Possible conditions:</h5><ul><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br><li><strong>Hyperemesis gravidarum</strong><br>Severity: Risky<br>Advice: Severe vomiting causing dehydration; see doctor urgently.</li><br><li><strong>Ectopic pregnancy</strong><br>Severity: Urgent<br>Advice: Fertilized egg outside uterus; severe pain/bleeding; emergency.</li><br></ul>', NULL),
(247, 19, '2025-08-07', 'insomnia', '2026-01-25 06:36:24', '<h5>Possible conditions:</h5><ul><li><strong>Normal pregnancy</strong><br>Severity: Normal<br>Advice: Routine pregnancy; attend checkups and maintain healthy lifestyle.</li><br><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br><li><strong>Gestational diabetes</strong><br>Severity: Monitor<br>Advice: High blood sugar during pregnancy; monitor diet and glucose.</li><br></ul>', NULL),
(248, 19, '2025-08-18', 'loss of appitite', '2026-01-25 06:36:43', 'No matching conditions found. Make sure to visit your doctor..', NULL),
(249, 19, '2025-08-18', 'loss of appetite', '2026-01-25 06:36:55', '<h5>Possible conditions:</h5><ul><li><strong>Hyperemesis gravidarum</strong><br>Severity: Risky<br>Advice: Severe vomiting causing dehydration; see doctor urgently.</li><br><li><strong>Iron-deficiency anemia</strong><br>Severity: Monitor<br>Advice: Low iron; follow diet or supplements.</li><br><li><strong>Ectopic pregnancy</strong><br>Severity: Urgent<br>Advice: Fertilized egg outside uterus; severe pain/bleeding; emergency.</li><br></ul>', NULL),
(250, 19, '2025-08-20', 'moodswings', '2026-01-25 06:37:10', '<h5>Possible conditions:</h5><ul><li><strong>Normal pregnancy</strong><br>Severity: Normal<br>Advice: Routine pregnancy; attend checkups and maintain healthy lifestyle.</li><br><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br><li><strong>Gestational diabetes</strong><br>Severity: Monitor<br>Advice: High blood sugar during pregnancy; monitor diet and glucose.</li><br></ul>', NULL),
(251, 19, '2025-08-31', 'weakness', '2026-01-25 06:37:33', '<h5>Possible conditions:</h5><ul><li><strong>Placental insufficiency</strong><br>Severity: Monitor<br>Advice: Poor blood flow to placenta; monitor fetal growth.</li><br><li><strong>Iron-deficiency anemia</strong><br>Severity: Monitor<br>Advice: Low iron; follow diet or supplements.</li><br><li><strong>Anemia (non-iron)</strong><br>Severity: Monitor<br>Advice: Low blood count; follow doctor recommendations.</li><br></ul>', NULL),
(252, 19, '2025-08-31', 'weakness,hurtburn', '2026-01-25 06:37:41', '<h5>Possible conditions:</h5><ul><li><strong>Placental insufficiency</strong><br>Severity: Monitor<br>Advice: Poor blood flow to placenta; monitor fetal growth.</li><br><li><strong>Iron-deficiency anemia</strong><br>Severity: Monitor<br>Advice: Low iron; follow diet or supplements.</li><br><li><strong>Anemia (non-iron)</strong><br>Severity: Monitor<br>Advice: Low blood count; follow doctor recommendations.</li><br></ul>', NULL),
(253, 19, '2025-08-31', 'weakness,fever', '2026-01-25 06:37:51', '<h5>Possible conditions:</h5><ul><li><strong>HELLP syndrome</strong><br>Severity: Urgent<br>Advice: Severe liver and blood disorder; emergency care.</li><br><li><strong>Iron-deficiency anemia</strong><br>Severity: Monitor<br>Advice: Low iron; follow diet or supplements.</li><br><li><strong>Pulmonary embolism (PE)</strong><br>Severity: Urgent<br>Advice: Blood clot in lungs; sudden shortness of breath; emergency care.</li><br></ul>', NULL),
(254, 19, '2025-09-10', 'heartburn', '2026-01-25 06:38:07', '<h5>Possible conditions:</h5><ul><li><strong>Normal pregnancy</strong><br>Severity: Normal<br>Advice: Routine pregnancy; attend checkups and maintain healthy lifestyle.</li><br><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br><li><strong>Gestational diabetes</strong><br>Severity: Monitor<br>Advice: High blood sugar during pregnancy; monitor diet and glucose.</li><br></ul>', NULL),
(255, 19, '2025-09-14', 'swollen eyes', '2026-01-25 06:38:20', '<h5>Possible conditions:</h5><ul><li><strong>Normal pregnancy</strong><br>Severity: Normal<br>Advice: Routine pregnancy; attend checkups and maintain healthy lifestyle.</li><br><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br><li><strong>Gestational diabetes</strong><br>Severity: Monitor<br>Advice: High blood sugar during pregnancy; monitor diet and glucose.</li><br></ul>', NULL),
(256, 19, '2025-09-14', 'neckpain', '2026-01-25 06:38:40', '<h5>Possible conditions:</h5><ul><li><strong>Symphysis pubis dysfunction (SPD)</strong><br>Severity: Monitor<br>Advice: Pelvic joint pain; use support and gentle exercise.</li><br><li><strong>Round ligament pain</strong><br>Severity: Normal<br>Advice: Stretching ligament pain; normal in pregnancy.</li><br><li><strong>Incompetent cervix</strong><br>Severity: Risky<br>Advice: Cervix opens too early; medical intervention required.</li><br></ul>', NULL),
(257, 19, '2025-09-14', 'head ache', '2026-01-25 06:38:54', '<h5>Possible conditions:</h5><ul><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br><li><strong>Placental abruption</strong><br>Severity: Urgent<br>Advice: Placenta separates early; severe pain/bleeding; emergency.</li><br><li><strong>Peripartum cardiomyopathy</strong><br>Severity: Urgent<br>Advice: Heart weakness near delivery; emergency care.</li><br></ul>', NULL),
(258, 19, '2025-09-24', 'constipation and vomiting', '2026-01-25 06:39:10', '<h5>Possible conditions:</h5><ul><li><strong>Hyperemesis gravidarum</strong><br>Severity: Risky<br>Advice: Severe vomiting causing dehydration; see doctor urgently.</li><br><li><strong>Ectopic pregnancy</strong><br>Severity: Urgent<br>Advice: Fertilized egg outside uterus; severe pain/bleeding; emergency.</li><br><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br></ul>', NULL),
(259, 19, '2025-09-24', 'vomiting', '2026-01-25 06:39:18', '<h5>Possible conditions:</h5><ul><li><strong>Hyperemesis gravidarum</strong><br>Severity: Risky<br>Advice: Severe vomiting causing dehydration; see doctor urgently.</li><br><li><strong>Ectopic pregnancy</strong><br>Severity: Urgent<br>Advice: Fertilized egg outside uterus; severe pain/bleeding; emergency.</li><br><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br></ul>', NULL),
(260, 19, '2025-09-29', 'cold hands and feet', '2026-01-25 06:39:41', '<h5>Possible conditions:</h5><ul><li><strong>Normal pregnancy</strong><br>Severity: Normal<br>Advice: Routine pregnancy; attend checkups and maintain healthy lifestyle.</li><br><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br><li><strong>Gestational diabetes</strong><br>Severity: Monitor<br>Advice: High blood sugar during pregnancy; monitor diet and glucose.</li><br></ul>', NULL),
(261, 19, '2025-09-29', 'dizziness', '2026-01-25 06:39:53', '<h5>Possible conditions:</h5><ul><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br><li><strong>Gestational hypertension</strong><br>Severity: Monitor<br>Advice: High blood pressure; monitor and follow doctor instructions.</li><br><li><strong>Pulmonary embolism (PE)</strong><br>Severity: Urgent<br>Advice: Blood clot in lungs; sudden shortness of breath; emergency care.</li><br></ul>', NULL),
(262, 19, '2025-10-08', 'swollen eyes', '2026-01-25 06:40:17', '<h5>Possible conditions:</h5><ul><li><strong>Normal pregnancy</strong><br>Severity: Normal<br>Advice: Routine pregnancy; attend checkups and maintain healthy lifestyle.</li><br><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br><li><strong>Gestational diabetes</strong><br>Severity: Monitor<br>Advice: High blood sugar during pregnancy; monitor diet and glucose.</li><br></ul>', NULL),
(263, 19, '2025-10-08', 'swollen eyes, tiredness', '2026-01-25 06:40:26', '<h5>Possible conditions:</h5><ul><li><strong>Normal pregnancy</strong><br>Severity: Normal<br>Advice: Routine pregnancy; attend checkups and maintain healthy lifestyle.</li><br><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br><li><strong>Gestational diabetes</strong><br>Severity: Monitor<br>Advice: High blood sugar during pregnancy; monitor diet and glucose.</li><br></ul>', NULL),
(264, 19, '2025-10-08', 'tired', '2026-01-25 06:40:48', 'No matching conditions found. Make sure to visit your doctor..', NULL),
(265, 19, '2025-10-08', 'tired eyes', '2026-01-25 06:40:54', '<h5>Possible conditions:</h5><ul><li><strong>Normal pregnancy</strong><br>Severity: Normal<br>Advice: Routine pregnancy; attend checkups and maintain healthy lifestyle.</li><br><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br><li><strong>Gestational diabetes</strong><br>Severity: Monitor<br>Advice: High blood sugar during pregnancy; monitor diet and glucose.</li><br></ul>', NULL),
(266, 19, '2025-10-24', 'constipation', '2026-01-25 06:41:09', '<h5>Possible conditions:</h5><ul><li><strong>Iron-deficiency anemia</strong><br>Severity: Monitor<br>Advice: Low iron; follow diet or supplements.</li><br><li><strong>Anemia (non-iron)</strong><br>Severity: Monitor<br>Advice: Low blood count; follow doctor recommendations.</li><br><li><strong>Normal pregnancy</strong><br>Severity: Normal<br>Advice: Routine pregnancy; attend checkups and maintain healthy lifestyle.</li><br></ul>', NULL),
(267, 19, '2025-10-19', 'hip pain', '2026-01-25 06:41:24', '<h5>Possible conditions:</h5><ul><li><strong>Incompetent cervix</strong><br>Severity: Risky<br>Advice: Cervix opens too early; medical intervention required.</li><br><li><strong>Round ligament pain</strong><br>Severity: Normal<br>Advice: Stretching ligament pain; normal in pregnancy.</li><br><li><strong>Normal pregnancy</strong><br>Severity: Normal<br>Advice: Routine pregnancy; attend checkups and maintain healthy lifestyle.</li><br></ul>', NULL),
(268, 19, '2025-11-06', 'hip pain', '2026-01-25 06:41:36', '<h5>Possible conditions:</h5><ul><li><strong>Incompetent cervix</strong><br>Severity: Risky<br>Advice: Cervix opens too early; medical intervention required.</li><br><li><strong>Round ligament pain</strong><br>Severity: Normal<br>Advice: Stretching ligament pain; normal in pregnancy.</li><br><li><strong>Normal pregnancy</strong><br>Severity: Normal<br>Advice: Routine pregnancy; attend checkups and maintain healthy lifestyle.</li><br></ul>', NULL),
(269, 19, '2025-11-06', 'backache, headache, vomiting', '2026-01-25 06:41:49', '<h5>Possible conditions:</h5><ul><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br><li><strong>Hyperemesis gravidarum</strong><br>Severity: Risky<br>Advice: Severe vomiting causing dehydration; see doctor urgently.</li><br><li><strong>Ectopic pregnancy</strong><br>Severity: Urgent<br>Advice: Fertilized egg outside uterus; severe pain/bleeding; emergency.</li><br></ul>', NULL),
(270, 19, '2025-11-06', 'nausea', '2026-01-25 06:41:57', '<h5>Possible conditions:</h5><ul><li><strong>Hyperemesis gravidarum</strong><br>Severity: Risky<br>Advice: Severe vomiting causing dehydration; see doctor urgently.</li><br><li><strong>Ectopic pregnancy</strong><br>Severity: Urgent<br>Advice: Fertilized egg outside uterus; severe pain/bleeding; emergency.</li><br><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br></ul>', NULL),
(271, 19, '2025-11-11', 'gas', '2026-01-25 06:42:16', '<h5>Possible conditions:</h5><ul><li><strong>Normal pregnancy</strong><br>Severity: Normal<br>Advice: Routine pregnancy; attend checkups and maintain healthy lifestyle.</li><br><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br><li><strong>Gestational diabetes</strong><br>Severity: Monitor<br>Advice: High blood sugar during pregnancy; monitor diet and glucose.</li><br></ul>', NULL),
(272, 19, '2025-11-19', 'normal', '2026-01-25 06:42:30', '<h5>Possible conditions:</h5><ul><li><strong>Normal pregnancy</strong><br>Severity: Normal<br>Advice: Routine pregnancy; attend checkups and maintain healthy lifestyle.</li><br><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br><li><strong>Gestational diabetes</strong><br>Severity: Monitor<br>Advice: High blood sugar during pregnancy; monitor diet and glucose.</li><br></ul>', NULL),
(273, 19, '2025-11-20', 'normal', '2026-01-25 06:42:37', '<h5>Possible conditions:</h5><ul><li><strong>Normal pregnancy</strong><br>Severity: Normal<br>Advice: Routine pregnancy; attend checkups and maintain healthy lifestyle.</li><br><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br><li><strong>Gestational diabetes</strong><br>Severity: Monitor<br>Advice: High blood sugar during pregnancy; monitor diet and glucose.</li><br></ul>', NULL),
(274, 19, '2025-11-20', 'constipation', '2026-01-25 06:42:45', '<h5>Possible conditions:</h5><ul><li><strong>Iron-deficiency anemia</strong><br>Severity: Monitor<br>Advice: Low iron; follow diet or supplements.</li><br><li><strong>Anemia (non-iron)</strong><br>Severity: Monitor<br>Advice: Low blood count; follow doctor recommendations.</li><br><li><strong>Normal pregnancy</strong><br>Severity: Normal<br>Advice: Routine pregnancy; attend checkups and maintain healthy lifestyle.</li><br></ul>', NULL),
(275, 19, '2025-11-23', 'dizziness', '2026-01-25 06:42:58', '<h5>Possible conditions:</h5><ul><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br><li><strong>Gestational hypertension</strong><br>Severity: Monitor<br>Advice: High blood pressure; monitor and follow doctor instructions.</li><br><li><strong>Pulmonary embolism (PE)</strong><br>Severity: Urgent<br>Advice: Blood clot in lungs; sudden shortness of breath; emergency care.</li><br></ul>', NULL),
(276, 19, '2025-11-27', 'weakness', '2026-01-25 06:43:11', '<h5>Possible conditions:</h5><ul><li><strong>Placental insufficiency</strong><br>Severity: Monitor<br>Advice: Poor blood flow to placenta; monitor fetal growth.</li><br><li><strong>Iron-deficiency anemia</strong><br>Severity: Monitor<br>Advice: Low iron; follow diet or supplements.</li><br><li><strong>Anemia (non-iron)</strong><br>Severity: Monitor<br>Advice: Low blood count; follow doctor recommendations.</li><br></ul>', NULL),
(277, 19, '2025-12-12', 'swollen feet\r\nswollen eyes', '2026-01-25 06:43:29', '<h5>Possible conditions:</h5><ul><li><strong>Deep vein thrombosis (DVT)</strong><br>Severity: Urgent<br>Advice: Blood clot in legs; swelling/pain; seek urgent care.</li><br><li><strong>Hypercoagulable state</strong><br>Severity: Monitor<br>Advice: Increased risk of blood clots; follow doctor advice.</li><br><li><strong>Normal pregnancy</strong><br>Severity: Normal<br>Advice: Routine pregnancy; attend checkups and maintain healthy lifestyle.</li><br></ul>', NULL),
(278, 19, '2025-12-12', 'heart burn', '2026-01-25 06:43:49', '<h5>Possible conditions:</h5><ul><li><strong>Normal pregnancy</strong><br>Severity: Normal<br>Advice: Routine pregnancy; attend checkups and maintain healthy lifestyle.</li><br><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br><li><strong>Gestational diabetes</strong><br>Severity: Monitor<br>Advice: High blood sugar during pregnancy; monitor diet and glucose.</li><br></ul>', NULL),
(279, 19, '2025-12-16', 'vomiting', '2026-01-25 06:44:00', '<h5>Possible conditions:</h5><ul><li><strong>Hyperemesis gravidarum</strong><br>Severity: Risky<br>Advice: Severe vomiting causing dehydration; see doctor urgently.</li><br><li><strong>Ectopic pregnancy</strong><br>Severity: Urgent<br>Advice: Fertilized egg outside uterus; severe pain/bleeding; emergency.</li><br><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br></ul>', NULL),
(280, 19, '2025-12-16', 'rash', '2026-01-25 06:44:11', '<h5>Possible conditions:</h5><ul><li><strong>Cholestasis of pregnancy</strong><br>Severity: Monitor<br>Advice: Liver disorder causing itching; follow doctor advice.</li><br><li><strong>Normal pregnancy</strong><br>Severity: Normal<br>Advice: Routine pregnancy; attend checkups and maintain healthy lifestyle.</li><br><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br></ul>', NULL),
(281, 19, '2025-12-31', 'watery eyes', '2026-01-25 06:44:30', '<h5>Possible conditions:</h5><ul><li><strong>Normal pregnancy</strong><br>Severity: Normal<br>Advice: Routine pregnancy; attend checkups and maintain healthy lifestyle.</li><br><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br><li><strong>Gestational diabetes</strong><br>Severity: Monitor<br>Advice: High blood sugar during pregnancy; monitor diet and glucose.</li><br></ul>', NULL),
(282, 19, '2025-12-20', 'heaviness', '2026-01-25 06:44:51', 'No matching conditions found. Make sure to visit your doctor..', NULL),
(283, 19, '2025-12-20', 'leg heaviness', '2026-01-25 06:44:59', '<h5>Possible conditions:</h5><ul><li><strong>Varicose veins</strong><br>Severity: Normal<br>Advice: Swollen veins; elevate legs and stay active.</li><br><li><strong>Hypercoagulable state</strong><br>Severity: Monitor<br>Advice: Increased risk of blood clots; follow doctor advice.</li><br><li><strong>Normal pregnancy</strong><br>Severity: Normal<br>Advice: Routine pregnancy; attend checkups and maintain healthy lifestyle.</li><br></ul>', NULL),
(284, 19, '2026-01-08', 'heart burn', '2026-01-25 06:45:16', '<h5>Possible conditions:</h5><ul><li><strong>Normal pregnancy</strong><br>Severity: Normal<br>Advice: Routine pregnancy; attend checkups and maintain healthy lifestyle.</li><br><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br><li><strong>Gestational diabetes</strong><br>Severity: Monitor<br>Advice: High blood sugar during pregnancy; monitor diet and glucose.</li><br></ul>', NULL),
(285, 19, '2026-01-08', 'constipation', '2026-01-25 06:45:25', '<h5>Possible conditions:</h5><ul><li><strong>Iron-deficiency anemia</strong><br>Severity: Monitor<br>Advice: Low iron; follow diet or supplements.</li><br><li><strong>Anemia (non-iron)</strong><br>Severity: Monitor<br>Advice: Low blood count; follow doctor recommendations.</li><br><li><strong>Normal pregnancy</strong><br>Severity: Normal<br>Advice: Routine pregnancy; attend checkups and maintain healthy lifestyle.</li><br></ul>', NULL),
(286, 19, '2026-01-04', 'constipation', '2026-01-25 06:45:32', '<h5>Possible conditions:</h5><ul><li><strong>Iron-deficiency anemia</strong><br>Severity: Monitor<br>Advice: Low iron; follow diet or supplements.</li><br><li><strong>Anemia (non-iron)</strong><br>Severity: Monitor<br>Advice: Low blood count; follow doctor recommendations.</li><br><li><strong>Normal pregnancy</strong><br>Severity: Normal<br>Advice: Routine pregnancy; attend checkups and maintain healthy lifestyle.</li><br></ul>', NULL),
(287, 19, '2026-01-16', 'headache', '2026-01-25 06:45:47', '<h5>Possible conditions:</h5><ul><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br><li><strong>Placental abruption</strong><br>Severity: Urgent<br>Advice: Placenta separates early; severe pain/bleeding; emergency.</li><br><li><strong>Peripartum cardiomyopathy</strong><br>Severity: Urgent<br>Advice: Heart weakness near delivery; emergency care.</li><br></ul>', NULL),
(288, 19, '2026-01-20', 'headache', '2026-01-25 06:45:54', '<h5>Possible conditions:</h5><ul><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br><li><strong>Placental abruption</strong><br>Severity: Urgent<br>Advice: Placenta separates early; severe pain/bleeding; emergency.</li><br><li><strong>Peripartum cardiomyopathy</strong><br>Severity: Urgent<br>Advice: Heart weakness near delivery; emergency care.</li><br></ul>', NULL),
(289, 19, '2026-01-20', 'headache, back pain', '2026-01-25 06:46:03', '<h5>Possible conditions:</h5><ul><li><strong>Placental abruption</strong><br>Severity: Urgent<br>Advice: Placenta separates early; severe pain/bleeding; emergency.</li><br><li><strong>Preeclampsia</strong><br>Severity: Urgent<br>Advice: High blood pressure and organ stress; seek immediate medical care.</li><br><li><strong>Preterm labor</strong><br>Severity: Urgent<br>Advice: Labor before term; contact hospital immediately.</li><br></ul>', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `conditions`
--
ALTER TABLE `conditions`
  ADD PRIMARY KEY (`condition_id`);

--
-- Indexes for table `email_reminders`
--
ALTER TABLE `email_reminders`
  ADD PRIMARY KEY (`reminder_id`),
  ADD KEY `fk_email_reminders_user` (`user_id`);

--
-- Indexes for table `insights`
--
ALTER TABLE `insights`
  ADD PRIMARY KEY (`insight_id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `post_id` (`post_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `symptoms`
--
ALTER TABLE `symptoms`
  ADD PRIMARY KEY (`symptom_id`);

--
-- Indexes for table `symptom_condition`
--
ALTER TABLE `symptom_condition`
  ADD PRIMARY KEY (`condition_id`,`symptom_id`),
  ADD KEY `fk_symptom` (`symptom_id`);

--
-- Indexes for table `symptom_logs`
--
ALTER TABLE `symptom_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_diagnosis`
--
ALTER TABLE `user_diagnosis`
  ADD PRIMARY KEY (`diagnosis_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_symptoms`
--
ALTER TABLE `user_symptoms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_symptoms_user` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `email_reminders`
--
ALTER TABLE `email_reminders`
  MODIFY `reminder_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `insights`
--
ALTER TABLE `insights`
  MODIFY `insight_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `symptoms`
--
ALTER TABLE `symptoms`
  MODIFY `symptom_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `symptom_logs`
--
ALTER TABLE `symptom_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `user_diagnosis`
--
ALTER TABLE `user_diagnosis`
  MODIFY `diagnosis_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_symptoms`
--
ALTER TABLE `user_symptoms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=290;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `email_reminders`
--
ALTER TABLE `email_reminders`
  ADD CONSTRAINT `fk_email_reminders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `symptom_condition`
--
ALTER TABLE `symptom_condition`
  ADD CONSTRAINT `fk_condition` FOREIGN KEY (`condition_id`) REFERENCES `conditions` (`condition_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_symptom` FOREIGN KEY (`symptom_id`) REFERENCES `symptoms` (`symptom_id`) ON DELETE CASCADE;

--
-- Constraints for table `symptom_logs`
--
ALTER TABLE `symptom_logs`
  ADD CONSTRAINT `symptom_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `user_diagnosis`
--
ALTER TABLE `user_diagnosis`
  ADD CONSTRAINT `user_diagnosis_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_symptoms`
--
ALTER TABLE `user_symptoms`
  ADD CONSTRAINT `fk_user_symptoms_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
