SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `trader2`
--

-- --------------------------------------------------------

CREATE TABLE `aggr` (
  `idaggr` int(11) NOT NULL,
  `user_iduser` int(11) NOT NULL,
  `character_eve_idcharacter` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------


CREATE TABLE `api` (
  `apikey` int(11) NOT NULL,
  `vcode` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------


CREATE TABLE `assets` (
  `idassets` bigint(11) NOT NULL,
  `characters_eve_idcharacters` int(11) NOT NULL,
  `item_eve_iditem` int(11) NOT NULL,
  `quantity` bigint(11) NOT NULL,
  `locationID` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------


CREATE TABLE `calendar` (
  `days` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------


CREATE TABLE `changelog` (
  `idchangelog` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `content` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE `characters` (
  `eve_idcharacter` int(10) NOT NULL,
  `name` varchar(45) NOT NULL,
  `balance` bigint(20) NOT NULL,
  `api_apikey` int(11) NOT NULL,
  `networth` bigint(20) NOT NULL,
  `escrow` bigint(20) NOT NULL,
  `total_sell` bigint(20) NOT NULL,
  `broker_relations` enum('0','1','2','3','4','5') NOT NULL DEFAULT '0',
  `accounting` enum('0','1','2','3','4','5') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DELIMITER $$
CREATE TRIGGER `update_net_history_insert` AFTER INSERT ON `characters` FOR EACH ROW BEGIN
  
    IF NEW.eve_idcharacter THEN
      SET @characters = NEW.eve_idcharacter, @total_wallet = NEW.balance, @dates = date(NOW()), @total_assets = NEW.networth, @total_sell = NEW.total_sell, @total_escrow = NEW.escrow;

    END IF;
    
    INSERT INTO net_history (characters_eve_idcharacters, date, total_wallet, total_assets, total_sell, total_escrow) VALUES (@characters, @dates, @total_wallet, @total_assets, @total_sell, @total_escrow);
    
    END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_net_history_update` AFTER UPDATE ON `characters` FOR EACH ROW BEGIN
      SET @characters = NEW.eve_idcharacter, @total_wallet = NEW.balance, @dates = date(NOW()), @total_assets = NEW.networth, @total_sell = NEW.total_sell, @total_escrow = NEW.escrow;



    DELETE FROM net_history WHERE characters_eve_idcharacters = @characters AND date = @dates;
    INSERT INTO net_history (characters_eve_idcharacters, date, total_wallet, total_assets, total_sell, total_escrow) VALUES (@characters, @dates, @total_wallet, @total_assets, @total_sell, @total_escrow);
    
    END
$$
DELIMITER ;

-- --------------------------------------------------------


CREATE TABLE `characters_public` (
  `eve_idcharacters` int(11) NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------


CREATE TABLE `citadel_tax` (
  `idcitadel_tax` int(11) NOT NULL,
  `character_eve_idcharacter` int(11) NOT NULL,
  `station_eve_idstation` bigint(11) NOT NULL,
  `value` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------


CREATE TABLE `contracts` (
  `eve_idcontracts` int(11) NOT NULL,
  `issuer_id` int(11) DEFAULT NULL,
  `acceptor_id` int(11) DEFAULT NULL,
  `status` enum('outstanding','deleted','completed','failed','completedByIssuer','completedByContractor','cancelled','rejected','reversed','inProgress') NOT NULL,
  `availability` enum('Public','Private') NOT NULL,
  `type` enum('ItemExchange','Courier','Loan','Auction') NOT NULL,
  `creation_date` datetime NOT NULL,
  `expiration_date` datetime DEFAULT NULL,
  `accepted_date` datetime DEFAULT NULL,
  `completed_date` datetime DEFAULT NULL,
  `price` double(15,2) DEFAULT NULL,
  `reward` double(15,2) DEFAULT NULL,
  `colateral` double(15,2) DEFAULT NULL,
  `volume` bigint(20) DEFAULT NULL,
  `fromStation_eve_idstation` bigint(11) DEFAULT NULL,
  `toStation_eve_idstation` bigint(11) DEFAULT NULL,
  `characters_eve_idcharacters` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

CREATE TABLE `corporation` (
  `eve_idcorporation` int(11) NOT NULL,
  `faction_eve_idfaction` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------


CREATE TABLE `faction` (
  `eve_idfaction` int(11) NOT NULL,
  `name` varchar(45) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------


CREATE TABLE `history` (
  `idhistory` bigint(20) NOT NULL,
  `characters_eve_idcharacters` int(11) NOT NULL,
  `date` date NOT NULL,
  `total_buy` double(15,2) DEFAULT NULL,
  `total_sell` double(15,2) DEFAULT NULL,
  `total_profit` double(15,2) DEFAULT NULL,
  `margin` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------


CREATE TABLE `item` (
  `eve_iditem` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `volume` double NOT NULL,
  `type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------


CREATE TABLE `itemcontents` (
  `iditemcontents` int(11) NOT NULL,
  `itemlist_iditemlist` int(11) NOT NULL,
  `item_eve_iditem` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------


CREATE TABLE `itemlist` (
  `iditemlist` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `user_iduser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------


CREATE TABLE `item_price_data` (
  `item_eve_iditem` int(11) NOT NULL,
  `price_evecentral` double(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------


CREATE TABLE `log` (
  `idlog` bigint(20) NOT NULL,
  `user_iduser` int(11) NOT NULL,
  `type` varchar(45) NOT NULL,
  `datetime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------


CREATE TABLE `net_history` (
  `characters_eve_idcharacters` int(11) NOT NULL,
  `date` date NOT NULL,
  `total_wallet` double(15,2) NOT NULL,
  `total_assets` double(15,2) NOT NULL,
  `total_sell` double(15,2) NOT NULL,
  `total_escrow` double(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

CREATE TABLE `new_info` (
  `characters_eve_idcharacters` int(11) NOT NULL,
  `contracts` int(11) NOT NULL,
  `orders` int(11) NOT NULL,
  `transactions` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------


CREATE TABLE `orders` (
  `idorders` bigint(11) NOT NULL,
  `eve_item_iditem` int(11) NOT NULL,
  `station_eve_idstation` bigint(20) NOT NULL,
  `characters_eve_idcharacters` int(11) NOT NULL,
  `price` double(15,2) DEFAULT NULL,
  `volume_remaining` bigint(20) NOT NULL,
  `duration` int(11) NOT NULL,
  `escrow` double(15,2) NOT NULL,
  `type` enum('buy','sell') CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `order_state` enum('open','closed','expired','canceled','pending','character_deleted') NOT NULL,
  `order_range` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `transkey` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

CREATE TABLE `order_status` (
  `orders_transkey` bigint(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `timestamp_check` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

CREATE TABLE `profit` (
  `idprofit` bigint(11) NOT NULL,
  `transaction_idbuy_buy` bigint(11) NOT NULL,
  `transaction_idbuy_sell` bigint(11) NOT NULL,
  `profit_unit` double(15,2) DEFAULT NULL,
  `timestamp_buy` datetime NOT NULL,
  `timestamp_sell` datetime NOT NULL,
  `characters_eve_idcharacters_IN` int(11) NOT NULL,
  `characters_eve_idcharacters_OUT` int(11) NOT NULL,
  `quantity_profit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

CREATE TABLE `region` (
  `eve_idregion` int(10) NOT NULL,
  `name` varchar(45) NOT NULL,
  `isKS` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE `ship_volumes` (
  `marketGroupID` int(11) NOT NULL,
  `marketGroupName` varchar(100) DEFAULT NULL,
  `description` varchar(3000) DEFAULT NULL,
  `vol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE `standings_corporation` (
  `idstandings_corporation` int(11) NOT NULL,
  `characters_eve_idcharacters` int(11) NOT NULL,
  `corporation_eve_idcorporation` int(11) NOT NULL,
  `value` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------


CREATE TABLE `standings_faction` (
  `idstandings_faction` int(11) NOT NULL,
  `characters_eve_idcharacters` int(11) NOT NULL,
  `faction_eve_idfaction` int(11) NOT NULL,
  `value` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------


CREATE TABLE `station` (
  `eve_idstation` bigint(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `system_eve_idsystem` int(10) NOT NULL,
  `corporation_eve_idcorporation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE `system` (
  `eve_idsystem` int(10) NOT NULL,
  `name` varchar(45) NOT NULL,
  `region_eve_idregion` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE `traderoutes` (
  `idtraderoute` int(11) NOT NULL,
  `user_iduser` int(11) NOT NULL,
  `station_eve_idstation_from` bigint(11) NOT NULL,
  `station_eve_idstation_to` bigint(11) NOT NULL,
  `starting_character` int(11) DEFAULT NULL,
  `destination_character` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

CREATE TABLE `transaction` (
  `idbuy` bigint(11) NOT NULL,
  `time` datetime NOT NULL,
  `quantity` int(10) NOT NULL,
  `price_unit` double(15,2) NOT NULL,
  `price_total` double(15,2) NOT NULL,
  `transaction_type` enum('Buy','Sell') NOT NULL,
  `character_eve_idcharacter` int(10) NOT NULL,
  `station_eve_idstation` bigint(20) NOT NULL,
  `item_eve_iditem` int(11) NOT NULL,
  `transkey` bigint(20) NOT NULL,
  `client` varchar(100) NOT NULL,
  `remaining` bigint(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE `user` (
  `iduser` int(11) NOT NULL,
  `username` varchar(45) NOT NULL,
  `registration_date` datetime NOT NULL,
  `password` varchar(100) NOT NULL,
  `reports` enum('none','daily','weekly','monthly') NOT NULL,
  `email` varchar(45) NOT NULL,
  `salt` varchar(100) NOT NULL,
  `login_count` int(11) NOT NULL,
  `updating` int(1) NOT NULL,
  `default_buy_behaviour` tinyint(1) NOT NULL DEFAULT '1',
  `default_sell_behaviour` int(1) NOT NULL DEFAULT '1',
  `cross_character_profits` tinyint(1) NOT NULL DEFAULT '1',
  `ignore_citadel_tax` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_profit_details`
-- (See below for the actual view)
--
CREATE TABLE `v_profit_details` (
`idprofit` bigint(11)
,`buy_ref` bigint(11)
,`sell_ref` bigint(11)
,`quantity_sell` int(10)
,`quantity_buy` int(10)
,`profit_quantity` int(11)
,`price_unit_buy` double(15,2)
,`price_unit_sell` double(15,2)
,`price_total_buy` double(15,2)
,`price_total_sell` double(15,2)
,`item` varchar(100)
,`character_buy` varchar(45)
,`character_sell` varchar(45)
,`station_buy` varchar(100)
,`station_buy_id` bigint(10)
,`station_sell` varchar(100)
,`station_sell_id` bigint(10)
,`time_buy` datetime
,`time_sell` datetime
,`difference` time
,`profit_unit` double(15,2)
,`profit_total` double(19,2)
,`boughtFrom` varchar(100)
,`soldTo` varchar(100)
,`character_buy_id` int(10)
,`character_sell_id` int(10)
,`item_id` int(11)
,`margin` double(23,6)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_transaction_details`
-- (See below for the actual view)
--
CREATE TABLE `v_transaction_details` (
`idbuy` bigint(11)
,`character_id` int(10)
,`time` datetime
,`quantity` int(10)
,`price_unit` double(15,2)
,`price_total` double(15,2)
,`transaction_type` enum('Buy','Sell')
,`transkey` bigint(20)
,`client` varchar(100)
,`character_name` varchar(45)
,`station_name` varchar(100)
,`item_name` varchar(100)
,`item_id` int(11)
,`station_id` bigint(10)
,`region_id` int(10)
,`region_name` varchar(45)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_user_characters`
-- (See below for the actual view)
--
CREATE TABLE `v_user_characters` (
`iduser` int(11)
,`username` varchar(45)
,`user_iduser` int(11)
,`character_eve_idcharacter` int(10)
,`name` varchar(45)
,`apikey` int(11)
);

-- --------------------------------------------------------

--
-- Structure for view `v_profit_details`
--
DROP TABLE IF EXISTS `v_profit_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_profit_details`  AS  select `profit`.`idprofit` AS `idprofit`,`profit`.`transaction_idbuy_buy` AS `buy_ref`,`profit`.`transaction_idbuy_sell` AS `sell_ref`,`t2`.`quantity` AS `quantity_sell`,`t1`.`quantity` AS `quantity_buy`,`profit`.`quantity_profit` AS `profit_quantity`,`t1`.`price_unit` AS `price_unit_buy`,`t2`.`price_unit` AS `price_unit_sell`,`t1`.`price_total` AS `price_total_buy`,`t2`.`price_total` AS `price_total_sell`,`item`.`name` AS `item`,`c1`.`name` AS `character_buy`,`c2`.`name` AS `character_sell`,`s1`.`name` AS `station_buy`,`s1`.`eve_idstation` AS `station_buy_id`,`s2`.`name` AS `station_sell`,`s2`.`eve_idstation` AS `station_sell_id`,`t1`.`time` AS `time_buy`,`t2`.`time` AS `time_sell`,timediff(`t2`.`time`,`t1`.`time`) AS `difference`,`profit`.`profit_unit` AS `profit_unit`,(`profit`.`profit_unit` * `profit`.`quantity_profit`) AS `profit_total`,`t1`.`client` AS `boughtFrom`,`t2`.`client` AS `soldTo`,`c1`.`eve_idcharacter` AS `character_buy_id`,`c2`.`eve_idcharacter` AS `character_sell_id`,`item`.`eve_iditem` AS `item_id`,((`profit`.`profit_unit` / `t1`.`price_unit`) * 100) AS `margin` from (((((((`profit` join `transaction` `t1` on((`t1`.`idbuy` = `profit`.`transaction_idbuy_buy`))) join `transaction` `t2` on((`t2`.`idbuy` = `profit`.`transaction_idbuy_sell`))) join `item` on((`item`.`eve_iditem` = (select `transaction`.`item_eve_iditem` from `transaction` where (`transaction`.`idbuy` = `profit`.`transaction_idbuy_buy`))))) join `characters` `c1` on((`c1`.`eve_idcharacter` = (select `transaction`.`character_eve_idcharacter` from `transaction` where (`transaction`.`idbuy` = `profit`.`transaction_idbuy_buy`))))) join `characters` `c2` on((`c2`.`eve_idcharacter` = (select `transaction`.`character_eve_idcharacter` from `transaction` where (`transaction`.`idbuy` = `profit`.`transaction_idbuy_sell`))))) join `station` `s1` on((`s1`.`eve_idstation` = (select `transaction`.`station_eve_idstation` from `transaction` where (`transaction`.`idbuy` = `profit`.`transaction_idbuy_buy`))))) join `station` `s2` on((`s2`.`eve_idstation` = (select `transaction`.`station_eve_idstation` from `transaction` where (`transaction`.`idbuy` = `profit`.`transaction_idbuy_sell`))))) ;

-- --------------------------------------------------------

--
-- Structure for view `v_transaction_details`
--
DROP TABLE IF EXISTS `v_transaction_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_transaction_details`  AS  select `transaction`.`idbuy` AS `idbuy`,`characters`.`eve_idcharacter` AS `character_id`,`transaction`.`time` AS `time`,`transaction`.`quantity` AS `quantity`,`transaction`.`price_unit` AS `price_unit`,`transaction`.`price_total` AS `price_total`,`transaction`.`transaction_type` AS `transaction_type`,`transaction`.`transkey` AS `transkey`,`transaction`.`client` AS `client`,`characters`.`name` AS `character_name`,ifnull(`station`.`name`,`transaction`.`station_eve_idstation`) AS `station_name`,`item`.`name` AS `item_name`,`item`.`eve_iditem` AS `item_id`,`station`.`eve_idstation` AS `station_id`,`region`.`eve_idregion` AS `region_id`,coalesce(`region`.`name`,'unknown') AS `region_name` from (((((`transaction` left join `station` on((`transaction`.`station_eve_idstation` = `station`.`eve_idstation`))) left join `system` on((`system`.`eve_idsystem` = `station`.`system_eve_idsystem`))) left join `region` on((`region`.`eve_idregion` = `system`.`region_eve_idregion`))) left join `characters` on((`transaction`.`character_eve_idcharacter` = `characters`.`eve_idcharacter`))) left join `item` on((`transaction`.`item_eve_iditem` = `item`.`eve_iditem`))) ;

-- --------------------------------------------------------

--
-- Structure for view `v_user_characters`
--
DROP TABLE IF EXISTS `v_user_characters`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_user_characters`  AS  select `user`.`iduser` AS `iduser`,`user`.`username` AS `username`,`aggr`.`user_iduser` AS `user_iduser`,`aggr`.`character_eve_idcharacter` AS `character_eve_idcharacter`,`characters`.`name` AS `name`,`characters`.`api_apikey` AS `apikey` from ((`user` join `aggr` on((`user`.`iduser` = `aggr`.`user_iduser`))) join `characters` on((`aggr`.`character_eve_idcharacter` = `characters`.`eve_idcharacter`))) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aggr`
--
ALTER TABLE `aggr`
  ADD PRIMARY KEY (`idaggr`),
  ADD KEY `fk_aggr_user1_idx` (`user_iduser`),
  ADD KEY `fk_aggr_character1_idx` (`character_eve_idcharacter`);

--
-- Indexes for table `api`
--
ALTER TABLE `api`
  ADD PRIMARY KEY (`apikey`);

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`idassets`),
  ADD KEY `characters_eve_idcharacters` (`characters_eve_idcharacters`),
  ADD KEY `item_eve_iditem` (`item_eve_iditem`);

--
-- Indexes for table `calendar`
--
ALTER TABLE `calendar`
  ADD PRIMARY KEY (`days`);

--
-- Indexes for table `changelog`
--
ALTER TABLE `changelog`
  ADD PRIMARY KEY (`idchangelog`);

--
-- Indexes for table `characters`
--
ALTER TABLE `characters`
  ADD PRIMARY KEY (`eve_idcharacter`),
  ADD UNIQUE KEY `name_UNIQUE` (`name`),
  ADD KEY `fk_character_api1_idx` (`api_apikey`);

--
-- Indexes for table `characters_public`
--
ALTER TABLE `characters_public`
  ADD PRIMARY KEY (`eve_idcharacters`);

--
-- Indexes for table `citadel_tax`
--
ALTER TABLE `citadel_tax`
  ADD PRIMARY KEY (`idcitadel_tax`),
  ADD UNIQUE KEY `idx_pair` (`character_eve_idcharacter`,`station_eve_idstation`),
  ADD KEY `idex_station` (`station_eve_idstation`),
  ADD KEY `idx_character` (`character_eve_idcharacter`);

--
-- Indexes for table `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`eve_idcontracts`,`characters_eve_idcharacters`),
  ADD KEY `index_station` (`fromStation_eve_idstation`),
  ADD KEY `index_station_to` (`toStation_eve_idstation`),
  ADD KEY `index_characters` (`characters_eve_idcharacters`);

--
-- Indexes for table `corporation`
--
ALTER TABLE `corporation`
  ADD PRIMARY KEY (`eve_idcorporation`),
  ADD KEY `fk_corp` (`eve_idcorporation`),
  ADD KEY `index_faction` (`faction_eve_idfaction`);

--
-- Indexes for table `faction`
--
ALTER TABLE `faction`
  ADD PRIMARY KEY (`eve_idfaction`);

--
-- Indexes for table `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`idhistory`),
  ADD UNIQUE KEY `unique_char_day` (`characters_eve_idcharacters`,`date`),
  ADD KEY `characters` (`characters_eve_idcharacters`),
  ADD KEY `date` (`date`),
  ADD KEY `character_index` (`characters_eve_idcharacters`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`eve_iditem`),
  ADD KEY `item_index` (`name`);

--
-- Indexes for table `itemcontents`
--
ALTER TABLE `itemcontents`
  ADD PRIMARY KEY (`iditemcontents`),
  ADD UNIQUE KEY `unique_item_list` (`itemlist_iditemlist`,`item_eve_iditem`),
  ADD KEY `idx_list` (`itemlist_iditemlist`),
  ADD KEY `idx_item` (`item_eve_iditem`);

--
-- Indexes for table `itemlist`
--
ALTER TABLE `itemlist`
  ADD PRIMARY KEY (`iditemlist`),
  ADD KEY `idx_list_user` (`user_iduser`);

--
-- Indexes for table `item_price_data`
--
ALTER TABLE `item_price_data`
  ADD PRIMARY KEY (`item_eve_iditem`),
  ADD KEY `item_eve_iditem` (`item_eve_iditem`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`idlog`),
  ADD KEY `username` (`user_iduser`);

--
-- Indexes for table `net_history`
--
ALTER TABLE `net_history`
  ADD PRIMARY KEY (`characters_eve_idcharacters`,`date`),
  ADD UNIQUE KEY `unique_history` (`characters_eve_idcharacters`,`date`),
  ADD KEY `index_wallet` (`total_wallet`),
  ADD KEY `index_assets` (`total_assets`),
  ADD KEY `index_sell` (`total_sell`),
  ADD KEY `index_escrow` (`total_escrow`),
  ADD KEY `index_date` (`date`);

--
-- Indexes for table `new_info`
--
ALTER TABLE `new_info`
  ADD PRIMARY KEY (`characters_eve_idcharacters`),
  ADD UNIQUE KEY `characters_eve_idcharacters` (`characters_eve_idcharacters`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`idorders`),
  ADD UNIQUE KEY `index_orders` (`transkey`),
  ADD KEY `index_station` (`station_eve_idstation`),
  ADD KEY `index_characters` (`characters_eve_idcharacters`),
  ADD KEY `index_item` (`eve_item_iditem`) USING BTREE;

--
-- Indexes for table `order_status`
--
ALTER TABLE `order_status`
  ADD PRIMARY KEY (`orders_transkey`);

--
-- Indexes for table `profit`
--
ALTER TABLE `profit`
  ADD PRIMARY KEY (`idprofit`),
  ADD UNIQUE KEY `unique_profit` (`transaction_idbuy_sell`,`transaction_idbuy_buy`),
  ADD KEY `index_char_IN` (`characters_eve_idcharacters_IN`),
  ADD KEY `index_char_OUT` (`characters_eve_idcharacters_OUT`),
  ADD KEY `index_idbuy_buy` (`transaction_idbuy_buy`);

--
-- Indexes for table `region`
--
ALTER TABLE `region`
  ADD PRIMARY KEY (`eve_idregion`),
  ADD UNIQUE KEY `name_UNIQUE` (`name`);

--
-- Indexes for table `ship_volumes`
--
ALTER TABLE `ship_volumes`
  ADD PRIMARY KEY (`marketGroupID`);

--
-- Indexes for table `standings_corporation`
--
ALTER TABLE `standings_corporation`
  ADD PRIMARY KEY (`idstandings_corporation`),
  ADD UNIQUE KEY `unique_corp` (`characters_eve_idcharacters`,`corporation_eve_idcorporation`),
  ADD KEY `characters_eve_idcharacters` (`characters_eve_idcharacters`),
  ADD KEY `index_corporation` (`corporation_eve_idcorporation`);

--
-- Indexes for table `standings_faction`
--
ALTER TABLE `standings_faction`
  ADD PRIMARY KEY (`idstandings_faction`),
  ADD UNIQUE KEY `unique_faction` (`characters_eve_idcharacters`,`faction_eve_idfaction`),
  ADD KEY `characters_eve_idcharacters` (`characters_eve_idcharacters`),
  ADD KEY `index_faction` (`faction_eve_idfaction`);

--
-- Indexes for table `station`
--
ALTER TABLE `station`
  ADD PRIMARY KEY (`eve_idstation`),
  ADD UNIQUE KEY `name_UNIQUE` (`name`),
  ADD KEY `fk_station_system1_idx` (`system_eve_idsystem`),
  ADD KEY `station_index` (`name`),
  ADD KEY `fk_corp` (`corporation_eve_idcorporation`);

--
-- Indexes for table `system`
--
ALTER TABLE `system`
  ADD PRIMARY KEY (`eve_idsystem`),
  ADD UNIQUE KEY `name_UNIQUE` (`name`),
  ADD KEY `fk_system_region1_idx` (`region_eve_idregion`),
  ADD KEY `index_name` (`name`(1));

--
-- Indexes for table `traderoutes`
--
ALTER TABLE `traderoutes`
  ADD PRIMARY KEY (`idtraderoute`),
  ADD KEY `stationFrom` (`station_eve_idstation_from`),
  ADD KEY `stationTo` (`station_eve_idstation_to`),
  ADD KEY `iduser` (`user_iduser`),
  ADD KEY `starting_character` (`starting_character`),
  ADD KEY `destination_character` (`destination_character`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`idbuy`),
  ADD UNIQUE KEY `unique_transaction` (`transkey`,`transaction_type`),
  ADD KEY `fk_transaction_item1_idx` (`item_eve_iditem`),
  ADD KEY `fk_transaction_station1_idx` (`station_eve_idstation`),
  ADD KEY `fk_transaction_character1_idx` (`character_eve_idcharacter`),
  ADD KEY `idx_transaction_lookup` (`transaction_type`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`iduser`),
  ADD UNIQUE KEY `username_UNIQUE` (`username`),
  ADD UNIQUE KEY `email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aggr`
--
ALTER TABLE `aggr`
  MODIFY `idaggr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `idassets` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `changelog`
--
ALTER TABLE `changelog`
  MODIFY `idchangelog` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `citadel_tax`
--
ALTER TABLE `citadel_tax`
  MODIFY `idcitadel_tax` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `corporation`
--
ALTER TABLE `corporation`
  MODIFY `eve_idcorporation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `faction`
--
ALTER TABLE `faction`
  MODIFY `eve_idfaction` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `history`
--
ALTER TABLE `history`
  MODIFY `idhistory` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `itemcontents`
--
ALTER TABLE `itemcontents`
  MODIFY `iditemcontents` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `itemlist`
--
ALTER TABLE `itemlist`
  MODIFY `iditemlist` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `log`
--
ALTER TABLE `log`
  MODIFY `idlog` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `idorders` bigint(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `profit`
--
ALTER TABLE `profit`
  MODIFY `idprofit` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `standings_corporation`
--
ALTER TABLE `standings_corporation`
  MODIFY `idstandings_corporation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `standings_faction`
--
ALTER TABLE `standings_faction`
  MODIFY `idstandings_faction` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `traderoutes`
--
ALTER TABLE `traderoutes`
  MODIFY `idtraderoute` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `idbuy` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `iduser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- Constraints for dumped tables
--

--
--
ALTER TABLE `aggr`
  ADD CONSTRAINT `fk_aggr_character1` FOREIGN KEY (`character_eve_idcharacter`) REFERENCES `characters` (`eve_idcharacter`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_aggr_user1` FOREIGN KEY (`user_iduser`) REFERENCES `user` (`iduser`);

--
--
ALTER TABLE `assets`
  ADD CONSTRAINT `fk_assets_character` FOREIGN KEY (`characters_eve_idcharacters`) REFERENCES `characters` (`eve_idcharacter`) ON DELETE CASCADE ON UPDATE CASCADE;

--
--
ALTER TABLE `characters`
  ADD CONSTRAINT `fk_character_api1` FOREIGN KEY (`api_apikey`) REFERENCES `api` (`apikey`) ON UPDATE CASCADE;

--
--
ALTER TABLE `citadel_tax`
  ADD CONSTRAINT `fk_citadel_tax` FOREIGN KEY (`character_eve_idcharacter`) REFERENCES `characters` (`eve_idcharacter`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_citadel_tax_station` FOREIGN KEY (`station_eve_idstation`) REFERENCES `station` (`eve_idstation`) ON DELETE CASCADE ON UPDATE CASCADE;

--
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `fk_contracts_character` FOREIGN KEY (`characters_eve_idcharacters`) REFERENCES `characters` (`eve_idcharacter`) ON DELETE CASCADE ON UPDATE CASCADE;

--
--
ALTER TABLE `corporation`
  ADD CONSTRAINT `fk_corp_faction` FOREIGN KEY (`faction_eve_idfaction`) REFERENCES `faction` (`eve_idfaction`);

--
--
ALTER TABLE `history`
  ADD CONSTRAINT `fk_history_characters` FOREIGN KEY (`characters_eve_idcharacters`) REFERENCES `characters` (`eve_idcharacter`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_history_date` FOREIGN KEY (`date`) REFERENCES `calendar` (`days`);

--
--
ALTER TABLE `itemcontents`
  ADD CONSTRAINT `fk_content_list` FOREIGN KEY (`itemlist_iditemlist`) REFERENCES `itemlist` (`iditemlist`) ON DELETE CASCADE ON UPDATE CASCADE;

--
--
ALTER TABLE `itemlist`
  ADD CONSTRAINT `fk_list_user` FOREIGN KEY (`user_iduser`) REFERENCES `user` (`iduser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
--
ALTER TABLE `log`
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`user_iduser`) REFERENCES `user` (`iduser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
--
ALTER TABLE `net_history`
  ADD CONSTRAINT `fk_net_calendar` FOREIGN KEY (`date`) REFERENCES `calendar` (`days`),
  ADD CONSTRAINT `fk_net_character` FOREIGN KEY (`characters_eve_idcharacters`) REFERENCES `characters` (`eve_idcharacter`) ON DELETE CASCADE ON UPDATE CASCADE;

--
--
ALTER TABLE `new_info`
  ADD CONSTRAINT `fk_new_info_character_id` FOREIGN KEY (`characters_eve_idcharacters`) REFERENCES `characters` (`eve_idcharacter`) ON DELETE CASCADE ON UPDATE CASCADE;

--
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_order_char` FOREIGN KEY (`characters_eve_idcharacters`) REFERENCES `characters` (`eve_idcharacter`) ON DELETE CASCADE ON UPDATE CASCADE;

--
--
ALTER TABLE `order_status`
  ADD CONSTRAINT `fk_status_order` FOREIGN KEY (`orders_transkey`) REFERENCES `orders` (`transkey`) ON DELETE CASCADE ON UPDATE CASCADE;

--
--
ALTER TABLE `profit`
  ADD CONSTRAINT `fk_profit_buy` FOREIGN KEY (`transaction_idbuy_buy`) REFERENCES `transaction` (`idbuy`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_profit_charin` FOREIGN KEY (`characters_eve_idcharacters_IN`) REFERENCES `characters` (`eve_idcharacter`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_profit_charout` FOREIGN KEY (`characters_eve_idcharacters_OUT`) REFERENCES `characters` (`eve_idcharacter`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_profit_sell` FOREIGN KEY (`transaction_idbuy_sell`) REFERENCES `transaction` (`idbuy`) ON DELETE CASCADE ON UPDATE CASCADE;

--
--
ALTER TABLE `standings_corporation`
  ADD CONSTRAINT `standings_corp_corp` FOREIGN KEY (`corporation_eve_idcorporation`) REFERENCES `corporation` (`eve_idcorporation`),
  ADD CONSTRAINT `standings_corporation_ibfk_1` FOREIGN KEY (`characters_eve_idcharacters`) REFERENCES `characters` (`eve_idcharacter`) ON DELETE CASCADE ON UPDATE CASCADE;

--
--
ALTER TABLE `standings_faction`
  ADD CONSTRAINT `fk_standinds_faction_faction` FOREIGN KEY (`faction_eve_idfaction`) REFERENCES `faction` (`eve_idfaction`),
  ADD CONSTRAINT `fk_standings_faction_ib_1` FOREIGN KEY (`characters_eve_idcharacters`) REFERENCES `characters` (`eve_idcharacter`) ON DELETE CASCADE ON UPDATE CASCADE;

--
--
ALTER TABLE `station`
  ADD CONSTRAINT `fk_station_corp` FOREIGN KEY (`corporation_eve_idcorporation`) REFERENCES `corporation` (`eve_idcorporation`),
  ADD CONSTRAINT `fk_station_system1` FOREIGN KEY (`system_eve_idsystem`) REFERENCES `system` (`eve_idsystem`);

--
--
ALTER TABLE `system`
  ADD CONSTRAINT `fk_system_region1` FOREIGN KEY (`region_eve_idregion`) REFERENCES `region` (`eve_idregion`);

--
--
ALTER TABLE `traderoutes`
  ADD CONSTRAINT `fk_character_to` FOREIGN KEY (`destination_character`) REFERENCES `characters` (`eve_idcharacter`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_station_from` FOREIGN KEY (`station_eve_idstation_from`) REFERENCES `station` (`eve_idstation`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_station_to` FOREIGN KEY (`station_eve_idstation_to`) REFERENCES `station` (`eve_idstation`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_iduser`) REFERENCES `user` (`iduser`) ON DELETE CASCADE ON UPDATE CASCADE;

--
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `fk_transaction_character1` FOREIGN KEY (`character_eve_idcharacter`) REFERENCES `characters` (`eve_idcharacter`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
