<template>
  <Head title="Favorites" />

  <breeze-authenticated-layout>
    <template #header>
      <h2 class="h4 font-weight-bold">
        Favorites
      </h2>
    </template>
    <!-- Page content here -->

    <h3 class="mb-3">Categories</h3>

    <div class="d-flex flex-row justify-content flex-wrap mb-2">
        <b-button v-for="category in categories" :key="category" class="m-1" @click.capture="selectCategory(category)" style="cursor: pointer;">
            <CategoryImage :category="category" v-bind:style="[category.includes(search) ? 'opacity: 1.0' : 'opacity: 0.3']"></CategoryImage>
        </b-button>
    </div>

    <h1 class="mb-3">Favorites</h1>

    <div v-for="favorite in sortedArray" :key="favorite.id" class="d-flex align-items-center justify-content-between mb-1 p-2" :style="'border-radius: 12px; position: relative; background-color: ' + getPastelColor(favorite.category.name) + ';'" >
        <div class="d-flex align-items-center">
            <div style="margin-right: 1rem;">
                <CategoryImage :category="favorite.category.name"></CategoryImage>
            </div>
            <div>{{ favorite.name }}</div>
        </div>
        <div class="d-flex align-items-center">
            <button class="btn p-0" style="margin-right: 1rem;" @click="removeFavorite(favorite)">
                <i class="bi bi-heart-fill"></i>
            </button>
        </div>
    </div>

    <Pagination class="mt-3" :links="favorites.links" />
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
    props: ["favorites"],
    data() {
        return {
            search: "",
        };
    },
    methods: {
        removeFavorite(favorite) {
            if (confirm('Are you sure you want to remove this favorite ?')){
                notify({
                    title: '<b>Remove</b>',
                    text: "The product '" + favorite.name + "' has been successfully removed from favorites !",
                    type: 'success',
                });
                Inertia.delete(route('favorites.destroy', favorite.id));
            }
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
        }
    },
    computed:{
        categories: function() {
            let categories = [];
            this.favorites.data.forEach(function(favorite) {
                categories.push(favorite.category.name);
            });
            return new Set(categories);
        },
        sortedArray: function() {
            console.log(this.favorites.data);

            return this.favorites.data.filter(favorite => {
                return favorite.category.name.includes(this.search);
            });
        }
    }
}

</script>
