# Site NatanFiuza.dev.br





## Auto-hospedar fontes Google

Para auto-hospedar facilmente fontes do Google no Laravel, você pode usar um pacote do **"spatie"** chamado **"laravel-google-fonts"**. Este pacote é muito simples de usar e abaixo estão os passos para integrá-lo ao seu projeto Laravel.

### Etapa 1: Instalar usando o Composer

Você pode instalar este pacote usando o `composer` executando o comando abaixo.

```bash

compositor requer spatie/laravel-google-fonts

```
### Passo 2: Publicar configuração

Depois de instalado você pode publicar o arquivo de configuração do pacote. O arquivo de configuração será usado para especificar as fontes que você deseja incluir/auto-hospedar.

```bash

php artisan vendor:publish --provider="Spatie\GoogleFonts\GoogleFontsServiceProvider" --tag="google-fonts-config"

```


### Etapa 3: Definir as fontes do Google
Você pode definir as fontes do Google que deseja auto-hospedar dentro do valor da matriz, como abaixo. Observe que as outras configurações foram omitidas neste exemplo.

```php

<?php // config/google-fonts.php

return [
    'fonts' => [
        'default' => 'https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,700;1,400;1,700&display=swap',
        'code' => 'https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:ital,wght@0,400;0,700;1,400&display=swap',
        'roboto' => 'https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900'
    ],
    /* other configs */
];


```


### Passo 4: Consulte o arquivo Blade
Agora que você definiu isso, você pode consultar a fonte do seu arquivo blade usando a diretiva "@googlefonts". Por padrão, ele obterá a fonte "default" e para aqueles que possuem um nome diferente, você pode especificá-la passando um parâmetro para a diretiva como abaixo.

```css

@googlefonts
@googlefonts('roboto') // get the google font

```

Para o exemplo de código completo, será o seguinte.
```php
{{-- resources/views/layouts/app.blade.php --}}
<html>
<head>
    {{-- Loads Inter Font --}}
    @googlefonts

    {{-- Loads Roboto Font --}}
    @googlefonts('code')
</head>
<body></body>
</html>

```
### Extra: pré-busca de fontes
Você pode querer pré-buscar as fontes na produção executando o comando prefetch.

```bash
php artisan google-fonts:fetch

```


Observe que você precisará ter o armazenamento vinculado para isso se estiver usando o driver “público”. Se você ainda não o vinculou, execute o comando abaixo.

```bash

php artisan storage:link

```
