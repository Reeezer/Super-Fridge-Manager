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

    <div class="d-flex flex-row justify-content flex-wrap mb-2">
        <Link v-for="category in categories" :key="category" class="m-1" v-model="search" @click="selectCategory(category)">
            <CategoryImage :category="category"></CategoryImage>
        </Link>
    </div>

    <h1 class="mb-3">Produits</h1>

    <div v-for="product in elements" :key="product.id" class="d-flex align-items-center justify-content-between mb-1 p-2" :style="'border-radius: 12px; position: relative; background-color: ' + getPastelColor(product.category.name) + ';'" >
        <div class="d-flex align-items-center">
            <div style="margin-right: 1rem;">
                <CategoryImage :category="product.category.name"></CategoryImage>
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
import CategoryImage from '@/Components/CategoryImage.vue'

export default {
    components: {
        BreezeAuthenticatedLayout,
        Pagination,
        Head,
        Link,
        CategoryImage
    },
    props: ["products"],
    data() {
        return {
            elements: [],
            search: ""
        };
    },
    methods: {
        destroy(id) {
            if (confirm('Are you sure you want to delete this product ?'))
            {
                Inertia.delete(route('products.destroy', id));
                this.search = "";
                this.elements = this.products.data;
            }
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
            if (this.search.includes(category))
            {
                this.search = "";
                this.elements = this.products.data;
            }
            else
            {
                this.search = category;

                this.elements = this.elements.filter(function(product){
                    if (product)
                        return product.category.name == category;
                });
            }
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
    },
    mounted() {
        this.elements = this.products.data;
    }
}

</script>
