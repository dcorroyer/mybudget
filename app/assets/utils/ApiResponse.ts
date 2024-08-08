export type ApiResponse<T> = {
  data: T
}

export type ApiResponseList<T> = {
  data: T
  meta: Meta
}

export type Meta = {
  currentPage: number
  from: number
  hasMore: boolean
  perPage: number
  to: number
  total: number
  totalPages: number
}
