const myPageApiUrl = 'https://mercury.swin.edu.au/cos30043/s104838522/test/backend/api_mypage.php'

// Fetch the profile information of a single user
export function getUserInfo(userId) {
  return fetch(myPageApiUrl + '?user_id=' + userId)
    .then(response => response.json())
}

// Update a user's name and email
export function updateUserInfo(userId, name, email) {
  return fetch(myPageApiUrl, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      user_id: userId,
      name: name,
      email: email
    })
  })
    .then(response => response.json())
}
