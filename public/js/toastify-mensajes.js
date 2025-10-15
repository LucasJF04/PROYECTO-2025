// public/js/toastify-mensajes.js
document.addEventListener('DOMContentLoaded', function() {
    const successMessage = document.querySelector('meta[name="toast-success"]')?.content;
    const errorMessage   = document.querySelector('meta[name="toast-error"]')?.content;

    if(successMessage) {
        Toastify({
            text: successMessage,
            duration: 2000,
            gravity: "top",
            position: "center",
            backgroundColor: "#4CAF50",
            close: true
        }).showToast();
    }

    if(errorMessage) {
        Toastify({
            text: errorMessage,
            duration: 2000,
            gravity: "top",
            position: "center",
            backgroundColor: "#f44336",
            close: true
        }).showToast();
    }
});
