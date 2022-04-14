export { onBeforeRender }

function onBeforeRender() {
  const documentProps = {
    // This title and description will override the defaults
    title: 'About SpaceX',
    description: 'Our mission is to explore the galaxy.'
  }
  return {
    pageContext: {
      documentProps
    }
  }
}
