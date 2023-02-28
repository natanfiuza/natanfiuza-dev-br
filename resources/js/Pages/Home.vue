<template>
    <div>
        <div class="isolate bg-white">

            <header>
                <div class="px-6 pt-6 lg:px-8">
                    <nav class="flex items-center justify-between" aria-label="Global">
                        <div class="flex lg:flex-1">
                            <a href="#" class="-m-1.5 p-1.5">
                                <span class="sr-only">NatanFiuza.<i class="fas fa-cc-discover    "></i></span>
                                <img class="h-8" src="assets/img/logo_natanfiuza.png" alt="" />
                            </a>
                        </div>
                        <div class="flex ">
                            <inertia-link href="/login">
                                <button type="button"
                                    class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
                                    <span class="">Open main</span>

                                </button>
                            </inertia-link>
                        </div>
                        <div class="hidden lg:flex lg:gap-x-12">
                            <a v-for="item in navigation" :key="item.name" :href="item.href"
                                class="text-sm font-semibold leading-6 text-gray-900">{{ item.name }}</a>
                        </div>
                        <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                            <inertia-link href="/login"
                                class="text-sm font-semibold leading-6 text-gray-900">Login</inertia-link>
                        </div>
                    </nav>

                </div>
            </header>
            <main>
                <div class="relative px-6 lg:px-8">
                    <div class="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
                        <div class="hidden sm:mb-8 sm:flex sm:justify-center">
                            <div
                                class="relative rounded-full py-1 px-3 text-sm leading-6 text-gray-600 ring-1 ring-gray-900/10 hover:ring-gray-900/20">
                                Announcing our next round of funding. <a href="#"
                                    class="font-semibold text-indigo-600"><span class="absolute inset-0"
                                        aria-hidden="true" />Read more <span aria-hidden="true">&rarr;</span></a>
                            </div>
                        </div>
                        <div class="text-center">
                            <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">Data to enrich your
                                online business</h1>
                            <p class="mt-6 text-lg leading-8 text-gray-600">Anim aute id magna aliqua ad ad non deserunt
                                sunt. Qui irure qui lorem cupidatat commodo. Elit sunt amet fugiat veniam occaecat fugiat
                                aliqua.</p>
                            <div class="mt-10 flex items-center justify-center gap-x-6">
                                <a href="#"
                                    class="rounded-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Get
                                    started</a>
                                <a href="#" class="text-sm font-semibold leading-6 text-gray-900">Learn more <span
                                        aria-hidden="true">→</span></a>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>

    </div>
</template>
<script>
export default {

}
</script>
<style>
.preto {
    color: black;
}
</style>
