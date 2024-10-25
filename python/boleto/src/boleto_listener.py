import time
import mysql.connector
import logging

logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')

time.sleep(45)

db_config = {
    'host': 'db',
    'user': 'user',
    'password': 'user_password',
    'database': 'kanastra_db'
}

def connect_to_db():
    return mysql.connector.connect(**db_config)

def get_boletos_batch(connection):
    cursor = connection.cursor(dictionary=True)
    cursor.execute(f"SELECT * FROM boletos WHERE `generated` = 'N' LIMIT 10000")
    boletos = cursor.fetchall()
    cursor.close()
    
    logging.info(f"Records found in batch: {len(boletos)}")
    return boletos

def process_boletos(boletos, connection):
    boleto_ids = [boleto['id'] for boleto in boletos]
    
    for boleto in boletos:
        logging.info(f"Processing boleto ID: {boleto['id']}, Name: {boleto['name']}")

    cursor = connection.cursor()
    query = "UPDATE boletos SET `generated` = 'Y' WHERE id IN ({})".format(','.join(['%s'] * len(boleto_ids)))
    cursor.execute(query, boleto_ids)
    connection.commit()
    cursor.close()

    logging.info(f"Batch of {len(boletos)} boletos processed and updated to 'Y'.")

def main():
    logging.info("Starting listener for boletos in batches.")

    while True:
        # Cria uma nova conexão a cada ciclo
        connection = connect_to_db()
        
        try:
            boletos = get_boletos_batch(connection)

            if boletos:
                logging.info(f"{len(boletos)} boletos found for processing.")
                process_boletos(boletos, connection)
            else:
                logging.info("No new boletos found.")
                time.sleep(10)  # Aguarda 10 segundos antes de verificar novamente

        except mysql.connector.Error as e:
            logging.error(f"Database error: {e}")
        
        finally:
            connection.close()  # Fecha a conexão a cada ciclo

        time.sleep(5)  # Pausa breve para reduzir carga entre verificações

if __name__ == "__main__":
    main()
