<template>
  <Head title="Produits" />

  <breeze-authenticated-layout>
    <template #header>
      <h2 class="h4 font-weight-bold">
        Produits
      </h2>
    </template>
    <!-- Page content here -->

    <h3 class="mb-3">Catégories</h3>

    <Carousel items-to-show="10" snapAlign="end" >
        <Slide v-for="category in categories" :key="category">
            <Link href="">
                <img :src="'/resources/images/' + category.toLowerCase() + '.png'" :alt="category" :style="'height: 48px; border-radius: 8px;background-color: ' + getDarkColor(category) + ';'">
            </Link>
        </Slide>

       <template #addons>
            <Pagi />
        </template>
    </Carousel>

    <h1 class="mb-3">Produits</h1>

    <div v-for="product in sortedArray" :key="product.id" class="d-flex align-items-center justify-content-between mb-1 p-2" :style="'border-radius: 12px; position: relative; background-color: ' + getPastelColor(product.category.name) + ';'" >
        <div class="d-flex align-items-center">
            <div style="margin-right: 1rem;">
                <img :class="product.category.name" :style="'height: 48px; border-radius: 8px; background-color: ' + getDarkColor(product.category.name) + ';'" :src="'/resources/images/' + product.category.name.toLowerCase() + '.png'">
            </div>
            <Link class="btn p-0" :href="route('products.show', product.id)">
                <div>{{ product.name }}</div>
            </Link>
        </div>
        <div class="d-flex align-items-center">
            <button class="btn p-0" style="margin-right: 1rem;" @click="destroy(product.id)">
                <i class="bi bi-x-lg"></i>
            </button>
            <Link class="btn p-0" style="margin-right: 1rem;" :href="route('products.edit', product.id)">
                <i class="bi bi-pencil"></i>
            </Link>
            <div class="expiration-date" style="margin-right: 0.5rem;" :style="[daysLeft(product.created_at, product.category.expiration_days) <= 3 ? {'color':'red'} : {}]">
                {{ daysLeft(product.created_at, product.category.expiration_days) }} d
            </div>
        </div>
        <div v-if="daysLeft(product.created_at, product.category.expiration_days) <= 3" class="bg-danger expiration-icon" style="position: absolute; right:0; width: 10px; height: 48px; border-radius: 8px 0px 0px 8px;"></div>
    </div>

    <Pagination class="mt-3" :links="products.links" />
  </breeze-authenticated-layout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import Pagination from '@/Components/Pagination.vue'
import { Head, Link } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'

import 'vue3-carousel/dist/carousel.css';
import { Carousel, Slide, Navigation, Pagination as Pagi } from 'vue3-carousel';

export default {
    components: {
        BreezeAuthenticatedLayout,
        Pagination,
        Head,
        Link,
        Carousel,
        Slide,
        Navigation,
        Pagi
    },
    props: [
        "products"
    ],
    methods: {
        destroy(id) {
            if (confirm('Are you sure you want to delete this product ?'))
                Inertia.delete(route('products.destroy', id));
        },
        daysLeft(created_at, expiration_days) { // TODO Duplicate method
            const nbDays = new Date() - new Date(created_at);
            return expiration_days - Math.floor(nbDays / (1000 * 3600 * 24));
        },
        getHSL(category) {
            let colors = require('/resources/colors.json');
            let hsl;
            if (colors[category])
                hsl = colors[category];
            else
                hsl = colors['default'];
            return hsl;
        },
        getDarkColor(category) {
            let hsl = this.getHSL(category.toLowerCase());
            return 'hsl(' + hsl[0] + ',' + hsl[1] + '%,' + hsl[2] + '%)';
        },
        getPastelColor(category) {
           let hsl = this.getHSL(category.toLowerCase());
            return 'hsl(' + hsl[0] + ',' + hsl[1] + '%,' + (hsl[2]+20) + '%)';
        },
        selectCategory(category) {

        }
    },
    computed:{
        sortedArray: function() {
            function compare(a, b) {
                if (a < b)
                    return -1;
                if (a > b)
                    return 1;
                return 0;
            }

            function days(created_at, expiration_days) { // TODO Duplicate method
                const nbDays = new Date() - new Date(created_at);
                return expiration_days - Math.floor(nbDays / (1000 * 3600 * 24));
            }

            return this.products.data.sort((a, b) => compare(days(a.created_at, a.category.expiration_days), days(b.created_at, b.category.expiration_days)));
        },
        categories: function() {
            let categories = [];
            this.products.data.forEach(function(product) {
                categories.push(product.category.name);
            });
            return new Set(categories);
        }
    }
}

</script>
