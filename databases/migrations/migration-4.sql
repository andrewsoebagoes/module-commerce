CREATE TABLE product_prices(
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    price INT NOT NULL,
    min_quantity INT NOT NULL,
    CONSTRAINT fk_product_prices_product_id FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);