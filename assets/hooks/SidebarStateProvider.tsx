import React, {createContext, useContext, useEffect, useMemo, useState} from 'react'

interface SidebarContextType {
    isOpen: boolean
    toggle: () => void
}

const SidebarContext = createContext<SidebarContextType>({
    isOpen: true,
    toggle: () => {},
})

const SidebarStateProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
    const [isOpen, setIsOpen] = useState<boolean>(() => {
        const storedValue = localStorage.getItem('isSidebarOpen');
        return storedValue ? JSON.parse(storedValue) : true;
    });

    const toggle = () => {
        setIsOpen((prevIsOpen) => !prevIsOpen);
    };

    useEffect(() => {
        localStorage.setItem('isSidebarOpen', JSON.stringify(isOpen));
    }, [isOpen]);

    const contextValue = useMemo(() => {
        return {
            isOpen,
            toggle,
        }
    }, [isOpen])

    return <SidebarContext.Provider value={contextValue}>{children}</SidebarContext.Provider>
}

export const useSidebar = () => {
    return useContext(SidebarContext)
}

export default SidebarStateProvider
