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
  <AuthPromptModal
    :show="showAuthModal"
    :message="authModalMessage"
    @cancel="onModalCancel"
  />
</template>

<script>
import { removeFromCart, updateCartQuantity, getCart } from "../api/cart.js";
import ErrorAlert from "../components/ErrorAlert.vue";
import PageHeader from "../components/PageHeader.vue";
import EmptyState from "../components/EmptyState.vue";
import AuthPromptModal from "../components/AuthPromptModal.vue";
import { useAuth } from "../composables/useAuth.js";
import { readAuthSession, clearAuthSession } from "../utils/authSession.js";

export default {
  name: "Cart",
  components: { ErrorAlert, PageHeader, EmptyState, AuthPromptModal },
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
  setup() {
    const { showAuthModal, authModalMessage, closeAuthModal, requireAuth } =
      useAuth();
    return { showAuthModal, authModalMessage, closeAuthModal, requireAuth };
  },
  mounted() {
    // Retrieve stored details from authSession.
    const session = readAuthSession();
    if (session) {
      this.$store.commit("setUser", session.user || null);
      this.$store.commit("setRememberMe", !!session.rememberMe);
      if (session.expiresAt) {
        this.$store.commit("setExpiresAt", session.expiresAt);
      }
    }

    // Check if session is fresh (not just if user exists)
    const hasFreshAuth = Boolean(
      session?.expiresAt && Date.now() < session.expiresAt,
    );
    const isExpired =
      session?.user && !hasFreshAuth && !this.$store.state.rememberMe;

    // Handle expiry directly
    if (isExpired) {
      clearAuthSession();
      this.$store.commit("logout");
      this.showAuthModal = true; // Directly set modal
      this.authModalMessage = "Your session has expired. Please log in again.";
      return; // Stop here
    }

    // Handle no user/guest edge case
    if (!this.$store.state.user) {
      this.showAuthModal = true;
      return;
    }

    // IfsSession is valid, load cart
    this.userId = this.$store.state.user?.id || null;

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
    onModalCancel() {
      this.closeAuthModal();
      const prev = document.referrer;
      if (!prev || prev.includes("/login")) {
        this.$router.push("/home");
      } else {
        this.$router.go(-1);
      }
    },
    onModalCancel() {
      // User clicked "Cancel" on auth modal (advanced feature - tuan)
      this.showAuthModal = false;
      const prev = document.referrer;
      // If previous page is login or empty, go home instead
      if (!prev || prev.includes("/login")) {
        this.$router.push("/home");
      } else {
        this.$router.go(-1);
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
