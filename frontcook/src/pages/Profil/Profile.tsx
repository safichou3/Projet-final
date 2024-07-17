import React, { useEffect, useState, useContext } from 'react';
import axios from 'axios';
import { AuthContext } from '../../context/AuthContext';
import { Navigate } from 'react-router-dom';

const Profile = () => {
    const [profile, setProfile] = useState<any>(null);
    const { auth } = useContext(AuthContext);

    useEffect(() => {
        const fetchProfile = async () => {
            try {
                const response = await axios.post('http://localhost:8000/api/user/getcurrent', {}, {
                    headers: {
                        Authorization: `Bearer ${auth}`
                    }
                });
                setProfile(response.data);
            } catch (error) {
                console.error(error);
            }
        };

        if (auth) {
            fetchProfile();
        }
    }, [auth]);

    if (!auth) {
        return <Navigate to="/login" />;
    }

    if (!profile) return <div>Loading...</div>;

    return (
        <div>
            <h1>Profile</h1>
            {/*<p>Email: {profile.email}</p>*/}
            <p>Nom d'utilisateur: {profile.name}</p>
        </div>
    );
};

export default Profile;
