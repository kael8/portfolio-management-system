module.exports = {
    content: [
        "./resources/views/**/*.blade.php", // Add all your Blade views
        "./resources/js/**/*.vue", // Add your Vue files
        "./resources/sass/**/*.scss", // If using SCSS
        "./resources/css/**/*.css", // If using plain CSS
    ],
    theme: {
        extend: {
            colors: {
                primary: "#145DA0",
                secondary: "#0C2D48",
                accent: "#2E8BC0",
                highlight: "#B1D4E0",
            },
        },
    },
    plugins: [],
};
