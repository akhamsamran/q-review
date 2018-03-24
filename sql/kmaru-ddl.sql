ALTER DATABASE faqt CHARACTER SET utf8 COLLATE utf8_unicode_ci;
DROP TABLE IF EXISTS ledger;
DROP TABLE IF EXISTS board;
DROP TABLE IF EXISTS card;
DROP TABLE IF EXISTS category;
DROP TABLE IF EXISTS profile;

-- profile table:
CREATE TABLE profile(
-- attribute for primary key:
	profileId BINARY(16) NOT NULL,
	-- attributes for profile:
	profileActivationToken CHAR(32),
	profileEmail VARCHAR(128) NOT NULL,
	profileHash CHAR(128) NOT NULL,
	profileName VARCHAR(50),
	profilePrivilege TINYINT UNSIGNED,
	profileSalt CHAR(64) NOT NULL,
	profileUsername VARCHAR(50),
	-- unique index created:
	UNIQUE (profileEmail),
	UNIQUE (profileUsername),
	-- Primary key:
	PRIMARY KEY(profileId)
	);
-- category table:
CREATE TABLE category(
-- attribute for primary key:
	categoryId BINARY(16) NOT NULL,
	-- attribute for foreign key:
	categoryProfileId BINARY(16) NOT NULL,
	-- attributes for category;
	categoryName VARCHAR(64) NOT NULL,
	-- unique index created:
	INDEX (categoryProfileId),
	INDEX (categoryName),
	-- create foreign keys and relationships:
	FOREIGN KEY (categoryProfileId) REFERENCES profile(profileId),
	-- Primary key:
	PRIMARY KEY(categoryId)
);

-- card table:
CREATE TABLE card(
-- attribute for primary key:
	cardId BINARY(16) NOT NULL,
	-- attribute for foreign key:
	cardCategoryId BINARY(16) NOT NULL,
	-- attributes for card:
	cardAnswer VARCHAR(255) NOT NULL,
	cardPoints TINYINT UNSIGNED,
	cardQuestion VARCHAR(255),
	-- unique index created:
	INDEX (cardCategoryId),
	INDEX (cardPoints),
	-- create foreign keys and relationships:
	FOREIGN KEY (cardCategoryId) REFERENCES category(categoryId),
	-- Primary Key:
	PRIMARY KEY(cardId)
);

-- board table:
CREATE TABLE board(
	-- attribute for primary key:
	boardId BINARY(16) NOT NULL,
	-- attribute for foreign key:
	boardProfileId BINARY(16) NOT NULL,
	-- other attribute for board:
	boardName VARCHAR(64),
	-- unique index created:
	INDEX (boardProfileId),
	-- create foreign key and relationships:
	FOREIGN KEY (boardProfileId) REFERENCES profile(profileId),
	-- Primary key:
	PRIMARY KEY (boardId)
);

-- ledger table:
CREATE TABLE ledger(
	-- attribute for foreign keys:
	ledgerBoardId BINARY(16) NOT NULL,
	ledgerCardId BINARY(16) NOT NULL,
	ledgerProfileId BINARY(16) NOT NULL,
	-- attributes for ledger:
	ledgerPoints MEDIUMINT SIGNED,
	ledgerType TINYINT UNSIGNED,
	-- unique index created:
	INDEX (ledgerBoardId),
	INDEX (ledgerCardId),
	INDEX (ledgerProfileId),
	-- create foreign keys and relationships:
	FOREIGN KEY (ledgerBoardId) REFERENCES board(boardId),
	FOREIGN KEY (ledgerCardId) REFERENCES card(cardId),
	FOREIGN KEY (ledgerProfileId) REFERENCES profile(profileId),
	-- Primary Key (compound key):
	PRIMARY KEY (ledgerBoardId, ledgerCardId, ledgerProfileId)
);

-- the captain:
INSERT INTO profile(profileId, profileActivationToken, profileEmail, profileHash, profileName, profilePrivilege, profileSalt, profileUsername)
VALUES(UNHEX(REPLACE('C2954660F27B45578F2DC68E8B2D8AA8', '-', '')), 'null', 'deepdivedylan@cnm.edu',	'58c2503d7de5c20ac408b907f863f5a0c1db94cc2445063f0f67ce9900d3d55b854959a05bf885e3d49beed169a241112a7609815b24667a68cd2651d4d2bafd',	'Dylan',	'1', 'e99ae3a325867c32b76ccffe0a15d099ce1690aed6aad46c8ab1226c0aafa942', 'Captain Dylan');

INSERT INTO profile(profileId, profileActivationToken, profileEmail, profileHash, profileName, profilePrivilege, profileSalt, profileUsername)
VALUES(UNHEX(REPLACE('5C12B395E7824422B1B056D57598A7FE', '-', '')), 'null', 'grodd@cnm.edu',	'fb72e587e7b92c5d5aebccafee8f435888a0f9f953e884dccf54f8660367ff1b9eb84d33665e0b5237ea6be475ff3345b7c79abdbaca826351f8fca00cb05e02',	'Gene Roddenberry',	'0', '634d3d43a49c2c241fb10360302a35f1338ab388535261ba12a63749b5129449', 'GRodd');


-- categories:


INSERT INTO category(categoryId, categoryProfileId, categoryName)
VALUES(UNHEX(REPLACE('0a253dc2-54a1-4985-ae66-dd5aed20b601', '-', '')), UNHEX(REPLACE('5C12B395E7824422B1B056D57598A7FE', '-', '')), 'join');




-- boards:


INSERT INTO board(boardId, boardProfileId, boardName)
VALUES (UNHEX(REPLACE('0c1c711e-e2e4-439d-9975-9658851b1781', '-', '')), UNHEX(REPLACE('C2954660F27B45578F2DC68E8B2D8AA8', '-', '')), 'DDC');

