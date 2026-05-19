const CART_KEY = 'music_shop_cart'

export function getCart() {
  return JSON.parse(localStorage.getItem(CART_KEY)) || []
}

export function saveCart(cart) {
  localStorage.setItem(CART_KEY, JSON.stringify(cart))
}

export function addToCart(product) {
  const cart = getCart()

  const existingItem = cart.find(item => item.id === product.id)

  if (existingItem) {
    existingItem.quantity += 1
  } else {
    cart.push({
      ...product,
      quantity: 1
    })
  }

  saveCart(cart)
}

export function removeFromCart(id) {
  const cart = getCart().filter(item => item.id !== id)
  saveCart(cart)
}

export function clearCart() {
  localStorage.removeItem(CART_KEY)
}