var figlet = require('figlet')

figlet(
  'POP',
  {
    font: 'Alpha',
    horizontalLayout: 'default',
    verticalLayout: 'default'
  },
  function(err, data) {
    if (err) {
      console.log('Something went wrong...')
      console.dir(err)
      return
    }
    console.log(data)
  }
)
