const js = require("@eslint/js");
const importPlugin = require("eslint-plugin-import");
const globals = require("globals");

module.exports = [
  // Base JS recommended config
  js.configs.recommended,

  // Main configuration with import plugin
  {
    files: ["**/*.{js,mjs,cjs}"],
    plugins: {
      import: importPlugin,
    },
    languageOptions: {
      globals: {
        ...globals.browser,
        ...globals.node,
      },
    },
    rules: {
      "no-unused-vars": [
        "warn",
        {
          argsIgnorePattern: "^_",
          varsIgnorePattern: "^_",
          caughtErrorsIgnorePattern: "^_",
        },
      ],

      "import/no-unresolved": [
        "error",
        {
          commonjs: true, // Penting untuk CommonJS
          caseSensitive: true,
        },
      ],

      "import/named": "error",
      "import/namespace": "error",
      "import/default": "error",

      // Untuk CJS, aturan ini harus dimatikan
      "import/no-commonjs": "off",
    },
    settings: {
      "import/resolver": {
        node: {
          extensions: [".js", ".jsx", ".mjs", ".cjs"],
        },
      },
    },
  },

  // CommonJS specific config (for .js files)
  {
    files: ["**/*.js"],
    languageOptions: {
      sourceType: "commonjs",
    },
  },
];
