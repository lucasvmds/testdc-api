# Documentação API

Essa API foi feita utilizando o `Laravel` com o ambiente de desenvolvimento através do `Laravel Sail`.

Ela fornece os endpoints para o painel administrativo disponível [**nesse repositório**](https://github.com/lucasvmds/testedc-admin).

Para executar a aplicação basta instalar as dependencias com o `composer install`, configurar seu `.env` e rodar seu ambiente `Laravel Sail` com `./vendor/bin/sail up`.

Após executar a aplicação, você pode visitar a documentação da API através do endereço `http://localhost/docs/api`

Há testes automatizados para verificar o funcionamento da API. Execute eles com `php artisan test`