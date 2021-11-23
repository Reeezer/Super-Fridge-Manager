<template>
  <Head title="Produits" />

  <breeze-authenticated-layout>
    <template #header>
      <h2 class="h4 font-weight-bold">
        Produits
      </h2>
    </template>
    <!-- Page content here -->

    <h1 class="mb-3">Produits</h1>

    <div v-for="product in sortedArray" :key="product.id" class="d-flex align-items-center justify-content-between bg-primary mb-1 p-2" style="border-radius: 12px; position: relative;">
        <div class="d-flex align-items-center">
            <div class="bg-secondary" style="margin-right: 1rem; border-radius: 8px;">
                <img style="height: 48px;" id='image' :src="'/images/' + product.category.name.toLowerCase() + '.png'">
            </div>
            <div>{{product.name}}</div>
        </div>
        <div class="d-flex align-items-center">
            <button class="btn p-0" style="margin-right: 1rem;" @click="destroy(product.id)">
                <i class="bi bi-x-lg"></i>
            </button>
            <Link class="btn p-0" style="margin-right: 1rem;" :href="route('products.edit', product.id)">
                <i class="bi bi-pencil"></i>
            </Link>
            <div class="expiration-date" style="margin-right: 1rem;" :style="[daysLeft(product.created_at, product.category.expiration_days) <= 3 ? {'color':'red'} : {}]">
                {{daysLeft(product.created_at, product.category.expiration_days)}} d
            </div>
            <Link class="btn p-0" style="margin-right: 1rem;" :href="route('products.show', product.id)">
                <i class="bi bi-arrow-right-circle"></i>
            </Link>
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

export default {
    components: {
        BreezeAuthenticatedLayout,
        Pagination,
        Head,
        Link,
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
        }
    }
}

function checkExpiration() {

}

</script>
