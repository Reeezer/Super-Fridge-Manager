<template>
  <Head title="Welcome" />

  <div>
    <div class="container-fluid fixed-top p-4">
      <div class="col-12">
        <div v-if="canLogin" class="d-flex justify-content-end">
          <div>
            <Link v-if="$page.props.auth.user" href="/products" class="text-muted">
              Products
            </Link>

            <template v-else>
              <Link :href="route('login')" class="text-muted">
                Log in
              </Link>

              <Link v-if="canRegister" :href="route('register')" class="ms-4 text-muted">
                Register
              </Link>
            </template>
          </div>
        </div>
      </div>
    </div>
  </div>

    <div class="home-container">
        <h1 class="title">
            SUPER FRIDGE MANAGER
        </h1>
        <img class="home-img" src="resources/images/fridge.jpg"/>
        <div class="text">
            <h2 style="margin-bottom: 16px;">
                Welcome on Super Fridge Manager !
            </h2>
            <p style="text-align: center;">
                Super Fridge Manger is your next generation, fridge manager. Don't ever forget something in the fridge, for too long! Features include automatic barcode scanning, favourites and automatic Best-Before dates for product of specific categories.
            </p>
        </div>
    </div>
</template>

<script>
import { Link, Head } from "@inertiajs/inertia-vue3";

export default {
  components: {
    Link,
    Head
  },
  props: {
    canLogin: Boolean,
    canRegister: Boolean
  }
};
</script>

<style>

.home-container{
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: auto;
    margin-top: 5%;
    width: 80%;
}

.text {
    width: 80%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.title{
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 72px;
    font-weight: bold;
}

.home-img{
    margin-top: 32px;
    margin-bottom: 32px;
    width: 100%;
    height: auto;
    border-radius: 8px;
}

</style>
