## Instalação local do composer

Baixa o composer:

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
```

Instala o composer:

```bash
php composer-setup.php
```

Remove o arquivo de instalação:

```bash
php -r "unlink('composer-setup.php');"
```


Aí para usar o composer local, é só rodar seus comandos dessa forma:

```bash
./composer.phar [SEU COMANDO]
```
