import { defineConfig } from 'vitepress'

// https://vitepress.dev/reference/site-config
export default defineConfig({
    base: '/docs/',
    outDir: '../public/docs',
    title: "VL. WBK docs",
    description: "Onze bijhorende documentatie voor de ontwikkeling en gebruik van het Vl woordenboek ",
    themeConfig: {
        // https://vitepress.dev/reference/default-theme-config
        sidebar: [
            {
                text: 'Artikelen',
                collapsed: true,
                items: [
                    { text: 'Notities', link: '/dictionary-articles/notes' },
                    { text: 'Labels', link: '/dictionary-articles/labels'},
                    { text: 'Versiebeheer', link: '/dictionary-articles/versiebeheer' }
                ]
            },
            {
                text: 'Overig',
                collapsed: true,
                items: [
                    { text: 'Glossarium', link: '/other/glossarium' },
                ]
            }
        ],

        socialLinks: [
            { icon: 'github', link: 'https://github.com/vuejs/vitepress' },
        ]
    }
})
