const USERS_KEY = 'music_shop_users'

export function registerUser(userData) {
  const users = JSON.parse(localStorage.getItem(USERS_KEY)) || []

  const newUser = {
    id: Date.now(),
    name: userData.name,
    email: userData.email,
    password: userData.password
  }

  users.push(newUser)
  localStorage.setItem(USERS_KEY, JSON.stringify(users))

  return newUser
}