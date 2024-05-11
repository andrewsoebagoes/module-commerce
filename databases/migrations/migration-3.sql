ALTER TABLE invoice_media DROP FOREIGN KEY fk_invoice_media_id; 
ALTER TABLE invoice_media ADD CONSTRAINT fk_invoice_media_id FOREIGN KEY (media_id) REFERENCES media(id) ON DELETE CASCADE ON UPDATE RESTRICT;
