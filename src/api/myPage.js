var myPageApiUrl = 'https://mercury.swin.edu.au/cos30043/s104838522/test/backend/api_mypage.php'

export function getUserInfo(userId) {
  return fetch(myPageApiUrl + '?user_id=' + userId)
    .then(response => {
      return response.json()
    })
    .catch(error => {
      console.error('Error fetching user info:', error)
      throw error
    })
}

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
    .then(response => {
      return response.json()
    })
    .catch(error => {
      console.error('Error updating user info:', error)
      throw error
    })
}
