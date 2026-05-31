const reviewApiUrl = 'https://mercury.swin.edu.au/cos30043/s104838522/test/backend/api_review.php'

// Fetch all reviews for a specific product
export function getReviews(productId) {
  return fetch(reviewApiUrl + '?product_id=' + productId)
    .then(response => response.json())
}

// Fetch every review across all products
export function getAllReviews() {
  return fetch(reviewApiUrl)
    .then(response => response.json())
}

// Submit a new review for a product
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
    .then(response => response.json())
}

// Update an existing review's rating and comment
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
    .then(response => response.json())
}

// Delete a review by its id
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
    .then(response => response.json())
}