INSERT INTO board(boardId, boardProfileId, boardName)
VALUES (UNHEX(REPLACE('129c71cf-89e0-4d5d-9968-3c4cf079fd46', '-', '')), UNHEX(REPLACE('C2954660F27B45578F2DC68E8B2D8AA8', '-', '')), 'great game');

-- cards:


INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('fa41de8f-f69b-47cd-8b71-6fff8a3a1185', '-', '')),UNHEX(REPLACE('0a253dc2-54a1-4985-ae66-dd5aed20b601', '-', '')), 'who is kitty 2', '2', 'The name of kitty 2');








-- Bootstrap Category:
INSERT INTO category(categoryId, categoryProfileId, categoryName)
VALUES(UNHEX(REPLACE('ee37cc48-ab4b-4f91-9845-86efea8f4af4', '-', '')), UNHEX(REPLACE('C2954660-F27B-4557-8F2D-C68E8B2D8AA8', '-', '')), 'Bootstrap');

-- Bootstrap Cards 1:
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('8da0b37e-0f50-4237-b8e4-47c7ab717a54', '-', '')),UNHEX(REPLACE('ee37cc48-ab4b-4f91-9845-86efea8f4af4', '-', '')), 'What is 12?', '1', 'The number of columns in the Bootstrap Grid System');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('685a86b2-d2a8-4110-b4cf-514ee0df6a6f', '-', '')),UNHEX(REPLACE('ee37cc48-ab4b-4f91-9845-86efea8f4af4', '-', '')), 'What is .container or .container-fluid?', '1', 'The number of columns in the Bootstrap Grid System');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('5e42ef55-dfb4-4745-aae5-cbd08a1b4509', '-', '')),UNHEX(REPLACE('ee37cc48-ab4b-4f91-9845-86efea8f4af4', '-', '')),'What is jQuery and Popper.js?', '1', 'Bootstrap requires these two JavaScript dependencies in order for the all built-in JavaScript components to function properly');
-- Bootstrap Cards 2:
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('6b5f8d58-a4e0-4cac-b44f-c3a8a842ff65', '-', '')),UNHEX(REPLACE('ee37cc48-ab4b-4f91-9845-86efea8f4af4', '-', '')),'What is 768 - 991px?', '2', 'This is the range of screen widths for the MD breakpoint');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('2ec0ae72-61fc-45f3-bbec-87eddab2692a', '-', '')),UNHEX(REPLACE('ee37cc48-ab4b-4f91-9845-86efea8f4af4', '-', '')),'What is .container-fluid?', '2', 'Use this Bootstrap class to create a container that will fill the entire width of the viewport');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('8784e938-7016-4b6c-86b6-95103939ae94', '-', '')),UNHEX(REPLACE('ee37cc48-ab4b-4f91-9845-86efea8f4af4', '-', '')), 'What is .img-fluid?', '2', 'This Bootstrap class will scale an image proportionally within its parent element');
-- Bootstrap Cards 4:
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('d3d971c8-be30-4c7a-a3a9-d4ba1a393eaa', '-', '')),UNHEX(REPLACE('ee37cc48-ab4b-4f91-9845-86efea8f4af4', '-', '')), 'What is <576 px?', '4', 'This is the width range of the XS breakpoint');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('3f63ab67-57b9-457d-a8ad-2ab4b5d4c2e0', '-', '')),UNHEX(REPLACE('ee37cc48-ab4b-4f91-9845-86efea8f4af4', '-', '')), 'What are .btn and .btn-danger?', '4', 'place these two Bootstrap classes on a <button> tag to create a red button');
-- Bootstrap Cards 8:
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('a43c1014-4766-4598-91fe-8b8b5eebc270', '-', '')),UNHEX(REPLACE('ee37cc48-ab4b-4f91-9845-86efea8f4af4', '-', '')), 'What is .col-lg-6?', '8', 'This is the Bootstrap grid class to use to make an element 6 columns wide on screens that are larger than 991px wide');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('4b646a7c-6363-4942-9925-0315c5def1b0', '-', '')),UNHEX(REPLACE('ee37cc48-ab4b-4f91-9845-86efea8f4af4', '-', '')), 'What is  box-sizing: border-box;?', '8', 'Bootstrap applies this CSS rule to all elements, which changes the default CSS Box Model');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('a84cf6fc-74ab-46f3-ae2c-c21401a43ef7', '-', '')),UNHEX(REPLACE('ee37cc48-ab4b-4f91-9845-86efea8f4af4', '-', '')), 'What is 16px?', '8', 'This is Bootstrap\'s global default font-size');
-- Bootstrap Cards 16:
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('16d4cf1a-a3a3-4e3d-b9c2-62ffcb015fa0', '-', '')),UNHEX(REPLACE('ee37cc48-ab4b-4f91-9845-86efea8f4af4', '-', '')), 'What is 1140 px?', '16', 'This is the size of the fixed-width Bootstrap .container on screens 1200px wide and larger');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('6e42f90d-8281-4d8a-9ee7-95c7ae53fb64', '-', '')),UNHEX(REPLACE('ee37cc48-ab4b-4f91-9845-86efea8f4af4', '-', '')), 'What is .table?', '16', 'To enable basic Bootstrap table styling, add this class to the <table> tag');
-- Bootstrap Cards 32:
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('268c8315-128b-4011-8a23-98d05cb9de25', '-', '')),UNHEX(REPLACE('ee37cc48-ab4b-4f91-9845-86efea8f4af4', '-', '')), 'What is .form-control?', '32', 'Use this Bootstrap class to automatically style <input> form fields');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('e1bfb1e2-f06c-4121-979d-f8b7228f1745', '-', '')),UNHEX(REPLACE('ee37cc48-ab4b-4f91-9845-86efea8f4af4', '-', '')), 'What is 768-991px?', '32', 'This is the range of screen widths for the MD breakpoint);');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('bdbdf44a-6d7e-4824-88d9-8c5f3c9ca592', '-', '')),UNHEX(REPLACE('ee37cc48-ab4b-4f91-9845-86efea8f4af4', '-', '')), 'What is .sr-only?', '32', 'You would apply this Bootstrap class on HTML tags that contain content for screen readers only');

