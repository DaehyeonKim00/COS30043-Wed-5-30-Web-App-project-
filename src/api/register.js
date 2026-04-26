const authApiUrl = 'http://localhost/backend/api_auth.php'

export function registerUser(name, email, password) {
  return fetch(authApiUrl, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      action: 'register',
      name: name,
      email: email,
      password: password
    })
  })
    .then(response => {
      return response.json()
    })
}
