import { escapeInject } from 'vite-plugin-ssr'

export function onBeforeRender() {
  const documentProps = {
    // This title and description will override the defaults
    title: 'spa',
    description: 'spa.'
  }
  return {
    pageContext: {
      documentProps
    }
  }
}

export function render() {
  return escapeInject`<!DOCTYPE html>
  <html lang="en">
    <head>
      <meta charset="UTF-8" />
      <link rel="icon" href="" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <meta name="description" content="" />
      <title>spa</title>
    </head>
    <body>
      <div id="app"></div>
    </body>
  </html>`
}
