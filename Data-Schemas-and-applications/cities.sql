-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 11, 2022 at 02:26 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Cities`
--

-- --------------------------------------------------------

--
-- Table structure for table `Category`
--

CREATE TABLE `Category` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Category`
--

INSERT INTO `Category` (`id`, `name`) VALUES
(1, 'Museums'),
(2, 'Outdoors'),
(3, 'Art and Culture'),
(4, 'History'),
(5, 'Markets');

-- --------------------------------------------------------

--
-- Table structure for table `City`
--

CREATE TABLE `City` (
  `id` int(11) NOT NULL,
  `name` varchar(86) NOT NULL,
  `country` varchar(2) NOT NULL,
  `state` varchar(45) DEFAULT NULL,
  `area` decimal(9,4) DEFAULT NULL,
  `population` int(11) NOT NULL,
  `latitude` varchar(12) NOT NULL,
  `longitude` varchar(12) NOT NULL,
  `wikiLink` varchar(130) DEFAULT NULL,
  `currency` char(3) NOT NULL,
  `description` varchar(2000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `City`
--

INSERT INTO `City` (`id`, `name`, `country`, `state`, `area`, `population`, `latitude`, `longitude`, `wikiLink`, `currency`, `description`) VALUES
(1, 'London', 'GB', 'London', '1572.0000', 8961989, '51.50750880', '-0.13393442', 'https://en.wikipedia.org/wiki/London', 'GBP', 'London is the capital and largest city of England and the United Kingdom. It stands on the River Thames in south-east England at the head of a 50-mile (80 km) estuary down to the North Sea, and has been a major settlement for two millennia. The City of London, its ancient core and financial centre, was founded by the Romans as Londinium and retains boundaries close to its medieval ones. Since the 19th century, \"London\" has also referred to the metropolis around this core, historically split between the counties of Middlesex, Essex, Surrey, Kent, and Hertfordshire, which largely makes up Greater London, the region governed by the Greater London Authority. The City of Westminster, to the west of the City, has for centuries held the national government and parliament.'),
(2, 'New York City', 'US', 'New York', '1223.5900', 8804190, '40.762661921', '-74.0012581', 'https://en.wikipedia.org/wiki/New_York_City', 'USD', 'New York, often called New York City to distinguish it from New York State, or NYC for short, is the most populous city in the United States. With a 2020 population of 8,804,190 distributed over 300.46 square miles (778.2 km2), New York City is also the most densely populated major city in the United States. Located at the southern tip of the State of New York, the city is the center of the New York metropolitan area, the largest metropolitan area in the world by urban area. With over 20 million people in its metropolitan statistical area and 23,582,649 in its combined statistical area as of 2020, New York is one of the world\'s most populous megacities. New York City has been described as the cultural, financial, and media capital of the world, significantly influencing commerce, entertainment, research, technology, education, politics, tourism, dining, art, fashion, and sports, and is the most photographed city in the world. Home to the headquarters of the United Nations, New York is an important center for international diplomacy, and has sometimes been called the capital of the world.');

-- --------------------------------------------------------

--
-- Table structure for table `Comment`
--

CREATE TABLE `Comment` (
  `id` int(11) NOT NULL,
  `comment` varchar(280) NOT NULL,
  `username` varchar(45) DEFAULT NULL,
  `dateCreated` varchar(45) NOT NULL,
  `idCity` int(11) DEFAULT NULL,
  `idPOI` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `Flickr`
--

CREATE TABLE `Flickr` (
  `id` varchar(16) NOT NULL,
  `filename` varchar(256) NOT NULL,
  `title` varchar(256) DEFAULT NULL,
  `dateCreated` int(11) NOT NULL,
  `idPOI` int(11) DEFAULT NULL,
  `idCity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `Photo`
--

CREATE TABLE `Photo` (
  `id` int(11) NOT NULL,
  `filename` varchar(256) NOT NULL,
  `username` varchar(45) DEFAULT NULL,
  `dateCreated` int(11) NOT NULL,
  `idCity` int(11) DEFAULT NULL,
  `idPOI` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `POI`
--

CREATE TABLE `POI` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `description` varchar(2000) DEFAULT NULL,
  `photo` varchar(256) DEFAULT NULL,
  `latitude` varchar(12) NOT NULL,
  `longitude` varchar(12) NOT NULL,
  `wikiLink` varchar(130) DEFAULT NULL,
  `openings` varchar(128) DEFAULT NULL,
  `idCity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `POI`
--

INSERT INTO `POI` (`id`, `name`, `description`, `photo`, `latitude`, `longitude`, `wikiLink`, `openings`, `idCity`) VALUES
(1, 'Buckingham Palace', 'Buckingham Palace is the London residence and administrative headquarters of the monarchy of the United Kingdom. Located in the City of Westminster, the palace is often at the centre of state occasions and royal hospitality. It has been a focal point for the British people at times of national rejoicing and mourning. Originally known as Buckingham House, the building at the core of today\'s palace was a large townhouse built for the Duke of Buckingham in 1703 on a site that had been in private ownership for at least 150 years.', NULL, '51.501364', '-0.144084', 'https://en.wikipedia.org/wiki/Buckingham_Palace', 'July-August: 09:30-19:30', 1),
(2, 'Natural History Museum', 'The Natural History Museum in London is a natural history museum that exhibits a vast range of specimens from various segments of natural history. It is one of three major museums on Exhibition Road in South Kensington, the others being the Science Museum and the Victoria and Albert Museum. The Natural History Museum\'s main frontage, however, is on Cromwell Road.', NULL, '51.496111', '-0.176389', 'https://en.wikipedia.org/wiki/Natural_History_Museum,_London', 'Monday-Sunday: 10:00-17:50', 1),
(3, 'Borough Market', 'Borough Market is a wholesale and retail market hall in Southwark, London, England. It is one of the largest and oldest food markets in London, with a market on the site dating back to at least the 12th century. The present buildings were built in the 1850s, and today the market mainly sells specialty foods to the general public.', NULL, '51.504908', '-0.091089', 'https://en.wikipedia.org/wiki/Borough_Market', 'Mon-Thurs: 10:00-17:00, Fri: 10:00-18:00, Sat: 08:00-17:00, Sun: 10:00-16:00', 1),
(4, 'The British Museum', 'The British Museum is a public institution dedicated to human history, art and culture located in the Bloomsbury area of London, England. Its permanent collection of some eight million works is among the largest and most comprehensive in existence, having been widely collected during the era of the British Empire. It documents the story of human culture from its beginnings to the present. It was the first public national museum in the world. The Museum was established in 1753, largely based on the collections of the Anglo-Irish physician and scientist Sir Hans Sloane. It first opened to the public in 1759, in Montagu House, on the site of the current building. Its expansion over the following 250 years was largely a result of expanding British colonisation and has resulted in the creation of several branch institutions, the first being the Natural History Museum in 1881.', NULL, '51.519489', '-0.127019', 'https://en.wikipedia.org/wiki/British_Museum', 'Mon-Sun: 10:00-17:00', 1),
(5, 'Hyde Park', 'Hyde Park is a Grade I-listed major park in Central London. It is the largest of four Royal Parks that form a chain from the entrance of Kensington Palace through Kensington Gardens and Hyde Park, via Hyde Park Corner and Green Park past the main entrance to Buckingham Palace. The park is divided by the Serpentine and the Long Water lakes.\r\n\r\nThe park was established by Henry VIII in 1536 when he took the land from Westminster Abbey and used it as a hunting ground. It opened to the public in 1637 and quickly became popular, particularly for May Day parades. Major improvements occurred in the early 18th century under the direction of Queen Caroline. Several duels took place in Hyde Park during this time, often involving members of the nobility. The Great Exhibition of 1851 was held in the park, for which The Crystal Palace, designed by Joseph Paxton, was erected.', NULL, '51.508611', '-0.163611', 'https://en.wikipedia.org/wiki/Hyde_Park', 'Mon-Sun: 5:00-00:00', 1),
(6, 'Big Ben', 'Big Ben is the nickname for the Great Bell of the striking clock at the north end of the Palace of Westminster, although the name is frequently extended to refer also to the clock and the clock tower. The official name of the tower in which Big Ben is located was originally the Clock Tower, but it was renamed Elizabeth Tower in 2012, to mark the Diamond Jubilee of Elizabeth II.\r\n\r\nThe tower was designed by Augustus Pugin in a neo-Gothic style. When completed in 1859, its clock was the largest and most accurate four-faced striking and chiming clock in the world. The tower stands 316 feet (96 m) tall, and the climb from ground level to the belfry is 334 steps. Its base is square, measuring 40 feet (12 m) on each side. Dials of the clock are 22.5 feet (6.9 m) in diameter. All four nations of the UK are represented on the tower on shields featuring a rose for England, thistle for Scotland, shamrock for Northern Ireland, and leek for Wales. On 31 May 2009, celebrations were held to mark the tower\'s 150th anniversary.', NULL, '51.500711', '-0.124511', 'https://en.wikipedia.org/wiki/Big_Ben', 'Mon and Fri: 9:15-16:30, Tues-Thurs: 13:15-16:30', 1),
(7, 'The Albert Memorial', 'The Albert Memorial, directly north of the Royal Albert Hall in Kensington Gardens, London, was commissioned by Queen Victoria in memory of her beloved husband Prince Albert, who died in 1861. Designed by Sir George Gilbert Scott in the Gothic Revival style, it takes the form of an ornate canopy or pavilion 176 feet (54 m) tall, in the style of a Gothic ciborium over the high altar of a church, sheltering a statue of the prince facing south. It took over ten years to complete, the £120,000 cost (the equivalent of about £10,000,000 in 2010) met by public subscription.\r\n\r\nThe memorial was opened in July 1872 by Queen Victoria, with the statue of Albert ceremonially \"seated\" in 1876. It has been Grade I listed since 1970.', NULL, '51.5025', '-0.177778', 'https://en.wikipedia.org/wiki/Albert_Memorial', 'Mon-Sun: 06:00-21:00', 1),
(8, 'Millennium Bridge', 'The Millennium Bridge, officially known as the London Millennium Footbridge, is a steel suspension bridge for pedestrians crossing the River Thames in London, linking Bankside with the City of London. It is owned and maintained by Bridge House Estates, a charitable trust overseen by the City of London Corporation. Construction began in 1998, and it initially opened on 10 June 2000.\r\n\r\nLondoners nicknamed it the \"Wobbly Bridge\" after pedestrians experienced an alarming swaying motion on its opening day. The bridge was closed later that day and, after two days of limited access, it was closed again for almost two years so that modifications and repairs could be made to keep the bridge stable and stop the swaying motion. It reopened in February 2002.', NULL, '51.50962443', '-0.09859135', 'https://en.wikipedia.org/wiki/Millennium_Bridge,_London', '24 hours', 1),
(9, 'Museum of Modern Art', 'The Museum of Modern Art (MoMA) is an art museum located in Midtown Manhattan, New York City, on 53rd Street between Fifth and Sixth Avenues.\r\n\r\nIt plays a major role in developing and collecting modern art, and is often identified as one of the largest and most influential museums of modern art in the world. MoMA\'s collection offers an overview of modern and contemporary art, including works of architecture and design, drawing, painting, sculpture, photography, prints, illustrated books and artist\'s books, film, and electronic media.\r\n\r\nThe MoMA Library includes approximately 300,000 books and exhibition catalogs, more than 1,000 periodical titles, and more than 40,000 files of ephemera about individual artists and groups. The archives hold primary source material related to the history of modern and contemporary art.\r\n\r\nIt attracted 706,060 visitors in 2020, a drop of sixty-five percent from 2019, due to the COVID-19 pandemic. It ranked twenty-fifth on the list of most visited art museums in the world in 2020.', NULL, '40.761611', '-73.977611', 'https://en.wikipedia.org/wiki/Museum_of_Modern_Art', 'Sun-Mon: 10:30-17:30, Sat: 10:30-19:00', 2),
(10, 'Central Park', 'Central Park is an urban park in New York City located between the Upper West and Upper East Sides of Manhattan. It is the fifth-largest park in the city by area, covering 843 acres (341 ha). It is the most visited urban park in the United States, with an estimated 42 million visitors annually as of 2016, and is the most filmed location in the world.\r\n\r\nFollowing proposals for a large park in Manhattan during the 1840s, it was approved in 1853 to cover 778 acres (315 ha). In 1857, landscape architects Frederick Law Olmsted and Calvert Vaux won a design competition for the park with their \"Greensward Plan\". Construction began the same year; existing structures, including a majority-Black settlement named Seneca Village, were seized through eminent domain and razed. The park\'s first areas were opened to the public in late 1858. Additional land at the northern end of Central Park was purchased in 1859, and the park was completed in 1876. After a period of decline in the early 20th century, New York City parks commissioner Robert Moses started a program to clean up Central Park in the 1930s. The Central Park Conservancy, created in 1980 to combat further deterioration in the late 20th century, refurbished many parts of the park starting in the 1980s.', NULL, '40.782222', '-73.965278', 'https://en.wikipedia.org/wiki/Central_Park', 'Mon-Sun: 06:00-01:00', 2),
(11, 'Statue of Liberty National Monument', 'The Statue of Liberty (Liberty Enlightening the World; French: La Liberté éclairant le monde) is a colossal neoclassical sculpture on Liberty Island in New York Harbor in New York City, in the United States. The copper statue, a gift from the people of France to the people of the United States, was designed by French sculptor Frédéric Auguste Bartholdi and its metal framework was built by Gustave Eiffel. The statue was dedicated on October 28, 1886.\r\n\r\nThe statue is a figure of Libertas, a robed Roman liberty goddess. She holds a torch above her head with her right hand, and in her left hand carries a tabula ansata inscribed JULY IV MDCCLXXVI (July 4, 1776 in Roman numerals), the date of the U.S. Declaration of Independence. A broken shackle and chain lie at her feet as she walks forward, commemorating the recent national abolition of slavery. After its dedication, the statue became an icon of freedom and of the United States, seen as a symbol of welcome to immigrants arriving by sea.', NULL, '40.689167', '-74.044444', 'https://en.wikipedia.org/wiki/Statue_of_Liberty', 'Mon-Sun: 08:30-16:00', 2),
(12, 'Museum of Natural History', 'The American Museum of Natural History (abbreviated as AMNH) is a natural history museum on the Upper West Side of Manhattan, New York City. In Theodore Roosevelt Park, across the street from Central Park, the museum complex comprises 26 interconnected buildings housing 45 permanent exhibition halls, in addition to a planetarium and a library. The museum collections contain over 34 million specimens of plants, animals, fossils, minerals, rocks, meteorites, human remains, and human cultural artifacts, as well as specialized collections for frozen tissue and genomic and astrophysical data, of which only a small fraction can be displayed at any given time. The museum occupies more than 2 million square feet (190,000 m2). AMNH has a full-time scientific staff of 225, sponsors over 120 special field expeditions each year, and averages about five million visits annually.', NULL, '40.780556', '-73.974722', 'https://en.wikipedia.org/wiki/American_Museum_of_Natural_History', 'Wed-Sun: 10:00-17:30', 2),
(13, 'Chelsea Market', 'Chelsea Market is a food hall, shopping mall, office building and television production facility located in the Chelsea neighborhood of the borough of Manhattan, in New York City. The Chelsea Market complex occupies an entire city block with a connecting bridge over Tenth Avenue to the adjacent 85 Tenth Avenue building. The High Line passes through the 10th Avenue side of the building.\r\n\r\nChelsea Market was constructed in the 1890s and was originally the site of the National Biscuit Company (Nabisco) factory complex where the Oreo cookie was invented and produced. The complex was redeveloped in the 1990s and features a retail concourse at ground level with office space above. Chelsea Market is currently owned by Alphabet Inc., parent company of Google. Chelsea Market lies within the \"Gansevoort Market Historic District\", which is recognized by New York State and National Register of Historic Places.', NULL, '40.7425', '-74.006111', 'https://en.wikipedia.org/wiki/Chelsea_Market', 'Mon-Sat: 07:00-02:00, Sun: 8am–10pm', 2),
(14, 'East River Park', 'East River Park, also called John V. Lindsay East River Park, is 57.5-acre (20 ha) public park located on the Lower East Side of Manhattan, part of the New York City Department of Parks and Recreation. Bisected by the Williamsburg Bridge, it stretches along the East River from Montgomery Street up to 12th Street on the east side of the FDR Drive. Its amphitheater, built in 1941 just south of Grand Street, has been reconstructed and is often used for public performances. The park includes football, baseball, and soccer fields; tennis, basketball, and handball courts; a running track; and bike paths, including the East River Greenway. Fishing is another popular activity.', NULL, '40.7191747', '-73.9737913', 'https://en.wikipedia.org/wiki/East_River_Park', 'Mon-Sun: 06:00-01:00', 2),
(15, 'Empire State Building', 'The Empire State Building is a 102-story Art Deco skyscraper in Midtown Manhattan in New York City, United States. It was designed by Shreve, Lamb & Harmon and built from 1930 to 1931. Its name is derived from \"Empire State\", the nickname of the state of New York. The building has a roof height of 1,250 feet (380 m) and stands a total of 1,454 feet (443.2 m) tall, including its antenna. The Empire State Building stood as the world\'s tallest building until the construction of the World Trade Center in 1970; following the latter\'s collapse in 2001, the Empire State Building was again the city\'s tallest skyscraper until 2012. As of 2020, the building is the seventh-tallest building in New York City, the ninth-tallest completed skyscraper in the United States, the 49th-tallest in the world, and the sixth-tallest freestanding structure in the Americas.', NULL, '40.7485748', '-73.9856689', 'https://en.wikipedia.org/wiki/Empire_State_Building', 'Mon-Sun: 10:00-22:00', 2),
(16, 'Times Square', 'Times Square is a major commercial intersection, tourist destination, entertainment center, and neighborhood in the Midtown Manhattan section of New York City, at the junction of Broadway and Seventh Avenue. Brightly lit by numerous billboards and advertisements, it stretches from West 42nd to West 47th Streets, and is sometimes referred to as \"the Crossroads of the World\", \"the Center of the Universe\", \"the heart of the Great White Way\", and \"the heart of the world\". One of the world\'s busiest pedestrian areas, it is also the hub of the Broadway Theater District and a major center of the world\'s entertainment industry. Times Square is one of the world\'s most visited tourist attractions, drawing an estimated 50 million visitors annually. Approximately 330,000 people pass through Times Square daily, many of them tourists, while over 460,000 pedestrians walk through Times Square on its busiest days.', NULL, '40.757', '-73.986', 'https://en.wikipedia.org/wiki/Times_Square', '24 hour', 2),
(17, 'National September 11 Memorial', 'The National September 11 Memorial & Museum (also known as the 9/11 Memorial & Museum) is a memorial and museum in New York City commemorating the September 11, 2001 attacks, which killed 2,977 people, and the 1993 World Trade Center bombing, which killed six. The memorial is located at the World Trade Center site, the former location of the Twin Towers that were destroyed during the September 11 attacks. It is operated by a non-profit institution whose mission is to raise funds for, program, and operate the memorial and museum at the World Trade Center site.\r\n\r\nA memorial was planned in the immediate aftermath of the attacks and destruction of the World Trade Center for the victims and those involved in rescue and recovery operations. The winner of the World Trade Center Site Memorial Competition was Israeli-American architect Michael Arad of Handel Architects, a New York- and San Francisco-based firm. Arad worked with landscape-architecture firm Peter Walker and Partners on the design, creating a forest of swamp white oak trees with two square reflecting pools in the center marking where the Twin Towers stood. In August 2006, the World Trade Center Memorial Foundation and the Port Authority of New York and New Jersey began heavy construction on the memorial and museum. The design is consistent with the original master plan by Daniel Libeskind, which called for the memorial to be 30 feet (9.1 m) below street level—originally 70 feet (21 m)—in a plaza, and was the only finalist to disregard Libeskind\'s requirement that the buildings overhang the footprints of the Twin Towers. The World Trade Center Memorial Foundation was renamed the National September 11 Memorial & Museum in 2007.', NULL, '40.711667', '-74.013611', 'https://en.wikipedia.org/wiki/National_September_11_Memorial_%26_Museum', 'Thurs-Mon: 10:00-17:00', 2),
(18, 'Grand Central Market', 'Grand Central Market offers a European-style gourmet shopping experience steps from your train or subway ride home. Conveniently located between the Graybar Building and the 4/5/6 Subway lines on Lexington Avenue, the Market features 13 local vendors offering ingredients and prepared foods ready for your next meal at home, picnic in the park, or gift for you host.', NULL, '40.752778', '-73.977222', 'https://en.wikipedia.org/wiki/Grand_Central_Terminal', 'Mon-Fri: 08:00-19:00, Sat-Sun: 11:00-17:00', 2),
(19, 'Soho', 'The energetic streets of Soho, in the West End, feature a variety of dining, nightlife, and shopping options. Dean, Frith, Beak, and Old Compton streets are the epicentre of activity day and night, and long-running Ronnie Scott\'s Jazz Club is also here. Theatre-goers head to Shaftesbury Avenue, while shoppers bustle around Carnaby, Oxford and Regent streets and the iconic Liberty\'s department store.', NULL, '51.5135459', '-0.1359169', 'https://en.wikipedia.org/wiki/Soho', '24 Hours', 1),
(20, 'The National Gallery', 'The National Gallery is an art museum in Trafalgar Square in the City of Westminster, in Central London. Founded in 1824, it houses a collection of over 2,300 paintings dating from the mid-13th century to 1900.\r\n\r\nThe Gallery is an exempt charity, and a non-departmental public body of the Department for Digital, Culture, Media and Sport. Its collection belongs to the government on behalf of the British public, and entry to the main collection is free of charge. In 2020, due to the COVID-19 pandemic it attracted only 1,197,143 visitors, a drop of 50 per cent from 2019, but it still ranked eighth on the list of most-visited art museums in the world.', NULL, '51.508929', '-0.1304877', 'https://en.wikipedia.org/wiki/National_Gallery', 'Sat-Thurs: 10:00-18:00\r\nFri: 10:00-21:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `POI_Category`
--

CREATE TABLE `POI_Category` (
  `idPOI` int(11) NOT NULL,
  `idCategory` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `POI_Category`
--

INSERT INTO `POI_Category` (`idPOI`, `idCategory`) VALUES
(1, 3),
(2, 1),
(2, 4),
(3, 2),
(3, 5),
(4, 1),
(4, 4),
(5, 2),
(6, 3),
(7, 2),
(7, 3),
(7, 4),
(8, 2),
(9, 1),
(9, 3),
(10, 2),
(11, 2),
(11, 3),
(12, 1),
(12, 4),
(13, 5),
(14, 2),
(15, 3),
(16, 2),
(16, 3),
(17, 2),
(17, 4),
(18, 5),
(19, 2),
(20, 1),
(20, 3);

-- --------------------------------------------------------

--
-- Table structure for table `Tweets`
--

CREATE TABLE `Tweets` (
  `id` varchar(21) NOT NULL,
  `tweet` varchar(280) DEFAULT NULL,
  `username` varchar(15) NOT NULL,
  `dateCreated` int(11) NOT NULL,
  `idCity` int(11) DEFAULT NULL,
  `idPOI` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `Tweets`
--

INSERT INTO `Tweets` (`id`, `tweet`, `username`, `dateCreated`, `idCity`, `idPOI`) VALUES
('1497199250123329536', 'Annie Smith OBE (1854-1937) Lichenologist. Elected to the Linnaean Soc. in 1904. Cofounder of the British Mycological Soc. She worked on fungi &amp; lichens at the British (now Natural History) Museum (1892-1933). Paid externally to get round the bar on women employees at the Mus', 'Womans_Place_UK', 1645795092, 1, 4),
('1497200430279008260', 'Getting to use a picture of a source that I took Myself? A picture that I took cos I saw something by chance when I was at the British Museum? Y E S', 'LingsTief', 1645795373, 1, 4),
('1497203537734156293', 'Did you know?\n\nBig Ben\'s clock stopped at 10:07 p.m. on 27 May 2005, most likely due to an extremely hot temperature of 31.8 degrees Celsius.\n\nSource:- GOOGLE', 'NerdBott', 1645796114, 1, 6),
('1497204606908211200', 'Cowboys need to sign Big Ben to push Dak. Imagine Big Ben with this weapons 😍😍😍', 'E_donche', 1645796369, 1, 6),
('1497205472037847063', 'We are big fans of #Wordle here at Salesforce Ben. Here\'s an unofficial extra @lucymazalon has created to supplement your daily fix...', 'SalesforceBen', 1645796575, 1, 6),
('1497223730031902750', 'Contracts Manager - City of London - THE BRITISH MUSEUM', 'GJ_Arts', 1645800928, 1, 4),
('1497225157806538765', 'London, home of big ben, the queen (😬) and donny tourette. Y\'all keen for another RC show soon?', 'regalcheer', 1645801269, 1, 6),
('1497225300438040598', '#DidYouKnow British illustrator Frank Hampson, best known as the creator and artist of Dan Dare, studied art right here in Southport!\n\nYou can learn all about Hampson, Dan Dare &amp;  Eagle Magazine in our Between Land &amp; Sea museum.\n\n💰 Free\n\nPlan your visit: https://t.co/nc2I', 'AtkinsonThe', 1645801303, 1, 4),
('1497226121506693126', 'Genuinely might have to buy some big time rush tickets', 'Ben__Currie', 1645801499, 1, 6),
('1497226246543003654', 'In today’s ⁦@stltoday⁩: Reed-Francois faces first big #Mizzou decision with Martin\'s future, and we\'ll learn a lot about her when it arrives', 'Ben_Fred', 1645801528, 1, 6),
('1497230850420736004', 'Big Ben is gone, why do you still want  Diontae Johnson shares?', 'PotPharma', 1645802626, 1, 6),
('1497232098620579855', 'A big birthday shoutout to sophomore attackman, #1 Ben Warren! \n\n#birthday #spartans #UDLACRO22E', 'dubuqueMlax', 1645802924, 1, 6),
('1497232206070272001', 'More pointless conjecture from the British Museum.', 'polblonde', 1645802949, 1, 4),
('1497233186664890374', 'UK big failure. \"UK rules out Ukraine no-fly zone as ‘declaring war on Russia.\'\"  What nonsense.  Defending Ukraine from bombing is not \"declaring war on Russia.\" If Russia persists, then it declares war on whoever\'s plane it attacks not vice versa. ', 'dinerlee', 1645803183, 1, 6),
('1497235718439112705', 'At Big Ben. Not that big I’d av him', 'AVFC_Aston', 1645803787, 1, 6),
('1497237023115071491', 'Steelers beat writers also lobbying for a younger, stronger, faster, more respectable, less rapey, more likable, more talented, more athletic, more gritty, more well hung, more Christian, more smart, safer motorcycle riding Big Ben?!?!?! LETS CRAPPIN GO!!!  #wentzwagon', 'HungerlordIII', 1645804098, 1, 6),
('1497237053972656136', 'How many times have she cried about Burna leaving her already', 'BIG_BEN____', 1645804105, 1, 6),
('1497237537458323465', 'Oh suddenly, Ben Sasse has found his voice again? Mr. “talk big and do nothing?” I’m so tired of him. He had MANY chances to stand up against Trump and he never did. He can sit down and be quiet right now. @cnn @BenSasse @jimsciutto #GOPTraitorsToDemocracy', 'LMplusG', 1645804220, 1, 6),
('1497237767868149769', 'Speaking from personal experience, this kid can make all of the throws. \n\nI’m with you @ArlenHarris33. That kid deserves a big time opportunity.', 'Ben_Morr', 1645804275, 1, 6),
('1497237885887655936', 'Big Ben, London', 'bestartpage', 1645804303, 1, 6),
('1497238198623289345', 'Take a moment and listen to Brother Ben.\n\nHis words may have a big impact on the quality of your day. 🫂', 'NoelDavila', 1645804378, 1, 6),
('1497238718570278912', 'ITF Week 2 winners crowned. Big congratulations to 2 time winner Given Roach and 14 year old Ben Gusic Wan.Well done to our two runners up Daniela Piani and Fabio Nestola who took part in two very exciting finals @LiverpoolTennis', 'LiverpoolTC', 1645804502, 1, 6),
('1497238763818270723', 'Can’t wait for takes like this in the future when we start downplaying Big Ben’s accomplishments', 'jaelan_7', 1645804513, 1, 6),
('1497560450619244548', 'How have I never gone via borough market en route to HQ 🤯🌹🌹', 'S7OCKY', 1645881209, 1, 3),
('1497564573339209729', 'For blades fans in borough market the 1414 1431 train down to @MillwallFC these two trains are only  running with a small number of carriages! You may struggle to get on and will not make kick off on the later ones 1355 1401 (to south Bermondsey) have more carriages #UTB', 'SUFC_Police', 1645882192, 1, 3),
('1497583945717673984', 'Rogue Trader Chain Themselves to Buckingham Palace To Protest! Leader of Opposition Resigns #RoyalNews #WorldNews', 'RandomNews65', 1645886811, 1, 1),
('1497587358421585925', 'Borough Market the arcade', 'posttruthcity', 1645887624, 1, 3),
('1497587402310766596', '🌿IRAFLORA Palace💐\n#omelites #obeyme #obeymehc \n\n[ a thread of what i think Irafloras castle would look like ] \n\n● we\'\'ve already seen in the thread of  the creator of this AU that Irafloras palace would look like the Buckingham. \n\nBut, what about the inside?', 'reveltica', 1645887635, 1, 1),
('1497589600788832257', 'i wonder if… buckingham palace is haunted…?', 'SHIRASAKA666', 1645888159, 1, 1),
('1497597425409687554', 'Borough Market the Council Office', 'posttruthcity', 1645890024, 1, 3),
('1497598404049920001', 'Waterworks of old England: the Borough Well in Bungay, with surviving Tudor brickwork, fed by a spring: the town’s main supply until 1923. From the 17thC a pump sent water through pipes, ultimately to an outlet in the Market Square…', 'highamnews', 1645890258, 1, 3),
('1497609328978714625', 'The absolute greatest pairing of host and musical guest of all time.', 'Big_Ben_M9', 1645892862, 1, 6),
('1497610768761724928', 'section 28 - the equivalent of the new floridian \"don\'\'t say gay\" bill in the uk - had a devastating impact on queer youth, including myself, for decades...\n\nnow felt like a good time to highlight the work of activists who fought to have it', 'HeyRowanEllis', 1645893206, 1, 6),
('1497610956314226691', 'They really gave the Nets Ben Simmons lol that’s not joking the best big three in the NBA', 'CAPTHABASEDGOD', 1645893250, 1, 6),
('1497632658012078081', 'Borough Market the playground', 'posttruthcity', 1645898424, 1, 3),
('1497642384062132227', 'The British Museum is hoping to elucidate the history of Stonehenge with an extensive exhibition that explores the society that built the famed stone circle, as well as their art, religion, and ways of life: https://t.co/M44XF9jhru', 'artnet', 1645900743, 1, 4),
('1497649581026426880', 'Just found the ticket for the British Museum exhibition in a book @WillBreckin Busy weekend meeting Ramin too. But what shows did we see and why was @EdBreckin there?🤔', 'bickypeg', 1645902459, 1, 4),
('1497667281081581569', 'Just posted a photo @ Borough Market', 'j_iona_lib', 1645906679, 1, 3),
('1497669862172213248', 'Giant sloth in the U of O Museum of Natural and Cultural History. #homeschoolfieldtrip', 'DinkumTribe', 1645907295, 1, 2),
('1497672589203890176', 'Always wanted to go to natural history museum. Today I did. I need to go back so much I didn\'\'t get chance to see though cos of time', 'EmmaPinky83', 1645907945, 1, 2),
('1497677226321620993', 'Manhattan Borough President: Seize Russian oligarchs’ NY assets now https://t.co/FdGB9zXsRL\n\nMany Russian oligarchs have for decades parked their money in the safe haven that is America’s luxury real estate market, but now that their country has invaded Ukraine, some are callin…', 'RKhodadadian', 1645909050, 1, 3),
('1497681866446364672', 'Back in 2014 he told the https://t.co/vpAcxe2gV9 that the ghost of a \"slain monk\" appears on the grounds of Buckingham Palace every year on Christmas Day — and that this may be the reason the royals \"prefer to spend the festive season at', 'MarkDRudningen', 1645910157, 1, 1),
('1497682979740692483', 'Day 1. We tried our best to stay awake today &amp; stay on the go. So… Buckingham Palace, the London eye, Westminster Abby, big &amp; little Ben all in about 19,000 steps. 🤪', 'koconnor23', 1645910422, 1, 1),
('1497685038845992961', 'PRE COLUMBIAN VASE WAS ON DISPLAY NATURAL HISTORY MUSEUM REPRO ?? NOT SURE https://t.co/tfndpcJXxN eBay', 'DecorativeCoUK', 1645910913, 1, 2),
('1497685497618972677', 'Buckingham Palace the department store', 'posttruthcity', 1645911022, 1, 1),
('1497686399881031685', 'Ben Markley Big Band - Cedar\'\'s Blues', 'wzumradio', 1645911238, 1, 6),
('1497686907974684672', 'Big ben wallace yes i boo Thee', 'kyserholcomb39', 1645911359, 1, 6),
('1497687042385494017', 'U know vibes r high when big Mike pulls up in the Og finner tarp', 'Ben_hockey88', 1645911391, 1, 6),
('1497688422193926145', 'Discover the World of Stonehenge ➡️ https://t.co/OGsbZlWoTy\n\nA brand new major exhibition at @britishmuseum brings the story of Stonehenge into sharper focus.\n\n#GreatWestWay', 'theGreatWestWay', 1645911720, 1, 4),
('1497690107742244866', 'About trip into London, this time to quick look around the British Museum\n#britishmuseum #london @ British Museum', 'ClaireLJG1987', 1645912122, 1, 4),
('1497691688302751748', 'Robert Graves on The British Museum:', 'noluckst2', 1645912498, 1, 4),
('1498396735428771845', 'You right, game to game he was better. the only thing you have for Big Ben is \"I remember him being really good on the last 1 or 2 drives of games\" while he too was surrounded by FHOFers in the backfield &amp; WR', 'BluntStatement', 1646080595, 1, 6),
('1498400582285070341', 'Tap into out digital sports show with @minasaywhat and @seanbelllive talking James Harden First Triple Double, New Sixers Big 3 &amp; Ben Simmons Update #ThePlaybook', 'rnbphilly', 1646081512, 1, 6),
('1498401361251254274', 'What a big day! Benedict in another conquest. I feel like he needed to receive all this love today, to hear from important people how amazing, talented and generous he is. We love you Ben! You deserve the world.', 'mcubatch', 1646081698, 1, 6),
('1501875387332235264', 'British Museum announces first ever exhibition on female spiritual beings through the ages  https://t.co/TSCtOOouh0 \n#exhibition #museums #museumlover #spiritual #museology', 'Museologists', 1646909970, 1, 4),
('1501876198800044032', 'Hear me out...\n\nWhite History Month.\n\n...except it\'\'s just a catalogue of all the ways Western Europe screwed the world over, and at the end we give the Koh-I-Noor diamond back and empty the British Museum.\n\n(Fully expect this to be QT\'\'d by bigots, but jokes on you, i\'\'m into th', 'TrudePerkins', 1646910163, 1, 4),
('1501878140263612422', 'JOSS STONE most impressive photos\nNr: 95872\n@JossStone during the launch of the QUEEN`s YOUNG LEADERS PROGRAMME at BUCKINGHAM PALACE , 2014', 'Nuerburgring1', 1646910626, 1, 1),
('1501878248539664384', 'JOSS STONE most impressive photos\nNr: 95872\n@JossStone during the launch of the QUEEN`s YOUNG LEADERS PROGRAMME at BUCKINGHAM PALACE , 2014', 'Nuerburgring1', 1646910652, 1, 1),
('1501879197974806528', 'Brian here.  Little Max and I are in Liverpool for Ben’s graduation ceremony. It felt right to put something on his Twitter. It’s so wrong and sad that you’re not here big guy. Doctor Ben Campbell. Miss you forever. #foreveryoung', 'BenMacCam', 1646910879, 1, 6),
('1501879571695734784', 'Band of the household cavalry leave Buckingham palace https://t.co/7iAeNEwu1N via @YouTube.  BE INSPIRED', 'ronaldkray4', 1646910968, 1, 1),
('1501882659651338241', 'Oh, dammit. This is where the hejnał mariacki usually plays on the hour, every hour. Every bit as iconic as, say, Big Ben\'\'s chimes. More so. One of the exile-beloved tapes of Polish folk music familiar from my youth started with it. https://t.co/g6y1jFpUA1', 'Girlinthe', 1646911704, 1, 6),
('1501884517115641859', 'Book of the Dead of Hunefer (Hw-nfr) \nThe scene (vignettes) shows episodes in Hunefer\'\'s judgement. In this papyrus \n\n19th dynasty …\nEgypt \n\nInformation from :British museum \n\n#history #egyptology #ancienthistory #ancientegypt #egypt #Archaeology', 'strollingintime', 1646912147, 1, 4),
('1502181385339027456', 'Since they’ve now been sanctioned, they should use the Millennium &amp; Copthorne Hotels @ Stamford Bridge &amp; the Oligarch homes in London. Would be a humanitarian move for Chelsea.', 'The_Paul_Winn', 1646982926, 1, 8),
('1502200259614703616', 'Borough Market the arcade', 'posttruthcity', 1646987426, 1, 3),
('1502212848486813696', 'Borough Market the zoo', 'posttruthcity', 1646990427, 1, 3),
('1502217709521620995', 'The Millennium bridge, London Wednesday 9th 2022', 'chriswitt1966', 1646991586, 1, 8),
('1502237906416844806', 'The British Red Cross Museum in London The British Red Cross Museum chronicles the organization’s history, beginning with its inception in 1834 and continuing to the present day....', 'theLondonLink1', 1646996401, 1, 4),
('1502240201283973120', 'To Track Magma’s Path to Eruption, Scientists Say There’s Something in the Water https://t.co/0mHDrQtUbj via @smithsonianmag', 'news12ctgwen', 1646996948, 1, 2),
('1502241987940032516', 'Residents and locals just confirmed to I and @r_petrucco that the Millennium Hotel has been closed including Merchandise shops and Gym centres\n\nThe impact of the UK Gov sanctions on the local residents here at Stamford Bridge is in epic proportions\n@SportsGazette https://t.co/321', 'MzMary_Cathryn', 1646997374, 1, 8),
('1502243446047875073', 'Cllr Kemi Akinola Wandsworth Borough Council: Save Tooting Market Evening Trade! - Sign the Petition! https://t.co/wkPFXOMPZt via @UKChange', 'sefrances', 1646997722, 1, 3),
('1502244950330724356', 'At the natural history museum\n\n#AiArt #CreativeAi #DigitalArt #ArtificialIntelligence @Frauenfelder', 'unltd_dream_co', 1646998081, 1, 2),
('1502246889458216960', 'My Museum of the Day: @BMMuseum Why not check out the world\'\'s largest collection of British Classic Motor Cars? @VisitStratford #motormuseum #warwickshire #ClassicCars #historicbritishcars #familyactivities #bmmuseum #MuseumFromHome #minicooper See you there?', 'thetravellocker', 1646998543, 1, 4),
('1502247095608168451', 'Seconded! Sound idea. Can we make it a reality? Buckingham Palace would be ideal for MPs to use instead of the poor bloody working class having to subside MPs second homes.', 'author_hughes', 1646998592, 1, 1),
('1502247332733231106', 'Hot off the press! 🎉 A new book by our very own, @MichaelMorpugo, will be published to commemorate the Queen\'\'s Platinum Jubilee on May 12. We can\'\'t wait to read it!🥰', 'farmersforaweek', 1646998649, 1, 1),
('1502247731850616834', '🏛️CfP #museumhistorian 🏛️ Smithsonian to give back \'\'its\'\' collection of Benin bronzes. British Museum under pressure for \'\'its\'\' Benin bronzes. And the latest issue of Roots-Routes aims to reflect on what ownership and restitution mean in museums', 'MariaChiaraScu', 1646998744, 1, 4),
('1502248634678665218', 'Catch sight of Mars at the @NHM_London to celebrate the 6th anniversary of the launch of ExoMars.  Only there 14th and 15th March before it heads off on tour https://t.co/7lkXWfO6DY', 'itsyourlondon', 1646998959, 1, 2),
('1502248805181403143', 'The copy-paste Buckingham Palace point is all well and good but the real story here is Boris doing NOTHING but say \"Want to help Ukrainian refugees? Do it yourselves.\" Which is not only lazy and half-hearted, but nigh-on impossible given our horrific borders restrictions', 'FranPayne', 1646999000, 1, 1),
('1502249214633463810', 'Looool no thanks. There\'\'s space in Buckingham Palace', 'SannahBannah', 1646999097, 1, 1),
('1502249447778050051', 'Any #singers/#choirs out there please join us on Sunday 13th in Hyde Park for this important show of unity with #ukraine. https://t.co/VDyJxvGJcm', 'donrowlands', 1646999153, 1, 5),
('1502249659510759427', 'Would you look at that, Buckingham Palace has now become temporary living for Ukrainian refugees!', 'kvdzii', 1646999203, 1, 1),
('1502249667265974278', 'Buckingham Palace will be an O2 venue in no time, watch out Liz', 'feetband', 1646999205, 1, 1),
('1502249887940947971', 'If people can open up their singular spare room to refugees then the @RoyalFamily can open up their numerous houses / palaces with hundreds of rooms to them also. Let\'\'s start with Buckingham Palace.', 'Aimz_1987', 1646999258, 1, 1),
('1502249923332481032', 'I heard Buckingham Palace is vacant', 'SincerelyTops', 1646999266, 1, 1),
('1502250409515225088', 'Look at the reaction of the left to the suggestion the British public open their homes to Ukrainian refugees. \n\nThey’ve gone all “Yeah, but, no, but”.. what about Buckingham Palace.\n\nBP should be used for rehabilitation of our veterans. \n\nThe left must open their own homes up..', 'sandieshoes', 1646999382, 1, 1),
('1502250454889160707', 'We’re not tweeting this for laughs, but Buckingham Palace would be perfect for homing refugees - nice, central location, lots of space, give the Royal family some great PR!', 'PeoplesSELondon', 1646999393, 1, 1),
('1502250708606885888', 'Oh no. Buckingham Palace is trending and here come all of the people who have no idea about the separation of Crown and Gov, who don\'\'t understand historic properties, and don\'\'t realize those rooms are bedrooms/offices. Not livable spaces for an independent family.', 'tinysapien', 1646999454, 1, 1),
('1502251033573175298', 'A  filthy bunch of scum. [I remember Aneta Safiak leering and crowing on the phone to my other half - when she was making blackmail threats wrt Fiona O\'\'Leary - about the fact that her carrying out of her blackmail threats was going to be a big help to Ben Gilroy.]', 'EamonnVIDF', 1646999531, 1, 6),
('1502251150053195779', 'Having a dander round the British Museum today, which always me of the time my mother and I visited it together for the first &amp; only time. After 10 min or so of persuing she got very angry &amp; declared: \'\'It\'\'s all just stuff that they stole from other places!\'\'', 'MurphGothic', 1646999559, 1, 4),
('1502251230571159557', 'Thanks for the update on the obvious traffic around Hyde Park Corner. Any update on trophies at Spurs yet you cu*t?', 'ucgmugs', 1646999578, 1, 5),
('1502251331674943489', 'Buckingham palace is sitting empty right now just saying', 'deffonottommac', 1646999602, 1, 1),
('1502251464676323330', 'They can stay at Buckingham palace, don’t they have hundreds of spare bedrooms there?', 'Erinma_2809', 1646999634, 1, 1),
('1502251479213781003', 'how full is buckingham palace? 😏', 'iseawhales', 1646999637, 1, 1),
('1502251909175984137', 'Nathan Collins expects big Burnley reaction after Chelsea mauling', 'Independent', 1646999740, 1, 6),
('1502251978058977283', '#30152 [CREATURES] Discovered in 1935, a skull at the Harvard Museum of Natural History is called a type specimen, as it helped paleontologists establish this type of dinosaur #trivia', 'triviastorm', 1646999756, 1, 2),
('1502252141246828547', 'Buckingham palace is empty.', 'jezebelsbabylon', 1646999795, 1, 1),
('1502252999350964224', 'FROM OUR BRITISH MUSIC MUSEUM 🎵🎵\n\nA hit song from the 2010s...\n\nDon\'\'t miss it!\n#RwOT #Aspire \n#BritishMusicMuseum  #BritishCulture #music #MUSICLOVER #English #LearnEnglish', 'CentreAspire', 1647000000, 1, 4),
('1502253038811164672', 'Murfreesboro Rescue One Firefighter Ben Yeargan was the big winner of the chicken wing eating contest at the Grand Reopening of the Zaxby’s at 905 Old Fort Parkway in Murfreesboro on March 5.', 'Mboro_Post', 1647000009, 1, 6),
('1502253064111239170', 'Fake News today..\nQueen opens up Buckingham Palace as a refuge for 500 Refugees in a show of solidarity 🇺🇦 \nThe Blairs are following her lead by opening all their vacant properties to help out..', 'Nowoolovermymi1', 1647000015, 1, 1),
('1502253097149730830', 'Congratulations to the 39 ex-South Bromsgrove students who have been invited to Buckingham Palace in May to collect their Gold Duke of Edinburgh Award. Very well done!', 'SouthBromsHigh', 1647000023, 1, 1),
('1502253394211360768', 'we\'\'ll start our day with another #FannyFriday example of an ancient Roman (maybe earlier) lamp which seems to be modelled on female genitalia. This one is in the British museum ...\n\nsource: https://t.co/2ZxsCLzept', 'rogueclassicist', 1647000094, 1, 4),
('1502253450809204738', 'Quadrangle Tower, Cambridge Square, Hyde Park, W2\n\nA beautifully refurbished two bedroom, two bathroom apartment on the 19th floor of a recently refurbished, well maintained gated purpose built apartment block. \n\nhttps://t.co/TldhFt89Ep', 'ManorsLondon', 1647000107, 1, 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Category`
--
ALTER TABLE `Category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `City`
--
ALTER TABLE `City`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Comment`
--
ALTER TABLE `Comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_city_id_fk_idx` (`idCity`),
  ADD KEY `comments_poi_id_fk_idx` (`idPOI`);

--
-- Indexes for table `Flickr`
--
ALTER TABLE `Flickr`
  ADD PRIMARY KEY (`id`),
  ADD KEY `flickr_poi_id_fk_idx` (`idPOI`),
  ADD KEY `flickr_city_id_fk_idx` (`idCity`);

--
-- Indexes for table `Photo`
--
ALTER TABLE `Photo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `photos_cities_id_fk_idx` (`idCity`),
  ADD KEY `photos_poi_id_fk_idx` (`idPOI`);

--
-- Indexes for table `POI`
--
ALTER TABLE `POI`
  ADD PRIMARY KEY (`id`),
  ADD KEY `poi_city_id_fk_idx` (`idCity`);

--
-- Indexes for table `POI_Category`
--
ALTER TABLE `POI_Category`
  ADD PRIMARY KEY (`idPOI`,`idCategory`),
  ADD KEY `category_id_fk_idx` (`idCategory`),
  ADD KEY `poi_id_fk_idx` (`idPOI`);

--
-- Indexes for table `Tweets`
--
ALTER TABLE `Tweets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tweets_poi_id_fk_idx` (`idPOI`),
  ADD KEY `tweets_city_id_fk_idx` (`idCity`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Category`
--
ALTER TABLE `Category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `City`
--
ALTER TABLE `City`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Comment`
--
ALTER TABLE `Comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Photo`
--
ALTER TABLE `Photo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `POI`
--
ALTER TABLE `POI`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Comment`
--
ALTER TABLE `Comment`
  ADD CONSTRAINT `comments_city_id_fk` FOREIGN KEY (`idCity`) REFERENCES `City` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_poi_id_fk` FOREIGN KEY (`idPOI`) REFERENCES `POI` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `Flickr`
--
ALTER TABLE `Flickr`
  ADD CONSTRAINT `flickr_city_id_fk` FOREIGN KEY (`idCity`) REFERENCES `City` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `flickr_poi_id_fk` FOREIGN KEY (`idPOI`) REFERENCES `POI` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Photo`
--
ALTER TABLE `Photo`
  ADD CONSTRAINT `photos_cities_fk_id` FOREIGN KEY (`idCity`) REFERENCES `City` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `photos_poi_fk_id` FOREIGN KEY (`idPOI`) REFERENCES `POI` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `POI`
--
ALTER TABLE `POI`
  ADD CONSTRAINT `poi_city_id_fk` FOREIGN KEY (`idCity`) REFERENCES `City` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `POI_Category`
--
ALTER TABLE `POI_Category`
  ADD CONSTRAINT `category_id_fk` FOREIGN KEY (`idCategory`) REFERENCES `Category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `poi_id_fk` FOREIGN KEY (`idPOI`) REFERENCES `POI` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `Tweets`
--
ALTER TABLE `Tweets`
  ADD CONSTRAINT `tweets_city_id_fk` FOREIGN KEY (`idCity`) REFERENCES `City` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `tweets_poi_id_fk` FOREIGN KEY (`idPOI`) REFERENCES `POI` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
