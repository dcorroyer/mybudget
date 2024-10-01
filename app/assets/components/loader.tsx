import { useIsFetching, useIsMutating } from '@tanstack/react-query'
import React from 'react'

export default function Loader() {
  const isFetching = useIsFetching()
  const isMutating = useIsMutating()
  if (!isFetching && !isMutating) return null

  return <div>Loading...</div>
}
