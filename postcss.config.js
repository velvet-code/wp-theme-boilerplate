module.exports = ({ env }) => ({
  plugins: [
    require("postcss-import"),
    require("tailwindcss"),
    require("postcss-nested"),
    require("autoprefixer")
  ]
});
