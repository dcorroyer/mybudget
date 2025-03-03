import { defineConfig } from 'orval'

export default defineConfig({
  mybudget: {
    input: {
      target: './assets/api/api-doc.json',
    },
    output: {
      mode: 'tags-split',
      target: './assets/api/generated',
      client: 'react-query',
      prettier: true,
      schemas: './assets/api/models',
      override: {
        mutator: {
          path: './assets/api/axiosInstance.ts',
          name: 'customInstance',
        },
        query: {
          useQuery: true,
          useInfinite: true,
          useInfiniteQueryParam: 'page',
          options: {
            staleTime: 10000,
          },
        },
        useDates: true,
      },
    },
  },
})
