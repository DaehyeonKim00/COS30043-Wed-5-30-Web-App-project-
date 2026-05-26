// src/composables/useAuth.js
// This file defines a Vue 3 composable that provides reactive state and functions for managing user authentication and authorization.
// It uses the Vuex store to access user information and provides functions to check if a user is logged in, if they are an admin, and to log out. 
// It also includes a function to require authentication for certain actions, which can show a modal prompt if the user is not logged in.
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useStore } from 'vuex'

export function useAuth() {
  const router = useRouter()
  const store = useStore()

  const user = computed(() => store.state.user)
  const isLoggedIn = computed(() => store.state.isLoggedIn)
  const isAdmin = computed(() => store.state.user?.role === 'admin')

  // Modal state
  const showAuthModal = ref(false)
  const authModalMessage = ref('Please log in to continue.')

  function requireAuth(message = 'You must be logged in to access this page.') {
    if (!store.state.user) {
    // Show alert then redirect
      authModalMessage.value = message
      showAuthModal.value = true
      return false
    }
    return true
  }

  function closeAuthModal() {
    showAuthModal.value = false
  }

  function requireAdmin() {
    if (!store.state.user || store.state.user.role !== 'admin') {
      router.push('/home')
      return false
    }
    return true
  }

  function logout() {
    localStorage.removeItem('user')
    store.commit('logout')
    router.push('/login')
  }

  return {
    user, isLoggedIn, isAdmin,
    requireAuth, requireAdmin, logout,
    showAuthModal, authModalMessage, closeAuthModal
  }
}