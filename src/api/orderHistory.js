var ordersApiUrl = 'https://mercury.swin.edu.au/cos30043/s104838522/test/backend/api_orders.php'

export function getOrders(userId) {
  return fetch(ordersApiUrl + '?user_id=' + userId)
    .then(response => {
      return response.json()
    })
    .catch(error => {
      console.error('Error fetching orders:', error)
      throw error
    })
}
