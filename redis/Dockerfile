# Usar a imagem oficial do Redis como base
FROM redis:alpine

# Copiar o arquivo redis.conf customizado, se houver
COPY ./redis/redis.conf /usr/local/etc/redis/redis.conf

# Expor a porta 6379
EXPOSE 6379

# Definir o comando de inicialização do Redis, utilizando o arquivo redis.conf
CMD ["redis-server", "/usr/local/etc/redis/redis.conf"]
