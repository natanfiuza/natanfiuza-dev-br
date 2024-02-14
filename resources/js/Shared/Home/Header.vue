<template>
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
</template>
<script>
export default {
    setup(props) {
        
    }
}
</script>