-- CSS Category:
INSERT INTO category(categoryId, categoryProfileId, categoryName)
VALUES(UNHEX(REPLACE('d0e530dd-54e0-46b7-9918-1ead8876fa9c', '-', '')), UNHEX(REPLACE('C2954660-F27B-4557-8F2D-C68E8B2D8AA8', '-', '')), 'CSS');

-- CSS Cards 1:
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('9c394792-be8c-4dc3-83c5-3faf05bbbe89', '-', '')),UNHEX(REPLACE('d0e530dd-54e0-46b7-9918-1ead8876fa9c', '-', '')),'What is margin?', '1', 'This is the outermost element in the CSS box model');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('bd327b70-ce40-4c14-a80a-491a1c539b72', '-', '')),UNHEX(REPLACE('d0e530dd-54e0-46b7-9918-1ead8876fa9c', '-', '')),'What is Selector?', '1', 'This part of a CSS rule targets which elements in your HTML document documents the style will apply to');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('794e87c3-29ad-41b9-a080-14b4c63320d7', '-', '')),UNHEX(REPLACE('d0e530dd-54e0-46b7-9918-1ead8876fa9c', '-', '')),'What is the Color attribute?', '1', 'This CSS property will alter the color of text');
-- CSS Cards 2:
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('4e298473-adf0-4e22-955a-e300c06a109a', '-', '')),UNHEX(REPLACE('d0e530dd-54e0-46b7-9918-1ead8876fa9c', '-', '')),'What is float: right;?', '2', 'This CSS rule will push an element as far as possible to the right inside its container, and allows other elements to wrap around it');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('4cd4a046-ab02-4c16-b8d9-fc6f2d13310d', '-', '')),UNHEX(REPLACE('d0e530dd-54e0-46b7-9918-1ead8876fa9c', '-', '')),'What is inline CSS?', '2', 'These kinds of CSS rules are the most specific, and will override any and all other conflicting style rules');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('4f79e8fb-0a4a-4885-9715-c097dbdea325', '-', '')),UNHEX(REPLACE('d0e530dd-54e0-46b7-9918-1ead8876fa9c', '-', '')),'What is CSS Specificity?', '2', 'This term refers to the rules that determine what CSS styles get applied based on how and where they are written');
-- CSS Cards 4:
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('23beab9f-5a8a-4ebb-b775-25c5e445280e', '-', '')),UNHEX(REPLACE('d0e530dd-54e0-46b7-9918-1ead8876fa9c', '-', '')),'What is the Alpha channel? (Opacity or Transparency also acceptable)', '4', 'The "a" in rgba and hsla color values stands for this');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('fb060eb7-8e62-4c42-ab04-4c3484985b97', '-', '')),UNHEX(REPLACE('d0e530dd-54e0-46b7-9918-1ead8876fa9c', '-', '')),'What is :last-child?', '4', 'This CSS pseudo-class will target any element that is the last child of its parent');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('796dd201-f79e-4faf-928b-48ff33a69619', '-', '')),UNHEX(REPLACE('d0e530dd-54e0-46b7-9918-1ead8876fa9c', '-', '')),'What are media queries?', '4', 'Using these, we can limit certain styles based on the media type and device characteristics, such as screen width');
-- CSS Cards 8:
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('d8f899cc-360c-4bfa-9aaa-c4fead7c42bb', '-', '')),UNHEX(REPLACE('d0e530dd-54e0-46b7-9918-1ead8876fa9c', '-', '')),'What is display: inline-block;?', '8', 'Setting this CSS display value will enable an element to sit inline, while at the same time preserving the box model margins and padding like that of a block element');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('41e0229c-806c-4d76-ba32-2ef98e1ea62f', '-', '')),UNHEX(REPLACE('d0e530dd-54e0-46b7-9918-1ead8876fa9c', '-', '')),'What is an Attribute Selector?', '8', 'This kind of CSS Selector allows you to target HTML elements with a specific attribute and/or value');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('eb9848bd-5020-467b-979c-25e55b9e126f', '-', '')),UNHEX(REPLACE('d0e530dd-54e0-46b7-9918-1ead8876fa9c', '-', '')),'What is clear: both; (or clearfix hack)?', '8', 'This CSS rule will clear all space on the left and right sides of block elements');
-- CSS Cards 16:
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('9be9d10b-08e7-4808-bc94-d09c44cdc534', '-', '')),UNHEX(REPLACE('d0e530dd-54e0-46b7-9918-1ead8876fa9c', '-', '')),'What is position: static;?', '16', 'This is the default CSS position for all HTML elements');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('68546015-e8cd-446a-ad31-262271a493c6', '-', '')),UNHEX(REPLACE('d0e530dd-54e0-46b7-9918-1ead8876fa9c', '-', '')),'What are user-agent styles or user-agent stylesheets?', '16', 'This is the technical term for browser default styles');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('fcddadff-9ad5-4aa6-9c95-8bc4a0c6927f', '-', '')),UNHEX(REPLACE('d0e530dd-54e0-46b7-9918-1ead8876fa9c', '-', '')),'What is box-sizing: border-box?', '16', 'This CSS rule changes the default box model by moving the border and padding to the inside of an element\'s box');
-- CSS Cards 32:
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('69069085-202a-47d2-b6ae-e0e0e2f1ae9c', '-', '')),UNHEX(REPLACE('d0e530dd-54e0-46b7-9918-1ead8876fa9c', '-', '')),'What is :nth-child(3)?', '32', 'This CSS pseudo-class will target any element that is the 3rd child of its parent');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('1152d15c-ec4d-4e73-b137-fce9fcdb1080', '-', '')),UNHEX(REPLACE('d0e530dd-54e0-46b7-9918-1ead8876fa9c', '-', '')),'What is z-index?', '32', 'This CSS property specifies the stack order of elements that are positioned absolute, relative, or fixed');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('72dbce57-9cf1-4005-bf2a-1350a05015f7', '-', '')),UNHEX(REPLACE('d0e530dd-54e0-46b7-9918-1ead8876fa9c', '-', '')),'What are inline elements?', '32', 'On these kinds of HTML elements, vertical margins, padding and borders will NOT push away other elements above or below');

