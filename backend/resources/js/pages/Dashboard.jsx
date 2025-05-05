import React, { useState, useEffect } from "react";
import AuthWrapper from "@/components/AuthWrapper";
import { DataGrid } from "@mui/x-data-grid";

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

    const columns = [
        { field: "id", headerName: "ID", width: 70 },
        { field: "name", headerName: "Nome", width: 200 },
        { field: "description", headerName: "Descrição", width: 250 },
        { field: "quantity", headerName: "Quantidade", width: 120 },
        {
            field: "price",
            headerName: "Preço",
            width: 120,
            valueFormatter: (value) =>
                value
                    ? new Intl.NumberFormat("pt-BR", {
                          style: "currency",
                          currency: "BRL",
                      }).format(value)
                    : "R$ 0,00",
        },
        { field: "category", headerName: "Categoria", width: 150 },
        { field: "sku", headerName: "SKU", width: 150 },
    ];

    return (
        <AuthWrapper>
            <div style={{ padding: "20px", fontFamily: "Arial, sans-serif" }}>
                <h1>Dashboard</h1>
                <div style={{ height: 600, width: "100%", marginTop: "20px" }}>
                    <DataGrid
                        rows={products}
                        columns={columns}
                        pageSize={10}
                        rowsPerPageOptions={[10, 20, 50]}
                        getRowId={(row) => row.id}
                    />
                </div>
            </div>
        </AuthWrapper>
    );
}

export default Dashboard;
