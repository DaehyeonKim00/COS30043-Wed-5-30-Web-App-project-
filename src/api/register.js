// Register.vue is in test mode (no backend call) because the Mercury
// server's PHP does not support password_hash() in api_auth.php.
// This API file is commented out — uncomment it once real auth is added.

 const authApiUrl = 'https://mercury.swin.edu.au/cos30043/s104838522/test/backend/api_auth.php'

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
     .catch(error => {
       console.error('Error registering:', error)
       throw error
     })
 }
