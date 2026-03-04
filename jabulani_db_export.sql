-- Jabulani Database Dump
-- Generated at: 2026-03-04 09:17:14

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `addresses`;
CREATE TABLE `addresses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'shipping',
  `address_line_1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_line_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postal_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `addresses_user_id_foreign` (`user_id`),
  CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `banners`;
CREATE TABLE `banners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `banners` VALUES 
('1', 'Jabulani Group of Companies', 'Hardware stores, Crush & Quarry, Building material, Construction solutions', NULL, 'images/TRUCKS2.webp', '/contact', '2026-03-04 07:11:35', '2026-03-04 07:11:35'),
('2', 'Jabulani Group of Companies', 'Hardware stores, Crush & Quarry, Building material, Construction solutions', NULL, 'images/JBQ7.webp', '/contact', '2026-03-04 07:11:35', '2026-03-04 07:11:35'),
('3', 'Jabulani Group of Companies', 'Hardware stores, Crush & Quarry, Building material, Construction solutions', NULL, 'images/QTMACH2.webp', '/contact', '2026-03-04 07:11:35', '2026-03-04 07:11:35'),
('4', 'Jabulani Group of Companies', 'Hardware stores, Crush & Quarry, Building material, Construction solutions', NULL, 'images/YARD2.webp', '/contact', '2026-03-04 07:11:35', '2026-03-04 07:11:35'),
('5', 'Jabulani Group of Companies', 'Hardware stores, Crush & Quarry, Building material, Construction solutions', NULL, 'images/JB_BT.webp', '/contact', '2026-03-04 07:11:35', '2026-03-04 07:11:35');

DROP TABLE IF EXISTS `blog_categories`;
CREATE TABLE `blog_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `blog_categories` VALUES 
('1', 'General', 'general', '2026-03-04 07:11:49', '2026-03-04 07:11:49');

DROP TABLE IF EXISTS `blog_posts`;
CREATE TABLE `blog_posts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `blog_category_id` bigint unsigned NOT NULL,
  `author_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `feature_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_posts_slug_unique` (`slug`),
  KEY `blog_posts_blog_category_id_foreign` (`blog_category_id`),
  KEY `blog_posts_author_id_foreign` (`author_id`),
  CONSTRAINT `blog_posts_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `blog_posts_blog_category_id_foreign` FOREIGN KEY (`blog_category_id`) REFERENCES `blog_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `blog_posts` VALUES 
