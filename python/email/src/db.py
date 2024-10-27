import mysql.connector
from config import DB_CONFIG
from logger import logger

def connect_to_db():
    try:
        connection = mysql.connector.connect(**DB_CONFIG)
        return connection
    except mysql.connector.Error as e:
        logger.error(f"Erro ao conectar ao banco de dados: {e}")
        raise

def get_boletos_to_email(connection, batch_size):
    try:
        cursor = connection.cursor(dictionary=True)
        cursor.execute("SELECT * FROM boletos WHERE `generated` = 'Y' AND `sendMail` = 'N' LIMIT %s", (batch_size,))
        boletos = cursor.fetchall()
        cursor.close()
        logger.info(f"{len(boletos)} boletos encontrados para envio de email.")
        return boletos
    except mysql.connector.Error as e:
        logger.error(f"Erro ao consultar boletos: {e}")
        raise

def update_boletos_as_emailed(connection, boleto_ids):
    try:
        cursor = connection.cursor()
        query = "UPDATE boletos SET `sendMail` = 'Y' WHERE id IN ({})".format(','.join(['%s'] * len(boleto_ids)))
        cursor.execute(query, boleto_ids)
        connection.commit()
        cursor.close()
        logger.info(f"{len(boleto_ids)} boletos atualizados para 'Y' no campo sendMail.")
    except mysql.connector.Error as e:
        logger.error(f"Erro ao atualizar boletos: {e}")
        raise
