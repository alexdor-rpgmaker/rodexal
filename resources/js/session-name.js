export default (sessionId) => {
  let sessionName = (sessionId < 10) ? '0' + sessionId : sessionId
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

  return '20' + sessionName
}
