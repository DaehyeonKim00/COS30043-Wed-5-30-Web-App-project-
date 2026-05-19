const USER_KEY = 'music_shop_user'

export function loginUser(email, password) {
  const user = {
    email: email,
    name: email.split('@')[0],
    isLoggedIn: true
  }

  localStorage.setItem(USER_KEY, JSON.stringify(user))
  return user
}

export function logoutUser() {
  localStorage.removeItem(USER_KEY)
}

export function getCurrentUser() {
  return JSON.parse(localStorage.getItem(USER_KEY)) || null
}