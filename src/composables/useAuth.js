// This file defines a Vue 3 composable that provides reactive state and functions for managing user authentication and authorization.
// It uses the Vuex store to access user information and provides functions to check if a user is logged in, and to show a modal prompt if the user is not logged in.
import { ref, computed } from 'vue'
import { useStore } from 'vuex'

// Modal/guard composable — UI only. Session/logout is handled by authSession.js
export function useAuth() {
  var store = useStore()

  var user = computed(() => store.state.user)
  var isLoggedIn = computed(() => store.state.isLoggedIn)
  var isAdmin = computed(() => store.state.user && store.state.user.role === 'admin')

  // Modal state
  var showAuthModal = ref(false)
  var authModalMessage = ref('Please log in to continue.')

  function requireAuth(message) {
    if (!store.state.user) {
      authModalMessage.value = message || 'You must be logged in to access this page.'
      showAuthModal.value = true
      return false
    }
    return true
  }

  function closeAuthModal() {
    showAuthModal.value = false
  }

  return {
    user,
    isLoggedIn,
    isAdmin,
    requireAuth,
    showAuthModal,
    authModalMessage,
    closeAuthModal
  }
}

export default useAuth