('1', '1', '1', 'Step-by-Step Guide to Installing Your Rainwater Tank', 'step-by-step-guide-to-installing-your-rainwater-tank', '<div class=\"post-entry\">
                            <p class=\"wow fadeInUp\">Installing a rainwater tank is an excellent way to conserve water and reduce dependence on municipal supplies. A well-installed tank ensures stability, longevity, and efficient water collection for household or garden use.</p>

                            <p class=\"wow fadeInUp\" data-wow-delay=\"0.2s\">Proper installation begins with selecting the ideal location, ensuring adequate space and accessibility. A stable foundation is essential to prevent shifting or damage over time. By following the correct steps—preparing a solid base, reinforcing with concrete and mesh, and securely positioning the tank—you can ensure long-term functionality and reliability.</p>
                            
                            <blockquote class=\"wow fadeInUp\" data-wow-delay=\"0.4s\">
                                <p>Set Up Your Rainwater Tank the Right Way: Ensure Stability, Maximize Water Harvesting, and Build a Sustainable Water Source for Years to Come.</p>
                            </blockquote>
                            
                            <p class=\"wow fadeInUp\" data-wow-delay=\"0.6s\">A well-planned rainwater tank installation not only helps in water conservation but also prevents potential issues such as foundation sinking or leakage. Taking the time to create a solid and level base ensures that your tank remains functional and secure.</p>
                            
                            <h2 class=\"wow fadeInUp\" data-wow-delay=\"0.8s\">Key Steps to <span>Successful</span> Rainwater Tank Installation</h2>
                            
                            <p class=\"wow fadeInUp\" data-wow-delay=\"1s\">Following a structured installation process is crucial for ensuring a long-lasting and efficient rainwater harvesting system. From foundation preparation to tank placement, every step plays a vital role in maintaining stability and effectiveness.</p>
                            
                            <ul class=\"wow fadeInUp\" data-wow-delay=\"1.2s\">
                                <li>Select a stable and accessible location for installation</li>
                                <li>Prepare a reinforced foundation with concrete and mesh</li>
                                <li>Use concrete blocks to build a sturdy base for the tank</li>
                                <li>Ensure proper leveling to prevent shifting or damage</li>
                                <li>Allow the foundation to cure before securely placing the tank</li>
                            </ul>
                            
                            <p class=\"wow fadeInUp\" data-wow-delay=\"1.4s\">Installing a rainwater tank is a valuable investment that provides environmental and cost-saving benefits. By ensuring a solid foundation, proper reinforcement, and correct placement, you can set up a reliable and efficient system for water collection. Take the right steps today to create a sustainable water source for the future.</p>
                            
                        </div>', 'images/rainwater_tip.webp', '1', '2026-03-04 07:11:49', '2026-03-04 07:11:49'),
('2', '1', '1', 'A Comprehensive Guide to Bricks and Blocks Used in Construction', 'a-comprehensive-guide-to-bricks-and-blocks-used-in-construction', '<div class=\"post-entry\">
                            <p class=\"wow fadeInUp\">Bricks and blocks are the backbone of any construction project, providing strength, durability, and versatility. Understanding the different types and their applications can help you make the right choices for your building needs. Let’s explore the various options available and their uses in construction.</p>

                            <p class=\"wow fadeInUp\" data-wow-delay=\"0.2s\">Face bricks and common bricks serve distinct purposes. Face bricks are aesthetically appealing and used in visible areas without the need for plastering. On the other hand, common bricks, also known as plaster bricks, are more functional and are typically covered with plaster for protection and finishing.</p>
                            
                            <blockquote class=\"wow fadeInUp\" data-wow-delay=\"0.4s\">
                                <p>Choosing the right bricks and blocks for your project ensures durability, stability, and a quality finish, making a significant difference in the longevity of your structure.</p>
                            </blockquote>
                            
                            <p class=\"wow fadeInUp\" data-wow-delay=\"0.6s\">Blocks, being larger than bricks, speed up the building process and require plastering to ensure waterproof walls unless using decorative face blocks. Construction standards recommend M140 blocks for external walls and M90 blocks for internal partitions.</p>
                            
                            <h2 class=\"wow fadeInUp\" data-wow-delay=\"0.8s\">Selecting the Right <span>Materials</span> for Construction Success</h2>
                            
                            <p class=\"wow fadeInUp\" data-wow-delay=\"1s\">Header bricks, often placed with the shorter side exposed, help strengthen the structure, while properly cutting blocks ensures precision in construction. To cut a block, score a line using a chisel and steel square, then gradually deepen it before breaking it cleanly. Alternatively, an angle grinder can be used with appropriate safety measures.</p>
                            
                            <ul class=\"wow fadeInUp\" data-wow-delay=\"1.2s\">
                                <li>Face bricks add style and eliminate the need for plastering.</li>
                                <li>Common bricks provide structural support and require finishing.</li>
                                <li>Blocks accelerate construction and must be plastered unless decorative.</li>
                                <li>Proper cutting techniques ensure precision and stability.</li>
                                <li>Choosing the right materials improves efficiency and long-term durability.</li>
                            </ul>
                            
                            <p class=\"wow fadeInUp\" data-wow-delay=\"1.4s\">Whether you are constructing a home, commercial building, or any other structure, selecting the right type of bricks and blocks is crucial. Quality materials ensure a sturdy, long-lasting build. For accurate estimations and expert advice, consult professionals to determine the right quantities and types for your project.</p>
                            
                        </div>', 'images/bricks_blocks (2).webp', '1', '2026-03-04 07:11:49', '2026-03-04 07:11:49'),
('3', '1', '1', 'Why Choose Aluminium Windows? Features & Benefits Explained', 'why-choose-aluminium-windows-features-benefits-explained', '<div class=\"post-entry\">
                            <p class=\"wow fadeInUp\">Looking to upgrade your property with aluminium windows? Here are the top features and benefits that make aluminium windows a fantastic choice.</p>

                            <p class=\"wow fadeInUp\" data-wow-delay=\"0.2s\">Aluminium windows provide a sleek, high-end finish that enhances the appearance of your property, making it stand out with modern elegance. Designed for both residential and architectural projects, these windows are a durable, low-maintenance solution that helps save money over time.</p>
                            
                            <blockquote class=\"wow fadeInUp\" data-wow-delay=\"0.4s\">
                                <p>Invest in Long-lasting Aluminium Windows: Upgrade Your Property\'s Look, Minimize Maintenance, and Maximize Energy Efficiency.</p>
                            </blockquote>
                            
                            <p class=\"wow fadeInUp\" data-wow-delay=\"0.6s\">Every aluminium window is built to last with features like mechanically crimped corners, factory glazing that meets N Pat glazing regulations, and hardware made to coastal specifications. They also come with all necessary accessories, including fixing lugs, handles, and stainless steel screws.</p>
                            
                            <h2 class=\"wow fadeInUp\" data-wow-delay=\"0.8s\">Why Choose Aluminium Windows?</h2>
                            
                            <p class=\"wow fadeInUp\" data-wow-delay=\"1s\">These windows offer exceptional insulation, keeping your home warmer in the winter with less energy consumption. Their design includes concealed friction stays, woodpile for better air and water resistance, and secure top-hung sashes to prevent any leaks.</p>
                            
                            <ul class=\"wow fadeInUp\" data-wow-delay=\"1.2s\">
                                <li>Durable anodised aluminium extrusions that are ideal for coastal environments</li>
                                <li>Available in PT (one vent), PTT (two vents), and P4TT (four vents) configurations</li>
                                <li>Powder-coated finishes ensure long-lasting colour and eliminate the need for painting</li>
                                <li>Lightweight yet strong construction provides lasting quality</li>
                            </ul>
                            
                            <p class=\"wow fadeInUp\" data-wow-delay=\"1.4s\">With the added benefit of a powder-coated finish, these windows remain pristine for years, making them an excellent investment for any property. Whether you\'re looking to update your home or complete an architectural project, aluminium windows are the ideal solution for both style and functionality.</p>
                            
                        </div>', 'images/alu_win.webp', '1', '2026-03-04 07:11:49', '2026-03-04 07:11:49');

DROP TABLE IF EXISTS `brands`;
CREATE TABLE `brands` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brands_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `brands` VALUES 
('1', 'Jab Logo', 'jab-logo', 'images/jab_logo.png', '2026-03-04 07:11:38', '2026-03-04 07:11:38'),
('2', 'Ingco Logo', 'ingco-logo', 'images/Ingco_Logo.png', '2026-03-04 07:11:39', '2026-03-04 07:11:39'),
('3', 'Hendok', 'hendok', 'images/hendok.png', '2026-03-04 07:11:39', '2026-03-04 07:11:39'),
('4', 'Harvey', 'harvey', 'images/harvey.png', '2026-03-04 07:11:39', '2026-03-04 07:11:39'),
('5', 'Makita', 'makita', 'images/makita.png', '2026-03-04 07:11:39', '2026-03-04 07:11:39'),
('6', 'Duramlogo', 'duramlogo', 'images/Duramlogo.png', '2026-03-04 07:11:39', '2026-03-04 07:11:39'),
('7', 'Afrisam', 'afrisam', 'images/Afrisam.png', '2026-03-04 07:11:39', '2026-03-04 07:11:39'),
('8', 'PGBISON', 'pgbison', 'images/PGBISON.png', '2026-03-04 07:11:40', '2026-03-04 07:11:40'),
('9', 'Lasher', 'lasher', 'images/Lasher.png', '2026-03-04 07:11:40', '2026-03-04 07:11:40'),
('10', 'Roofco', 'roofco', 'images/Roofco.png', '2026-03-04 07:11:40', '2026-03-04 07:11:40'),
('11', 'Geo', 'geo', 'images/geo.png', '2026-03-04 07:11:40', '2026-03-04 07:11:40'),
('12', 'Jojotanks', 'jojotanks', 'images/jojotanks.png', '2026-03-04 07:11:40', '2026-03-04 07:11:40'),
('13', 'Eureka', 'eureka', 'images/Eureka.png', '2026-03-04 07:11:40', '2026-03-04 07:11:40'),
('14', 'Flash', 'flash', 'images/Flash.png', '2026-03-04 07:11:40', '2026-03-04 07:11:40'),
('15', 'Corobrik', 'corobrik', 'images/Corobrik.png', '2026-03-04 07:11:41', '2026-03-04 07:11:41');

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` VALUES 
('1', 'Jabulani
                                            Products', 'jabulani-products', NULL, '2026-03-04 07:11:42', '2026-03-04 07:11:42'),
('2', 'Building
                                            Materials', 'building-materials', NULL, '2026-03-04 07:11:43', '2026-03-04 07:11:43'),
('3', 'Building
                                            Materails', 'building-materails', NULL, '2026-03-04 07:11:43', '2026-03-04 07:11:43'),
('4', 'Roof
                                            & Ceiling', 'roof-ceiling', NULL, '2026-03-04 07:11:43', '2026-03-04 07:11:43'),
('5', 'Tiles
                                            & Flooring', 'tiles-flooring', NULL, '2026-03-04 07:11:44', '2026-03-04 07:11:44'),
('6', 'Boards
                                            & Timber', 'boards-timber', NULL, '2026-03-04 07:11:45', '2026-03-04 07:11:45'),
('7', 'Hardware
                                            & Tools', 'hardware-tools', NULL, '2026-03-04 07:11:46', '2026-03-04 07:11:46'),
('8', 'Paint', 'paint', NULL, '2026-03-04 07:11:47', '2026-03-04 07:11:47'),
('9', 'Bathroom
                                            & Kitchen', 'bathroom-kitchen', NULL, '2026-03-04 07:11:47', '2026-03-04 07:11:47'),
('10', 'Plumbing
                                            Materials', 'plumbing-materials', NULL, '2026-03-04 07:11:48', '2026-03-04 07:11:48'),
('11', 'Plumbing
                                            & Electrical', 'plumbing-electrical', NULL, '2026-03-04 07:11:48', '2026-03-04 07:11:48');

DROP TABLE IF EXISTS `coupons`;
CREATE TABLE `coupons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_amount` decimal(12,2) NOT NULL,
  `discount_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed',
  `expires_at` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `gallery_items`;
CREATE TABLE `gallery_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `gallery_items` VALUES 
('1', 'Gallery Showcase', 'images/fleet.webp', 'General', '2026-03-04 07:11:50', '2026-03-04 07:11:50'),
('2', 'Gallery Showcase', 'images/quarry_truck.webp', 'General', '2026-03-04 07:11:50', '2026-03-04 07:11:50'),
('3', 'Gallery Showcase', 'images/meeting.webp', 'General', '2026-03-04 07:11:50', '2026-03-04 07:11:50'),
('4', 'Gallery Showcase', 'images/JABULANI BLOCKS.webp', 'General', '2026-03-04 07:11:50', '2026-03-04 07:11:50'),
('5', 'Gallery Showcase', 'images/hiesters.webp', 'General', '2026-03-04 07:11:50', '2026-03-04 07:11:50'),
('6', 'Gallery Showcase', 'images/jb_collection.webp', 'General', '2026-03-04 07:11:50', '2026-03-04 07:11:50'),
('7', 'Gallery Showcase', 'images/Bell.webp', 'General', '2026-03-04 07:11:50', '2026-03-04 07:11:50'),
('8', 'Gallery Showcase', 'images/quarrypic4.webp', 'General', '2026-03-04 07:11:50', '2026-03-04 07:11:50'),
('9', 'Gallery Showcase', 'images/TRUK.webp', 'General', '2026-03-04 07:11:51', '2026-03-04 07:11:51'),
('10', 'Gallery Showcase', 'images/blockyardpic1.webp', 'General', '2026-03-04 07:11:51', '2026-03-04 07:11:51'),
('11', 'Gallery Showcase', 'images/TRUCK (2).webp', 'General', '2026-03-04 07:11:51', '2026-03-04 07:11:51'),
('12', 'Gallery Showcase', 'images/big_machine1.webp', 'General', '2026-03-04 07:11:51', '2026-03-04 07:11:51'),
('13', 'Gallery Showcase', 'images/JB_BT.webp', 'General', '2026-03-04 07:11:51', '2026-03-04 07:11:51'),
('14', 'Gallery Showcase', 'images/MISSION.JPG', 'General', '2026-03-04 07:11:51', '2026-03-04 07:11:51'),
('15', 'Gallery Showcase', 'images/YARD2.webp', 'General', '2026-03-04 07:11:51', '2026-03-04 07:11:51'),
('16', 'Gallery Showcase', 'images/jb_tylon.webp', 'General', '2026-03-04 07:11:51', '2026-03-04 07:11:51'),
('17', 'Gallery Showcase', 'images/GREEN_TRUCK.webp', 'General', '2026-03-04 07:11:52', '2026-03-04 07:11:52'),
('18', 'Gallery Showcase', 'images/yellow_fleet.webp', 'General', '2026-03-04 07:11:52', '2026-03-04 07:11:52'),
('19', 'Gallery Showcase', 'images/sand_trucks.webp', 'General', '2026-03-04 07:11:52', '2026-03-04 07:11:52'),
('20', 'Gallery Showcase', 'images/quarrypic3.webp', 'General', '2026-03-04 07:11:52', '2026-03-04 07:11:52'),
('21', 'Gallery Showcase', 'images/window_pro.webp', 'General', '2026-03-04 07:11:52', '2026-03-04 07:11:52'),
('22', 'Gallery Showcase', 'images/quarrypic1.webp', 'General', '2026-03-04 07:11:52', '2026-03-04 07:11:52'),
('23', 'Gallery Showcase', 'images/jb_steel.webp', 'General', '2026-03-04 07:11:52', '2026-03-04 07:11:52'),
('24', 'Gallery Showcase', 'images/logistics_truck.webp', 'General', '2026-03-04 07:11:52', '2026-03-04 07:11:52'),
('25', 'Gallery Showcase', 'images/blockyardpic2.webp', 'General', '2026-03-04 07:11:53', '2026-03-04 07:11:53'),
('26', 'Gallery Showcase', 'images/big_machine.webp', 'General', '2026-03-04 07:11:53', '2026-03-04 07:11:53'),
('27', 'Gallery Showcase', 'images/tile_machine.webp', 'General', '2026-03-04 07:11:53', '2026-03-04 07:11:53'),
('28', 'Gallery Showcase', 'images/LOADER.webp', 'General', '2026-03-04 07:11:53', '2026-03-04 07:11:53');

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` VALUES 
('1', '2014_10_12_000000_create_users_table', '1'),
('2', '2014_10_12_100000_create_password_reset_tokens_table', '1'),
('3', '2019_08_19_000000_create_failed_jobs_table', '1'),
('4', '2019_12_14_000001_create_personal_access_tokens_table', '1'),
('5', '2026_03_03_091353_create_stores_table', '1'),
('6', '2026_03_03_091456_create_categories_table', '1'),
('7', '2026_03_03_091457_create_brands_table', '1'),
('8', '2026_03_03_091457_create_products_table', '1'),
('9', '2026_03_03_091458_create_product_store_stocks_table', '1'),
('10', '2026_03_03_091916_create_services_table', '1'),
('11', '2026_03_03_091916_create_team_members_table', '1'),
('12', '2026_03_03_091917_create_blog_categories_table', '1'),
('13', '2026_03_03_091918_create_blog_posts_table', '1'),
('14', '2026_03_03_091918_create_gallery_items_table', '1'),
('15', '2026_03_03_091919_create_banners_table', '1'),
('16', '2026_03_03_091920_create_orders_table', '1'),
('17', '2026_03_03_091921_create_coupons_table', '1'),
('18', '2026_03_03_091922_create_settings_table', '1'),
('19', '2026_03_03_091923_create_addresses_table', '1'),
('20', '2026_03_03_091925_create_order_items_table', '1'),
('21', '2026_03_03_092509_add_role_to_users_table', '1'),
('22', '2026_03_03_093847_add_image_to_products_table', '1'),
('23', '2026_03_03_102510_add_image_and_slug_to_stores_table', '1'),
('24', '2026_03_03_113709_add_customer_info_to_orders_table', '1'),
('25', '2026_03_04_070021_add_description_to_banners_table', '1'),
('26', '2026_03_04_070021_add_status_to_products_table', '1'),
('27', '2026_03_04_070805_add_indexes_to_tables', '1'),
('28', '2026_03_04_074800_add_order_enhanced_fields_to_orders_table', '2'),
('29', '2026_03_04_075845_create_store_user_pivot_table', '3');

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `vat` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_index` (`order_id`),
  KEY `order_items_product_id_index` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `order_items` VALUES 
('1', '1', '2', '1', '5.00', '0.75', '2026-03-04 07:36:46', '2026-03-04 07:36:46');

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_id` bigint unsigned NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `vat` decimal(12,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'delivery',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `lat` decimal(10,8) DEFAULT NULL,
  `lng` decimal(11,8) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_index` (`user_id`),
  KEY `orders_store_id_index` (`store_id`),
  KEY `orders_status_index` (`status`),
  KEY `orders_order_number_index` (`order_number`),
  CONSTRAINT `orders_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `orders` VALUES 
('1', 'JB-20260304-KDTZFS', '1', 'Shelly Miranda', 'veqaz@mailinator.com', '+1 (696) 968-9288', 'Quos sed qui vel sit', 'Dolor quis cupidatat', '123123', '3', '5.00', '0.75', 'delivered', 'cod', 'delivery', NULL, NULL, NULL, '2026-03-04 07:36:46', '2026-03-04 07:48:34');

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `product_store_stocks`;
CREATE TABLE `product_store_stocks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `store_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_store_stocks_product_id_foreign` (`product_id`),
  KEY `product_store_stocks_store_id_foreign` (`store_id`),
  CONSTRAINT `product_store_stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_store_stocks_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `product_store_stocks` VALUES 
('1', '2', '1', '0', '2026-03-04 07:25:34', '2026-03-04 07:25:34'),
('2', '2', '2', '0', '2026-03-04 07:25:34', '2026-03-04 07:25:34'),
('3', '2', '3', '0', '2026-03-04 07:25:34', '2026-03-04 07:25:34'),
('4', '2', '4', '0', '2026-03-04 07:25:34', '2026-03-04 07:25:34'),
('5', '2', '5', '0', '2026-03-04 07:25:34', '2026-03-04 07:25:34'),
('6', '2', '6', '0', '2026-03-04 07:25:34', '2026-03-04 07:25:34'),
('7', '2', '7', '0', '2026-03-04 07:25:34', '2026-03-04 07:25:34'),
('8', '2', '8', '0', '2026-03-04 07:25:34', '2026-03-04 07:25:34'),
('9', '2', '9', '0', '2026-03-04 07:25:35', '2026-03-04 07:25:35'),
('10', '2', '10', '0', '2026-03-04 07:25:35', '2026-03-04 07:25:35');

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category_id` bigint unsigned NOT NULL,
  `brand_id` bigint unsigned DEFAULT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `vat_rate` decimal(5,2) NOT NULL DEFAULT '15.00',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  KEY `products_status_index` (`status`),
  KEY `products_category_id_index` (`category_id`),
  KEY `products_brand_id_index` (`brand_id`),
  CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `products` VALUES 
('1', 'Bricks & Blocks', 'bricks-blocks-1', 'Jabulani Bricks & Blocks - High quality construction material.', '1', NULL, 'JB-822OIANO', '0.00', '15.00', '0', 'active', 'images/jabulani_blocks.webp', '2026-03-04 07:11:42', '2026-03-04 07:11:42'),
('2', 'Aluminium Windows & Doors', 'aluminium-windows-doors-3', 'Jabulani Aluminium Windows & Doors - High quality construction material.', '1', NULL, 'JB-4GWP9JZA', '5.00', '15.00', '0', 'active', 'images/Aluminium_Door.webp', '2026-03-04 07:11:42', '2026-03-04 07:25:33'),
('3', 'Structural & Concrete Elements', 'structural-concrete-elements-4', 'Jabulani Structural & Concrete Elements - High quality construction material.', '1', NULL, 'JB-MHPBNUJM', '0.00', '15.00', '0', 'active', 'images/Lintel_piller.webp', '2026-03-04 07:11:42', '2026-03-04 07:11:42'),
('4', 'Roofing', 'roofing-5', 'Jabulani Roofing - High quality construction material.', '1', NULL, 'JB-OUVOCUYZ', '0.00', '15.00', '0', 'active', 'images/ROOF_FLOOR2.webp', '2026-03-04 07:11:42', '2026-03-04 07:11:42'),
('5', 'Jabulani Tile Adhesive', 'jabulani-tile-adhesive-6', 'Jabulani Jabulani Tile Adhesive - High quality construction material.', '1', NULL, 'JB-WHXUPSDN', '0.00', '15.00', '0', 'active', 'images/jb_adhesive.webp', '2026-03-04 07:11:43', '2026-03-04 07:11:43'),
('6', 'Jabulani Crush Stone', 'jabulani-crush-stone-7', 'Jabulani Jabulani Crush Stone - High quality construction material.', '1', NULL, 'JB-I4TBN0W1', '0.00', '15.00', '0', 'active', 'images/crush2.webp', '2026-03-04 07:11:43', '2026-03-04 07:11:43'),
('7', 'Structural & Concrete Products', 'structural-concrete-products-9', 'Jabulani Structural & Concrete Products - High quality construction material.', '3', NULL, 'JB-YXX95626', '0.00', '15.00', '0', 'active', 'images/Lintel_piller.webp', '2026-03-04 07:11:43', '2026-03-04 07:11:43'),
('8', 'Cements & Premixes', 'cements-premixes-10', 'Jabulani Cements & Premixes - High quality construction material.', '3', NULL, 'JB-46OV1PGK', '0.00', '15.00', '0', 'active', 'images/Cement.webp', '2026-03-04 07:11:43', '2026-03-04 07:11:43'),
('9', 'Structural Steel', 'structural-steel-11', 'Jabulani Structural Steel - High quality construction material.', '3', NULL, 'JB-DX3OPO7Y', '0.00', '15.00', '0', 'active', 'images/steel.webp', '2026-03-04 07:11:43', '2026-03-04 07:11:43'),
('10', 'Reinforcing & Underlay', 'reinforcing-underlay-12', 'Jabulani Reinforcing & Underlay - High quality construction material.', '3', NULL, 'JB-ZT5VFXVP', '0.00', '15.00', '0', 'active', 'images/Fence.webp', '2026-03-04 07:11:43', '2026-03-04 07:11:43'),
('11', 'Roof Tiles', 'roof-tiles-13', 'Jabulani Roof Tiles - High quality construction material.', '4', NULL, 'JB-O41Q3LN8', '0.00', '15.00', '0', 'active', 'images/ROOF_FLOOR.webp', '2026-03-04 07:11:44', '2026-03-04 07:11:44'),
('12', 'Roof Sheeting', 'roof-sheeting-14', 'Jabulani Roof Sheeting - High quality construction material.', '4', NULL, 'JB-W4YZOFOY', '0.00', '15.00', '0', 'active', 'images/Sheeting.webp', '2026-03-04 07:11:44', '2026-03-04 07:11:44'),
('13', 'Fascia & Barge', 'fascia-barge-15', 'Jabulani Fascia & Barge - High quality construction material.', '4', NULL, 'JB-1NBXYTHH', '0.00', '15.00', '0', 'active', 'images/fascia.webp', '2026-03-04 07:11:44', '2026-03-04 07:11:44'),
('14', 'Ceiling Boards,Tiles & Pine', 'ceiling-boardstiles-pine-16', 'Jabulani Ceiling Boards,Tiles & Pine - High quality construction material.', '4', NULL, 'JB-DQDRARLY', '0.00', '15.00', '0', 'active', 'images/ceiling.webp', '2026-03-04 07:11:44', '2026-03-04 07:11:44'),
('15', 'Cornices', 'cornices-17', 'Jabulani Cornices - High quality construction material.', '4', NULL, 'JB-KXMTF6K6', '0.00', '15.00', '0', 'active', 'images/Cornice.webp', '2026-03-04 07:11:44', '2026-03-04 07:11:44'),
('16', 'Tiles', 'tiles-18', 'Jabulani Tiles - High quality construction material.', '5', NULL, 'JB-8XOKNPC2', '0.00', '15.00', '0', 'active', 'images/Tiles2.webp', '2026-03-04 07:11:44', '2026-03-04 07:11:44'),
('17', 'Tile Adhesives & Grouts', 'tile-adhesives-grouts-19', 'Jabulani Tile Adhesives & Grouts - High quality construction material.', '5', NULL, 'JB-YA5REGX5', '0.00', '15.00', '0', 'active', 'images/adhesive.webp', '2026-03-04 07:11:44', '2026-03-04 07:11:44'),
('18', 'Flooring', 'flooring-20', 'Jabulani Flooring - High quality construction material.', '5', NULL, 'JB-6VY2RPMA', '0.00', '15.00', '0', 'active', 'images/Flooring.webp', '2026-03-04 07:11:45', '2026-03-04 07:11:45'),
('19', 'Carpet & Accessories', 'carpet-accessories-21', 'Jabulani Carpet & Accessories - High quality construction material.', '5', NULL, 'JB-XTV5FZOZ', '0.00', '15.00', '0', 'active', 'images/Carpet.webp', '2026-03-04 07:11:45', '2026-03-04 07:11:45'),
('20', 'Boards', 'boards-22', 'Jabulani Boards - High quality construction material.', '6', NULL, 'JB-IWT3M4BV', '0.00', '15.00', '0', 'active', 'images/Plywood.webp', '2026-03-04 07:11:45', '2026-03-04 07:11:45'),
('21', 'Decking & Cladding', 'decking-cladding-23', 'Jabulani Decking & Cladding - High quality construction material.', '6', NULL, 'JB-TP8RCSOH', '0.00', '15.00', '0', 'active', 'images/Cladding.webp', '2026-03-04 07:11:45', '2026-03-04 07:11:45'),
('22', 'Mouldings & PAR Timber', 'mouldings-par-timber-24', 'Jabulani Mouldings & PAR Timber - High quality construction material.', '6', NULL, 'JB-MB7STA3P', '0.00', '15.00', '0', 'active', 'images/Mouldings.webp', '2026-03-04 07:11:45', '2026-03-04 07:11:45'),
('23', 'Poles & Exotic Timber', 'poles-exotic-timber-25', 'Jabulani Poles & Exotic Timber - High quality construction material.', '6', NULL, 'JB-N2XJNAUU', '0.00', '15.00', '0', 'active', 'images/Poles.webp', '2026-03-04 07:11:46', '2026-03-04 07:11:46'),
('24', 'Edging', 'edging-26', 'Jabulani Edging - High quality construction material.', '6', NULL, 'JB-ACALMFVW', '0.00', '15.00', '0', 'active', 'images/Edging.webp', '2026-03-04 07:11:46', '2026-03-04 07:11:46'),
('25', 'Safety & Protective Wear', 'safety-protective-wear-27', 'Jabulani Safety & Protective Wear - High quality construction material.', '7', NULL, 'JB-XSZFSZCU', '0.00', '15.00', '0', 'active', 'images/safety.webp', '2026-03-04 07:11:46', '2026-03-04 07:11:46'),
('26', 'Window & Door Accessories', 'window-door-accessories-28', 'Jabulani Window & Door Accessories - High quality construction material.', '7', NULL, 'JB-EHJLOC99', '0.00', '15.00', '0', 'active', 'images/LOCKS.webp', '2026-03-04 07:11:46', '2026-03-04 07:11:46'),
('27', 'Chain, Clamps & Aluminium Profile', 'chain-clamps-aluminium-profile-29', 'Jabulani Chain, Clamps & Aluminium Profile - High quality construction material.', '7', NULL, 'JB-KCHSJYZE', '0.00', '15.00', '0', 'active', 'images/Alu_profile.webp', '2026-03-04 07:11:46', '2026-03-04 07:11:46'),
('28', 'Power & Building Tools', 'power-building-tools-30', 'Jabulani Power & Building Tools - High quality construction material.', '7', NULL, 'JB-NA0OFSGK', '0.00', '15.00', '0', 'active', 'images/Power_tools.webp', '2026-03-04 07:11:46', '2026-03-04 07:11:46'),
('29', 'Gardening Tools', 'gardening-tools-31', 'Jabulani Gardening Tools - High quality construction material.', '7', NULL, 'JB-TAL3OSML', '0.00', '15.00', '0', 'active', 'images/garden.webp', '2026-03-04 07:11:47', '2026-03-04 07:11:47'),
('30', 'Waterproofing', 'waterproofing-32', 'Jabulani Waterproofing - High quality construction material.', '8', NULL, 'JB-LZVMAA6V', '0.00', '15.00', '0', 'active', 'images/Waterproofing.webp', '2026-03-04 07:11:47', '2026-03-04 07:11:47'),
('31', 'Enamel & PVA', 'enamel-pva-33', 'Jabulani Enamel & PVA - High quality construction material.', '8', NULL, 'JB-FHUKZJYK', '0.00', '15.00', '0', 'active', 'images/PVA.webp', '2026-03-04 07:11:47', '2026-03-04 07:11:47'),
('32', 'Roof paint', 'roof-paint-34', 'Jabulani Roof paint - High quality construction material.', '8', NULL, 'JB-P0KR7LNQ', '0.00', '15.00', '0', 'active', 'images/RoofPaint.webp', '2026-03-04 07:11:47', '2026-03-04 07:11:47'),
('33', 'Wood Coating', 'wood-coating-35', 'Jabulani Wood Coating - High quality construction material.', '8', NULL, 'JB-C4SE9QIW', '0.00', '15.00', '0', 'active', 'images/WoodCoat.webp', '2026-03-04 07:11:47', '2026-03-04 07:11:47'),
('34', 'Paint Accessories', 'paint-accessories-36', 'Jabulani Paint Accessories - High quality construction material.', '8', NULL, 'JB-QTFISZXH', '0.00', '15.00', '0', 'active', 'images/PaintBrush.webp', '2026-03-04 07:11:47', '2026-03-04 07:11:47'),
('35', 'Bathtroomware', 'bathtroomware-37', 'Jabulani Bathtroomware - High quality construction material.', '9', NULL, 'JB-TXJAT0F1', '0.00', '15.00', '0', 'active', 'images/Shower.webp', '2026-03-04 07:11:48', '2026-03-04 07:11:48'),
('36', 'Kitchen', 'kitchen-38', 'Jabulani Kitchen - High quality construction material.', '9', NULL, 'JB-DWXDNP41', '0.00', '15.00', '0', 'active', 'images/sink.webp', '2026-03-04 07:11:48', '2026-03-04 07:11:48'),
('37', 'Pipes, Waste Traps & Fittings', 'pipes-waste-traps-fittings-39', 'Jabulani Pipes, Waste Traps & Fittings - High quality construction material.', '10', NULL, 'JB-AYGIZ9PC', '0.00', '15.00', '0', 'active', 'images/Pipes.webp', '2026-03-04 07:11:48', '2026-03-04 07:11:48'),
('38', 'Geysers & Rainwater Goods', 'geysers-rainwater-goods-40', 'Jabulani Geysers & Rainwater Goods - High quality construction material.', '10', NULL, 'JB-ISZKCXE1', '0.00', '15.00', '0', 'active', 'images/geyser.webp', '2026-03-04 07:11:48', '2026-03-04 07:11:48'),
('39', 'Plumbing Accessories', 'plumbing-accessories-41', 'Jabulani Plumbing Accessories - High quality construction material.', '11', NULL, 'JB-PWMYDMRC', '0.00', '15.00', '0', 'active', 'images/plumbingTools.webp', '2026-03-04 07:11:48', '2026-03-04 07:11:48'),
('40', 'Wiring & Cables', 'wiring-cables-42', 'Jabulani Wiring & Cables - High quality construction material.', '11', NULL, 'JB-CE0UFCBU', '0.00', '15.00', '0', 'active', 'images/cables.webp', '2026-03-04 07:11:48', '2026-03-04 07:11:48'),
('41', 'plugs, Sockets & Bulbs', 'plugs-sockets-bulbs-43', 'Jabulani plugs, Sockets & Bulbs - High quality construction material.', '11', NULL, 'JB-PECNLJ6E', '0.00', '15.00', '0', 'active', 'images/Bulbs.webp', '2026-03-04 07:11:49', '2026-03-04 07:11:49');

DROP TABLE IF EXISTS `services`;
CREATE TABLE `services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `services_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `services` VALUES 
('1', 'Deliveries', 'deliveries', 'Make transportation hassle-free with our fast and reliable delivery service, straight to your site—saving you time and effort.', 'fa fa-cog', NULL, '2026-03-04 07:11:41', '2026-03-04 07:11:41'),
('2', 'Custom Aluminium Products', 'custom-aluminium-products', 'We offer custom-made aluminium doors and windows tailored to your exact measurements and specifications.', 'fa fa-cog', NULL, '2026-03-04 07:11:41', '2026-03-04 07:11:41'),
('3', 'Building Plan Assessment', 'building-plan-assessment', 'Our experienced staff will assist you in accurately determining the materials needed based on your building plans.', 'fa fa-cog', NULL, '2026-03-04 07:11:41', '2026-03-04 07:11:41'),
('4', 'Paint Mixing', 'paint-mixing', 'Whether you\'re looking for a bold statement shade or a subtle tone, we ensure a precise color match to bring your vision to life.', 'fa fa-cog', NULL, '2026-03-04 07:11:41', '2026-03-04 07:11:41'),
('5', 'Glass Cutting', 'glass-cutting', 'Precision-cut glass tailored to perfectly fit any window frame or door, ensuring a seamless and secure fit.', 'fa fa-cog', NULL, '2026-03-04 07:11:41', '2026-03-04 07:11:41'),
('6', 'Board Cutting', 'board-cutting', 'Precision-cut boards & clean edges tailored to fit your project needs.', 'fa fa-cog', NULL, '2026-03-04 07:11:41', '2026-03-04 07:11:41');

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `store_user`;
CREATE TABLE `store_user` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `store_user_store_id_foreign` (`store_id`),
  KEY `store_user_user_id_foreign` (`user_id`),
  CONSTRAINT `store_user_store_id_foreign` FOREIGN KEY (`store_id`) REFERENCES `stores` (`id`) ON DELETE CASCADE,
  CONSTRAINT `store_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `store_user` VALUES 
('1', '1', '2', '2026-03-04 07:59:31', '2026-03-04 07:59:31');

DROP TABLE IF EXISTS `stores`;
CREATE TABLE `stores` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lat` decimal(10,8) DEFAULT NULL,
  `lng` decimal(11,8) DEFAULT NULL,
  `contact_details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stores_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `stores` VALUES 
('1', 'Jabulani Build & Tiles Mount Frere', 'jabulani-build-tiles-mount-frere', 'images/buildTile.webp', '54 N2, KwaBacha, 5090, Eastern Cape, South Africa', 'Eastern Cape', '-30.90670000', '28.99210000', 'jabulanigroup79@gmail.com | 0731577357', '2026-03-04 07:11:35', '2026-03-04 07:40:33'),
('2', 'Jabulani Hardware Mount Frere', 'jabulani-hardware-mount-frere', 'images/JBshop(small).webp', '219 Main Street, KwaBhaca, 5090, South Africa', 'Eastern Cape', '-30.90660000', '28.99220000', 'jabulanihardware01@gmail.com | 0793039102', '2026-03-04 07:11:36', '2026-03-04 07:11:36'),
('3', 'Jabulani Steel Pipe & Hardware Mt Frere', 'jabulani-steel-pipe-hardware-mt-frere', 'images/JBshop(steel).webp', 'Erf 346 Ludidi street, KwaBhaca, 5090, South Africa', 'Eastern Cape', '-30.90720000', '28.99360000', 'jabulanisteelpipe2023@gmail.com | 0717791288', '2026-03-04 07:11:37', '2026-03-04 07:11:37'),
('4', 'Moin Hardware Mount Frere', 'moin-hardware-mount-frere', 'images/JBshop(moin).webp', 'Shop 218 Main Street 66, N2, KwaBhaca, 5090, South Africa', 'Eastern Cape', '-30.90670000', '28.99210000', 'moinhardware2@gmail.com | 0744858421', '2026-03-04 07:11:37', '2026-03-04 07:11:37'),
('5', 'Jabulani Hardware Shinta', 'jabulani-hardware-shinta', 'images/JBshop(shinta).webp', 'N2 Main Road NGQINIBENI KWA SHINTA, KwaBhaca, 5090, South Africa', 'Eastern Cape', '-30.96140170', '28.95917280', 'shintajabulani@gmail.com | 0631570514', '2026-03-04 07:11:38', '2026-03-04 07:11:38'),
('6', 'Jabulani Board & Tiles Umtata', 'jabulani-board-tiles-umtata', 'images/JBshop(Umtata).webp', '21 Sutherland St, Umtata Centeral, Mthatha, 5100, South Africa', 'Eastern Cape', '-31.58850000', '28.79440000', 'jabulaniboardandtiles@gmail.com | 0832817127', '2026-03-04 07:11:38', '2026-03-04 07:11:38'),
('7', 'Jabulani Build & Tiles Qumbu', 'jabulani-build-tiles-qumbu', 'images/JBshop(Qumbu).webp', 'Shop 258, N2 Main St, Qumbu, 5180, South Africa', 'Eastern Cape', '-31.15970000', '28.86910000', 'jabulaniqumbu258@gmail.com | 0783116448', '2026-03-04 07:11:38', '2026-03-04 07:11:38'),
('8', 'Jabulani Hardware Tsolo', 'jabulani-hardware-tsolo', 'images/JBshop(Tsolo).webp', 'ERF 105 Main Street, Tsolo, 5170, South Africa', 'Eastern Cape', '-31.30910000', '28.75450000', 'jabulanigroup2002@gmail.com | 0717999497', '2026-03-04 07:11:38', '2026-03-04 07:11:38'),
('9', 'Jabulani Group BlockYard Tsolo', 'jabulani-group-blockyard-tsolo', 'images/JBshop(blockyard).webp', 'ERF 105 Main Street, Tsolo, 5170, South Africa', 'Eastern Cape', '-31.31910000', '28.76260000', 'jabulanigroup2002@gmail.com | 0613303916', '2026-03-04 07:11:38', '2026-03-04 07:11:38'),
('10', 'Jabulani Quarries Tsolo', 'jabulani-quarries-tsolo', 'images/quarrypic3.webp', ' Portion of Farm 541 Tiki Tiki Main Street, Tsolo, 5170, South Africa', 'Eastern Cape', '-31.33799160', '28.76128650', 'jabulanigroup2002@gmail.com | 0613303916', '2026-03-04 07:11:38', '2026-03-04 07:11:38');

DROP TABLE IF EXISTS `team_members`;
CREATE TABLE `team_members` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `team_members` VALUES 
('1', 'Naeem Ahmed', 'CEO Jabulani Group of Companies', 'Naeem Ahmad, born on May 2, 1978, is a visionary entrepreneur with over 32 years of experience in the hardware and building materials industry. Before founding Jabulani Group of Companies in 2002, he spent 10 years working as a retailer and salesperson, mastering the trade and understanding customer needs firsthand.

                                From the very beginning, his mission was clear—to give back to the community by making high-quality building materials affordable for everyone. His commitment to accessibility and affordability led him to expand Jabulani Group of Companies, opening eight stores across four cities: Qumbu, Tsolo, Mount Frere, and Mthatha ensuring that each store was spacious and well-organized so customers could shop comfortably.
                                
                                To further reduce costs without compromising quality, Naeem Ahmad began importing premium hardware and building materials from international markets like China. He also established his own state-of-the-art production plant, manufacturing SABS-approved blocks, bricks, custom aluminum products and so on. Today through his leadership, Jabulani Group of Companies is a leading supplier, delivering high-quality and affordable materials across the Eastern Cape and beyond.', 'images/CEO2.png', '2026-03-04 07:11:42', '2026-03-04 07:11:42');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` VALUES 
('1', 'Super Admin', 'admin@jabulani.com', NULL, '$2y$10$p9H.ZfiHo9poxuAmC2cEFuN1Dnyw/1EoWfPZ5yZt.EaehCKvziDLy', NULL, '2026-03-04 07:11:34', '2026-03-04 07:11:34', 'admin'),
('2', 'User', 'branch@gmail.com', NULL, '$2y$10$e6WIZsLzwDVyBGeordZ6gerwhdiV50cjslTZt4BaEVpBm9t4DZFBS', NULL, '2026-03-04 07:38:30', '2026-03-04 07:38:30', 'manager');

SET FOREIGN_KEY_CHECKS=1;
