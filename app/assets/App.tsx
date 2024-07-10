import { MantineProvider } from '@mantine/core'
import React from 'react'

import '@mantine/core/styles.css'

function App(): React.JSX.Element {
  return (
    <MantineProvider>
      <div>
        <h1>Hello, world!</h1>
      </div>
    </MantineProvider>
  )
}

export default App
