var reviewApiUrl = 'https://mercury.swin.edu.au/cos30043/s104838522/test/backend/api_review.php'

export function getReviews(productId) {
  return fetch(reviewApiUrl + '?product_id=' + productId)
    .then(response => {
      return response.json()
    })
    .catch(error => {
      console.error('Error fetching reviews:', error)
      throw error
    })
}

export function getAllReviews() {
  return fetch(reviewApiUrl)
    .then(response => {
      return response.json()
    })
    .catch(error => {
      console.error('Error fetching all reviews:', error)
      throw error
    })
}

export function postReview(userId, productId, rating, comment) {
  return fetch(reviewApiUrl, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      user_id: userId,
      product_id: productId,
      rating: rating,
      comment: comment
    })
  })
    .then(response => {
      return response.json()
    })
    .catch(error => {
      console.error('Error posting review:', error)
      throw error
    })
}

export function updateReview(id, rating, comment) {
  return fetch(reviewApiUrl, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id: id,
      rating: rating,
      comment: comment
    })
  })
    .then(response => {
      return response.json()
    })
    .catch(error => {
      console.error('Error updating review:', error)
      throw error
    })
}

export function deleteReview(id) {
  return fetch(reviewApiUrl, {
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
    .catch(error => {
      console.error('Error deleting review:', error)
      throw error
    })
}
