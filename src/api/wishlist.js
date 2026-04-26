const wishlistApiUrl = 'http://localhost/backend/api_wishlist.php'

export function getWishlist(userId) {
  return fetch(`${wishlistApiUrl}?user_id=${userId}`)
    .then(response => {
      return response.json()
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
}

export function removeFromWishlistById(id) {
  return fetch(wishlistApiUrl, {
    method: 'DELETE',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id: id
    })
  })
    .then(response => {
      return response.json()
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
}
