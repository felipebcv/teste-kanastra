
# Projeto de Processamento de Boletos - Teste Kanastra

Este projeto consiste em um sistema de processamento de arquivos CSV, geração de boletos e envio de e-mails, construído com **Laravel 10**, **MySQL**, **PHP 8.2**, e **Docker Compose**. O sistema foi estruturado com repositories, interfaces, e services, com foco em performance e simplicidade, e tem como objetivo atender aos requisitos do teste técnico da empresa Kanastra.

## Checklist de Tarefas Realizadas

- **Configuração Inicial do Ambiente**
  - Configuração do ambiente Docker com PHP 8.2, Laravel 10 e banco de dados MySQL.
  - Criação do projeto Laravel dentro do diretório `src`.

- **Estruturação do Projeto**
  - Organização do projeto com repositories, interfaces, services, seguindo princípios de S.O.L.I.D.
  - Definição de classes para processamento de CSV, geração de boletos e envio de e-mails.

- **Implementação de Funcionalidades**
  - Endpoint para upload de arquivos CSV.
  - Serviço para processamento de CSV.
  - Geração de boletos (simulada) em container isolado.
  - Envio de e-mails (simulado) em container isolado.

- **Testes**
  - Testes unitários para services e repositórios.
  - Testes de integração para o fluxo completo.

- **Finalização**
  - Configuração de logs e tratamento de erros.
  - Documentação completa no README.

---

## Guia de Instalação

1. Clone o repositório:

   ```bash
   git clone git@github.com:felipebcv/teste-kanastra.git
   ```

2. Na raiz do sistema, execute o comando para build e subida dos containers:

   ```bash
   docker compose build --no-cache && docker compose up -d
   ```

3. A primeira vez que o deploy for executado, aguarde até que todos os serviços estejam prontos. O Laravel pode demorar um pouco devido à instalação das dependências do Composer.

4. Para verificar o progresso da instalação dentro do container, use o comando:

   ```bash
   docker exec -it teste_kanastra bash
   ```

5. Após a instalação, com todos os 5 containers em execução, você estará pronto para iniciar os testes.

---

## Estrutura dos Containers

- **mysql_kanastra**: Container com o banco de dados MySQL. Na raiz do projeto, o diretório `mysql` contém um script SQL (`db_demo_test.sql`) para configuração rápida do banco. Alternativamente, execute `php artisan migrate` para configurar as tabelas.

- **redis_kanastra**: Container Redis utilizado para gerenciamento das filas de processamento de boletos.

- **teste_kanastra**: Container principal onde o Laravel roda, servindo como API do sistema.

- **teste-kanastra-boleto_listener-1**: Listener que verifica os registros no banco de dados e gera boletos conforme necessário.

- **teste-kanastra-email_sender-1**: Listener para envio de e-mails após a geração dos boletos.

### Teste da API

1. Certifique-se de que todos os containers estejam ativos.
2. Use uma ferramenta como Postman para fazer requisições:
   - Crie uma requisição `POST`.
   - No campo `Body`, selecione `form-data`, insira a chave `file` como tipo `file`, e carregue o arquivo `utils/input.csv` (disponível na raiz do projeto).
   - Envie a requisição. Uma resposta de sucesso retornará:

   ```json
   {
       "message": "CSV file processed successfully."
   }
   ```

3. A API gerenciará a fila de cadastro de boletos no banco de dados, e você poderá acompanhar o progresso pelo banco MySQL.

4. Os listeners em Python gerenciam automaticamente a geração de boletos e envio de e-mails conforme os registros são processados.

---

## Considerações Finais

1. O arquivo `.env` e o diretório `vendor` foram incluídos para facilitar o setup inicial. Em um ambiente real, o `.env` e `vendor` não devem ser versionado.
2. Para executar testes unitários e de features, use o comando:

   ```bash
   php artisan test
   ```

3. Este sistema foi projetado para fins de teste e pode ser aprimorado com recursos adicionais, como autenticação, documentação com L5 Swagger, e outras melhorias, também serviços de mensageria como Service Bus, Rabbitmq e outros.

4. No caso de um sistema em produção, minha sugestão seria usar microssrviços, subindo em Pods separados e matendo segregado cada fluxo, como entrada de dados, geração de boletoes, envio de boletos e um container para reprocessamento, onde seria reprocessados os que por algum motivo deram erro, e devolvendo o motivo do reprocessamento, também poderiamos parametrizar a quantidade de retries.

5. Para cada etapa, poderia também ser criado um StepControl, para consulta de cada etapa do processo, desde a subida do .cvs no sistema até o ultimo pode que é o envio de email com sucesso ou registro de erro em qul etapa paraou, asssim poderiamos continuar de onde paramos.

6. Afim de estudo sobre a linguagem GO que é utilizado na Kanastra, fiz uma tentativa de contruir um Worker com a linguagem, está subindo e executando, mas ainda tem algum ponto de ajuste para funcionar, então deixo o versionamento aqui apenas a titulo de demonstrar minha tentativa, não conhecia a linguagem e nunca tinha tido contato, mas li a respeito e achei interessante e achei legal em tentar criar o Worker.

7. Para otimizar o uso do Docker após a primeira instalação, uma vez que o diretório `vendor` tenha sido criado, substitua o comando atual no `docker-compose.yml`:

   **Comando atual**:
   
   ```yaml
   command: /bin/sh -c 'chmod -R 777 /var/www/html/storage/* /var/www/html/bootstrap/cache/* && chown -R www-data:www-data /var/www/html/storage/* /var/www/html/bootstrap/cache/* && apache2-foreground && composer install && php artisan queue:work'
   ```

   **Comando otimizado**:

   ```yaml
   command: /bin/sh -c 'chmod -R 777 /var/www/html/storage/* /var/www/html/bootstrap/cache/* && chown -R www-data:www-data /var/www/html/storage/* /var/www/html/bootstrap/cache/* && apache2-foreground && php artisan queue:work'
   ```

Este ajuste elimina o `composer install` em execuções subsequentes, reduzindo o tempo de inicialização.

---
