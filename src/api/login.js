const authApiUrl = 'http://localhost/backend/api_auth.php'

export function loginUser(email, password) {
  return fetch(authApiUrl, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      action: 'login',
      email: email,
      password: password
    })
  })
    .then(response => {
      return response.json()
    })
}
