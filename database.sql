CREATE DATABASE IF NOT EXISTS trendyol_clone;
USE trendyol_clone;


CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);


CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    category VARCHAR(50),
    brand VARCHAR(50),
    image_url VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    image_url VARCHAR(255),
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS basket (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    product_id INT,
    quantity INT DEFAULT 1,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

INSERT INTO users (username, password) VALUES ('admin', 'password123');
INSERT INTO users (username, password) VALUES ('user', '12345');


INSERT INTO products (name, description, price, category, brand, image_url) VALUES 
('Double Duvet Cover Set', '100% Cotton, High quality print.', 450.00, 'Bedroom', 'English Home', 'https://m.media-amazon.com/images/I/81giY-nZGSL._AC_UF894,1000_QL80_.jpg'),
('Porcelain Dinner Set 24 Pieces', 'Elegant design for 6 people.', 1200.00, 'Kitchen', 'Karaca', 'https://m.media-amazon.com/images/I/61gPHclYMPL._AC_UF1000,1000_QL80_.jpg'),
('Decorative Metal Wall Clock', 'Modern minimalist design, black metal.', 350.50, 'Decoration', 'Mudo Concept', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSBbDmiCo-9bZHATJQokDKKGwZ5YDHaExvPCw&s'),
('Bamboo Laundry Basket', 'Foldable, eco-friendly bamboo.', 200.00, 'Bathroom', 'Madame Coco', 'https://m.media-amazon.com/images/I/71IPxsjVuFL.jpg'),
('Velvet Cushion Cover Set', 'Soft velvet touch, 4 pieces.', 150.00, 'Textile', 'Trendyol Home', 'https://m.media-amazon.com/images/I/61CJbQolcHL._AC_UF1000,1000_QL80_.jpg'),
('Ceramic Vase', 'Handmade ceramic vase, white.', 99.90, 'Decoration', 'Chakra', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQynu89Mmav4guYZ89ukBYJm8MYLUQ1eEPG8w&s'),
('Turkish Coffee Machine', 'Automatic coffee maker, 4 cups capacity.', 899.00, 'Electronics', 'Arzum', 'https://images.zoodmall.com/cloudinary/87/9f9/https%253A%252F%252Fuae.geepas.com%252Fmedia%252Fcatalog%252Fproduct%252Fcache%252F458bb0c1b3b8625d9889d3c5660fbe40%252Fg%252Fc%252Fgcm41517_3_xaiij5i.jpg'),
('Scented Candle Large', 'Vanilla and Caramel scent, 3 wicks.', 220.00, 'Decoration', 'Yankee Candle', 'https://m.media-amazon.com/images/I/81HSydTuXVL.jpg'),
('Orthopedic Pillow', 'Memory foam support pillow.', 300.00, 'Bedroom', 'Yataş', 'https://www.duroflexworld.com/cdn/shop/files/1_8505131f-ba06-47c7-911b-45e342aa337d.jpg?v=1758868886'),
('Glass Water Carafe', '1.5 Liters borosilicate glass.', 85.00, 'Kitchen', 'Paşabahçe', 'https://image.made-in-china.com/2f0j00MEaftCOBhJrv/1-1-5L-Pyrex-Borosilicate-Glass-Water-Pitcher-Jug-Carafe-with-Stainless-Steel-Lid.webp'),
('Floor Lamp', 'Wooden tripod floor lamp.', 550.00, 'Lighting', 'Koçtaş', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTlBL_yA3tNWCHH_Xz1nnobhdG0C0vpMzqANw&s'),
('Kitchen Towel Set', '100% Cotton, 3 pieces.', 60.00, 'Kitchen', 'Özdilek', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQylBB9kQ7RDx31tT7eklZQqDMURmb4bpixOw&s'),
('Storage Box Set', 'Plastic organizers, set of 5.', 110.00, 'Organization', 'Ikea', 'https://image-ikea.mncdn.com/urunler/500_500/PE920330.jpg'),
('Bath Mat Set', 'Non-slip backing, 2 pieces.', 180.00, 'Bathroom', 'English Home', 'https://m.media-amazon.com/images/I/91lCQg65kBL._AC_UF894,1000_QL80_.jpg'),
('Wall Art Prints', 'Botanical theme, set of 3 (No frame).', 75.00, 'Decoration', 'Poster Store', 'https://i.etsystatic.com/23583054/r/il/a168cd/3549722771/il_570xN.3549722771_6aml.jpg'),
('Tea Glass Set', 'Traditional Turkish tea glasses, 6 pack.', 50.00, 'Kitchen', 'Paşabahçe', 'https://m.media-amazon.com/images/I/713wgmZCu4L._AC_UF894,1000_QL80_.jpg'),
('Bookshelf', '5-tier wooden bookshelf.', 700.00, 'Furniture', 'Vivense', 'https://m.media-amazon.com/images/I/71eOIVo4ZnL._AC_UF894,1000_QL80_.jpg'),
('Desk Organizer', 'Metal mesh organizer.', 45.00, 'Office', 'Mas', 'https://cdn.manomano.com/images/images_products/30045240/P/103250617_1.jpg'),
('Artificial Plant', 'Potted artificial fern.', 130.00, 'Decoration', 'Herdekora', 'https://www.artificialgreen.co.uk/cdn/shop/files/PottedFernArtificialPlants_1200x1200.jpg?v=1743766645'),
('Throw Blanket', 'Soft acrylic knit throw.', 160.00, 'Textile', 'Yargıcı', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRKiHdyCpRYgj889YTgySGNV3nNfWrcE7HUiA&s'),
('Cutting Board', 'Wooden bamboo cutting board.', 95.00, 'Kitchen', 'Bambum', 'https://image.made-in-china.com/365f3j00GPqcsWHhbfkZ/Bamboo-Chopping-Board-for-Kitchen-Fruit-Cut-Board-with-Metal-Handle.webp'),
('Spice Jar Set', 'Glass jars with labels, 12 pieces.', 250.00, 'Kitchen', 'Vip Ahmet', 'https://m.media-amazon.com/images/I/91Dlo21v9ZL._AC_SL1500_.jpg'),
('Door Mat', 'Welcome mat with pattern.', 55.00, 'Decoration', 'Koçtaş', 'https://onlymat.com/cdn/shop/products/Screenshot2020-12-21at12.15.50AM.png?v=1608489991'),
('Curtain LED Lights', 'Warm white fairy lights.', 140.00, 'Lighting', 'TechStore', 'https://m.media-amazon.com/images/I/71eI54ABfvL.jpg'),
('Yoga Mat', 'Non-slip yoga mat, purple.', 110.00, 'Sports', 'Decathlon', 'https://images-cdn.ubuy.co.in/64d9a3aa5c1a195ee23990f8-purple-high-density-yoga-mat-72-in-l-x.jpg');

INSERT INTO product_images (product_id, image_url) VALUES
(1, 'https://cdn.dsmcdn.com/ty1336/product/media/images/prod/PIM/20240523/15/d5b4e723-5e9c-490f-9034-7548b6c00223/1_org_zoom.jpg'),
(1, 'https://cdn.dsmcdn.com/ty1336/product/media/images/prod/PIM/20240523/15/050f28e5-3375-4c07-ba7d-df9dca685116/1_org_zoom.jpg');
