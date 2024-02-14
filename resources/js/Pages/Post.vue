<template>
    <div>

        <Head title="Home" />
        <Header />
        <SearchModal />
        <section class="blog-post section-header-offset">
            <div class="blog-post-container container">
                <div class="blog-post-data">
                    <h3 class="title blog-post-title">Is VR the future?</h3>
                    <div class="article-data">
                        <span>Dec 6th 2021</span>
                        <span class="article-data-spacer"></span>
                        <span>4 Min read</span>
                    </div>
                    <img src="/assets/images/featured/featured-1.jpg" alt="">
                </div>

                <div class="container">
                    <p>
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Debitis eius possimus hic eligendi distinctio rerum incidunt, esse quasi eum molestiae ducimus ipsam quae, aliquid ullam placeat dolorum nulla vero. Quam? Lorem ipsum dolor sit, amet consectetur adipisicing elit. Sapiente repellat consequatur culpa, repudiandae aut dolores iusto. Rem natus soluta, dolores, ad deleniti, aut dolorem corrupti quasi amet unde delectus hic?
                    </p>

                    <p>
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Debitis eius possimus hic eligendi distinctio rerum incidunt, esse quasi eum molestiae ducimus ipsam quae, aliquid ullam placeat dolorum nulla vero. Quam? Lorem ipsum dolor sit amet consectetur, adipisicing elit. Vero quod necessitatibus, aspernatur pariatur asperiores earum quas adipisci veritatis quidem facilis! Nihil veniam quaerat nulla possimus, asperiores vero voluptatum placeat. Eveniet!
                    </p>

                    <blockquote class="quote">
                        <p><span><i class="ri-double-quotes-l"></i></span> Lorem ipsum dolor sit amet consectetur adipisicing elit. Officia voluptates, laboriosam voluptatum quos non consequuntur nesciunt necessitatibus tempora quod inventore corporis rem nihil itaque, at provident minus aliquam veritatis. Labore?  <span><i class="ri-double-quotes-r"></i></span></p>
                    </blockquote>

                    <p>
                        Lorem, ipsum dolor sit amet consectetur adipisicing elit. Debitis eius possimus hic eligendi distinctio rerum incidunt, esse quasi eum molestiae ducimus ipsam quae, aliquid ullam placeat dolorum nulla vero. Quam? Lorem ipsum dolor sit amet consectetur, adipisicing elit. Vero quod necessitatibus, aspernatur pariatur asperiores earum quas adipisci veritatis quidem facilis! Nihil veniam quaerat nulla possimus, asperiores vero voluptatum placeat. Eveniet!
                    </p>

                    <div class="author d-grid">
                        <div class="author-image-box">
                            <img src="/assets/img/natanfiuza.jpg" alt="" class="article-image">
                        </div>
                        <div class="author-about">
                            <h3 class="author-name">Natan Fiuza</h3>
                            <p>Desenvolvedor Fullstack, Pai, Avô</p>
                            <ul class="list social-media">
                                <li class="list-item">
                                    <a href="https://www.instagram.com/natanfiuza/" class="list-link"><i class="ri-instagram-line"></i></a>
                                </li>
                                <li class="list-item">
                                    <a href="https://www.facebook.com/natanielfiuza" class="list-link"><i class="ri-facebook-circle-line"></i></a>
                                </li>
                                <li class="list-item">
                                    <a href="https://twitter.com/natanfiuza" class="list-link"><i class="ri-twitter-line"></i></a>
                                </li>
                                <li class="list-item">
                                   <a href="https://www.linkedin.com/in/natanfiuza/" class="list-link"><i class="ri-linkedin-line"></i></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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
