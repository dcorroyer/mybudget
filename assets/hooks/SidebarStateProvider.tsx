import React, {createContext, useContext, useEffect, useMemo, useState} from 'react'

interface SidebarContextType {
    isOpen: boolean
    openItem: string
    lastOpenItem: string
    setOpenItem: (openItem: string) => void
    setLastOpenItem: (lastOpenItem: string) => void
    toggle: () => void
}

const SidebarContext = createContext<SidebarContextType>({
    isOpen: true,
    openItem: '',
    lastOpenItem: '',
    setOpenItem: () => {},
    setLastOpenItem: () => {},
    toggle: () => {},
})

const SidebarStateProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
    const [isOpen, setIsOpen] = useState(true)
    const [lastOpenItem, setLastOpenItem] = useState('')
    const [openItem, setOpenItem] = useState('')

    const toggle = () => {
        setIsOpen((prevIsOpen) => !prevIsOpen)
    }

    useEffect(() => {
        if (isOpen) {
            setOpenItem(lastOpenItem)
        } else {
            setLastOpenItem(openItem)
            setOpenItem('')
        }
    }, [isOpen])

    const contextValue = useMemo(() => {
        return {
            isOpen,
            toggle,
            openItem,
            lastOpenItem,
            setOpenItem,
            setLastOpenItem
        }
    }, [isOpen])

    return (
        <SidebarContext.Provider value={contextValue}>
            {children}
        </SidebarContext.Provider>
    )
}

export const useSidebar = () => {
    return useContext(SidebarContext)
}

export default SidebarStateProvider