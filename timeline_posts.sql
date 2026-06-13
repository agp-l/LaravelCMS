-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Počítač: db.dw141.webglobe.com
-- Vytvořeno: Sob 13. čen 2026, 03:36
-- Verze serveru: 8.0.45-36
-- Verze PHP: 8.1.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `timelines`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `timeline_posts`
--

CREATE TABLE `timeline_posts` (
  `id` int NOT NULL,
  `created_at` datetime NOT NULL,
  `icon_class` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `map_url` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Vypisuji data pro tabulku `timeline_posts`
--

INSERT INTO `timeline_posts` (`id`, `created_at`, `icon_class`, `content`, `map_url`) VALUES
(30, '2025-02-04 02:28:00', 'zmdi zmdi-airplane', 'Letiště se noří do noci a já si užívám pohodlí v křesle s výhledem na letiště. Myslel jsem že budu na letišti panikařit, trpět na nepohodlné lavici, místo těchto obav si to užívám víc jak na silvestra. 😃 Prázdné letiště je zábavní park.[img]/uploads/67a15c8eb9123.jpg[/img]', 'none'),
(32, '2025-01-31 11:53:00', 'zmdi zmdi-balance', '[p]Raději jsem vsadil na jistotu a směnil jsem na novější dollar přezdívaný  Blue dollar, skrývá v sobě ochranný proužek, starší zelený bratříček by v Paraguay mohl způsobit jen odmítavé pohledy směnárníků. Paraguay mě překvapuje - platební karty údajně berou téměř všude. Je to výhoda? Nevím. Ale alespoň nemusím řešit záhadu místních bankomatů. Jeden problém mizí, ale kolik jich ještě čeká? Dám věděť jak dobře se v PY platí kartou.[/p]\r\n\r\n[img]/uploads/679eb313e41a8.jpg[/img]', 'none'),
(33, '2025-01-31 12:46:00', 'zmdi zmdi-airplane', '[b][p]Letový plán [/p][/b]\n[p]PRG - MAD FR 2767 4.2 close v 5:30[/p]\n[p]MAD - ASU  UX023   4.2 close 23:45[/p]', 'none'),
(39, '2025-02-03 15:54:00', 'zmdi zmdi-alert-octagon', 'Právě jsem se vydal na cestu do Jižní Ameriky. Stalo se to skutečností. Do Jižní Ameriky dorazím 5.2. 2025. Nyní cestuji na Pražské letiště. 😃', 'none'),
(40, '2025-02-03 23:42:00', 'zmdi zmdi-airplane', 'Jsem na Pražském letišti, nikdo tu není, procházím stovky metrů, vůbec nikdo. Jsem na celém letišti téměř sám, občas uklízečku zahlédnu. Je to jak ve filmu.', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3164.9497637177296!2d14.268130236664339!3d50.10504935740781!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x470bbf15e3397e17%3A0x69ea59130551a25e!2sTerminal%202%2C%20Aviatick%C3%A1%2C%20161%2000%20Praha%206!5e0!3m2!1scs!2scz!4v1738283357543!5m2!1scs!2scz'),
(41, '2025-02-04 00:18:00', 'zmdi zmdi-airplane', 'Bezpečnostní kontrola na letišti byla úplně na pohodu, byl jsem tam sám. Personál nic neřešil, mohl jsem snad pronést i bombu a nevšimli si. Úplně bez zájmu mé věci prohodili rentgenem, na to jeden řekl kolegovy to je ale marast - plná vanička elektroniky a drátů. Kéž by vše šlo nadále hladce.', 'none'),
(42, '2025-02-04 13:15:00', 'zmdi zmdi-airplane', '[img]/uploads/67a20517ba772.jpg[/img]', 'none'),
(43, '2025-02-04 13:51:00', 'zmdi zmdi-airplane', 'Letět nízkonákladovkou je za trest, mezi sedadly není žádné místo.  Když nemají všichni místenku je to chaoz. Už nikdy nebudu sedět u okénka, jít na záchod znamená vzbudit dva lidi.', 'none'),
(44, '2025-02-04 13:57:00', 'zmdi zmdi-subway', 'Celý den na letišti', 'none'),
(45, '2025-02-05 09:25:00', 'zmdi zmdi-subway', '[img]/uploads/67a366ec86358.jpg[/img]', 'none'),
(46, '2025-02-05 14:26:00', 'zmdi zmdi-flight-land', 'Přílet [img]/uploads/67a367185a133.jpg[/img]', 'none'),
(47, '2025-02-05 11:27:00', 'zmdi zmdi-subway', 'Čekám než si mohu vyzvednout klíče od ubytka z Airbnb [img]/uploads/67a36752d09af.jpg[/img]', 'none'),
(48, '2025-02-05 14:26:00', 'zmdi zmdi-thumb-down', '[img]/uploads/67a3984055681.jpg[/img]', 'none'),
(49, '2025-02-05 14:27:00', 'zmdi zmdi-shopping-basket', '[img]/uploads/67a398b1b829f.jpg[/img]', 'none'),
(50, '2025-02-05 14:48:00', 'zmdi zmdi-subway', '[img]/uploads/67a4232181edc.jpg[/img]', 'none'),
(51, '2025-02-06 11:05:00', 'zmdi zmdi-hotel', 'Dnes jsem spal jen 6 hodin abych mohl nastříhat videa a mohl jsem brzo stávat, protože za chvíli musím se přesunout na nové ubytko. Do 10 hodin musím vyklidit prostor, což dává smysl, protože v 10 hodin někam cestovat je už pozdě, to už vás slunce sežere za živa.', 'none'),
(52, '2025-02-06 15:31:00', 'zmdi zmdi-hotel', '[img]/uploads/67a4c7ebbc9a0.jpg[/img]', 'none'),
(53, '2025-02-06 15:32:00', 'zmdi zmdi-airline-seat-recline-extra', '[img]/uploads/67a4c83beb14f.jpg[/img]', 'none'),
(54, '2025-02-06 15:39:00', 'zmdi zmdi-pin-drop', 'Moje poloha ubytování', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3607.618814496631!2d-57.625324199999994!3d-25.283405299999995!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x945da7e918d4ffa9%3A0x19ded48ecfe5a45e!2sManuel%20Gondra%20393%2C%20Asunci%C3%B3n%20001215!5e0!3m2!1scs!2spy!4v1738855376198!5m2!1scs!2spy'),
(55, '2025-02-06 15:49:00', 'zmdi zmdi-hotel', '[img]/uploads/67a4cc0713c79.jpg[/img]', 'none'),
(56, '2025-02-06 16:30:00', 'zmdi zmdi-pin-help', 'Ubytování se spolubydlícími má nesporné výhody, docela zábava, lze spoustu věcí zjistit.', 'none'),
(57, '2025-02-06 21:06:00', 'zmdi zmdi-wifi-outline', 'Hapruje tu wifi.  Někdy nefunguje, zatím nevím proč.', 'none'),
(58, '2025-02-06 21:07:00', 'zmdi zmdi-alert-octagon', 'Zatím nemám nemám co sdílet, nacházím se blízko nebezpečné oblasti, projížděl jsem jí autem a nic tak drsného jsem neviděl ani ve filmu. Do města na průzkumy se rozhodně neženu, naopak.', 'none'),
(59, '2025-02-06 22:57:00', 'zmdi zmdi-hotel', 'Až do konce března jsem si zaplatil nynější ubytko, dokonce se slevou, protože jsem obešel Airbnb a domluvil se přímo s majitelem ubytování.  Jsou tu velice fajn lidi, učí mě anglicky, je to dost hard.  Je to zábava, je nás tu 9. Všichni jsou super.  Je tu němec, polák, australan, USsák, a další...  :D Je tu zábava. Nejméně rozumím domovníkovy nějak neumí polopaticky ale je super.', 'none'),
(60, '2025-02-07 16:34:00', 'zmdi zmdi-alert-octagon', 'Vedle ubytování mám policejní stanici, to je dost uklidňující, na každým rohu je policajt.  Jsem blízko nějakýho geta, co jsem jenom zahlédl, bylo dost drsné. :D Vidět něco takového na vlastní oči je úplně jiné. Nechtěl bych se tam zabloudit, z okénka auta to stačilo.', 'none'),
(61, '2025-02-07 20:58:00', 'zmdi zmdi-subway', 'Moje první jídlo v Paraguay [img]/uploads/67a665dc155fa.jpg[/img]', 'none'),
(62, '2025-02-07 20:58:00', 'zmdi zmdi-local-cafe', 'Moje první grilovačka v Paraguay [img]/uploads/67a6661f21330.jpg[/img]', 'none'),
(63, '2025-02-08 05:09:00', 'zmdi zmdi-comments', 'Hééj, ostatní se na mě domluvili a neřekli mi, že mám spolubydlícího z Česka,  bylo to od nich pěkný, chtěli abych se učil cizí jazyk :D', 'none'),
(64, '2025-02-08 17:48:00', 'zmdi zmdi-local-see', '[img]/uploads/67ac0f73e33fe.jpg[/img]', 'none'),
(65, '2025-02-08 18:50:00', 'zmdi zmdi-local-see', 'Jsem v Paraguay milionář! [img]/uploads/67a79993af29a.jpg[/img]', 'none'),
(66, '2025-02-09 03:34:00', 'zmdi zmdi-library', '[img]/uploads/67a81479917e5.jpg[/img]', 'none'),
(69, '2025-02-09 17:14:00', 'zmdi zmdi-local-see', 'Navštívil jsem místní tržiště - Mercado 4, měli tam nejlepší ananasový džus, co jsem kdy pil, nechutnalo to jako ananas. :) [img]/uploads/67ac121d09d2c.jpg[/img]', 'none'),
(70, '2025-02-09 10:51:00', 'zmdi zmdi-my-location', 'Mercado 4 - obrovské tržiště se vším', 'https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d439.72935909715835!2d-57.62227213940625!3d-25.299845707065757!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sMercado%204!5e0!3m2!1scs!2spy!4v1739134507332!5m2!1scs!2spy'),
(71, '2025-02-09 23:50:00', 'zmdi zmdi-local-see', '[img]/uploads/67a93170d4001.jpg[/img]', 'none'),
(72, '2025-02-09 23:51:00', 'zmdi zmdi-local-see', '[img]/uploads/67a934b7d1f30.jpg[/img]', 'none'),
(73, '2025-02-10 00:34:00', 'zmdi zmdi-lamp', 'To jsem osel, tu vychází nejvíce lidí večer po deváté hodině, celé rodiny, desítky dětí si hraje na ulici schovku a to i ti nejmenší. Lidi jsou tu laskaví, přátelští, a chudí. V parku to ožilo jak o slavnostech. Na každém rohu stojí policista, je jich tu desítky. Žebráci tu nejsou dotěrní, i když jsem tam byl s nimi sám v tmavé uličce.', 'none'),
(74, '2025-02-10 16:43:00', 'zmdi zmdi-network-warning', 'Další videa už nebudu vydávat tak často, chci zvýšit kvalitu zpracování. Nyní jsem zaznamenával moje první zážitky po příletu do Asunciónu, a narychlo na koleni stříhal co jsem kde viděl a zažil. :)', 'none'),
(75, '2025-02-10 16:58:00', 'zmdi zmdi-format-quote', 'Tady jsem už ztratil pojem o čase, většinou neutiším ani přibližně kolik je hodin. Jestli je den nebo noc, už to neřeším, spím tehdy až se mi chce. Nestíhám tu nic, myslel jsem si že tu budu mít spoustu času na všechno, opak je pravdou, zatím vůbec nic nestíhám, volného času jsem moc neměl, vlastně žádný. Snažil jsem se zjistit jak to tu chodí, kde jsou obchody, jestli je bezpečné vycházet a kam, snažil jsem se seznámit se spolubydlícími. Čas na učení španělštiny tu nemám žádný. Konečně si jdu vyprat prádlo, jdu do sprchy, a už bude zase večer a vyrazím ven. Nachodil jsem tu desítky kilometrů, nožičky bolí, Teď si dávám pauzu. Pořád jsem zde dehydrovaný, během jedné procházky vypiji 2 litry vody.', 'none'),
(77, '2025-02-12 04:03:00', 'zmdi zmdi-local-see', 'Tak takové mít, tak procestuji celí svět :D [img]/uploads/67ac0f8fb1183.jpg[/img]', 'none'),
(78, '2025-02-12 04:03:00', 'zmdi zmdi-local-see', 'Všechny zdravím z Jižní Ameriky!  [img]/uploads/67ac0fa27234c.jpg[/img]', 'none'),
(79, '2025-02-12 04:04:00', 'zmdi zmdi-local-see', '[img]/uploads/67ac0fb59671f.jpg[/img]', 'none'),
(80, '2025-02-12 04:04:00', 'zmdi zmdi-local-see', 'Ahoj všem, posílám pozdrav z Asunciónu :)  [img]/uploads/67ac0fe583172.jpg[/img]', 'none'),
(82, '2025-02-12 23:45:00', 'zmdi zmdi-local-see', 'Zajímavé, bez sáčku to jde taky. [img]/uploads/67ad248c84ded.jpg[/img]', 'none'),
(83, '2025-02-14 03:33:00', 'zmdi zmdi-local-see', '[img]/uploads/67aeab873ba49.jpg[/img]', 'none'),
(84, '2025-02-14 03:33:00', 'zmdi zmdi-subway', '[img]/uploads/67aeabcbda666.jpg[/img]', 'none'),
(85, '2025-02-14 03:35:00', 'zmdi zmdi-subway', '[img]/uploads/67aeabf591665.jpg[/img]', 'none'),
(86, '2025-02-14 03:36:00', 'zmdi zmdi-local-see', '[img]/uploads/67aeac40964b0.jpg[/img]', 'none'),
(87, '2025-02-14 03:36:00', 'zmdi zmdi-local-see', '[img]/uploads/67aeac5dc42d4.jpg[/img]', 'none'),
(88, '2025-02-14 03:37:00', 'zmdi zmdi-local-see', '[img]/uploads/67aeac83f352d.jpg[/img]', 'none'),
(90, '2025-02-14 03:38:00', 'zmdi zmdi-local-see', 'Setkání expatů [img]/uploads/67aeacc6a1c59.jpg[/img]', 'none'),
(91, '2025-02-14 03:56:00', 'zmdi zmdi-shopping-basket', '[img]/uploads/67aeb0ed376c8.jpg[/img]', 'none'),
(92, '2025-02-14 16:15:00', 'zmdi zmdi-thumb-up', 'Vyřídil jsem úspěšně rezidenci na úřadech, žádost podána, nyní v PY mohu být neomezeně dlouho, za tři měsíce dostanu občanský průkaz.', 'none'),
(93, '2025-02-14 18:21:00', 'zmdi zmdi-lamp', 'Viajo por la mitad del planeta para descubrir que lo que buscaba estaba donde he estado todo el tiempo.', 'none'),
(94, '2025-02-14 08:20:00', 'zmdi zmdi-notifications-active', 'Vyrážím na úřady... [img]/uploads/67af898e100d8.jpg[/img]', 'none'),
(95, '2025-02-14 23:19:00', 'zmdi zmdi-local-see', '[img]/uploads/67afc1973899b.jpg[/img]', 'none'),
(96, '2025-02-15 12:38:00', 'zmdi zmdi-car', 'Jsem s Markem na výletě (týpek z česka), a zrovna jsme v auto opravně. 😃 [img]/uploads/67b07dd355d90.jpg[/img]', 'none'),
(97, '2025-02-15 14:34:00', 'zmdi zmdi-car', 'Jsme v další auto opravně.[img]/uploads/67b097edaf90f.jpg[/img]', 'none'),
(98, '2025-02-15 20:12:00', 'zmdi zmdi-compass', 'Rybníček ve městě hlídaný vojáky, ale voják nás chytl a vykázal pryč. [img]/uploads/67b0e72d252c4.jpg[/img]', 'none'),
(99, '2025-02-15 22:53:00', 'zmdi zmdi-map', 'Město Asunción je velice nezajímavé město. Nic tu není, kromě pláže a pár ulic kolem. Koupat se tu nedá, nejspíš za měsíc se podívám Encarnación - tam se lze koupat, vodopády v Brazílii jen pokud bude dobrá příležitost.  Nejbližší plán se podívat do Balneario Paso Carreta - Cerro Acahay - řeka, kopec, vyhlídka.', 'none'),
(100, '2025-02-16 01:47:00', 'zmdi zmdi-notifications-active', 'Rakušané mi připomínají českou povahu – snad díky historickému spojení skrze Rakousko-Uhersko. Jejich otevřenost a přátelskost je na první pohled patrná.\nMladí Němci působí velmi přátelsky, otevřeně a s porozuměním, okamžitě navazují kontakt a jejich zájem je skutečný a milí. Naopak přístup Američanů na mě působil poněkud arogantně, což mi zanechalo smíšený dojem. U Angličanů si všímám jisté rezervovanosti, i když jsou ochotní pomoci a občas projevují přátelský zájem, chybí jim ta spontánní blízkost, kterou cítím u Rakušanů nebo Němců. S nimi se spíše setkám na společenské párty – příjemně si pokecáte, ale netvoří se hned opravdové přátelství. Naopak Polák se ihned chová jako starý přítel, občas mu však schází jemný zájem o to, jak se máte.', 'none'),
(101, '2025-02-16 18:42:00', 'zmdi zmdi-case-check', 'Paraguayský vítr má smysl pro humor: moje nejlepší spodky a ponožky teď zdobí střechy a vrcholky stromů, 700 korun se vznáší nad zemí a já jen bezmocně přihlížím.', 'none'),
(102, '2025-02-16 23:39:00', 'zmdi zmdi-local-see', '[img]/uploads/67b26931f09ab.jpg[/img]', 'none'),
(104, '2025-02-16 23:39:00', 'zmdi zmdi-local-see', '[img]/uploads/67b2694f54b0a.jpg[/img]', 'none'),
(105, '2025-02-16 23:40:00', 'zmdi zmdi-local-see', '[img]/uploads/67b2696f57e86.jpg[/img]', 'none'),
(106, '2025-02-17 13:28:00', 'zmdi zmdi-airline-seat-individual-suite', 'Musel jsem v půlce své cesty v Paraguari ukončit výlet, protože jsem si natáhl něco u kotníku. Nyní zpět na motelu. [img]/uploads/67b32b8f9fc15.jpg[/img]', 'none'),
(107, '2025-02-17 13:34:00', 'zmdi zmdi-local-see', 'Musel jsem v půlce své cesty v Paraguari ukončit výlet, protože jsem si natáhl něco u kotníku. Nyní zpět na motelu. [img]/uploads/67b32cda54b8b.jpg[/img]', 'none'),
(108, '2025-02-17 13:46:00', 'zmdi zmdi-local-see', 'Stará točna. [img]/uploads/67b32fcdbd4ff.jpg[/img]', 'none'),
(109, '2025-02-17 13:53:00', 'zmdi zmdi-local-see', '[img]/uploads/67b3317149bdf.jpg[/img]', 'none'),
(110, '2025-02-17 18:21:00', 'zmdi zmdi-notifications-active', 'Musím do sprchy. 😃 Byl to náročný výlet. [img]/uploads/67b39a6c0e0e9.jpg[/img]', 'none'),
(111, '2025-02-17 20:17:00', 'zmdi zmdi-local-see', '[img]/uploads/67b47a8b6e9af.jpg[/img]', 'none'),
(112, '2025-02-18 19:32:00', 'zmdi zmdi-local-see', 'LidoBar - lomito a caballo con cebolla [img]/uploads/67b4d242e499e.jpg[/img]', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14430.690573319918!2d-57.643618599421416!3d-25.28159532113147!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x945da7f24904311d%3A0xc2a897ad5f9fec16!2sLido%20Bar!5e0!3m2!1scs!2spy!4v1739903884742!5m2!1scs!2spy'),
(113, '2025-02-18 19:36:00', 'zmdi zmdi-local-see', '[img]/uploads/67b4d3585257b.jpg[/img]', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d14430.690573319918!2d-57.643618599421416!3d-25.28159532113147!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x945da7f24904311d%3A0xc2a897ad5f9fec16!2sLido%20Bar!5e0!3m2!1scs!2spy!4v1739903884742!5m2!1scs!2spy'),
(114, '2025-02-18 23:47:00', 'zmdi zmdi-airplane', 'Zpáteční let do Madridu jsem naplánoval o měsíc dříve na April 27, 2025. Let z Madridu do ČR zatím neplánuji, zvažuji že se ještě někde po cestě zdržím.', 'none'),
(115, '2025-02-19 17:53:00', 'zmdi zmdi-local-see', '[img]/uploads/67b60ca3a3d88.jpg[/img]', 'none'),
(116, '2025-02-19 17:54:00', 'zmdi zmdi-local-see', '[img]/uploads/67b60cc303b0b.jpg[/img]', 'none'),
(117, '2025-02-19 17:54:00', 'zmdi zmdi-local-see', '[img]/uploads/67b60cd772bdd.jpg[/img]', 'none'),
(118, '2025-02-19 17:54:00', 'zmdi zmdi-local-see', 'Zítra půjdu zkusit jídelnu v S6 [img]/uploads/67b60ce6cb569.jpg[/img]', 'none'),
(119, '2025-02-19 17:55:00', 'zmdi zmdi-local-see', 'Pomelo [img]/uploads/67b60d0da23e9.jpg[/img]', 'none'),
(120, '2025-02-19 17:56:00', 'zmdi zmdi-local-see', '[img]/uploads/67b60d5527fd4.jpg[/img]', 'none'),
(121, '2025-02-20 16:46:00', 'zmdi zmdi-quote', 'Celý den u PC.', 'none'),
(122, '2025-02-20 17:25:00', 'zmdi zmdi-cake', 'Vanička napravo chutnala dobře ale zvláštně hořčičně, nepoznal jsem co to je, ale chutnalo to dost kukuřičně, vajíčka s ? Nalevo suchá buchta s květákem?   [img]/uploads/67b757854be1d.jpg[/img]', 'none'),
(123, '2025-02-22 10:59:00', 'zmdi zmdi-laptop-mac', 'Už tři dny jen u pc.  [br][br]  public function renderComponents()\r\n    {\r\n        $componentRenderData = [];\r\n\r\n        foreach ($this->componentData as $data) {\r\n            $contentData = Content::find($data->contents_id);', 'none'),
(124, '2025-02-22 22:03:00', 'zmdi zmdi-pin-help', 'Jogurt v sáčku? [img]/uploads/67ba3bac22f63.jpg[/img]', 'none'),
(126, '2025-02-23 18:44:00', 'zmdi zmdi-local-see', '[img]/uploads/67bb5e858e7fb.jpg[/img]', 'none'),
(127, '2025-02-23 18:44:00', 'zmdi zmdi-local-see', '[img]/uploads/67bb5ea4f09f0.jpg[/img]', 'none'),
(128, '2025-02-23 18:45:00', 'zmdi zmdi-local-see', '[img]/uploads/67bb5ec501819.jpg[/img]', 'none'),
(129, '2025-02-24 22:41:00', 'zmdi zmdi-thumb-up', 'Seznámil jsem se tu zde i s místními. Občas dát tu někomu na ulici studené pití, znamená že vás na ulici místní zdraví, přátelí se, a dokonce i chrání.', 'none'),
(130, '2025-02-25 04:13:00', 'zmdi zmdi-local-see', '[img]/uploads/67bd3583a6c87.jpg[/img]', 'none'),
(131, '2025-02-25 04:14:00', 'zmdi zmdi-local-see', '[img]/uploads/67bd35f12c22e.jpg[/img]', 'none'),
(132, '2025-02-25 17:27:00', 'zmdi zmdi-mood', 'Místní mě pozvali k sobě domů na pití Tereré.', 'none'),
(133, '2025-02-25 18:18:00', 'zmdi zmdi-lamp', 'Banány 25kč za 1kg [img]/uploads/67bdfbad67997.jpg[/img]', 'none'),
(134, '2025-02-26 04:14:00', 'zmdi zmdi-local-see', '[img]/uploads/67be87676fa9b.jpg[/img]', 'none'),
(135, '2025-02-26 04:15:00', 'zmdi zmdi-local-see', '[img]/uploads/67be87a87e7f3.jpg[/img]', 'none'),
(136, '2025-02-26 04:24:00', 'zmdi zmdi-local-see', '[img]/uploads/67be898d838ce.jpg[/img]', 'none'),
(137, '2025-02-27 03:12:00', 'zmdi zmdi-airline-seat-individual-suite', 'Prospal jsem celý den. Nefunguje mi esim v náhradním mobilu, ale jiné datové sim fungují. Přišel jsem o spojení v app Signal, bez čísla se nepřihlásím téměř do ničeho. Mobil s esim jsem tu zatím nenašel.', 'none'),
(138, '2025-02-27 17:51:00', 'zmdi zmdi-my-location', 'Prší jsem na hostelu. Ležím a spím, ve dne spím v noci bdím. [img]/uploads/67c098548dba2.jpg[/img]', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d892.9672530241448!2d-57.62549404361987!3d-25.283886862025536!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x945da7e918ddc643%3A0x616850833d6d9164!2sManuel%20Gondra%20393%2C%20Asunci%C3%B3n%20001215!5e0!3m2!1scs!2spy!4v1740675052824!5m2!1scs!2spy'),
(139, '2025-02-28 00:26:00', 'zmdi zmdi-local-see', '[img]/uploads/67c0f532a0bd7.jpg[/img]', 'none'),
(140, '2025-02-28 00:28:00', 'zmdi zmdi-local-see', '[img]/uploads/67c0f545cacdd.jpg[/img]', 'none'),
(141, '2025-02-28 00:29:00', 'zmdi zmdi-local-see', '[img]/uploads/67c0f55a7e923.jpg[/img]', 'none'),
(144, '2025-03-01 20:06:00', 'zmdi zmdi-local-see', 'Otevřené víko aby bylo vidět jak je naplněna [img]/uploads/67c35acf0a747.jpg[/img]', 'none'),
(145, '2025-03-01 20:07:00', 'zmdi zmdi-local-see', '[img]/uploads/67c35b0648c48.jpg[/img]', 'none'),
(146, '2025-03-01 20:07:00', 'zmdi zmdi-local-see', '[img]/uploads/67c35b1a6cef0.jpg[/img]', 'none'),
(147, '2025-03-01 20:08:00', 'zmdi zmdi-local-see', '[img]/uploads/67c35b3de94be.jpg[/img]', 'none'),
(148, '2025-03-03 17:43:00', 'zmdi zmdi-local-see', 'Existují místa, kam nevedou žádné cesty. Místa, která zůstávají skrytá před zraky světa, pohlcená hustou džunglí a zapomenutá časem. A právě tam jsem se vydal...\r\n\r\nBez mapy, bez jistoty, co mě čeká. Jen já a neprostupná zeleň, která se mě snažila pohltit. Krok za krokem, prodíral jsem se divočinou, přeskakoval řeku po kluzkých kamenech a cítil, jak se kolem mě stahuje něco neviditelného, tajemného…\r\n\r\nA pak jsem ji uviděl – jeskyni, kterou skryly liány a tma. Bylo to jediné místo, kudy se dalo projít. Zpomalil jsem. Co tam číhá? Tajemné šumění vody se mísilo se zvuky džungle, a já cítil zvláštní chvění. Jít dál, nebo se otočit?\r\n\r\nTohle nebyla obyčejná cesta k vodopádu. Tohle bylo dobrodružství, kde se každý krok mohl stát osudovým.\r\n\r\nA na konci? Skrytý vodopád. Nenápadný, ale obklopený neskutečnou energií místa, kam se jen tak někdo nedostane.\r\n\r\n[img]/uploads/67c5dc3919222.jpg[/img]', 'none'),
(149, '2025-03-03 17:47:00', 'zmdi zmdi-local-see', 'Výroba uhlí [img]/uploads/67c5dd6f936f1.jpg[/img]', 'none'),
(150, '2025-03-03 17:52:00', 'zmdi zmdi-local-see', '[img]/uploads/67c5dea4a838d.jpg[/img]', 'none'),
(151, '2025-03-03 18:00:00', 'zmdi zmdi-local-see', '[img]/uploads/67c5e0556b236.jpg[/img]', 'none'),
(152, '2025-03-03 18:02:00', 'zmdi zmdi-local-see', '[img]/uploads/67c5e0b516373.jpg[/img]', 'none'),
(153, '2025-03-05 23:32:00', 'zmdi zmdi-local-see', '[img]/uploads/67c8d125b38a6.jpg[/img]', 'none'),
(154, '2025-03-05 23:33:00', 'zmdi zmdi-local-see', '[img]/uploads/67c8d13a7f6ff.jpg[/img]', 'none'),
(155, '2025-03-05 23:33:00', 'zmdi zmdi-local-see', '[img]/uploads/67c8d14e5ee8a.jpg[/img]', 'none'),
(156, '2025-03-05 23:33:00', 'zmdi zmdi-local-see', '[img]/uploads/67c8d16181dcf.jpg[/img]', 'none'),
(157, '2025-03-05 23:34:00', 'zmdi zmdi-local-see', '[img]/uploads/67c8d17443c67.jpg[/img]', 'none'),
(158, '2025-03-05 23:34:00', 'zmdi zmdi-local-see', '[img]/uploads/67c8d19125753.jpg[/img]', 'none'),
(159, '2025-03-07 03:24:00', 'zmdi zmdi-local-see', 'Potkal jsem Jirku v Asu úplnou náhodou, za chvíli z Paraguay bude Praguay [img]/uploads/67ca58d741057.jpg[/img]', 'none'),
(160, '2025-03-08 00:23:00', 'zmdi zmdi-local-see', '[img]/uploads/67cb80506c100.jpg[/img]', 'none'),
(161, '2025-03-08 00:25:00', 'zmdi zmdi-local-see', '[img]/uploads/67cb8072e849e.jpg[/img]', 'none'),
(162, '2025-03-08 00:25:00', 'zmdi zmdi-local-see', '[img]/uploads/67cb808856caf.jpg[/img]', 'none'),
(163, '2025-03-08 00:26:00', 'zmdi zmdi-subway', '[img]/uploads/67cb80a895c72.jpg[/img]', 'none'),
(164, '2025-03-08 00:26:00', 'zmdi zmdi-local-see', '[img]/uploads/67cb80cfb25eb.jpg[/img]', 'none'),
(165, '2025-03-08 00:27:00', 'zmdi zmdi-local-see', '[img]/uploads/67cb80fb3e82f.jpg[/img]', 'none'),
(166, '2025-03-08 00:28:00', 'zmdi zmdi-local-see', '[img]/uploads/67cb814783c23.jpg[/img]', 'none'),
(167, '2025-03-08 00:29:00', 'zmdi zmdi-local-see', '[img]/uploads/67cb81a300d53.jpg[/img]', 'none'),
(168, '2025-03-08 00:45:00', 'zmdi zmdi-local-see', '[img]/uploads/67cb850e1a869.jpg[/img]', 'none'),
(169, '2025-03-08 08:33:00', 'zmdi zmdi-local-see', '[img]/uploads/67cbf2c51b3eb.jpg[/img]', 'none'),
(170, '2025-03-08 18:39:00', 'zmdi zmdi-local-see', '[img]/uploads/67cc80e44afea.jpg[/img]', 'none'),
(171, '2025-03-10 17:57:00', 'zmdi zmdi-shopping-basket', 'Už jedině merino triko od firmy [url=https://shop.lasting.eu/cs/trika-kratky-rukav/17404-59082-grizly-panske-merino-triko-s-tiskem-modre-8596445203837.html#/933-velikost-xl/1060-barva-modra/1598-odstin-modra_5160]Lasting[/url] !\r\n\r\n[img]/uploads/67cf1a9db71b2.jpg[/img]', 'none'),
(172, '2025-03-14 02:11:00', 'zmdi zmdi-local-see', '[img]/uploads/67d382500c0c6.jpg[/img]', 'none'),
(173, '2025-03-14 02:11:00', 'zmdi zmdi-local-see', '[img]/uploads/67d3827193826.jpg[/img]', 'none'),
(174, '2025-03-14 02:12:00', 'zmdi zmdi-local-see', '[img]/uploads/67d38288409f2.jpg[/img]', 'none'),
(175, '2025-03-14 02:12:00', 'zmdi zmdi-local-see', '[img]/uploads/67d382e96cfa1.jpg[/img]', 'none'),
(176, '2025-03-15 09:30:00', 'zmdi zmdi-thumb-down', 'Celou noc prší. 18C', 'none'),
(177, '2025-03-16 02:52:00', 'zmdi zmdi-local-see', '[img]/uploads/67d62ef186871.jpg[/img]', 'none'),
(178, '2025-03-16 02:53:00', 'zmdi zmdi-subway', '[img]/uploads/67d62f0fe0aa5.jpg[/img]', 'none'),
(179, '2025-03-17 03:30:00', 'zmdi zmdi-local-see', '[img]/uploads/67d7895b55d88.jpg[/img]', 'none'),
(180, '2025-03-17 03:31:00', 'zmdi zmdi-subway', '[img]/uploads/67d7897c583c1.jpg[/img]', 'none'),
(181, '2025-03-17 03:31:00', 'zmdi zmdi-local-see', '[img]/uploads/67d789a37408e.jpg[/img]', 'none'),
(182, '2025-03-17 03:32:00', 'zmdi zmdi-local-see', '[img]/uploads/67d789c2c55ba.jpg[/img]', 'none'),
(183, '2025-03-17 19:36:00', 'zmdi zmdi-local-see', '[img]/uploads/67d86bab90d53.jpg[/img]', 'none'),
(184, '2025-03-19 13:41:00', 'zmdi zmdi-local-see', '[img]/uploads/67dabb8636aae.jpg[/img]', 'none'),
(185, '2025-03-20 15:19:00', 'zmdi zmdi-local-cafe', '[img]/uploads/67dc2404b86e1.jpg[/img]', 'none'),
(186, '2025-03-21 04:29:00', 'zmdi zmdi-local-see', '[img]/uploads/67dcdd3d04530.jpg[/img]', 'none'),
(187, '2025-03-21 04:30:00', 'zmdi zmdi-local-see', '[img]/uploads/67dcdd5e30a3f.jpg[/img]', 'none'),
(188, '2025-03-21 04:30:00', 'zmdi zmdi-local-see', '[img]/uploads/67dcdd8e5c951.jpg[/img]', 'none'),
(189, '2025-03-21 19:54:00', 'zmdi zmdi-local-see', '[img]/uploads/67ddb61adafb6.jpg[/img]', 'none'),
(190, '2025-03-21 19:55:00', 'zmdi zmdi-local-see', '[img]/uploads/67ddb62ebb2ba.jpg[/img]', 'none'),
(191, '2025-03-21 19:55:00', 'zmdi zmdi-local-see', '[img]/uploads/67ddb66be998e.jpg[/img]', 'none'),
(192, '2025-03-21 19:55:00', 'zmdi zmdi-local-see', '[img]/uploads/67ddb640d1168.jpg[/img]', 'none'),
(193, '2025-03-21 19:56:00', 'zmdi zmdi-local-see', '[img]/uploads/67ddb6deae7ee.jpg[/img]', 'none'),
(194, '2025-03-23 18:16:00', 'zmdi zmdi-local-see', 'Tady bydlím 😃😃😃[img]/uploads/67e041f69a407.jpg[/img]', 'none'),
(195, '2025-03-23 18:55:00', 'zmdi zmdi-local-see', '[img]/uploads/67e04b42d33c4.jpg[/img]', 'none'),
(196, '2025-03-23 19:09:00', 'zmdi zmdi-local-see', '[img]/uploads/67e04e943b2ed.jpg[/img]', 'none'),
(197, '2025-03-23 19:19:00', 'zmdi zmdi-local-see', '[img]/uploads/67e050cc40cfc.jpg[/img]', 'none'),
(199, '2025-03-23 23:01:00', 'zmdi zmdi-local-see', '[img]/uploads/67e084e76b24a.jpg[/img]', 'none'),
(200, '2025-03-23 23:03:00', 'zmdi zmdi-local-see', '[img]/uploads/67e085311447c.jpg[/img]', 'none'),
(201, '2025-03-23 23:25:00', 'zmdi zmdi-local-see', '[img]/uploads/67e2091bd5074.jpg[/img]', 'none'),
(202, '2025-03-23 23:25:00', 'zmdi zmdi-local-see', '[img]/uploads/67e08a7da78c4.jpg[/img]', 'none'),
(203, '2025-03-23 23:26:00', 'zmdi zmdi-local-see', '[img]/uploads/67e08ab839cb4.jpg[/img]', 'none'),
(204, '2025-03-23 23:45:00', 'zmdi zmdi-local-see', '[img]/uploads/67e08f1dc0f59.jpg[/img]', 'none'),
(205, '2025-03-23 23:46:00', 'zmdi zmdi-local-see', '[img]/uploads/67e08f8b688ef.jpg[/img]', 'none'),
(206, '2025-03-22 20:37:00', 'zmdi zmdi-local-see', '[img]/uploads/67e0a975a77db.jpg[/img]', 'none'),
(207, '2025-03-27 00:52:00', 'zmdi zmdi-local-see', 'Když není Alza, vyřešeno za pár sekund, oživena baterie 😃[img]/uploads/67e4936d1a4af.jpg[/img]', 'none'),
(208, '2025-03-28 19:27:00', 'zmdi zmdi-local-see', 'Pořád prší, podzim. [img]/uploads/67e6ea1aeb169.jpg[/img]', 'none'),
(209, '2025-03-29 00:07:00', 'zmdi zmdi-local-see', '[img]/uploads/67e72bb956a9c.jpg[/img]', 'none'),
(210, '2025-03-29 00:07:00', 'zmdi zmdi-local-see', '[img]/uploads/67e72bce14992.jpg[/img]', 'none'),
(211, '2025-03-29 00:08:00', 'zmdi zmdi-local-see', '[img]/uploads/67e72c1b62e78.jpg[/img]', 'none'),
(212, '2025-03-29 00:09:00', 'zmdi zmdi-local-see', '[img]/uploads/67e72c49685de.jpg[/img]', 'none'),
(214, '2025-04-05 04:51:00', 'zmdi zmdi-local-see', '[img]/uploads/67f09aa937a07.jpg[/img]', 'none'),
(215, '2025-04-06 06:08:00', 'zmdi zmdi-local-see', '[img]/uploads/67f1fe3e97126.jpg[/img]', 'none'),
(216, '2025-04-10 23:50:00', 'zmdi zmdi-local-see', '[img]/uploads/67f83d3907dec.jpg[/img]', 'none'),
(218, '2025-04-11 00:19:00', 'zmdi zmdi-local-see', '[img]/uploads/67f843fa5a2ed.jpg[/img]', 'none'),
(219, '2025-04-11 00:28:00', 'zmdi zmdi-local-see', '[img]/uploads/67f845f88f620.jpg[/img]', 'none'),
(220, '2025-04-11 01:57:00', 'zmdi zmdi-local-see', '[img]/uploads/67f85b02c3fcc.jpg[/img]', 'none'),
(221, '2025-04-11 02:30:00', 'zmdi zmdi-local-see', '[img]/uploads/67f862c4373b1.jpg[/img]', 'none'),
(222, '2025-04-11 02:31:00', 'zmdi zmdi-local-see', '[img]/uploads/67f862d9711eb.jpg[/img]', 'none'),
(223, '2025-04-14 01:55:00', 'zmdi zmdi-local-see', '[img]/uploads/67fc4ef4e534e.jpg[/img]', 'none'),
(224, '2025-04-17 22:08:00', 'zmdi zmdi-local-see', '[img]/uploads/68015fde1bcd3.jpg[/img]', 'none'),
(225, '2025-04-20 11:29:00', 'zmdi zmdi-local-see', '[img]/uploads/6804be97e7da8.jpg[/img]', 'none'),
(226, '2025-04-20 11:30:00', 'zmdi zmdi-local-see', '[img]/uploads/6804beba4c8b4.jpg[/img]', 'none'),
(227, '2025-04-20 11:30:00', 'zmdi zmdi-local-see', '[img]/uploads/6804bef7d3fd0.jpg[/img]', 'none'),
(228, '2025-04-22 01:23:00', 'zmdi zmdi-local-see', '[img]/uploads/6806d39850587.jpg[/img]', 'none'),
(229, '2025-04-26 00:34:00', 'zmdi zmdi-airplane', 'UX024   - ASU -  MAD April 27, 13:15h - 05:20h   [br]\r\nFR690 - MAD -  Vídeň, 29 DUB, 10:40 - 13:35 [br]\r\nBUS - Vídeň -  Brno -  cca19.00', 'none'),
(230, '2025-04-28 07:45:00', 'zmdi zmdi-local-see', '[img]/uploads/680f15f797f26.jpg[/img]', 'none'),
(231, '2025-04-28 07:45:00', 'zmdi zmdi-local-see', 'Už jsem v Madridu. Další den mi letí letadlo do Vídně.  [img]/uploads/680f16047ae99.jpg[/img]', 'none');

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `timeline_posts`
--
ALTER TABLE `timeline_posts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `timeline_posts`
--
ALTER TABLE `timeline_posts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=232;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
