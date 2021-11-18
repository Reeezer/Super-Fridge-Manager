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

    <div class="container">
        <div v-for="product in sortedArray" :key="product.id" class="row align-items-center bg-primary rounded-3 mb-1 p-1">
            <div class="col"><img id='image' :src="'/images/' + product.category.name.toLowerCase() + '.png'"></div>
            <div class="col-7">{{product.name}}</div>
            <div class="col">{{daysLeft(product.created_at, product.category.expiration_days)}} d</div>
            <div class="col">
                <Link class="btn" :href="route('products.show', product.id)"><i class="bi bi-arrow-right-circle"></i></Link>
                <Link class="btn" :href="route('products.edit', product.id)"><i class="bi bi-pencil"></i></Link>
                <button @click="destroy(product.id)" class="btn"><i class="bi bi-trash"></i></button>
            </div>
        </div>
    </div>

    <Pagination class="mt-6" :links="products.links" />
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
</script>
