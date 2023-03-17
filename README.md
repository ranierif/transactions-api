# Transactions API

API experimental de transações entre usuários.

## Como instalar via Docker

1. Clone o repositório na sua máquina
2. Execute o comando `cp .env.example .env` 
3. Execute o comando `docker-compose up -d`
4. Acesse o container `docker exec -it transactions-api bash`
    - Instale o composer `composer install`
    - Rode as migrations & seeds `php artisan migrate:fresh --seed`
