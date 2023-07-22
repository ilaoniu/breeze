const boxShadow = require("./resources/js/i-ui/tailwind/tailwindcss.box-shadow.cjs");
const extendTheme = require("./resources/js/i-ui/tailwind/tailwindcss.config.extend.cjs");

module.exports = {
    content: ["./resources/views/**/*.blade.php", "./resources/js/**/*.vue"],
    plugins: [boxShadow],
    theme: {
        extend: extendTheme
    }
};
