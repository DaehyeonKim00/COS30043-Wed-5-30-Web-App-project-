const checkoutApiUrl = 'http://localhost/backend/api_checkout.php'

export function placeOrder(userId, totalPrice, items) {
  return fetch(checkoutApiUrl, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      user_id: userId,
      total_price: totalPrice,
      items: items
    })
  })
    .then(response => {
      return response.json()
    })
}
