import mysql.connector
from mysql.connector import MySQLConnection
from config import DB_CONFIG
from logger import logger

class Database:
    @staticmethod
    def connect() -> MySQLConnection:
        return mysql.connector.connect(**DB_CONFIG)

    @staticmethod
    def get_boletos_batch(connection: MySQLConnection, limit: int = 10000):
        cursor = connection.cursor(dictionary=True)
        cursor.execute("SELECT * FROM boletos WHERE `generated` = 'N' LIMIT %s", (limit,))
        boletos = cursor.fetchall()
        cursor.close()
        logger.info(f"Records found in batch: {len(boletos)}")
        return boletos

    @staticmethod
    def update_boletos_status(connection: MySQLConnection, boleto_ids: list):
        if not boleto_ids:
            return
        cursor = connection.cursor()
        query = "UPDATE boletos SET `generated` = 'Y' WHERE id IN ({})".format(','.join(['%s'] * len(boleto_ids)))
        cursor.execute(query, boleto_ids)
        connection.commit()
        cursor.close()
        logger.info(f"Batch of {len(boleto_ids)} boletos updated to 'Y'.")
