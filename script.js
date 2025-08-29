let publicVapidKey = "";

let navegadorId = localStorage.getItem('navegadorId');
if (!navegadorId) {
    navegadorId = crypto.randomUUID();
    localStorage.setItem('navegadorId', navegadorId);
}

async function fetchVapidKey() {
    const res = await fetch('get_vapid.php');
    const data = await res.json();
    return data.publicKey;
}

if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register("sw.js")
        .then(async reg => {
            console.log("Service Worker registrado:", reg);
            await navigator.serviceWorker.ready;

            publicVapidKey = await fetchVapidKey();
            initPush();
        })
        .catch(err => console.error("Error SW:", err));
}

Notification.requestPermission().then(permission => {
    if (permission !== "granted") {
        alert("Permiso de notificaciones denegado");
    }
});

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    return Uint8Array.from([...rawData].map(c => c.charCodeAt(0)));
}

async function initPush() {
    try {
        const reg = await navigator.serviceWorker.ready;

        const sub = await reg.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: urlBase64ToUint8Array(publicVapidKey)
        });

        const subData = { ...sub.toJSON(), navegadorId };

        await fetch('guardar_subscription.php', {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(subData)
        });

        console.log("Suscripción guardada:", subData);

    } catch (err) {
        console.error("Error al suscribirse:", err);
    }
}

document.getElementById("notificationBtn").addEventListener("click", async () => {
    try {
        const res = await fetch(`SendPush.php?navegadorId=${encodeURIComponent(navegadorId)}`);
        const text = await res.text();
        console.log("Respuesta de PHP:", text);
    } catch (err) {
        console.error("Error al llamar a SendPush.php:", err);
    }
});
