import time
from db import Database
from processor import BoletoProcessor
from config import BATCH_INTERVAL, CHECK_INTERVAL
from logger import logger

def main():
    logger.info("Starting listener for boletos in batches.")
    time.sleep(45)

    while True:
        connection = Database.connect()

        try:
            processor = BoletoProcessor(connection)
            processed_count = processor.process_boletos()

            if processed_count == 0:
                time.sleep(BATCH_INTERVAL)

        except Exception as e:
            logger.error(f"Error during processing: {e}")

        finally:
            connection.close()

        time.sleep(CHECK_INTERVAL)

if __name__ == "__main__":
    main()
