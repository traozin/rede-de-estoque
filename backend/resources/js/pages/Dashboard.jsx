import React, { useState, useEffect } from "react";
import AuthWrapper from "@/components/AuthWrapper";

function Dashboard() {
    const [products, setProducts] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        const token = localStorage.getItem("token");

        const fetchProducts = async () => {
            try {
                const response = await fetch("/api/v1/produtos", {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: `Bearer ${token}`,
                    },
                });

                if (response.ok) {
                    const data = await response.json();
                    setProducts(data.data);
                } else {
                    const errorData = await response.json();
                    setError(errorData.message);
                }
            } catch (error) {
                setError("Ocorreu um erro ao carregar os produtos.");
            } finally {
                setLoading(false);
            }
        };

        fetchProducts();
    }, []);

    if (loading) {
        return <div>Carregando...</div>;
    }

    if (error) {
        return <div>Erro: {error}</div>;
    }

    return (
        <AuthWrapper>
            <div style={{ padding: "20px", fontFamily: "Arial, sans-serif" }}>
                <h1>Dashboard</h1>
                <p>Bem-vindo ao painel de controle!</p>
            </div>
        </AuthWrapper>
    );
}

export default Dashboard;
