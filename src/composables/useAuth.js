import { ref, computed } from 'vue'
import { useStore } from 'vuex'

// Modal/guard composable - UI only. Session/logout is handled by authSession.js
export function useAuth() {
  var store = useStore()

  var user = computed(() => store.state.user)
  var isLoggedIn = computed(() => store.state.isLoggedIn)
  var isAdmin = computed(() => store.state.user && store.state.user.role === 'admin')

  // Modal state
  var showAuthModal = ref(false)
  var authModalMessage = ref('Please log in to continue.')

  // Ensure the user is logged in; opens the auth modal and returns false if not
  function requireAuth(message) {
    if (!store.state.user) {
      authModalMessage.value = message || 'You must be logged in to access this page.'
      showAuthModal.value = true
      return false
    }
    return true
  }

  // Close the auth prompt modal
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
