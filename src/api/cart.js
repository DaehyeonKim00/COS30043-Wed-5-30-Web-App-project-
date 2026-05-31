const cartApiUrl = 'https://mercury.swin.edu.au/cos30043/s104838522/test/backend/api_cart.php'

// Fetch all cart items for the given user
export function getCart(userId) {
  return fetch(cartApiUrl + '?user_id=' + userId)
    .then(response => response.json())
}

// Add a product to the cart (forceQuantity overwrites the quantity instead of adding to it)
export function addToCart(userId, productId, quantity, forceQuantity = false) {
  return fetch(cartApiUrl, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      user_id: userId,
      product_id: productId,
      quantity: quantity,
      force_quantity: forceQuantity
    })
  })
    .then(response => response.json())
}

// Remove a single item from the cart by its cart id
export function removeFromCart(id) {
  return fetch(cartApiUrl, {
    method: 'DELETE',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id: id
    })
  })
    .then(response => response.json())
}

// Update the quantity of a single cart item
export function updateCartQuantity(cartId, quantity) {
  return fetch(cartApiUrl, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id: cartId,
      quantity: quantity
    })
  })
    .then(response => response.json())
}
