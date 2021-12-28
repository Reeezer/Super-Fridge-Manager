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
