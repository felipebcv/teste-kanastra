from logger import logger

def send_emails(boletos):
    for boleto in boletos:
        logger.info(f"Enviando email para boleto ID: {boleto['id']}, Nome: {boleto['name']}")
        # Aqui iriam as chamadas de envio de email reais
    return [boleto['id'] for boleto in boletos]