-- UX Category:
INSERT INTO category(categoryId, categoryProfileId, categoryName)
VALUES(UNHEX(REPLACE('a73145d8-4253-451e-a3b8-ee963cc2ae89', '-', '')), UNHEX(REPLACE('C2954660-F27B-4557-8F2D-C68E8B2D8AA8', '-', '')), 'UX');
-- UX Class 1:
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('19063b93-c2bb-4752-b563-e8ed399cad4f', '-', '')),UNHEX(REPLACE('a73145d8-4253-451e-a3b8-ee963cc2ae89', '-', '')),'What is UX or User Experience?', '1', 'This term/acronym refers to all aspects of an end-user\'s interaction with a product, system, or service');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('6afb205d-2b7d-489b-9430-2fc5796fd587', '-', '')),UNHEX(REPLACE('a73145d8-4253-451e-a3b8-ee963cc2ae89', '-', '')),'What is a persona?', '1', 'This term refers to a hypothetical model of a user of your product');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('55869cff-e632-4255-843d-526a5bfe300e', '-', '')),UNHEX(REPLACE('a73145d8-4253-451e-a3b8-ee963cc2ae89', '-', '')),'What is a Use Case?', '1', 'This term refers to a model of how, when, where a user will interact with your product');
-- UX Class 2:
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('a5588577-0223-41e5-90df-632d655ac670', '-', '')),UNHEX(REPLACE('a73145d8-4253-451e-a3b8-ee963cc2ae89', '-', '')),'What is a UI or User Interface?', '2', 'This term or acronym refers to the graphical elements of your site or app that a user sees and interacts with on the screen');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('6fd4ff28-6625-43ef-bf10-9e603c4b0442', '-', '')),UNHEX(REPLACE('a73145d8-4253-451e-a3b8-ee963cc2ae89', '-', '')),'What is Ease of Use or Usability?', '2', 'This term refers to the ease with which a user can employ a tool, product or service to achieve a specific goal');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('9c2d1390-7e5b-47ab-88b0-c6986c7938b9', '-', '')),UNHEX(REPLACE('a73145d8-4253-451e-a3b8-ee963cc2ae89', '-', '')),'What is MVP or Minimum Viable Product?', '2', 'This term or acronym refers to a product development strategy where a new product is developed with only sufficient features necessary to satisfy early adopters');
-- UX Class 4:
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('10c0259c-2b90-4a05-8b02-0fc6daca3304', '-', '')),UNHEX(REPLACE('a73145d8-4253-451e-a3b8-ee963cc2ae89', '-', '')),'What is Responsive Web Design/Development? (NOTE: Mobile First is NOT Acceptable answer?', '4', 'This term refers to the design and development of websites that scale according to screen size of the device they are accessed with');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('e149d3ca-2a9e-4a5d-9447-b3c0751d8840', '-', '')),UNHEX(REPLACE('a73145d8-4253-451e-a3b8-ee963cc2ae89', '-', '')),'What is target market, existing users, or early adopters?(other acceptable answers: Market Research Data, Analytics Data?', '4', 'An ideal persona should be created based upon this');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('a55d18ba-b8c6-4e9b-b4c5-4f5325cb70b4', '-', '')),UNHEX(REPLACE('a73145d8-4253-451e-a3b8-ee963cc2ae89', '-', '')),'Who is EVERYONE on the team?', '4', 'hese are the individuals on a web development team that are responsible for the UX of a website or application');




-- UX Cards 8:
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('c3b6bbd1-7650-434f-90e0-623ac1814fc2', '-', '')),UNHEX(REPLACE('a73145d8-4253-451e-a3b8-ee963cc2ae89', '-', '')),'What is Mobile-First Web Development?', '8', 'This term refers to a web development approach where the bare essentials of a website are loaded for smaller platforms first, before adding content, features and functionality for larger devices on an as-needed secondary basis');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('a5637b23-cbec-4b60-8613-69d25f403870', '-', '')),UNHEX(REPLACE('a73145d8-4253-451e-a3b8-ee963cc2ae89', '-', '')),'What is Wireframing?', '8', 'This term refers to the process where one begins to plan out the organization and layout of the UI elements in black and white, prior to the graphic design of the website or application');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('bd08aef3-e265-4e50-83b9-5760de10b6bf', '-', '')),UNHEX(REPLACE('a73145d8-4253-451e-a3b8-ee963cc2ae89', '-', '')),'What is Sitemapping?', '8', 'This process involves creating a graphical outline that details the grouping and hierarchical structure of the pages of a website or app');
-- UX Class 16:
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('97c4b11b-cb72-4195-a9e4-cca66706d3a0', '-', '')),UNHEX(REPLACE('a73145d8-4253-451e-a3b8-ee963cc2ae89', '-', '')),'What is Progressive Enhancement?', '16', 'This term refers to the addition of content, features and functionality to a website as it scales up for larger platforms');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('219d4bfa-7484-4486-bf23-3f4f741f4d12', '-', '')),UNHEX(REPLACE('a73145d8-4253-451e-a3b8-ee963cc2ae89', '-', '')),'What is Graceful Degradation?', '16', 'This term refers to the addition of content, features and functionality to a website as it scales down for small platforms');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('4144461d-c169-43b6-9175-25c4dc11352f', '-', '')),UNHEX(REPLACE('a73145d8-4253-451e-a3b8-ee963cc2ae89', '-', '')),'What is IxD, or Interaction Design?', '16', ' subset of UX design, this term or acronym refers specifically to designing human-computer interactions');
-- UX Cards 32:
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('bae855b7-7e4f-42de-89f6-abdffaea78f6', '-', '')),UNHEX(REPLACE('a73145d8-4253-451e-a3b8-ee963cc2ae89', '-', '')),'What is A/B testing?', '32', 'This term refers to the process of testing two different versions of the same website or app, and gathering user interaction data to determine which one performs best');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('2d132161-c40b-4811-ba59-e8f67495253f', '-', '')),UNHEX(REPLACE('a73145d8-4253-451e-a3b8-ee963cc2ae89', '-', '')),'What is a Wireframe?', '32', 'This term refers to a visual representation of a user interface that is used to define the hierarchy of content on a screen');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('63af30c7-0929-458f-a088-5bff03f8690c', '-', '')),UNHEX(REPLACE('a73145d8-4253-451e-a3b8-ee963cc2ae89', '-', '')),'What is MVP or Minimum Viable Product?', '32', 'This term refers to the process of testing two different versions of the same website or app, and gathering user interaction data to determine which one performs best');


