import "./bootstrap";
import { Notyf } from "notyf";

// A global instance of Notyf
window.notyf = new Notyf({
    duration: 4000,
    position: {
        x: "right",
        y: "top",
    },
    types: [
        {
            type: "success",
            background: "#ec4899",
            dismissible: true,
        },
        {
            type: "error",
            background: "#ef4444",
            dismissible: true,
        },
    ],
});
