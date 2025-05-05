import React, { useState, useEffect } from "react";
import AuthWrapper from "@/components/AuthWrapper";
import { DataGrid } from "@mui/x-data-grid";
import styled from "styled-components";

const Container = styled.div`
    padding: 2rem;
    font-family: "Arial", sans-serif;
    @media (max-width: 768px) {
        padding: 1rem;
    }
`;

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
                    // Simula carregamento de 2s antes de exibir
                    setTimeout(() => {
                        setProducts(data.data);
                        setLoading(false);
                    }, 2000);
                } else {
                    const errorData = await response.json();
                    setError(errorData.message);
                }
            } catch (error) {
                setError("Ocorreu um erro ao carregar os produtos.");
            } finally {
                setTimeout(() => {
                    setLoading(false);
                }, 2000);
            }
        };

        fetchProducts();
    }, []);

    const columns = [
        { field: "id", headerName: "ID", width: 70 },
        { field: "name", headerName: "Nome", width: 200 },
        { field: "description", headerName: "Descrição", width: 250 },
        { field: "quantity", headerName: "Quantidade", width: 120 },
        {
            field: "price",
            headerName: "Preço",
            width: 120,
            valueFormatter: (params) =>
                params
                    ? new Intl.NumberFormat("pt-BR", {
                          style: "currency",
                          currency: "BRL",
                      }).format(parseFloat(params))
                    : "R$ 0,00",
        },
        { field: "category", headerName: "Categoria", width: 150 },
        { field: "sku", headerName: "SKU", width: 150 },
    ];

    if (loading) {
        return (
            <div className="flex flex-col items-center justify-center h-screen text-gray-600">
                <div className="animate-spin rounded-full h-12 w-12 border-t-4 border-blue-500 border-solid mb-4"></div>
                <p className="text-lg">Carregando...</p>
            </div>
        );
    }

    if (error) {
        return (
            <Container>
                <p className="text-red-600 font-medium">Erro: {error}</p>
            </Container>
        );
    }

    return (
        <AuthWrapper>
            <Container>
                <h1 className="text-3xl font-bold mb-2 text-gray-800">
                    Dashboard
                </h1>
                <p className="text-gray-600 mb-6">
                    Bem-vindo ao painel de controle!
                </p>
                <div className="shadow-lg rounded-lg bg-white">
                    <DataGrid
                        rows={products}
                        columns={columns}
                        pageSize={10}
                        rowsPerPageOptions={[10, 20, 50]}
                        getRowId={(row) => row.id}
                    />
                </div>
            </Container>
        </AuthWrapper>
    );
}

export default Dashboard;
