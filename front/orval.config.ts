import { defineConfig } from 'orval'

export default defineConfig({
  applicationApi: {
    input: './src/lib/api/openapi.json',
    output: {
      mode: 'tags-split',
      target: './src/lib/api/api.ts',
      schemas: './src/lib/api/models',
      client: 'react-query',
      mock: true,
      baseUrl: 'https://myfitness.api.localhost',
      override: {
        mutator: {
          path: './src/lib/api/axios-instance.ts',
          name: 'customInstance',
        },
      },
    },
  },
})
