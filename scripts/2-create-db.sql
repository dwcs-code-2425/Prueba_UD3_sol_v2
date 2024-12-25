SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE DATABASE IF NOT EXISTS `dbusuariosv2` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `dbusuariosv2`;
--
-- Database: `dbusuariosv2`
--


--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(320) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pwdhash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT IGNORE INTO `usuario`(`id`, `email`, `pwdhash`) VALUES (1,'user1@edu.es','$2y$10$wnH5AW7Tk7WFsAjRr8r.2.hJV8Bn.1kXV5WsQR11iCT2.pNjvvXEW');
INSERT IGNORE INTO `usuario`(`id`, `email`, `pwdhash`) VALUES (2,'user2@edu.es','$2y$10$21zpjzgVWLO65cnOxvY2iuF6d2ZXu2nza8QkKDIiHHQoQEGPASV4W');




-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(255) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT IGNORE INTO `categories` (`CategoryID`, `CategoryName`, `Description`) VALUES
(1, 'Beverages', 'Soft drinks, coffees, teas, beers, and ales'),
(2, 'Condiments', 'Sweet and savory sauces, relishes, spreads, and seasonings'),
(3, 'Confections', 'Desserts, candies, and sweet breads'),
(4, 'Dairy Products', 'Cheeses'),
(5, 'Grains/Cereals', 'Breads, crackers, pasta, and cereal'),
(6, 'Meat/Poultry', 'Prepared meats'),
(7, 'Produce', 'Dried fruit and bean curd'),
(8, 'Seafood', 'Seaweed and fish');

-- --------------------------------------------------------


-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(255) DEFAULT NULL,    
  `Price` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT IGNORE INTO `products` (`ProductID`, `ProductName`,  `Price`) VALUES
(1, 'Chais',  18),
(2, 'Chang',  19),
(3, 'Aniseed Syrup',  10),
(4, 'Chef Anton\'s Cajun Seasoning',  22),
(5, 'Chef Anton\'s Gumbo Mix',  21.35),
(6, 'Grandma\'s Boysenberry Spread',  25),
(7, 'Uncle Bob\'s Organic Dried Pears',  30),
(8, 'Northwoods Cranberry Sauce',  40),
(9, 'Mishi Kobe Niku',  97),
(10, 'Ikura', 31),
(11, 'Queso Cabrales',  21);

-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `products_categories` (
  `ProductID` int(11)  REFERENCES products(`ProductID`),
  `CategoryID` int(11) REFERENCES categories(`CategoryID`)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products_categories``
--

INSERT IGNORE INTO `products_categories` (`ProductID`, `CategoryID`) VALUES
(1, 1),
(2, 1),
(3, 2),
(4, 2),
(5,  2),
(6,  2),
(7, 7),
(8, 2),
(9, 6),
(10,  8),
(11, 4);


--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CategoryID`);




--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ProductID`);
 
 
 
 --
-- Indexes for table `products`
--
ALTER TABLE `products_categories`
  ADD PRIMARY KEY (`ProductID`, `CategoryID`);
 

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;


--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;





ALTER TABLE `products_categories`
  ADD CONSTRAINT `prods_cats_cat_fk_1` FOREIGN KEY (`CategoryID`) REFERENCES `categories` (`CategoryID`),
  ADD CONSTRAINT `prods_cats_prod_fk_2` FOREIGN KEY (`ProductID`) REFERENCES `products` (`ProductID`);


COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;