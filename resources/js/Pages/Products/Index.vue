<template>
  <Head title="Products" />

  <breeze-authenticated-layout>
    <template #header>
      <h2 class="h4 font-weight-bold">
        Products
      </h2>
    </template>
    <!-- Page content here -->

    <h3 class="mb-3">Categories</h3>

    <div class="d-flex flex-row justify-content flex-wrap mb-2">
        <b-button v-for="category in categories" :key="category" class="m-1" @click.capture="selectCategory(category)" style="cursor: pointer;">
            <CategoryImage :category="category" v-bind:style="[category.includes(search) ? 'opacity: 1.0' : 'opacity: 0.3']"></CategoryImage>
        </b-button>
    </div>

    <h1 class="mb-3">Products</h1>

    <div v-for="product in sortedArray" :key="product.id" class="d-flex align-items-center justify-content-between mb-1 p-2 product" :style="'background-color:' + getPastelColor(product.category.name) + ';'" >
        <div class="d-flex align-items-center">
            <!-- Category -->
            <div style="margin-right: 1rem;">
                <CategoryImage :category="product.category.name"></CategoryImage>
            </div>

            <!-- Product name -->
            <Link class="btn p-0" :href="route('products.show', product.id)">
                <div>{{ product.name }}</div>
            </Link>
        </div>
        <div class="d-flex align-items-center">
            <!-- Favorites -->
            <button v-if="isFavorite(product)" class="btn p-0" style="margin-right: 1rem;" @click="removeFavorite(product)">
                <i class="bi bi-heart-fill"></i>
            </button>
            <button v-else class="btn p-0" style="margin-right: 1rem;" @click="addFavorite(product)">
                <i class="bi bi-heart"></i>
            </button>

            <!-- Remove -->
            <button class="btn p-0" style="margin-right: 1rem;" @click="destroyProduct(product)">
                <i class="bi bi-x-lg"></i>
            </button>

            <!-- Edit -->
            <Link class="btn p-0" style="margin-right: 1rem;" :href="route('products.edit', product.id)">
                <i class="bi bi-pencil"></i>
            </Link>

            <!-- Expiration -->
            <div class="expiration-date" style="margin-right: 0.5rem;" :style="[daysLeft(product.pivot.added_date, product.category.expiration_days) <= 3 ? {'color':'red'} : {}]">
                {{ daysLeft(product.pivot.added_date, product.category.expiration_days) }} d
            </div>
        </div>
        <div v-if="daysLeft(product.pivot.added_date, product.category.expiration_days) <= 3" class="bg-danger expiration-icon expirationIcon"></div>
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
import { notify } from "@kyvg/vue3-notification"

export default {
    components: {
        BreezeAuthenticatedLayout,
        Pagination,
        Head,
        Link,
        CategoryImage
    },
    props: ["products", "favorites"],
    data() {
        return {
            search: "",
        };
    },
    methods: {
        destroyProduct(product) {
            if (confirm('Are you sure you want to delete this product ?')){
                notify({
                    title: '<b>Delete</b>',
                    text: "The product '" + product.name + "' has been successfully deleted !",
                    type: 'success',
                });
                Inertia.delete(route('products.user_delete', product));
            }
            else {
                notify({
                    title: '<b>Delete</b>',
                    text: "The product '" + product.name + "' has not been deleted ...",
                    type: 'warn',
                });
            }
        },
        removeFavorite(product) {
            if (confirm('Are you sure you want to remove this product from favorites ?')){
                notify({
                    title: '<b>Remove favorite</b>',
                    text: "The product '" + product.name + "' has been successfully removed from favorites !",
                    type: 'success',
                });
                Inertia.delete(route('favorites.destroy', product.id));
            }
            else {
                notify({
                    title: '<b>Remove favorite</b>',
                    text: "The product '" + product.name + "' has not been removed from favorites ...",
                    type: 'warn',
                });
            }
        },
        addFavorite(product) {
            notify({
                title: '<b>Add favorite</b>',
                text: "The product '" + product.name + "' has been successfully added to favorites !",
                type: 'success',
            });
            Inertia.post(route('favorites.store'), product);
        },
        daysLeft(created_at, expiration_days) { // TODO Duplicate method (called days in mounted())
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
        getPastelColor(category) {
            let hsl = this.getHSL(category.toLowerCase());
            return 'hsl(' + hsl[0] + ',' + hsl[1] + '%,' + (hsl[2]+20) + '%)';
        },
        selectCategory(category) {
            this.search = this.search.includes(category) ? "" : category;
        },
        isFavorite(product) {
            return this.favoriteIDs.has(product.id);
        }
    },
    computed:{
        favoriteIDs: function() {
            // makes a set out of the favorites array so we can quickly check if a product has
            // been favorited by the user

            let ids = new Set();

            for (let favorite in this.favorites) {
                ids.add(this.favorites[favorite].pivot.product_id);
            }

            return ids;
        },
        categories: function() {
            let categories = [];
            this.products.data.forEach(function(product) {
                categories.push(product.category.name);
            });
            return new Set(categories);
        },
        sortedArray: function() {
            function compare(a, b) {
                if (a < b)
                    return -1;
                if (a > b)
                    return 1;
                return 0;
            }

            function days(created_at, expiration_days) { // TODO Duplicate method (called daysLeft above)
                const nbDays = new Date() - new Date(created_at);
                return expiration_days - Math.floor(nbDays / (1000 * 3600 * 24));
            }

            let filteredProducts = this.products.data.filter(product => {
                return product.category.name.includes(this.search);
            });

            return filteredProducts.sort((a, b) => compare(days(a.pivot.added_date, a.category.expiration_days), days(b.pivot.added_date, b.category.expiration_days)));
        }
    },
    mounted() {
        let updateSuccess = window.localStorage.getItem('updateSuccess');
        if (updateSuccess != null)
        {
            window.localStorage.removeItem('updateSuccess');
            notify({
                title: '<b>Update</b>',
                text: "The product '" + updateSuccess + "' has been successfully updated !",
                type: 'success',
            });
        }

        let updateCancel = window.localStorage.getItem('updateCancel');
        if (updateCancel != null)
        {
            window.localStorage.removeItem('updateCancel');
            notify({
                title: '<b>Update</b>',
                text: "The product '" + updateCancel + "' has not been updated ...",
                type: 'warn',
            });
        }

        let createSuccess = window.localStorage.getItem('createSuccess');
        if (createSuccess != null)
        {
            window.localStorage.removeItem('createSuccess');
            notify({
                title: '<b>Create</b>',
                text: "The product '" + createSuccess + "' has been successfully added !",
                type: 'success',
            });
        }

        let createCancel = window.localStorage.getItem('createCancel');
        if (createCancel != null)
        {
            window.localStorage.removeItem('createCancel');
            notify({
                title: '<b>Create</b>',
                text: "No product has been added ...",
                type: 'warn',
            });
        }
    }
}

</script>

<style>

.product {
    border-radius: 12px;
    position: relative;
}

.expirationIcon {
    position: absolute;
    right: 0;
    width: 10px;
    height: 48px;
    border-radius: 8px 0px 0px 8px;
}

</style>
