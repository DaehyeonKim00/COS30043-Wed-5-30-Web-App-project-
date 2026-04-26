const myPageApiUrl = 'http://localhost/backend/api_mypage.php'

export function getUserInfo(userId) {
  return fetch(`${myPageApiUrl}?user_id=${userId}`)
    .then(response => {
      return response.json()
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
}