-- HTML Category:
INSERT INTO category(categoryId, categoryProfileId, categoryName)
VALUES(UNHEX(REPLACE('f1ec3a33-e758-481e-8139-506ae284f5d3', '-', '')), UNHEX(REPLACE('C2954660-F27B-4557-8F2D-C68E8B2D8AA8', '-', '')), 'HTML');
-- HTML Cards 1:
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('b02f8a4a-9d81-4c53-8a28-21d26b071e2c', '-', '')),UNHEX(REPLACE('f1ec3a33-e758-481e-8139-506ae284f5d3', '-', '')),'What is Hypertext Markup Language?', '1', 'This is what the acronym HTML stands for');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('c7f28e0b-eb09-49ff-af39-251a540969c2', '-', '')),UNHEX(REPLACE('f1ec3a33-e758-481e-8139-506ae284f5d3', '-', '')),'What are ids?', '1', 'These types of HTML attributes are used to uniquely identify an element and their values can only occur once per page');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('4fd04296-234c-4e06-b95a-a5dd78b5c485', '-', '')),UNHEX(REPLACE('f1ec3a33-e758-481e-8139-506ae284f5d3', '-', '')),'What is a <div>?', '1', 'This HTML tag is a generic block container element');
-- HTML Cards 2:
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('a6538f32-bf7d-46dd-bb98-ab16c0450ea8', '-', '')),UNHEX(REPLACE('f1ec3a33-e758-481e-8139-506ae284f5d3', '-', '')),'What is a <span> tag?', '2', 'This HTML tag is a generic inline container element');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('97b77690-8df3-4c86-8a76-f9bf27370365', '-', '')),UNHEX(REPLACE('f1ec3a33-e758-481e-8139-506ae284f5d3', '-', '')),'What is the <nav> tag?', '2', 'This HTML tag is used to house links that function as page navigation');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('e5f6b02c-673b-43fb-ac94-47d8acedab98', '-', '')),UNHEX(REPLACE('f1ec3a33-e758-481e-8139-506ae284f5d3', '-', '')),'What is Doctype Declaration, or <!DOCTYPE html>?', '2', 'laced at the top of every HTML document, this line of code tells the browser what version of HTML the document is written in');
-- HTML Cards 4:
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('e6faa36e-d6b2-4539-8454-21ed5ea8dd64', '-', '')),UNHEX(REPLACE('f1ec3a33-e758-481e-8139-506ae284f5d3', '-', '')),'Who is Tim Berners-Lee?', '4', 'The inventor of HTML');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('f827c940-a54c-4637-9dd5-5796fee4a132', '-', '')),UNHEX(REPLACE('f1ec3a33-e758-481e-8139-506ae284f5d3', '-', '')),'What is the <title> tag?', '4', 'Text inside this HTML tag will appear inside a browser tab right next to the favicon');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('d80d9f35-de02-46f2-ad1d-12c88dc02e62', '-', '')),UNHEX(REPLACE('f1ec3a33-e758-481e-8139-506ae284f5d3', '-', '')),'What is the <textarea> tag?', '4', 'Used in forms, this HTML tag creates a multiline text input area');
-- HTML Cards 8:
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('cc34f8f8-8024-4b3f-a696-72bbd2236499', '-', '')),UNHEX(REPLACE('f1ec3a33-e758-481e-8139-506ae284f5d3', '-', '')),'What is the alt attribute?', '8', 'This attribute is required on all image tags, and provides alternate text for screen readers');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('0477abb6-454d-4ec5-9280-5d12dad80faa', '-', '')),UNHEX(REPLACE('f1ec3a33-e758-481e-8139-506ae284f5d3', '-', '')),'What is &amp;?', '8', 'This is the HTML entity code for the "&" symbol');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('c562643c-967e-47e7-9ae6-cd8f60d13e51', '-', '')),UNHEX(REPLACE('f1ec3a33-e758-481e-8139-506ae284f5d3', '-', '')),'What is the <pre> tag?', '8', 'This HTML tag preserves all spaces and line breaks in text content exactly as they are typed');
-- HTML CARDS 16:
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('8856ed52-3ccf-4591-bff3-fdf74fbdbe8b', '-', '')),UNHEX(REPLACE('f1ec3a33-e758-481e-8139-506ae284f5d3', '-', '')),'What is the rel attribute?', '16', 'This attribute, which is required when linking to CSS stylesheets, specifies the relationship between the current document and the linked resource');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('3dbb1324-87c0-40e9-9dd3-1e09bed10ae2', '-', '')),UNHEX(REPLACE('f1ec3a33-e758-481e-8139-506ae284f5d3', '-', '')),'What is the DOM or Document Object Model?', '16', 'This model maps the hierarchy of the elements on a web page and provides a programming interface for HTML');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('2f41f7f4-3489-4849-a851-1540ddbc1340', '-', '')),UNHEX(REPLACE('f1ec3a33-e758-481e-8139-506ae284f5d3', '-', '')),'What is the <meta name="viewport".../> tag?', '16', 'This specific HTML tag tells the browser to set the width of the viewport to the width of the device');
-- HTML Cards 32:
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('ef7acd35-6d1c-4704-aae8-229037b35545', '-', '')),UNHEX(REPLACE('f1ec3a33-e758-481e-8139-506ae284f5d3', '-', '')),'What are custom data attributes?', '32', 'These kinds of HTML attributes are used to store custom data private to the page or application');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('f32e0011-d372-4661-a680-b656c050af2f', '-', '')),UNHEX(REPLACE('f1ec3a33-e758-481e-8139-506ae284f5d3', '-', '')),'What is the <canvas> tag??', '32', 'Use this HTML tag as a container for drawing graphics using JavaScript');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX (REPLACE('69e94d29-999c-435b-a984-23336612b316', '-', '')),UNHEX(REPLACE('f1ec3a33-e758-481e-8139-506ae284f5d3', '-', '')),'What is the <marquee> tag?', '32', 'This obsolete HTML tag was used in the 90s to create a scrolling line of text');





