CREATE OR REPLACE FUNCTION update_modified_column()
RETURNS TRIGGER AS $$
BEGIN
   IF row(NEW.*) IS DISTINCT FROM row(OLD.*) THEN
      NEW.modified = now(); 
      RETURN NEW;
   ELSE
      RETURN OLD;
   END IF;
END;
$$ language 'plpgsql';

CREATE TRIGGER address_modified_trigger BEFORE UPDATE ON address FOR EACH ROW EXECUTE PROCEDURE  update_modified_column();
CREATE TRIGGER buy_modified_trigger BEFORE UPDATE ON buy FOR EACH ROW EXECUTE PROCEDURE  update_modified_column();
CREATE TRIGGER buy_items_modified_trigger BEFORE UPDATE ON buy_items FOR EACH ROW EXECUTE PROCEDURE  update_modified_column();
CREATE TRIGGER customer_modified_trigger BEFORE UPDATE ON customer FOR EACH ROW EXECUTE PROCEDURE  update_modified_column();
CREATE TRIGGER machine_modified_trigger BEFORE UPDATE ON machine FOR EACH ROW EXECUTE PROCEDURE  update_modified_column();
CREATE TRIGGER product_modified_trigger BEFORE UPDATE ON product FOR EACH ROW EXECUTE PROCEDURE  update_modified_column();
CREATE TRIGGER token_modified_trigger BEFORE UPDATE ON token FOR EACH ROW EXECUTE PROCEDURE  update_modified_column();