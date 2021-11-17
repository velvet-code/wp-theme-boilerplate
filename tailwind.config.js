module.exports = {
  mode: "jit",
  purge: ["./**/*.html", "./**/*.php", "./src/**/*.css", "./src/**/*.js"],
  theme: {
    container: {
      center: true,
    },
    extend: {
      colors: {
        black: "#000",
        white: "#fff",
      },
    },
  },
  variants: {},
  plugins: [],
};
