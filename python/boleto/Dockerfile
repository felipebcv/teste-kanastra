# Usa uma imagem base do Python
FROM python:3.9-slim

# Instala o MySQL connector para Python
RUN pip install mysql-connector-python

# Define o diretório de trabalho
WORKDIR /app

# Copia o arquivo de listener para o container
COPY ./python/boleto/src .

# Comando para rodar o script
CMD ["python", "main.py"]
