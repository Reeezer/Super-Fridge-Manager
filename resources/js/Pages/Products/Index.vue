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
                <th scope="col">Nome</th>
                <th scope="col">Code EAN</th>
                <th scope="col">Expiration</th>
                <th scope="col">Categorie</th>
                <th scope="col">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="product in products.data" :key="product.id">
                <td>{{product.name}}</td>
                <td>{{product.ean_code}}</td>
                <td>{{product.category.expiration_days}} d</td>
                <td>{{product.category.name}}</td>
                <td>
                    <Link class="btn btn-info" :href="route('products.show', product.id)"><i class="bi bi-arrow-right-circle"></i></Link>
                    <Link class="btn btn-primary" :href="route('products.edit', product.id)"><i class="bi bi-pencil"></i></Link>
                    <button @click="destroy(product.id)" class="btn btn-danger"><i class="bi bi-trash"></i></button>
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
          Inertia.delete(route('products.destroy', id));
      }
  }
}
</script>
