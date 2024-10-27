import time
from db import connect_to_db, get_boletos_to_email, update_boletos_as_emailed
from email_sender import send_emails
from config import CHECK_INTERVAL, BATCH_SIZE
from logger import logger

def main():
    logger.info("Iniciando o listener de envio de emails.")
    
    while True:
        connection = connect_to_db()
        
        try:
            boletos_to_email = get_boletos_to_email(connection, BATCH_SIZE)
            
            if boletos_to_email:
                boleto_ids = send_emails(boletos_to_email)
                update_boletos_as_emailed(connection, boleto_ids)
            else:
                logger.info("Nenhum novo email para enviar.")
                
            time.sleep(CHECK_INTERVAL)
        
        except Exception as e:
            logger.error(f"Erro no processamento: {e}")
        
        finally:
            connection.close() 
            
if __name__ == "__main__":
    main()
