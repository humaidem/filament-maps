const esbuild = require('esbuild')
const shouldWatch = process.argv.includes('--watch')
const fs = require('fs');

let copyDevFilesPlugin = {
    name: 'copyDevFilesPlugin',
    setup(build) {
        build.onEnd(result => {
            if (shouldWatch) {
                fs.readdir('dist/humaidem/filament-maps', (err, files) => {
                    files.forEach(file => {
                        if (file.endsWith('.js')) {
                            fs.copyFile(
                                `dist/humaidem/filament-maps/${file}`,
                                `../../../public/js/humaidem/filament-maps/components/${file}`,
                                (err) => {
                                    if (err) throw err;
                                    console.log(`${file} was copied to destination`);
                                });
                        }
                        if (file.endsWith('.css')) {
                            fs.copyFile(
                                `dist/humaidem/filament-maps/${file}`,
                                `../../../public/css/humaidem/filament-maps/${file}`,
                                (err) => {
                                    if (err) throw err;
                                    console.log(`${file} was copied to destination`);
                                });
                        }
                    });
                });
            }
        })
    },
}
const formComponents = [
    'filament-maps-field',
]
formComponents.forEach((component) => {
    esbuild
        .build({
            loader: {
                '.png': 'dataurl',
                '.svg': 'dataurl',
            },
            define: {
                'process.env.NODE_ENV': shouldWatch
                    ? `'production'`
                    : `'development'`,
            },
            entryPoints: [
                `resources/js/${component}.js`,
            ],
            outfile: `dist/humaidem/filament-maps/${component}.js`,
            bundle: true,
            platform: 'neutral',
            mainFields: ['module', 'main'],
            watch: shouldWatch,
            minifySyntax: true,
            minifyWhitespace: true,
            plugins: [
                copyDevFilesPlugin
            ]
        })
        .catch(() => process.exit(1))
})
