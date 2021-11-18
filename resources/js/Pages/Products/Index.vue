<template>
  <Head title="Produits" />

  <breeze-authenticated-layout>
    <template #header>
      <h2 class="h4 font-weight-bold">
        Produits
      </h2>
    </template>
    <!-- Page content here -->

    <h1>Produits</h1>

    <Link :href="route('products.create')" class="btn btn-primary mb-2">Ajouter un produit</Link>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Days left</th>
                <th scope="col">Categorie</th>
                <th scope="col">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="product in sortedArray" :key="product.id">
                <td>{{product.name}}</td>
                <td>{{daysLeft(product.created_at, product.category.expiration_days)}} d</td>
                <td>{{product.category.name}}</td>
                <td>
                    <Link class="btn" :href="route('products.show', product.id)"><i class="bi bi-arrow-right-circle"></i></Link>
                    <Link class="btn" :href="route('products.edit', product.id)"><i class="bi bi-pencil"></i></Link>
                    <button @click="destroy(product.id)" class="btn"><i class="bi bi-trash"></i></button>
                </td>
            </tr>
        </tbody>
    </table>

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
