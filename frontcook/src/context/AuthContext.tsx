import React, { createContext, useContext, useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';

interface AuthContextType {
    auth: string | null;
    setAuth: React.Dispatch<React.SetStateAction<string | null>>;
    checkTokenValidity: (token: string | null) => boolean;
    logout: () => void;
}

export const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const AuthProvider = ({ children }: { children: React.ReactNode }) => {
    const [auth, setAuth] = useState<string | null>(localStorage.getItem('token'));
    const navigate = useNavigate();

    const checkTokenValidity = (token: string | null): boolean => {
        if (!token) return false;
        try {
            const payload = JSON.parse(atob(token.split('.')[1]));
            const exp = payload.exp;
            return exp * 1000 > Date.now();
        } catch (error) {
            return false;
        }
    };

    const logout = () => {
        setAuth(null);
        localStorage.removeItem('token');
        navigate('/login', { state: { message: 'Vous avez été déconnecté, veuillez vous reconnecter pour continuer.' } });
    };

    useEffect(() => {
        if (auth && !checkTokenValidity(auth)) {
            logout();
        }
    }, [auth]);

    return (
        <AuthContext.Provider value={{ auth, setAuth, checkTokenValidity, logout }}>
            {children}
        </AuthContext.Provider>
    );
};

export const useAuth = () => {
    const context = useContext(AuthContext);
    if (context === undefined) {
        throw new Error('useAuth must be used within an AuthProvider');
    }
    return context;
};
