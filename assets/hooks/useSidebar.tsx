import { useState } from 'react'

interface SidebarStore {
    isOpen: boolean
    toggle: () => void
}

export const useSidebar = (): SidebarStore => {
    const [isOpen, setIsOpen] = useState(true)

    const toggle = () => {
        setIsOpen((prevIsOpen) => !prevIsOpen)
    }

    return {
        isOpen,
        toggle,
    }
}
