const defaultTheme = require("tailwindcss/defaultTheme");

/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: "class",
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
        "./resources/js/**/*.js",
        "./node_modules/flowbite/**/*.js",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Instrument Sans", ...defaultTheme.fontFamily.sans],
                display: ["Fraunces", ...defaultTheme.fontFamily.serif],
            },
            colors: {
                pine: {
                    50: "#eef6f3",
                    100: "#d5e9e1",
                    200: "#abd3c4",
                    300: "#79b6a0",
                    400: "#4d957e",
                    500: "#327a64",
                    600: "#256250",
                    700: "#1f4f42",
                    800: "#1b4037",
                    900: "#17352e",
                    950: "#0b1e1a",
                },
                clay: {
                    50: "#fbf6ee",
                    100: "#f5e8d3",
                    200: "#ead0a6",
                    300: "#ddb171",
                    400: "#d19448",
                    500: "#c47b32",
                    600: "#a86128",
                    700: "#8b4a24",
                    800: "#723c24",
                    900: "#5e3320",
                    950: "#341a0f",
                },
            },
            boxShadow: {
                float: "0 12px 32px -12px rgb(28 25 23 / 0.25), 0 2px 6px -2px rgb(28 25 23 / 0.08)",
            },
        },
    },

    plugins: [require("@tailwindcss/forms"), require("flowbite/plugin")],
};
