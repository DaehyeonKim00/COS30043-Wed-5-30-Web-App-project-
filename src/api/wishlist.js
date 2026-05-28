const wishlistApiUrl = 'https://mercury.swin.edu.au/cos30043/s104838522/test/backend/api_wishlist.php'

export function getWishlist(userId) {
  return fetch(wishlistApiUrl + '?user_id=' + userId)
    .then(response => response.json())
}

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
