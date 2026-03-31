import { defineConfig } from 'vite';

export default defineConfig({
    resolve: {
        alias: {
            '@': './resources/js',
            '@modules': './resources/js/modules',
            '@pages': './resources/js/pages',
        },
        extensions: ['.js', '.ts', '.vue', '.json'],
    },
});
