INSERT INTO categories (character_code, category_name) 
VALUES 
	('boards', 'Доски и лыжи'),
	('attachment', 'Крепления'),
	('boots', 'Ботинки'),
	('clothing', 'Одежда'),
	('tools', 'Инструменты'),
	('other', 'Разное'); 


INSERT INTO users (email, user_name, user_password, contacts) 
VALUES 
	('antoha255@mail.ru', 'antuan77', 'dskdnnn&d03FG', '+79151000104'),
	('kat-bisquit151090@rambler.ru', 'Katerina', 'cat39W@_&dk', '+79773058994');

INSERT INTO lots (title, description, image, start_price, date_finish, step, user_id, category_id)
VALUES
	('2014 Rossignol District Snowboard', 'Легкий маневренный сноуборд, готовый дать жару на любой горке', 'img/lot-1.jpg',10999 , '2023-01-31 15:00', 600, 1, 1),
	('DC Ply Mens 2016/2017 Snowboard', 'Легкий маневренный сноуборд ждет своего героя', 'img/lot-2.jpg', 159999, '2023-02-01 15:00', 1000, 1, 1),
	('Крепления Union Contact Pro 2015 года размер L/XL', 'Суперкрепления для супергонщиков', 'img/lot-3.jpg', 8000, '2023-02-08 15:00', 150, 2, 2),
	('Ботинки для сноуборда DC Mutiny Charocal', 'Подходит для стильных молодых людей!', 'img/lot-4.jpg', 10999, '2023-02-10 15:00', 500, 2, 3),
	('Куртка для сноуборда DC Mutiny Charocal', 'Прекрасная куртка! Тебя заметят даже в темноте!', 'img/lot-5.jpg', 7500, '2023-03-11 15:00', 250, 1, 4),
	('Маска Oakley Canopy', 'Ты будешь дышать даже с закрытым лицом', 'img/lot-6.jpg', 5400, '2023-04-23 15:00', 100, 1, 6);


INSERT INTO bets (price, user_id, lot_id)
VALUES 
	(8000, 2, 5),
	(8150, 1, 3);

--INSERT INTO users (email, user_name, user_password, contacts) 
--VALUES 
--('jorik@mail.ru', 'Gorge7', 'Jdkjju349_kj34&?', '+79634570123');



-- Получаем все категории --
SELECT category_name AS 'Категории' FROM categories;

--получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории--

SELECT lots.id, lots.title, lots.start_price, lots.image, bets.price, categories.category_name
FROM lots JOIN bets ON bets.lot_id = lots.id
JOIN categories ON lots.category_id = categories.id;

--показать лот по его ID. Получите также название категории, к которой принадлежит лот;--

SELECT lots.id, lots.date_creation, lots.title, lots.description, lots.image, lots.start_price, lots.date_finish, lots.step, categories.category_name 
FROM lots JOIN categories ON lots.category_id = categories.id 
WHERE lots.id = 4;

--обновить название лота по его идентификатору--

UPDATE lots SET lots.title = 'Маска NewAge' WHERE lots.id = 6;
SELECT title FROM lots WHERE id = 6;


--получить список ставок для лота по его идентификатору с сортировкой по дате.--

SELECT bets.date_create, bets.price, lots.title, users.user_name
FROM bets
JOIN users ON users.id = bets.user_id
JOIN lots ON lots.id = bets.lot_id
WHERE lots.id = 5 
ORDER BY bets.date_create DESC; 

