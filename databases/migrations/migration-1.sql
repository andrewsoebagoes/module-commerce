CREATE TABLE products(
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    parent_id INT NULL,
    sku INT NOT NULL,
    price INT NOT NULL,
    status VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    CONSTRAINT fk_products_item_id FOREIGN KEY (item_id) REFERENCES inventory_items(id) ON DELETE CASCADE
);

CREATE TABLE product_pics(
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    media_id INT NOT NULL,
    CONSTRAINT fk_product_pics_product_id FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    CONSTRAINT fk_product_pics_media_id FOREIGN KEY (media_id) REFERENCES media(id) ON DELETE CASCADE
);

CREATE TABLE discounts(
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(100) NOT NULL,
    name VARCHAR(100) NOT NULL,
    discount_type VARCHAR(100) NOT NULL,
    discount_value INT NOT NULL,
    record_type VARCHAR(100) NOT NULL,
    status VARCHAR(100) NOT NULL
);

CREATE TABLE discount_applicables(
    id INT AUTO_INCREMENT PRIMARY KEY,
    discount_id INT NOT NULL,
    user_id INT NOT NULL,
    CONSTRAINT fk_discount_applicables_discount_id FOREIGN KEY (discount_id) REFERENCES discounts(id) ON DELETE CASCADE,
    CONSTRAINT fk_discount_applicables_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE product_discount(
    id INT AUTO_INCREMENT PRIMARY KEY,
    discount_id INT NOT NULL,
    product_id INT NOT NULL,
    status VARCHAR(100) NOT NULL DEFAULT 'DRAFT',
    CONSTRAINT fk_product_discount_discount_id FOREIGN KEY (discount_id) REFERENCES discounts(id) ON DELETE CASCADE,
    CONSTRAINT fk_product_discount_product_id FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

CREATE TABLE invoices(
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(100) NOT NULL,
    user_id INT NOT NULL,
    status VARCHAR(100) NOT NULL,
    notes VARCHAR(100) NOT NULL,
    total_amount INT NULL,
    payment_receive INT NULL,
    payment_return INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT NULL,
    organization_id INT NULL,
    CONSTRAINT fk_invoices_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_invoices_created_by FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    CONSTRAINT fk_invoices_organization_id FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE
);

CREATE TABLE invoice_items(
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_id INT NOT NULL,
    item_id INT NOT NULL,
    item_type VARCHAR(100) NULL,
    discount_id INT NULL,
    item_snapshot JSON NULL,
    discount_snapshot JSON NULL,
    quantity INT NOT NULL,
    item_price INT NOT NULL,
    discount_price INT NOT NULL,
    total_price INT NOT NULL,
    CONSTRAINT fk_invoice_items_invoice_id FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE,
    CONSTRAINT fk_invoice_item_id FOREIGN KEY (item_id) REFERENCES inventory_items(id) ON DELETE CASCADE
);

CREATE TABLE invoice_media(
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_id INT NOT NULL,
    media_id INT NOT NULL,
    status VARCHAR(100) NULL,
    CONSTRAINT fk_invoice_media_invoice_id FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE,
    CONSTRAINT fk_invoice_media_id FOREIGN KEY (media_id) REFERENCES storage_media(id) ON DELETE CASCADE
);


CREATE TABLE shippings(
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_id INT NOT NULL,
    country VARCHAR(100) NOT NULL,
    province VARCHAR(100) NOT NULL,
    city VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    courier VARCHAR(100) NOT NULL,
    notes VARCHAR(100) NOT NULL,
    CONSTRAINT fk_shippings_invoice_id FOREIGN KEY (invoice_id) REFERENCES invoices(id) ON DELETE CASCADE
);

