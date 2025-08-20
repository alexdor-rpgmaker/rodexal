import sessionName from '@/session-name'

describe('Session Name', () => {
  it.each([
    [3, '2003-2004'],
    ['3', '2003-2004'],
    [9, '2009'],
    [10, '2010'],
    ['10', '2010'],
    [16, '2016-2017'],
    [17, '2017-2018'],
    ['17', '2017-2018'],
    [23, '2023-2024'],
    [25, '2025']
  ])('when session ID is "%i" then returns "%s"', (input, expected) => {
    expect(sessionName(input)).toBe(expected)
  })
})
