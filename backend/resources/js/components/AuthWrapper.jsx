import { useEffect, useState } from "react";
import { router } from "@inertiajs/react";

function AuthWrapper({ children }) {
    const [loading, setLoading] = useState(true);
    const token = localStorage.getItem("token");

    if (!token) {
        router.push({ url: "/login", component: "Login" });
        return;
    }

    fetch("/api/v1/me", {
        headers: {
            Authorization: `Bearer ${token}`,
            Accept: "application/json",
        },
    })
        .then((res) => {
            if (!res.ok) throw new Error("Token inválido");
            return res.json();
        })
        .then(() => setLoading(false))
        .catch(() => {
            localStorage.removeItem("token");
            router.visit("/login");
        });

    if (loading) {
        return <div>Verificando sessão...</div>;
    }

    return children;
}

export default AuthWrapper;
