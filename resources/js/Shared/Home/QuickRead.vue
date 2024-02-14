<template>
    <div>
        <!-- Quick read -->
        <section class="quick-read section">

            <div class="container">

                <h2 class="title section-title" data-name="Quick read">Quick read</h2>
                <!-- Slider main container -->
                <!-- <div class="swiper"> -->
                  <swiper
                        :slides-per-view="3"
                        :space-between="50"
                        :options="swiperOptions"

                        @swiper="onSwiper"
                        @slideChange="onSlideChange"
                        id="swiper_slide_home"
                    >
                    <!-- Additional required wrapper -->
                    <!-- <div class="swiper-wrapper"> -->
                        <!-- Slides -->
                        <swiper-slide>
                        <a href="#" class="article swiper-slide">
                            <img src="/assets/images/quick_read/quick_read_1.jpg" alt="" class="article-image">

                            <div class="article-data-container">
                                <div class="article-data">
                                    <span>23 Dec 2021</span>
                                    <span class="article-data-spacer"></span>
                                    <span>3 Min read</span>
                                </div>
                                <h3 class="title article-title">Sample article title</h3>
                            </div>
                        </a>
                        </swiper-slide>
                        <!-- Slides -->
                          <swiper-slide>
                        <a href="#" class="article swiper-slide">
                            <img src="/assets/images/quick_read/quick_read_2.jpg" alt="" class="article-image">

                            <div class="article-data-container">
                                <div class="article-data">
                                    <span>23 Dec 2021</span>
                                    <span class="article-data-spacer"></span>
                                    <span>3 Min read</span>
                                </div>
                                <h3 class="title article-title">Sample article title</h3>
                            </div>
                        </a>
                          </swiper-slide>
                        <!-- Slides -->
                          <swiper-slide>
                        <a href="#" class="article swiper-slide">
                            <img src="/assets/images/quick_read/quick_read_3.jpg" alt="" class="article-image">

                            <div class="article-data-container">
                                <div class="article-data">
                                    <span>23 Dec 2021</span>
                                    <span class="article-data-spacer"></span>
                                    <span>3 Min read</span>
                                </div>
                                <h3 class="title article-title">Sample article title</h3>
                            </div>
                        </a>
                          </swiper-slide>
                        <!-- Slides -->
                          <swiper-slide>
                        <a href="#" class="article swiper-slide">
                            <img src="/assets/images/quick_read/quick_read_4.jpg" alt="" class="article-image">

                            <div class="article-data-container">
                                <div class="article-data">
                                    <span>23 Dec 2021</span>
                                    <span class="article-data-spacer"></span>
                                    <span>3 Min read</span>
                                </div>
                                <h3 class="title article-title">Sample article title</h3>
                            </div>
                        </a>
                          </swiper-slide>
                        <!-- Slides -->
                          <swiper-slide>
                        <a href="#" class="article swiper-slide">
                            <img src="/assets/images/quick_read/quick_read_5.jpg" alt="" class="article-image">

                            <div class="article-data-container">
                                <div class="article-data">
                                    <span>23 Dec 2021</span>
                                    <span class="article-data-spacer"></span>
                                    <span>3 Min read</span>
                                </div>
                                <h3 class="title article-title">Sample article title</h3>
                            </div>
                        </a>
                          </swiper-slide>
                        <!-- Slides -->
                          <swiper-slide>
                        <a href="#" class="article swiper-slide">
                            <img src="/assets/images/quick_read/quick_read_6.jpg" alt="" class="article-image">

                            <div class="article-data-container">
                                <div class="article-data">
                                    <span>23 Dec 2021</span>
                                    <span class="article-data-spacer"></span>
                                    <span>3 Min read</span>
                                </div>
                                <h3 class="title article-title">Sample article title</h3>
                            </div>
                        </a>
                          </swiper-slide>
                    <!-- </div> -->
                    <!-- Navigation buttons -->
                    <div class="swiper-button-prev swiper-controls"></div>
                    <div class="swiper-button-next swiper-controls"></div>
                    <!-- Pagination -->
                    <div class="swiper-pagination"></div>
                <!-- </div> -->
                  </swiper>
            </div>

        </section>
    </div>
</template>
<script>
import { Head, Link } from '@inertiajs/vue3'
// Import Swiper Vue.js components
import { Swiper, SwiperSlide, useSwiper } from 'swiper/vue';
import { Navigation, Pagination, Scrollbar, A11y } from 'swiper/modules';

// Import Swiper styles
import 'swiper/css';
import 'swiper/css/effect-cards';
// Swiper

export default {
    components: {
        Head,
        Link,
        Swiper,
        SwiperSlide,
    },
    setup() {
        // const swiper = useSwiper();
        const onSwiper = (swiper) => {
            console.log('swiper show');
            console.log(swiper);
        };
        const onSlideChange = () => {
            console.log('slide change');
        };
        return {
            // swiper,
            modules: [Navigation, Pagination, Scrollbar, A11y],
            swiperOptions: {
                pagination: {
                    el: '.swiper-pagination',
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },

            },
        };
    },
     data() {
        return {

        }
    },
};
</script>
