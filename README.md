# QuickStart - Laravel + VueJS + AdminLTE

![enter image description here](https://lh3.googleusercontent.com/Voxz5TMKAOkRbQ-WC5doHX38DW5LJMcFe4Vv7Dmf8E0l9BR-5gyhkqq66BThwt6IGnq2b6koA_pN20hsAecU9dchSur4NVPTO5w_zlWl4_HEGVqgsKUPFPKbFoL8QwGEbEe4FfTN3q86z6Z1KZdkkd_zQ2_5sW0oUuxyK1YDvv6johm6zWM3epR1sGLz-fbDHi7V7i77Stszw1VGHJGI1PHs8b0mzaauviMiACaI-5CsUNenMlipoSaRONCjhb1FT8QKe40haupsGQYUZdwWJHil0TToqrkrgzTSP9W_QW2Ww4eJlY-oXkvHf_YUbhHPRfhh0rT4IOzaAu_IF7iC0ixlobdQ_SCYabcAzCqgBSJmBSrIbf2Uu_YyFUUbc3qOnGCBQNzJ6jpWIO6XBIMucJuu-Z37DHXqxWOjT0p4ci9ZXVsiV77km5mcUcAMlmpfa8I-ILLL1Lb6pIh3SjUh4H3kbh4haaNXiVnaytE2F4sDouCbwnuQkORwJ2ydQetL4kEWYSe7MsQbIHPr6W8UnJxHnlCqPlZGvA27sl7Ro02bUyV_BaUxN1mY9g4t52C3kR7D-T-O0lYgGaO0SMV95_PI2tY8balvNULkNRzwC80j3Pb8-CkuMA8HqqL7NFrgjdld9GfTdZmWFROVgme4GdjG_tcNw2wAlZo85moMOTgZhH7X0wpBmbvy94Slu0M61BlQpumaAqT1kn-jUMvIqzyA69zV0zxS_qxwz1lLL7q7Ku9V=w640-h360-no)   


# Instruções de instalação

Clone o repositório: 
  `https://github.com/Rodr1go/boilerplate-laravel-vuejs.git`
Entre no diretório da aplicação via terminal: 
```sh 
$ cd vue-laravel
```
Instale as dependências da aplicação: 
```sh
$ composer install
```
Edite o arquivo `.env` com as informações de conexão com o BD
Gere a chave de segurança da aplicação:
```sh
$ php artisan key:generate
```
Gere as chaves de criptografia do Passport:
```sh
$ php artisan passport:keys
```
Execute o comando p/ instalar as dependências do frontend: 
```sh
$ npm install
```
Execute o comando p/ realizar as migrações do BD:
```sh
$ php artisan migrate --seed
```
Verifique a implantação navegando até o endereço do servidor no seu navegador preferido:
```sh
127.0.0.1:8000
```


License
----

MIT


**Free Software, Hell Yeah!**