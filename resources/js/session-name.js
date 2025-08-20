// noinspection JSUnusedGlobalSymbols
export default (givenSessionId) => {
  const sessionId = parseInt(givenSessionId, 10)
  let sessionName = String(sessionId + 2000)

  if (sessionId === 3) {
    sessionName += '-2004'
  }

  if (sessionId === 16) {
    sessionName += '-2017'
  }

  if (sessionId === 17) {
    sessionName += '-2018'
  }

  if (sessionId === 23) {
    sessionName += '-2024'
  }

  return sessionName
}
