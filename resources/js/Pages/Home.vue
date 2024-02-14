<template>
    <div>
    <Head title="Home" />
    <Header />
    <SearchModal />
    <Featured />
    <QuickRead />
    <OlderPosts />
    <PopularTagsHome />
    <NewsletterSectionHome />
    <FooterHome />
    </div>
</template>
<script>
import { Head, Link } from '@inertiajs/vue3'
import Header from '@/Shared/Home/Header.vue'
import FooterHome from '@/Shared/Home/Footer.vue'
import NewsletterSectionHome from '@/Shared/Home/NewsletterSection.vue'
import PopularTagsHome from '@/Shared/Home/PopularTags.vue'
import SearchModal from '@/Shared/Home/SearchModal.vue'
import OlderPosts from '@/Shared/Home/OlderPosts.vue'
import QuickRead from '@/Shared/Home/QuickRead.vue'
import Featured from '@/Shared/Home/Featured.vue'

// Swiper
export default {
    components: {
        Head,
        Link,
        Header,
        FooterHome,
        NewsletterSectionHome,
        PopularTagsHome,
        OlderPosts,
        QuickRead,
        Featured,

    },

}
</script>
<style>
.preto {
    color: black;
}
</style>
