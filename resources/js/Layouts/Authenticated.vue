<template>
  <div>
    <nav class="navbar navbar-expand-md navbar-light bg-white border-bottom sticky-top">
      <div class="container">

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <!-- Left Side Of Navbar -->
          <ul class="navbar-nav me-auto">
            <breeze-nav-link :href="route('products.index')" :active="route().current('products.*')">
              Products
            </breeze-nav-link>

            <breeze-nav-link :href="route('favorites.index')" :active="route().current('favorites.*')">
              Favorites
            </breeze-nav-link>

            <breeze-nav-link :href="route('products.create')" :active="route().current('products.create')">
              Add a product
            </breeze-nav-link>
          </ul>

          <!-- Right Side Of Navbar -->
          <ul class="navbar-nav align-items-baseline">
            <!-- Authentication Links -->
            <breeze-dropdown id="settingsDropdown">
              <template #trigger>
                {{ $page.props.auth.user.name }}

                <svg class="ms-2" width="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </template>

              <template #content>
                <!-- Authentication -->
                <breeze-dropdown-link @click="logout" :as="'button'">
                  Log Out
                </breeze-dropdown-link>
              </template>
            </breeze-dropdown>
          </ul>
        </div>
      </div>
    </nav>

    <notifications position='bottom left' width="30%" height="5%" duration="4" />

    <!-- Page Content -->
    <main class="container my-3">
        <slot/>
    </main>
  </div>
</template>

<script>
import BreezeDropdown from '@/Components/Dropdown.vue'
import BreezeDropdownLink from '@/Components/DropdownLink.vue'
import BreezeNavLink from '@/Components/NavLink.vue'
import { Link } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'

export default {
  components: {
    BreezeDropdown,
    BreezeDropdownLink,
    BreezeNavLink,
    Link,
  },

  data() {
    return {
      showingNavigationDropdown: false,
    }
  },

  methods:{
      logout(){
          Inertia.post(route("logout"));
      }
  }
}
</script>
