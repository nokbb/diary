const path = require("path");

module.exports = {
    mode: "development", // または 'production' または 'none'
    entry: "./src/index.js", // エントリーポイントとなるファイル
    output: {
        filename: "bundle.js", // バンドルされたファイル名
        path: path.resolve(__dirname, "dist"), // 出力先のディレクトリ
    },

    stats: {
        children: true,
    },
    devtool: "source-map",
};
