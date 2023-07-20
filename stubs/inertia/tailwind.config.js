import boxShadow from "./resources/js/i-ui/tailwind/tailwindcss.box-shadow";
import theme from "./resources/js/i-ui/tailwind/tailwindcss.config.extend";

export default {
    content: ["./resources/views/**/*.blade.php", "./resources/js/**/*.vue"],
    plugins: [boxShadow],
    theme: {
        extend: theme
    }
};
