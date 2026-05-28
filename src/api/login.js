const authApiUrl = 'https://mercury.swin.edu.au/cos30043/s104838522/test/backend/api_auth.php'

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
    .then(response => response.json())
}
