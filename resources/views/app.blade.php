<!DOCTYPE html>
<html>
  <head>
 <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="./assets/img/favicon.ico">
    <link rel="stylesheet" href="./assets/css/swiper-bundle.min.css"/>
    <link href="./assets/css/remixicon/remixicon.css" rel="stylesheet">
    <title>NatanFiuza.dev | Home</title>
    @vite('resources/js/app.js')
    @vite('resources/css/main_css_blog.css')
    @inertiaHead
    @googlefonts
  </head>
  <body>
        <header class="header" id="header">

        <nav class="navbar container">
            <a href="{{route('principal')}}">
                <h3 class="logo"><span class="logo-keys logo-keys-left" data-name="{">{</span>NatanFiuza<span class="logo-keys logo-keys-right" data-name="}">}</span></h3>
            </a>

            <div class="menu" id="menu">
                <ul class="list">
                    <li class="list-item">
                        <a href="#" class="list-link current">Home</a>
                    </li>
                    <li class="list-item">
                        <a href="#" class="list-link">Categorias</a>
                    </li>
                    <li class="list-item">
                        <a href="#" class="list-link">Sobre</a>
                    </li>

                    <li class="list-item">
                        <a href="#" class="list-link">Contato</a>
                    </li>


                </ul>
            </div>

            <div class="list list-right">
                <button class="btn place-items-center" id="theme-toggle-btn">
                    <i class="ri-sun-line sun-icon"></i>
                    <i class="ri-moon-line moon-icon"></i>
                </button>

                <button class="btn place-items-center" id="search-icon">
                    <i class="ri-search-line"></i>
                </button>

                <button class="btn place-items-center screen-lg-hidden menu-toggle-icon" id="menu-toggle-icon">
                    <i class="ri-menu-3-line open-menu-icon"></i>
                    <i class="ri-close-line close-menu-icon"></i>
                </button>

                <a href="#" class="list-link screen-sm-hidden">Entrar</a>
                <a href="#" class="btn  fancy-border screen-sm-hidden">
                    <span>Registrar</span>
                </a>
            </div>
        </nav>
    </header>
     <!-- Search -->
    <div class="search-form-container container" id="search-form-container">

        <div class="form-container-inner">

            <form action="" class="form">
                <input class="form-input" type="text" placeholder="O que você procura?">
                <button class="btn form-btn " id="btn-global-search-button" type="button">
                    <i class="ri-search-line"></i>
                </button>
            </form>
            <span class="form-note">ou pressione ESC para fechar.</span>

        </div>

        <button class="btn form-close-btn place-items-center" id="form-close-btn" title="Fechar">
            <i class="ri-close-line"></i>
        </button>

    </div>
    <main>
        asdasdas
        <br>
        @inertia
    </main>
 @include('footer')

  </body>
</html>
