const USER_KEY = 'user'
const REMEMBER_KEY = 'rememberMe'
const EXPIRES_AT_KEY = 'expiresAt'
const DEFAULT_SESSION_MINUTES = 30

function parseJSON(value) {
  if (!value) return null
  try {
    return JSON.parse(value)
  } catch (error) {
    return null
  }
}

export function saveAuthSession(
  user,
  rememberMe,
  expiresAt = Date.now() + DEFAULT_SESSION_MINUTES * 60 * 1000
) {
  var storage = rememberMe ? localStorage : sessionStorage
  var otherStorage = rememberMe ? sessionStorage : localStorage

  storage.setItem(USER_KEY, JSON.stringify(user))
  storage.setItem(REMEMBER_KEY, rememberMe ? '1' : '0')
  storage.setItem(EXPIRES_AT_KEY, String(expiresAt))
  otherStorage.removeItem(USER_KEY)
  otherStorage.removeItem(REMEMBER_KEY)
  otherStorage.removeItem(EXPIRES_AT_KEY)
}

export function readAuthSession() {
  var sessionUser = parseJSON(sessionStorage.getItem(USER_KEY))
  if (sessionUser) {
    return {
      user: sessionUser,
      rememberMe: sessionStorage.getItem(REMEMBER_KEY) === '1',
      expiresAt: Number(sessionStorage.getItem(EXPIRES_AT_KEY)) || null
    }
  }

  var storedUser = parseJSON(localStorage.getItem(USER_KEY))
  if (storedUser) {
    return {
      user: storedUser,
      rememberMe: localStorage.getItem(REMEMBER_KEY) === '1',
      expiresAt: Number(localStorage.getItem(EXPIRES_AT_KEY)) || null
    }
  }

  return {
    user: null,
    rememberMe: false,
    expiresAt: null
  }
}

export function clearAuthSession() {
  sessionStorage.removeItem(USER_KEY)
  sessionStorage.removeItem(REMEMBER_KEY)
  sessionStorage.removeItem(EXPIRES_AT_KEY)
  localStorage.removeItem(USER_KEY)
  localStorage.removeItem(REMEMBER_KEY)
  localStorage.removeItem(EXPIRES_AT_KEY)
}

export function formatDuration(msRemaining) {
  var safeMs = Math.max(0, msRemaining)
  var totalSeconds = Math.floor(safeMs / 1000)
  var minutes = Math.floor(totalSeconds / 60)
  var seconds = totalSeconds % 60

  return String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0')
}
