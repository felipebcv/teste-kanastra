import os

DB_CONFIG = {
    'host': os.getenv('DB_HOST', 'db'),
    'user': os.getenv('DB_USER', 'user'),
    'password': os.getenv('DB_PASSWORD', 'user_password'),
    'database': os.getenv('DB_NAME', 'kanastra_db')
}

BATCH_INTERVAL = int(os.getenv('BATCH_INTERVAL', 10))
CHECK_INTERVAL = int(os.getenv('CHECK_INTERVAL', 5))
