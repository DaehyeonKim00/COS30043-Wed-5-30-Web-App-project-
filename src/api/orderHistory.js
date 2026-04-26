const ordersApiUrl = 'http://localhost/backend/api_orders.php'

export function getOrders(userId) {
  return fetch(`${ordersApiUrl}?user_id=${userId}`)
    .then(response => {
      return response.json()
    })
}
