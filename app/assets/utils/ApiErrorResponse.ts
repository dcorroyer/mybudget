export type ApiErrorResponse = {
  violations: Violation[]
}

export type Violation = {
  propertyPath: string
  title: string
}
