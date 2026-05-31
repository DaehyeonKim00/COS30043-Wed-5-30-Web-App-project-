const authApiUrl = 'https://mercury.swin.edu.au/cos30043/s104838522/test/backend/api_auth.php'

// Register a new user account
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
    .then(response => response.json())
}