-- Angular Category:
INSERT INTO category(categoryId, categoryProfileId, categoryName)
VALUES(UNHEX(REPLACE('c4ae08ef-c319-4fd3-9b4a-c0388fa4725f', '-', '')), UNHEX(REPLACE('C2954660-F27B-4557-8F2D-C68E8B2D8AA8', '-', '')), 'Angular');
-- Angular
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('b1a27888-dd64-4239-8ebd-eaa4a5b41bf3', '-', '')), UNHEX(REPLACE('c4ae08ef-c319-4fd3-9b4a-c0388fa4725f', '-', '')), 'What is TypeScript?', 1, 'Angular primarily uses this language');
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('26e4d3d0-1907-4174-acf0-d97c8b25cb48', '-', '')), UNHEX(REPLACE('c4ae08ef-c319-4fd3-9b4a-c0388fa4725f', '-', '')), 'What is webpack?', 1, 'Task runner used to package Angular code');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('d8da3a95-c137-4410-9adb-4e06639aca37', '-', '')), UNHEX(REPLACE('c4ae08ef-c319-4fd3-9b4a-c0388fa4725f', '-', '')), 'What is an observer?', 2, 'Design pattern embraced by Angular for REST calls or a spectator at an event');
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('14d99a49-3ec7-418c-9a99-e859d2063888', '-', '')), UNHEX(REPLACE('c4ae08ef-c319-4fd3-9b4a-c0388fa4725f', '-', '')), 'What is Google?', 2, 'Company that bankrolls the Angular project');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('1a82123a-531f-4516-bc3f-15f46e019f77', '-', '')), UNHEX(REPLACE('c4ae08ef-c319-4fd3-9b4a-c0388fa4725f', '-', '')), 'What is a decorator?', 4, 'Denoted by an @ character, this will delegate additional responsibilities to an Angular class or who you call to spruce up a room');
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('c9a303aa-80ce-4dac-aa87-b01ea1f5d98f', '-', '')), UNHEX(REPLACE('c4ae08ef-c319-4fd3-9b4a-c0388fa4725f', '-', '')), 'What is HttpClient?', 4, 'Angular\'s AJAX runner is called this');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('143c0cce-258f-4c5d-9294-ca2ee3b98720', '-', '')), UNHEX(REPLACE('c4ae08ef-c319-4fd3-9b4a-c0388fa4725f', '-', '')), 'What is an interceptor?', 8, 'Create one of these to customize HttpClient\'s handling of incoming or outgoing AJAX calls');
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('99553f9b-c91a-4277-808c-e654f1ee6b0b', '-', '')), UNHEX(REPLACE('c4ae08ef-c319-4fd3-9b4a-c0388fa4725f', '-', '')), 'What is banana in a box?', 8, 'Fruity packaged metaphor for two way binding, which Angular uses to sync the DOM and component automatically');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('1a88203a-c164-4b1e-af7f-9cb10b112f8c', '-', '')), UNHEX(REPLACE('c4ae08ef-c319-4fd3-9b4a-c0388fa4725f', '-', '')), 'What is one way binding?', 16, 'A conduit through which data can flow from one component to another');
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('45c78f1e-cf6d-4e5c-9c4b-fc99c7adfc36', '-', '')), UNHEX(REPLACE('c4ae08ef-c319-4fd3-9b4a-c0388fa4725f', '-', '')), 'What is ViewChild?', 16, 'This decorator povides Angular direct access to a dependent component or a kindergartener looking in a mirror');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('b4c2bc34-318d-413b-8469-47a0f1642950', '-', '')), UNHEX(REPLACE('c4ae08ef-c319-4fd3-9b4a-c0388fa4725f', '-', '')), 'What is BehaviorSubject?', 32, 'Use this class to encapsulate data consumed by a local observer');
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('871b6d93-c5e0-4e48-a2e4-926001382ab3', '-', '')), UNHEX(REPLACE('c4ae08ef-c319-4fd3-9b4a-c0388fa4725f', '-', '')), 'What is EventEmitter?', 32, 'Use this one way binding to send data from a child component to be received as an event in the parent component');

-- Object Oriented Category:
INSERT INTO category(categoryId, categoryProfileId, categoryName)
VALUES(UNHEX(REPLACE('1c444979-6c4e-497d-8381-5b6dd49c29c6', '-', '')), UNHEX(REPLACE('C2954660-F27B-4557-8F2D-C68E8B2D8AA8', '-', '')), 'Object Oriented');

