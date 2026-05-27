import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useStore } from 'vuex'

// Modal/guard composable — UI only. Session/logout is handled by authSession.js
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

  return {
    user, isLoggedIn, isAdmin,
    requireAuth, requireAdmin,
    showAuthModal, authModalMessage, closeAuthModal
  }
}

export default useAuth
