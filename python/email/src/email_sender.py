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

def get_boletos_to_email(connection):
    cursor = connection.cursor(dictionary=True)
    cursor.execute("SELECT * FROM boletos WHERE `generated` = 'Y' AND `sendMail` = 'N' LIMIT 10000")
    boletos = cursor.fetchall()
    cursor.close()
    
    logging.info(f"Records found for email batch: {len(boletos)}")
    return boletos

def send_emails(boletos, connection):
    boleto_ids = [boleto['id'] for boleto in boletos]
    
    for boleto in boletos:
        logging.info(f"Sending email for boleto ID: {boleto['id']}, Name: {boleto['name']}")

    cursor = connection.cursor()
    query = "UPDATE boletos SET `sendMail` = 'Y' WHERE id IN ({})".format(','.join(['%s'] * len(boleto_ids)))
    cursor.execute(query, boleto_ids)
    connection.commit()
    cursor.close()

    logging.info(f"Batch of {len(boleto_ids)} emails sent and updated to 'Y'.")

def main():
    logging.info("Starting listener for email sending.")

    while True:
        connection = connect_to_db()
        
        try:
            boletos_to_email = get_boletos_to_email(connection)
            
            if boletos_to_email:
                logging.info(f"{len(boletos_to_email)} boletos found for email sending.")
                send_emails(boletos_to_email, connection)
            else:
                logging.info("No new emails to send.")
                time.sleep(10)  # Aguarda 10 segundos antes da próxima verificação

        except mysql.connector.Error as e:
            logging.error(f"Database error: {e}")

        finally:
            connection.close()  # Fecha a conexão a cada ciclo

        time.sleep(5)  # Pausa entre os ciclos de verificação

if __name__ == "__main__":
    main()