-- Object Oriented
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('d863628d-79fe-4920-a5fc-7ca363417b26', '-', '')), UNHEX(REPLACE('1c444979-6c4e-497d-8381-5b6dd49c29c6', '-', '')), 'What is class?', 1, 'The first word of every object oriented class');
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('8e9330fa-7d5a-48c6-9aad-4c516b5f0365', '-', '')), UNHEX(REPLACE('1c444979-6c4e-497d-8381-5b6dd49c29c6', '-', '')), 'What is a method?', 1, 'A function within a class');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('026613bb-a111-4af2-982f-fdb5a914397f', '-', '')), UNHEX(REPLACE('1c444979-6c4e-497d-8381-5b6dd49c29c6', '-', '')), 'What is an accessor method?', 2, 'A method that provides read only access to a state variable');
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('ac1a8ee2-2527-480b-bde1-00dcb84eec94', '-', '')), UNHEX(REPLACE('1c444979-6c4e-497d-8381-5b6dd49c29c6', '-', '')), 'What is a mutator method?', 2, 'A method that provides limited write access to a state variable');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('8af89b94-1cd3-482a-8764-ce08a2072f56', '-', '')), UNHEX(REPLACE('1c444979-6c4e-497d-8381-5b6dd49c29c6', '-', '')), 'What is state?', 4, 'An object\'s internal variables or New Mexico');
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('1385cb44-7f79-458c-b8ed-600be9c9ba23', '-', '')), UNHEX(REPLACE('1c444979-6c4e-497d-8381-5b6dd49c29c6', '-', '')), 'Who are the Gang of 4?', 4, 'Authors of Deign Patterns: Elements of Reusable Software that had no place in Chinese history');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('560cc290-1046-44e5-ae32-fd4fa950f620', '-', '')), UNHEX(REPLACE('1c444979-6c4e-497d-8381-5b6dd49c29c6', '-', '')), 'What is an interface?', 8, 'A code block with empty methods meant to enforce consistent behavior among implementing classes');
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('531a22b5-9006-49b2-bcac-67c6b5553872', '-', '')), UNHEX(REPLACE('1c444979-6c4e-497d-8381-5b6dd49c29c6', '-', '')), 'What is __construct?', 8, 'Implement this method to customize the behavior of the new keyword');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('882ea1de-d1af-4adf-b1ff-88eca229f30f', '-', '')), UNHEX(REPLACE('1c444979-6c4e-497d-8381-5b6dd49c29c6', '-', '')), 'What is a contract?', 16, 'Java term for implementing a class to conform to certain standards or a legal agreement');
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('ef94caa9-b871-4e07-9e77-e329d1fc203a', '-', '')), UNHEX(REPLACE('1c444979-6c4e-497d-8381-5b6dd49c29c6', '-', '')), 'What is the diamond problem?', 16, 'A problem of conflicting methods from multiple inheritance that can be caused in PHP by importing multiple traits with the same method name');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('3fcac15c-f681-4349-98d3-5a98e359bc5b', '-', '')), UNHEX(REPLACE('1c444979-6c4e-497d-8381-5b6dd49c29c6', '-', '')), 'What are O(n log(n)) and O(n**2)?', 32, 'Average and worse case run time for quicksort');
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('31c460a6-ec4b-4627-bc88-85cc1a904857', '-', '')), UNHEX(REPLACE('1c444979-6c4e-497d-8381-5b6dd49c29c6', '-', '')), 'What is factory?', 32, 'This design pattern uses a single interface for object creation implemented by the children or a large source of pollution in an industrial area');

-- Star Trek Category:
INSERT INTO category(categoryId, categoryProfileId, categoryName)
VALUES(UNHEX(REPLACE('e15773ae-32bd-4a1f-b4da-6899168af96a', '-', '')), UNHEX(REPLACE('C2954660-F27B-4557-8F2D-C68E8B2D8AA8', '-', '')), 'Star Trek');
-- Star Trek


INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('6c999243-ddb9-4f81-9d0a-034a110b13f6', '-', '')), UNHEX(REPLACE('e15773ae-32bd-4a1f-b4da-6899168af96a', '-', '')), 'Who is Jean-Luc Picard?', 1, 'Captain of the USS Enterprise NCC-1701-D');
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('b3d56a5b-7f5b-4232-874c-2d71d5181103', '-', '')), UNHEX(REPLACE('e15773ae-32bd-4a1f-b4da-6899168af96a', '-', '')), 'What is the borg?', 1, 'Species that wanders the galaxy assimilating races declaring resistance is futile');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('81505ca2-8a60-4c46-abe8-fa66b27e234c', '-', '')), UNHEX(REPLACE('e15773ae-32bd-4a1f-b4da-6899168af96a', '-', '')), 'What is Q?', 2, 'Omnipotent Star Trek entity that can fit in a CHAR(1) field');
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('f4f3bc16-f212-4b24-af0e-d7c47f15350c', '-', '')), UNHEX(REPLACE('e15773ae-32bd-4a1f-b4da-6899168af96a', '-', '')), 'Who is Kathryn Janeway?', 2, 'Captain of USS Voyager');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('7e4ef954-76aa-4432-9a20-5b2d86b206cf', '-', '')), UNHEX(REPLACE('e15773ae-32bd-4a1f-b4da-6899168af96a', '-', '')), 'What is sovereign?', 4, 'Class of the USS Enterprise NCC-1701-E or a term for an independent nation');
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('126385de-5bb9-416a-bb85-92e4c6414753', '-', '')), UNHEX(REPLACE('e15773ae-32bd-4a1f-b4da-6899168af96a', '-', '')), 'Who are Betazoids?', 4, 'Star Trek race known for empathic abilities and black eyes; for example, Lwaxana Troi');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('36fd284d-1a49-4304-8905-323b0f2b17f7', '-', '')), UNHEX(REPLACE('e15773ae-32bd-4a1f-b4da-6899168af96a', '-', '')), 'Who are the Kazon?', 8, 'Known to the borg as species 329 and unworthy of assimilation, this is the first species to oppose Captain Janeway');
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('550a0c8c-e003-4359-87b0-6ed841f97bf6', '-', '')), UNHEX(REPLACE('e15773ae-32bd-4a1f-b4da-6899168af96a', '-', '')), 'What is Wolf 359?', 8, 'Planet where the Borg massacred 39 federation ships');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('7ed5b57a-d683-4163-836a-f5917885007d', '-', '')), UNHEX(REPLACE('e15773ae-32bd-4a1f-b4da-6899168af96a', '-', '')), 'WHAT IS THERE ARE FOUR LIGHTS?', 16, 'The number of lights in the room Jean-Luc Picard was interrogated in');
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('69798b58-48eb-4cf5-ae64-4c1b399e55de', '-', '')), UNHEX(REPLACE('e15773ae-32bd-4a1f-b4da-6899168af96a', '-', '')), 'What is poker?', 16, 'Card game favored by both the authors of this capstone and Will Riker');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('22d8f8ec-1df7-4b78-88f8-103145e9f857', '-', '')), UNHEX(REPLACE('e15773ae-32bd-4a1f-b4da-6899168af96a', '-', '')), 'What is poetry?', 32, 'Artistic prose that both chief engineers B\'Elanna Torres and Geordi La Forge have in common');
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('9f131ba9-8e1d-4ae1-9959-2b7c22f3fac0', '-', '')), UNHEX(REPLACE('e15773ae-32bd-4a1f-b4da-6899168af96a', '-', '')), 'What is Negh\'Var?', 32, 'Class of ship for the flagship of the Klingon Empire');

