from db import Database
from logger import logger

class BoletoProcessor:
    def __init__(self, connection):
        self.connection = connection

    def process_boletos(self):
        boletos = Database.get_boletos_batch(self.connection)
        if boletos:
            boleto_ids = [boleto['id'] for boleto in boletos]
            for boleto in boletos:
                logger.info(f"Processing boleto ID: {boleto['id']}, Name: {boleto['name']}")
            Database.update_boletos_status(self.connection, boleto_ids)
            return len(boletos)
        else:
            logger.info("No new boletos found.")
            return 0
