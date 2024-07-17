import axios from "axios";

const BASE_URL = 'http://localhost:8000/api/recipe';

export const getAllRecipes = async (link = '') => {
    const response = await axios.get(link || BASE_URL);
    return response.data;
};

export const getOneRecipe = async (id) => {
    const response = await axios.get(`${BASE_URL}/${id}`);
    return response.data;
};

export async function apiAddRecipe(data = {}) {
    console.log("Sending data:", data);
    const res = await fetch(`${BASE_URL}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    });

    const responseText = await res.text();
    console.log("Response text:", responseText);

    try {
        return JSON.parse(responseText);
    } catch (error) {
        console.error("Failed to parse response as JSON:", error);
        throw new Error("Invalid JSON response");
    }
}

export const apiEditRecipe = async (id, data) => {
    const response = await axios.put(`${BASE_URL}/${id}`, data);
    return response.data;
};

export const apiDeleteCategory = async (id) => {
    const response = await axios.delete(`${BASE_URL}/${id}`);
    return response.data;
};