-- People Category:
INSERT INTO category(categoryId, categoryProfileId, categoryName)
VALUES(UNHEX(REPLACE('c2a7af6e-e2b5-45fe-8c1e-b7300f85852c', '-', '')), UNHEX(REPLACE('C2954660-F27B-4557-8F2D-C68E8B2D8AA8', '-', '')), 'People');
-- People
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('49395863-f664-4de0-8498-c37375a4a72d', '-', '')), UNHEX(REPLACE('c2a7af6e-e2b5-45fe-8c1e-b7300f85852c', '-', '')), 'Who is Senator Arlo?', 1, 'The only Romulan Senator with nine lives');
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('01cd832f-a436-4bab-9b9a-d7bb2d35ab98', '-', '')), UNHEX(REPLACE('c2a7af6e-e2b5-45fe-8c1e-b7300f85852c', '-', '')), 'Who is John Mierzwa?', 1, 'Founder of Deep Dive Coding and Emperor of Emperors');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('8fa12ae4-32cc-4d40-a31c-04e3fd4c4e53', '-', '')), UNHEX(REPLACE('c2a7af6e-e2b5-45fe-8c1e-b7300f85852c', '-', '')), 'Who is Sir Tim Berners-Lee?', 2, 'Inventor of HTML who opened the 2012 London Olympics by tweeting \'this is for you\'');
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('6c6c839f-ac71-4514-9f6b-fa64ddbf9355', '-', '')), UNHEX(REPLACE('c2a7af6e-e2b5-45fe-8c1e-b7300f85852c', '-', '')), 'Who is Richard Stallman?', 2, 'Founder of the Free Software Foundation who is often made fun of in the web comic XKCD');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('44bfd68c-9066-49db-b911-d68853fc1831', '-', '')), UNHEX(REPLACE('c2a7af6e-e2b5-45fe-8c1e-b7300f85852c', '-', '')), 'Who are Dennis Ritchie and Ken Thompson?', 4, 'Inventors of C and Unix');
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('a0b6f676-3cf0-4270-8de1-7640863036bf', '-', '')), UNHEX(REPLACE('c2a7af6e-e2b5-45fe-8c1e-b7300f85852c', '-', '')), 'Who is Eric S Raymond?', 4, 'Author of the Cathedral on the Bazaar');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('f125c389-8435-4fe3-9e23-fae89a374732', '-', '')), UNHEX(REPLACE('c2a7af6e-e2b5-45fe-8c1e-b7300f85852c', '-', '')), 'Who is Jon Stewart?', 8, 'First Daily Show alumni to raise awareness of net neutrality on the show');
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('f2a862be-c333-4601-998c-7540fb3001b2', '-', '')), UNHEX(REPLACE('c2a7af6e-e2b5-45fe-8c1e-b7300f85852c', '-', '')), 'Who is Daniel Heron?', 8, 'Biggest Polyglot in the CNM STEMulus Center');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('fe21f736-65cd-4bc8-b8b2-1b6f06814c07', '-', '')), UNHEX(REPLACE('c2a7af6e-e2b5-45fe-8c1e-b7300f85852c', '-', '')), 'Who is James Barksdale?', 16, 'CEO of Netscape who testified in congress in the Microsoft antitrust trial');
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('0f71d861-7064-4a81-b727-1ff0fa6b817c', '-', '')), UNHEX(REPLACE('c2a7af6e-e2b5-45fe-8c1e-b7300f85852c', '-', '')), 'Who is Steve Ballmer?', 16, 'Chair throwing former Microsoft CEO with his own \'peak\'');

INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('c3d15b55-4cb6-40ce-9a43-2101b90587c7', '-', '')), UNHEX(REPLACE('c2a7af6e-e2b5-45fe-8c1e-b7300f85852c', '-', '')), 'Who is Ajit Pai?', 32, 'Reeses loving, net neutrality hating chairman');
INSERT INTO card(cardId, cardCategoryId, cardAnswer, cardPoints, cardQuestion)
VALUES (UNHEX(REPLACE('2d070b7e-193f-48fa-8328-a7def2c5d8ef', '-', '')), UNHEX(REPLACE('c2a7af6e-e2b5-45fe-8c1e-b7300f85852c', '-', '')), 'Who is Paul Vixie?', 32, '2014 Internet Hall of Fame inductee known for his work on DNS and anitspam measures');

-- Deletions:
DELETE FROM card
WHERE cardCategoryId= UNHEX(REPLACE('30d28a7e-192b-4f54-b97d-ba6166eb7433', '-', ''));

DELETE FROM card
WHERE cardCategoryId= UNHEX(REPLACE('4e7fc1e6-3c3b-43d7-9a7e-6b97b25e6593', '-', ''));