# Documentação API

Essa API foi feita utilizando o `Laravel` com o ambiente de desenvolvimento através do `Laravel Sail`.

Ela fornece os endpoints para o painel administrativo disponível [**nesse repositório**](https://github.com/lucasvmds/testedc-admin).

Para executar a aplicação basta instalar as dependencias com o `composer install`, configurar seu `.env` e rodar seu ambiente `Laravel Sail` com `./vendor/bin/sail up`.

Após executar a aplicação, você pode visitar a documentação da API através do endereço `http://localhost/docs/api`

Há testes automatizados para verificar o funcionamento da API. Execute eles com `php artisan test`

## Importante

No arquivo `.env` há uma configuração chamada `CORS_ALLOWED_ORIGINS`, preencha ele com o endereço utilizado pela aplicação no front end, seprando os valores por vírgula. Por exemplo: por padrão, a URL do front end deveria ser `http://localhost:5174`, sendo assim o arquivo ficaria assim:

```bash
# ...
CORS_ALLOWED_ORIGINS=http://localhost:5174
# ...
```

ou se se tiver mais de um endereço:

```bash
# ...
CORS_ALLOWED_ORIGINS=http://localhost:5174,http://test.localhost
# ...
```