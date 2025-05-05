import React, { useState } from "react";
import { router } from "@inertiajs/react";

function Register() {
    const [name, setName] = useState("");
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [confirmPassword, setConfirmPassword] = useState("");
    const [error, setError] = useState("");

    const handleSubmit = async (e) => {
        e.preventDefault();

        const passwordSanitized = password.trim();
        const confirmPasswordSanitized = confirmPassword.trim();

        if (passwordSanitized !== confirmPasswordSanitized) {
            setError("As senhas não coincidem.");
            return;
        }

        try {
            const response = await fetch("/api/v1/register", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ 
                    name: name, 
                    email: email, 
                    password: passwordSanitized, 
                    password_confirmation: confirmPasswordSanitized
                }),
            });

            if (response.ok) {
                localStorage.setItem(
                    "flash_success",
                    "Registro realizado com sucesso!"
                );

                router.push({
                    url: "/login",
                    component: "Login",
                });
            } else {
                const errorData = await response.json();
                let errorMessage = "Erro ao registrar.";

                if (errorData.data) {
                    errorMessage += "\nDetalhes:";
                    Object.entries(errorData.data).forEach(([key, value]) => {
                        if (Array.isArray(value)) {
                            value.forEach((item) => {
                                errorMessage += `\n- ${key}: ${item}`;
                            });
                        } else {
                            errorMessage += `\n- ${key}: ${value}`;
                        }
                    });
                } else if (errorData.message) {
                    errorMessage += `\n${errorData.message}`;
                }

                setError(errorMessage);
            }
        } catch (error) {
            console.error("Erro:", error);
            setError("Erro ao registrar. Tente novamente.");
        }
    };

    function ErrorMessage({ message }) {
        const messageLines = message.split("\n");
    
        return (
            <div className="text-red-500">
                {messageLines.map((line, index) => (
                    <div key={index}>{line}</div>
                ))}
            </div>
        );
    }

    return (
        <div className="flex items-center justify-center min-h-screen bg-blue-200">
            <div className="w-full max-w-3xl p-8 space-y-6 bg-white rounded shadow-md">
                <h2 className="text-2xl font-bold text-center text-gray-800">
                    Registro
                </h2>

                <ErrorMessage message={error} />

                <form className="space-y-4" onSubmit={handleSubmit}>
                    <div>
                        <label className="block text-sm font-medium text-gray-700">
                            Nome
                        </label>
                        <input
                            type="text"
                            value={name}
                            onChange={(e) => setName(e.target.value)}
                            className="w-full px-3 py-2 mt-1 border rounded-md focus:outline-none focus:ring focus:ring-blue-300"
                            placeholder="Digite seu nome"
                        />
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-gray-700">
                            Email
                        </label>
                        <input
                            type="email"
                            value={email}
                            onChange={(e) => setEmail(e.target.value)}
                            className="w-full px-3 py-2 mt-1 border rounded-md focus:outline-none focus:ring focus:ring-blue-300"
                            placeholder="Digite seu email"
                        />
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-gray-700">
                            Senha
                        </label>
                        <input
                            type="password"
                            value={password}
                            onChange={(e) => setPassword(e.target.value)}
                            className="w-full px-3 py-2 mt-1 border rounded-md focus:outline-none focus:ring focus:ring-blue-300"
                            placeholder="Digite sua senha"
                        />
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-gray-700">
                            Confirmar Senha
                        </label>
                        <input
                            type="password"
                            value={confirmPassword}
                            onChange={(e) => setConfirmPassword(e.target.value)}
                            className="w-full px-3 py-2 mt-1 border rounded-md focus:outline-none focus:ring focus:ring-blue-300"
                            placeholder="Confirme sua senha"
                        />
                    </div>

                    <button
                        type="submit"
                        className="w-full px-4 py-2 text-white bg-green-500 rounded-md hover:bg-green-600 focus:outline-none focus:ring focus:ring-green-300"
                    >
                        Registrar
                    </button>
                </form>

                <div className="text-center mt-4">
                    <span className="text-sm text-gray-700">
                        Já tem uma conta?{" "}
                        <a
                            href="/login"
                            className="text-blue-500 hover:underline"
                        >
                            Faça login
                        </a>
                    </span>
                </div>
            </div>
        </div>
    );
}

export default Register;
