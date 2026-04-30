var wishlistApiUrl = 'https://mercury.swin.edu.au/cos30043/s104838522/test/backend/api_wishlist.php'

export function getWishlist(userId) {
  return fetch(wishlistApiUrl + '?user_id=' + userId)
    .then(response => {
      return response.json()
    })
    .catch(error => {
      console.error('Error fetching wishlist:', error)
      throw error
    })
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
    .then(response => {
      return response.json()
    })
    .catch(error => {
      console.error('Error adding to wishlist:', error)
      throw error
    })
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
    .then(response => {
      return response.json()
    })
    .catch(error => {
      console.error('Error removing from wishlist:', error)
      throw error
    })
}
