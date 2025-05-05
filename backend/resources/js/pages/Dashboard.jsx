import React, { useState, useEffect } from "react";
import AuthWrapper from "@/components/AuthWrapper";
import { DataGrid } from "@mui/x-data-grid";
import {
    Dialog,
    DialogActions,
    DialogContent,
    DialogTitle,
    TextField,
} from "@mui/material";
import styled from "styled-components";
import { Pencil, Trash2 } from "lucide-react";

const IconButtonWrapper = styled.div`
    position: relative;
    display: inline-block;
    overflow: visible;
`;

const ModalButton = styled.button`
    background-color: ${(props) =>
        props.color === "primary" ? "#2563eb" : "#6b7280"};
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    border: none;
    font-weight: 500;
    cursor: pointer;
    margin-left: 8px;
    &:hover {
        background-color: ${(props) =>
            props.color === "primary" ? "#1d4ed8" : "#4b5563"};
    }
`;

const ROLE_ADMIN = 1;
const ROLE_MANAGER = 2;

function Dashboard() {
    const [products, setProducts] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [openModal, setOpenModal] = useState(false);
    const [selectedProduct, setSelectedProduct] = useState(null);
    const [userRole, setUserRole] = useState(null);

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
                    setLoading(false);
                } else {
                    const errorData = await response.json();
                    setError(errorData.message);
                    setLoading(false);
                }
            } catch (error) {
                setError("Ocorreu um erro ao carregar os produtos.");
                setLoading(false);
            }
        };

        const fetchUserRole = async () => {
            try {
                const response = await fetch("/api/v1/me", {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: `Bearer ${localStorage.getItem(
                            "token"
                        )}`,
                    },
                });

                if (response.ok) {
                    const userData = await response.json();
                    setUserRole(userData.data.role_id);
                } else {
                    throw new Error("Erro ao obter dados do usuário");
                }
            } catch (error) {
                console.error("Erro ao buscar dados do usuário:", error);
                setUserRole(null);
            }
        };

        fetchUserRole();
        fetchProducts();
    }, []);

    const handleOpenModal = (row) => {
        if (userRole === ROLE_ADMIN || userRole === ROLE_MANAGER) {
            setSelectedProduct(row);
            setOpenModal(true);
        } else {
            alert("Você não tem permissão para editar este produto.");
        }
    };

    const handleCloseModal = () => {
        setOpenModal(false);
        setSelectedProduct(null);
    };

    const handleSaveEdit = async () => {
        const token = localStorage.getItem("token");
        const updatedProduct = { ...selectedProduct };
        try {
            const response = await fetch(
                updatedProduct.id
                    ? `/api/v1/produtos/${updatedProduct.id}`
                    : "/api/v1/produtos",
                {
                    method: updatedProduct.id ? "PUT" : "POST",
                    headers: {
                        "Content-Type": "application/json",
                        Authorization: `Bearer ${token}`,
                    },
                    body: JSON.stringify(updatedProduct),
                }
            );

            if (response.ok) {
                const data = await response.json();
                setProducts((prev) =>
                    updatedProduct.id
                        ? prev.map((p) => (p.id === data.id ? data : p))
                        : [...prev, data]
                );
                setOpenModal(false);
            } else {
                const errorData = await response.json();
                let errorMessage = "Erro ao salvar o produto.";

                if (errorData.data) {
                    errorMessage += "\nDetalhes:";
                    Object.entries(errorData.data).forEach(([key, value]) => {
                        errorMessage += `\n- ${key}: ${value}`;
                    });
                } else if (errorData.message) {
                    errorMessage += `\n${errorData.message}`;
                }

                alert(errorMessage);
            }
        } catch (error) {
            console.error("Erro ao salvar produto:", error);
            alert("Erro ao salvar.");
        }
    };

    const handleDelete = async (productId) => {
        if (userRole !== ROLE_ADMIN) {
            alert("Você não tem permissão para excluir este produto.");
            return;
        }

        const token = localStorage.getItem("token");
        try {
            const response = await fetch(`/api/v1/produtos/${productId}`, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    Authorization: `Bearer ${token}`,
                },
            });

            if (response.ok) {
                setProducts((prev) =>
                    prev.filter((product) => product.id !== productId)
                );
            } else {
                const errorData = await response.json();
                alert("Erro ao excluir o produto: " + errorData.message);
            }
        } catch (error) {
            console.error("Erro ao excluir produto:", error);
            alert("Erro ao excluir o produto.");
        }
    };

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
                params.value
                    ? new Intl.NumberFormat("pt-BR", {
                          style: "currency",
                          currency: "BRL",
                      }).format(parseFloat(params.value))
                    : "R$ 0,00",
        },
        { field: "category", headerName: "Categoria", width: 150 },
        { field: "sku", headerName: "SKU", width: 150 },
        {
            field: "actions",
            headerName: "Ações",
            width: 150,
            renderCell: (params) => (
                <div className="flex gap-2">
                    <IconButtonWrapper>
                        <button
                            className="p-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                            onClick={() => handleOpenModal(params.row)}
                        >
                            <Pencil size={16} />
                        </button>
                    </IconButtonWrapper>
                    {userRole === ROLE_ADMIN && (
                        <IconButtonWrapper>
                            <button
                                className="p-2 bg-red-500 text-white rounded hover:bg-red-600"
                                onClick={() => handleDelete(params.row.id)}
                            >
                                <Trash2 size={16} />
                            </button>
                        </IconButtonWrapper>
                    )}
                </div>
            ),
        },
    ];

    if (loading) {
        return (
            <div className="flex flex-col items-center justify-center h-screen text-gray-600">
                <p className="text-lg">Carregando...</p>
            </div>
        );
    }

    if (error) {
        return (
            <div className="p-4">
                <p className="text-red-600 font-medium">Erro: {error}</p>
            </div>
        );
    }

    return (
        <AuthWrapper>
            <div className="flex flex-col items-center justify-center min-h-screen bg-green-200">
                <div className="p-8 font-sans w-full max-w-7xl">
                    <div className="flex justify-between items-start mb-6">
                        <div>
                            <h1 className="text-3xl font-bold text-gray-900">
                                Dashboard
                            </h1>
                            <p className="text-gray-700">
                                Bem-vindo ao painel de controle!
                            </p>
                        </div>
                        {userRole === ROLE_ADMIN && (
                            <button
                                className="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
                                onClick={() => {
                                    setSelectedProduct({
                                        name: "",
                                        description: "",
                                        quantity: "",
                                        price: "",
                                        category: "",
                                        sku: "",
                                    });
                                    setOpenModal(true);
                                }}
                            >
                                + Adicionar Produto
                            </button>
                        )}
                    </div>

                    <div className="shadow-lg rounded-lg bg-white">
                        <DataGrid
                            rows={products}
                            columns={columns}
                            pageSize={10}
                            rowsPerPageOptions={[10, 20, 50]}
                            getRowId={(row) => row.id}
                            disableSelectionOnClick
                            autoHeight={false}
                        />
                    </div>
                </div>
            </div>

            <Dialog open={openModal} onClose={handleCloseModal}>
                <DialogTitle>Edição do Produto</DialogTitle>
                <DialogContent>
                    {selectedProduct && (
                        <>
                            <TextField
                                label="Nome"
                                value={selectedProduct.name}
                                onChange={(e) =>
                                    setSelectedProduct({
                                        ...selectedProduct,
                                        name: e.target.value,
                                    })
                                }
                                fullWidth
                                margin="normal"
                            />
                            <TextField
                                label="Descrição"
                                value={selectedProduct.description}
                                onChange={(e) =>
                                    setSelectedProduct({
                                        ...selectedProduct,
                                        description: e.target.value,
                                    })
                                }
                                fullWidth
                                margin="normal"
                            />
                            <TextField
                                label="Quantidade"
                                value={selectedProduct.quantity}
                                onChange={(e) =>
                                    setSelectedProduct({
                                        ...selectedProduct,
                                        quantity: e.target.value,
                                    })
                                }
                                fullWidth
                                margin="normal"
                            />
                            <TextField
                                label="Preço"
                                value={selectedProduct.price}
                                onChange={(e) =>
                                    setSelectedProduct({
                                        ...selectedProduct,
                                        price: e.target.value,
                                    })
                                }
                                fullWidth
                                margin="normal"
                            />
                            <TextField
                                label="Categoria"
                                value={selectedProduct.category}
                                onChange={(e) =>
                                    setSelectedProduct({
                                        ...selectedProduct,
                                        category: e.target.value,
                                    })
                                }
                                fullWidth
                                margin="normal"
                            />
                            <TextField
                                label="SKU"
                                value={selectedProduct.sku}
                                onChange={(e) =>
                                    setSelectedProduct({
                                        ...selectedProduct,
                                        sku: e.target.value,
                                    })
                                }
                                fullWidth
                                margin="normal"
                            />
                        </>
                    )}
                </DialogContent>
                <DialogActions>
                    <ModalButton onClick={handleCloseModal} color="default">
                        Cancelar
                    </ModalButton>
                    <ModalButton onClick={handleSaveEdit} color="primary">
                        Salvar
                    </ModalButton>
                </DialogActions>
            </Dialog>
        </AuthWrapper>
    );
}

export default Dashboard;
