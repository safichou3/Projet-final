// App.tsx
import './App.scss';
import Header from "./components/Front/Header/Header";
import Footer from "./components/Front/Footer/Footer";
import { Outlet } from "react-router-dom";
import { AuthProvider } from './context/AuthContext';
import CartProvider from "./context/CartContext";
import StripeContainer from "./components/Stripe/StripeContainer";

function App() {
    return (
        <AuthProvider>
            <CartProvider>
                <StripeContainer>
                    <div>
                        <Header />
                        <Outlet />
                        <Footer />
                    </div>
                </StripeContainer>
            </CartProvider>
        </AuthProvider>
    );
}

export default App;
