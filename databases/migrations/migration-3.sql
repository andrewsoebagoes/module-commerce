ALTER TABLE shippings ADD COLUMN subdistrict VARCHAR(100) NOT NULL;
ALTER TABLE invoice_items DROP FOREIGN KEY fk_invoice_item_id;
