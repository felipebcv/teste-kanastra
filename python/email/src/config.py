import os

DB_CONFIG = {
    'host': 'db',
    'user': 'user',
    'password': 'user_password',
    'database': 'kanastra_db'
}

CHECK_INTERVAL = 10  # Intervalo entre verificações (em segundos)
BATCH_SIZE = 10000  # Tamanho do lote para consulta de boletos
