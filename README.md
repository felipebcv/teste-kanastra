# Projeto de Processamento de Boletos

Projeto para teste da empresa Kanastra
Este projeto consiste em um sistema de processamento de arquivos CSV, geração de boletos e envio de e-mails, utilizando Laravel 10, MySQL, PHP 8.2 e Docker Compose. O sistema será estruturado com repositories, interfaces e services, mantendo foco em performance e simplicidade.

## Checklist de Tarefas Realizadas para Esse Projeto

- [X] **Configuração Inicial do Ambiente**
  - [X] Configurar Docker com PHP 8.2, framework Laravel 10 e bando de dados MySQL.
  - [X] Criar projeto do Laravel 10 dentro de src
  
- [X] **Estruturação do Projeto**
  - [X] Criar a estrutura inicial com repositories, interfaces, services e outros seguindo as boas práticas dos princípios S.O.L.I.D.
  - [X] Definir classes base para processamento de CSV, geração de boletos e envio de e-mails.
  
- [X] **Implementação de Funcionalidades**
  - [X] Criar endpoint para upload de arquivos CSV.
  - [X] Implementar serviço para processamento do CSV.
  - [X] Implementar geração de boletos (simulada) Python em container segregado.
  - [X] Implementar envio de e-mails (simulado) Python em container segregado.

- [X] **Testes**
  - [X] Criar testes unitários para serviços e repositórios.
  - [X] Criar testes de integração para o fluxo completo.

- [X] **Finalização**
  - [X] Configurar logs e tratamento de erros.
  - [X] Escrever documentação completa no README.
