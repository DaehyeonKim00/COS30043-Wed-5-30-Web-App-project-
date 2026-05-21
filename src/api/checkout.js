const checkoutApiUrl = 'https://mercury.swin.edu.au/cos30043/s104838522/test/backend/api_checkout.php'

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
    .then(response => response.json())
    .catch(error => {
      console.error('Error placing order:', error)
      throw error
    })
}
