<template>
  <div class="container mt-4">
    <PageHeader title="Your Cart" />

    <ErrorAlert :message="err" />
    <div v-if="isLoading" class="text-center py-5">Loading cart...</div>

    <!-- Empty cart -->
    <EmptyState
      v-else-if="items.length === 0"
      message="Your cart is empty."
      link-to="/products"
      link-label="Browse Products"
    />

    <!-- Cart items -->
    <div v-else>
      <div v-for="item in items" :key="item.id" class="card mb-3">
        <div class="card-body d-flex align-items-center gap-3">
          <img :src="item.image" :alt="item.name" class="cart-thumb" />

          <div class="flex-grow-1">
            <h5 class="mb-1">{{ item.name }}</h5>
            <p class="text-muted mb-2">
              ${{ Number(item.price).toFixed(2) }} each
            </p>

            <!-- Quantity controls -->
            <div class="d-flex align-items-center gap-2">
              <button
                class="btn btn-sm btn-outline-secondary"
                @click="decreaseQty(item)"
              >
                −
              </button>
              <span class="px-2">{{ item.quantity }}</span>
              <button
                class="btn btn-sm btn-outline-secondary"
                @click="increaseQty(item)"
              >
                +
              </button>
            </div>
          </div>

          <div class="text-end">
            <p class="fw-bold mb-2">
              ${{ (item.price * item.quantity).toFixed(2) }}
            </p>
            <button class="btn btn-sm btn-danger" @click="deleteItem(item.id)">
              Remove
            </button>
          </div>
        </div>
      </div>

      <!-- Total + Checkout -->
      <div class="card mt-3">
        <div
          class="card-body d-flex justify-content-between align-items-center"
        >
          <h4 class="mb-0">Total: ${{ totalPrice }}</h4>
          <router-link to="/checkout" class="btn btn-primary"
            >Proceed to Checkout</router-link
          >
        </div>
      </div>
    </div>
    <!-- Auth Modal — shown when unauthenticated user tries to view cart (advanced feature - tuan) -->
    <AuthPromptModal
      :show="showAuthModal"
      :message="authModalMessage"
      @cancel="onModalCancel"
    />
  </div>
</template>

<script>
import { useAuth } from "../composables/useAuth.js";
import AuthPromptModal from "../components/AuthPromptModal.vue";
import { getCart, removeFromCart, updateCartQuantity } from "../api/cart.js";
import ErrorAlert from "../components/ErrorAlert.vue";
import PageHeader from "../components/PageHeader.vue";
import EmptyState from "../components/EmptyState.vue";
import { readAuthSession, clearAuthSession } from "../utils/authSession.js";

export default {
  name: "Cart",
  components: { ErrorAlert, PageHeader, EmptyState, AuthPromptModal },

  setup() {
    const { showAuthModal, authModalMessage, closeAuthModal } = useAuth();
    return { showAuthModal, authModalMessage, closeAuthModal };
  },

  data() {
    return {
      items: [],
      isLoading: false,
      err: "",
      userId: null,
    };
  },
  computed: {
    totalPrice() {
      return this.items
        .reduce((sum, item) => sum + item.price * item.quantity, 0)
        .toFixed(2);
    },
  },
  mounted() {
    const storeUser = this.$store.state.user;
    // Check authentication (advanced feature - tuan)
    //const savedSession = readAuthSession();
    const savedSession = readAuthSession();
    const sessionUser = storeUser || savedSession.user;

    this.userId = sessionUser ? sessionUser.id : null;

    if (!this.userId) {
      // This conflict with tuan's advance feature implementation.
      //clearAuthSession();
      //this.$store.commit("logout");
      //this.$router.push("/login");


      // Show modal instead of redirect
      this.showAuthModal = true
      //if (!self.$store.state.user) self.showAuthModal = true;
      return;
    }

    this.isLoading = true;
    getCart(this.userId)
      .then((data) => {
        this.items = data;
        this.isLoading = false;
        this.$store.commit("setCart", data);
      })
      .catch(() => {
        this.err = "Failed to load cart.";
        this.isLoading = false;
      });
  },
  methods: {
    onModalCancel() { // User clicked "Cancel" on auth modal (advanced feature - tuan)
      this.showAuthModal = false
      const prev = document.referrer
      // If previous page is login or empty, go home instead
      if (!prev || prev.includes('/login')) {
        this.$router.push('/home')
      } else {
        this.$router.go(-1)
      }
    },
    deleteItem(cartId) {
      removeFromCart(cartId)
        .then(() => {
          this.items = this.items.filter((i) => i.id !== cartId);
          this.$store.dispatch("fetchCart");
        })
        .catch(() => {
          this.err = "Failed to remove item.";
        });
    },
    increaseQty(item) {
      if (parseInt(item.quantity) >= parseInt(item.stock)) {
        this.err =
          "Cannot exceed available stock (" + item.stock + " available)";
        return;
      }

      const newQty = parseInt(item.quantity) + 1;
      updateCartQuantity(item.id, newQty)
        .then((data) => {
          if (data.success) {
            item.quantity = newQty;
            this.err = "";
            this.$store.dispatch("fetchCart");
          }
        })
        .catch(() => {
          this.err = "Failed to update quantity.";
        });
    },
    decreaseQty(item) {
      if (parseInt(item.quantity) <= 1) {
        this.deleteItem(item.id);
        return;
      }

      const newQty = parseInt(item.quantity) - 1;
      updateCartQuantity(item.id, newQty)
        .then((data) => {
          if (data.success) {
            item.quantity = newQty;
            this.$store.dispatch("fetchCart");
          }
        })
        .catch(() => {
          this.err = "Failed to update quantity.";
        });
    },
  },
};
</script>
