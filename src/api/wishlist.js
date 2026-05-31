const wishlistApiUrl = 'https://mercury.swin.edu.au/cos30043/s104838522/test/backend/api_wishlist.php'

// Fetch all wishlist items for the given user
export function getWishlist(userId) {
  return fetch(wishlistApiUrl + '?user_id=' + userId)
    .then(response => response.json())
}

// Add a product to the user's wishlist
export function addToWishlist(userId, productId) {
  return fetch(wishlistApiUrl, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      user_id: userId,
      product_id: productId
    })
  })
    .then(response => response.json())
}

// Remove a product from the wishlist by user_id and product_id
export function removeFromWishlist(userId, productId) {
  return fetch(wishlistApiUrl, {
    method: 'DELETE',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      user_id: userId,
      product_id: productId
    })
  })
    .then(response => response.json())
}

// Remove a wishlist item by its wishlist id
export function removeWishlistById(wishlistId) {
  return fetch(wishlistApiUrl, {
    method: 'DELETE',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id: wishlistId
    })
  })
    .then(response => response.json())
}
