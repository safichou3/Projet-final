import React, { useState, useContext, useEffect } from 'react';
import axios from 'axios';
import InputForm from "../../components/Form/InputForm";
import { useNavigate } from 'react-router-dom';
import { AuthContext } from '../../context/AuthContext';

const Register = () => {
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [isChef, setIsChef] = useState(false);
    const [agreeTerms, setAgreeTerms] = useState(false);
    const [error, setError] = useState('');
    const [success, setSuccess] = useState(false); // Add success state
    const { setAuth, message, setMessage } = useContext(AuthContext);
    const navigate = useNavigate();

    useEffect(() => {
        if (message) {
            alert(message);
            setMessage('');
        }
    }, [message, setMessage]);

    const handleSubmit = async (e) => {
        e.preventDefault();
        setError('');  // Reset error state
        setSuccess(false);  // Reset success state

        try {
            const response = await axios.post('http://localhost:8000/api/user/register', {
                name,
                email,
                plainPassword: password,
                isChef,
                agreeTerms,
                roles: ['ROLE_USER']
            });

            const token = response.data.token;
            if (token) { // Check if token is returned
                setAuth(token);
                const tokenExpirationTime = new Date().getTime() + (60 * 60 * 1000); // 1 heure
                localStorage.setItem('token', token);
                localStorage.setItem('tokenExpirationTime', String(tokenExpirationTime));
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                setSuccess(true);  // Set success state to true
                setTimeout(() => {
                    navigate('/profile');  // Redirection après connexion réussie
                }, 2000);  // Delay of 2 seconds before redirection
            } else {
                setError('Registration succeeded but no token returned. Please log in.');
            }
        } catch (error) {
            if (axios.isAxiosError(error) && error.response) {
                if (error.response.status === 409) {
                    setError('Email or Username already exists. Please try again with a different email or username.');
                } else {
                    setError('Registration failed. Please try again.');
                }
            } else {
                setError('An unexpected error occurred. Please try again.');
            }
        }
    };

    return (
        <form onSubmit={handleSubmit}>
            <InputForm type="text" label="Name" name="name" id="name" value={name} handleChange={(e) => setName(e.target.value)} />
            <InputForm type="email" label="Email" name="email" id="email" value={email} handleChange={(e) => setEmail(e.target.value)} />
            <InputForm type="password" label="Password" name="plainPassword" id="plainPassword" value={password} handleChange={(e) => setPassword(e.target.value)} />
            <div>
                <label>
                    <input type="checkbox" checked={agreeTerms} onChange={() => setAgreeTerms(!agreeTerms)} />
                    Agree to the terms and conditions
                </label>
            </div>
            <div>
                <label>
                    <input type="checkbox" checked={isChef} onChange={() => setIsChef(!isChef)} />
                    Are you a chef?
                </label>
            </div>
            {error && <p style={{ color: 'red' }}>{error}</p>}
            {success && <p style={{ color: 'green' }}>Inscription réussie ! Redirection vers votre profil...</p>}
            <button type="submit">Register</button>
        </form>
    );
};

export default Register;
